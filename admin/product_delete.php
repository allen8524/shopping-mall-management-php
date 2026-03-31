<?php
include "login_main_check.php";
include "../common.php";

// 1. ID 검증
$id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;
if ($id <= 0) {
    exit('유효하지 않은 상품 ID입니다.');
}

// 2. 트랜잭션 시작
mysqli_begin_transaction($db);

try {
    // 3. 이미지 파일명 조회 (Prepared Statement 사용)
    $stmt = mysqli_prepare($db, "SELECT image1, image2, image3 FROM product WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $img1, $img2, $img3);
    if (!mysqli_stmt_fetch($stmt)) {
        throw new Exception('존재하지 않는 상품입니다.');
    }
    mysqli_stmt_close($stmt);

    // 4. 파일 경로 검증 및 삭제
    $baseDir = realpath(__DIR__ . '/../product/');
    foreach (array($img1, $img2, $img3) as $img) {
        if ($img) {
            $file = realpath($baseDir . '/' . $img);
            // 경로 검사: realpath 결과가 baseDir 하위인지 확인
            if ($file === false || strpos($file, $baseDir) !== 0) {
                throw new Exception('유효하지 않은 파일 경로: ' . htmlspecialchars($img, ENT_QUOTES));
            }
            if (file_exists($file) && !unlink($file)) {
                throw new Exception('파일 삭제 실패: ' . htmlspecialchars($img, ENT_QUOTES));
            }
        }
    }

    // 5. 상품 레코드 삭제 (Prepared Statement 사용)
    $del = mysqli_prepare($db, "DELETE FROM product WHERE id = ?");
    mysqli_stmt_bind_param($del, 'i', $id);
    mysqli_stmt_execute($del);
    if (mysqli_stmt_affected_rows($del) === 0) {
        throw new Exception('상품 삭제에 실패했습니다.');
    }
    mysqli_stmt_close($del);

    // 6. 커밋 & 리다이렉트
    mysqli_commit($db);
    header('Location: product.php');
    exit;

} catch (Exception $e) {
    // 롤백 후 에러 출력
    mysqli_rollback($db);
    exit('에러: ' . $e->getMessage());
}
