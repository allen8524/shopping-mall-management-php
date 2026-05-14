<?php
session_start();
include "../common.php";

$adminid = $_POST["adminid"] ?? "";
$adminpw = $_POST["adminpw"] ?? "";

// 아이디·패스워드 검증
if ($adminid === $admin_id && $adminpw === $admin_pw) {
    session_regenerate_id(true);
    $_SESSION["admin_id"] = $adminid;
    $_SESSION["admin_login"] = true;

    header("Location: member.php");
    exit;
}

// 로그인 실패: 관리자 세션 및 legacy 쿠키 삭제 후 로그인 페이지로 이동
unset($_SESSION["admin_id"], $_SESSION["admin_login"]);
setcookie("cookie_admin", "", time() - 3600, "/");
header("Location: login.php?error=1");
exit;
