<?php
    include "common.php";

    $id=(int)($_REQUEST["id"] ?? 0);

    $sql="delete from juso where id=$id ";
    $result=mysqli_query($db, $sql);
    if (!$result) { error_log("Juso delete failed: " . mysqli_error($db)); exit("주소 삭제 처리 중 오류가 발생했습니다."); }

    echo("<script>location.href='juso_list.php'</script>");
