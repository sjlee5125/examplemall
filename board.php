<?php
require_once("inc/db.php");
require_once("inc/session.php");

$member_id = $_SESSION['member_id'] ?? null;
if (!$member_id) {
    header("Location: login.php");
    exit;
}

// 카테고리 확인
$category = $_GET['category'] ?? 'my_reviews';

// 내가 작성한 리뷰 데이터 가져오기
$reviews_query = "
    SELECT 
        r.review_id,
        r.member_id,
        r.order_id,
        r.content_code,
        r.review_text,
        r.photo,
        r.rating,
        r.review_date,
        c.content_name,
        c.content_img
    FROM review r
    JOIN contents c ON r.content_code = c.content_code
    WHERE r.member_id = ?
    ORDER BY r.review_date DESC
";
$reviews = db_select($reviews_query, [$member_id]);

// 작성 가능한 리뷰 데이터 가져오기
$reviewable_query = "
    SELECT 
        o.order_id,                 -- 주문 번호
        c.content_code,             -- 콘텐츠 코드
        c.content_name,             -- 콘텐츠 이름
        c.content_img,              -- 콘텐츠 이미지
        oi.quantity                 -- 주문 수량
    FROM orders o
    JOIN order_items oi ON o.order_id = oi.order_id
    JOIN contents c ON oi.content_code = c.content_code
    LEFT JOIN review r 
        ON r.order_id = o.order_id 
        AND r.content_code = c.content_code 
        AND r.member_id = ?
    WHERE o.member_id = ? 
      AND r.review_id IS NULL
    ORDER BY o.order_date DESC
";
$reviewable_items = db_select($reviewable_query, [$member_id, $member_id]);

$display_data = $category === 'my_reviews' ? $reviews : $reviewable_items;
?>


<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>게시판</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/board.css">
    <link rel="stylesheet" href="css/footer.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <?php include("inc/header.php"); ?>

    <main>
        <h2 class="board-title">게시판</h2>
        <div class="filter-section">
            <form method="GET" action="board.php">
                <select name="category">
                    <option value="my_reviews" <?= $category === 'my_reviews' ? 'selected' : '' ?>>내가 작성한 리뷰</option>
                    <option value="reviewable" <?= $category === 'reviewable' ? 'selected' : '' ?>>작성 가능한 리뷰</option>
                </select>
                <button type="submit" class="filter-button">필터</button>
            </form>
        </div>

        <table class="board-table">
            <thead>
                <tr>
                    <th>주문번호</th>
                    <th>이미지</th>
                    <th>상품명</th>
                    <th>내용</th>
                    <th>작성일</th>
                    <th>액션</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($display_data)): ?>
                    <?php foreach ($display_data as $data): ?>
                        <tr>
                            <td><?= htmlspecialchars($data['order_id']) ?></td>
                            <td>
                                <img src="<?= htmlspecialchars($data['content_img']) ?>" alt="상품 이미지" class="review-image">
                            </td>
                            <td><?= htmlspecialchars($data['content_name']) ?></td>
                            <td>
                                <?php if ($category === 'my_reviews'): ?>
                                    <!-- 내가 작성한 리뷰 -->
                                    <?= nl2br(htmlspecialchars($data['review_text'] ?? '')) ?><br>
                                    <span class="rating">
                                        <?= str_repeat('★', $data['rating']) . str_repeat('☆', 5 - $data['rating']) ?>
                                    </span>
                                    <?php if ($data['photo']): ?>
                                        <br><img src="<?= htmlspecialchars($data['photo']) ?>" alt="리뷰 이미지" class="review-photo">
                                    <?php endif; ?>
                                <?php else: ?>
                                    <!-- 작성 가능한 리뷰 -->
                                    리뷰를 작성할 수 있습니다.
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($data['review_date'] ?? '-') ?></td>
                            <td>
                                <button onclick="openPopup(
    '<?= htmlspecialchars($data['review_id'] ?? '') ?>',
    '<?= htmlspecialchars($data['order_id'] ?? '') ?>',  // order_id 없으면 빈 값 전달
    '<?= htmlspecialchars($data['content_code']) ?>',
    '<?= htmlspecialchars($data['content_name']) ?>',
    '<?= htmlspecialchars($data['content_img']) ?>',
    '<?= htmlspecialchars($data['review_text'] ?? '') ?>',
    '<?= htmlspecialchars($data['rating'] ?? 5) ?>'
)">
    <?= isset($data['review_id']) ? '리뷰 수정' : '리뷰 작성' ?>
</button>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">
                            <?= $category === 'my_reviews' ? '작성된 리뷰가 없습니다.' : '작성 가능한 리뷰가 없습니다.' ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>

    <div id="popup" class="popup-overlay">
        <div class="popup-content">
            <h2>리뷰 작성/수정</h2>
            <img id="popup-img" src="" alt="상품 이미지">
            <p id="popup-name"></p>
            <form action="submit_review.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="review_id" id="popup-review-id">
                <input type="hidden" name="content_code" id="popup-content-code">
                <input type="hidden" name="order_id" id="popup-order-id">
                <textarea name="review_text" id="popup-text" placeholder="리뷰를 작성하세요" required></textarea>
                <select name="rating" id="popup-rating" required>
                    <option value="5">★★★★★</option>
                    <option value="4">★★★★☆</option>
                    <option value="3">★★★☆☆</option>
                    <option value="2">★★☆☆☆</option>
                    <option value="1">★☆☆☆☆</option>
                </select>
                <label for="photo">이미지 업로드:</label>
                <input type="file" name="photo" id="photo">
                <button type="submit" class="save-btn">저장</button>
                <button type="button" class="close-btn" onclick="closePopup()">닫기</button>
            </form>
        </div>
    </div>

    <script>
        function openPopup(name, img, text, rating, reviewId, contentCode, orderId) {
            document.getElementById('popup-name').innerText = name;
            document.getElementById('popup-img').src = img;
            document.getElementById('popup-text').value = text || '';
            document.getElementById('popup-rating').value = rating || 5;
            document.getElementById('popup-review-id').value = reviewId || '';
            document.getElementById('popup-content-code').value = contentCode || '';
            document.getElementById('popup-order-id').value = orderId || '';
            document.getElementById('popup').style.display = 'flex';
        }

        function closePopup() {
            document.getElementById('popup').style.display = 'none';
        }
    </script>
    <?php include("inc/footer.php"); ?>
</body>
</html>
