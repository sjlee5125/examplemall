<?php
// DB 연결 포함
include('inc/db.php');

// 페이지네이션 설정
$items_per_page = 10; // 한 페이지에 표시할 항목 수
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // 현재 페이지
$page = $page > 0 ? $page : 1; // 페이지가 0 이하일 경우 1로 설정
$offset = ($page - 1) * $items_per_page; // 시작점 계산

// 검색 조건 처리
$search_text = $_GET['search_text'] ?? ''; // 검색어
$category_large = $_GET['category_large'] ?? ''; // 상위 카테고리
$category_small = $_GET['category_small'] ?? ''; // 하위 카테고리
$price_min = $_GET['price_min'] ?? ''; // 최소 가격
$price_max = $_GET['price_max'] ?? ''; // 최대 가격

// 상위 및 하위 카테고리 데이터
$categories = [
    'OUTER' => ['패딩', '자켓', '코트', '바람막이'],
    'TOP' => ['맨투맨', '후드티', '반팔티', '나시'],
    'KNIT' => ['긴팔니트', '베스트', '가디건', '반팔니트'],
    'SHIRTS' => ['데님', '체크', '무지'],
    'PANTS' => ['데님', '슬랙스', '코튼', '트레이닝/조거']
];

// WHERE 절 생성
$where_clauses = [];
if (!empty($search_text)) {
    $where_clauses[] = "content_name LIKE '%" . addslashes($search_text) . "%'";
}
if (!empty($category_large)) {
    $where_clauses[] = "category_large = '" . addslashes($category_large) . "'";
}
if (!empty($category_small)) {
    $where_clauses[] = "category_small = '" . addslashes($category_small) . "'";
}
if (!empty($price_min) && is_numeric($price_min)) {
    $where_clauses[] = "content_price >= " . (int)$price_min;
}
if (!empty($price_max) && is_numeric($price_max)) {
    $where_clauses[] = "content_price <= " . (int)$price_max;
}

$where_sql = '';
if (count($where_clauses) > 0) {
    $where_sql = 'WHERE ' . implode(' AND ', $where_clauses);
}

// 총 상품 개수 가져오기
$total_items_query = "SELECT COUNT(*) AS total FROM contents $where_sql";
$total_items_result = db_select($total_items_query);
$total_items = $total_items_result[0]['total'] ?? 0;

// 총 페이지 수 계산
$total_pages = ceil($total_items / $items_per_page);

// 상품 데이터 가져오기 (상품 코드 역순 정렬)
$query = "SELECT content_img, content_code, content_name, content_description, content_price 
          FROM contents $where_sql 
          ORDER BY content_code DESC 
          LIMIT $offset, $items_per_page";
$products = db_select($query);
?>


<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>상품 관리</title>
    <link rel="stylesheet" href="css/manager.css">
    <script>
        const subcategories = <?= json_encode($categories) ?>;

        // 하위 카테고리 업데이트
        function updateSubcategories() {
            const categoryLarge = document.getElementById('category_large').value;
            const subcategorySelect = document.getElementById('category_small');
            subcategorySelect.innerHTML = '<option value="">전체</option>';

            if (subcategories[categoryLarge]) {
                subcategories[categoryLarge].forEach(subcat => {
                    const option = document.createElement('option');
                    option.value = subcat;
                    option.textContent = subcat;
                    subcategorySelect.appendChild(option);
                });
            }
        }

        // 초기 하위 카테고리 설정
        document.addEventListener('DOMContentLoaded', () => {
            const initialCategory = "<?= addslashes($category_large) ?>";
            if (initialCategory) {
                document.getElementById('category_large').value = initialCategory;
                updateSubcategories();
                document.getElementById('category_small').value = "<?= addslashes($category_small) ?>";
            }
        });
    </script>
</head>
<body>
    <div class="container">
        <!-- 상단 메뉴 -->
        <div class="header">
            <ul>
                <a href="index.php"><li>홈</li></a>
                <a href="manager_member.php"><li>회원관리</li></a>
                <a href="manager_product.php"><li class="active">상품관리</li></a>
                <a href="manager_notice.php"><li>공지사항관리</li></a>
                <a href="manager_review.php"><li>리뷰관리</li></a>
                <a href="manager_faq.php"><li>FAQ관리</li></a>
            </ul>
        </div>

        <!-- 검색 폼 -->
        <div class="content">
            <h1>상품 관리</h1>
            <form class="search-form" method="GET">
                <div class="form-group">
                    <label for="search_text">검색어</label>
                    <input type="text" name="search_text" placeholder="상품명을 입력하세요" value="<?= htmlspecialchars($search_text) ?>">
                </div>
                <div class="category-group">
                    <label for="category_large">카테고리</label>
                    <select name="category_large" id="category_large" onchange="updateSubcategories()">
                        <option value="">상위 카테고리</option>
                        <?php foreach (array_keys($categories) as $key): ?>
                            <option value="<?= $key ?>" <?= $key === $category_large ? 'selected' : '' ?>><?= $key ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select name="category_small" id="category_small">
                        <option value="">하위 카테고리</option>
                        <!-- 하위 카테고리는 JavaScript로 설정 -->
                    </select>
                </div>
                <div class="form-group">
                    <label for="price_min">가격</label>
                    <input type="number" name="price_min" placeholder="최소" value="<?= htmlspecialchars($price_min) ?>">
                    ~
                    <input type="number" name="price_max" placeholder="최대" value="<?= htmlspecialchars($price_max) ?>">
                </div>
                <div class="form-actions">
                    <button type="submit">검색</button>
                    <button type="reset" onclick="window.location.href='?';">초기화</button>
                    <button type="button" onclick="window.location.href='product_registration.php';">등록</button>
                </div>
            </form>

            <!-- 상품 목록 -->
            <table class="product-table">
                <thead>
                    <tr>
                        <th>번호</th>
                        <th>이미지</th>
                        <th>상품코드</th>
                        <th>상품명</th>
                        <th>상품 설명</th>
                        <th>가격</th>
                        <th>수정</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($products && count($products) > 0) {
                        $number = $total_items - $offset; // 역순 번호 계산
                        foreach ($products as $product) {
                            echo "<tr>";
                            echo "<td>" . $number-- . "</td>";
                            echo "<td><img src='" . htmlspecialchars($product['content_img']) . "' alt='상품 이미지'></td>";
                            echo "<td>" . htmlspecialchars($product['content_code']) . "</td>";
                            echo "<td>" . htmlspecialchars($product['content_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($product['content_description']) . "</td>";
                            echo "<td>" . htmlspecialchars(number_format($product['content_price'])) . "원</td>";
                            echo "<td>";
                            echo "<form method='GET' action='product_registration.php'>";
                            echo "<input type='hidden' name='content_code' value='" . htmlspecialchars($product['content_code']) . "'>";
                            echo "<button type='submit'>수정</button>";
                            echo "</form>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>상품이 없습니다.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

            <!-- 페이지네이션 -->
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?= $page - 1 ?>">이전</a>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?= $i ?>" class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
                <?php endfor; ?>
                <?php if ($page < $total_pages): ?>
                    <a href="?page=<?= $page + 1 ?>">다음</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
