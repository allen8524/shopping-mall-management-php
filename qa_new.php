<?php
    include "main_top.php";

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
<!-------------------------------------------------------------------------------------------->	

<!-------------------------------------------------------------------------------------------->	
<!-- 시작 : 다른 웹페이지 삽입할 부분 -->
<!-------------------------------------------------------------------------------------------->	

<!--  현재 페이지 자바스크립  -------------------------------------------->
<script >

	function Check_Value() {
		if (!form2.title.value) {
				alert('글제목을 입력하여 주십시요');
				form1.title.focus();
				return;
		}
	  if (!form2.name.value) {
				alert('이름을 입력하여 주십시요');
				form2.name.focus();
				return;
		}
	  if (!form2.passwd.value) {
				alert('암호를 입력하여 주십시요');
				form2.passwd.focus();
				return;
		}
		form2.submit();
	}

</script>

<!--  form2 시작 -->
<form name="form2" method="post" action="qa_insert.php">

<div class="row m-1  mb-0 justify-content-center">
	<div class="col" align="center">

<h1>
    <span>Q</span>
    <span>&</span>
    <span>A</span>
</h1>

		<hr style="height:2px" class="mb-0">
		<table class="table table-sm m-0">
			<tr>
				<td width="15%" class="bg-light">제목</td>
				<td align="left" class="px-2">
					<div class="d-inline-flex">
						<input type="text" name="title" size="85" 
							class="form-control form-control-sm">				
					</div>
				</td>
			</tr>
			<tr>
				<td class="bg-light">작성자</td>
				<td align="left" class="px-2">
					<div class="d-inline-flex">
						<input type="text" name="name" size="20" 
							class="form-control form-control-sm">				
					</div>
				</td>
			</tr>
			<tr>
				<td class="bg-light">비밀번호</td>
				<td align="left" class="px-2">
					<div class="d-inline-flex">
						<input type="password" name="passwd" size="20" 
							class="form-control form-control-sm"">				
					</div>
				</td>
			</tr>
			<tr>
				<td class="bg-light">내용</td>
				<td align="left" class="p-2">
					<textarea name="contents" rows="10" cols="85" 
						class="form-control form-control-sm p-2"></textarea>
				</td>
			</tr>
		</table>

		<table width="100%" class="m-2">
			<tr>
				<td align="center" class="pe-2">
					<a href="javascript:Check_Value();" 
						class="btn btn-sm btn-dark text-white myfont">저장</a>&nbsp;&nbsp;
					<a href="javascript:history.back()" 
						class="btn btn-sm btn-dark text-white myfont">목록</a>
				</td>
			</tr>
		</table>

	</div>
</div>

</form>

<br><br><br>

<!-------------------------------------------------------------------------------------------->	
<!-- 끝 : 다른 웹페이지 삽입할 부분 -->
<!-------------------------------------------------------------------------------------------->	

<?php
    include "main_bottom.php";

?>
<!-------------------------------------------------------------------------------------------->	
</div>

</body>
</html>
