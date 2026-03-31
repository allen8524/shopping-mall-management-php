<?php
include "login_main_check.php";
include "../common.php";

// POST 데이터 가져오기
$ask    = isset($_POST['ask']) ? trim($_POST['ask']) : '';
$answer = isset($_POST['answer']) ? trim($_POST['answer']) : '';

// 필수 입력 검사
if ($ask === '' || $answer === '') {
    echo "<script>
        alert('질문과 답변을 모두 입력해주세요.');
        history.back();
    </script>";
    exit;
}

// DB에 INSERT
$sql = "INSERT INTO faq (ask, answer) VALUES ('$ask', '$answer')";
$result = mysqli_query($db, $sql);

if ($result) {
    echo "<script>
        location.href='faq.php';
    </script>";
} else {
    echo "<script>
        alert('등록 실패: " . mysqli_error($db) . "');
        history.back();
    </script>";
}
?>
