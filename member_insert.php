<?php
include "common.php";

$uid = $_REQUEST["uid"];
$pwd = $_REQUEST["pwd"];
$name = $_REQUEST["name"];
$tel1 = $_REQUEST["tel1"];
$tel2 = $_REQUEST["tel2"];
$tel3 = $_REQUEST["tel3"];
$zip = $_REQUEST["zip"];
$juso = $_REQUEST["juso"];
$email = $_REQUEST["email"];
$birthday1 = $_REQUEST["birthday1"];
$birthday2 = $_REQUEST["birthday2"];
$birthday3 = $_REQUEST["birthday3"];



$tel = sprintf("%-3s%-4s%-4s", $tel1, $tel2, $tel3);

$birthday = sprintf("%04d-%02d-%02d", $birthday1, $birthday2, $birthday3);

$sql = "INSERT INTO member (uid, pwd, name, tel, zip, juso, email, birthday, gubun) 
        VALUES ('$uid', '$pwd','$name', '$tel', '$zip', '$juso', '$email', '$birthday', 0)";
$result = mysqli_query($db, $sql);
if (!$result) {
    error_log('Member insert failed: ' . mysqli_error($db));
    exit('회원 가입 처리 중 오류가 발생했습니다.');
}

header('Location: member_joinend.php');
exit;
