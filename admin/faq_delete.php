<?php
include "login_main_check.php";
include "../common.php";

// 1. id 파라미터 검사
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id == 0) {
    echo "<script>
        alert('잘못된 접근입니다.');
        history.back();
    </script>";
    exit;
}

// 2. 삭제 쿼리 실행
$sql = "DELETE FROM faq WHERE id = $id";
$result = mysqli_query($db, $sql);

// 3. 결과 처리
if ($result) {
    echo "<script>
        location.href='faq.php';
    </script>";
} else {
    echo "<script>
        alert('삭제 실패: " . mysqli_error($db) . "');
        history.back();
    </script>";
}
