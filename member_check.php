<?php
    include "common.php";

    $uid = $_REQUEST["uid"]; // 사용자 아이디
	$pwd = $_REQUEST["pwd"]; // 사용자 비밀번호

    $sql = "SELECT id FROM member WHERE uid='$uid' AND pwd='$pwd'"; // SQL 쿼리문

    $result = mysqli_query($db, $sql);

    $row = mysqli_fetch_array($result); // 결과 값을 배열로 가져오기
    $count = mysqli_num_rows($result); // 결과 행의 개수

    if ($count > 0)
    {
        setcookie("cookie_id", $row['id']); // 쿠키 설정
        echo("<script>location.href='index.html'</script>"); // index.html로 이동
    }
    // 로그인 실패
    else {
        echo("<script>alert('로그인 정보가 일치하지 않습니다.'); location.href='member_login.php'</script>"); // 로그인 페이지로 이동
    }
