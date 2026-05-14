<?php
session_start();
// login_main_check.php - 관리자 인증 확인용

// 에러 표시 (개발 시에만 사용, 운영 시에는 제거 권장)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 관리자 세션 인증 확인
if (empty($_SESSION["admin_id"]) || empty($_SESSION["admin_login"])) {
    header("Location: login.php");
    exit;
}
