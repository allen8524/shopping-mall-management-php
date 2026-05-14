<?php
include "login_main_check.php";
include "../common.php";

$id = $_GET["id"] ?? "";
if (!$id) exit("주문번호 없음");

// 삭제 순서 중요: 먼저 상세항목(jumuns), 그 다음 본 주문(jumun)
$sql1 = "DELETE FROM jumuns WHERE jumun_id = '$id'";
$sql2 = "DELETE FROM jumun  WHERE id = '$id'";

mysqli_query($db, $sql1);
mysqli_query($db, $sql2);

// 삭제 후 목록 페이지로 리디렉션
echo "<script>location.href='jumun.php';</script>";
