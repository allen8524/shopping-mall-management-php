<?php
include "main_top.php";

// 1) 파라미터 초기화
$page   = isset($_REQUEST['page'])  ? (int)$_REQUEST['page']  : 1;
$sel1   = isset($_REQUEST['sel1'])  ? $_REQUEST['sel1']        : '';
$text1  = isset($_REQUEST['text1']) ? trim($_REQUEST['text1']) : '';
$id     = isset($_REQUEST['id'])    ? (int)$_REQUEST['id']     : 0;

if ($id < 1) {
    echo "<script>alert('잘못된 접근입니다.'); history.back();</script>";
    exit;
}

// 2) 원글 조회
$res = mysqli_query($db, "SELECT * FROM qa WHERE id = $id");
if (!$res || mysqli_num_rows($res) === 0) {
    echo "<script>alert('존재하지 않는 글입니다.'); history.back();</script>";
    exit;
}
$orig = mysqli_fetch_assoc($res);

// 3) pos1/pos2 설정
// pos1: 최상위 스레드 ID, pos2: 부모 글의 pos2 (깊이 표시)
$pos1 = $orig['pos1'] ? $orig['pos1'] : $orig['id'];
$pos2 = $orig['pos2'];  // 여기서 'A'를 붙이지 않습니다.

// 4) 기본값(prefill)
$title_prefill = 'Re: ' . $orig['title'];
$lines = explode("\n", $orig['contents']);
foreach ($lines as &$ln) {
    $ln = ':: ' . $ln;
}
$content_prefill = implode("\n", $lines) . "\n::\n";
?>
<!doctype html>
<html lang="kr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/my.css" rel="stylesheet">
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
  function Check_Value() {
    if (!form2.title.value) { alert('글제목을 입력하여 주십시요'); form2.title.focus(); return false; }
    if (!form2.name.value)  { alert('이름을 입력하여 주십시요'); form2.name.focus();  return false; }
    if (!form2.passwd.value){ alert('암호를 입력하여 주십시요'); form2.passwd.focus();return false; }
    form2.submit();
  }
  </script>

  <form name="form2" method="post" action="qa_insert.php">
    <input type="hidden" name="page"  value="<?= $page ?>">
    <input type="hidden" name="sel1"  value="<?= htmlspecialchars($sel1, ENT_QUOTES) ?>">
    <input type="hidden" name="text1" value="<?= htmlspecialchars($text1, ENT_QUOTES) ?>">
    <input type="hidden" name="pos1"  value="<?= $pos1 ?>">
    <input type="hidden" name="pos2"  value="<?= htmlspecialchars($pos2, ENT_QUOTES) ?>">

    <div class="row m-1 mb-0 justify-content-center">
      <div class="col text-center">

<h1>
    <span>Q</span>
    <span>&</span>
    <span>A</span>
</h1>

        <table class="table table-sm m-0">
          <tr>
            <td width="15%" class="bg-light">제목</td>
            <td class="px-2 text-start">
              <input type="text" name="title" size="85" value="<?= htmlspecialchars($title_prefill, ENT_QUOTES) ?>" class="form-control form-control-sm">
            </td>
          </tr>
          <tr>
            <td class="bg-light">작성자</td>
            <td class="px-2 text-start">
              <input type="text" name="name" size="20" class="form-control form-control-sm">
            </td>
          </tr>
          <tr>
            <td class="bg-light">비밀번호</td>
            <td class="px-2 text-start">
              <input type="password" name="passwd" size="20" class="form-control form-control-sm">
            </td>
          </tr>
          <tr>
            <td class="bg-light">내용</td>
            <td class="p-2 text-start">
              <textarea name="contents" rows="10" cols="85" class="form-control form-control-sm p-2 text-start"></textarea>
            </td>
          </tr>
        </table>

        <table width="100%" class="m-2">
          <tr>
            <td class="text-center pe-2">
              <a href="javascript:Check_Value();" class="btn btn-sm btn-dark text-white">저장</a>
              &nbsp;&nbsp;
              <a href="javascript:history.back();" class="btn btn-sm btn-dark text-white">목록</a>
            </td>
          </tr>
        </table>

      </div>
    </div>
  </form>

  <br><br><br>
<?php include "main_bottom.php"; ?>
</div>
</body>
</html>
