<?php
// DB 연결 포함
include('inc/db.php');

// 폼에서 전송된 데이터 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_code = $_POST['product_code'] ?? '';
    $product_name = $_POST['product_name'] ?? '';
    $product_description = $_POST['product_description'] ?? '';
    $product_image = $_POST['product_image'] ?? '';
    $category = $_POST['category'] ?? '';
    $category_large = $_POST['category_large'] ?? '';
    $category_small = $_POST['category_small'] ?? '';
    $colors = $_POST['colors'] ?? [];
    $sizes = $_POST['sizes'] ?? [];
    $original_price = $_POST['original_price'] ?? '';
    $price = $_POST['price'] ?? '';
    $discount_rate = $_POST['discount_rate'] ?? '';
    $additional_images = $_POST['additional_images'] ?? [];

    // DB에 삽입할 쿼리 작성
    $query = "
        INSERT INTO contents (
            content_code, 
            content_name, 
            content_description, 
            content_img,
            category,
            category_large, 
            category_small, 
            content_color1, 
            content_color2, 
            content_color3, 
            content_color4, 
            content_color5, 
            content_size1, 
            content_size2, 
            content_size3, 
            content_cost, 
            content_price, 
            content_img1, 
            content_img2, 
            content_img3, 
            content_img4
        ) VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
        )
    ";

    // 파라미터 준비
    $params = [
        $product_code,
        $product_name,
        $product_description,
        $product_image,
        $category,
        $category_large,
        $category_small,
        $colors[0] ?? null,
        $colors[1] ?? null,
        $colors[2] ?? null,
        $colors[3] ?? null,
        $colors[4] ?? null,
        $sizes[0] ?? null,
        $sizes[1] ?? null,
        $sizes[2] ?? null,
        $original_price,
        $price,
        $additional_images[0] ?? null,
        $additional_images[1] ?? null,
        $additional_images[2] ?? null,
        $additional_images[3] ?? null,
    ];

    // DB에 데이터 삽입
    $insert_result = db_insert($query, $params);

    // 결과 처리 및 리다이렉트
    if ($insert_result !== false) {
        // 성공 시 manager_product.php로 리다이렉트
        header("Location: manager_product.php");
        exit; // 리다이렉트 후 추가 실행 방지
    } else {
        // 실패 시 에러 메시지 출력
        echo "상품 등록에 실패했습니다.";
    }
}

if ($insert_result === false) {
    echo "상품 등록에 실패했습니다.";
    echo "SQL 오류가 발생했습니다.";
}

