<?php
	include "login_main_check.php";
	include "../common.php";

	$id = $_REQUEST["id"];     
	$id1 = $_REQUEST["id1"];   
	$name = $_REQUEST["name"]; 

	$sql = "UPDATE opts SET name='$name' WHERE id=$id1";
	$result = mysqli_query($db, $sql);
	if (!$result) exit("에러: $sql");

	echo("<script>location.href='opts.php?id=$id'</script>");
?>