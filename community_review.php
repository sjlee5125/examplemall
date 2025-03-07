<?php
// 데이터베이스 연결
require_once("inc/db.php"); // DB 연결 파일

// 리뷰 데이터 가져오기
try {
    // 리뷰 데이터를 최신순으로 가져오는 쿼리
    $query = "
        SELECT 
            r.review_id, 
            r.member_id, 
            r.review_text, 
            r.photo, 
            r.rating, 
            r.review_date
        FROM review r
        ORDER BY r.review_id DESC";
    $reviews = db_select($query); // 데이터 조회 함수 사용
} catch (Exception $e) {
    // DB 오류 처리
    echo "DB 오류: " . $e->getMessage();
    $reviews = [];
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
    <title>리뷰</title>
</head>

<body class="review-page">
    <!-- 헤더 -->
    <?php include("inc/header.php"); ?>

    <!-- 리뷰 섹션 -->
    <section class="review-section">
        <div class="container">
            <h1 class="review-title">REVIEW</h1>
            <p class="review-subtitle">고객 리뷰입니다.</p>

            <!-- 탭 메뉴 -->
            <div class="notice-tabs">
                <ul>
                    <li><a href="community_notice.php">NOTICE</a></li>
                    <li><a href="community_review.php" class="active">REVIEW</a></li>
                    <li><a href="community_faq.php">FAQ</a></li>
                </ul>
            </div>

            <!-- 리뷰 리스트 -->
            <ul class="review-list">
                <?php if (!empty($reviews)): ?>
                    <?php foreach ($reviews as $review): ?>
                        <li class="review-item">
                            <a href="review_detail.php?review_id=<?= htmlspecialchars($review['review_id']) ?>" class="review-link">
                                <!-- 리뷰 사진 -->
                                <div class="review-user-photo">
                                    <?php if (!empty($review['photo'])): ?>
                                        <img src="<?= htmlspecialchars($review['photo']) ?>" alt="리뷰 이미지">
                                    <?php else: ?>
                                        <img src="default-photo.png" alt="기본 이미지">
                                    <?php endif; ?>
                                </div>
                                <!-- 리뷰 내용 -->
                                <div class="review-content">
                                    <div class="review-writer"><?= htmlspecialchars($review['member_id']) ?>님</div>
                                    <div class="review-text"><?= htmlspecialchars(mb_strimwidth($review['review_text'], 0, 50, "...", "UTF-8")) ?></div>
                                </div>
                                <!-- 별점 및 작성시간 -->
                                <div class="review-side-info">
                                    <div class="review-star">★ <?= htmlspecialchars($review['rating']) ?>/5</div>
                                    <div class="review-date"><?= htmlspecialchars($review['review_date']) ?></div>
                                </div>
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="review-item">등록된 리뷰가 없습니다.</li>
                <?php endif; ?>
            </ul>
        </div>
    </section>

    <!-- 푸터 -->
    <?php include("inc/footer.php"); ?>
</body>

</html>
