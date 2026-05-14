<?php
include "login_main_check.php";
include "../common.php";

// 1. id 검증
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) exit('상품 ID 없음');

// 2. Prepared Statement로 상품 조회
$sql = "SELECT * FROM product WHERE id = ?";
$stmt = mysqli_prepare($db, $sql);
if (!$stmt) {
    exit('상품 조회 중 오류가 발생했습니다.');
}
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if (!$result || mysqli_num_rows($result) === 0) exit('존재하지 않는 상품입니다.');
$row = mysqli_fetch_array($result);
mysqli_stmt_close($stmt);
?>
<!doctype html>
<html lang="kr">
<head>
    <meta charset="utf-8">
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
<script> document.write(admin_menu()); </script>

<form name="form1" method="post" action="product_update.php" enctype="multipart/form-data">
<input type="hidden" name="id" value="<?= $id ?>">

<div class="row mx-1 justify-content-center">
<div class="col" align="center">
<h4 class="m-0 mb-3">제품 수정</h4>

<table class="table table-sm table-bordered myfs12 m-0 p-0">

<tr>
    <td class="bg-light" width="15%">상품분류</td>
    <td align="left" class="ps-2">
        <select name="menu" class="form-select form-select-sm bg-light myfs12" style="width:120px">
            <?php for ($i = 0; $i < $n_menu; $i++): ?>
            <option value="<?= $i ?>" <?= $i == $row['menu'] ? 'selected' : '' ?>><?= htmlspecialchars($a_menu[$i], ENT_QUOTES) ?></option>
            <?php endfor; ?>
        </select>
    </td>
</tr>

<tr>
    <td class="bg-light">상품코드</td>
    <td align="left" class="ps-2">
        <input type="text" name="code" value="<?= htmlspecialchars($row['code'], ENT_QUOTES) ?>" class="form-control form-control-sm" style="width:200px; text-align:left;">
    </td>
</tr>

<tr>
    <td class="bg-light">상품명</td>
    <td align="left" class="ps-2">
        <input type="text" name="name" value="<?= htmlspecialchars($row['name'], ENT_QUOTES) ?>" class="form-control form-control-sm" style="width:1000px; text-align:left;">
    </td>
</tr>

<tr>
    <td class="bg-light">제조사</td>
    <td align="left" class="ps-2">
        <input type="text" name="coname" value="<?= htmlspecialchars($row['coname'], ENT_QUOTES) ?>" class="form-control form-control-sm text-start" style="width:400px;">
    </td>
</tr>

<tr>
    <td class="bg-light">판매가</td>
    <td align="left" class="ps-2">
        <input type="text" name="price" value="<?= htmlspecialchars($row['price'], ENT_QUOTES) ?>" class="form-control form-control-sm text-start d-inline" style="width:100px;"> 원
    </td>
</tr>

<tr>
    <td class="bg-light">옵션</td>
    <td align="left" class="ps-2">
        <div class="d-inline-flex align-items-center gap-2">
            <select name="opt1" class="form-select form-select-sm bg-light myfs12">
                <option value="0">옵션1 선택</option>
                <?php foreach ([1=>'색상',2=>'사이즈',3=>'길이'] as $v=>$t): ?>
                <option value="<?= $v ?>" <?= $row['opt1']==$v?'selected':'' ?>><?= $t ?></option>
                <?php endforeach; ?>
            </select>
            <select name="opt2" class="form-select form-select-sm bg-light myfs12">
                <option value="0">옵션2 선택</option>
                <?php foreach ([1=>'색상',2=>'사이즈',3=>'길이'] as $v=>$t): ?>
                <option value="<?= $v ?>" <?= $row['opt2']==$v?'selected':'' ?>><?= $t ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </td>
</tr>

<tr>
    <td class="bg-light">제품설명</td>
    <td align="left" class="ps-2">
        <textarea name="contents" rows="5" class="form-control"><?= htmlspecialchars($row['contents'], ENT_QUOTES) ?></textarea>
    </td>
</tr>

<tr>
    <td class="bg-light">상품상태</td>
    <td align="left" class="ps-2 pt-2">
        <input type="radio" name="status" value="1" <?= $row['status']==1?'checked':'' ?>> 판매중 &nbsp;
        <input type="radio" name="status" value="2" <?= $row['status']==2?'checked':'' ?>> 판매중지 &nbsp;
        <input type="radio" name="status" value="3" <?= $row['status']==3?'checked':'' ?>> 품절
    </td>
</tr>

<tr>
    <td class="bg-light">아이콘</td>
    <td align="left" class="ps-2">
        <input type="checkbox" name="icon_new" value="1" <?= $row['icon_new']? 'checked':'' ?>> New &nbsp;
        <input type="checkbox" name="icon_hit" value="1" <?= $row['icon_hit']? 'checked':'' ?>> Hit &nbsp;
        <input type="checkbox" name="icon_sale" value="1" <?= $row['icon_sale']? 'checked':'' ?>> Sale &nbsp;
        할인율:
        <input type="text" name="discount" value="<?= htmlspecialchars($row['discount'], ENT_QUOTES) ?>" size="3" class="form-control form-control-sm d-inline" style="width:60px"> %
    </td>
</tr>

<tr>
    <td class="bg-light">등록일</td>
    <td align="left" class="ps-2">
        <input type="date" name="regday" value="<?= htmlspecialchars($row['regday'], ENT_QUOTES) ?>" class="form-control form-control-sm text-start" style="width:160px; display:inline-block;">
    </td>
</tr>

<tr>
    <td class="bg-light">이미지</td>
    <td align="left" class="ps-2">
        <!-- 이미지 1 -->
        <div class="mb-2 d-flex align-items-center">
            <img src="../product/<?= htmlspecialchars($row['image1'] ?: 'nopic.png', ENT_QUOTES) ?>" width="50" height="50" class="img-thumbnail me-2" data-bs-toggle="modal" data-bs-target="#zoomModal" onclick="document.getElementById('zoomModalLabel').innerText='이미지1'; picname.src='../product/<?= htmlspecialchars($row['image1'] ?: 'nopic.png', ENT_QUOTES) ?>'">
            <input type="hidden" name="imagename1" value="<?= htmlspecialchars($row['image1'], ENT_QUOTES) ?>">
            <input type="checkbox" name="checkno1" value="1"> 삭제
            &nbsp;&nbsp;<?= htmlspecialchars($row['image1'], ENT_QUOTES) ?>
            &nbsp;&nbsp;<input type="file" name="image1" class="form-control form-control-sm myfs12" style="width:250px">
        </div>
        <!-- 이미지 2 -->
        <div class="mb-2 d-flex align-items-center">
            <img src="../product/<?= htmlspecialchars($row['image2'] ?: 'nopic.png', ENT_QUOTES) ?>" width="50" height="50" class="img-thumbnail me-2" data-bs-toggle="modal" data-bs-target="#zoomModal" onclick="document.getElementById('zoomModalLabel').innerText='이미지2'; picname.src='../product/<?= htmlspecialchars($row['image2'] ?: 'nopic.png', ENT_QUOTES) ?>'">
            <input type="hidden" name="imagename2" value="<?= htmlspecialchars($row['image2'], ENT_QUOTES) ?>">
            <input type="checkbox" name="checkno2" value="1"> 삭제
            &nbsp;&nbsp;<?= htmlspecialchars($row['image2'], ENT_QUOTES) ?>
            &nbsp;&nbsp;<input type="file" name="image2" class="form-control form-control-sm myfs12" style="width:250px">
        </div>
        <!-- 이미지 3 -->
        <div class="mb-2 d-flex align-items-center">
            <img src="../product/<?= htmlspecialchars($row['image3'] ?: 'nopic.png', ENT_QUOTES) ?>" width="50" height="50" class="img-thumbnail me-2" data-bs-toggle="modal" data-bs-target="#zoomModal" onclick="document.getElementById('zoomModalLabel').innerText='이미지3'; picname.src='../product/<?= htmlspecialchars($row['image3'] ?: 'nopic.png', ENT_QUOTES) ?>'">
            <input type="hidden" name="imagename3" value="<?= htmlspecialchars($row['image3'], ENT_QUOTES) ?>">
            <input type="checkbox" name="checkno3" value="1"> 삭제
            &nbsp;&nbsp;<?= htmlspecialchars($row['image3'], ENT_QUOTES) ?>
            &nbsp;&nbsp;<input type="file" name="image3" class="form-control form-control-sm myfs12" style="width:250px">
        </div>
    </td>
</tr>

</table>

<a href="javascript:form1.submit();" class="btn btn-sm btn-dark text-white my-2">저 장</a>
<a href="javascript:history.back();" class="btn btn-sm btn-outline-dark my-2">돌아가기</a>

</div>
</div>
</form>
</div>

<!-- Zoom Modal -->
<div class="modal fade" id="zoomModal" tabindex="-1" aria-labelledby="zoomModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="zoomModalLabel">이미지 확대</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="닫기"></button>
            </div>
            <div class="modal-body text-center">
                <img src="#" name="picname" class="img-fluid img-thumbnail" data-bs-dismiss="modal">
            </div>
        </div>
    </div>
</div>
</body>
</html>
