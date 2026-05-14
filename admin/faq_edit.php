<?php
include "login_main_check.php";
include "../common.php";
include "csrf.php";

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: faq.php');
    exit;
}

$stmt = mysqli_prepare($db, "SELECT * FROM faq WHERE id = ?");
if (!$stmt) {
    error_log('FAQ edit prepare failed: ' . mysqli_error($db));
    exit('FAQ 정보를 조회할 수 없습니다.');
}
mysqli_stmt_bind_param($stmt, 'i', $id);
if (!mysqli_stmt_execute($stmt)) {
    error_log('FAQ edit execute failed: ' . mysqli_stmt_error($stmt));
    mysqli_stmt_close($stmt);
    exit('FAQ 정보를 조회할 수 없습니다.');
}
$result = mysqli_stmt_get_result($stmt);
$row = $result ? mysqli_fetch_array($result) : null;
mysqli_stmt_close($stmt);
if (!$row) {
    header('Location: faq.php');
    exit;
}
?>
<!doctype html>
<html lang="kr">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>4910</title>
 	<link rel="icon" href="../images/4910_top.ico" type="image/x-icon">
	<link href="../css/bootstrap.min.css" rel="stylesheet">
	<link href="../css/my.css" rel="stylesheet">
	<script src="../js/jquery-3.7.1.min.js"></script>
	<script src="../js/bootstrap.bundle.min.js"></script>
	<script src="../js/my.js"></script>
</head>
<body>

<div class="container">
<script> document.write(admin_menu());</script>

<form name="form1" method="post" action="faq_update.php">
<?= admin_csrf_input() ?>
<input type="hidden" name="id" value="<?= $id ?>">

<div class="row mx-1 justify-content-center">
	<div class="col-sm-10" align="center">

	<h4 class="m-0 mb-3">FAQ 수정</h4>

		<table class="table table-sm table-bordered myfs12">
			<tr height="40">
				<td width="15%" class="bg-light">질문</td>
				<td align="left" class="ps-2">
					<input type="text" name="ask" size="80" value="<?= htmlspecialchars($row['ask']) ?>" class="form-control form-control-sm myfs12">
				</td>
			</tr>
			<tr>
				<td class="bg-light">답변</td>
				<td align="left" class="ps-2">
					<textarea name="answer" rows="7" cols="80" class="form-control form-control-sm my-1 myfs12"><?= htmlspecialchars($row['answer']) ?></textarea>
				</td>
			</tr>
		</table>

		<a href="javascript:form1.submit();" class="btn btn-sm btn-dark text-white my-2">&nbsp;저 장&nbsp;</a>&nbsp;
		<a href="faq.php" class="btn btn-sm btn-outline-dark my-2">&nbsp;돌아가기&nbsp;</a>

	</div>
</div>
<br>
</form>
</div>

</body>
</html>
