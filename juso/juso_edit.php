<?
	include "common.php";
	
	$id=$_REQUEST["id"];
	
	$sql="select * from juso where id=$id";
	$result=mysqli_query($db,$sql);
	if (!$result) exit("에러: $sql");

	$row=mysqli_fetch_array($result);

    $tel1 = trim(substr($row["tel"], 0, 3));
    $tel2 = trim(substr($row["tel"], 3, 4));
    $tel3 = trim(substr($row["tel"], 7, 4));

    $birthday1 = (substr($row["birthday"], 0, 4));
    $birthday2 = (substr($row["birthday"], 5, 2));
    $birthday3 = (substr($row["birthday"], 8, 2));

?>
<!doctype html>
<html lang="kr">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>JUSO</title>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/my.css" rel="stylesheet">
	<script src="js/jquery-3.7.1.min.js"></script>
	<script src="js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="container">
<!-------------------------------------------------------------------------------------------->	
<br>
	
<form name="form1" method="post" action="juso_update.php">
<input type="hidden" name="id" value="<?=$id ?>">

<table class="table table-sm table-bordered mymargin5">
	<tr height="40">
		<td width="20%" class="mycolor2">ID</td>
		<td width="80%"align="left">&nbsp;<?=$row["id"]; ?></td>
	</tr>
	<tr>
		<td class="mycolor2">이름</td>
		<td align="left">
			<div class="d-inline-flex">
				<input type="text" name="name" size="20" value=<?=$row["name"]; ?>
					class="form-control form-control-sm">
			</div>
		</td>
	</tr>
	<tr>
		<td class="mycolor2">전화</td>
		<td align="left">
			<div class="d-inline-flex">
                <input type="text" name="tel1" size="3" maxlength="3" value=<?=$tel1; ?>
					class="form-control form-control-sm"> - 
				<input type="text" name="tel2" size="4" maxlength="4" value=<?=$tel2; ?> 
					class="form-control form-control-sm"> - 
				<input type="text" name="tel3" size="4" maxlength="4" value=<?=$tel3; ?>
					class="form-control form-control-sm">
			</div>
		</td>
	</tr>
	<tr  height="40">
		<td class="mycolor2">음력/양력</td>
		<td align="left">
        <?php if ($row["sm"] == 1): ?>
            &nbsp;<input type="radio" name="sm" value="1" class="form-check-input" checked> 음력
            &nbsp;<input type="radio" name="sm" value="0" class="form-check-input"> 양력
<?php else: ?>
    &nbsp;<input type="radio" name="sm" value="1" class="form-check-input"> 음력
    &nbsp;<input type="radio" name="sm" value="0" class="form-check-input" checked> 양력
<?php endif; ?>

		</td>
	</tr>
	<tr>
		<td class="mycolor2">생일</td>
		<td align="left">
			<div class="d-inline-flex">
				<input type="text" name="birthday1" size="4" maxlength="4" value=<?=$birthday1; ?>
					class="form-control form-control-sm"> -
				<input type="text" name="birthday2" size="2" maxlength="2" value=<?=$birthday2; ?>
					class="form-control form-control-sm"> -
				<input type="text" name="birthday3" size="2" maxlength="2" value=<?=$birthday3; ?>
					class="form-control form-control-sm">
			</div>
		</td>
	</tr>
	<tr>
		<td class="mycolor2">주소</td>
		<td align="left">
			<input type="text" name="juso" value=<?=$row["juso"]; ?> 
				class="form-control form-control-sm">
		</td>
	</tr>
</table>

<div align="center">
	<input type="submit" value="저장" class="btn btn-sm mycolor1">&nbsp;
	<input type="button" value="이전화면" class="btn btn-sm mycolor1" 
		onClick="history.back();">
</div>

</form>
	
<!-------------------------------------------------------------------------------------------->	
</div>

</body>
</html>

