<?php
include "common.php"; // DB 연결 포함

// 1) 파라미터 가져오기
$id     = isset($_POST['id'])     ? (int)$_POST['id']     : 0;
$passwd = isset($_POST['passwd']) ? trim($_POST['passwd']) : '';
$page   = isset($_POST['page'])   ? (int)$_POST['page']   : 1;
$sel1   = isset($_POST['sel1'])   ? $_POST['sel1']         : '';
$text1  = isset($_POST['text1'])  ? trim($_POST['text1'])  : '';

// 2) 필수값 체크
if ($id < 1 || $passwd === '') {
    echo "<script>alert('잘못된 요청입니다.'); history.back();</script>";
    exit;
}

// 3) 암호 확인 및 원글 정보 조회
$sql = "SELECT pos1, pos2, passwd FROM qa WHERE id = $id";
$res = mysqli_query($db, $sql);
if (!$res || mysqli_num_rows($res) === 0) {
    echo "<script>alert('존재하지 않는 글입니다.'); history.back();</script>";
    exit;
}
$row = mysqli_fetch_assoc($res);
if ($row['passwd'] !== $passwd) {
    echo "<script>alert('암호가 일치하지 않습니다.'); history.back();</script>";
    exit;
}

$root   = (int)$row['pos1'];
$prefix = $row['pos2']; // 삭제 대상 글의 pos2

// 4) 대상 글 및 하위 댓글 일괄 삭제
$sql = "
    DELETE FROM qa
     WHERE pos1 = $root
       AND pos2 LIKE '" . mysqli_real_escape_string($db, $prefix) . "%'
";
if (!mysqli_query($db, $sql)) {
    exit("DB 에러(DELETE): " . mysqli_error($db));
}

// 5) 목록으로 이동 (검색·페이징 유지)
$qs = 'page=' . $page;
if ($sel1  !== '') $qs .= '&sel1='  . urlencode($sel1);
if ($text1 !== '') $qs .= '&text1=' . urlencode($text1);

header('Location: qa.php?' . $qs);
exit;
