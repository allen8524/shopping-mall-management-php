<?php
// cart_edit.php - 장바구니 처리 + JSON 쿠키 직렬화 방식

// 1) 에러 표시 켜기 (개발 시에만)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 2) 쿠키에서 cart 배열과 n_cart 읽기 (JSON 디코드)
$cart   = isset($_COOKIE['cart'])   ? json_decode($_COOKIE['cart'], true) : array();
$n_cart = isset($_COOKIE['n_cart']) ? (int)$_COOKIE['n_cart']     : 0;
$kind   = $_REQUEST['kind'];

// 3) 기본값 설정
if (!$n_cart) $n_cart = 0;

switch ($kind) {
    case 'insert':    // product.php → 장바구니 담기
    case 'order':     // 바로 구매
        $n_cart++;
        // product.php에서 넘어온 값들
        $id    = (int) $_REQUEST['id'];
        $num   = (int) $_REQUEST['num'];
        $opts1 = (int) $_REQUEST['opts1'];
        $opts2 = (int) $_REQUEST['opts2'];
		$opts3 = (int) $_REQUEST['opts3'];
		$cart[$n_cart] = implode('^', array($id, $num, $opts1, $opts2, $opts3));
        break;

    case 'delete':    // 단일 삭제
        $pos = (int) $_REQUEST['pos'];
        unset($cart[$pos]);
        break;

    case 'update':    // 수량 수정
        $pos = (int) $_REQUEST['pos'];
        $num = (int) $_REQUEST['num'];
		list($id, $_old, $opts1, $opts2, $opts3) = explode('^', $cart[$pos] . '^^^^');
		$cart[$pos] = implode('^', array($id, $num, $opts1, $opts2, $opts3));
        break;

    case 'deleteall': // 전체 비우기
        $cart   = array();
        $n_cart = 0;
        break;
}

// 4) 쿠키에 다시 저장 (JSON 인코드 + 경로 '/')
setcookie('cart',   json_encode($cart), time()+3600, '/');
setcookie('n_cart', $n_cart,            time()+3600, '/');

// 5) 리다이렉트
if ($kind === 'order') {
    header('Location: order.php');
} else {
    header('Location: cart.php');
}
exit;
?>
