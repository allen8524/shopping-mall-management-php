<?php
include "login_main_check.php";
include "../common.php";
include "csrf.php";

$id1 = (int)($_GET['id'] ?? 0);
if ($id1 <= 0) {
    header('Location: opt.php');
    exit;
}

$stmt = mysqli_prepare($db, "SELECT * FROM opts WHERE id = ?");
if (!$stmt) {
    error_log('Sub option edit prepare failed: ' . mysqli_error($db));
    exit('소옵션 정보를 조회할 수 없습니다.');
}
mysqli_stmt_bind_param($stmt, 'i', $id1);
if (!mysqli_stmt_execute($stmt)) {
    error_log('Sub option edit execute failed: ' . mysqli_stmt_error($stmt));
    mysqli_stmt_close($stmt);
    exit('소옵션 정보를 조회할 수 없습니다.');
}
$result = mysqli_stmt_get_result($stmt);
$row = $result ? mysqli_fetch_assoc($result) : null;
mysqli_stmt_close($stmt);
if (!$row) {
    header('Location: opt.php');
    exit;
}

$opt_id = (int)$row["opt_id"];
?>
<!doctype html>
<html lang="kr">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>INDUK Mall</title>
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

<form name="form1" method="post" action="opts_update.php">
<?= admin_csrf_input() ?>
	<input type="hidden" name="id" value="<?= $opt_id; ?>">   <!-- 상위 옵션 ID -->
	<input type="hidden" name="id1" value="<?= $id1; ?>">     <!-- 소옵션 ID -->

	<div class="row mx-1  justify-content-center">
		<div class="col-sm-10" align="center">

			<h4 class="m-0 mb-3">소옵션</h4>

			<table class="table table-sm table-bordered myfs12">
				<tr>
					<td width="30%" class="bg-light p-2">소옵션번호</td>
					<td align="left" class="ps-3"><?= $row["id"]; ?></td>
				</tr>
				<tr>
					<td class="bg-light">소옵션명</td>
					<td align="left" class="ps-2">
						<div class="d-inline-flex">
							<input type="text" name="name" size="30" value="<?= $row['name'] ?>" class="form-control form-control-sm">
						</div>
					</td>
				</tr>
			</table>

			<a href="javascript:form1.submit();" class="btn btn-sm btn-dark text-white my-2">&nbsp;저 장&nbsp;</a>&nbsp;
			<a href="javascript:history.back();" class="btn btn-sm btn-outline-dark my-2">&nbsp;돌아가기&nbsp;</a>

		</div>
	</div>
	<br>
</form>
<!-------------------------------------------------------------------------------------------->	
</div>

</body>
</html>