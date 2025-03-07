<?php
// DB 연결 포함
include('inc/db.php');

// 요청 확인 (POST 요청인지 확인)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 상품 코드가 전달되었는지 확인
    if (isset($_POST['product_code']) && !empty($_POST['product_code'])) {
        $product_code = $_POST['product_code'];

        // 상품 삭제 쿼리 실행
        $query = "DELETE FROM contents WHERE content_code = ?";
        $result = db_update_delete($query, [$product_code]);

        if ($result) {
            // 성공 메시지와 리다이렉트
            echo "<script>
                    alert('상품이 성공적으로 삭제되었습니다.');
                    window.location.href = 'manager_product.php';
                  </script>";
        } else {
            // 실패 메시지
            echo "<script>
                    alert('상품 삭제 중 문제가 발생했습니다. 다시 시도해주세요.');
                    window.history.back();
                  </script>";
        }
    } else {
        // 상품 코드가 없을 경우
        echo "<script>
                alert('삭제할 상품 코드가 제공되지 않았습니다.');
                window.history.back();
              </script>";
    }
} else {
    // 잘못된 요청 (POST가 아닌 경우)
    echo "<script>
            alert('잘못된 요청입니다.');
            window.history.back();
          </script>";
}
?>
