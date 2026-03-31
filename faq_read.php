<?php
include "main_top.php";

// 1. ID 가져오기
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id == 0) {
    echo "<script>alert('잘못된 접근입니다.'); history.back();</script>";
    exit;
}

// 2. DB에서 FAQ 조회
$sql = "SELECT * FROM faq WHERE id = $id";
$result = mysqli_query($db, $sql);
if (!$result || mysqli_num_rows($result) == 0) {
    echo "<script>alert('FAQ 항목을 찾을 수 없습니다.'); history.back();</script>";
    exit;
}
$row = mysqli_fetch_array($result);
$ask = htmlspecialchars($row['ask']);
$answer = nl2br(htmlspecialchars($row['answer']));
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

<div class="row m-1 mb-0 justify-content-center">
	<div class="col" align="center">

<h1>
    <span>Q</span>
    <span>&</span>
    <span>A</span>
</h1>

		<hr style="height:2px" class="mb-0">
		<table class="table table-sm m-0" style="text-align:left">
			<tr height="35" class="bg-light">
				<td class="ps-3"><?= $ask ?></td>
			</tr>
			<tr height="35">
				<td class="p-3"><?= $answer ?></td>
			</tr>
		</table>

		<br>
		<a href="javascript:history.back();" class="btn btn-sm btn-dark text-white">&nbsp;돌아가기&nbsp;</a>

	</div>
</div>

<br><br><br><br><br><br>

<?php include "main_bottom.php"; ?>

</div>
</body>
</html>
