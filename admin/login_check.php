<?php
include "../common.php";

$adminid = $_REQUEST["adminid"];
$adminpw = $_REQUEST["adminpw"];

// 아이디·패스워드 검증
if ($adminid === $admin_id && $adminpw === $admin_pw) {
    // 로그인 성공: 쿠키 설정
    setcookie("cookie_admin", "yes", time() + 3600, "/"); // 1시간 유효
    echo("<script>location.href='member.php'</script>");
} else {
    // 로그인 실패: 쿠키 삭제 및 경고 후 로그인 페이지로
    setcookie("cookie_admin", "", time() - 3600, "/");
}
// (검증 로직 후)
if ($invalid) {
  header("Location: login.php?error=1");
  exit;
}
header("Location: login.php?error=1");
exit;


?>