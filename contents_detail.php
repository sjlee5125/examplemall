<?php
require_once("inc/session.php");
require_once("inc/db.php");

// 상품 코드 가져오기
$content_code = $_GET["content_code"] ?? null;
if (!$content_code) {
    die("상품 코드를 확인할 수 없습니다.");
}

// 상품 정보 가져오기
$result = db_select("SELECT * FROM contents WHERE content_code = ?", [$content_code]);
if (!$result) {
    die("상품 정보를 찾을 수 없습니다.");
}
$result = $result[0]; // 첫 번째 결과만 사용
?>

<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HARU - 상품 상세</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <?php include("inc/header.php"); ?>

    <main class="main_wrapper contents_detail">
        <form class="top" name="contents_form" method="POST">
            <!-- 좌측 이미지 섹션 -->
            <section class="top_left">
                <div class="category_info">
                    <?= htmlspecialchars($result["category_large"]); ?> > <?= htmlspecialchars($result["category_small"]); ?>
                </div>
                <div class="main_img">
                    <img src="<?= htmlspecialchars($result["content_img"]); ?>" alt="상품 이미지">
                </div>
                <div class="imgs">
                    <?php for ($i = 1; $i <= 4; $i++): ?>
                        <?php $imgField = "content_img" . $i; ?>
                        <?php if (!empty($result[$imgField])): ?>
                            <div class="img"><img src="<?= htmlspecialchars($result[$imgField]); ?>" alt="추가 이미지"></div>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>
            </section>

            <!-- 우측 설명 섹션 -->
            <section class="top_right">
                <div class="contents_infos">
                    <h1 class="product_title"><?= htmlspecialchars($result['content_name']); ?></h1>
                    <p class="product_description"><?= nl2br(htmlspecialchars($result['content_description'])); ?></p>
                </div>
                <hr>
                <div class="product_meta">
                    <div class="meta_item">
                        <span class="label">원가:</span>
                        <span class="value"><?= number_format($result['content_cost']); ?>원</span>
                    </div>
                    <div class="meta_item">
                        <span class="label">할인율:</span>
                        <span class="value"><?= htmlspecialchars($result['discount_rate']); ?>%</span>
                    </div>
                    <div class="meta_item">
                        <span class="label">판매가:</span>
                        <span class="value"><?= number_format($result['content_price']); ?>원</span>
                    </div>
                </div>
                <hr>
                <div class="selection_wrapper">
                    <!-- 색상 선택 -->
                    <div class="buttons choice">
                        <span class="choice_title">색상 선택</span>
                        <select name="content_color" class="color_select" required>
                            <option value="">- [필수] 옵션을 선택해 주세요 -</option>
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <?php $colorField = "content_color" . $i; ?>
                                <?php if (!empty($result[$colorField])): ?>
                                    <option value="<?= htmlspecialchars($result[$colorField]); ?>">
                                        <?= htmlspecialchars($result[$colorField]); ?>
                                    </option>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <!-- 사이즈 선택 -->
                    <div class="buttons choice">
                        <span class="choice_title">사이즈 선택</span>
                        <select name="content_size" class="size_select" required>
                            <option value="">- [필수] 옵션을 선택해 주세요 -</option>
                            <?php for ($i = 1; $i <= 3; $i++): ?>
                                <?php $sizeField = "content_size" . $i; ?>
                                <?php if (!empty($result[$sizeField])): ?>
                                    <option value="<?= htmlspecialchars($result[$sizeField]); ?>">
                                        <?= htmlspecialchars($result[$sizeField]); ?>
                                    </option>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <!-- 수량 선택 -->
                    <div class="buttons choice">
                        <span class="choice_title">수량 선택</span>
                        <input type="number" name="quantity" class="quantity_input" value="1" min="1" required>
                    </div>
                </div>
                <div class="total_price_wrapper">
                    <span class="total_price_title">총 상품 금액:</span>
                    <span class="total_price" id="total_price">0</span>원
                </div>
                <div class="buttons purchase">
                    <!-- 바로 구매 -->
                    <button type="submit" formaction="payment_info.php" class="buy_now_button">바로 구매하기</button>
                    <!-- 장바구니 추가 -->
                    <button type="submit" formaction="cart.php" class="cart_button">장바구니</button>
                    <!-- 찜하기 -->
                    <button type="button" class="wishlist_button" onclick="addToWishlist()">찜</button>
                </div>
                <input type="hidden" name="content_code" value="<?= htmlspecialchars($content_code); ?>">
                <input type="hidden" name="content_price" value="<?= htmlspecialchars($result['content_price']); ?>">
                <input type="hidden" name="content_name" value="<?= htmlspecialchars($result['content_name']); ?>">
            </section>
        </form>
    </main>

    <script>
        // 총 상품 금액 계산
        const price = <?= $result['content_price']; ?>;
        const quantityInput = document.querySelector('.quantity_input');
        const totalPriceEl = document.getElementById('total_price');

        function updateTotalPrice() {
            const quantity = parseInt(quantityInput.value) || 0;
            const totalPrice = price * quantity;
            totalPriceEl.textContent = totalPrice.toLocaleString();
        }

        quantityInput.addEventListener('input', updateTotalPrice);
        updateTotalPrice();

        // AJAX로 위시리스트 추가
        function addToWishlist() {
            const content_code = "<?= htmlspecialchars($content_code); ?>";
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "wishlist.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            alert(response.message);
                        } else {
                            alert("찜하기 실패: " + response.message);
                        }
                    } else {
                        alert("서버 오류: 찜하기 실패");
                    }
                }
            };
            xhr.send("action=add&content_code=" + encodeURIComponent(content_code));
        }
    </script>

    <?php include("inc/footer.php"); ?>
</body>

</html>