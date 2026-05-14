<?php
include "common.php";

$name = trim($_REQUEST["name"] ?? "");
$kor = (int)($_REQUEST["kor"] ?? 0);
$eng = (int)($_REQUEST["eng"] ?? 0);
$mat = (int)($_REQUEST["mat"] ?? 0);
$hap = (int)($_REQUEST["hap"] ?? 0);
$avg = (float)($_REQUEST["avg"] ?? 0);

$name_esc = mysqli_real_escape_string($db, $name);

$sql = "insert into sj (name, kor, eng, mat, hap, avg)
        values ('$name_esc', $kor, $eng, $mat, $hap, $avg)";
$result = mysqli_query($db, $sql);
if (!$result) {
    error_log("Score insert failed: " . mysqli_error($db));
    exit("성적 등록 처리 중 오류가 발생했습니다.");
}

header("Location: sj_list.php");
exit;
