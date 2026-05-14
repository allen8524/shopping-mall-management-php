<?php
include "login_main_check.php";
include "../common.php";

$id = isset($_POST['id']) ? (int)$_POST['id'] : (isset($_GET['id']) ? (int)$_GET['id'] : 0);
if ($id <= 0) {
    header('Location: opts.php');
    exit;
}

$selectSql = "SELECT opt_id FROM opts WHERE id = ?";
$selectStmt = mysqli_prepare($db, $selectSql);
if (!$selectStmt) {
    error_log('Sub option select prepare failed: ' . mysqli_error($db));
    exit('옵션 상세 삭제 처리 중 오류가 발생했습니다.');
}

mysqli_stmt_bind_param($selectStmt, 'i', $id);
if (!mysqli_stmt_execute($selectStmt)) {
    error_log('Sub option select execute failed: ' . mysqli_stmt_error($selectStmt));
    mysqli_stmt_close($selectStmt);
    exit('옵션 상세 삭제 처리 중 오류가 발생했습니다.');
}

$result = mysqli_stmt_get_result($selectStmt);
$row = $result ? mysqli_fetch_assoc($result) : null;
mysqli_stmt_close($selectStmt);

$opt_id = $row ? (int)$row['opt_id'] : 0;
if ($opt_id <= 0) {
    header('Location: opts.php');
    exit;
}

$deleteSql = "DELETE FROM opts WHERE id = ?";
$deleteStmt = mysqli_prepare($db, $deleteSql);
if (!$deleteStmt) {
    error_log('Sub option delete prepare failed: ' . mysqli_error($db));
    exit('옵션 상세 삭제 처리 중 오류가 발생했습니다.');
}

mysqli_stmt_bind_param($deleteStmt, 'i', $id);
if (!mysqli_stmt_execute($deleteStmt)) {
    error_log('Sub option delete execute failed: ' . mysqli_stmt_error($deleteStmt));
    mysqli_stmt_close($deleteStmt);
    exit('옵션 상세 삭제 처리 중 오류가 발생했습니다.');
}

mysqli_stmt_close($deleteStmt);
header('Location: opts.php?id=' . $opt_id);
exit;
