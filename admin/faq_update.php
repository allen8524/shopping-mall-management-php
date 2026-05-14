<?php
include "login_main_check.php";
include "../common.php";

// 1. POST 데이터 수집
$id     = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$ask    = isset($_POST['ask']) ? trim($_POST['ask']) : '';
$answer = isset($_POST['answer']) ? trim($_POST['answer']) : '';

// 2. 유효성 검사
if ($id == 0 || $ask === '' || $answer === '') {
    echo "<script>
        alert('모든 항목을 입력해주세요.');
        history.back();
    </script>";
    exit;
}

// 3. DB 수정
$sql = "UPDATE faq SET ask = '$ask', answer = '$answer' WHERE id = $id";
$result = mysqli_query($db, $sql);

// 4. 결과 처리
if ($result) {
    echo "<script>
        location.href='faq.php';
    </script>";
} else {
    echo "<script>
        alert('수정 실패: " . mysqli_error($db) . "');
        history.back();
    </script>";
}
