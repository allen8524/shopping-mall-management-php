<?
	include "login_main_check.php";
	include "../common.php";
	
	$id = $_REQUEST["id"];
	
	$sql="delete from opt where id=$id";
	$result=mysqli_query($db, $sql);
	if (!$result) exit("에러: $sql");
	
	echo("<script>location.href='opt.php'</script>");
?>