<?php
include "login_main_check.php";
include "../common.php";
include "csrf.php";
admin_require_post_csrf('product.php');

// 1. 필수 값 검증 및 필터링
$menu     = isset($_POST['menu'])    ? (int)$_POST['menu']    : null;
$code     = isset($_POST['code'])    ? trim($_POST['code'])   : '';
$name     = isset($_POST['name'])    ? trim($_POST['name'])   : '';
$coname   = isset($_POST['coname'])  ? trim($_POST['coname']) : '';
$price    = isset($_POST['price'])   ? (int)$_POST['price']   : null;
$opt1     = isset($_POST['opt1'])    ? (int)$_POST['opt1']    : 0;
$opt2     = isset($_POST['opt2'])    ? (int)$_POST['opt2']    : 0;
$contents = isset($_POST['contents'])? trim($_POST['contents']) : '';
$status   = isset($_POST['status'])  ? (int)$_POST['status']  : 1;
$icon_new  = isset($_POST['icon_new'])  ? 1 : 0;
$icon_hit  = isset($_POST['icon_hit'])  ? 1 : 0;
$icon_sale = isset($_POST['icon_sale']) ? 1 : 0;
$discount  = isset($_POST['discount'])  ? (int)$_POST['discount'] : 0;
$regday    = isset($_POST['regday'])    ? $_POST['regday'] : date('Y-m-d');

if (is_null($menu) || $price < 0 || $code === '' || $name === '') {
    exit('필수 입력값이 누락되었거나 형식이 잘못되었습니다.');
}

// 2. 이미지 업로드 함수
function uploadImage($fileKey) {
    $allowedExt = ['jpg','jpeg','png','gif'];
    if (empty($_FILES[$fileKey]['name'])) {
        return '';
    }
    $tmpPath = $_FILES[$fileKey]['tmp_name'];
    if (!is_uploaded_file($tmpPath)) {
        throw new Exception("업로드 오류: {$fileKey}");
    }
    $ext = strtolower(pathinfo($_FILES[$fileKey]['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExt)) {
        throw new Exception("허용되지 않는 파일 형식입니다: {$fileKey}");
    }
    // 고유 파일명 생성
    $newName = uniqid('prd_') . '.' . $ext;
    $baseDir = realpath(__DIR__ . '/../product/');
    $dest = $baseDir . '/' . $newName;
    if (strpos(realpath(dirname($dest)), $baseDir) !== 0) {
        throw new Exception("잘못된 경로: {$newName}");
    }
    if (!move_uploaded_file($tmpPath, $dest)) {
        throw new Exception("파일 저장 실패: {$newName}");
    }
    return $newName;
}

try {
    // 트랜잭션 시작
    mysqli_begin_transaction($db);

    // 3. 이미지 업로드 처리
    $pic1 = uploadImage('image1');
    $pic2 = uploadImage('image2');
    $pic3 = uploadImage('image3');

    // 4. INSERT 쿼리 (Prepared Statement)
    $sql = "INSERT INTO product
        (menu, code, name, coname, price, opt1, opt2, contents, status,
         icon_new, icon_hit, icon_sale, discount, regday, image1, image2, image3)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($db, $sql);
    if (!$stmt) {
        throw new Exception('상품 등록 준비 실패');
    }
    mysqli_stmt_bind_param(
        $stmt,
        'isssiiiisiiiissss',
        $menu, $code, $name, $coname, $price,
        $opt1, $opt2, $contents, $status,
        $icon_new, $icon_hit, $icon_sale, $discount,
        $regday, $pic1, $pic2, $pic3
    );
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception('상품 등록 실패');
    }
    mysqli_stmt_close($stmt);

    // 5. 커밋 후 리다이렉트
    mysqli_commit($db);
    header('Location: product.php');
    exit;

} catch (Exception $e) {
    // 롤백 및 업로드된 파일 삭제
    mysqli_rollback($db);
    foreach ([$pic1 ?? '', $pic2 ?? '', $pic3 ?? ''] as $img) {
        if ($img && file_exists(__DIR__ . '/../product/' . $img)) {
            unlink(__DIR__ . '/../product/' . $img);
        }
    }
    error_log('Product insert failed: ' . $e->getMessage());
    exit('상품 등록 처리 중 오류가 발생했습니다.');
}
