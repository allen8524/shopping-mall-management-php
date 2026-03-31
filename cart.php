<?php
// cart.php — 동적 장바구니 페이지
include "main_top.php";
// include "common.php";   // DB 연결, $db 정의

// 1. JSON으로 저장된 쿠키 읽어오기
$cart   = isset($_COOKIE['cart'])   ? json_decode($_COOKIE['cart'], true) : array();
$n_cart = isset($_COOKIE['n_cart']) ? (int)$_COOKIE['n_cart']     : 0;

// 2. opts 테이블에서 옵션명 동적 로드
$opt1_names = [];
$rs1 = mysqli_query($db, "SELECT id, name FROM opts WHERE opt_id = 1 ORDER BY id");
while ($r = mysqli_fetch_assoc($rs1)) {
    $opt1_names[$r['id']] = $r['name'];
}
$opt2_names = [];
$rs2 = mysqli_query($db, "SELECT id, name FROM opts WHERE opt_id = 2 ORDER BY id");
while ($r = mysqli_fetch_assoc($rs2)) {
    $opt2_names[$r['id']] = $r['name'];
}

$opt3_names = [];
$rs3 = mysqli_query($db, "SELECT id, name FROM opts WHERE opt_id = 3 ORDER BY id");
while ($r = mysqli_fetch_assoc($rs3)) {
    $opt3_names[$r['id']] = $r['name'];
}

// 3. 배송비 및 총액 초기화
$shipping = 2500;
$total    = 0;
?>
<!doctype html>
<html lang="kr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/my.css"         rel="stylesheet">
    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container">
    

    <script>
    function cart_edit(kind, pos) {
        var url = "cart_edit.php?kind=" + kind + "&pos=" + pos;
        if (kind === "update") {
            var num = document.form2["num" + pos].value;
            url += "&num=" + num;
        }
        location.href = url;
    }
    </script>

    <form name="form2" method="post" action="">
        <div class="row m-3 mb-0">
            <div class="col text-center">
                <h4 class="my-3">장바구니</h4>
                <hr class="m-0">
                <table class="table table-sm mb-5">
                    <tr class="bg-light" style="height:40px; font-size:14px;">
                        <td width="10%">이미지</td>
                        <td width="35%">상품정보</td>
                        <td width="10%">판매가</td>
                        <td width="20%">수량</td>
                        <td width="10%">금액</td>
                        <td width="10%">삭제</td>
                    </tr>
                    <?php for ($i = 1; $i <= $n_cart; $i++): ?>
                        <?php if (empty($cart[$i])) continue; ?>
                        <?php list($id, $num, $opts1, $opts2, $opts3) = explode('^', $cart[$i] . '^^^^'); // 누락 방지 ?>
                        <?php
                            // 제품 정보 조회
                            $sql    = "SELECT * FROM product WHERE id = $id";
                            $result = mysqli_query($db, $sql);
                            $row    = mysqli_fetch_array($result);

                            // 가격 계산
                            $unit_price = intval($row['price'] * (100 - $row['discount']) / 100);
                            $line_total = $unit_price * $num;
                            $total     += $line_total;
                        ?>
                        <tr style="height:85px; font-size:14px;">
                            <td>
                                <a href="product.php?id=<?= $id ?>">
                                    <img src="product/<?= $row['image1'] ?: 'nopic.png' ?>"
                                         width="60" height="70">
                                </a>
                            </td>
                            <td class="align-middle text-start">
                                <a href="product.php?id=<?= $id ?>" style="color:#0066CC;">
                                    <?= htmlspecialchars($row['name']) ?>
                                </a><br>
<small><b>[옵션]</b>
    <?= htmlspecialchars($opt1_names[(int)$opts1] ?? '') ?>
    <?= htmlspecialchars($opt2_names[(int)$opts2] ?? '') ?>
    <?= htmlspecialchars($opt3_names[(int)$opts3] ?? '') ?>
</small>

                            </td>
                            <td class="align-middle"><?= number_format($unit_price) ?></td>
                            <td class="align-middle">
                                <div class="d-inline-flex">
                                    <input type="text"
                                           name="num<?= $i ?>"
                                           size="2"
                                           value="<?= $num ?>"
                                           class="form-control form-control-sm text-center">
                                </div>
                                <a href="javascript:cart_edit('update','<?= $i ?>')"
                                   class="btn btn-sm mybutton mb-1"
                                   style="color:#0066CC">수정</a>
                            </td>
                            <td class="align-middle"><?= number_format($line_total) ?></td>
                            <td class="align-middle">
                                <a href="javascript:cart_edit('delete','<?= $i ?>')"
                                   class="btn btn-sm mybutton"
                                   style="color:#D06404">삭제</a>
                            </td>
                        </tr>
                    <?php endfor; ?>

                    <?php
                    // 총 합 계산 후 배송비 조건 적용
                    if ($total > 100000) {
                        $shipping = 0;
                    }
                    ?>
                    <tr class="bg-light" style="font-size:14px;">
                        <td colspan="6" class="text-end pe-4" style="height:40px;">
                            <span style="color:#0066CC;">총 합계금액</span> :
                            상품구매금액( <?= number_format($total) ?> )
                            + 배송비( <?= number_format($shipping) ?> )
                            = <span style="font-size:16px;"><?= number_format($total + $shipping) ?></span>
                        </td>
                    </tr>
                </table>

                <a href="index.html" class="btn btn-sm btn-outline-secondary mx-1">계속 쇼핑하기</a>
                <a href="javascript:cart_edit('deleteall','0')"
                   class="btn btn-sm btn-outline-secondary mx-1">장바구니 비우기</a>
                <a href="order.php" class="btn btn-sm btn-dark text-white mx-1">결제하기</a>
            </div>
        </div>
    </form>

    <?php include "main_bottom.php"; ?>
</div>
</body>
</html>
