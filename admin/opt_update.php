<?php
include "login_main_check.php";
include "../common.php";
include "csrf.php";
admin_require_post_csrf('opt.php');

$id = (int)($_POST['id'] ?? 0);
$name = trim($_POST['name'] ?? '');
if ($id <= 0 || $name === '') {
    header('Location: opt.php');
    exit;
}

$stmt = mysqli_prepare($db, "UPDATE opt SET name = ? WHERE id = ?");
if (!$stmt) {
    error_log('Option update prepare failed: ' . mysqli_error($db));
    exit('옵션 수정 처리 중 오류가 발생했습니다.');
}

mysqli_stmt_bind_param($stmt, 'si', $name, $id);
if (!mysqli_stmt_execute($stmt)) {
    error_log('Option update execute failed: ' . mysqli_stmt_error($stmt));
    mysqli_stmt_close($stmt);
    exit('옵션 수정 처리 중 오류가 발생했습니다.');
}

mysqli_stmt_close($stmt);
header('Location: opt.php');
exit;
