<?php
session_start();
// 관리자 로그아웃 처리
$_SESSION = [];

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), "", time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
}

session_destroy();

// legacy 쿠키 삭제: 과거 유효시간으로 설정
setcookie("cookie_admin", "", time() - 3600, "/");

// 로그인 페이지로 리다이렉트
header("Location: login.php");
exit;
?>
