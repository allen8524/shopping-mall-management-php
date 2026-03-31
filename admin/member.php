<?php
include "login_main_check.php";   // 관리자 인증 체크
include "../common.php";

// 검색 조건 처리
$text1 = $_REQUEST["text1"] ?? "";
$sel1  = $_REQUEST["sel1"] ?? 1;

// SQL 쿼리 조건 분기
if ($sel1 == 1) {
    $sql = "SELECT * FROM member WHERE name LIKE '%$text1%' ORDER BY name";
} else {
    $sql = "SELECT * FROM member WHERE uid LIKE '%$text1%' ORDER BY uid";
}

// 페이징 처리
$args = "text1=$text1&sel1=$sel1";
$result = mypagination($sql, $args, $count, $pagebar);

if (!$result) exit("에러: $sql");
?>

<!doctype html>
<html lang="kr">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>4910</title>
	<link rel="icon" type="image/x-icon" href="../images/4910_top.ico">
	<link  href="../css/bootstrap.min.css" rel="stylesheet">
	<link  href="../css/my.css" rel="stylesheet">
	<script src="..js/jquery-3.7.1.min.js"></script>
	<script src="../js/bootstrap.bundle.min.js"></script>
	<script src="../js/my.js"></script>
</head>
<body>

<div class="container">
<!-------------------------------------------------------------------------------------------->	
<script> document.write(admin_menu());</script>
<!-------------------------------------------------------------------------------------------->	

<div class="row mx-1 justify-content-center">
	<div class="col" align="center">

	<h4 class="m-0 mb-2">회원</h4>

	<form name="form1" method="post" action="member.php">
	
	<table class="table table-sm table-borderless m-0">
		<tr>
			<td align="left" style="padding-top:12px">
			&nbsp;회원수 : <font color="red"><?=$count?></font>
			</td>
			<td align="right">
				<div class="d-inline-flex">
					<div class="input-group input-group-sm">
						<select name="sel1" class="form-select form-select-sm bg-light myfs12" style="width:80px;"> 
								<option value="1" selected>이름</option><option value="2">아이디</option>
						</select>
						<input type="text" name="text1" value="<?=$text1?>" style="width:100px;" 
							class="form-control myfs12" 
							onKeydown="if (event.keyCode == 13) { form1.submit(); }"> 
						<button class="btn mycolor1 myfs12" type="button"  
							onClick="form1.submit();">검색</button>
					</div>
				</div>
				
			</td>
		</tr>
	</table>
	
	</form>


	<table class="table table-sm table-bordered table-hover m-0 mb-1">
		<tr class="bg-light">
			<td>아이디</td>
			<td>이름</td>
			<td>핸드폰</td>
			<td>E-Mail</td>
			<td width="10%">구분</td>
			<td width="15%">수정 / 삭제</td>
		</tr>

		<?
		foreach ($result as $row)
		{
			$tel1=trim(substr($row["tel"],0,3));
			$tel2=trim(substr($row["tel"],3,4));
			$tel3=trim(substr($row["tel"],7,4));
			$tel=$tel1 . "-" . $tel2 . "-" . $tel3;
	?>
		<tr>
			<td><?=$row["id"]; ?></td>
			<td><?=$row["name"]; ?></td>
			<td><?=$tel; ?></td>
			<td align="left" class="px-2"><?=$row["email"]; ?></td>
			<td><?=$row["gubun"]==0 ? "회원" : "탈퇴"?></td>
			<td>
				<a href="member_edit.php?id=<?=$row["id"]; ?>" 
					class="btn btn-sm btn-outline-info mybutton-blue">수정</a>
				<a href="member_delete.php?id=<?=$row["id"]; ?>"  
					class="btn btn-sm btn-outline-danger mybutton-red" 
					onclick="javascript:return confirm('삭제할까요 ?');">삭제</a>				
			</td>
		</tr>
		<?
		}
	?>
	</table>

	<?
	echo $pagebar;
?>

	</div>
</div>
<!-------------------------------------------------------------------------------------------->	
</div>

</body>
</html>
