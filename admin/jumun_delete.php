<?php
include "login_main_check.php";
include "../common.php";
include "csrf.php";
admin_require_post_csrf('jumun.php');

$id = trim($_POST['id'] ?? '');
if (!preg_match('/^\d{10}$/', $id)) {
    header('Location: jumun.php');
    exit;
}

mysqli_begin_transaction($db);
try {
    $stmt = mysqli_prepare($db, "DELETE FROM jumuns WHERE jumun_id = ?");
    if (!$stmt) {
        throw new Exception('Order detail delete prepare failed');
    }
    mysqli_stmt_bind_param($stmt, 's', $id);
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception('Order detail delete execute failed');
    }
    mysqli_stmt_close($stmt);

    $stmt = mysqli_prepare($db, "DELETE FROM jumun WHERE id = ?");
    if (!$stmt) {
        throw new Exception('Order delete prepare failed');
    }
    mysqli_stmt_bind_param($stmt, 's', $id);
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception('Order delete execute failed');
    }
    mysqli_stmt_close($stmt);

    mysqli_commit($db);
} catch (Throwable $e) {
    mysqli_rollback($db);
    error_log('Order delete failed: ' . $e->getMessage());
    exit('주문 삭제 처리 중 오류가 발생했습니다.');
}

header('Location: jumun.php');
exit;
