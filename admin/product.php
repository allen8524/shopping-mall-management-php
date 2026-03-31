<?php
include "login_main_check.php";
include "../common.php";

$sel1 = $_REQUEST["sel1"] ?? 0;
$sel2 = $_REQUEST["sel2"] ?? 0;
$sel3 = $_REQUEST["sel3"] ?? 0;
$sel4 = $_REQUEST["sel4"] ?? 1;
$text1 = $_REQUEST["text1"] ?? "";

$s = array(); $k = 0;
if ($sel1 != 0) $s[$k++] = "status=$sel1";
if ($sel2 == 1) $s[$k++] = "icon_new=1";
if ($sel2 == 2) $s[$k++] = "icon_hit=1";
if ($sel2 == 3) $s[$k++] = "icon_sale=1";
if ($sel3 != 0) $s[$k++] = "menu=$sel3";

if ($text1) {
	if ($sel4 == 1) $s[$k++] = "name like '%$text1%'";
	else            $s[$k++] = "code like '%$text1%'";
}

$condition = implode(" and ", $s);
if ($condition) $condition = "where " . $condition;

$sql = "select * from product $condition order by code";
$args = "sel1=$sel1&sel2=$sel2&sel3=$sel3&sel4=$sel4&text1=$text1";
$result = mypagination($sql, $args, $count, $pagebar);
$page = $_REQUEST["page"] ?? 1;
?>
<!doctype html>
<html lang="kr">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>4910</title>
 	<link rel="icon" href="../images/4910_top.ico" type="image/x-icon">
	<link  href="../css/bootstrap.min.css" rel="stylesheet">
	<link  href="../css/my.css" rel="stylesheet">
	<script src="../js/jquery-3.7.1.min.js"></script>
	<script src="../js/bootstrap.bundle.min.js"></script>
	<script src="../js/my.js"></script>
</head>
<body>
<div class="container">
<script> document.write(admin_menu());</script>

<script>
function search_clear() {
	form1.sel1.value = "0";
	form1.sel2.value = "0";
	form1.sel3.value = "0";
	form1.sel4.value = "1";
	form1.text1.value = "";
}
</script>

<div class="row mx-1 justify-content-center">
	<div class="col" align="center">

	<h4 class="m-0 mb-3">제품</h4>

	<form name="form1" method="post" action="product.php">
	<table class="table table-sm table-borderless m-0 p-0">
		<tr>
			<td width="20%" align="left" style="padding-top:8px">
				제품수 : <font color="red"><?=$count?></font>
			</td>
			<td align="right">
				<div class="d-inline-flex">
					<select name="sel1" class="form-select form-select-sm bg-light myfs12" style="width:100px">
<?php
for ($i = 0; $i < $n_status; $i++) {
	$sel = ($sel1 == $i) ? "selected" : "";
	echo "<option value='$i' $sel>$a_status[$i]</option>";
}
?>
					</select>&nbsp;

					<select name="sel2" class="form-select form-select-sm bg-light myfs12" style="width:120px">
<?php
for ($i = 0; $i < $n_icon; $i++) {
	$sel = ($sel2 == $i) ? "selected" : "";
	echo "<option value='$i' $sel>$a_icon[$i]</option>";
}
?>
					</select>&nbsp;
				</div>
				<div class="d-inline-flex">
					<select name="sel3" class="form-select form-select-sm bg-light myfs12" style="width:100px">
<?php
for ($i = 0; $i < $n_menu; $i++) {
	$sel = ($sel3 == $i) ? "selected" : "";
	echo "<option value='$i' $sel>$a_menu[$i]</option>";
}
?>
					</select>&nbsp;

					<select name="sel4" class="form-select form-select-sm bg-light myfs12" style="width:100px">
<?php
for ($i = 1; $i < $n_text1; $i++) {
	$sel = ($sel4 == $i) ? "selected" : "";
	echo "<option value='$i' $sel>$a_text1[$i]</option>";
}
?>
					</select>
				</div>
				<div class="d-inline-flex">
					<div class="input-group input-group-sm">
						<input type="text" name="text1" value="<?=$text1?>" size="10" class="form-control myfs12" onKeydown="if (event.keyCode == 13) { form1.submit(); }">
						<button class="btn mycolor1 myfs12" type="button" onClick="form1.submit();">검색</button>
					</div>
				</div>
				<div class="d-inline-flex">
					<a href="javascript:search_clear()" class="btn btn-sm mycolor1 myfs12">초기화</a>&nbsp;&nbsp;&nbsp;
					<a href="product_new.php" class="btn btn-sm mycolor1 myfs12">추가</a>&nbsp;
				</div>
			</td>
		</tr>
	</table>
	</form>

	<table class="table table-sm table-bordered table-hover mb-1">
		<tr class="bg-light">
			<td width="10%">제품분류</td>
			<td width="10%">제품코드</td>
			<td width="35%">제품명</td>
			<td width="10%">판매가</td>
			<td width="10%">상태</td>
			<td width="15%">이벤트</td>
			<td width="10%">수정/삭제</td>
		</tr>
<?php while ($row = mysqli_fetch_array($result)) {
	$price = number_format($row["price"]);
	$menu = $a_menu[$row["menu"]];
	$status = $a_status[$row["status"]];
	$event = "";
	if ($row["icon_new"] == 1) $event .= "New ";
	if ($row["icon_hit"] == 1) $event .= "Hit ";
	if ($row["icon_sale"] == 1) $event .= "Sale({$row["discount"]}%)";
?>
		<tr>
			<td><?=$menu?></td>
			<td><?=$row["code"]?></td>
			<td align="left"><?=$row["name"]?></td>
			<td align="right" class="px-2"><?=$price?></td>
			<td><?=$status?></td>
			<td><?=$event?></td>
			<td>
				<a href="product_edit.php?id=<?=$row["id"]?>&<?=$args?>&page=<?=$page?>" class="btn btn-sm btn-outline-info mybutton-blue">수정</a>
				<a href="product_delete.php?id=<?=$row["id"]?>&<?=$args?>&page=<?=$page?>" class="btn btn-sm btn-outline-danger mybutton-red" onclick="return confirm('삭제할까요?');">삭제</a>
			</td>
		</tr>
<?php } ?>
	</table>

	<?=$pagebar?>

	</div>
</div>
</div>
</body>
</html>
