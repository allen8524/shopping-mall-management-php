<?php
include "main_top.php";

$sort = (int)($_GET['sort'] ?? 1);
$menu = (int)($_GET['menu'] ?? 0);
$page = max(1, (int)($_GET['page'] ?? 1));
if ($sort < 1 || $sort > 5) $sort = 1;
if ($menu < 0 || $menu >= $n_menu) $menu = 0;

$order = "order by id desc";
switch ($sort) {
	case 2: $order = "order by icon_hit desc"; break;
	case 3: $order = "order by name"; break;
	case 4: $order = "order by price"; break;
	case 5: $order = "order by price desc"; break;
}

$condition = ($menu > 0) ? "where menu = " . (int)$menu : "";
$sql = "select * from product $condition $order";
$args = http_build_query(["sort" => $sort, "menu" => $menu]);
$result = mypagination($sql, $args, $total, $pagebar);
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


<!--  Category 제목 -->
<div class="row mt-5 mb-1"
     style="color: #bdfe01; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7);">
	<div class="col" align="center">
		<h2><?= $menu > 0 ? $a_menu[$menu] : "전체 상품" ?></h2>
	</div>
</div>

<!--  상품개수 & 정렬 -->
<div class="row m-0">
	<div class="col-2" align="left" style="font-size:15px">
		Total <?=$total?> items!
	</div>
	<div class="col" align="right" style="font-size:12px">
		<a href="menu.php?menu=<?=$menu?>&sort=1"><font color='<?=($sort==1 ? "steelblue" : "black")?>'>신상품</font></a>&nbsp;|
		<a href="menu.php?menu=<?=$menu?>&sort=2"><font color='<?=($sort==2 ? "steelblue" : "black")?>'>인기상품</font></a>&nbsp;|
		<a href="menu.php?menu=<?=$menu?>&sort=3"><font color='<?=($sort==3 ? "steelblue" : "black")?>'>상품명</font></a>&nbsp;|
		<a href="menu.php?menu=<?=$menu?>&sort=4"><font color='<?=($sort==4 ? "steelblue" : "black")?>'>낮은가격</font></a>&nbsp;|
		<a href="menu.php?menu=<?=$menu?>&sort=5"><font color='<?=($sort==5 ? "steelblue" : "black")?>'>높은가격</font></a>
	</div>
</div>
<hr class="mt-0 mb-4">

<!--  상품 진열  -->
<div class="row">
<?php while ($row = mysqli_fetch_array($result)): ?>
	<div class="col-sm-3 mb-3">
		<div class="card h-100">
			<div class="shine-wrapper" align="center">
				<a href="product.php?id=<?=$row['id']?>">
					<img src="product/<?=($row['image1'] ?: 'nopic.png')?>" height="360" class="card-img-top img-fluid">
				</a>
			</div>
			<div class="card-body text-center" style="font-size:15px; background-color:#3f4347;">
				<div class="card-title">
					<a href="product.php?id=<?=$row['id']?>"> <?=$row['name']?> </a><br>
					<?php
					if ($row['icon_new']) echo "<img src='images/i_new.gif'> ";
					if ($row['icon_hit']) echo "<img src='images/i_hit.gif'> ";
					if ($row['icon_sale']) echo "<img src='images/i_sale.gif'> <font size='2' color='red'>{$row['discount']}%</font>";
					?>
				</div>
				<p class="card-text text-white">
					<?php if ($row['icon_sale']): ?>
						<small><strike><?=number_format($row['price'])?> 원</strike></small>&nbsp;&nbsp;
						<?=number_format($row['price'] * (100 - $row['discount']) / 100)?> 원
					<?php else: ?>
						<?=number_format($row['price'])?> 원
					<?php endif; ?>
				</p>
			</div>
		</div>
	</div>
<?php endwhile; ?>
</div>

<!--  Pagination -->
<div class="row mb-4">
	<div class="col">
		<?=$pagebar?>
	</div>
</div>

<?php
include "main_bottom.php";
?>
</div>
</body>
</html>
