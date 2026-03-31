<?php
include "main_top.php";

$find_text = $_POST['find_text'] ?? '';
$text = trim($find_text);
$page = $_REQUEST['page'] ?? 1;
$args = "find_text=$text";

$where = $text ? "where name like '%$text%'" : "";
$sql = "select * from product $where order by name";
$result = mypagination($sql, $args, $count, $pagebar);
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


<div class="row m-1 mt-4 mb-0">
	<div class="col" align="center">

		<h4 class="m-3">상품검색</h4>

		<hr class="m-0">
		<table class="table table-sm mb-4">
			<tr height="40" class="bg-light">
				<td width="15%">이미지</td>
				<td width="45%">상품정보</td>
				<td width="20%">판매가</td>
				<td width="20%">금액</td>
			</tr>
<?php while ($row = mysqli_fetch_array($result)): ?>
			<tr height="85" style="font-size:14px;">
				<td>
					<a href="product.php?id=<?=$row['id']?>"><img src="product/<?=($row['image1'] ?: 'nopic.png')?>" width="60" height="70"></a>
				</td>
				<td align="left" valign="middle">
					<a href="product.php?id=<?=$row['id']?>" style="color:#0066CC"><?=$row['name']?></a><br>
					<?php
					if ($row['icon_new']) echo "<img src='images/i_new.gif'> ";
					if ($row['icon_hit']) echo "<img src='images/i_hit.gif'> ";
					if ($row['icon_sale']) echo "<img src='images/i_sale.gif'> <font size='2' color='red'>{$row['discount']}%</font>";
					?>
				</td>
				<td>
					<?php if ($row['icon_sale']): ?>
						<strike><?=number_format($row['price'])?> 원</strike>
					<?php else: ?>
						<?=number_format($row['price'])?> 원
					<?php endif; ?>
				</td>
				<td><b><?=number_format($row['price'] * (100 - $row['discount']) / 100)?> 원</b></td>
			</tr>
<?php endwhile; ?>
		</table>
	</div>
</div>

<div class="row mb-4">
	<div class="col">
		<?=$pagebar?>
	</div>
</div>

<br><br><br>
<?php include "main_bottom.php"; ?>
</div>

</body>
</html>