<?php
include "login_main_check.php";
include "../common.php";
include "csrf.php";
admin_require_post_csrf('faq.php');

$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) {
    header('Location: faq.php');
    exit;
}

$stmt = mysqli_prepare($db, "DELETE FROM faq WHERE id = ?");
if (!$stmt) {
    error_log('FAQ delete prepare failed: ' . mysqli_error($db));
    exit('FAQ 삭제 처리 중 오류가 발생했습니다.');
}

mysqli_stmt_bind_param($stmt, 'i', $id);
if (!mysqli_stmt_execute($stmt)) {
    error_log('FAQ delete execute failed: ' . mysqli_stmt_error($stmt));
    mysqli_stmt_close($stmt);
    exit('FAQ 삭제 처리 중 오류가 발생했습니다.');
}

mysqli_stmt_close($stmt);
header('Location: faq.php');
exit;
