<?php
// 에러 표시
error_reporting(E_ALL);
ini_set('display_errors', 1);
$cookie_id = $_COOKIE['cookie_id'] ?? '';

include "main_top.php";  // $db 및 common.php 포함

// 1) 주문번호 확인
$id = $_GET['id'] ?? '';
if (!$id) exit('주문번호 없음');

// 2) 주문 정보 조회
$sql = "SELECT * FROM jumun WHERE id = '" . mysqli_real_escape_string($db, $id) . "'";
$res = mysqli_query($db, $sql);
if (!$res || mysqli_num_rows($res) === 0) exit('해당 주문 없음');
$order = mysqli_fetch_assoc($res);

// 3) 권한 확인
$is_allowed = false;
if (!empty($_COOKIE['cookie_id']) && $order['member_id'] === $_COOKIE['cookie_id']) {
    $is_allowed = true;
} elseif (!empty($_COOKIE['guest_name']) && !empty($_COOKIE['guest_email']) &&
          $order['o_name'] === $_COOKIE['guest_name'] && $order['o_email'] === $_COOKIE['guest_email']) {
    $is_allowed = true;
}
if (!$is_allowed) {
    echo "<script>alert('접근 권한이 없습니다.'); history.back();</script>";
    exit;
}

// 4) 주문 항목 조회
$item_sql = 
  "SELECT j.*, p.name AS product_name, p.image1 AS pimg, " .
  "o1.name AS opt1_name, o2.name AS opt2_name, o3.name AS opt3_name " .
  "FROM jumuns j " .
  "LEFT JOIN product p ON j.product = p.id " .
  "LEFT JOIN opts o1 ON j.opts_id1 = o1.id " .
  "LEFT JOIN opts o2 ON j.opts_id2 = o2.id " .
  "LEFT JOIN opts o3 ON j.opts_id3 = o3.id " .
  "WHERE j.jumun_id = '" . mysqli_real_escape_string($db, $order['id']) . "'";
$item_res = mysqli_query($db, $item_sql);

// 5) common.php 배열 사용
$card_key = (int)$order['card_kind'];
$card_kind_name = $a_card_kind[$card_key] ?? '정보없음';
$bank_key = (int)$order['bank_kind'];
$bank_kind_name = $bank_info[$bank_key] ?? '정보없음';
$card_halbu_text = $order['card_halbu'] === '0' ? '일시불' : $order['card_halbu'] . '개월';

// 6) 전화번호 포맷 함수
function format_phone($tel) {
    return preg_replace('/(\d{2,3})(\d{3,4})(\d{4})/', '$1-$2-$3', $tel);
}
// 7) 배송비 및 총액 계산
$shipping = ($order['totalprice'] >= 100000) ? 0 : 2500;
$grand_total = $order['totalprice'] + $shipping;

?>
<!doctype html>
<html lang="kr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/my.css" rel="stylesheet">
    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
	<style>
    td {
        font-size: 16px;
    }
</style>

</head>
<body>
<div class="container">

    <!-- 주문상품내역 -->
    <div class="row m-1 mt-4 mb-0">
        <div class="col text-center">
            <h4 class="m-3">주문상품내역</h4>
            <hr class="m-0">
            <table class="table table-sm mb-4">
                <thead>
                    <tr class="bg-light" height="30">
                        <td width="15%">이미지</td>
                        <td width="35%">상품정보</td>
                        <td width="15%">판매가</td>
                        <td width="20%">수량</td>
                        <td width="15%">금액</td>
                    </tr>
                </thead>
                <tbody>
                <?php while ($item = mysqli_fetch_assoc($item_res)): ?>
                    <tr height="85" style="font-size:14px;">
                        <td>
                            <?php if ($item['product']): ?>
                                <a href="product.php?id=<?= $item['product'] ?>">
                                    <img src="product/<?= htmlspecialchars($item['pimg'], ENT_QUOTES) ?>" width="60" height="70">
                                </a>
                            <?php else: ?>
                                <img src="product/nopic.png" width="60" height="70">
                            <?php endif; ?>
                        </td>
                        <td align="left" valign="middle">
                            <?php if ($item['product']): ?>
                                <a href="product.php?id=<?= $item['product'] ?>" style="color:#0066CC">
                                    <?= htmlspecialchars($item['product_name'], ENT_QUOTES) ?>
                                </a><br>
                                <small><b>[옵션]</b> <?= htmlspecialchars($item['opt1_name'], ENT_QUOTES) ?> <?= htmlspecialchars($item['opt2_name'], ENT_QUOTES) ?> <?= htmlspecialchars($item['opt3_name'], ENT_QUOTES) ?></small>
                            <?php else: ?>
                                <span style="color:#0066CC">택배비</span>
                            <?php endif; ?>
                        </td>
                        <td><?= number_format($item['price']) ?> 원</td>
                        <td><?= $item['num'] ?></td>
                        <td><?= number_format($item['prices']) ?> 원</td>
						<?php if ($shipping > 0): ?>
<tr height="60" style="font-size:14px;">
    <td>
        <img src="images/delivery.png" width="60" height="70" alt="">
    </td>
    <td align="left" valign="middle">
        <span style="color:#0066CC">배송비</span><br>
        <small><b>[비고]</b> 10만원 미만 주문</small>
    </td>
    <td>2,500 원</td>
    <td>1</td>
    <td>2,500 원</td>
</tr>
<?php endif; ?>
                </tr>
                <?php endwhile; ?>
                    <tr class="bg-light" height="30" align="right" style="font-size:14px;">
                        <td colspan="5" class="pe-2">
                            <span style="color:#0066CC">결제금액</span> : 
                            <span style="font-size:16px"><?= number_format($grand_total) ?> 원</span>
                        </td>
                    </tr>

					
                </tbody>
            </table>
        </div>
    </div>

    <!-- 결제내역 -->
    <div class="row m-1">
        <div class="col text-center">
            <h4 class="m-0 text-danger">결제내역</h4>
            <hr class="m-2">
            <table class="table table-sm table-borderless">
                <tr height="30">
    <td width="20%"><b>주문번호 :</b></td>
    <td width="30%" style="text-align:left;"><?= htmlspecialchars($order['id'], ENT_QUOTES) ?></td>
    <td width="20%"><b>결제금액 :</b></td>
    <td width="30%" style="text-align:left;">
        <?= number_format($grand_total) ?> 원
        <?php if ($shipping > 0): ?>
            <small class="text-muted">(배송비 포함)</small>
        <?php endif; ?>
    </td>
</tr>

                <tr height="30">
                    <td><b>결제방식 :</b></td>
                    <td style="text-align:left;"><?= $order['pay_kind'] === '0' ? '카드' : '무통장' ?></td>
                    <td><b>승인번호 :</b></td>
                    <td style="text-align:left;"><?= htmlspecialchars($order['card_okno'], ENT_QUOTES) ?></td>
                </tr>
                <tr height="30">
                    <td><b>카드종류 :</b></td>
                    <td style="text-align:left;"><?= htmlspecialchars($card_kind_name, ENT_QUOTES) ?></td>
                    <td><b>할부 :</b></td>
                    <td style="text-align:left;"><?= htmlspecialchars($card_halbu_text, ENT_QUOTES) ?></td>
                </tr>
                <tr height="30">
                    <td><b>무통장 :</b></td>
                    <td style="text-align:left;"><?= htmlspecialchars($bank_kind_name, ENT_QUOTES) ?></td>
                    <td><b>입금자 :</b></td>
                    <td style="text-align:left;"><?= htmlspecialchars($order['card_sender'], ENT_QUOTES) ?></td>
                </tr>
            </table>
        </div>
    </div>

    <!-- 주문자 -->
    <div class="row m-1">
        <div class="col text-center">
            <h4 class="m-0 text-danger">주문자</h4>
            <hr class="m-2">
            <table class="table table-sm table-borderless">
                <tr height="30">
                    <td width="20%"><b>주문자 :</b></td>
                    <td width="30%" style="text-align:left;"><?= htmlspecialchars($order['o_name'], ENT_QUOTES) ?></td>
                    <td width="20%"><b>핸드폰 :</b></td>
                    <td width="30%" style="text-align:left;"><?= format_phone($order['o_tel']) ?></td>
                </tr>
                <tr height="30">
                    <td><b>이메일 :</b></td>
                    <td colspan="3" style="text-align:left;"><?= htmlspecialchars($order['o_email'], ENT_QUOTES) ?></td>
                </tr>
            </table>
        </div>
    </div>

    <!-- 배송내역 -->
    <div class="row m-1">
        <div class="col text-center">
            <h4 class="m-0 text-danger">배송내역</h4>
            <hr class="m-2">
            <table class="table table-sm table-borderless">
                <tr height="30">
                    <td width="20%"><b>수취인 :</b></td>
                    <td width="30%" style="text-align:left;"><?= htmlspecialchars($order['r_name'], ENT_QUOTES) ?></td>
                    <td width="20%"><b>핸드폰 :</b></td>
                    <td width="30%" style="text-align:left;"><?= format_phone($order['r_tel']) ?></td>
                </tr>
                <tr height="30">
                    <td><b>주소 :</b></td>
                    <td colspan="3" style="text-align:left;">[<?= htmlspecialchars($order['r_zip'], ENT_QUOTES) ?>] <?= htmlspecialchars($order['r_juso'], ENT_QUOTES) ?></td>
                </tr>
                <tr height="30">
                    <td><b>메모 :</b></td>
                    <td colspan="3" style="text-align:left;"><?= nl2br(htmlspecialchars($order['memo'], ENT_QUOTES)) ?></td>
                </tr>
            </table>
        </div>
    </div>

    <!-- 돌아가기 버튼 -->
    <div class="row"><div class="col text-center">
        <a href="javascript:history.back();" class="btn btn-sm btn-dark text-white">돌아가기</a>
    </div></div>
    <br><br>

</div>
</body>
</html>