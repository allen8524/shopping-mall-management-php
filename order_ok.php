<?php
include "common.php";
error_reporting(E_ALL);
ini_set("display_errors", 1);

function clean_post($key) {
    return trim($_POST[$key] ?? '');
}

function read_cart_items() {
    $decoded = isset($_COOKIE['cart']) ? json_decode($_COOKIE['cart'], true) : [];
    if (!is_array($decoded)) {
        return [];
    }

    $items = [];
    foreach ($decoded as $item) {
        if (!is_string($item)) {
            continue;
        }
        $parts = explode('^', $item . '^^^^');
        $pid = (int)$parts[0];
        $qty = (int)$parts[1];
        $opt1 = (int)$parts[2];
        $opt2 = (int)$parts[3];
        $opt3 = (int)$parts[4];

        if ($pid <= 0 || $qty < 1) {
            continue;
        }

        $items[] = [
            "product" => $pid,
            "qty" => $qty,
            "opt1" => max(0, $opt1),
            "opt2" => max(0, $opt2),
            "opt3" => max(0, $opt3),
        ];
    }

    return $items;
}

$cart_items = read_cart_items();
if (empty($cart_items)) {
    header("Location: cart.php");
    exit;
}

// 주문자 정보
$o_name  = clean_post('o_name');
$o_tel   = preg_replace('/\D/', '', clean_post('o_tel1') . clean_post('o_tel2') . clean_post('o_tel3'));
$o_email = clean_post('o_email');
$o_zip   = clean_post('o_zip');
$o_juso  = clean_post('o_juso');

// 수령자 정보
$r_name  = clean_post('r_name');
$r_tel   = preg_replace('/\D/', '', clean_post('r_tel1') . clean_post('r_tel2') . clean_post('r_tel3'));
$r_email = clean_post('r_email');
$r_zip   = clean_post('r_zip');
$r_juso  = clean_post('r_juso');
$memo    = clean_post('memo');

// 결제 정보
$pay_kind    = (int)($_POST['pay_kind'] ?? 0);
$card_kind   = (int)($_POST['card_kind'] ?? 0);
$card_okno   = preg_replace("/[^0-9]/", "", clean_post('card_no1') . clean_post('card_no2') . clean_post('card_no3') . clean_post('card_no4'));
$card_halbu  = (int)($_POST['card_halbu'] ?? 0);
$bank_kind   = (int)($_POST['bank_kind'] ?? 0);
$card_sender = clean_post('card_sender');

// 로그인 회원
$member_id = preg_replace('/\D/', '', $_COOKIE['cookie_id'] ?? '0');
if ($member_id === '') $member_id = '0';

$order_id = '';
$error_message = '';

try {
    mysqli_begin_transaction($db);

    // 주문번호 생성: 기존 yymmdd + 4자리 순번 형식 유지
    $prefix = date('ymd');
    $like_prefix = $prefix . '%';
    $stmt = mysqli_prepare($db, "SELECT MAX(id) AS max_id FROM jumun WHERE id LIKE ?");
    mysqli_stmt_bind_param($stmt, "s", $like_prefix);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    $next_seq = (!empty($row['max_id'])) ? (int)substr($row['max_id'], 6) + 1 : 1;
    $order_id = $prefix . str_pad($next_seq, 4, '0', STR_PAD_LEFT);

    $total_price = 0;
    $product_names = [];
    $product_nums = 0;
    $validated_items = [];

    $product_stmt = mysqli_prepare($db, "SELECT name, price, discount FROM product WHERE id = ? LIMIT 1");
    foreach ($cart_items as $item) {
        $item_product = $item['product'];
        mysqli_stmt_bind_param($product_stmt, "i", $item_product);
        mysqli_stmt_execute($product_stmt);
        $product_res = mysqli_stmt_get_result($product_stmt);
        $product = mysqli_fetch_assoc($product_res);

        if (!$product) {
            throw new Exception("존재하지 않는 상품이 장바구니에 포함되어 있습니다.");
        }

        $unit_price = (int)$product['price'];
        $discount = (int)$product['discount'];
        $final_price = (int)round($unit_price * (100 - $discount) / 100);
        $line_total = $final_price * $item['qty'];

        $validated_items[] = $item + [
            "name" => $product['name'],
            "price" => $final_price,
            "discount" => $discount,
            "prices" => $line_total,
        ];

        $total_price += $line_total;
        $product_nums += $item['qty'];
        $product_names[] = $product['name'];
    }

    if (empty($validated_items)) {
        throw new Exception("저장할 주문 상품이 없습니다.");
    }

    $product_names_str = implode(", ", $product_names);

    // jumun 테이블 저장
    $jumun_sql = "
        INSERT INTO jumun (
            id, member_id, jumunday, product_names, product_nums,
            o_name, o_tel, o_email, o_zip, o_juso,
            r_name, r_tel, r_email, r_zip, r_juso,
            memo, pay_kind, card_okno, card_halbu, card_kind,
            bank_kind, card_sender, totalprice, state
        ) VALUES (
            ?, ?, CURDATE(), ?, ?,
            ?, ?, ?, ?, ?,
            ?, ?, ?, ?, ?,
            ?, ?, ?, ?, ?,
            ?, ?, ?, 0
        )";
    $jumun_stmt = mysqli_prepare($db, $jumun_sql);
    mysqli_stmt_bind_param(
        $jumun_stmt,
        "sssisssssssssssisiiisi",
        $order_id,
        $member_id,
        $product_names_str,
        $product_nums,
        $o_name,
        $o_tel,
        $o_email,
        $o_zip,
        $o_juso,
        $r_name,
        $r_tel,
        $r_email,
        $r_zip,
        $r_juso,
        $memo,
        $pay_kind,
        $card_okno,
        $card_halbu,
        $card_kind,
        $bank_kind,
        $card_sender,
        $total_price
    );
    if (!mysqli_stmt_execute($jumun_stmt)) {
        throw new Exception("주문 마스터 저장 실패");
    }

    // jumuns 테이블 저장
    $jumuns_stmt = mysqli_prepare($db, "
        INSERT INTO jumuns (
            jumun_id, product, num, price, prices, discount, opts_id1, opts_id2, opts_id3
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    foreach ($validated_items as $item) {
        $detail_product = $item['product'];
        $detail_qty = $item['qty'];
        $detail_price = $item['price'];
        $detail_prices = $item['prices'];
        $detail_discount = $item['discount'];
        $detail_opt1 = $item['opt1'];
        $detail_opt2 = $item['opt2'];
        $detail_opt3 = $item['opt3'];
        mysqli_stmt_bind_param(
            $jumuns_stmt,
            "siiiiiiii",
            $order_id,
            $detail_product,
            $detail_qty,
            $detail_price,
            $detail_prices,
            $detail_discount,
            $detail_opt1,
            $detail_opt2,
            $detail_opt3
        );
        if (!mysqli_stmt_execute($jumuns_stmt)) {
            throw new Exception("주문 상세 저장 실패");
        }
    }

    mysqli_commit($db);

    // 성공 후에만 장바구니 쿠키 삭제
    setcookie("cart", "", time() - 3600, "/");
    setcookie("n_cart", "", time() - 3600, "/");
} catch (Throwable $e) {
    mysqli_rollback($db);
    error_log($e->getMessage());
    $error_message = "주문 저장 중 오류가 발생했습니다. 장바구니를 확인한 뒤 다시 시도해 주세요.";
}

include "main_top.php";
?>

    <div class="row m-5 mb-0">
        <div class="col text-center">
            <h4><?= $error_message ? '주문 오류' : '주문 완료' ?></h4>
        </div>
    </div>
    <hr class="m-0 mx-5">
    <div class="row m-3">
        <div class="col text-center">
            <br><br><br>
            <?php if ($error_message): ?>
                <h3><b>Sorry!</b></h3>
                <p><?= htmlspecialchars($error_message) ?></p>
                <br><br>
                <a href="cart.php" class="btn btn-sm btn-dark text-white">장바구니로</a>
            <?php else: ?>
                <h3><b>Thank You!</b></h3>
                <p>주문번호: <strong>#<?=htmlspecialchars($order_id)?></strong></p>
                <p>주문이 정상적으로 저장되었습니다.</p>
                <p>빠른 배송이 되도록 하겠습니다.</p>
                <br><br>
                <a href="index.html" class="btn btn-sm btn-dark text-white">메인으로</a>
            <?php endif; ?>
        </div>
    </div>
    <br><br><br>
    <?php include "main_bottom.php"; ?>
