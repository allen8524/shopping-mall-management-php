<!doctype html>
<html lang="kr" style="overflow:hidden">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>4910</title>
	<link rel="icon" type="image/x-icon" href="images/4910_top.ico">
	<link  href="css/bootstrap.min.css" rel="stylesheet">
	<link  href="css/my.css" rel="stylesheet">
	<script src="js/jquery-3.7.1.min.js"></script>
	<script src="js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="container-fulid">



<!--  현재 페이지 자바스크립  -------------------------------------------->
<script>
	function close_me(v)
	{
		opener.form2.check_id.value = v;
		self.close();
	}
</script>
<?php
    include "common.php";
    $uid = trim($_REQUEST["uid"] ?? '');
    $stmt = mysqli_prepare($db, "SELECT id FROM member WHERE uid = ?");
    if (!$stmt) {
        error_log('Member ID check prepare failed: ' . mysqli_error($db));
        exit('아이디 확인 중 오류가 발생했습니다.');
    }
    mysqli_stmt_bind_param($stmt, 's', $uid);
    if (!mysqli_stmt_execute($stmt)) {
        error_log('Member ID check execute failed: ' . mysqli_stmt_error($stmt));
        mysqli_stmt_close($stmt);
        exit('아이디 확인 중 오류가 발생했습니다.');
    }
    $result = mysqli_stmt_get_result($stmt);
    $count = $result ? mysqli_num_rows($result) : 0;
    mysqli_stmt_close($stmt);
?>

<!--  페이지 제목 -->
<div class="row m-0">
	<div class="col bg-light" align="center">
		<h4 class="m-2">중복 ID 조사</h4>
	</div>	
</div>	

<div class="row">
	<div class="col" align="center">
		<hr style="height:2px" class="my-0">
		<br><br>
		
        <?php
            if ($count == 0)
                echo "<b>$uid</b>는 사용 가능한 아이디입니다.
                        <br><br><br>
<a href=\"javascript:close_me('yes');\" class=\"btn btn-sm btn-dark text-white myfont\">확 인</a>";
            else 
                echo"<b>$uid</b>는 사용할 수 없는 아이디입니다.
                        <br><br><br>
            <a href=\"javascript:close_me('');\" class=\"btn btn-sm btn-dark text-white myfont\">확 인</a>";
        ?>
		

	</div>
</div>

<!-------------------------------------------------------------------------------------------->	
</div>

</body>
</html>
