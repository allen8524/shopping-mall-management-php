<?php
include "common.php";

$uid = trim($_POST["uid"] ?? "");
$pwd = trim($_POST["pwd"] ?? "");
$name = trim($_POST["name"] ?? "");
$tel1 = preg_replace('/\D+/', '', trim($_POST["tel1"] ?? ""));
$tel2 = preg_replace('/\D+/', '', trim($_POST["tel2"] ?? ""));
$tel3 = preg_replace('/\D+/', '', trim($_POST["tel3"] ?? ""));
$zip = trim($_POST["zip"] ?? "");
$juso = trim($_POST["juso"] ?? "");
$email = trim($_POST["email"] ?? "");
$birthday1 = (int)($_POST["birthday1"] ?? 0);
$birthday2 = (int)($_POST["birthday2"] ?? 0);
$birthday3 = (int)($_POST["birthday3"] ?? 0);

if ($uid === '' || $pwd === '' || $name === '') {
    exit('회원 가입 필수 항목이 누락되었습니다.');
}

$tel = sprintf("%-3s%-4s%-4s", $tel1, $tel2, $tel3);
$birthday = sprintf("%04d-%02d-%02d", $birthday1, $birthday2, $birthday3);
$gubun = 0;

$sql = "INSERT INTO member (uid, pwd, name, tel, zip, juso, email, birthday, gubun)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($db, $sql);
if (!$stmt) {
    error_log('Member insert prepare failed: ' . mysqli_error($db));
    exit('회원 가입 처리 중 오류가 발생했습니다.');
}

mysqli_stmt_bind_param($stmt, 'ssssssssi', $uid, $pwd, $name, $tel, $zip, $juso, $email, $birthday, $gubun);
if (!mysqli_stmt_execute($stmt)) {
    error_log('Member insert execute failed: ' . mysqli_stmt_error($stmt));
    mysqli_stmt_close($stmt);
    exit('회원 가입 처리 중 오류가 발생했습니다.');
}

mysqli_stmt_close($stmt);
header('Location: member_joinend.php');
exit;
