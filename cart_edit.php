<?php
// cart_edit.php - 장바구니 처리 + JSON 쿠키 직렬화 방식

// 1) 에러 표시 켜기 (개발 시에만)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 2) 쿠키에서 cart 배열과 n_cart 읽기 (JSON 디코드)
$decoded_cart = isset($_COOKIE['cart']) ? json_decode($_COOKIE['cart'], true) : array();
$cart   = is_array($decoded_cart) ? $decoded_cart : array();
$n_cart = isset($_COOKIE['n_cart']) ? max(0, (int)$_COOKIE['n_cart']) : count($cart);
$kind   = $_REQUEST['kind'] ?? '';

switch ($kind) {
    case 'insert':    // product.php → 장바구니 담기
    case 'order':     // 바로 구매
        // product.php에서 넘어온 값들
        $id    = max(0, (int)($_REQUEST['id'] ?? 0));
        $num   = max(1, (int)($_REQUEST['num'] ?? 1));
        $opts1 = max(0, (int)($_REQUEST['opts1'] ?? 0));
        $opts2 = max(0, (int)($_REQUEST['opts2'] ?? 0));
        $opts3 = max(0, (int)($_REQUEST['opts3'] ?? 0));

        if ($id > 0) {
            $n_cart++;
            $cart[$n_cart] = implode('^', array($id, $num, $opts1, $opts2, $opts3));
        }
        break;

    case 'delete':    // 단일 삭제
        $pos = (int)($_REQUEST['pos'] ?? 0);
        if ($pos > 0 && isset($cart[$pos])) {
            unset($cart[$pos]);
        }
        break;

    case 'update':    // 수량 수정
        $pos = (int)($_REQUEST['pos'] ?? 0);
        $num = max(1, (int)($_REQUEST['num'] ?? 1));
        if ($pos > 0 && isset($cart[$pos])) {
            list($id, $_old, $opts1, $opts2, $opts3) = explode('^', $cart[$pos] . '^^^^');
            $cart[$pos] = implode('^', array((int)$id, $num, (int)$opts1, (int)$opts2, (int)$opts3));
        }
        break;

    case 'deleteall': // 전체 비우기
        $cart   = array();
        $n_cart = 0;
        break;

    default:
        header('Location: cart.php');
        exit;
}

// 비어 있는 slot 제거 및 장바구니 개수 보정
$cart = array_filter($cart, fn($item) => is_string($item) && $item !== '');
$n_cart = empty($cart) ? 0 : max(array_map('intval', array_keys($cart)));

// 4) 쿠키에 다시 저장 (JSON 인코드 + 경로 '/')
setcookie('cart', json_encode($cart), time()+3600, '/');
setcookie('n_cart', (string)$n_cart, time()+3600, '/');

// 5) 리다이렉트
if ($kind === 'order') {
    header('Location: order.php');
} else {
    header('Location: cart.php');
}
exit;
?>
