<?php
ob_start();
session_start();

// 이미 로그인(또는 비회원 조회용 cookie_id) 상태면 바로 주문내역 페이지로
if (!empty($_COOKIE['cookie_id'])) {
    header("Location: jumun.php");
    exit;
}

include "main_top.php";

// 1) 비회원 POST 요청 처리 (이름+이메일로 쿠키 저장)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if ($name && $email) {
        $name  = mysqli_real_escape_string($db, $name);
        $email = mysqli_real_escape_string($db, $email);

        $sql = "SELECT COUNT(*) as cnt FROM jumun WHERE o_name='$name' AND o_email='$email'";
        $res = mysqli_query($db, $sql);
        $cnt = mysqli_fetch_assoc($res)['cnt'] ?? 0;

        if ($cnt > 0) {
            // 조회용 식별 쿠키 저장 (cookie_id 대신 guest_name/email만 사용하는 경우, cookie_id 로직에 맞춰 조정)
            setcookie('cookie_id', session_id(), time() + 3600, '/');
            setcookie('guest_name', $name, time() + 3600, '/');
            setcookie('guest_email', $email, time() + 3600, '/');
            header("Location: jumun.php");
            exit;
        } else {
            echo "<script>alert('해당 정보로 주문 내역을 찾을 수 없습니다.'); history.back();</script>";
            exit;
        }
    }
}
?>
<!doctype html>
<html lang="kr">
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

<div class="container">
<!-------------------------------------------------------------------------------------------->	



<!-------------------------------------------------------------------------------------------->	
<!-- 시작 : 다른 웹페이지 삽입할 부분 -->
<!-------------------------------------------------------------------------------------------->	


<!--  현재 페이지 자바스크립  -------------------------------------------->
<script>
	function NoMember_Check() {
		const form = document.form2;
		if (!form.name.value.trim()) {
			alert("이름을 입력해 주십시오.");
			form.name.focus();
			return;
		}
		if (!form.email.value.trim()) {
			alert("E-Mail을 입력해 주십시오.");
			form.email.focus();
			return;
		}
		form.submit();
	}
</script>


<!-- form2 시작 -->
<form name="form2" method="post" action="jumun.php">

<div class="row mb-0">
	<div class="col"></div>
	<div class="col" align="center">

		<h3 class="mt-5">비회원 주문조회</h3>
		<hr size="4px" class="m-0 mb-5">

		<table width="340" height="200" style="border:4px solid #e2e2e2" 
			bgcolor="#fcfcfc" class="table-borderless">
			<tr>
				<td align="center">
				
						<table  class="table table-borderless mt-3">
						<tr height="45">
							<td width="20%">이름</td>
							<td width="50%">
								<div class="d-inline-flex">
									<input type="text" name="name" size="20" value="" tabindex="1" 
										class="form-control form-control-sm">
								</div>
							</td>
<td width="30%" rowspan="2" class="p-0">
	<div class="d-flex align-items-center justify-content-center h-100">
		<button type="button" onclick="NoMember_Check();" 
			class="btn btn-sm btn-dark text-white" 
			style="height:75px; width:75px;">
			로그인
		</button>
	</div>
</td>


						</tr>
						<tr height="45">
							<td>E-Mail</td>
							<td>
								<div class="d-inline-flex">
									<input type="text" name="email" size="20" value="" tabindex="2" 
										class="form-control form-control-sm">
								</div>
							</td>
						</tr>
					</table>					
				
				</td>
			</tr>
			<tr><td><hr class="m-0"></td></tr>
			<tr height="50">
				<td align="center">※ 회원님은 로그인 후, 이용하세요.</td>
			</tr>
		</table>
<?php if (!empty($_COOKIE['guest_name']) && !empty($_COOKIE['guest_email'])): ?>
	<div class="row mt-3">
		<div class="col text-center">
			<a href="guest_logout.php" class="btn btn-sm btn-outline-secondary">비회원 로그아웃</a>
		</div>
	</div>
<?php endif; ?>
	</div>
	<div class="col"></div>
</div>

</form>

<br><br><br><br><br>

<!-------------------------------------------------------------------------------------------->	
<!-- 끝 : 다른 웹페이지 삽입할 부분 -->
<!-------------------------------------------------------------------------------------------->	

<?php

    include "main_bottom.php"

?>

<!-------------------------------------------------------------------------------------------->	
</div>

</body>
</html>
