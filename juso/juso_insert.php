<?php
include "common.php";

$name = trim($_REQUEST["name"] ?? "");
$tel1 = trim($_REQUEST["tel1"] ?? "");
$tel2 = trim($_REQUEST["tel2"] ?? "");
$tel3 = trim($_REQUEST["tel3"] ?? "");
$sm = (int)($_REQUEST["sm"] ?? 0);
$birthday1 = (int)($_REQUEST["birthday1"] ?? 0);
$birthday2 = (int)($_REQUEST["birthday2"] ?? 0);
$birthday3 = (int)($_REQUEST["birthday3"] ?? 0);
$juso = trim($_REQUEST["juso"] ?? "");

$tel = sprintf("%-3s%-4s%-4s", $tel1, $tel2, $tel3);
$birthday = sprintf("%04d-%02d-%02d", $birthday1, $birthday2, $birthday3);

$name_esc = mysqli_real_escape_string($db, $name);
$tel_esc = mysqli_real_escape_string($db, $tel);
$birthday_esc = mysqli_real_escape_string($db, $birthday);
$juso_esc = mysqli_real_escape_string($db, $juso);

$sql = "INSERT INTO juso (name, tel, sm, birthday, juso)
        VALUES ('$name_esc', '$tel_esc', $sm, '$birthday_esc', '$juso_esc')";
$result = mysqli_query($db, $sql);
if (!$result) {
    error_log("Juso insert failed: " . mysqli_error($db));
    exit("주소 등록 처리 중 오류가 발생했습니다.");
}

header("Location: juso_list.php");
exit;
