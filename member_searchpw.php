<?php
include "common.php";

$userid = trim($_GET['userid'] ?? '');
$name   = trim($_GET['name']   ?? '');

$found_pwd = null;
if ($userid !== '' && $name !== '') {
    $uid_esc  = mysqli_real_escape_string($db, $userid);
    $name_esc = mysqli_real_escape_string($db, $name);
    $sql = "SELECT pwd 
            FROM member 
            WHERE uid='{$uid_esc}' 
              AND name='{$name_esc}' 
            LIMIT 1";
    $res = mysqli_query($db, $sql);
    if ($res && mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        $found_pwd = $row['pwd'];
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

<div class="container-fulid">

    <!-- 페이지 제목 -->
    <div class="row m-0">
        <div class="col bg-light" align="center">
            <h4 class="m-2">회원 암호 확인</h4>
        </div>    
    </div>    

    <div class="row">
        <div class="col" align="center">
            <hr style="height:2px" class="my-0">
            <br><br>

            <?php if ($found_pwd !== null): ?>
                문의하신 암호는 <b><?= htmlspecialchars($found_pwd) ?></b>입니다.
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
