<?php
require_once("inc/session.php");
require_once("inc/db.php");

$members_id = $_SESSION['member_id'] ?? null;

if (!$members_id) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => '로그인이 필요합니다.']);
    exit;
}

// POST 요청 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $content_code = $_POST['content_code'] ?? null;

    if ($action === 'add' && $content_code) {
        // 위시리스트에 추가
        db_query("INSERT IGNORE INTO wishlist (user_id, content_code) VALUES (?, ?)", [$members_id, $content_code]);
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => '상품이 위시리스트에 추가되었습니다.']);
        exit;
    }

    if ($action === 'delete' && $content_code) {
        // 위시리스트에서 삭제
        db_query("DELETE FROM wishlist WHERE user_id = ? AND content_code = ?", [$members_id, $content_code]);
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => '상품이 위시리스트에서 삭제되었습니다.']);
        exit;
    }

    if ($action === 'add_to_cart' && $content_code) {
        // 장바구니에 추가
        db_query("INSERT IGNORE INTO cart (member_id, content_code) VALUES (?, ?)", [$members_id, $content_code]);
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => '상품이 장바구니에 추가되었습니다.']);
        exit;
    }

    if ($action === 'buy_now' && $content_code) {
        // 바로 구매 페이지로 리다이렉트
        header("Location: checkout.php?content_code=" . urlencode($content_code));
        exit;
    }
}

// 위시리스트 항목 조회
$query = "
    SELECT wishlist.*, contents.content_name, contents.content_img, contents.content_price
    FROM wishlist
    JOIN contents ON wishlist.content_code = contents.content_code
    WHERE wishlist.user_id = ?
";
$wishlist_items = db_select($query, [$members_id]);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>위시리스트</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/wishlist.css?v=2">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <script>
        // AJAX로 위시리스트에서 항목 삭제
        function deleteFromWishlist(contentCode) {
            if (!confirm('정말 삭제하시겠습니까?')) return;

            const xhr = new XMLHttpRequest();
            xhr.open("POST", "wishlist.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            alert(response.message);
                            document.getElementById('wishlist-item-' + contentCode).remove(); // DOM에서 제거
                        } else {
                            alert("삭제 실패: " + response.message);
                        }
                    } else {
                        alert("서버 오류: 삭제 실패");
                    }
                }
            };

            xhr.send("action=delete&content_code=" + encodeURIComponent(contentCode));
        }

        // AJAX로 장바구니에 추가
        function addToCart(contentCode) {
            if (!confirm('이 상품을 장바구니에 추가하시겠습니까?')) return;

            const xhr = new XMLHttpRequest();
            xhr.open("POST", "wishlist.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            alert(response.message);
                        } else {
                            alert("장바구니 추가 실패: " + response.message);
                        }
                    } else {
                        alert("서버 오류: 장바구니 추가 실패");
                    }
                }
            };

            xhr.send("action=add_to_cart&content_code=" + encodeURIComponent(contentCode));
        }

        // AJAX로 바로 구매 처리
        function buyNow(contentCode) {
            if (!confirm('바로 구매하시겠습니까?')) return;

            window.location.href = "checkout.php?content_code=" + encodeURIComponent(contentCode);
        }
    </script>
</head>
<body>
    <?php include("inc/header.php"); ?>

    <h1>위시리스트</h1>
    <div class="wishlist">
        <?php if (!empty($wishlist_items)): ?>
            <ul class="wishlist-items">
                <?php foreach ($wishlist_items as $item): ?>
                    <li class="wishlist-item" id="wishlist-item-<?php echo htmlspecialchars($item['content_code']); ?>">
                        <img src="<?php echo htmlspecialchars($item['content_img']); ?>" alt="상품 이미지">
                        <div class="wishlist-info">
                            <span class="wishlist-name"><?php echo htmlspecialchars($item['content_name']); ?></span>
                            <span class="wishlist-price"><?php echo number_format($item['content_price']); ?>원</span>
                        </div>
                        <div class="wishlist-actions">
                            <button type="button" onclick="addToCart('<?php echo htmlspecialchars($item['content_code']); ?>')">장바구니</button>
                            <button type="button" onclick="deleteFromWishlist('<?php echo htmlspecialchars($item['content_code']); ?>')">삭제</button>
                            <button type="button" onclick="buyNow('<?php echo htmlspecialchars($item['content_code']); ?>')">바로 구매</button>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="wishlist-empty">위시리스트가 비어 있습니다.</p>
        <?php endif; ?>
    </div>
    <?php include("inc/footer.php"); ?>
</body>
</html>
