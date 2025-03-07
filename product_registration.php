<?php
// DB 연결 포함
include('inc/db.php');

// 초기값 설정
$product_code = '';
$product_name = '';
$product_description = '';
$product_image = ''; // 메인 이미지 파일 경로
$colors = ['', '', '', '', ''];
$category='';
$category_large = '';
$category_small = '';
$sizes = ['', '', '']; // 사이즈
$original_price = ''; // 원가
$price = ''; // 가격
$discount_rate = ''; // 할인율
$additional_images = ['', '', '', '']; // 추가 이미지 파일 경로
$is_update_mode = false; // 등록/수정 모드 구분

// 상품 코드가 전달된 경우 해당 데이터 불러오기 (수정 모드)
if (isset($_GET['content_code'])) {
    $is_update_mode = true; // 수정 모드
    $content_code = $_GET['content_code'];
    $query = "SELECT * FROM contents WHERE content_code = ?";
    $product = db_select($query, [$content_code]);

    if ($product && count($product) > 0) {
        $product = $product[0];
        $product_code = $product['content_code'];
        $product_name = $product['content_name'];
        $product_description = $product['content_description'];
        $product_image = $product['content_img']; // 메인 이미지 파일 경로
        $original_price = $product['content_cost'] ?? ''; // 원가
        $price = $product['content_price'] ?? ''; // 가격

        // 할인율 계산 (옵션)
        if (!empty($original_price) && !empty($price)) {
            $discount_rate = round((1 - ($price / $original_price)) * 100, 2);
        }

        // 추가 이미지 파일 경로 가져오기
        for ($i = 1; $i <= 4; $i++) {
            $img_var = "content_img$i";
            $additional_images[$i - 1] = $product[$img_var] ?? '';
        }

        // 색상 가져오기
        for ($i = 1; $i <= 5; $i++) {
            $color_var = "content_color$i";
            $colors[$i - 1] = $product[$color_var] ?? '';
        }

        // 사이즈 가져오기
        for ($i = 1; $i <= 3; $i++) {
            $size_var = "content_size$i";
            $sizes[$i - 1] = $product[$size_var] ?? '';
        }

        // 카테고리 가져오기
        $category= $product['category']??'';
        $category_large = $product['category_large'] ?? '';
        $category_small = $product['category_small'] ?? '';

    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $is_update_mode ? '상품 수정' : '상품 등록' ?></title>
    <link rel="stylesheet" href="css/manager.css">
    <script>
        // 상위 카테고리에 따른 하위 카테고리 매핑
        const subcategories = {
            OUTER: ['패딩', '자켓', '코트', '바람막이'],
            TOP: ['맨투맨', '후드티', '반팔티', '나시'],
            KNIT: ['긴팔니트', '베스트', '가디건', '반팔니트'],
            SHIRTS: ['데님', '체크', '무지'],
            PANTS: ['데님', '슬랙스', '코튼', '트레이닝/조거']
        };

        // 상위 카테고리 변경 시 하위 카테고리를 업데이트
        function updateSubcategories() {
            const categoryLarge = document.getElementById('category_large').value;
            const subcategorySelect = document.getElementById('category_small');

            // 하위 카테고리 초기화
            subcategorySelect.innerHTML = '';

            if (subcategories[categoryLarge]) {
                subcategories[categoryLarge].forEach(subcat => {
                    const option = document.createElement('option');
                    option.value = subcat;
                    option.textContent = subcat;
                    subcategorySelect.appendChild(option);
                });
            }
        }

        // 페이지 로드 시 하위 카테고리 초기화
        document.addEventListener('DOMContentLoaded', () => {
            updateSubcategories();
            // 수정 모드일 때 하위 카테고리 설정 
            if ("<?= $is_update_mode ?>") { // category_large 설정 
            document.getElementById('category_large').value = "<?= $category_large ?>"; updateSubcategories(); // category_small 설정 
            setTimeout(() => { document.getElementById('category_small').value = "<?= $category_small ?>"; }, 100); // 하위 카테고리 로드 완료 후 설정
            }
        });
    </script>
</head>
<body>
    <div class="container">
        <div class="header">
            <ul>
                <a href="#"><li>홈</li></a>
                <a href="manager_member.php"><li>회원관리</li></a>
                <a href="manager_product.php"><li class="active">상품관리</li></a>
                <a href="#"><li>주문관리</li></a>
                <a href="#"><li>고객문의관리</li></a>
                <a href="manager_faq.php"><li>FAQ관리</li></a>
            </ul>
        </div>

        <div class="content">
            <h1><?= $is_update_mode ? '상품 수정' : '상품 등록' ?></h1>
            <!-- action 속성을 동적으로 설정 -->
            <form method="POST" action="<?= $is_update_mode ? 'save_product.php' : 'insert_product.php' ?>">
                <table class="form-table">
                <tr>
                    <th>상품코드</th>
                    <td>
                        <input type="text" name="product_code" 
                            value="<?= $is_update_mode ? htmlspecialchars($product_code) : '' ?>" 
                            <?= $is_update_mode ? 'readonly' : 'required' ?>>
                    </td>
                </tr>


                    <!-- 상품명 -->
                    <tr>
                        <th>상품명</th>
                        <td><input type="text" name="product_name" value="<?= htmlspecialchars($product_name) ?>"></td>
                    </tr>

                    <!-- 제품설명 -->
                    <tr>
                        <th>제품설명</th>
                        <td><textarea name="product_description"><?= htmlspecialchars($product_description) ?></textarea></td>
                    </tr>

                    <!-- 카테고리 -->
                    <tr>
                        <th>카테고리</th>
                        <td>
                            <select name="category_large" id="category_large" onchange="updateSubcategories()">
                                <option value="OUTER" <?= $category_large === 'OUTER' ? 'selected' : '' ?>>OUTER</option>
                                <option value="TOP" <?= $category_large === 'TOP' ? 'selected' : '' ?>>TOP</option>
                                <option value="KNIT" <?= $category_large === 'KNIT' ? 'selected' : '' ?>>KNIT</option>
                                <option value="SHIRTS" <?= $category_large === 'SHIRTS' ? 'selected' : '' ?>>SHIRTS</option>
                                <option value="PANTS" <?= $category_large === 'PANTS' ? 'selected' : '' ?>>PANTS</option>
                            </select>
                            <select name="category_small" id="category_small">
                                <!-- 하위 카테고리는 JavaScript로 설정 -->
                            </select>
                        </td>
                    </tr>
                    <!-- NEW/BEST -->
                    <tr>
                        <th>NEW/BEST</th>
                        <td><input type="text" name="category" placeholder="" value="<?= htmlspecialchars($category) ?>"></td>
                    </tr>

                    <!-- 색상 -->
                    <tr>
                        <th>색상</th>
                        <td>
                            <div class="color-row">
                                <?php for ($i = 0; $i < 5; $i++): ?>
                                    <input type="text" name="colors[]" placeholder="색상 <?= $i + 1 ?>" value="<?= htmlspecialchars($colors[$i]) ?>">
                                <?php endfor; ?>
                            </div>
                        </td>
                    </tr>

                    <!-- 사이즈 -->
                    <tr>
                        <th>사이즈</th>
                        <td>
                            <div class="size-row">
                                <?php for ($i = 0; $i < 3; $i++): ?>
                                    <input type="text" name="sizes[]" placeholder="사이즈 <?= $i + 1 ?>" value="<?= htmlspecialchars($sizes[$i]) ?>">
                                <?php endfor; ?>
                            </div>
                        </td>
                    </tr>

                    <!-- 원가 -->
                    <tr>
                        <th>원가</th>
                        <td><input type="text" name="original_price" placeholder="원가" value="<?= htmlspecialchars($original_price) ?>"></td>
                    </tr>

                    <!-- 가격 -->
                    <tr>
                        <th>가격</th>
                        <td><input type="text" name="price" placeholder="가격" value="<?= htmlspecialchars($price) ?>"></td>
                    </tr>

                    <!-- 할인율 -->
                    <tr>
                        <th>할인율</th>
                        <td>
                            <input type="text" name="discount_rate" placeholder="할인율(%)" value="<?= htmlspecialchars($discount_rate) ?>">
                        </td>
                    </tr>

                    <!-- 메인 이미지 -->
                    <tr>
                        <th>메인 이미지</th>
                        <td>
                            <div>
                                <?php if (!empty($product_image)): ?>
                                    <img src="<?= htmlspecialchars($product_image) ?>" alt="메인 이미지" style="max-width: 150px; max-height: 150px;">
                                <?php endif; ?>
                                <input type="text" name="product_image" placeholder="파일 경로 (예: images/main.jpg)" value="<?= htmlspecialchars($product_image) ?>">
                            </div>
                        </td>
                    </tr>

                    <!-- 추가 이미지 -->
                    <tr>
                        <th>추가 이미지</th>
                        <td>
                            <?php for ($i = 0; $i < 4; $i++): ?>
                                <div>
                                    <?php if (!empty($additional_images[$i])): ?>
                                        <img src="<?= htmlspecialchars($additional_images[$i]) ?>" alt="추가 이미지 <?= $i + 1 ?>" style="max-width: 150px; max-height: 150px;">
                                    <?php endif; ?>
                                    <input type="text" name="additional_images[]" placeholder="추가 이미지 경로 <?= $i + 1 ?>" value="<?= htmlspecialchars($additional_images[$i]) ?>">
                                </div>
                            <?php endfor; ?>
                        </td>
                    </tr>
                </table>
                <div class="form-actions">
                    <!-- 등록/수정 버튼 -->
                    <button type="submit" 
                            formaction="<?= $is_update_mode ? 'save_product.php' : 'insert_product.php' ?>" 
                            class="save-btn">
                        <?= $is_update_mode ? '수정' : '등록' ?>
                    </button>

                    <!-- 삭제 버튼 -->
                    <?php if ($is_update_mode): ?>
                        <button type="submit" 
                                formaction="delete_product.php" 
                                class="delete-btn" 
                                onclick="return confirm('정말로 삭제하시겠습니까? 이 작업은 되돌릴 수 없습니다.');">
                            삭제
                        </button>
                    <?php endif; ?>
                </div>
            </form>
            </form>
        </div>
    </div>
</body>
</html>
