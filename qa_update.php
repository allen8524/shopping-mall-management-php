<?php
include "common.php";

// 1) 폼 POST 값 가져오기
$id       = isset($_POST['id'])       ? (int)$_POST['id']                     : 0;
$title    = isset($_POST['title'])    ? trim($_POST['title'])                  : '';
$name     = isset($_POST['name'])     ? trim($_POST['name'])                   : '';
$passwd   = isset($_POST['passwd'])   ? trim($_POST['passwd'])                 : '';
$contents = isset($_POST['contents']) ? trim($_POST['contents'])               : '';
$page     = isset($_POST['page'])     ? (int)$_POST['page']                    : 1;
$sel1     = isset($_POST['sel1'])     ? $_POST['sel1']                          : '';
$text1    = isset($_POST['text1'])    ? trim($_POST['text1'])                   : '';

// 2) 기본 검증
if ($id < 1 || $title === '' || $name === '' || $passwd === '') {
    echo "<script>
            alert('필수 항목이 비어있습니다.');
            history.back();
          </script>";
    exit;
}

// 3) 암호 확인
$sql  = "SELECT passwd FROM qa WHERE id = $id";
$res  = mysqli_query($db, $sql);
if (!$res || mysqli_num_rows($res) === 0) {
    echo "<script>alert('존재하지 않는 글입니다.'); history.back();</script>";
    exit;
}
$row = mysqli_fetch_assoc($res);
if ($row['passwd'] !== $passwd) {
    echo "<script>alert('암호가 일치하지 않습니다.'); history.back();</script>";
    exit;
}

// 4) UPDATE 실행
$titleEsc    = mysqli_real_escape_string($db, $title);
$nameEsc     = mysqli_real_escape_string($db, $name);
$contentsEsc = mysqli_real_escape_string($db, $contents);

$sql = "
    UPDATE qa
       SET title    = '$titleEsc',
           name     = '$nameEsc',
           contents = '$contentsEsc'
     WHERE id       = $id
";
if (!mysqli_query($db, $sql)) {
    error_log('QA update failed: ' . mysqli_error($db));
    exit('게시글 수정 처리 중 오류가 발생했습니다.');
}

// 5) 목록으로 리다이렉트 (검색/페이징 유지)
$qs = "page=$page";
if ($sel1 !== '')  $qs .= "&sel1=" . urlencode($sel1);
if ($text1 !== '') $qs .= "&text1=" . urlencode($text1);

header("Location: qa.php?$qs");
exit;
