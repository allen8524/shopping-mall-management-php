<?php
include "common.php";

$id = (int)($_REQUEST["id"] ?? 0);
$name = trim($_REQUEST["name"] ?? "");
$kor = (int)($_REQUEST["kor"] ?? 0);
$eng = (int)($_REQUEST["eng"] ?? 0);
$mat = (int)($_REQUEST["mat"] ?? 0);
$hap = (int)($_REQUEST["hap"] ?? 0);
$avg = (float)($_REQUEST["avg"] ?? 0);

if ($id <= 0) {
    header("Location: sj_list.php");
    exit;
}

$name_esc = mysqli_real_escape_string($db, $name);

$sql = "update sj set name='$name_esc', kor=$kor, eng=$eng, mat=$mat, hap=$hap, avg=$avg where id=$id";
$result = mysqli_query($db, $sql);
if (!$result) {
    error_log("Score update failed: " . mysqli_error($db));
    exit("성적 수정 처리 중 오류가 발생했습니다.");
}

header("Location: sj_list.php");
exit;
