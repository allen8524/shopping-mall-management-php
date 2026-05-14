<?php
    include "common.php";

    $id=(int)($_REQUEST["id"] ?? 0);
    $name = $_REQUEST["name"];
    $tel1 = $_REQUEST["tel1"];
    $tel2 = $_REQUEST["tel2"];
    $tel3 = $_REQUEST["tel3"];
    $sm = $_REQUEST["sm"];
    $birthday1 = $_REQUEST["birthday1"];
    $birthday2 = $_REQUEST["birthday2"];
    $birthday3 = $_REQUEST["birthday3"];
    $juso = $_REQUEST["juso"];

    $tel = sprintf("%-3s%-4s%-4s", $tel1, $tel2, $tel3);

    $birthday = sprintf("%04d-%02d-%02d", $birthday1, $birthday2, $birthday3);

    $sql="update juso set name='$name', tel='$tel', sm='$sm', birthday='$birthday',
                juso='$juso' where id=$id";
    $result=mysqli_query($db, $sql);
    if (!$result) { error_log("Juso update failed: " . mysqli_error($db)); exit("주소 수정 처리 중 오류가 발생했습니다."); }

    echo("<script>location.href='juso_list.php'</script>");
