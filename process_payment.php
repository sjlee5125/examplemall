<?php
require_once("inc/session.php");
require_once("inc/db.php");

// 로그인된 사용자 확인
$member_id = $_SESSION['member_id'] ?? null;
if (!$member_id) {
    header("Location: login.php");
    exit;
}

// 필수 데이터 검증
if (
    empty($_POST['recipient_name']) ||
    empty($_POST['recipient_phone']) ||
    empty($_POST['shipping_address']) ||
    empty($_POST['payment_method'])
) {
    echo "필수 정보를 모두 입력해 주세요.";
    exit;
}

// 데이터 추출
$content_codes = $_POST['content_codes'];
$quantities = $_POST['quantities'];
$recipient_name = $_POST['recipient_name'];
$recipient_phone = $_POST['recipient_phone'];
$shipping_address = $_POST['shipping_address'];
$delivery_note = $_POST['delivery_note'] ?? '';
$payment_method = $_POST['payment_method'];

// 상품 정보 가져오기
$placeholders = implode(',', array_fill(0, count($content_codes), '?'));
$query = "
    SELECT content_code, content_price 
    FROM contents 
    WHERE content_code IN ($placeholders)
";
$product_info = db_select($query, $content_codes);

// 상품 정보 매핑
$products = [];
foreach ($product_info as $product) {
    $products[$product['content_code']] = $product;
}

// 총 결제 금액 계산
$total_price = 0;
foreach ($content_codes as $index => $code) {
    $quantity = $quantities[$index];
    $total_price += $products[$code]['content_price'] * $quantity;
}

try {
    $pdo = db_get_pdo();
    $pdo->beginTransaction();

    // 주문 생성
    $order_query = "
        INSERT INTO orders (member_id, total_price, payment_method, recipient_name, recipient_phone, shipping_address, delivery_note)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ";
    $pdo->prepare($order_query)->execute([
        $member_id, $total_price, $payment_method,
        $recipient_name, $recipient_phone, $shipping_address, $delivery_note
    ]);
    $order_id = $pdo->lastInsertId();

    // 주문 항목 추가
    $order_item_query = "INSERT INTO order_items (order_id, content_code, quantity) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($order_item_query);
    foreach ($content_codes as $index => $code) {
        $stmt->execute([$order_id, $code, $quantities[$index]]);
    }

    $pdo->commit();

    echo "<script>alert('주문이 완료되었습니다. 주문 번호: $order_id'); window.location.href = 'index.php';</script>";
} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    die("주문 처리 중 오류 발생: " . $e->getMessage());
}
?>
