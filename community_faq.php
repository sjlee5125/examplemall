<?php
// 데이터베이스 연결
require_once("inc/db.php"); // DB 연결 파일

// FAQ 데이터 가져오기
try {
    // FAQ 데이터를 최신순으로 가져오는 쿼리
    $query = "
        SELECT 
            faq_id, 
            title, 
            content 
        FROM faq
        ORDER BY faq_id DESC";
    $faqs = db_select($query); // 데이터 조회 함수 사용
} catch (Exception $e) {
    // DB 오류 처리
    echo "DB 오류: " . $e->getMessage();
    $faqs = [];
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
    <title>FAQ</title>
    <style>
        /* FAQ 상세 내용 숨기기 */
        .faq-content {
            display: none;
            padding: 10px 20px;
            background-color: #f9f9f9;
            border-left: 3px solid #007acc;
            margin-top: 10px;
            border-radius: 4px;
            color: #333;
        }

        .faq-item.active .faq-content {
            display: block; /* 활성화된 항목의 내용을 표시 */
        }

        .faq-link {
            cursor: pointer;
            text-decoration: none;
            color: #007acc;
            font-weight: bold;
        }

        .faq-link:hover {
            text-decoration: underline;
        }
    </style>
    <script>
        // FAQ 제목 클릭 시 내용 표시/숨기기
        document.addEventListener('DOMContentLoaded', function () {
            const faqItems = document.querySelectorAll('.faq-item');

            faqItems.forEach(item => {
                item.querySelector('.faq-link').addEventListener('click', function (e) {
                    e.preventDefault();
                    // 모든 FAQ 내용을 닫기
                    faqItems.forEach(i => i.classList.remove('active'));
                    // 클릭한 항목 열기/닫기
                    item.classList.toggle('active');
                });
            });
        });
    </script>
</head>

<body class="faq-page">
    <!-- 헤더 -->
    <?php include("inc/header.php"); ?>

    <!-- FAQ 섹션 -->
    <section class="faq-section">
        <div class="container">
            <h1 class="faq-title">FAQ</h1>
            <p class="faq-subtitle">자주 묻는 질문입니다.</p>

            <!-- 탭 메뉴 -->
            <div class="notice-tabs">
                <ul>
                    <li><a href="community_notice.php">NOTICE</a></li>
                    <li><a href="community_review.php">REVIEW</a></li>
                    <li><a href="community_faq.php" class="active">FAQ</a></li>
                </ul>
            </div>

            <!-- FAQ 리스트 -->
            <ul class="faq-list">
                <?php if (!empty($faqs)): ?>
                    <?php foreach ($faqs as $faq): ?>
                        <li class="faq-item">
                            <a href="#" class="faq-link"><?= htmlspecialchars($faq['title']) ?></a>
                            <div class="faq-content">
                                <?= nl2br(htmlspecialchars($faq['content'])) ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="faq-item">등록된 FAQ가 없습니다.</li>
                <?php endif; ?>
            </ul>
        </div>
    </section>

    <!-- 푸터 -->
    <?php include("inc/footer.php"); ?>
</body>

</html>
