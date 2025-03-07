<?php
require_once("inc/session.php");
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/my_page.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>마이페이지</title>
</head>
<body>
    <?php include("inc/header.php"); ?>

    <main class="mypage_main">
        <h1>마이 페이지</h1>
        
        <!-- 포인트, 예치금 및 쿠폰 정보 -->
        <section class="point_info">
            <div class="point_item">가용적립금<br>0원</div>
            <div class="point_item">총적립금<br>0원</div>
            <div class="point_item">사용적립금<br>0원</div>
            <div class="point_item">예치금<br>0원 <button>조회</button></div>
            <div class="point_item">총 주문<br>0원</div>
            <div class="point_item">쿠폰<br>0원 <button>조회</button></div>
        </section>

        <!-- 주문 처리 현황 -->
        <section class="order_status">
            <h2>나의 주문처리 현황 (최근 3개월 기준)</h2>
            <div class="status_grid">
                <div>입금 전<br><span>0</span></div>
                <div>배송 준비중<br><span>0</span></div>
                <div>배송 중<br><span>0</span></div>
                <div>배송 완료<br><span>0</span></div>
            </div>
        </section>

        <!-- 마이페이지 메뉴 -->
        <section class="mypage_menu">
            <div class="menu_item"><a href="order_history.php">Order<br>주문 내역 조회</a></div>
            <div class="menu_item"><a href="profile.php">Profile<br>회원 정보</a></div>
            <div class="menu_item"><a href="wishlist.php">Wishlist<br>관심 상품</a></div>
            <div class="menu_item"><a href="mileage.php">Mileage<br>적립금</a></div>
            <div class="menu_item"><a href="coupon.php">Coupon<br>쿠폰</a></div>
            <div class="menu_item"><a href="board.php">Board<br>게시물</a></div>
            <div class="menu_item"><a href="address.php">Regular Delivery<br>정기 배송 관리</a></div>
        </section>
    </main>
</body>
</html>
