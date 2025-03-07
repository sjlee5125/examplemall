<?php require_once("contents.import.php");

// MAIN 카테고리 상품 가져오기
$main_products = db_select("SELECT * FROM contents WHERE category_large = 'MAIN'");
?>


<!--  php 오류 숨김 코드	-->
<?php ini_set('display_errors', '0'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <title>Exam Mall</title>
</head>

<body>
    <?php include 'inc/header.php'; ?>
    <?php $search_main = $_POST['search_main']; ?>

    <main class="main_wrapper">
        <!-- 슬라이더 배너 영역 -->
        <div class="banner_main">
            <div class="slider">
                <?php
                if (isset($result['MAIN']) && !empty($result['MAIN'])) {
                    foreach ($result['MAIN'] as $product) {
                        echo '<div class="slide">';
                        echo '<a href="contents_detail.php?content_code=' . htmlspecialchars($product['content_code']) . '">';
                        echo '<img src="' . htmlspecialchars($product['content_img']) . '" alt="' . htmlspecialchars($product['content_name']) . '">';
                        echo '</a>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>표시할 메인 상품이 없습니다.</p>';
                }
                ?>
            </div>
            <button class="arrow left-arrow" onclick="prevSlide()">&#10094;</button>
            <button class="arrow right-arrow" onclick="nextSlide()">&#10095;</button>
        </div>

        <!-- BEST 상품 영역 -->
        <div class="recommend_main_wrapper">
            <div class="recommend_main_header">
                <span class="recommend_main_title">BEST</span>
                <div class="sort_nav_wrapper">
                    <?php
                    // 카테고리 배열 생성
                    $categories = ["OUTER", "TOP", "KNIT", "SHIRTS", "PANTS"];
                    foreach ($categories as $index => $category) {
                        echo '<div class="sort_nav' . ($index + 1) . ' sort_nav' . ($index == 0 ? ' active' : '') . '" data-category="' . $category . '"><span>' . $category . '</span></div>';
                    }
                    ?>
                </div>
            </div>

            <div class="recommend_main_content_wrapper">
                <ul class="recommend_main_contents">
                    <?php
                    // 카테고리별 BEST 상품 가져오기
                    foreach ($categories as $category) {
                        $query = "SELECT content_code, content_img, content_name, discount_rate, content_price
                                  FROM contents 
                                  WHERE category_large = :category AND category = 'BEST'";
                        $products = db_select($query, ['category' => $category]);

                        foreach ($products as $product) { ?>
                            <a href="contents_detail.php?content_code=<?php echo htmlspecialchars($product['content_code']); ?>">
                                <li class="recommend_main_content" data-category="<?php echo htmlspecialchars($category); ?>" <?php if ($category !== "OUTER") echo 'style="display: none;"'; ?>>
                                    <div class="content_img_wrapper">
                                        <img src="<?php echo htmlspecialchars($product['content_img']); ?>" alt="<?php echo htmlspecialchars($product['content_name']); ?>" />
                                    </div>
                                    <div class="content_text_wrapper">
                                        <div class="content_name_wrapper">
                                            <span class="content_name"><?php echo htmlspecialchars($product['content_name']); ?></span>
                                        </div>
                                        <div class="content_price_wrapper">
                                            <span class="discount_rate"><?php echo htmlspecialchars($product['discount_rate']); ?>%</span>
                                            <span class="content_price"><?php echo number_format($product['content_price']); ?>원</span>
                                        </div>
                                    </div>
                                </li>
                            </a>
                    <?php }
                    } ?>
                </ul>
            </div>


            </ul>
        </div>
        </div>

        <script>
            // 카테고리 필터링 스크립트
            document.querySelectorAll('.sort_nav').forEach(nav => {
                nav.addEventListener('click', function() {
                    const category = this.dataset.category;

                    // 탭 활성화 상태 변경
                    document.querySelectorAll('.sort_nav').forEach(nav => nav.classList.remove('active'));
                    this.classList.add('active');

                    // 해당 카테고리 항목만 표시
                    document.querySelectorAll('.recommend_main_content').forEach(content => {
                        content.style.display = content.dataset.category === category ? 'block' : 'none';
                    });
                });
            });
        </script>

        <!-- NEW 상품 영역 -->
        <div class="new_main_wrapper">
            <div class="new_main_header">
                <span class="new_main_title">NEW</span>
            </div>
            <div class="new_main_content_wrapper">
                <ul class="new_main_contents">
                    <?php
                    // NEW 카테고리 데이터가 있는지 확인하고 출력
                    if (isset($result['NEW']) && is_array($result['NEW'])) {
                        foreach ($result['NEW'] as $new) { ?>
                            <a href="contents_detail.php?content_code=<?php echo htmlspecialchars($new['content_code']); ?>">
                                <li class="new_main_content">
                                    <div class="content_img_wrapper">
                                        <img src="<?php echo htmlspecialchars($new['content_img']); ?>" alt="" />
                                    </div>
                                    <div class="content_text_wrapper">
                                        <div class="content_name_wrapper">
                                            <span class="content_name"><?php echo htmlspecialchars($new['content_name']); ?></span>
                                        </div>
                                        <div class="content_price_wrapper">
                                            <span class="discount_rate"><?php echo htmlspecialchars($new['discount_rate']); ?>%</span>
                                            <span class="content_price"><?php echo number_format($new['content_price']); ?>원</span>
                                        </div>
                                    </div>
                                </li>
                            </a>
                    <?php }
                    }
                    ?>
                </ul>
            </div>
        </div>
    </main>

    <?php include("inc/footer.php"); ?>

    <!-- 슬라이더 구현 -->
    <script>
        let currentIndex = 0;
        const slides = document.querySelectorAll('.slide');
        const totalSlides = slides.length;

        function showSlide(index) {
            const slider = document.querySelector('.slider');
            slider.style.transform = `translateX(-${index * 100}%)`;
        }

        function nextSlide() {
            currentIndex = (currentIndex + 1) % totalSlides; // 마지막 슬라이드에서 처음으로 순환
            showSlide(currentIndex);
        }

        function prevSlide() {
            currentIndex = (currentIndex - 1 + totalSlides) % totalSlides; // 처음 슬라이드에서 마지막으로 순환
            showSlide(currentIndex);
        }

        // 자동 슬라이드
        setInterval(nextSlide, 3000);

        // 초기 슬라이드 설정
        showSlide(currentIndex);

        // 카테고리 필터링 스크립트
        document.querySelectorAll('.sort_nav').forEach(nav => {
            nav.addEventListener('click', function() {
                const category = this.dataset.category;

                // 탭 활성화 상태 변경
                document.querySelectorAll('.sort_nav').forEach(nav => nav.classList.remove('active'));
                this.classList.add('active');

                // 해당 카테고리 항목만 표시
                document.querySelectorAll('.recommend_main_content').forEach(content => {
                    content.style.display = content.dataset.category === category || category === "ALL" ? 'block' : 'none';
                });
            });
        });
    </script>


    <script src="https://kit.fontawesome.com/73fbcb87e6.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
    <script src="js/hot_issue.js"></script>
    <script src="js/member.js"></script>
    <script src="js/sort.js"></script>

</body>

</html>