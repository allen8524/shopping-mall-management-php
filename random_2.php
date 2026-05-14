<?php
include "common.php"; // DB 연결 설정 파일 포함

// coname이 '우알롱'인 제품 중 무작위로 하나 선택
$sql = "SELECT id FROM product WHERE coname = '디미트리블랙' ORDER BY RAND() LIMIT 1";
$res = mysqli_query($db, $sql);

if ($res && mysqli_num_rows($res) > 0) {
    $row = mysqli_fetch_assoc($res);
    // 선택된 제품 상세 페이지로 리다이렉트
    header("Location: product.php?id={$row['id']}");
    exit;
}

// 조건에 맞는 제품이 없을 경우 기본 상품 페이지로 이동
header("Location: product.php");
exit;
