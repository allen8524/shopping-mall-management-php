<?php
include "login_main_check.php";
include "../common.php";

$id       = $_REQUEST["id"];
$menu     = $_REQUEST["menu"];
$code     = $_REQUEST["code"];
$name     = $_REQUEST["name"];
$coname   = $_REQUEST["coname"];
$price    = $_REQUEST["price"];
$opt1     = $_REQUEST["opt1"];
$opt2     = $_REQUEST["opt2"];
$contents = $_REQUEST["contents"];
$status   = $_REQUEST["status"];
$icon_new  = isset($_REQUEST["icon_new"]) ? 1 : 0;
$icon_hit  = isset($_REQUEST["icon_hit"]) ? 1 : 0;
$icon_sale = isset($_REQUEST["icon_sale"]) ? 1 : 0;
$discount  = $_REQUEST["discount"];
$regday    = $_REQUEST["regday"];

$image1 = $_REQUEST["imagename1"];
$image2 = $_REQUEST["imagename2"];
$image3 = $_REQUEST["imagename3"];

if (isset($_REQUEST["checkno1"]) && $image1) { unlink("../product/$image1"); $image1=""; }
if (isset($_REQUEST["checkno2"]) && $image2) { unlink("../product/$image2"); $image2=""; }
if (isset($_REQUEST["checkno3"]) && $image3) { unlink("../product/$image3"); $image3=""; }

if ($_FILES["image1"]["error"] == 0) {
	$image1 = $_FILES["image1"]["name"];
	move_uploaded_file($_FILES["image1"]["tmp_name"], "../product/$image1");
}
if ($_FILES["image2"]["error"] == 0) {
	$image2 = $_FILES["image2"]["name"];
	move_uploaded_file($_FILES["image2"]["tmp_name"], "../product/$image2");
}
if ($_FILES["image3"]["error"] == 0) {
	$image3 = $_FILES["image3"]["name"];
	move_uploaded_file($_FILES["image3"]["tmp_name"], "../product/$image3");
}

$sql = "update product set 
	menu=$menu, code='$code', name='$name', coname='$coname', price=$price,
	opt1=$opt1, opt2=$opt2, contents='$contents', status=$status,
	icon_new=$icon_new, icon_hit=$icon_hit, icon_sale=$icon_sale,
	discount=$discount, regday='$regday',
	image1='$image1', image2='$image2', image3='$image3'
	where id=$id";

$result = mysqli_query($db, $sql);
if (!$result) exit("에러: $sql");

echo("<script>location.href='product.php'</script>");
