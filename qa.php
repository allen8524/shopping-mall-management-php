<?php
include "main_top.php";

// 1) 파라미터 초기화
$page  = isset($_REQUEST['page'])  ? (int)$_REQUEST['page']  : 1;
$sel1  = isset($_REQUEST['sel1'])  ? $_REQUEST['sel1']        : '';
$text1 = isset($_REQUEST['text1']) ? trim($_REQUEST['text1']) : '';

// 2) 검색 조건 생성
$where = '';
if ($text1 !== '') {
    $t = mysqli_real_escape_string($db, $text1);
    if ($sel1 == '1') {
        $where = " WHERE title LIKE '%{$t}%' OR contents LIKE '%{$t}%'";
    } elseif ($sel1 == '2') {
        $where = " WHERE title LIKE '%{$t}%'";
    } elseif ($sel1 == '3') {
        $where = " WHERE name LIKE '%{$t}%'";
    }
}

$args = "sel1={$sel1}&text1=" . urlencode($text1);
$result = mypagination(
    "SELECT * FROM qa{$where}
     ORDER BY (CASE WHEN pos1=0 THEN id ELSE pos1 END) ASC,
              pos2 ASC",
    $args,
    $count,
    $pagebar
);

?>
<!doctype html>
<html lang="kr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/my.css" rel="stylesheet">
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

  <div class="row m-1 mb-0 justify-content-center">
    <div class="col text-center">

<h1>
    <span>Q</span>
    <span>&</span>
    <span>A</span>
</h1>

      <table class="table table-sm m-0">
        <thead class="bg-light" style="height:35px;">
          <tr>
            <th width="10%">번호</th>
            <th width="45%">제목</th>
            <th width="15%">작성자</th>
            <th width="20%">작성일</th>
            <th width="10%">조회</th>
          </tr>
        </thead>
        <tbody>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
          <tr style="height:35px;">
            <td><?= $row['id'] ?></td>
            <td class="text-start">
              <?php 
                if ($row['pos2'] !== '') {
                  // pos2 문자열 길이만큼 들여쓰기
                  $depth = mb_strlen($row['pos2']);
                  echo str_repeat('&nbsp;&nbsp;', $depth),
                       '<img src="images/i_re.gif" border="0">&nbsp;';
                }
              ?>
              <a href="qa_read.php?
                         id=<?= $row['id'] ?>&
                         page=<?= $page ?>&
                         sel1=<?= $sel1 ?>&
                         text1=<?= urlencode($text1) ?>"
                 style="color:#0066CC;">
                <?= htmlspecialchars($row['title'], ENT_QUOTES) ?>
              </a>
            </td>
            <td><?= htmlspecialchars($row['name'], ENT_QUOTES) ?></td>
            <td><?= $row['writeday'] ?></td>
            <td><?= $row['count'] ?></td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>

      <!-- 검색 폼 및 새글 버튼 -->
      <table class="table table-sm table-borderless mt-1 m-0">
        <tr>
          <td>
            <form name="form2" method="post" action="qa.php">
              <div class="d-inline-flex">
                <div class="input-group input-group-sm">
                  <select name="sel1" class="form-select form-select-sm">
                    <option value="1" <?= $sel1=='1'?'selected':'' ?>>제목+내용</option>
                    <option value="2" <?= $sel1=='2'?'selected':'' ?>>제목</option>
                    <option value="3" <?= $sel1=='3'?'selected':'' ?>>작성자</option>
                  </select>
                  <input type="text" name="text1"
                         value="<?= htmlspecialchars($text1) ?>"
                         class="form-control bg-light form-control-sm">
                  <button type="submit"
                          class="btn btn-sm btn-outline-secondary">
                    검색
                  </button>
                </div>
              </div>
            </form>
          </td>
          <td class="text-end">
            <a href="qa_new.php"
               class="btn btn-sm btn-dark text-white">새글</a>
          </td>
        </tr>
      </table>

      <!-- 페이지바 -->
      <?= $pagebar ?>

    </div>
  </div>
<?php include "main_bottom.php"; ?>
</div>

</body>
</html>
