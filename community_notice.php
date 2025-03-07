<?php
// 데이터베이스 연결
require_once("inc/db.php"); // DB 연결 파일

// 공지사항 데이터 가져오기
try {
    // 공지사항 데이터를 최신순으로 가져오는 쿼리
    $query = "SELECT notice_id, title, writer, created_at, views FROM notice ORDER BY created_at DESC";
    $notices = db_select($query); // 데이터 조회 함수 사용
} catch (Exception $e) {
    // DB 오류 처리
    echo "DB 오류: " . $e->getMessage();
    $notices = [];
}
?>

<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css"> <!-- 기존 스타일 -->
    <link rel="stylesheet" href="css/community.css"> <!-- 공지사항 스타일 -->
    <link rel="stylesheet" href="css/header.css">   <!-- 헤더 스타일 -->
    <link rel="stylesheet" href="css/footer.css">   <!-- 푸터 스타일 -->
    <title>공지사항</title>
</head>

<body class="notice-page">
    <!-- 헤더 -->
    <?php include("inc/header.php"); ?>

    <!-- 공지사항 섹션 -->
    <section class="notice-section">
        <div class="container">
            <h1 class="notice-title">NOTICE</h1>
            <p class="notice-subtitle">공지사항입니다.</p>
            
            <!-- 탭 메뉴 -->
            <div class="notice-tabs">
                <ul>
                    <li><a href="community_notice.php" class="active">NOTICE</a></li>
                    <li><a href="community_review.php">REVIEW</a></li>
                    <li><a href="community_faq.php">FAQ</a></li>
                </ul>
            </div>

            <!-- 공지사항 리스트 -->
            <ul class="notice-list">
                <?php if (!empty($notices)): ?>
                    <?php foreach ($notices as $notice): ?>
                        <li class="notice-item">
                            <span class="notice-category">공지 ★</span>
                            <!-- 공지사항 상세보기 링크 -->
                            <a href="notice_detail.php?id=<?= htmlspecialchars($notice['notice_id']) ?>" class="notice-link">
                                <?= htmlspecialchars($notice['title']) ?>
                            </a>
                            <span class="notice-date"><?= date("Y-m-d", strtotime($notice['created_at'])) ?></span>
                            <span class="notice-views">조회 <?= htmlspecialchars($notice['views']) ?></span>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- 공지사항이 없을 경우 -->
                    <li class="notice-item">등록된 공지사항이 없습니다.</li>
                <?php endif; ?>
            </ul>
        </div>
    </section>

    <!-- 푸터 -->
    <?php include("inc/footer.php"); ?>
</body>

</html>
