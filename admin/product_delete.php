<?php
include "login_main_check.php";
include "../common.php";
include "csrf.php";
admin_require_post_csrf('product.php');

function safe_product_image_path_for_delete($baseDir, $name) {
    $name = basename(trim((string)$name));
    if ($name === '') {
        return '';
    }

    $path = $baseDir . DIRECTORY_SEPARATOR . $name;
    $dir = realpath(dirname($path));
    if ($dir === false || strpos($dir, $baseDir) !== 0) {
        throw new Exception('Invalid product image path');
    }

    return $path;
}

$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) {
    header('Location: product.php');
    exit;
}

$deleteAfterCommit = [];
$transactionStarted = false;

try {
    $baseDir = realpath(__DIR__ . '/../product/');
    if ($baseDir === false) {
        throw new Exception('Product image directory not found');
    }

    if (!mysqli_begin_transaction($db)) {
        throw new Exception('Product delete transaction start failed');
    }
    $transactionStarted = true;

    $stmt = mysqli_prepare($db, "SELECT image1, image2, image3 FROM product WHERE id = ?");
    if (!$stmt) {
        throw new Exception('Product image select prepare failed');
    }
    mysqli_stmt_bind_param($stmt, 'i', $id);
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception('Product image select execute failed');
    }
    mysqli_stmt_bind_result($stmt, $img1, $img2, $img3);
    if (!mysqli_stmt_fetch($stmt)) {
        mysqli_stmt_close($stmt);
        throw new Exception('Product not found');
    }
    mysqli_stmt_close($stmt);

    $del = mysqli_prepare($db, "DELETE FROM product WHERE id = ?");
    if (!$del) {
        throw new Exception('Product delete prepare failed');
    }
    mysqli_stmt_bind_param($del, 'i', $id);
    if (!mysqli_stmt_execute($del)) {
        throw new Exception('Product delete execute failed');
    }
    mysqli_stmt_close($del);

    $deleteAfterCommit = array_filter([$img1, $img2, $img3]);
    mysqli_commit($db);
    $transactionStarted = false;

    foreach (array_unique($deleteAfterCommit) as $img) {
        $file = safe_product_image_path_for_delete($baseDir, $img);
        if ($file !== '' && is_file($file)) {
            unlink($file);
        }
    }
} catch (Throwable $e) {
    if ($transactionStarted) {
        mysqli_rollback($db);
    }
    error_log('Product delete failed: ' . $e->getMessage());
    exit('상품 삭제 처리 중 오류가 발생했습니다.');
}

header('Location: product.php');
exit;
