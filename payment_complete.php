<?php
require_once("inc/session.php");
require_once("inc/db.php");

// 주문 ID 확인
$order_id = $_GET['order_id'] ?? null;
if (!$order_id) {
    die("주문 정보가 없습니다.");
}

// 주문 정보 가져오기
$query = "
    SELECT o.order_id, o.total_price, o.final_price, o.payment_method, o.recipient_name, o.recipient_phone, o.shipping_address, o.delivery_note, o.created_at,
           oi.content_code, oi.quantity, c.content_name, c.content_img, c.content_price
    FROM orders o
    JOIN order_items oi ON o.order_id = oi.order_id
    JOIN contents c ON oi.content_code = c.content_code
    WHERE o.order_id = ?
";

$order_info = db_select($query, [$order_id]);

if (empty($order_info)) {
    die("주문 정보를 불러올 수 없습니다.");
}

// 주문 정보 및 주문 항목 정보 분리
$order = $order_info[0];
$order_items = array_slice($order_info, 1);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>결제 완료</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include("inc/header.php"); ?>

    <main class="main_wrapper complete">
        <h1>결제가 완료되었습니다</h1>
        <section class="order_details">
            <h2>주문 상세 정보</h2>
            <p>주문 번호: <?= htmlspecialchars($order['order_id']) ?></p>
            <p>주문자 이름: <?= htmlspecialchars($order['recipient_name']) ?></p>
            <p>연락처: <?= htmlspecialchars($order['recipient_phone']) ?></p>
            <p>주소: <?= nl2br(htmlspecialchars($order['shipping_address'])) ?></p>
            <p>배송 요청사항: <?= nl2br(htmlspecialchars($order['delivery_note'])) ?></p>
            <p>결제 수단: <?= htmlspecialchars($order['payment_method']) ?></p>
            <p>총 결제 금액: <?= number_format($order['final_price']) ?>원</p>
        </section>

        <section class="order_items">
            <h2>주문 상품</h2>
            <table>
                <thead>
                    <tr>
                        <th>이미지</th>
                        <th>상품명</th>
                        <th>수량</th>
                        <th>가격</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order_info as $item): ?>
                        <tr>
                            <td><img src="<?= htmlspecialchars($item['content_img']) ?>" alt="상품 이미지" width="80"></td>
                            <td><?= htmlspecialchars($item['content_name']) ?></td>
                            <td><?= htmlspecialchars($item['quantity']) ?>개</td>
                            <td><?= number_format($item['content_price'] * $item['quantity']) ?>원</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>
