<?php
include "login_main_check.php";
include "../common.php";

// 파라미터 받기
$id    = trim($_REQUEST["id"] ?? "");
$state = (int)($_REQUEST["state"] ?? -1);
$page  = max(1, (int)($_REQUEST["page"] ?? 1));
$sel1  = $_REQUEST["sel1"] ?? "";
$sel2  = (int)($_REQUEST["sel2"] ?? 1);
$text1 = trim($_REQUEST["text1"] ?? "");
$day1  = $_REQUEST["day1"] ?? date("Y-m-01");
$day2  = $_REQUEST["day2"] ?? date("Y-m-d");

$sel1 = in_array((string)$sel1, ["", "0", "1", "2", "3", "4", "5"], true) ? (string)$sel1 : "";
$sel2 = in_array($sel2, [1, 2, 3], true) ? $sel2 : 1;
$day1 = preg_match('/^\d{4}-\d{2}-\d{2}$/', $day1) ? $day1 : date("Y-m-01");
$day2 = preg_match('/^\d{4}-\d{2}-\d{2}$/', $day2) ? $day2 : date("Y-m-d");

$params = [
    "page" => $page,
    "sel1" => $sel1,
    "sel2" => $sel2,
    "text1" => $text1,
    "day1" => $day1,
    "day2" => $day2,
];
$redirect_url = "jumun.php?" . http_build_query($params);

// 필수 파라미터 누락 또는 허용되지 않은 상태값 차단
if (!preg_match('/^\d{10}$/', $id) || $state < 0 || $state > 5) {
    header("Location: $redirect_url");
    exit;
}

$stmt = mysqli_prepare($db, "UPDATE jumun SET state = ? WHERE id = ?");
if (!$stmt) {
    error_log("admin/jumun_update.php: failed to prepare order state update");
    header("Location: $redirect_url");
    exit;
}

mysqli_stmt_bind_param($stmt, "is", $state, $id);
if (!mysqli_stmt_execute($stmt)) {
    error_log("admin/jumun_update.php: failed to execute order state update");
}
mysqli_stmt_close($stmt);

// 목록 페이지로 리다이렉트 (파라미터 유지)
header("Location: $redirect_url");
exit;
