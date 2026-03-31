<?php

setcookie("cart", "", time() - 3600, "/");
setcookie("n_cart", "", time() - 3600, "/");

include "main_top.php";
error_reporting(E_ALL);
ini_set("display_errors", 1);

// POST 값 필터링 함수
function val($key) {
    global $db;
    return mysqli_real_escape_string($db, $_POST[$key] ?? '');
}

// 주문자 정보
$o_name  = val('o_name');
$o_tel   = preg_replace('/\D/', '', val('o_tel1') . val('o_tel2') . val('o_tel3'));
$o_email = val('o_email');
$o_zip   = val('o_zip');
$o_juso  = val('o_juso');

// 수령자 정보
$r_name  = val('r_name');
$r_tel   = preg_replace('/\D/', '', val('r_tel1') . val('r_tel2') . val('r_tel3'));
$r_email = val('r_email');
$r_zip   = val('r_zip');
$r_juso  = val('r_juso');
$memo    = val('memo');

// 결제 정보
$pay_kind    = (int)$_POST['pay_kind'];
$card_kind   = (int)$_POST['card_kind'];
$card_okno   = preg_replace("/[^0-9]/", "", val('card_no1') . val('card_no2') . val('card_no3') . val('card_no4'));
$card_halbu  = (int)$_POST['card_halbu'];
$bank_kind   = (int)$_POST['bank_kind'];
$card_sender = val('card_sender');

// 로그인 회원
$member_id = $_COOKIE['cookie_id'] ?? 0;

// 장바구니 읽기
$cart = isset($_COOKIE['cart']) ? json_decode($_COOKIE['cart'], true) : [];
$total_price = 0;
$product_names = [];
$product_nums = 0;
$shipping = 2500;

// ✅ 주문번호 먼저 생성
$prefix = date('ymd');
$res = mysqli_query($db, "SELECT MAX(id) AS max_id FROM jumun WHERE id LIKE '{$prefix}%'");
$row = mysqli_fetch_assoc($res);
$next_seq = ($row['max_id']) ? (int)substr($row['max_id'], 6) + 1 : 1;
$order_id = $prefix . str_pad($next_seq, 4, '0', STR_PAD_LEFT);

// ✅ jumuns 테이블 저장
foreach ($cart as $item) {
    list($pid, $qty, $opt1, $opt2, $opt3) = explode('^', $item);
    $res = mysqli_query($db, "SELECT name, price, discount FROM product WHERE id=$pid");
    if (!$res) continue;
    $row = mysqli_fetch_assoc($res);
    $unit_price = intval($row['price']);
    $discount = intval($row['discount']);
    $final_price = intval($unit_price * (100 - $discount) / 100);
    $prices = $final_price * $qty;

    // 총합계 계산용
    $total_price += $prices;
    $product_nums += $qty;
    $product_names[] = $row['name'];

    // 개별 상품 저장
	$sql2 = "
		INSERT INTO jumuns (
			jumun_id, product, num, price, prices, discount, opts_id1, opts_id2, opts_id3
		) VALUES (
			'$order_id', $pid, $qty, $final_price, $prices, $discount, $opt1, $opt2, $opt3
		)";

    mysqli_query($db, $sql2) or die("jumuns 저장 실패: " . mysqli_error($db));
}

// 배송비 조건 적용
if ($total_price >= 100000) $shipping = 0;
$product_names_str = implode(", ", $product_names);

// ✅ jumun 테이블 저장
$sql = "
INSERT INTO jumun (
    id, member_id, jumunday, product_names, product_nums,
    o_name, o_tel, o_email, o_zip, o_juso,
    r_name, r_tel, r_email, r_zip, r_juso,
    memo, pay_kind, card_okno, card_halbu, card_kind,
    bank_kind, card_sender, totalprice, state
) VALUES (
    '$order_id', '$member_id', CURDATE(), '$product_names_str', $product_nums,
    '$o_name', '$o_tel', '$o_email', '$o_zip', '$o_juso',
    '$r_name', '$r_tel', '$r_email', '$r_zip', '$r_juso',
    '$memo', $pay_kind, '$card_okno', $card_halbu, $card_kind,
    $bank_kind, '$card_sender', $total_price, 0
)";
mysqli_query($db, $sql) or die("주문 저장 실패: " . mysqli_error($db));


?>

<!doctype html>
<html lang="kr">
<head>
    <meta charset="utf-8">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/my.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="row m-5 mb-0">
        <div class="col text-center">
            <h4>주문 완료</h4>
        </div>
    </div>
    <hr class="m-0 mx-5">
    <div class="row m-3">
        <div class="col text-center">
            <br><br><br>
            <h3><b>Thank You!</b></h3>
            <p>주문번호: <strong>#<?=$order_id?></strong></p>
            <p>주문이 정상적으로 저장되었습니다.</p>
            <p>빠른 배송이 되도록 하겠습니다.</p>
            <br><br>
            <a href="index.html" class="btn btn-sm btn-dark text-white">메인으로</a>
        </div>
    </div>
    <br><br><br>
    <?php include "main_bottom.php"; ?>
</div>
</body>
</html>
