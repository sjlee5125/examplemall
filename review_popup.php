<?php
require_once("inc/db.php");
require_once("inc/session.php");

// 로그인된 사용자 확인
$member_id = $_SESSION['member_id'] ?? null;
if (!$member_id) {
    header("Location: login.php");
    exit;
}

// 리뷰 ID 또는 상품 코드 확인
$review_id = $_GET['review_id'] ?? null;
$content_code = $_GET['content_code'] ?? null;
$order_id = $_GET['order_id'] ?? null; // order_id를 추가

if (!$review_id && !$content_code) {
    die("잘못된 접근입니다.");
}

// 기존 리뷰 데이터 가져오기
if ($review_id) {
    $review_query = "
        SELECT 
            r.review_text,
            r.rating,
            r.photo,
            c.content_name 
        FROM review r
        JOIN contents c ON r.content_code = c.content_code
        WHERE r.review_id = ? AND r.member_id = ?
    ";
    $review = db_select_one($review_query, [$review_id, $member_id]);

    if (!$review) {
        die("리뷰 정보를 찾을 수 없습니다.");
    }
} elseif ($content_code) {
    $content_query = "
        SELECT 
            c.content_name 
        FROM contents c
        WHERE c.content_code = ?
    ";
    $content = db_select_one($content_query, [$content_code]);

    if (!$content) {
        die("상품 정보를 찾을 수 없습니다.");
    }
}

// 리뷰 저장 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $review_text = trim($_POST['review_text'] ?? '');
    $rating = (int) ($_POST['rating'] ?? 0);
    $photo = null;

    // 데이터 검증
    if (empty($review_text) || $rating < 1 || $rating > 5) {
        die("리뷰 내용과 별점을 올바르게 입력해주세요.");
    }

    // 파일 업로드 처리
    if (!empty($_FILES['photo']['name'])) {
        $upload_dir = "uploads/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $photo_tmp = $_FILES['photo']['tmp_name'];
        $photo_name = time() . "_" . $member_id . "_" . basename($_FILES['photo']['name']);
        $target_file = $upload_dir . $photo_name;

        if (!move_uploaded_file($photo_tmp, $target_file)) {
            die("이미지 업로드 실패");
        }
        $photo = $target_file;
    }

    try {
        if ($review_id) {
            // 리뷰 수정
            $update_query = "
                UPDATE review
                SET review_text = ?, rating = ?, photo = ?
                WHERE review_id = ? AND member_id = ?
            ";
            db_query($update_query, [$review_text, $rating, $photo, $review_id, $member_id]);
            echo "<script>alert('리뷰가 수정되었습니다.');</script>";
        } else {
            // 리뷰 작성
            $insert_query = "
                INSERT INTO review (member_id, content_code, order_id, review_text, photo, rating, review_date)
                VALUES (?, ?, ?, ?, ?, ?, NOW())
            ";
            db_query($insert_query, [$member_id, $content_code, $order_id, $review_text, $photo, $rating]);
            echo "<script>alert('리뷰가 작성되었습니다.');</script>";
        }

        echo "<script>window.opener.location.reload(); window.close();</script>";
    } catch (Exception $e) {
        error_log("리뷰 저장 중 오류 발생: " . $e->getMessage());
        die("리뷰 저장 중 오류가 발생했습니다. 다시 시도해주세요.");
    }

    exit;
}
?>

<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>리뷰 <?= $review_id ? "수정" : "작성" ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
            font-size: 14px;
        }

        select {
            padding: 5px;
            font-size: 14px;
        }

        button {
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <h2>리뷰 <?= $review_id ? "수정" : "작성" ?></h2>
    <p>상품명: <?= htmlspecialchars($review['content_name'] ?? $content['content_name'] ?? '') ?></p>
    <form method="POST" enctype="multipart/form-data">
        <textarea name="review_text" placeholder="리뷰 내용을 입력하세요" required><?= htmlspecialchars($review['review_text'] ?? '') ?></textarea>
        <select name="rating" required>
            <option value="5" <?= isset($review['rating']) && $review['rating'] == 5 ? 'selected' : '' ?>>★★★★★</option>
            <option value="4" <?= isset($review['rating']) && $review['rating'] == 4 ? 'selected' : '' ?>>★★★★☆</option>
            <option value="3" <?= isset($review['rating']) && $review['rating'] == 3 ? 'selected' : '' ?>>★★★☆☆</option>
            <option value="2" <?= isset($review['rating']) && $review['rating'] == 2 ? 'selected' : '' ?>>★★☆☆☆</option>
            <option value="1" <?= isset($review['rating']) && $review['rating'] == 1 ? 'selected' : '' ?>>★☆☆☆☆</option>
        </select>
        <label>사진 업로드 (선택):</label>
        <input type="file" name="photo">
        <button type="submit">저장</button>
        <button type="button" onclick="window.close()">닫기</button>
    </form>
</body>

</html>
