<!doctype html>
<html lang="kr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>4910</title>
  <link rel="icon" href="images/4910_top.ico" type="image/x-icon">
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/my.css" rel="stylesheet">
  <link href="css/weather.css" rel="stylesheet"> <!-- 날씨 전용 CSS -->
  <script src="js/jquery-3.7.1.min.js"></script>
  <script src="js/bootstrap.bundle.min.js"></script>
  <link href="css/header.css" rel="stylesheet">




</head>
<body>
  <div class="container">
<!-------------------------------------------------------------------------------------------->  
<script>
  document.addEventListener("DOMContentLoaded", () => {
    const menuIcons = document.querySelectorAll(".top-icon-menu a");

    menuIcons.forEach((icon) => {
      icon.addEventListener("mouseenter", () => {
        icon.classList.remove("hover-left", "hover-right");
        const direction = Math.random() < 0.5 ? "hover-left" : "hover-right";
        icon.classList.add(direction);
      });

      icon.addEventListener("mouseleave", () => {
        icon.classList.remove("hover-left", "hover-right");
      });
    });
  });
</script>
    <!-- 4910 앱 설치 배너: 화면 오른쪽 중앙에 고정 -->
    <div id="appBanner" class="position-fixed"
         style="top: 50%; right: 0; transform: translateY(-50%); z-index: 1030;">
        <div class="d-flex align-items-center bg-dark text-white p-2 rounded-start"
             style="width: 200px;">
            <div class="flex-grow-1" style="font-size:0.9rem; line-height:1.1;">
                <p class="mb-0">4910 앱에서 더 많은 할인과 혜택을 받아 가세요!</p>
            </div>
            <div class="text-center ms-2 flex-shrink-0">
                <img src="https://static.4910.kr/_next/static/media/img_4910_qr.c0e66ec0.png"
                     alt="앱 설치 QR" width="50" height="50">
            </div>
        </div>
    </div>

<!--  Title과  메뉴(로그인/회원가입/장바구니/주문조회/게시판/Q&A) -->
<div class="row g-0 bg-dark">
  <div class="col fs-3" align="left">
    <a href="index.html">
      <img src="images/4910.png" alt="4910" width="120">
    </a>
  </div>

<div class="col text-white text-end top-icon-menu" style="font-size:12px;">
  <a href="index.html">
    <img src="images/home.png" alt="Home">
    <div class="custom-tooltip">홈으로 이동</div>
  </a>

  <?php
    include_once "common.php";
    $cookie_id = $_COOKIE['cookie_id'] ?? '';

    if(!$cookie_id) {
      echo('
        <a href="member_login.php">
          <img src="images/login.png" alt="Login">
          <div class="custom-tooltip">로그인</div>
        </a>
        <a href="member_join.php">
          <img src="images/join.png" alt="Sign Up">
          <div class="custom-tooltip">회원가입</div>
        </a>
      ');
    } else {
      echo('
        <a href="member_logout.php">
          <img src="images/logout.png" alt="Logout">
          <div class="custom-tooltip">로그아웃</div>
        </a>
        <a href="member_edit.php">
          <img src="images/edit.png" alt="Edit">
          <div class="custom-tooltip">정보 수정</div>
        </a>
      ');
    }
  ?>

  <a href="cart.php">
    <img src="images/cart.png" alt="Cart">
    <div class="custom-tooltip">장바구니</div>
  </a>

  <a href="jumun_login.php">
    <img src="images/order.png" alt="Order">
    <div class="custom-tooltip">주문 조회</div>
  </a>

  <a href="qa.php">
    <img src="images/qa.png" alt="Q&A">
    <div class="custom-tooltip">Q & A</div>
  </a>

  <a href="faq.php">
    <img src="images/faq.png" alt="FAQ">
    <div class="custom-tooltip">자주 묻는 질문</div>
  </a>
</div>






<!-- Slide Images -->
<div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" data-bs-interval="2000">
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" aria-label="Slide 1" class="active" aria-current="true"></button>
    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="3" aria-label="Slide 4"></button>
  </div>
  <div class="carousel-inner">
    <div class="carousel-item active">
  <a href="random_1.php">
    <img src="images/m1.webp" class="d-block w-100" alt="...">
  </a>
      <div class="carousel-caption d-none d-md-block">
        <h1 style="text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.7);">WOOALONG</h1>
        <p><h6 style="text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.7);">New Entry</h6></p>
      </div>
    </div>
    <div class="carousel-item">
  <a href="random_2.php">
    <img src="images/m2.webp" class="d-block w-100" alt="...">
  </a>
      <div class="carousel-caption d-none d-md-block">
        <h1 style="text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.7);">DIMITRI BLACK</h1>
        <p><h6 style="text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.7);">Treding</h6></p>
      </div>
    </div>
<div class="carousel-item">
 <a href="random_3.php">
    <img src="images/m3.webp" class="d-block w-100" alt="...">
  </a>
  <div class="carousel-caption d-none d-md-block">
    <h1 style="text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.7);">PRADA</h1>
    <p><h6 style="text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.7);">Luxury</h6></p>
  </div>
</div>
    <div class="carousel-item">
 <a href="random_4.php">
    <img src="images/m4.webp" class="d-block w-100" alt="...">
  </a>
      <div class="carousel-caption d-none d-md-block">
        <h1 style="text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.7);">THE NORTH FACE</h1>
        <p><h6 style="text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.7);">Sports</h6></p>
      </div>
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>



<!--  상품 Category 메뉴/ 상품검색 -->
<div class="row bg-dark m-0 p-1 fs-6 border">
  <div class="col">
    <div class="d-flex">
      <ul class="nav me-auto nav-glow"><!-- nav-glow 클래스 추가 -->
<?php
  for ($i = 1; $i < $n_menu; $i++) {
    $name = $a_menu[$i];
    echo "<li class='nav-item zoom_a'>
            <a class='nav-link text-white' style='font-size:15px;' href='menu.php?menu=$i'>$name</a>
          </li>";
  }
?>

      </ul>
      <form name="form1" method="post" action="product_search.php" class="d-flex">
        <div class="input-group input-group-sm pt-1">
          <span class="input-group-text" style="font-size:13px;">상품검색</span>
          <input type="text" name="find_text" class="form-control form-control-sm" size="10">
          <button type="button"
                  class="btn btn-sm btn-outline-light"
                  style="font-size:13px;"
                  onclick="check_findproduct()">Search</button>
        </div>
      </form>
    </div>
  </div>
</div>

</div>

<script>
  function check_findproduct() {
    if (!form1.find_text.value) {
      alert('검색어를 입력하세요');
      return;
    }
    form1.submit();
  }
</script>
<script src="js/app.js"></script> <!-- 날씨용 JS -->
</body>
</html>
