<?php
include "login_main_check.php";
include "../common.php";
include "csrf.php";
admin_require_post_csrf('member.php');

$id = (int)($_POST['id'] ?? 0);
$pwd = trim($_POST['pwd'] ?? '');
$name = trim($_POST['name'] ?? '');
$tel1 = trim($_POST['tel1'] ?? '');
$tel2 = trim($_POST['tel2'] ?? '');
$tel3 = trim($_POST['tel3'] ?? '');
$zip = trim($_POST['zip'] ?? '');
$juso = trim($_POST['juso'] ?? '');
$email = trim($_POST['email'] ?? '');
$birthday1 = (int)($_POST['birthday1'] ?? 0);
$birthday2 = (int)($_POST['birthday2'] ?? 0);
$birthday3 = (int)($_POST['birthday3'] ?? 0);
$gubun = (int)($_POST['gubun'] ?? 0);
$gubun = ($gubun === 1) ? 1 : 0;

if ($id <= 0 || $name === '') {
    header('Location: member.php');
    exit;
}

$tel = sprintf("%-3s%-4s%-4s", $tel1, $tel2, $tel3);
$birthday = sprintf("%04d-%02d-%02d", $birthday1, $birthday2, $birthday3);

$sql = "UPDATE member SET pwd = ?, name = ?, tel = ?, zip = ?, juso = ?, email = ?, birthday = ?, gubun = ? WHERE id = ?";
$stmt = mysqli_prepare($db, $sql);
if (!$stmt) {
    error_log('Member update prepare failed: ' . mysqli_error($db));
    exit('회원 수정 처리 중 오류가 발생했습니다.');
}

mysqli_stmt_bind_param($stmt, 'sssssssii', $pwd, $name, $tel, $zip, $juso, $email, $birthday, $gubun, $id);
if (!mysqli_stmt_execute($stmt)) {
    error_log('Member update execute failed: ' . mysqli_stmt_error($stmt));
    mysqli_stmt_close($stmt);
    exit('회원 수정 처리 중 오류가 발생했습니다.');
}

mysqli_stmt_close($stmt);
header('Location: member.php');
exit;
