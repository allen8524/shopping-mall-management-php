<?php
include "login_main_check.php";
include "../common.php";
include "csrf.php";
admin_require_post_csrf('faq.php');

$ask = trim($_POST['ask'] ?? '');
$answer = trim($_POST['answer'] ?? '');
if ($ask === '' || $answer === '') {
    header('Location: faq.php');
    exit;
}

$stmt = mysqli_prepare($db, "INSERT INTO faq (ask, answer) VALUES (?, ?)");
if (!$stmt) {
    error_log('FAQ insert prepare failed: ' . mysqli_error($db));
    exit('FAQ 등록 처리 중 오류가 발생했습니다.');
}

mysqli_stmt_bind_param($stmt, 'ss', $ask, $answer);
if (!mysqli_stmt_execute($stmt)) {
    error_log('FAQ insert execute failed: ' . mysqli_stmt_error($stmt));
    mysqli_stmt_close($stmt);
    exit('FAQ 등록 처리 중 오류가 발생했습니다.');
}

mysqli_stmt_close($stmt);
header('Location: faq.php');
exit;
