<?php
require_once("inc/db.php");
require_once("inc/session.php");

// 로그인된 사용자 확인
$member_id = $_SESSION['member_id'] ?? null;
if (!$member_id) {
    header("Location: login.php");
    exit;
}

// 주문 내역 가져오기
$query = "
    SELECT 
        o.order_id,
        o.order_date,
        o.total_price,
        o.status,
        oi.quantity,
        c.content_code,
        c.content_name,
        c.content_img,
        c.content_price,
        IFNULL(r.review_id, 0) AS review_id,
        IFNULL(r.review_text, '') AS review_text, -- review_text로 수정
        IFNULL(r.rating, 0) AS rating,           -- rating 컬럼 사용
        IFNULL(r.photo, '') AS photo
    FROM orders o
    JOIN order_items oi ON o.order_id = oi.order_id
    JOIN contents c ON oi.content_code = c.content_code
    LEFT JOIN review r 
        ON r.content_code = c.content_code AND r.order_id = o.order_id AND r.member_id = ?
    WHERE o.member_id = ?
    ORDER BY o.order_date DESC";

$orders = db_select($query, [$member_id, $member_id]);

?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>주문 내역</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/order_history.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script>
        function openPopup(reviewId, orderId, contentCode, contentName, contentImg, reviewText, rating) {
            const params = new URLSearchParams({
                review_id: reviewId,
                order_id: orderId,
                content_code: contentCode,
                content_name: contentName,
                content_img: contentImg,
                review_text: reviewText,
                rating: rating
            });

            const url = `review_popup.php?${params.toString()}`;
            const options = "width=600,height=500,resizable=no,scrollbars=no,status=no";
            window.open(url, "리뷰 작성/수정", options);
        }
    </script>
</head>

<body>
    <?php include("inc/header.php"); ?>

    <main class="order_main">
        <h1>주문 내역</h1>

        <section class="order_table_section">
            <table class="order_table">
                <thead>
                    <tr>
                        <th>주문일자<br>[주문번호]</th>
                        <th>이미지</th>
                        <th>상품 정보</th>
                        <th>수량</th>
                        <th>상품 구매 금액</th>
                        <th>주문 상태</th>
                        <th>리뷰</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($orders)): ?>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td>
                                    <?= htmlspecialchars($order['order_date']) ?><br>
                                    [<?= htmlspecialchars($order['order_id']) ?>]
                                </td>
                                <td>
                                    <img src="<?= htmlspecialchars($order['content_img']) ?>" alt="상품 이미지" class="product_image">
                                </td>
                                <td><?= htmlspecialchars($order['content_name']) ?></td>
                                <td><?= htmlspecialchars($order['quantity']) ?>개</td>
                                <td><?= number_format($order['content_price'] * $order['quantity']) ?>원</td>
                                <td><?= htmlspecialchars($order['status']) ?></td>
                                <td>
                                    <button onclick="openPopup(
                                        '<?= $order['review_id'] ?>',
                                        '<?= $order['order_id'] ?>',
                                        '<?= $order['content_code'] ?>',
                                        '<?= htmlspecialchars($order['content_name'], ENT_QUOTES) ?>',
                                        '<?= htmlspecialchars($order['content_img'], ENT_QUOTES) ?>',
                                        '<?= htmlspecialchars($order['review_text'], ENT_QUOTES) ?>',
                                        '<?= $order['rating'] ?>'
                                    )">
                                        <?= $order['review_id'] > 0 ? '리뷰 수정' : '리뷰 작성' ?>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">주문 내역이 없습니다.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>
    <?php include("inc/footer.php"); ?>
</body>
</html>
