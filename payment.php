<?php
require_once("inc/session.php");
require_once("inc/db.php");

// 로그인된 사용자 확인
$member_id = $_SESSION['member_id'] ?? null;
if (!$member_id) {
    header("Location: login.php");
    exit;
}

// POST 데이터 확인
if (empty($_POST['content_codes']) || empty($_POST['quantities'])) {
    echo "상품 정보를 불러올 수 없습니다. POST 데이터가 비어 있습니다.";
    echo "<pre>";
    print_r($_POST); // 디버깅용
    echo "</pre>";
    exit;
}

$content_codes = $_POST['content_codes'];
$quantities = $_POST['quantities'];

// 상품 정보 가져오기
$placeholders = implode(',', array_fill(0, count($content_codes), '?'));
$query = "
    SELECT content_code, content_name, content_price, content_img 
    FROM contents 
    WHERE content_code IN ($placeholders)
";
$product_info = db_select($query, $content_codes);

// 상품 정보를 content_code 기준으로 매핑
$products = [];
foreach ($product_info as $product) {
    $products[$product['content_code']] = $product;
}

// POST 데이터가 비정상적인 경우
if (empty($products)) {
    echo "상품 정보를 불러올 수 없습니다. 데이터베이스에서 해당 상품을 찾을 수 없습니다.";
    echo "<pre>";
    print_r($content_codes); // 디버깅용
    echo "</pre>";
    exit;
}

// 총 결제 금액 계산
$total_price = 0;
foreach ($content_codes as $index => $code) {
    if (isset($products[$code])) {
        $quantity = $quantities[$index];
        $total_price += $products[$code]['content_price'] * $quantity;
    }
}

// 주문 완료 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $recipient_name = $_POST['recipient_name'];
    $recipient_phone = $_POST['recipient_phone'];
    $shipping_address = $_POST['shipping_address'];
    $delivery_note = $_POST['delivery_note'];
    $used_points = $_POST['used_points'] ?? 0;
    $payment_method = $_POST['payment_method'];

    // 최종 결제 금액 계산
    $final_price = $total_price - $used_points;

    try {
        $pdo = db_get_pdo();
        $pdo->beginTransaction();

        // 주문 데이터 삽입
        $order_query = "
            INSERT INTO orders (member_id, total_price, used_points, final_price, payment_method, recipient_name, recipient_phone, shipping_address, delivery_note, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'completed')
        ";
        $stmt = $pdo->prepare($order_query);
        $stmt->execute([
            $member_id, $total_price, $used_points, $final_price, $payment_method,
            $recipient_name, $recipient_phone, $shipping_address, $delivery_note
        ]);

        $order_id = $pdo->lastInsertId();

        // 주문 항목 데이터 삽입
        $order_item_query = "INSERT INTO order_items (order_id, content_code, quantity) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($order_item_query);
        foreach ($content_codes as $index => $code) {
            $stmt->execute([$order_id, $code, $quantities[$index]]);
        }

        $pdo->commit();

        // 결제 완료 페이지로 리다이렉트
        header("Location: payment_complete.php?order_id=$order_id");
        exit;
    } catch (PDOException $ex) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        die("주문 처리 중 오류 발생: " . $ex->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>결제 페이지</title>
    <link rel="stylesheet" href="css/payment.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <?php include("inc/header.php"); ?>

    <main class="payment_main">
        <h1>결제 페이지</h1>
        <form method="POST" class="payment_form">
            <!-- 주문자 정보 -->
            <section class="orderer_info">
                <h2>주문자 정보</h2>
                <label for="recipient_name">이름</label>
                <input type="text" id="recipient_name" name="recipient_name" required>

                <label for="recipient_phone">연락처</label>
                <input type="text" id="recipient_phone" name="recipient_phone" required>
            </section>

            <!-- 배송지 정보 -->
            <section class="shipping_info">
                <h2>배송지 정보</h2>
                <label for="shipping_address">주소</label>
                <textarea id="shipping_address" name="shipping_address" required></textarea>

                <label for="delivery_note">배송 요청사항</label>
                <textarea id="delivery_note" name="delivery_note"></textarea>
            </section>

            <!-- 주문 상품 정보 -->
            <section class="product_summary">
                <h2>주문 상품 정보</h2>
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
                        <?php foreach ($content_codes as $index => $code): ?>
                            <?php if (isset($products[$code])): ?>
                                <tr>
                                    <td><img src="<?= htmlspecialchars($products[$code]['content_img']) ?>" alt="상품 이미지" width="80"></td>
                                    <td><?= htmlspecialchars($products[$code]['content_name']) ?></td>
                                    <td><?= htmlspecialchars($quantities[$index]) ?></td>
                                    <td><?= number_format($products[$code]['content_price'] * $quantities[$index]) ?>원</td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>

            <!-- 포인트 사용 및 결제 수단 -->
            <section class="payment_details">
                <h2>결제 정보</h2>
                <label for="used_points">포인트 사용</label>
                <input type="number" id="used_points" name="used_points" value="0" min="0" max="10000">

                <label for="payment_method">결제 수단</label>
                <select id="payment_method" name="payment_method" required>
                    <option value="credit_card">신용카드</option>
                    <option value="bank_transfer">계좌이체</option>
                    <option value="mobile_payment">휴대폰 결제</option>
                </select>

                <p>총 결제 금액: <strong><?= number_format($total_price) ?>원</strong></p>
            </section>

            <!-- 주문 버튼 -->
            <section class="submit_button">
                <button type="submit" name="place_order">주문하기</button>
            </section>
        </form>
    </main>
</body>
</html>
                                