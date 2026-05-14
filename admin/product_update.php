<?php
include "login_main_check.php";
include "../common.php";
include "csrf.php";
admin_require_post_csrf('product.php');

function clean_product_image_name($name) {
    $name = trim((string)$name);
    if ($name === '') {
        return '';
    }

    $baseName = basename($name);
    if ($baseName !== $name) {
        throw new Exception('잘못된 이미지 파일명입니다.');
    }

    return $baseName;
}

function product_image_path($name) {
    $baseDir = realpath(__DIR__ . '/../product/');
    if ($baseDir === false) {
        throw new Exception('상품 이미지 폴더를 찾을 수 없습니다.');
    }

    $safeName = clean_product_image_name($name);
    if ($safeName === '') {
        return '';
    }

    $path = $baseDir . DIRECTORY_SEPARATOR . $safeName;
    $dir = realpath(dirname($path));
    if ($dir === false || strpos($dir, $baseDir) !== 0) {
        throw new Exception('잘못된 이미지 경로입니다.');
    }

    return $path;
}

function upload_product_image($fileKey) {
    $allowedExt = ['jpg', 'jpeg', 'png', 'gif'];

    if (!isset($_FILES[$fileKey]) || $_FILES[$fileKey]['error'] === UPLOAD_ERR_NO_FILE) {
        return '';
    }

    if ($_FILES[$fileKey]['error'] !== UPLOAD_ERR_OK) {
        throw new Exception("업로드 오류: {$fileKey}");
    }

    $tmpPath = $_FILES[$fileKey]['tmp_name'];
    if (!is_uploaded_file($tmpPath)) {
        throw new Exception("업로드 파일 확인 실패: {$fileKey}");
    }

    $ext = strtolower(pathinfo($_FILES[$fileKey]['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExt, true)) {
        throw new Exception("허용되지 않는 파일 형식입니다: {$fileKey}");
    }

    $newName = uniqid('prd_', true) . '.' . $ext;
    $dest = product_image_path($newName);

    if (!move_uploaded_file($tmpPath, $dest)) {
        throw new Exception("파일 저장 실패: {$fileKey}");
    }

    return $newName;
}

$id       = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$menu     = isset($_POST['menu']) ? (int)$_POST['menu'] : null;
$code     = isset($_POST['code']) ? trim($_POST['code']) : '';
$name     = isset($_POST['name']) ? trim($_POST['name']) : '';
$coname   = isset($_POST['coname']) ? trim($_POST['coname']) : '';
$price    = isset($_POST['price']) ? (int)$_POST['price'] : null;
$opt1     = isset($_POST['opt1']) ? (int)$_POST['opt1'] : 0;
$opt2     = isset($_POST['opt2']) ? (int)$_POST['opt2'] : 0;
$contents = isset($_POST['contents']) ? trim($_POST['contents']) : '';
$status   = isset($_POST['status']) ? (int)$_POST['status'] : 1;
$icon_new  = isset($_POST['icon_new']) ? 1 : 0;
$icon_hit  = isset($_POST['icon_hit']) ? 1 : 0;
$icon_sale = isset($_POST['icon_sale']) ? 1 : 0;
$discount  = isset($_POST['discount']) ? (int)$_POST['discount'] : 0;
$regday    = isset($_POST['regday']) ? trim($_POST['regday']) : '';

$newImages = [];
$deleteAfterCommit = [];
$transactionStarted = false;

try {
    if ($id <= 0 || is_null($menu) || is_null($price) || $price < 0 || $code === '' || $name === '' || $regday === '') {
        throw new Exception('필수 입력값이 누락되었거나 형식이 잘못되었습니다.');
    }

    $selectSql = "SELECT image1, image2, image3 FROM product WHERE id = ?";
    $selectStmt = mysqli_prepare($db, $selectSql);
    if (!$selectStmt) {
        throw new Exception('상품 정보 조회 준비 실패');
    }

    mysqli_stmt_bind_param($selectStmt, 'i', $id);
    if (!mysqli_stmt_execute($selectStmt)) {
        mysqli_stmt_close($selectStmt);
        throw new Exception('상품 정보 조회 실패');
    }

    $result = mysqli_stmt_get_result($selectStmt);
    $product = $result ? mysqli_fetch_assoc($result) : null;
    mysqli_stmt_close($selectStmt);

    if (!$product) {
        throw new Exception('상품 정보를 찾을 수 없습니다.');
    }

    $image1 = clean_product_image_name($product['image1'] ?? '');
    $image2 = clean_product_image_name($product['image2'] ?? '');
    $image3 = clean_product_image_name($product['image3'] ?? '');

    $uploaded1 = upload_product_image('image1');
    if ($uploaded1 !== '') $newImages[] = $uploaded1;
    $uploaded2 = upload_product_image('image2');
    if ($uploaded2 !== '') $newImages[] = $uploaded2;
    $uploaded3 = upload_product_image('image3');
    if ($uploaded3 !== '') $newImages[] = $uploaded3;

    if ($uploaded1 !== '') {
        if ($image1 !== '') $deleteAfterCommit[] = $image1;
        $image1 = $uploaded1;
    } elseif (isset($_POST['checkno1']) && $image1 !== '') {
        $deleteAfterCommit[] = $image1;
        $image1 = '';
    }

    if ($uploaded2 !== '') {
        if ($image2 !== '') $deleteAfterCommit[] = $image2;
        $image2 = $uploaded2;
    } elseif (isset($_POST['checkno2']) && $image2 !== '') {
        $deleteAfterCommit[] = $image2;
        $image2 = '';
    }

    if ($uploaded3 !== '') {
        if ($image3 !== '') $deleteAfterCommit[] = $image3;
        $image3 = $uploaded3;
    } elseif (isset($_POST['checkno3']) && $image3 !== '') {
        $deleteAfterCommit[] = $image3;
        $image3 = '';
    }

    if (!mysqli_begin_transaction($db)) {
        throw new Exception('트랜잭션 시작 실패');
    }
    $transactionStarted = true;

    $sql = "UPDATE product SET
        menu = ?, code = ?, name = ?, coname = ?, price = ?,
        opt1 = ?, opt2 = ?, contents = ?, status = ?,
        icon_new = ?, icon_hit = ?, icon_sale = ?,
        discount = ?, regday = ?, image1 = ?, image2 = ?, image3 = ?
        WHERE id = ?";

    $stmt = mysqli_prepare($db, $sql);
    if (!$stmt) {
        throw new Exception('상품 수정 준비 실패');
    }

    mysqli_stmt_bind_param(
        $stmt,
        'isssiiisiiiiissssi',
        $menu,
        $code,
        $name,
        $coname,
        $price,
        $opt1,
        $opt2,
        $contents,
        $status,
        $icon_new,
        $icon_hit,
        $icon_sale,
        $discount,
        $regday,
        $image1,
        $image2,
        $image3,
        $id
    );

    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception('상품 수정 실패');
    }
    mysqli_stmt_close($stmt);

    mysqli_commit($db);
    $transactionStarted = false;

    $finalImages = array_filter([$image1, $image2, $image3]);
    foreach (array_unique($deleteAfterCommit) as $img) {
        if (in_array($img, $finalImages, true)) {
            continue;
        }

        $path = product_image_path($img);
        if ($path !== '' && is_file($path)) {
            unlink($path);
        }
    }

    header('Location: product.php');
    exit;
} catch (Throwable $e) {
    if ($transactionStarted) {
        mysqli_rollback($db);
    }

    foreach ($newImages as $img) {
        try {
            $path = product_image_path($img);
            if ($path !== '' && is_file($path)) {
                unlink($path);
            }
        } catch (Throwable $cleanupError) {
            error_log('Product image cleanup failed: ' . $cleanupError->getMessage());
        }
    }

    error_log('Product update failed: ' . $e->getMessage());
    exit('상품 수정 처리 중 오류가 발생했습니다.');
}
