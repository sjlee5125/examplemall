<?php
require_once("inc/session.php");
require_once("inc/db.php");

if (!isset($_SESSION['member_id'])) {
    echo "<script>alert('로그인이 필요합니다.'); location.href = 'login.php';</script>";
    exit;
}

// POST
$cart_items = isset($_POST['cart_items']) ? json_decode($_POST['cart_items'], true) : null;
$total_price = $_POST['total_price'] ?? null;
$recipient_name = $_POST['recipient_name'] ?? null;
$recipient_phone = $_POST['recipient_phone'] ?? null;
$shipping_address = $_POST['shipping_address'] ?? null;
$payment_method = $_POST['payment_method'] ?? null;

// 필수 데이터 검증
if (!$cart_items || !$total_price || !$recipient_name || !$recipient_phone || !$shipping_address || !$payment_method) {
    echo "<script>alert('잘못된 요청입니다. 필수 정보가 누락되었습니다.'); history.back();</script>";
    exit;
}

try {
    // DB 연결
    $pdo = db_get_pdo();
    $pdo->beginTransaction();

    // 주문 추가
    $query = "INSERT INTO orders (member_id, total_price, recipient_name, recipient_phone, shipping_address, payment_method, order_date) 
              VALUES (?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        $_SESSION['member_id'],
        $total_price,
        $recipient_name,
        $recipient_phone,
        $shipping_address,
        $payment_method
    ]);

    // 마지막으로 삽입된 주문 ID 가져오기
    $order_id = $pdo->lastInsertId();

    // 주문 상품 추가
    $query = "INSERT INTO order_items (order_id, content_code, quantity, content_color, content_size, price) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($query);

    foreach ($cart_items as $item) {
        $stmt->execute([
            $order_id,
            $item['content_code'],
            $item['quantity'],
            $item['content_color'],
            $item['content_size'],
            $item['content_price']
        ]);
    }

    // 트랜잭션 커밋
    $pdo->commit();

    // 세션에서 선택된 장바구니 데이터 초기화
    unset($_SESSION['selected_cart_items']);

    echo "<script>alert('결제가 완료되었습니다.'); location.href = 'order_history.php';</script>";
    exit;

} catch (Exception $e) {
    // 트랜잭션 롤백
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    die("결제 처리 중 오류가 발생했습니다: " . $e->getMessage());
}
