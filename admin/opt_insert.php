<?
	include "login_main_check.php";
	include "../common.php";
	
	$name = $_REQUEST["name"];
	
	$sql = "insert into opt (name)
								values('$name')";
	$result = mysqli_query($db, $sql);
	if (!$result) exit("에러: $sql");
	
	echo("<script>location.href='opt.php'</script>");
?>