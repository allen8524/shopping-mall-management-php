<?
	include "login_main_check.php";
	include "../common.php";

	$id = $_REQUEST["id"];
	$name = $_REQUEST["name"];

	$sql = "INSERT INTO opts (opt_id, name)
			VALUES ($id, '$name')";
	$result = mysqli_query($db, $sql);
	if (!$result) exit("에러: $sql");

	echo("<script>location.href='opts.php?id=$id'</script>");
