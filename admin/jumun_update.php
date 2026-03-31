<?php
include "login_main_check.php";
include "../common.php";

// 파라미터 받기
$id    = $_REQUEST["id"]    ?? "";
$state = $_REQUEST["state"] ?? "";
$page  = $_REQUEST["page"]  ?? 1;
$sel1  = $_REQUEST["sel1"]  ?? "";
$sel2  = $_REQUEST["sel2"]  ?? 1;
$text1 = $_REQUEST["text1"] ?? "";
$day1  = $_REQUEST["day1"]  ?? date("Y-m-01");
$day2  = $_REQUEST["day2"]  ?? date("Y-m-d");

// 필수 파라미터 누락 시 차단
if ($id === "" || $state === "") {
    echo "<script>alert('잘못된 접근입니다.'); history.back();</script>";
    exit;
}

// 상태 업데이트 쿼리 실행
$query = "UPDATE jumun SET state = $state WHERE id = $id";
mysqli_query($db, $query);

// 목록 페이지로 리다이렉트 (파라미터 유지)
$url = "jumun.php?page=$page&sel1=$sel1&sel2=$sel2&text1=$text1&day1=$day1&day2=$day2";
echo "<script>location.href='$url';</script>";
