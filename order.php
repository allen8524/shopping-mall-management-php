<?php
// order.php - 주문(배송정보) 페이지 동적 구현

include "main_top.php";  // common.php 포함, $db 사용 가능

// 1. JSON 직렬화된 쿠키에서 장바구니 읽어오기
$cart   = isset($_COOKIE['cart'])   ? json_decode($_COOKIE['cart'], true) : array();
$n_cart = isset($_COOKIE['n_cart']) ? (int)$_COOKIE['n_cart']     : 0;


// 2. 옵션명 매핑 (DB에서 불러온 ID → 이름 연관 배열)
$opt1_names = [];  // 색상(opt_id=1)
$res1 = mysqli_query($db, "SELECT id, name FROM opts WHERE opt_id=1");
while($r = mysqli_fetch_assoc($res1)) {
    // $r['id'] = opts 테이블의 PK
    $opt1_names[$r['id']] = $r['name'];
}

$opt2_names = [];  // 사이즈(opt_id=2)
$res2 = mysqli_query($db, "SELECT id, name FROM opts WHERE opt_id=2");
while($r = mysqli_fetch_assoc($res2)) {
    $opt2_names[$r['id']] = $r['name'];
}

$opt3_names = [];  // 길이(opt_id=3)
$res3 = mysqli_query($db, "SELECT id, name FROM opts WHERE opt_id=3");
while($r = mysqli_fetch_assoc($res3)) {
    $opt3_names[$r['id']] = $r['name'];
}


// 3. 배송비 및 총액 초기화
$shipping = 2500;
$total    = 0;

// 4. 로그인된 회원 정보 디폴트 세팅
$o_name = $o_tel1 = $o_tel2 = $o_tel3 = $o_email = $o_zip = $o_juso = '';
if (isset($_COOKIE['cookie_id']) && $_COOKIE['cookie_id'] !== '') {
    $user_id = mysqli_real_escape_string($db, $_COOKIE['cookie_id']);
    $m_sql   = "SELECT name, tel, email, zip, juso FROM member WHERE id = '$user_id' LIMIT 1";
    $m_res   = mysqli_query($db, $m_sql);
    if ($m = mysqli_fetch_assoc($m_res)) {
        $o_name  = $m['name'];
        $tel = preg_replace('/\D/', '', $m['tel']);
        $o_tel1 = substr($tel, 0, 3);
        $o_tel2 = substr($tel, 3, 4);
        $o_tel3 = substr($tel, 7);
        $o_email = $m['email'];
        $o_zip   = $m['zip'];
        $o_juso  = $m['juso'];
    }
}
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
</head>
<body>

<div class="container">

<script>
    function Check_Value() {
        if (!form2.o_name.value) { alert("주문자 이름이 잘못 되었습니다."); form2.o_name.focus(); return; }
        if (!form2.o_tel1.value || !form2.o_tel2.value || !form2.o_tel3.value) { alert("핸드폰이 잘못 되었습니다."); form2.o_tel1.focus(); return; }
        if (!form2.o_email.value) { alert("이메일이 잘못 되었습니다."); form2.o_email.focus(); return; }
        if (!form2.o_zip.value) { alert("우편번호가 잘못 되었습니다."); form2.o_zip.focus(); return; }
        if (!form2.o_juso.value) { alert("주소가 잘못 되었습니다."); form2.o_juso.focus(); return; }
        if (!form2.r_name.value) { alert("받으실 분의 이름이 잘못 되었습니다."); form2.r_name.focus(); return; }
        if (!form2.r_tel1.value || !form2.r_tel2.value || !form2.r_tel3.value) { alert("핸드폰이 잘못 되었습니다."); form2.r_tel1.focus(); return; }
        if (!form2.r_email.value) { alert("이메일이 잘못 되었습니다."); form2.r_email.focus(); return; }
        if (!form2.r_zip.value) { alert("우편번호가 잘못 되었습니다."); form2.r_zip.focus(); return; }
        if (!form2.r_juso.value) { alert("주소가 잘못 되었습니다."); form2.r_juso.focus(); return; }
        form2.submit();
    }
    function FindZip(zip_kind) {
        window.open("zipcode.php?zip_kind="+zip_kind, "", "scrollbars=no,width=490,height=320");
    }
    function SameCopy(str) {
        if (str === "Y") {
            form2.r_name.value  = form2.o_name.value;
            form2.r_tel1.value  = form2.o_tel1.value;
            form2.r_tel2.value  = form2.o_tel2.value;
            form2.r_tel3.value  = form2.o_tel3.value;
            form2.r_email.value = form2.o_email.value;
            form2.r_zip.value   = form2.o_zip.value;
            form2.r_juso.value  = form2.o_juso.value;
        } else {
            form2.r_name.value = form2.r_tel1.value = form2.r_tel2.value = form2.r_tel3.value = '';
            form2.r_email.value = form2.r_zip.value = form2.r_juso.value = '';
        }
    }
</script>

<!-- 장바구니 상품 목록 -->
<div class="row m-1 mb-0">
    <div class="col text-center">
        <h4 class="m-3">주문(배송정보)</h4>
        <hr class="m-0">
        <table class="table table-sm mb-5">
            <tr height="40" class="bg-light">
                <td width="15%">이미지</td>
                <td width="35%">상품정보</td>
                <td width="15%">판매가</td>
                <td width="20%">수량</td>
                <td width="15%">금액</td>
            </tr>
            <?php for ($i=1; $i<=$n_cart; $i++):
                if (empty($cart[$i])) continue;
                list($id, $num, $opts1, $opts2, $opts3) = explode('^', $cart[$i]);
                $res = mysqli_query($db, "SELECT * FROM product WHERE id=$id");
                $row = mysqli_fetch_array($res);
                $unit_price = intval($row['price'] * (100 - $row['discount']) / 100);
                $line_total = $unit_price * $num;
                $total += $line_total;
            ?>
            <tr height="85" style="font-size:14px;">
                <td>
                    <a href="product.php?id=<?= $id ?>">
                        <img src="product/<?= htmlspecialchars($row['image1'] ?: 'nopic.png') ?>"
                             width="60" height="70">
                    </a>
                </td>
                <td class="align-middle text-start">
                    <a href="product.php?id=<?= $id ?>" style="color:#0066CC">
                        <?= htmlspecialchars($row['name']) ?>
                    </a><br>
					<small><b>[옵션]</b>
						<?= htmlspecialchars($opt1_names[(int)$opts1] ?? '') ?>&nbsp;
						<?= htmlspecialchars($opt2_names[(int)$opts2] ?? '') ?>&nbsp;
						<?= htmlspecialchars($opt3_names[(int)($opts3 ?? 0)] ?? '') ?>
					</small>
                </td>
                <td class="align-middle"><?= number_format($unit_price) ?></td>
                <td class="align-middle"><?= $num ?></td>
                <td class="align-middle"><?= number_format($line_total) ?></td>
            </tr>
            <?php endfor;
            if ($total > 100000) $shipping = 0;
            ?>
            <tr height="40" align="right" class="bg-light" style="font-size:14px;">
                <td align="center"><img src="images/cart_image1.gif"></td>
                <td colspan="4" class="text-end pe-4">
                    <span style="color:#0066CC;">총금액</span> :
                    상품( <?= number_format($total) ?> ) +
                    배송비( <?= number_format($shipping) ?> ) =
                    <strong style="font-size:16px">
                        <?= number_format($total + $shipping) ?>
                    </strong>
                </td>
            </tr>
        </table>
    </div>
</div>

<!-- 주문정보 + 배송정보 폼 -->
<form name="form2" method="post" action="order_pay.php">
    <div class="row mx-1 my-3">
        <div class="col" align="center">
            <font size="4" color="#B90319">주문정보</font><hr class="m-0 my-2">
            <table style="font-size:13px;">
                <tr height="40"><td align="left" width="20%">이름 <font color="red">*</font></td><td align="left"><div class="d-inline-flex"><input type="text" name="o_name" size="20" value="<?=htmlspecialchars($o_name)?>" class="form-control form-control-sm"></div></td></tr>
                <tr height="40"><td align="left">휴대폰 <font color="red">*</font></td><td align="left"><div class="d-inline-flex"><input type="text" name="o_tel1" size="3" maxlength="3" value="<?=htmlspecialchars($o_tel1?:'010')?>" class="form-control form-control-sm">-<input type="text" name="o_tel2" size="4" maxlength="4" value="<?=htmlspecialchars($o_tel2)?>" class="form-control form-control-sm">-<input type="text" name="o_tel3" size="4" maxlength="4" value="<?=htmlspecialchars($o_tel3)?>" class="form-control form-control-sm"></div></td></tr>
                <tr height="40"><td align="left">이메일 <font color="red">*</font></td><td align="left"><div class="d-inline-flex"><input type="text" name="o_email" size="50" value="<?=htmlspecialchars($o_email)?>" class="form-control form-control-sm"></div></td></tr>
                <tr height="80"><td align="left">주소 <font color="red">*</font></td><td align="left"><div class="d-inline-flex mb-1"><input type="text" name="o_zip" size="5" maxlength="5" value="<?=htmlspecialchars($o_zip)?>" class="form-control form-control-sm">&nbsp;</div><a href="javascript:FindZip(1)" class="btn btn-sm btn-secondary text-white mb-1" style="font-size:12px">우편번호 찾기</a><br><div class="d-inline-flex"><input type="text" name="o_juso" size="50" value="<?=htmlspecialchars($o_juso)?>" class="form-control form-control-sm"></div></td></tr>
            </table>
        </div>
    </div>

    <div class="row mx-1 my-3">
        <div class="col" align="center">
            <font size="4" color="#B90319">배송정보</font><hr class="m-0 my-2">
            <table style="font-size:13px;">
                <tr height="40"><td align="left" width="20%">위 복사</td><td align="left"><div class="d-inline-flex"><div class="form-check"><input class="form-check-input" type="radio" name="same" onclick="SameCopy('Y')"><label class="form-check-label me-2">예</label></div><div class="form-check"><input class="form-check-input" type="radio" name="same" onclick="SameCopy('N')"><label class="form-check-label">아니오</label></div></div></td></tr>
                <tr height="40"><td align="left">이름 <font color="red">*</font></td><td align="left"><div class="d-inline-flex"><input type="text" name="r_name" size="20" value="" class="form-control form-control-sm"></div></td></tr>
                <tr height="40"><td align="left">휴대폰 <font color="red">*</font></td><td align="left"><div class="d-inline-flex"><input type="text" name="r_tel1" size="3" maxlength="3" value="" class="form-control form-control-sm">-<input type="text" name="r_tel2" size="4" maxlength="4" value="" class="form-control form-control-sm">-<input type="text" name="r_tel3" size="4" maxlength="4" value="" class="form-control form-control-sm"></div></td></tr>
                <tr height="40"><td align="left">이메일 <font color="red">*</font></td><td align="left"><div class="d-inline-flex"><input type="text" name="r_email" size="50" value="" class="form-control form-control-sm"></div></td></tr>
                <tr height="80"><td align="left">주소 <font color="red">*</font></td><td align="left"><div class="d-inline-flex mb-1"><input type="text" name="r_zip" size="5" maxlength="5" value="" class="form-control form-control-sm">&nbsp;</div><a href="javascript:FindZip(2)" class="btn btn-sm btn-secondary text-white mb-1" style="font-size:12px">우편번호 찾기</a><br><div class="d-inline-flex"><input type="text" name="r_juso" size="50" value="" class="form-control form-control-sm"></div></td></tr>
                <tr height="90"><td align="left">요구사항</td><td align="left"><div class="d-inline-flex"><textarea name="memo" cols="50" rows="3" class="form-control form-control-sm"></textarea></div></td></tr>
            </table>
        </div>
    </div>

    <div class="row mx-5">
        <div class="col" align="center">
            <a href="javascript:Check_Value()" class="btn btn-sm btn-dark text-white">다음</a>
        </div>
    </div>
</form>

<br><br><br>
<?php include "main_bottom.php"; ?>
</div>
</body>
</html>
