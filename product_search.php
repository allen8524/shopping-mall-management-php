<?php
include "main_top.php";

$find_text = $_POST['find_text'] ?? ($_GET['find_text'] ?? '');
$text = trim($find_text);
$text_esc = mysqli_real_escape_string($db, $text);
$page = max(1, (int)($_REQUEST['page'] ?? 1));
$args = http_build_query(["find_text" => $text]);

$where = $text !== '' ? "where name like '%$text_esc%'" : "";
$sql = "select * from product $where order by name";
$result = mypagination($sql, $args, $count, $pagebar);
?>

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
					<a href="product.php?id=<?=(int)$row['id']?>"><img src="product/<?=htmlspecialchars($row['image1'] ?: 'nopic.png', ENT_QUOTES, 'UTF-8')?>" width="60" height="70"></a>
				</td>
				<td align="left" valign="middle">
					<a href="product.php?id=<?=(int)$row['id']?>" style="color:#0066CC"><?=htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8')?></a><br>
					<?php
					if ($row['icon_new']) echo "<img src='images/i_new.gif'> ";
					if ($row['icon_hit']) echo "<img src='images/i_hit.gif'> ";
					if ($row['icon_sale']) echo "<img src='images/i_sale.gif'> <font size='2' color='red'>" . (int)$row['discount'] . "%</font>";
					?>
				</td>
				<td>
					<?php if ($row['icon_sale']): ?>
						<strike><?=number_format((int)$row['price'])?> 원</strike>
					<?php else: ?>
						<?=number_format((int)$row['price'])?> 원
					<?php endif; ?>
				</td>
				<td><b><?=number_format((int)round((int)$row['price'] * (100 - (int)$row['discount']) / 100))?> 원</b></td>
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
