<?php
require_once("inc/db.php");
require_once("inc/session.php");

// 로그인 확인
$member_id = $_SESSION['member_id'] ?? null;
if (!$member_id) {
    die("로그인이 필요합니다.");
}

// POST 데이터 확인 및 초기화
$review_id = $_POST['review_id'] ?? null;
$content_code = $_POST['content_code'] ?? null;
$order_id = $_POST['order_id'] ?? null;
$review_contents = trim($_POST['review_text'] ?? ''); // 'review_text'로 확인
$star = intval($_POST['rating'] ?? 0); // 'rating' 확인
$photo_path = null;

// 필수 필드 검증
if (!$content_code || !$review_contents || $star <= 0 || !$order_id) {
    die("모든 필드를 입력해주세요.");
}

// 디버그 로그: 데이터 확인
file_put_contents('debug.log', "POST 데이터:\n" . print_r($_POST, true), FILE_APPEND);

// 이미지 업로드 처리
if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $photo_tmp = $_FILES['photo']['tmp_name'];
    $photo_name = basename($_FILES['photo']['name']);
    $upload_dir = 'uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    $photo_path = $upload_dir . time() . '_' . $photo_name;
    if (!move_uploaded_file($photo_tmp, $photo_path)) {
        die("이미지 업로드 실패.");
    }
}

try {
    $pdo = db_get_pdo();
    if ($review_id) {
        // 기존 리뷰 수정
        $query = "UPDATE review 
                  SET review_text = ?, rating = ?, photo = ?, review_date = NOW() 
                  WHERE review_id = ? AND member_id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$review_contents, $star, $photo_path, $review_id, $member_id]);
    } else {
        // 새 리뷰 작성
        $query = "INSERT INTO review (member_id, content_code, order_id, review_text, rating, photo, review_date)
                  VALUES (?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$member_id, $content_code, $order_id, $review_contents, $star, $photo_path]);
    }

    // 성공 메시지
    echo "<script>alert('리뷰가 저장되었습니다.'); window.location.href = 'board.php';</script>";
    exit;
} catch (PDOException $e) {
    file_put_contents('debug.log', "DB 오류:\n" . $e->getMessage(), FILE_APPEND);
    die("리뷰 저장 중 오류가 발생했습니다: " . $e->getMessage());
}
?>
