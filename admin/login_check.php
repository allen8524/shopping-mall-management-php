<?php
include "../common.php";

$adminid = $_POST["adminid"] ?? "";
$adminpw = $_POST["adminpw"] ?? "";

// 아이디·패스워드 검증
if ($adminid === $admin_id && $adminpw === $admin_pw) {
    // 로그인 성공: 쿠키 설정 후 관리자 회원 페이지로 이동
    setcookie("cookie_admin", "yes", time() + 3600, "/"); // 1시간 유효
    header("Location: member.php");
    exit;
}

// 로그인 실패: 쿠키 삭제 후 로그인 페이지로 이동
setcookie("cookie_admin", "", time() - 3600, "/");
header("Location: login.php?error=1");
exit;
