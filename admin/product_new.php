<?php
include "login_main_check.php";
include "../common.php";
$rs_opt = mysqli_query($db, "SELECT id, name FROM opt ORDER BY id");
$a_opt = [0 => '옵션 선택'];
while ($ro = mysqli_fetch_assoc($rs_opt)) {
    $a_opt[$ro['id']] = $ro['name'];
}
?>
<!doctype html>
<html lang="kr">
<head>
	<meta charset="utf-8">
	<title>4910</title>
 	<link rel="icon" href="../images/4910_top.ico" type="image/x-icon">
	<link  href="../css/bootstrap.min.css" rel="stylesheet">
	<link  href="../css/my.css" rel="stylesheet">
	<script src="../js/jquery-3.7.1.min.js"></script>
	<script src="../js/bootstrap.bundle.min.js"></script>
	<script src="../js/my.js"></script>
	<script>
	function checkForm() {
		const code = document.form1.code.value.trim();
		const name = document.form1.name.value.trim();
		const coname = document.form1.coname.value.trim();
		const price = document.form1.price.value.trim();
		const contents = document.form1.contents.value.trim();

		if (!code) {
			alert("상품코드를 입력하세요.");
			document.form1.code.focus();
			return false;
		}
		if (!name) {
			alert("상품명을 입력하세요.");
			document.form1.name.focus();
			return false;
		}
		if (!coname) {
			alert("제조사를 입력하세요.");
			document.form1.coname.focus();
			return false;
		}
		if (!price) {
			alert("판매가를 입력하세요.");
			document.form1.price.focus();
			return false;
		}
		if (isNaN(price) || Number(price) < 0) {
			alert("판매가는 숫자로 입력하세요.");
			document.form1.price.focus();
			return false;
		}
		if (!contents) {
			alert("제품 설명을 입력하세요.");
			document.form1.contents.focus();
			return false;
		}
		return true;
	}
	</script>
</head>
<body>

<div class="container">
<script> document.write(admin_menu());</script>

<form name="form1" method="post" action="product_insert.php" enctype="multipart/form-data" onsubmit="return checkForm()">

<div class="row mx-1 justify-content-center">
	<div class="col" align="center">

	<h4 class="m-0 mb-3">제품 등록</h4>

	<table class="table table-sm table-bordered myfs12 m-0 p-0">
	<tr>
		<td class="bg-light" width="15%">상품분류</td>
		<td align="left" class="ps-2">
				<select name="menu" class="form-select form-select-sm bg-light myfs12" style="width:120px; text-align:left;">
			<?php
				for ($i = 0; $i < $n_menu; $i++) {
					$sel = ($i == 0) ? "selected" : "";  // "메뉴선택" 항목에 selected
					echo "<option value='$i' $sel>$a_menu[$i]</option>";
				}
			?>
			</select>
		</td>
	</tr>

	<tr>
		<td class="bg-light">상품코드</td>
		<td align="left" class="ps-2">
			<input type="text" name="code" value="" class="form-control form-control-sm" style="width:200px; text-align:left;">
		</td>
	</tr>

	<tr>
		<td class="bg-light">상품명</td>
		<td align="left" class="ps-2">
			<input type="text" name="name" value="" class="form-control form-control-sm" style="width:1000px; text-align:left;">
		</td>
	</tr>

	<tr>
		<td class="bg-light">제조사</td>
		<td align="left" class="ps-2">
			<input type="text" name="coname" value="" class="form-control form-control-sm" style="width:400px; text-align:left;">
		</td>
	</tr>

	<tr>
		<td class="bg-light">판매가</td>
		<td align="left" class="ps-2">
			<input type="text" name="price" value="" class="form-control form-control-sm d-inline" style="width:100px; text-align:left;"> 원
		</td>
	</tr>

    <tr>
        <td class="bg-light">옵션</td>
        <td align="left" class="ps-2">
            <div class="d-inline-flex align-items-center gap-2">
                <select name="opt1" class="form-select form-select-sm bg-light myfs12" style="width:100px; text-align:left;">
                    <?php
                    foreach ($a_opt as $key => $val) {
                        $sel = ($key === 0) ? 'selected' : '';
                        echo "<option value='$key' $sel>$val</option>";
                    }
                    ?>
                </select>
                <select name="opt2" class="form-select form-select-sm bg-light myfs12" style="width:100px; text-align:left;">
                    <?php
                    foreach ($a_opt as $key => $val) {
                        $sel = ($key === 0) ? 'selected' : '';
                        echo "<option value='$key' $sel>$val</option>";
                    }
                    ?>
                </select>
            </div>
        </td>
    </tr>

	<tr>
		<td class="bg-light">제품설명</td>
		<td align="left" class="ps-2">
			<textarea name="contents" rows="5" class="form-control form-control-sm myfs12" style="width:1000px; text-align:left;"></textarea>
		</td>
	</tr>

<tr>
    <td class="bg-light">상품상태</td>
    <td align="left" class="ps-2 pt-2">
        <?php
            for ($i = 1; $i < $n_status; $i++) {
                $checked = ($i === 1) ? 'checked' : '';
                echo "<div class='form-check form-check-inline'>
                        <input class='form-check-input' type='radio' name='status' value='$i' $checked>
                        <label class='form-check-label'>{$a_status[$i]}</label>
                      </div>";
            }
        ?>
    </td>
</tr>

	<tr>
		<td class="bg-light">아이콘</td>
		<td align="left" class="ps-2">
			<input type="checkbox" name="icon_new" value="1" checked> New &nbsp;
			<input type="checkbox" name="icon_hit" value="1"> Hit &nbsp;
			<input type="checkbox" name="icon_sale" value="1"> Sale &nbsp;
			할인율:
			<input type="text" name="discount" value="0" size="2" maxlength="3"
				class="form-control form-control-sm d-inline"
				style="width:60px; text-align:left;"> %
		</td>
	</tr>

	<tr>
		<td class="bg-light">등록일</td>
		<td align="left" class="ps-2">
			<input type="date" name="regday" value="<?=date('Y-m-d')?>"
				class="form-control form-control-sm"
				style="width:160px; display:inline-block; text-align:left;">
		</td>
	</tr>

	<tr>
		<td class="bg-light">이미지</td>
		<td align="left" class="ps-2">
			<b>이미지1 :</b> <input type="file" name="image1" class="form-control form-control-sm myfs12 mb-1" style="width:250px">
			<b>이미지2 :</b> <input type="file" name="image2" class="form-control form-control-sm myfs12 mb-1" style="width:250px">
			<b>이미지3 :</b> <input type="file" name="image3" class="form-control form-control-sm myfs12" style="width:250px">
		</td>
	</tr>
	</table>

	<!-- ✅ 표준 방식의 submit 버튼 사용 -->
	<button type="submit" class="btn btn-sm btn-dark text-white my-2">저 장</button>
	<a href="javascript:history.back();" class="btn btn-sm btn-outline-dark my-2">돌아가기</a>

	</div>
</div>
</form>
</div>
</body>
</html>
