<?php
require_once("inc/session.php");
require_once("inc/db.php");

// 로그인 확인
if (!isset($_SESSION['member_id'])) {
    echo "<script>alert('로그인이 필요합니다.'); location.href = 'login.php';</script>";
    exit;
}

$total_price = 0;
$cart_items = [];

// 1. 장바구니에서 선택된 상품 처리
if (isset($_SESSION['selected_cart_items']) && !empty($_SESSION['selected_cart_items'])) {
    $cart_items = $_SESSION['selected_cart_items'];
    foreach ($cart_items as $item) {
        $total_price += $item['content_price'] * $item['quantity'];
    }
}

// 2. 컨텐츠 디테일에서 바로 구매 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content_code'])) {
    // 세션에 저장된 장바구니 데이터를 초기화 (중복 방지)
    unset($_SESSION['selected_cart_items']);
    
    // 컨텐츠 디테일 데이터 가져오기
    $content_code = $_POST['content_code'];
    $content_price = $_POST['content_price'];
    $content_name = $_POST['content_name'];
    $content_color = $_POST['content_color'];
    $content_size = $_POST['content_size'];
    $quantity = $_POST['quantity'];

    // 데이터 검증
    if (!$content_code || !$content_price || !$content_name || !$content_color || !$content_size || !$quantity) {
        die("상품 정보가 누락되었습니다.");
    }

    // 총 금액 계산
    $total_price = $content_price * $quantity;

    // 장바구니 형식으로 데이터를 저장
    $cart_items = [
        [
            'content_code' => $content_code,
            'content_price' => $content_price,
            'content_name' => $content_name,
            'content_color' => $content_color,
            'content_size' => $content_size,
            'quantity' => $quantity
        ]
    ];
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>결제 정보 입력</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/payment_info.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <?php include("inc/header.php"); ?>

    <main class="main_wrapper">
        <h1>결제 정보 입력</h1>
        <form action="buy_now.php" method="POST">
            <!-- 상품 정보 -->
            <section>
                <h2>상품 정보</h2>
                <?php foreach ($cart_items as $item): ?>
                    <p>
                        <strong>상품명:</strong> <?= htmlspecialchars($item['content_name']); ?><br>
                        <strong>옵션:</strong> <?= htmlspecialchars($item['content_color']); ?> / <?= htmlspecialchars($item['content_size']); ?><br>
                        <strong>수량:</strong> <?= htmlspecialchars($item['quantity']); ?>개<br>
                        <strong>가격:</strong> <?= number_format($item['content_price']); ?>원
                    </p>
                <?php endforeach; ?>
                <p><strong>총 결제 금액:</strong> <?= number_format($total_price); ?>원</p>

                <!-- Hidden Inputs -->
                <input type="hidden" name="cart_items" value="<?= htmlspecialchars(json_encode($cart_items)); ?>">
                <input type="hidden" name="total_price" value="<?= htmlspecialchars($total_price); ?>">
            </section>

            <!-- 배송 정보 -->
            <section>
                <h2>배송 정보</h2>
                <label for="recipient_name">수령인 이름:</label>
                <input type="text" id="recipient_name" name="recipient_name" required placeholder="예: 홍길동">

                <label for="recipient_phone">연락처:</label>
                <input type="tel" id="recipient_phone" name="recipient_phone" required placeholder="예: 010-1234-5678">

                <label for="shipping_address">주소:</label>
                <textarea id="shipping_address" name="shipping_address" required placeholder="예: 서울특별시 강남구 테헤란로 123"></textarea>
            </section>

            <!-- 결제 방법 -->
            <section>
                <h2>결제 방법</h2>
                <label for="payment_method">결제 수단:</label>
                <select id="payment_method" name="payment_method" required>
                    <option value="" disabled selected>결제 방법 선택</option>
                    <option value="card">신용카드</option>
                    <option value="bank_transfer">계좌이체</option>
                    <option value="mobile_payment">휴대폰 결제</option>
                </select>
            </section>

            <!-- 결제 버튼 -->
            <button type="submit" class="submit_button">결제하기</button>
        </form>
    </main>

    <?php include("inc/footer.php"); ?>
</body>
</html>
