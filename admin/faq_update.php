<?php
include "login_main_check.php";
include "../common.php";
include "csrf.php";
admin_require_post_csrf('faq.php');

$id = (int)($_POST['id'] ?? 0);
$ask = trim($_POST['ask'] ?? '');
$answer = trim($_POST['answer'] ?? '');
if ($id <= 0 || $ask === '' || $answer === '') {
    header('Location: faq.php');
    exit;
}

$stmt = mysqli_prepare($db, "UPDATE faq SET ask = ?, answer = ? WHERE id = ?");
if (!$stmt) {
    error_log('FAQ update prepare failed: ' . mysqli_error($db));
    exit('FAQ 수정 처리 중 오류가 발생했습니다.');
}

mysqli_stmt_bind_param($stmt, 'ssi', $ask, $answer, $id);
if (!mysqli_stmt_execute($stmt)) {
    error_log('FAQ update execute failed: ' . mysqli_stmt_error($stmt));
    mysqli_stmt_close($stmt);
    exit('FAQ 수정 처리 중 오류가 발생했습니다.');
}

mysqli_stmt_close($stmt);
header('Location: faq.php');
exit;
