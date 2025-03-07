<?php 
require_once("inc/db.php");

// review_id 가져오기
$review_id = isset($_GET['review_id']) ? intval($_GET['review_id']) : null;

if ($review_id) {
    try {
        // 리뷰와 컨텐츠 데이터를 가져오는 쿼리
        $query = "
            SELECT 
                r.review_id,
                r.member_id,
                r.review_text,
                r.photo,
                r.rating,
                c.content_name,
                c.content_img
            FROM review r
            LEFT JOIN contents c ON r.content_code = c.content_code
            WHERE r.review_id = :review_id";

        // 쿼리 실행
        $result = db_select($query, ['review_id' => $review_id]);
        $review = $result ? $result[0] : null;

    } catch (Exception $e) {
        $review = null;
    }
}
?>

<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css"> <!-- 기본 스타일 -->
    <link rel="stylesheet" href="css/community.css"> <!-- 커뮤니티 스타일 -->
    <link rel="stylesheet" href="css/header.css">   <!-- 헤더 스타일 -->
    <link rel="stylesheet" href="css/footer.css">   <!-- 푸터 스타일 -->
    <link rel="stylesheet" href="css/review_detail.css"> <!-- 리뷰 상세 스타일 -->
    <title>리뷰 상세</title>
</head>

<body>
    <!-- 헤더 -->
    <?php include("inc/header.php"); ?>

    <section class="review-detail-section">
        <div class="review-detail-container">
            <?php if ($review): ?>
                <!-- 상품 이미지와 이름 -->
                <div class="product-header">
                    <div class="product-image">
                        <?php if (!empty($review['content_img'])): ?>
                            <img src="<?= htmlspecialchars($review['content_img']) ?>" alt="<?= htmlspecialchars($review['content_name']) ?>">
                        <?php else: ?>
                            <img src="img/default-product.png" alt="기본 이미지">
                        <?php endif; ?>
                    </div>
                    <div class="product-name">
                        <h1><?= htmlspecialchars($review['content_name']) ?></h1>
                    </div>
                </div>

                <!-- 리뷰 헤더 -->
                <div class="review-header">
                    <p class="review-meta">
                        작성자: <?= htmlspecialchars($review['member_id']) ?> |
                        평점: <span class="review-star">★ <?= htmlspecialchars($review['rating']) ?>/5</span>
                    </p>
                </div>

                <!-- 리뷰 본문 -->
                <div class="review-body">
                    <?php if (!empty($review['photo'])): ?>
                        <div class="review-image">
                            <img src="<?= htmlspecialchars($review['photo']) ?>" alt="리뷰 이미지">
                        </div>
                    <?php endif; ?>
                    <div class="review-content">
                        <p><?= nl2br(htmlspecialchars($review['review_text'])) ?></p>
                    </div>
                </div>
            <?php else: ?>
                <!-- 리뷰가 없을 때 메시지 -->
                <p class="no-review-message">해당 리뷰를 찾을 수 없습니다.</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- 풋터 -->
    <?php include("inc/footer.php"); ?>
</body>

</html>
