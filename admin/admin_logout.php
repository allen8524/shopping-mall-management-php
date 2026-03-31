<?php
// 관리자 로그아웃 처리
// 쿠키 삭제: 과거 유효시간으로 설정
setcookie("cookie_admin", "", time() - 3600, "/");

// 로그인 페이지로 리다이렉트
header("Location: login.php");
exit;
?>
