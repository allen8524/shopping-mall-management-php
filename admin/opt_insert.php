<?php
include "login_main_check.php";
include "../common.php";
include "csrf.php";
admin_require_post_csrf('opt.php');

$name = trim($_POST['name'] ?? '');
if ($name === '') {
    header('Location: opt.php');
    exit;
}

$stmt = mysqli_prepare($db, "INSERT INTO opt (name) VALUES (?)");
if (!$stmt) {
    error_log('Option insert prepare failed: ' . mysqli_error($db));
    exit('옵션 등록 처리 중 오류가 발생했습니다.');
}

mysqli_stmt_bind_param($stmt, 's', $name);
if (!mysqli_stmt_execute($stmt)) {
    error_log('Option insert execute failed: ' . mysqli_stmt_error($stmt));
    mysqli_stmt_close($stmt);
    exit('옵션 등록 처리 중 오류가 발생했습니다.');
}

mysqli_stmt_close($stmt);
header('Location: opt.php');
exit;
