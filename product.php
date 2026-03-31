<?php
    include "main_top.php";

    // 1) product 정보
    $id     = $_GET['id'] ?? 0;
    $sql    = "SELECT * FROM product WHERE id = {$id}";
    $result = mysqli_query($db, $sql);
    $row    = mysqli_fetch_array($result);

    // 2) 옵션 카테고리 목록 (opt 테이블)
    $rs_cat = mysqli_query($db, "SELECT id, name FROM opt ORDER BY id");
    $categories = [];
    while ($c = mysqli_fetch_assoc($rs_cat)) {
        $categories[] = $c;
    }

    // 3) 판매 중지 / 품절 처리
    if ($row['status'] === '2' || $row['status'] === '3') {
        $msg = $row['status'] === '2' ? '판매 중지' : '품절';
        $sub = $row['status'] === '2'
             ? '현재 이 상품은 판매가 중단되었습니다.'
             : '현재 이 상품은 품절되었습니다.';
        echo "
        <div class='d-flex justify-content-center align-items-center my-5'>
            <div class='border rounded-3 shadow-sm p-4 bg-light' style='max-width:400px;'>
                <h4 class='text-center text-danger mb-2'>
                    <i class='bi bi-x-circle-fill me-2'></i>{$msg}
                </h4>
                <p class='text-center text-secondary' style='font-size:14px;'>{$sub}</p>
            </div>
        </div>";
        include "main_bottom.php";
        exit;
    }
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
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>

<div class="container">

<script>
// 수량·금액 계산
function cal_price() {
    let price    = parseFloat(form2.price.value);
    let discount = parseFloat(form2.discount.value);
    let num      = parseInt(form2.num.value);
    if (isNaN(num) || num < 1) {
        alert("수량을 1 이상으로 입력하세요.");
        form2.num.value = 1;
        num = 1;
    }
    let sale_price = price * (100 - discount) / 100;
    let total      = Math.round(sale_price * num);
    form2.prices.value = total.toLocaleString();
    form2.num.focus();
}

// 폼 유효성 검사
function check_form2(kind) {
<?php foreach ($categories as $cat): ?>
if (form2.opts<?= $cat['id'] ?>.value == 0) {
    alert("<?= $cat['name'] ?>을(를) 선택하세요.");
    form2.opts<?= $cat['id'] ?>.focus();
    return;
}
<?php endforeach; ?>


    if (!form2.num.value) {
        alert("수량을 입력하세요.");
        form2.num.focus();
        return;
    }
    form2.action   = "cart_edit.php";
    form2.kind.value = (kind === "D" ? "order" : "insert");
    form2.submit();
}
</script>

<form name="form2" method="post" action="">
    <input type="hidden" name="kind"     value="insert">
    <input type="hidden" name="id"       value="<?= $row['id'] ?>">
    <input type="hidden" name="price"    value="<?= $row['price'] ?>">
    <input type="hidden" name="discount" value="<?= $row['discount'] ?>">

    <div class="row mx-1 my-4">
        <div class="col" align="center">
            <!-- 상품 이미지 & 이름 -->
            <table class="table table-sm table-borderless">
            <tr>
                <td width="50%" valign="top" align="center">
                    <img src="product/<?= $row['image1'] ?: 'nopic.png' ?>"
                         width="80%"
                         class="img-thumbnail img-fluid mt-2"
                         style="cursor:zoom-in"
                         data-bs-toggle="modal"
                         data-bs-target="#zoomModal">
                </td>
                <td width="50%" align="center" valign="top" class="px-0">
                    <hr class="my-2" size="5px" width="100%">
                    <table width="100%" class="table table-sm table-borderless p-0 m-0" style="font-size:12px;">
                        <tr height="50">
                            <td colspan="2" align="center" style="font-size:20px; color:#222;">
                                <?= $row['name'] ?>
                            </td>
                        </tr>
                        <tr height="35">
                            <td colspan="2" align="center">
                                <?php
                                    if ($row['icon_new'])  echo "<img src='images/i_new.gif'> ";
                                    if ($row['icon_hit'])  echo "<img src='images/i_hit.gif'> ";
                                    if ($row['icon_sale']) echo "<img src='images/i_sale.gif'>
                                        <font color='red' size='3'>{$row['discount']}%</font>";
                                ?>
                            </td>
                        </tr>
                        <tr><td colspan="2"><hr class="my-2"></td></tr>
						<tr height="35">
							<td width="30%" align="center">정가</td>
							<td width="70%" align="left" style="font-size:15px;">
								<?php if ($row['icon_sale']): ?>
									<strike><?= number_format($row['price']) ?></strike>
								<?php else: ?>
									<?= number_format($row['price']) ?>
								<?php endif; ?>
							</td>
						</tr>

						<?php if ($row['icon_sale']): ?>
						<tr height="35">
							<td align="center">할인가</td>
							<td align="left" style="font-size:15px;">
								<?= number_format($row['price'] * (100 - $row['discount']) / 100) ?>
							</td>
						</tr>
						<?php endif; ?>

                        <tr><td colspan="2"><hr class="my-2"></td></tr>

                        <!-- 옵션 카테고리별 select -->
                        <?php foreach ($categories as $cat): ?>
<tr>
    <td align="center"><?= $cat['name'] ?></td>
    <td align="left">
        <select name="opts<?= $cat['id'] ?>"
                class="form-select form-select-sm mb-2"
                style="width:90%; font-size:12px;">
            <option value="0" selected>선택하세요.</option>
            <?php
                $rs_item = mysqli_query(
                    $db,
                    "SELECT id, name 
                       FROM opts 
                      WHERE opt_id = {$cat['id']}
                      ORDER BY id"
                );
                while ($it = mysqli_fetch_assoc($rs_item)):
            ?>
            <option value="<?= $it['id'] ?>">
                <?= $it['name'] ?>
            </option>
            <?php endwhile; ?>
        </select>
    </td>
</tr>
<?php endforeach; ?>

                        <!-- /옵션 -->

                        <tr><td colspan="2"><hr class="my-2"></td></tr>
                        <tr>
                            <td align="center">수량</td>
                            <td align="left">
                                <div class="d-inline-flex">
                                    <input type="text"
                                           name="num"
                                           size="5"
                                           value="1"
                                           class="form-control form-control-sm"
                                           style="text-align:center;"
                                           onchange="cal_price()">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td align="center">금액</td>
                            <td align="left">
                                <div class="d-inline-flex">
                                    <input type="text"
                                           name="prices"
                                           value="<?= number_format($row['price'] * (100 - $row['discount']) / 100) ?>"
                                           size="10"
                                           class="form-control form-control-sm"
                                           style="border:0;background:white;text-align:left;font-size:18px;"
                                           readonly>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" height="100" align="center">
                                <a href="javascript:check_form2('D')"
                                   class="btn btn-sm btn-secondary text-light">
                                    바로 구매
                                </a>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <a href="javascript:check_form2('C')"
                                   class="btn btn-sm btn-outline-secondary">
                                    장바구니
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            </table>
        </div>
    </div>
</form>

<hr class="my-0 mx-3">

<div align="center">
    <br>
    본 제품의 상세설명은 다음과 같습니다...<br><br>
    <?php
        $detailImgs = [];
        for ($i = 1; $i <= 3; $i++) {
            if (!empty($row["image{$i}"])) {
                $detailImgs[] = $row["image{$i}"];
            }
        }
        if (empty($detailImgs)) {
            $detailImgs[] = 'nopic.png';
        }
        foreach ($detailImgs as $img) {
            echo "<img src='product/{$img}' 
                      class='img-thumbnail mb-2' 
                      style='border:0; max-width:80%;'><br>";
        }
    ?>
</div>
<br><br>

<!-- Zoom Modal (Unfolding) -->
<div id="modal-container">
  <div class="modal-background">
    <div class="modal">
      <h2><?= $row['name'] ?></h2>
      <button type="button" class="btn-close" id="modal-close"></button>
      <div class="modal-body text-center" style="position: relative; z-index: 9999;">
        <img src="product/<?= $row['image1'] ?: 'nopic.png' ?>"
             class="zoom-in img-thumbnail
             style="max-width:100%;"
             id="modal-img">
      </div>
    </div>
  </div>
</div>

 
<script>
  // 모달 열기
  document.querySelectorAll('img[data-bs-toggle="modal"]').forEach(img => {
    img.addEventListener('click', () => {
      document.documentElement.classList.add('modal-active');
      document.body.classList.add('modal-active');
      const mc = document.getElementById('modal-container');
      mc.classList.remove('out');
      mc.classList.add('one');    // 여길 추가
    });
  });

  // 모달 닫기
  function closeModal() {
    const mc = document.getElementById('modal-container');
    mc.classList.add('out');
    mc.classList.remove('one');  // 여길 추가
    document.documentElement.classList.remove('modal-active');
    document.body.classList.remove('modal-active');
  }

  document.getElementById('modal-close').addEventListener('click', closeModal);
  document.getElementById('modal-container').addEventListener('click', e => {
    if (e.target.id === 'modal-container') closeModal();
  });
</script>


<?php include "main_bottom.php"; ?>
</div>
</body>
</html>
