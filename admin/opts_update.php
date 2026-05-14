<?php
include "login_main_check.php";
include "../common.php";
include "csrf.php";
admin_require_post_csrf('opts.php');

$opt_id = (int)($_POST['id'] ?? 0);
$id = (int)($_POST['id1'] ?? 0);
$name = trim($_POST['name'] ?? '');
if ($opt_id <= 0 || $id <= 0 || $name === '') {
    header('Location: opt.php');
    exit;
}

$stmt = mysqli_prepare($db, "UPDATE opts SET name = ? WHERE id = ? AND opt_id = ?");
if (!$stmt) {
    error_log('Sub option update prepare failed: ' . mysqli_error($db));
    exit('소옵션 수정 처리 중 오류가 발생했습니다.');
}

mysqli_stmt_bind_param($stmt, 'sii', $name, $id, $opt_id);
if (!mysqli_stmt_execute($stmt)) {
    error_log('Sub option update execute failed: ' . mysqli_stmt_error($stmt));
    mysqli_stmt_close($stmt);
    exit('소옵션 수정 처리 중 오류가 발생했습니다.');
}

mysqli_stmt_close($stmt);
header('Location: opts.php?id=' . $opt_id);
exit;
