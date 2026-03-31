<?php
include "main_top.php";
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


    <!-- 신상품 섹션 -->
<div class="row mt-5 mb-1" style="text-align:center;">
<div class="glitch-wrapper">
   <div class="glitch" data-text="신상품">신상품</div>
</div>
</div>

    <div class="row">
        <?php
        $sql = "SELECT * FROM product WHERE icon_new = 1 ORDER BY RAND() LIMIT 12";
        $result = mysqli_query($db, $sql);
        while ($row = mysqli_fetch_array($result)) {
            $id = $row['id'];
            $name = $row['name'];
            $image = $row['image1'] ?: 'nopic.png';
            $price = number_format($row['price']);
            $discount = $row['discount'];
            $sale_price = number_format($row['price'] * (100 - $discount) / 100);
        ?>
        <div class="col-sm-3 mb-3">
            <div class="card h-100">
                <div class="shine-wrapper" align="center">
                    <a href="product.php?id=<?=$id?>">
                        <img src="product/<?=$image?>" class="card-img-top img-fluid" height="360">
                    </a>
                </div>
<div class="card-body text-center" style="font-size:15px; background-color:#3f4347;">
  <h5 class="card-title mb-2">
    <a href="product.php?id=<?=$id?>" class="text-dark" style="text-decoration:none; font-size:15px;">
      <?=$name?>
    </a><br>
    <?php
      if ($row['icon_new'])  echo "<img src='images/i_new.gif'>&nbsp;";
      if ($row['icon_hit'])  echo "<img src='images/i_hit.gif'>&nbsp;";
      if ($row['icon_sale']) echo "<img src='images/i_sale.gif'>&nbsp;<font size='2' color='red'>{$discount}%</font>";
    ?>
  </h5>
  <p class="card-text text-white">
    <?php if ($row['icon_sale']): ?>
      <small><strike><?=$price?> 원</strike></small>&nbsp;&nbsp;<?=$sale_price?> 원
    <?php else: ?>
      <b><?=$price?> 원</b>
    <?php endif; ?>
  </p>
</div>
            </div>
        </div>
        <?php } ?>
    </div>
    <?php include "main_bottom.php"; ?>
</div>
</body>
</html>
