<?php
// DB 연결 포함
include('inc/db.php');

// POST 데이터 가져오기
$product_code = $_POST['product_code'];
$product_name = $_POST['product_name'];
$product_description = $_POST['product_description'];
$original_price = $_POST['original_price']; // 원가
$price = $_POST['price']; // 가격
$discount_rate = $_POST['discount_rate']; // 할인율
$colors = $_POST['colors'] ?? []; // 색상 (배열 형태)
$sizes = $_POST['sizes'] ?? []; // 사이즈 (배열 형태)
$category = $_POST['category'];
$category_large = strtoupper($_POST['category_large']); // 소문자로 변환
$category_small = $_POST['category_small']; // 하위 카테고리

// 이미지 파일명 텍스트 처리
$product_image = $_POST['product_image']; // 메인 이미지 파일명
$additional_images = $_POST['additional_images'] ?? []; // 추가 이미지 파일명 (배열 형태)

// 경로 중복 방지 함수
function formatImagePath($category, $image) {
    $base_path = "img/contents/$category/";
    if (strpos($image, $base_path) === 0) {
        // 이미지 경로에 이미 기본 경로가 포함되어 있다면 그대로 반환
        return $image;
    }
    return $base_path . $image;
}

// 메인 이미지 경로 처리
$product_image_path = formatImagePath($category_large, $product_image);

// 추가 이미지 경로 처리
$additional_images_paths = [];
foreach ($additional_images as $image) {
    if (!empty($image)) {
        $additional_images_paths[] = formatImagePath($category_large, $image);
    } else {
        $additional_images_paths[] = ''; // 빈 값 처리
    }
}

// 데이터 검증
if (empty($product_code) || empty($product_name)) {
    echo "<script>alert('상품 코드와 상품명은 필수 항목입니다.'); history.back();</script>";
    exit;
}

// 업데이트 쿼리 실행
$query = "UPDATE contents 
          SET content_name = ?, 
              content_description = ?, 
              content_cost = ?, 
              discount_rate = ?, 
              content_price = ?, 
              content_img = ?, 
              content_img1 = ?, 
              content_img2 = ?, 
              content_img3 = ?, 
              content_img4 = ?, 
              content_color1 = ?, 
              content_color2 = ?, 
              content_color3 = ?, 
              content_color4 = ?, 
              content_color5 = ?, 
              content_size1 = ?, 
              content_size2 = ?, 
              content_size3 = ?, 
              category = ?, 
              category_large = ?, 
              category_small = ?
          WHERE content_code = ?";
$params = [
    $product_name,
    $product_description,
    $original_price,
    $discount_rate,
    $price,
    $product_image_path, // 메인 이미지 경로
    $additional_images_paths[0] ?? '',
    $additional_images_paths[1] ?? '',
    $additional_images_paths[2] ?? '',
    $additional_images_paths[3] ?? '',
    $colors[0] ?? '',
    $colors[1] ?? '',
    $colors[2] ?? '',
    $colors[3] ?? '',
    $colors[4] ?? '',
    $sizes[0] ?? '',
    $sizes[1] ?? '',
    $sizes[2] ?? '',
    $category,
    $category_large,
    $category_small,
    $product_code
];

// DB 업데이트 실행
$result = db_update_delete($query, $params);

// 결과 확인
if ($result) {
    echo "<script>alert('상품이 수정되었습니다.'); window.location.href='manager_product.php';</script>";
} else {
    echo "<script>alert('수정에 실패했습니다. 다시 시도해주세요.'); history.back();</script>";
}
?>
