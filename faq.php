<?php
include "main_top.php";
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
		animation: bounce 0.3s ease infinite alternate;
		
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

<!-- 시작 : FAQ 목록 -->
<div class="row m-1 mb-0 justify-content-center">
	<div class="col" align="center">

<h1>
    <span>F</span>
    <span>A</span>
    <span>Q</span>
</h1>

		<hr style="height:2px" class="mb-0">
		<table class="table table-sm m-0">
			<tr height="30" class="bg-light">
				<td width="10%">번호</td>
				<td width="90%">제목</td>
			</tr>

<?php
$sql = "SELECT id, ask FROM faq ORDER BY id ASC";
$result = mysqli_query($db, $sql);
if ($result) {
	while ($row = mysqli_fetch_array($result)) {
		$id = $row["id"];
		$ask = htmlspecialchars($row["ask"]);
		echo "<tr height='35'>
				<td>{$id}</td>
				<td align='left'>
					<a href='faq_read.php?id={$id}' style='color:#0066CC'>{$ask}</a>
				</td>
			  </tr>";
	}
} else {
	echo "<tr><td colspan='2'>FAQ를 불러올 수 없습니다.</td></tr>";
}
?>

		</table>
	</div>
</div>

<br><br><br><br><br><br>

<!-- 끝 : FAQ 목록 -->

<?php include "main_bottom.php"; ?>
</div>
</body>
</html>
