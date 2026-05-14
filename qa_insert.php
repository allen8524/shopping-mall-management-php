<?php
include "common.php"; // DB 연결 포함
// 에러 표시
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 1) POST 값 가져오기
$page          = isset($_POST['page'])     ? (int)$_POST['page']    : 1;
$sel1          = isset($_POST['sel1'])     ? $_POST['sel1']         : '';
$text1         = isset($_POST['text1'])    ? trim($_POST['text1'])  : '';
$title         = isset($_POST['title'])    ? trim($_POST['title'])  : '';
$name          = isset($_POST['name'])     ? trim($_POST['name'])   : '';
$passwd        = isset($_POST['passwd'])   ? trim($_POST['passwd']) : '';
$contents_raw  = isset($_POST['contents']) ? trim($_POST['contents']): '';
// 새글이면 pos1=0, 답글이면 부모 쓰레드 ID 전달
$pos1          = isset($_POST['pos1'])     ? (int)$_POST['pos1']    : 0;
// 답글일 때 부모 pos2
$parent_pos2   = isset($_POST['pos2'])     ? trim($_POST['pos2'])   : '';

// 2) 필수값 검증
if ($title === '' || $name === '' || $passwd === '') {
    echo "<script>alert('제목, 작성자, 비밀번호는 필수 입력사항입니다.'); history.back();</script>";
    exit;
}

// 3) 인용문(:: ) 제거
$contents_clean = preg_replace('/^::\s*/m', '', $contents_raw);

// 4) 이스케이프 처리
$t = mysqli_real_escape_string($db, $title);
$n = mysqli_real_escape_string($db, $name);
$p = mysqli_real_escape_string($db, $passwd);
$c = mysqli_real_escape_string($db, $contents_clean);

if ($pos1 === 0) {
    // └── 루트 새글
    $sql = "INSERT INTO qa (pos1, pos2, title, name, passwd, writeday, `count`, contents) VALUES (0, '', '$t', '$n', '$p', NOW(), 0, '$c')";
    if (!mysqli_query($db, $sql)) {
        error_log('QA root insert failed: ' . mysqli_error($db));
        exit('게시글 등록 처리 중 오류가 발생했습니다.');
    }
    // 삽입된 ID를 pos1에 업데이트
    $new_id = mysqli_insert_id($db);
    if (!mysqli_query($db, "UPDATE qa SET pos1 = $new_id WHERE id = $new_id")) {
        error_log('QA pos1 update failed: ' . mysqli_error($db));
        exit('게시글 등록 처리 중 오류가 발생했습니다.');
    }
} else {
    // └── 답글: A, B, AA, AB...
    $depth      = mb_strlen($parent_pos2, 'UTF-8');
    $next_len   = $depth + 1;
    $prefix     = mysqli_real_escape_string($db, $parent_pos2);
    // 마지막 문자(max_ch) 조회
    $sql = "SELECT MAX(RIGHT(pos2,1)) AS max_ch FROM qa WHERE pos1 = $pos1 AND CHAR_LENGTH(pos2) = $next_len AND pos2 LIKE '{$prefix}%'
    ";
    $res = mysqli_query($db, $sql);
    if (!$res) {
        error_log('QA reply position select failed: ' . mysqli_error($db));
        exit('게시글 등록 처리 중 오류가 발생했습니다.');
    }
    $row = mysqli_fetch_assoc($res);
    $last = $row['max_ch'];
    $next = $last ? chr(ord($last) + 1) : 'A';
    $new_pos2 = $parent_pos2 . $next;

    // 답글 INSERT
    $sql = "INSERT INTO qa (pos1, pos2, title, name, passwd, writeday, `count`, contents) VALUES ($pos1, '$new_pos2', '$t', '$n', '$p', NOW(), 0, '$c')";
    if (!mysqli_query($db, $sql)) {
        error_log('QA reply insert failed: ' . mysqli_error($db));
        exit('게시글 등록 처리 중 오류가 발생했습니다.');
    }
}

// 5) 리스트로 리다이렉트
$qs = 'page=' . $page;
if ($sel1 !== '')  $qs .= '&sel1='  . urlencode($sel1);
if ($text1 !== '') $qs .= '&text1=' . urlencode($text1);
header('Location: qa.php?' . $qs);
exit;
