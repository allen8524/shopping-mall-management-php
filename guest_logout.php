<?php
// 비회원 쿠키 삭제
setcookie('guest_name', '', time() - 3600, '/');
setcookie('guest_email', '', time() - 3600, '/');

// 로그인 페이지로 이동
header("Location: jumun_login.php");
exit;
