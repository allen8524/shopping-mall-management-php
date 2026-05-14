<?php
include "common.php";

$cookie_id = (int)($_COOKIE["cookie_id"] ?? 0);
$pwd = trim($_POST["pwd"] ?? '');
$pwd1 = trim($_POST["pwd1"] ?? '');
$name = trim($_POST["name"] ?? '');
$tel1 = trim($_POST["tel1"] ?? '');
$tel2 = trim($_POST["tel2"] ?? '');
$tel3 = trim($_POST["tel3"] ?? '');
$zip = trim($_POST["zip"] ?? '');
$juso = trim($_POST["juso"] ?? '');
$email = trim($_POST["email"] ?? '');
$birthday1 = (int)($_POST["birthday1"] ?? 0);
$birthday2 = (int)($_POST["birthday2"] ?? 0);
$birthday3 = (int)($_POST["birthday3"] ?? 0);

if ($cookie_id <= 0 || $name === '') {
    exit('회원 수정 처리 중 오류가 발생했습니다.');
}

$tel = sprintf("%-3s%-4s%-4s", $tel1, $tel2, $tel3);
$birthday = sprintf("%04d-%02d-%02d", $birthday1, $birthday2, $birthday3);

if ($pwd === $pwd1 && $pwd !== '') {
    $stmt = mysqli_prepare($db, "UPDATE member SET pwd = ?, name = ?, tel = ?, zip = ?, juso = ?, email = ?, birthday = ? WHERE id = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'sssssssi', $pwd, $name, $tel, $zip, $juso, $email, $birthday, $cookie_id);
    }
} else {
    $stmt = mysqli_prepare($db, "UPDATE member SET name = ?, tel = ?, zip = ?, juso = ?, email = ?, birthday = ? WHERE id = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'ssssssi', $name, $tel, $zip, $juso, $email, $birthday, $cookie_id);
    }
}

if (!$stmt) {
    error_log('Member self update prepare failed: ' . mysqli_error($db));
    exit('회원 수정 처리 중 오류가 발생했습니다.');
}

if (!mysqli_stmt_execute($stmt)) {
    error_log('Member self update execute failed: ' . mysqli_stmt_error($stmt));
    mysqli_stmt_close($stmt);
    exit('회원 수정 처리 중 오류가 발생했습니다.');
}
mysqli_stmt_close($stmt);

header('Location: main.php');
exit;
