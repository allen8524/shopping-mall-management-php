<?php
include "common.php";

$name  = trim($_GET['name']  ?? '');
$email = trim($_GET['email'] ?? '');

$found_uid = null;
if ($name !== '' && $email !== '') {
    $name_esc  = mysqli_real_escape_string($db, $name);
    $email_esc = mysqli_real_escape_string($db, $email);
    $sql = "SELECT uid FROM member WHERE name='{$name_esc}' AND email='{$email_esc}' LIMIT 1";
    $res = mysqli_query($db, $sql);
    if ($res && mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        $found_uid = $row['uid'];
    }
}
?>
<!doctype html>
<html lang="kr" style="overflow:hidden">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link  href="css/bootstrap.min.css" rel="stylesheet">
    <link  href="css/my.css" rel="stylesheet">
    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="container-fluid">

    <!-- 페이지 제목 -->
    <div class="row m-0">
        <div class="col bg-light" align="center">
            <h4 class="m-2">회원 ID 확인</h4>
        </div>    
    </div>    

    <div class="row">
        <div class="col" align="center">
            <hr style="height:2px" class="my-0">
            <br><br>

            <?php if ($found_uid !== null): ?>
                문의하신 아이디는 <b><?= htmlspecialchars($found_uid) ?></b>입니다.
            <?php else: ?>
                문의하신 정보는 없습니다.
            <?php endif; ?>

            <br><br><br>
            <a href="javascript:self.close();" class="btn btn-sm btn-dark text-white myfont">확 인</a>
        </div>
    </div>

</div>

</body>
</html>
