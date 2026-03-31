<?
	include "login_main_check.php";
	include "../common.php";
	$id = $_REQUEST["id"];
	$name = $_REQUEST["name"];
	$sql="update opt set name='$name' where id = $id";
	$result=mysqli_query($db, $sql);
	if (!$result) exit("에러: $sql");
	
	echo("<script>location.href='opt.php'</script>");
?>
