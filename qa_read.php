<?php
include "main_top.php";

// 1) 파라미터 & DB 연결
$page   = isset($_REQUEST['page'])   ? (int)$_REQUEST['page']   : 1;
$sel1   = isset($_REQUEST['sel1'])   ? $_REQUEST['sel1']         : '';
$text1  = isset($_REQUEST['text1'])  ? trim($_REQUEST['text1'])  : '';
$id     = isset($_REQUEST['id'])     ? (int)$_REQUEST['id']      : 0;

if ($id < 1) {
    echo "<script>alert('잘못된 접근입니다.'); history.back();</script>";
    exit;
}

// 2) 조회수 증가
mysqli_query($db, "UPDATE qa SET `count` = `count` + 1 WHERE id = {$id}");

// 3) 레코드 가져오기
$result = mysqli_query($db, "SELECT * FROM qa WHERE id = {$id}");
if (!$result || mysqli_num_rows($result) === 0) {
    echo "<script>alert('존재하지 않는 글입니다.'); history.back();</script>";
    exit;
}
$row = mysqli_fetch_assoc($result);
?>
<!doctype html>
<html lang="kr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link  href="css/bootstrap.min.css" rel="stylesheet">
  <link  href="css/my.css" rel="stylesheet">
  <script src="js/jquery-3.7.1.min.js"></script>
  <script src="js/bootstrap.bundle.min.js"></script>
  		<style>
	h1 {
		height: 100px;
		margin-top: 30px;
	}
	h1 span {
		position: relative;
		display: inline-block;
		-webkit-font-smoothing: antialiased;
		text-shadow: 0 1px 0 #ccc, 
					 0 2px 0 #ccc, 
					 0 3px 0 #ccc,  
					 0 4px 0 #ccc, 
					 0 5px 0 #ccc, 
					 0 6px 0 transparent,
					 0 7px 0 transparent,
					 0 8px 0 transparent,
					 0 9px 0 transparent,
					 0 15px 5px rgba(0,0,0,0.4);
		animation: bounce 0.2s ease infinite alternate;
	}
	h1 span:nth-child(2){animation-delay: 0.1s;}
	h1 span:nth-child(3){animation-delay: 0.2s;}
	h1 span:nth-child(4){animation-delay: 0.3s;}
	h1 span:nth-child(5){animation-delay: 0.4s;}
	h1 span:nth-child(6){animation-delay: 0.5s;}
	h1 span:nth-child(7){animation-delay: 0.6s;}
	h1 span:nth-child(8){animation-delay: 0.7s;}

	@keyframes bounce {
		100% {
			top: -3px;
			text-shadow: 0 1px 0 #ccc,
						 0 1px 0 #ccc,
						 0 3px 0 #ccc,
						 0 4px 0 #ccc,
						 0 5px 0 #ccc,
						 0 6px 0 transparent,
						 0 7px 0 transparent,
						 0 8px 0 transparent,
						 0 9px 0 transparent,
						 0 30px 5px rgba(0,0,0,0.4);
		}
	}
	</style>
</head>
<body>
<div class="container">

  <script>
    function Go_Reply() {
      form2.action = "qa_reply.php";
      form2.submit();
    }
    function Check_Modify() {
      if (!form2.passwd.value) {
        alert('암호를 입력하세요.');
        form2.passwd.focus();
        return false;
      }
      form2.action = "qa_edit.php";
      form2.submit();
    }
    function Check_Delete() {
      if (!form2.passwd.value) {
        alert('암호를 입력하세요.');
        form2.passwd.focus();
        return false;
      }
      form2.action = "qa_delete.php";
      form2.submit();
    }
  </script>

  <div class="row m-1 mb-0 justify-content-center">
    <div class="col text-center">

<h1>
    <span>Q</span>
    <span>&</span>
    <span>A</span>
</h1>

      <table class="table table-sm m-0">
        <tr height="35">
          <td width="15%" class="bg-light">제목</td>
          <td class="px-2 text-start"><?= htmlspecialchars($row['title'], ENT_QUOTES) ?></td>
        </tr>
        <tr height="35">
          <td class="bg-light">작성자</td>
          <td class="px-2 text-start"><?= htmlspecialchars($row['name'], ENT_QUOTES) ?></td>
        </tr>
        <tr height="35">
          <td class="bg-light">작성일</td>
          <td class="px-2 text-start"><?= substr($row['writeday'], 0, 10) ?></td>
        </tr>
        <tr height="35">
          <td class="bg-light">조회</td>
          <td class="px-2 text-start"><?= $row['count'] ?></td>
        </tr>
        <tr>
          <td valign="top" class="bg-light pt-2">내용</td>
          <td height="250" valign="top" class="p-2 text-start">
            <?= nl2br(htmlspecialchars($row['contents'], ENT_QUOTES)) ?>
          </td>
        </tr>
      </table>

      <!-- form2 시작 -->
      <form name="form2" method="post" action="">
        <input type="hidden" name="page"  value="<?= $page ?>">
        <input type="hidden" name="sel1"  value="<?= htmlspecialchars($sel1) ?>">
        <input type="hidden" name="text1" value="<?= htmlspecialchars($text1, ENT_QUOTES) ?>">
        <input type="hidden" name="id"    value="<?= $id ?>">

        <table width="100%" class="m-1">
          <tr>
            <td align="left" valign="top">
              <div class="d-inline-flex">
                <div class="input-group input-group-sm">
                  <span class="input-group-text" style="font-size:12px;">암호</span>
                  <input type="password" name="passwd" size="7"
                         class="form-control bg-light" style="font-size:12px;">
                </div>
              </div>
            </td>
            <td align="right" valign="top">
              <a href="javascript:Go_Reply();" class="btn btn-sm btn-dark text-white">답글</a>&nbsp;
              <a href="javascript:Check_Modify();" class="btn btn-sm btn-dark text-white">수정</a>&nbsp;
              <a href="javascript:Check_Delete();" class="btn btn-sm btn-dark text-white">삭제</a>&nbsp;
              <a href="javascript:history.back()" class="btn btn-sm btn-dark text-white">목록</a>&nbsp;
            </td>
          </tr>
        </table>
      </form>
      <!-- form2 끝 -->

    </div>
  </div>

  <br><br><br>

<?php include "main_bottom.php"; ?>
</div>
</body>
</html>
