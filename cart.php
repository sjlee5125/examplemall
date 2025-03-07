<?php
require_once("inc/db.php");
require_once("inc/session.php");

// 세션 시작
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$member_id = $_SESSION['member_id'] ?? null;
if (!$member_id) {
    header("Location: login.php");
    exit;
}

// 장바구니에 상품 추가
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content_code'])) {
    $content_code = $_POST['content_code'];
    $content_color = $_POST['content_color'];
    $content_size = $_POST['content_size'];
    $quantity = $_POST['quantity'] ?? 1;

    // 데이터 검증
    if (!$content_code || !$content_color || !$content_size || $quantity < 1) {
        die("상품 정보가 올바르지 않습니다.");
    }

    // 동일 상품이 있는지 확인
    $existing_item = db_select(
        "SELECT id, quantity FROM cart 
         WHERE member_id = ? AND content_code = ? AND content_color = ? AND content_size = ?",
        [$member_id, $content_code, $content_color, $content_size]
    );

    if ($existing_item) {
        $new_quantity = $existing_item[0]['quantity'] + $quantity;
        db_query("UPDATE cart SET quantity = ? WHERE id = ?", [$new_quantity, $existing_item[0]['id']]);
    } else {
        db_query(
            "INSERT INTO cart (member_id, content_code, content_color, content_size, quantity) 
             VALUES (?, ?, ?, ?, ?)",
            [$member_id, $content_code, $content_color, $content_size, $quantity]
        );
    }
    header("Location: cart.php");
    exit;
}

// 장바구니 데이터 조회
$query = "
    SELECT cart.id, cart.quantity, 
           contents.content_name, contents.content_price, contents.content_img, 
           cart.content_color, cart.content_size, cart.content_code
    FROM cart
    JOIN contents ON cart.content_code = contents.content_code
    WHERE cart.member_id = ?";
$result = db_select($query, [$member_id]);

// 삭제 및 주문 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 전체 상품 삭제
    if (isset($_POST['delete_all'])) {
        db_query("DELETE FROM cart WHERE member_id = ?", [$member_id]);
        header("Location: cart.php");
        exit;
    }

    // 선택된 상품 삭제
    if (isset($_POST['delete_selected']) && !empty($_POST['selected_ids'])) {
        $delete_ids = $_POST['selected_ids'];
        $placeholders = implode(',', array_fill(0, count($delete_ids), '?'));
        $params = array_merge($delete_ids, [$member_id]);

        // 선택된 ID만 삭제
        $query = "DELETE FROM cart WHERE id IN ($placeholders) AND member_id = ?";
        db_query($query, $params);
        
        header("Location: cart.php");
        exit;
    }

    // 선택 상품 주문
    if (isset($_POST['selected_order']) && !empty($_POST['selected_ids'])) {
        $selected_ids = $_POST['selected_ids'];
        $placeholders = implode(',', array_fill(0, count($selected_ids), '?'));
        $query = "
            SELECT cart.id, cart.quantity, 
                   contents.content_name, contents.content_price, contents.content_img, 
                   cart.content_color, cart.content_size, cart.content_code
            FROM cart
            JOIN contents ON cart.content_code = contents.content_code
            WHERE cart.id IN ($placeholders) AND cart.member_id = ?";
        $selected_cart_items = db_select($query, array_merge($selected_ids, [$member_id]));

        if (!empty($selected_cart_items)) {
            $_SESSION['selected_cart_items'] = $selected_cart_items;
            header("Location: payment_info.php");
            exit;
        } else {
            die("선택된 상품 정보를 불러올 수 없습니다.");
        }
    }

    // 전체 상품 주문
    if (isset($_POST['order_all'])) {
        $_SESSION['selected_cart_items'] = $result;
        header("Location: payment_info.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>장바구니</title>
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/cart.css">
    <link rel="stylesheet" href="css/footer.css">
</head>
<body>
    <?php include("inc/header.php"); ?>

    <main class="main_wrapper cart">
        <form method="POST" action="cart.php">
            <section class="cart_header">
                <h1>장바구니</h1>
                <div class="delete_buttons">
                    <button type="submit" name="delete_all" class="delete_all_button" onclick="return confirm('정말 전체 상품을 삭제하시겠습니까?');">
                        전체 상품 삭제
                    </button>
                    <button type="submit" name="delete_selected" class="delete_selected_button" onclick="return confirmDeleteSelected();">
                        선택 상품 삭제
                    </button>
                </div>
            </section>
            <table class="cart_table">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select_all" onclick="toggleSelectAll()"></th>
                        <th>이미지</th>
                        <th>상품정보</th>
                        <th>가격</th>
                        <th>수량</th>
                        <th>합계</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $total_price = 0; ?>
                    <?php foreach ($result as $item): ?>
                        <tr>
                            <td><input type="checkbox" name="selected_ids[]" value="<?= $item['id']; ?>"></td>
                            <td><img src="<?= htmlspecialchars($item['content_img']); ?>" width="80" alt="상품 이미지"></td>
                            <td>
                                <?= htmlspecialchars($item['content_name']); ?><br>
                                색상: <?= htmlspecialchars($item['content_color']); ?><br>
                                사이즈: <?= htmlspecialchars($item['content_size']); ?>
                            </td>
                            <td><?= number_format($item['content_price']); ?>원</td>
                            <td><?= $item['quantity']; ?>개</td>
                            <td><?= number_format($item['content_price'] * $item['quantity']); ?>원</td>
                        </tr>
                        <?php $total_price += $item['content_price'] * $item['quantity']; ?>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5">총 합계</td>
                        <td><?= number_format($total_price); ?>원</td>
                    </tr>
                </tfoot>
            </table>

            <section class="purchase_buttons">
                <button type="submit" name="selected_order">선택 상품 주문하기</button>
                <button type="submit" name="order_all">전체 상품 주문하기</button>
            </section>
        </form>
    </main>

    <script>
        function toggleSelectAll() {
            const checkboxes = document.querySelectorAll('input[name="selected_ids[]"]');
            checkboxes.forEach(cb => cb.checked = document.getElementById('select_all').checked);
        }

        function confirmDeleteSelected() {
            const selected = document.querySelectorAll('input[name="selected_ids[]"]:checked');
            if (selected.length === 0) {
                alert('삭제할 상품을 선택해주세요.');
                return false;
            }
            return confirm('정말 선택한 상품을 삭제하시겠습니까?');
        }
    </script>

    <?php include("inc/footer.php"); ?>
</body>
</html>
