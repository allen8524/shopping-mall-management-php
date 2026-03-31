<?php
	include "login_main_check.php";
    include "../common.php";
    $opt_id = isset($_REQUEST["id"]) ? (int)$_REQUEST["id"] : 0;
    $text1  = $_REQUEST["text1"] ?? "";

    // 옵션명 조회
    $sql_name    = "SELECT name FROM opt WHERE id = {$opt_id}";
    $result_name = mysqli_query($db, $sql_name);
    $row_name    = mysqli_fetch_array($result_name);

    // 소옵션 페이징: id 파라미터 추가
    $sql_opts = "SELECT * FROM opts WHERE opt_id = {$opt_id} ORDER BY id";
    // ↓ 여기 id를 포함
    $args   = "id={$opt_id}&text1=" . urlencode($text1);
    $result = mypagination($sql_opts, $args, $count, $pagebar);
?>

<!doctype html>
<html lang="kr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>4910</title>
    <link rel="icon" href="../images/4910_top.ico" type="image/x-icon">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/my.css" rel="stylesheet">
    <script src="../js/jquery-3.7.1.min.js"></script>
    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../js/my.js"></script>
</head>
<body>

<div class="container">
    <script> document.write(admin_menu());</script>

    <div class="row mx-1 justify-content-center">
        <div class="col-sm-10" align="center">

            <h4 class="m-0">소옵션</h4>
            <div class="row myfs13">
                <div class="col" align="left" style="padding-top:8px">
                    &nbsp;옵션명 : <font color="red"><?= htmlspecialchars($row_name["name"]) ?></font>
                </div>
                <div class="col" align="right">
                    <a href="opts_new.php?id=<?= $opt_id ?>" class="btn btn-sm mycolor1 myfs12">소옵션 추가</a>
                </div>
            </div>

            <table class="table table-sm table-bordered table-hover my-1">
                <tr class="bg-light">
                    <td width="25%">소옵션 번호</td>
                    <td>소옵션명</td>
                    <td width="25%">수정 / 삭제</td>
                </tr>
<?php
    // mysqli_result 객체를 while로 순회
    while($row = mysqli_fetch_array($result)) {
?>
                <tr>
                    <td><?= $row["id"] ?></td>
                    <td><?= htmlspecialchars($row["name"]) ?></td>
                    <td>
                        <a href="opts_edit.php?id=<?= $row["id"] ?>&id1=<?= $opt_id ?>" class="btn btn-sm mybutton-blue">수정</a>
                        <a href="opts_delete.php?id=<?= $row["id"] ?>&id1=<?= $opt_id ?>"
                           class="btn btn-sm mybutton-red"
                           onclick="return confirm('삭제할까요?');">삭제</a>
                    </td>
                </tr>
<?php
    }
?>
            </table>

            <!-- 페이지네이션 -->
            <div class="d-flex justify-content-center">
                <?= $pagebar ?>
            </div>

            <a href="opt.php" class="btn btn-sm btn-outline-dark my-2">&nbsp;돌아가기&nbsp;</a>
        </div>
    </div>
</div>

</body>
</html>
