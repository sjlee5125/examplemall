<?php
// 세션 파일 및 공통 설정 로드
require_once("inc/session.php");
require_once("inc/db.php"); // DB 함수 포함 파일
require_once("contents.import.php"); // 추가 공통 파일

// BEST 영역 및 카테고리 데이터 가져오기
try {
    // BEST 영역 데이터 가져오기
    $queryBest = "SELECT content_code, content_img, content_name FROM contents WHERE category_large = 'shirts' AND category = 'BEST'";
    $shirtsBestItems = db_select($queryBest); // 모든 BEST 상품 로드

    // 현재 선택된 카테고리 가져오기
    $currentCategory = $_GET['category'] ?? "ALL";

    // 선택된 카테고리의 데이터 가져오기
    if ($currentCategory === "ALL") {
        // ALL일 경우 category_large가 shirts인 데이터만 가져옴
        $queryCategory = "SELECT content_code, content_img, content_name, content_price, discount_rate, category 
                          FROM contents WHERE category_large = 'shirts'";
        $currentItems = db_select($queryCategory);
    } else {
        // 특정 카테고리(패딩, 자켓 등)가 선택된 경우
        $queryCategory = "SELECT content_code, content_img, content_name, content_price, discount_rate, category 
                          FROM contents WHERE category_large = 'shirts' AND category_small = :category";
        $currentItems = db_select($queryCategory, ['category' => $currentCategory]);
    }
} catch (Exception $e) {
    echo "DB 오류: " . $e->getMessage();
    $shirtsBestItems = [];
    $currentItems = [];
}
?>

<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css"> <!-- 메인 스타일 -->
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/category.css"> <!-- 카테고리 전용 스타일 -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css"> <!-- Swiper CSS -->
    <title>SHIRTS</title>
</head>

<body>
    <!-- 헤더 -->
    <?php include("inc/header.php"); ?>

    <!-- BEST 영역 -->
    <div class="best-section-wrapper">
        <div class="best-section">
            <h3 style="text-align: center; font-weight: bold;">SHIRTS BEST ITEM</h3>
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <!-- 반복되는 BEST ITEM -->
                    <?php foreach ($shirtsBestItems as $bestItem): ?>
                        <div class="swiper-slide">
                            <a href="contents_detail.php?content_code=<?php echo urlencode($bestItem['content_code']); ?>">
                                <img src="<?php echo htmlspecialchars($bestItem['content_img']); ?>" alt="<?php echo htmlspecialchars($bestItem['content_name']); ?>">
                                <p><?php echo htmlspecialchars($bestItem['content_name']); ?></p>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
                <!-- 하단 페이지네이션 추가 -->
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </div>

    <?php
    // 카테고리 배열 정의
    $categories = [
        "ALL" => "전체 상품",
        "데님" => "데님",
        "체크" => "체크",
        "무지" => "무지",
    ];

    // 현재 선택된 카테고리 이름 설정 (기본값: SHIRTS)
    $currentCategoryName = $categories[$currentCategory] ?? "SHIRTS";
    ?>

    <!-- 카테고리 메뉴 -->
    <section class="category-menu">
        <h3><?php echo htmlspecialchars($currentCategoryName); ?></h3> <!-- 제목을 동적으로 변경 -->
        <nav>
            <?php foreach ($categories as $key => $name): ?>
                <a href="?category=<?php echo htmlspecialchars($key); ?>" class="<?php echo $currentCategory == $key ? 'active' : ''; ?>">
                    <?php echo htmlspecialchars($name); ?>
                </a>
            <?php endforeach; ?>
        </nav>
    </section>

    <!-- 메인 콘텐츠 -->
    <main class="main_wrapper">
        <div class="product-grid">
            <?php if (!empty($currentItems)): ?>
                <?php foreach ($currentItems as $product): ?>
                    <div class="product-item">
                        <a href="contents_detail.php?content_code=<?php echo htmlspecialchars($product['content_code']); ?>">
                            <!-- 상품 이미지 -->
                            <img src="<?php echo htmlspecialchars($product['content_img']); ?>" alt="<?php echo htmlspecialchars($product['content_name']); ?>" class="product-image">

                            <!-- 상품 이름 -->
                            <p class="product-name"><?php echo htmlspecialchars($product['content_name']); ?></p>

                            <!-- 상품 설명 영역 -->
                            <div class="product-info">
                                <span class="discount-rate">
                                    <?php echo isset($product['discount_rate']) ? htmlspecialchars($product['discount_rate']) . '%' : '0%'; ?>
                                </span>
                                <span class="discounted-price">
                                    <?php echo isset($product['content_price']) ? number_format($product['content_price']) . '원' : '가격 정보 없음'; ?>
                                </span>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>등록된 상품이 없습니다.</p>
            <?php endif; ?>
        </div>
    </main>

    <!-- Swiper JS -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const swiper = new Swiper('.swiper-container', {
                loop: true,
                slidesPerView: 3, // 슬라이드 4개 보이기
                spaceBetween: 20,
                pagination: {
                    el: '.swiper-pagination', // 페이지네이션 연결
                    clickable: true, // 클릭 가능
                },
                breakpoints: {
                    768: {
                        slidesPerView: 2,
                    },
                    1024: {
                        slidesPerView: 3,
                    },
                },
            });
        });
    </script>

    <!-- 푸터 -->
    <?php include("inc/footer.php"); ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
    <script src="../js/common.js"></script> <!-- 공통 자바스크립트 -->
</body>

</html>