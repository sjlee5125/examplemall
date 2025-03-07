<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>검색 페이지</title>

    <!-- 공통 스타일 -->
    <link rel="stylesheet" href="/examplemall/css/style.css">

    <!-- 개별 스타일 -->
    <link rel="stylesheet" href="/examplemall/css/search.css">
    <link rel="stylesheet" href="/examplemall/css/header.css">
    <link rel="stylesheet" href="/examplemall/css/footer.css">

</head>

<body>
    <?php
    // header.php 파일 포함
    include("inc/header.php");

    // 데이터베이스 연결 설정
    require_once("inc/db.php");

    // 검색어 처리
    $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
    $results = [];

    if (!empty($keyword)) {
        try {
            // content_name만을 기준으로 검색하도록 수정
            $query = "SELECT content_code, content_name, content_description, content_price, content_img 
                      FROM contents 
                      WHERE content_name LIKE :keyword";
            $params = [':keyword' => "%$keyword%"];
            $results = db_select($query, $params);
        } catch (Exception $e) {
            echo "<p>DB 오류: " . $e->getMessage() . "</p>";
        }
    }
    ?>
    <div class="search-page">
        <div class="search-container">
            <h2>검색 결과</h2>
            <form method="get" action="search.php" class="search-form">
                <input type="text" name="keyword" class="search-input" placeholder="검색어를 입력하세요" value="<?php echo htmlspecialchars($keyword); ?>" />
                <button type="submit" class="search-button">검색</button>
            </form>

            <div class="search-results">
                <?php if (empty(trim($keyword))): ?>
                    <p>검색어를 입력해주세요.</p>
                <?php elseif (!empty($results)): ?>
                    <div class="product-grid">
                        <?php foreach ($results as $item): ?>
                            <div class="product-item">
                                <a href="contents_detail.php?content_code=<?php echo urlencode($item['content_code']); ?>" class="product-link">
                                    <img src="<?php echo htmlspecialchars($item['content_img']); ?>" alt="<?php echo htmlspecialchars($item['content_name']); ?>" class="product-image">
                                    <div class="product-name"><?php echo htmlspecialchars($item['content_name']); ?></div>
                                    <div class="product-info">
                                        <span class="discounted-price"><?php echo number_format($item['content_price']); ?>원</span>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="no-results">
                        <p>검색 결과가 없습니다.</p>
                        <p>다른 검색어를 시도해주세요.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php
        // footer.php 파일 포함
        include("inc/footer.php");
        ?>
</body>

</html>
