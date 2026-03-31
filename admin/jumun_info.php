<?php
include "login_main_check.php";
include "../common.php";

$id = $_GET["id"] ?? "";
if (!$id) exit("주문번호 없음");

// 주문 기본 정보
$sql = "SELECT * FROM jumun WHERE id = '" . mysqli_real_escape_string($db, $id) . "'";
$result = mysqli_query($db, $sql);
if (!$result || mysqli_num_rows($result) == 0) exit("해당 주문 없음");
$row = mysqli_fetch_array($result);

// 주문 상태 및 결제 관련 명칭 매핑
$state_names = ["주문신청", "주문확인", "입금확인", "배송중", "주문완료", "주문취소"];
$state_text = $state_names[$row["state"]] ?? "알수없음";

$card_key      = (int)$row["card_kind"];
$card_kind_name = isset($a_card_kind[$card_key])
                  ? $a_card_kind[$card_key]
                  : "정보없음";

$bank_key      = (int)$row["bank_kind"];
$bank_kind_name = isset($bank_info[$bank_key])
                  ? $bank_info[$bank_key]
                  : "정보없음";

function format_phone($tel) {
    return preg_replace("/(\d{3})(\d{4})(\d{4})/", "$1-$2-$3", $tel);
}

// 상품 목록 + 옵션명 조인 (opts_id1, opts_id2 -> opts.id, opts.opt_id -> opt.id)
$sql2 = "
SELECT 
    j.id,
    j.jumun_id,
    j.product,
    j.num,
    j.price,
    j.prices,
    j.discount,
    j.opts_id1,
    j.opts_id2,
    j.opts_id3,
    p.name AS product_name,
    op1.name AS opt1_title,
    o1.name  AS opt1_value,
    op2.name AS opt2_title,
    o2.name  AS opt2_value,
    op3.name AS opt3_title,
    o3.name  AS opt3_value
FROM jumuns j
LEFT JOIN product p ON j.product = p.id
LEFT JOIN opts o1 ON j.opts_id1 = o1.id
LEFT JOIN opt  op1 ON o1.opt_id = op1.id
LEFT JOIN opts o2 ON j.opts_id2 = o2.id
LEFT JOIN opt  op2 ON o2.opt_id = op2.id
LEFT JOIN opts o3 ON j.opts_id3 = o3.id
LEFT JOIN opt  op3 ON o3.opt_id = op3.id
WHERE j.jumun_id = '" . mysqli_real_escape_string($db, $id) . "'
";
$result2 = mysqli_query($db, $sql2);

// 배송비 계산
$shipping    = ($row["totalprice"] >= 100000) ? 0 : 2500;
$grand_total = $row["totalprice"] + $shipping;
?>

<!doctype html>
<html lang="kr">
<head>
    <meta charset="utf-8">
	<title>4910</title>
 	<link rel="icon" href="../images/4910_top.ico" type="image/x-icon">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/my.css" rel="stylesheet">
    <script src="../js/jquery-3.7.1.min.js"></script>
    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../js/my.js"></script>
</head>
<body>

<div class="container">
<script> document.write(admin_menu()); </script>

<div class="row mx-1 justify-content-center">
<div class="col-sm-10" align="center">

<h4 class="m-0 mb-3">주문 ( <b><?=htmlspecialchars($row["id"]) ?></b> )</h4>

<!-- 주문 정보 테이블 -->
<table class="table table-sm table-bordered mb-3">
<tr>
    <td class="bg-light" width="15%">상태</td><td width="35%"><?=$state_text?></td>
    <td class="bg-light" width="15%">주문일</td><td width="35%"><?=$row["jumunday"]?></td>
</tr>
</table>

<!-- 주문자/배송지 정보 -->
<table class="table table-sm table-bordered mb-3">
    <tr>
        <td class="bg-light" width="15%"><b>주문자</b></td><td width="35%"><?=htmlspecialchars($row["o_name"]) ?></td>
        <td class="bg-light" width="15%">구분</td><td width="35%"><?=$row["member_id"] ? "회원" : "비회원"?></td>
    </tr>
    <tr>
        <td class="bg-light">전화</td><td><?=format_phone($row["o_tel"])?></td>
        <td class="bg-light">E-Mail</td><td><?=htmlspecialchars($row["o_email"]) ?></td>
    </tr>
    <tr>
        <td class="bg-light">주소</td>
        <td colspan="3" align="left">(<?=htmlspecialchars($row["o_zip"])?>) <?=htmlspecialchars($row["o_juso"])?> </td>
    </tr>
</table>

<!-- 수신자 정보 -->
<table class="table table-sm table-bordered mb-3">
    <tr>
        <td class="bg-light" width="15%"><b>수신자</b></td><td width="35%"><?=htmlspecialchars($row["r_name"]) ?></td>
        <td class="bg-light" width="15%"></td><td></td>
    </tr>
    <tr>
        <td class="bg-light">전화</td><td><?=format_phone($row["r_tel"])?></td>
        <td class="bg-light">E-Mail</td><td><?=htmlspecialchars($row["r_email"]) ?></td>
    </tr>
    <tr>
        <td class="bg-light">주소</td>
        <td colspan="3" align="left">(<?=htmlspecialchars($row["r_zip"])?>) <?=htmlspecialchars($row["r_juso"])?> </td>
    </tr>
    <tr>
        <td class="bg-light">메모</td>
<td colspan="3" align="left" style="word-break: break-word;">
    <?=nl2br(htmlspecialchars($row["memo"]))?>
</td>

    </tr>
</table>

<!-- 결제 정보 -->
<table class="table table-sm table-bordered mb-3">
    <?php if ($row["pay_kind"] == 0): // 카드 결제 ?>
        <tr>
            <td class="bg-light" width="15%"><b>카드</b></td>
            <td width="35%"><?= htmlspecialchars($card_kind_name) ?></td>
            <td class="bg-light" width="15%">승인</td>
            <td width="35%"><?= htmlspecialchars($row["card_okno"] ?: "-") ?></td>
        </tr>
        <tr>
            <td class="bg-light">할부</td>
            <td><?= $row["card_halbu"]
                    ? htmlspecialchars($row["card_halbu"]) . " 개월"
                    : "일시불" ?></td>
            <td class="bg-light"></td><td></td>
        </tr>
    <?php elseif ($row["pay_kind"] == 1): // 무통장 ?>
        <tr>
            <td class="bg-light"><b>무통장</b></td>
            <td><?= htmlspecialchars($bank_kind_name) ?></td>
            <td class="bg-light">입금자</td>
            <td><?= htmlspecialchars($row["card_sender"]) ?></td>
        </tr>
    <?php endif; ?>
</table>





<!-- 상품 목록 및 옵션 -->
<table class="table table-sm table-bordered mb-3">
<tr class="bg-light">
    <td>제품명</td>
    <td width="10%">수량</td>
    <td width="10%">단가</td>
    <td width="10%">금액</td>
    <td width="10%">할인</td>
    <td width="20%">옵션</td>
</tr>
<?php while ($item = mysqli_fetch_array($result2)): ?>
<tr>
    <td align="left"><?=htmlspecialchars($item["product_name"] ?? "상품정보 없음")?></td>
    <td><?=$item["num"]?></td>
    <td align="right"><?=number_format($item["price"])?></td>
    <td align="right"><?=number_format($item["prices"])?></td>
    <td><?=$item["discount"] ? $item["discount"] . "%" : ""?></td>
<td>
    <?php
    $opt1 = $item["opt1_title"] && $item["opt1_value"] ? "{$item["opt1_title"]} : {$item["opt1_value"]}" : "";
    $opt2 = $item["opt2_title"] && $item["opt2_value"] ? "{$item["opt2_title"]} : {$item["opt2_value"]}" : "";
    $opt3 = $item["opt3_title"] && $item["opt3_value"] ? "{$item["opt3_title"]} : {$item["opt3_value"]}" : "";
    echo htmlspecialchars(implode(" / ", array_filter([$opt1, $opt2, $opt3])));
    ?>
</td>

</tr>
<?php endwhile; ?>
<?php if ($shipping > 0): ?>
<tr>
    <td align="left">택배비(100000원 이하 구매)</td>
    <td>1</td>
    <td align="right"><?=number_format($shipping)?></td>
    <td align="right"><?=number_format($shipping)?></td>
    <td></td><td></td>
</tr>
<?php endif; ?>
</table>

<!-- 총금액 -->
<table class="table table-sm table-bordered mb-3 p-2">
<tr>
    <td class="bg-light" width="15%">총금액</td>
    <td width="85%" align="right" style="font-size:18px"><?=number_format($grand_total)?> 원</td>
</tr>
</table>

<a href="javascript:print();" class="btn btn-sm btn-dark text-white my-2">&nbsp;프린트&nbsp;</a>
<a href="javascript:history.back();" class="btn btn-sm btn-outline-dark my-2">&nbsp;돌아가기&nbsp;</a>

</div>
</div>
</div>
</body>
</html>
