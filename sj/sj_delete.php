<?php
	include "common.php";
	
	$id=(int)($_REQUEST["id"] ?? 0);
	
	$sql="delete from sj where id = $id";
	$result=mysqli_query($db,$sql);
	if (!$result) { error_log("Score delete failed: " . mysqli_error($db)); exit("성적 삭제 처리 중 오류가 발생했습니다."); }
	
	echo("<script>location.href='sj_list.php'</script>");
