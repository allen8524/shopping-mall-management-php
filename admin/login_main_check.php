<?php
// login_main_check.php - 관리자 인증 확인용

// 에러 표시 (개발 시에만 사용, 운영 시에는 제거 권장)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 관리자 인증 쿠키 확인
if (!isset($_COOKIE['cookie_admin']) || $_COOKIE['cookie_admin'] !== 'yes') {
    // 로그인 페이지로 리디렉션
    header("Location: login.php");
    exit;
}
?>
