<?php
// order_pay.php - 결제정보 확인 및 결제 처리 페이지

include "main_top.php";   // common.php 포함, $db 사용 가능

// 1. JSON 직렬화된 쿠키에서 장바구니 읽어오기
$cart   = isset($_COOKIE['cart'])   ? json_decode($_COOKIE['cart'], true) : [];
$n_cart = isset($_COOKIE['n_cart']) ? (int)$_COOKIE['n_cart']     : 0;

// 2. opts 테이블 전체를 불러와서 opt_id별로 매핑
$opt_names = [];
$res_opts = mysqli_query($db, "SELECT opt_id, id, name FROM opts ORDER BY opt_id, id");
while ($r = mysqli_fetch_assoc($res_opts)) {
    $opt_names[$r['opt_id']][$r['id']] = $r['name'];
}

// 3. 배송비 및 총액 초기화
$shipping = 2500;
$total    = 0;

// 4. 주문(배송정보)에서 넘어온 값 (hidden input 으로 전달됨)
$o_name   = $_POST['o_name']   ?? '';
$o_tel1   = $_POST['o_tel1']   ?? '';
$o_tel2   = $_POST['o_tel2']   ?? '';
$o_tel3   = $_POST['o_tel3']   ?? '';
$o_email  = $_POST['o_email']  ?? '';
$o_zip    = $_POST['o_zip']    ?? '';
$o_juso   = $_POST['o_juso']   ?? '';
$r_name   = $_POST['r_name']   ?? '';
$r_tel1   = $_POST['r_tel1']   ?? '';
$r_tel2   = $_POST['r_tel2']   ?? '';
$r_tel3   = $_POST['r_tel3']   ?? '';
$r_email  = $_POST['r_email']  ?? '';
$r_zip    = $_POST['r_zip']    ?? '';
$r_juso   = $_POST['r_juso']   ?? '';
$memo     = $_POST['memo']     ?? '';
// order_pay.php 상단
$o_name   = $_POST['o_name']   ?? '';
$o_tel1   = $_POST['o_tel1']   ?? '';
$o_tel2   = $_POST['o_tel2']   ?? '';
$o_tel3   = $_POST['o_tel3']   ?? '';
$o_tel    = $o_tel1 . $o_tel2 . $o_tel3;   // <-- 여기를 추가
// … 이하 배송지도 동일
$r_name   = $_POST['r_name']   ?? '';
$r_tel1   = $_POST['r_tel1']   ?? '';
$r_tel2   = $_POST['r_tel2']   ?? '';
$r_tel3   = $_POST['r_tel3']   ?? '';
$r_tel    = $r_tel1 . $r_tel2 . $r_tel3;   // <-- 추가
// 이후 order_ok.php 로 넘길 때에도 이 $o_tel, $r_tel 을 hidden 으로 넘기세요.

?>
<!doctype html>
<html lang="kr">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link  href="css/bootstrap.min.css" rel="stylesheet">
	<link  href="css/my.css" rel="stylesheet">
	<script src="js/jquery-3.7.1.min.js"></script>
	<script src="js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="container">
<!-------------------------------------------------------------------------------------------->	
<script>
	function Check_Value() 
	{
		if (form2.pay_kind[0].checked)
		{
			if (form2.card_kind.value==0) { alert("카드종류를 선택하세요."); form2.card_kind.focus(); return; }
			if (!form2.card_no1.value)  { alert("카드번호를 입력하세요."); form2.card_no1.focus();  return; }
			if (!form2.card_no2.value)  { alert("카드번호를 입력하세요."); form2.card_no2.focus();  return; }
			if (!form2.card_no3.value)  { alert("카드번호를 입력하세요."); form2.card_no3.focus();  return; }
			if (!form2.card_no4.value)  { alert("카드번호를 입력하세요."); form2.card_no4.focus();  return; }
			if (!form2.card_month.value){ alert("카드기간 월을 입력하세요."); form2.card_month.focus();return; }
			if (!form2.card_year.value) { alert("카드기간 년도를 입력하세요."); form2.card_year.focus(); return; }
			if (!form2.card_pw.value)   { alert("카드 비밀번호 뒷의 2자리를 입력하세요."); form2.card_pw.focus(); return; }
		}
		else
		{
			if (form2.bank_kind.value==0)    { alert("입금할 은행을 선택하세요."); form2.bank_kind.focus();    return; }
			if (!form2.card_sender.value)    { alert("입금자 이름을 입력하세요."); form2.card_sender.focus();   return; }
		}
		form2.card_kind.disabled = false;
		form2.card_no1.disabled  = false;
		form2.card_no2.disabled  = false;
		form2.card_no3.disabled  = false;
		form2.card_no4.disabled  = false;
		form2.card_year.disabled = false;
		form2.card_month.disabled= false;
		form2.card_pw.disabled   = false;
		form2.card_halbu.disabled= false;
		form2.bank_kind.disabled = false;
		form2.card_sender.disabled= false;
		form2.submit();
	}

	function PaySel(n) 
	{
		if (n == 0) {
			form2.card_kind.disabled = false;
			form2.card_no1.disabled  = false;
			form2.card_no2.disabled  = false;
			form2.card_no3.disabled  = false;
			form2.card_no4.disabled  = false;
			form2.card_year.disabled = false;
			form2.card_month.disabled= false;
			form2.card_halbu.disabled= false;
			form2.card_pw.disabled   = false;
			form2.bank_kind.disabled = true;
			form2.card_sender.disabled= true;
		} else {
			form2.card_kind.disabled = true;
			form2.card_no1.disabled  = true;
			form2.card_no2.disabled  = true;
			form2.card_no3.disabled  = true;
			form2.card_no4.disabled  = true;
			form2.card_year.disabled = true;
			form2.card_month.disabled= true;
			form2.card_halbu.disabled= true;
			form2.card_pw.disabled   = true;
			form2.bank_kind.disabled = false;
			form2.card_sender.disabled= false;
		}
	}
</script>

    <!-- 장바구니 상품 목록 -->
    <div class="row m-1 mb-0">
        <div class="col text-center">
            <h4 class="m-3">결제정보 확인</h4>
            <hr class="m-0">
            <table class="table table-sm mb-5">
                <tr height="40" class="bg-light">
                    <td width="15%">이미지</td>
                    <td width="35%">상품정보</td>
                    <td width="15%">판매가</td>
                    <td width="20%">수량</td>
                    <td width="15%">금액</td>
                </tr>
                <?php for ($i = 1; $i <= $n_cart; $i++): 
                    if (empty($cart[$i])) continue;
                    // 1) explode 로 parts 정의
                    $parts   = explode('^', $cart[$i]);
                    $prod_id = (int)$parts[0];
                    $num     = (int)$parts[1];

                    // 2) 상품 정보 조회
                    $res_p = mysqli_query($db, "SELECT * FROM product WHERE id = {$prod_id}");
                    $row_p = mysqli_fetch_array($res_p);
                    $unit  = intval($row_p['price'] * (100 - $row_p['discount']) / 100);
                    $line  = $unit * $num;
                    $total += $line;
                ?>
                <tr height="85" style="font-size:14px;">
                    <td>
                        <a href="product.php?id=<?= $prod_id ?>">
                            <img src="product/<?= htmlspecialchars($row_p['image1'] ?: 'nopic.png') ?>"
                                 width="60" height="70">
                        </a>
                    </td>
                    <td class="align-middle text-start">
                        <a href="product.php?id=<?= $prod_id ?>" style="color:#0066CC">
                            <?= htmlspecialchars($row_p['name']) ?>
                        </a><br>
                        <small><b>[옵션]</b>
                            <?php
                            // 3) parts[2] 이상은 opts.id 값, opt_id = index-1
                            for ($j = 2; $j < count($parts); $j++) {
                                $opt_id   = $j - 1;              // opt_id 그룹
                                $opt_val  = (int)$parts[$j];    // opts.id
                                // 매핑된 이름 출력
                                echo htmlspecialchars($opt_names[$opt_id][$opt_val] ?? '') . ' ';
                            }
                            ?>
                        </small>
                    </td>
                    <td class="align-middle"><?= number_format($unit) ?></td>
                    <td class="align-middle"><?= $num ?></td>
                    <td class="align-middle"><?= number_format($line) ?></td>
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
                        <strong style="font-size:16px"><?= number_format($total + $shipping) ?></strong>
                    </td>
                </tr>
            </table>
        </div>
    </div>


<!-- form2 시작 -->
<form name="form2" method="post" action="order_ok.php">
	<!-- 주문자 정보 -->
	<input type="hidden" name="o_name"   value="<?=htmlspecialchars($o_name)?>">
	<input type="hidden" name="o_tel1"   value="<?=htmlspecialchars(substr($o_tel, 0, 3))?>">
	<input type="hidden" name="o_tel2"   value="<?=htmlspecialchars(substr($o_tel, 3, 4))?>">
	<input type="hidden" name="o_tel3"   value="<?=htmlspecialchars(substr($o_tel, 7))?>">
	<input type="hidden" name="o_email"  value="<?=htmlspecialchars($o_email)?>">
	<input type="hidden" name="o_zip"    value="<?=htmlspecialchars($o_zip)?>">
	<input type="hidden" name="o_juso"   value="<?=htmlspecialchars($o_juso)?>">

	<!-- 수령자 정보 -->
	<input type="hidden" name="r_name"   value="<?=htmlspecialchars($r_name)?>">
	<input type="hidden" name="r_tel1"   value="<?=htmlspecialchars(substr($r_tel, 0, 3))?>">
	<input type="hidden" name="r_tel2"   value="<?=htmlspecialchars(substr($r_tel, 3, 4))?>">
	<input type="hidden" name="r_tel3"   value="<?=htmlspecialchars(substr($r_tel, 7))?>">
	<input type="hidden" name="r_email"  value="<?=htmlspecialchars($r_email)?>">
	<input type="hidden" name="r_zip"    value="<?=htmlspecialchars($r_zip)?>">
	<input type="hidden" name="r_juso"   value="<?=htmlspecialchars($r_juso)?>">
	<input type="hidden" name="memo"     value="<?=htmlspecialchars($memo)?>">

	
	

	<div class="row mx-1 my-0">
		<div class="col" align="center">
			<font size="4" color="#B90319">결제방법</font>
			<hr class="m-0 my-2">
			<table width="90%" style="font-size:13px;">
				<tr height="40">
					<td width="40%" align="right" class="pe-4">결제선택</td>
					<td align="left">
						<div class="d-inline-flex mt-2">
							<div class="form-check me-2">
								<input class="form-check-input" type="radio" name="pay_kind" value="0" onclick="PaySel(0);" checked>
								<label class="form-check-label me-2">카드</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="pay_kind" value="1" onclick="PaySel(1);">
								<label class="form-check-label">무통장</label>
							</div>
						</div>
					</td>
				</tr>
			</table>
			<br><br>

			<font size="4" color="#B90319">카드</font>
			<hr class="m-0 my-2">
			<table width="90%" style="font-size:13px;">
<tr height="40">
    <td width="40%" align="right" class="pe-4">카드종류</td>
    <td align="left">
        <select name="card_kind" class="form-select form-select-sm myfs13">
<?php for ($i = 0; $i < $n_card_kind; $i++): ?>
    <option value="<?= $i ?>" <?= $i === 0 ? 'selected' : '' ?>>
        <?= $i === 0 ? '카드종류를 선택하세요.' : htmlspecialchars($a_card_kind[$i]) ?>
    </option>
<?php endfor; ?>

        </select>
    </td>
</tr>

				<tr height="40">
					<td align="right" class="pe-4">카드번호</td>
					<td align="left">
						<div class="d-inline-flex">
							<input type="text" name="card_no1" size="4" maxlength="4" class="form-control form-control-sm">&nbsp;
							<input type="text" name="card_no2" size="4" maxlength="4" class="form-control form-control-sm">&nbsp;
							<input type="text" name="card_no3" size="4" maxlength="4" class="form-control form-control-sm">&nbsp;
							<input type="text" name="card_no4" size="4" maxlength="4" class="form-control form-control-sm">
						</div>
					</td>
				</tr>
				<tr height="40">
					<td align="right" class="pe-4">카드기간</td>
					<td align="left">
						<div class="d-inline-flex">
							<input type="text" name="card_month" size="2" maxlength="2" class="form-control form-control-sm">
							<div class="ms-1 mt-2">월</div>&nbsp;&nbsp;
							<input type="text" name="card_year" size="2" maxlength="2" class="form-control form-control-sm">
							<div class="ms-1 mt-2">년</div>
						</div>
					</td>
				</tr>
				<tr height="40">
					<td align="right" class="pe-4">카드비밀번호</td>
					<td align="left">
						<div class="d-inline-flex">
							<div class="mt-2 fs-6">**</div>&nbsp;
							<input type="password" name="card_pw" size="2" maxlength="2" class="form-control form-control-sm">
						</div>
					</td>
				</tr>
				<tr height="40">
					<td align="right" class="pe-4">할부</td>
					<td align="left">
						<select name="card_halbu" class="form-select form-select-sm myfs13">
							<option value="0" selected>일시불</option>
							<option value="1">1 개월</option>
							<option value="3">3 개월</option>
							<option value="6">6 개월</option>
							<option value="9">9 개월</option>
							<option value="12">12 개월</option>
							<option value="18">18 개월</option>
							<option value="24">24 개월</option>
						</select>
					</td>
				</tr>
			</table>
			<br><br>

		<font size="4" color="#B90319">무통장</font>
		<hr class="m-0 my-2">
		<table width="90%" style="font-size:13px;">
			<tr height="40">
				<td width="40%" align="right" class="pe-4">은행선택</td>
				<td align="left">
					<select name="bank_kind" class="form-select form-select-sm myfs13" disabled>
						<option value="0" selected>입금할 은행을 선택하세요.</option>
						<?php foreach ($bank_info as $bk => $info): ?>
							<option value="<?= $bk ?>"><?= htmlspecialchars($info) ?></option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
			<tr height="40">
				<td align="right" class="pe-4">입금자이름</td>
				<td align="left">
					<input type="text" name="card_sender" size="20" class="form-control form-control-sm" disabled>
				</td>
			</tr>
		</table>

		</div>
	</div>
	<br>
	<div class="row">
		<div class="col" align="center">
			<a href="javascript:Check_Value()" class="btn btn-sm btn-dark text-white">결제하기</a>
		</div>
	</div>
</form>

<br><br><br>

<?php include "main_bottom.php"; ?>
</div>
</body>
</html>
