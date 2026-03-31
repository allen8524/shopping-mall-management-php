<?php
ob_start();
session_start();
include "main_top.php";

$where = '';
$is_guest = false;

// 1) 회원 로그인 시 처리
if (!empty($_COOKIE['cookie_id'])) {
    $id = mysqli_real_escape_string($db, $_COOKIE['cookie_id']);
    $where = "WHERE member_id = '$id'";
}
// 2) 비회원 POST 요청 (이름 + 이메일로 확인 후 쿠키 저장)
else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if ($name && $email) {
        $name_esc  = mysqli_real_escape_string($db, $name);
        $email_esc = mysqli_real_escape_string($db, $email);

        $sql = "SELECT COUNT(*) AS cnt FROM jumun WHERE o_name='$name_esc' AND o_email='$email_esc'";
        $res = mysqli_query($db, $sql);
        $cnt = mysqli_fetch_assoc($res)['cnt'] ?? 0;

        if ($cnt > 0) {
            // 쿠키 저장
            setcookie('guest_name', $name, time() + 3600, '/');
            setcookie('guest_email', $email, time() + 3600, '/');
            // 새로고침으로 GET 전환
            header("Location: jumun.php");
            exit;
        } else {
            echo "<script>alert('해당 정보로 주문 내역을 찾을 수 없습니다.'); history.back();</script>";
            exit;
        }
    }
}
// 3) 비회원 쿠키 로그인 상태 (페이지 새로고침 포함)
else if (!empty($_COOKIE['guest_name']) && !empty($_COOKIE['guest_email'])) {
    $name  = mysqli_real_escape_string($db, $_COOKIE['guest_name']);
    $email = mysqli_real_escape_string($db, $_COOKIE['guest_email']);
    $where = "WHERE o_name='$name' AND o_email='$email'";
    $is_guest = true;
}
// 4) 로그인 정보 없음
else {
    header("Location: jumun_login.php");
    exit;
}

// 5) 주문 목록 로딩
$page   = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$list   = 10;
$offset = ($page - 1) * $list;

$count_sql = "SELECT COUNT(*) AS cnt FROM jumun $where";
$count_res = mysqli_query($db, $count_sql);
$total_count = mysqli_fetch_assoc($count_res)['cnt'] ?? 0;
$total_page  = ceil($total_count / $list);

$sql = "SELECT * FROM jumun $where ORDER BY jumunday DESC LIMIT $offset, $list";
$result = mysqli_query($db, $sql);

$state_names = ["주문신청", "주문확인", "입금확인", "배송중", "주문완료", "주문취소"];
?>
<!doctype html>
<html lang="kr">
<head>
	<meta charset="utf-8">
	<title>4910 - 주문조회</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/my.css" rel="stylesheet">
	<script src="js/jquery-3.7.1.min.js"></script>
	<script src="js/bootstrap.bundle.min.js"></script>
		<style>
    td {
        font-size: 17px;
    }
</style>
</head>
<body>
<div class="container">
	<div class="row m-1 mt-4 mb-0">
		<div class="col text-center">
			<h4 class="m-3">주문조회</h4>
			<hr class="m-0">
			<table class="table table-sm mb-4">
				<thead>
					<tr height="40" class="bg-light">
						<th width="15%">주문일</th>
						<th width="15%">주문번호</th>
						<th width="35%">제품정보</th>
						<th width="15%">결제금액</th>
						<th width="20%">주문상태</th>
					</tr>
				</thead>
				<tbody>
				<?php if ($total_count > 0): ?>
					<?php while ($row = mysqli_fetch_assoc($result)): ?>
						<tr height="40">
							<td><?= substr($row['jumunday'], 0, 10) ?></td>
							<td>
								<a href="jumun_info.php?id=<?= $row['id'] ?>" style="font-size:14px;color:#0066CC;">
									<?= $row['id'] ?>
								</a>
							</td>
							<td align="left"><?= htmlspecialchars($row['product_names'], ENT_QUOTES) ?></td>
							<td align="right"><?= number_format($row['totalprice']) ?>원</td>
							<td>
								<font color="<?= ((int)$row['state'] === 5) ? '#D06404' : '#0066CC' ?>">
									<?= $state_names[(int)$row['state']] ?>
								</font>
							</td>
						</tr>
					<?php endwhile; ?>
				<?php else: ?>
					<tr><td colspan="5" class="text-center">조회된 주문이 없습니다.</td></tr>
				<?php endif; ?>
				</tbody>
			</table>
			<?php if (!empty($_COOKIE['guest_name']) && !empty($_COOKIE['guest_email'])): ?>
	<div class="row mt-3">
		<div class="col text-center">
			<a href="guest_logout.php" class="btn btn-sm btn-outline-secondary">비회원 로그아웃</a>
		</div>
	</div>
<?php endif; ?>
		</div>
	</div>

	<!-- Pagination -->
	<div class="row mb-4">
		<div class="col">
			<nav aria-label="Page navigation">
				<ul class="pagination pagination-sm justify-content-center">
					<?php for ($i = 1; $i <= $total_page; $i++): ?>
						<li class="page-item <?= ($i === $page) ? 'active' : '' ?>">
							<a class="page-link" href="jumun.php?page=<?= $i ?>"><?= $i ?></a>
						</li>
					<?php endfor; ?>
				</ul>
			</nav>
		</div>
	</div>
	<?php include "main_bottom.php"; ?>
</div>
</body>
</html>
