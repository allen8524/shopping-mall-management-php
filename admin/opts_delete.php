<?php
	include "login_main_check.php";
	include "../common.php";

	$id = $_REQUEST["id"];  

	
	$row = mysqli_fetch_array(mysqli_query($db, "select opt_id from opts where id=$id"));
	$opt_id = $row["opt_id"];  

	
	$sql = "delete from opts where id=$id";
	$result = mysqli_query($db, $sql);
	if (!$result) exit("에러: $sql");

	
	echo("<script>location.href='opts.php?id=$opt_id'</script>");
