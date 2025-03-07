<?php
// 데이터베이스 연결
require_once("inc/db.php"); // DB 연결 파일

// GET 파라미터로 공지사항 ID 가져오기
$notice_id = $_GET['id'] ?? null;

// 유효한 ID인지 확인
if (!$notice_id) {
    echo "잘못된 접근입니다.";
    exit;
}

try {
    // 조회수 증가
    $updateQuery = "UPDATE notice SET views = views + 1 WHERE notice_id = :notice_id";
    db_update_delete($updateQuery, ['notice_id' => $notice_id]); // db_update_delete 함수 사용

    // 공지사항 상세 정보 가져오기
    $query = "SELECT * FROM notice WHERE notice_id = :notice_id";
    $notice = db_select($query, ['notice_id' => $notice_id])[0] ?? null;

    // 공지사항이 존재하지 않을 경우
    if (!$notice) {
        echo "공지사항을 찾을 수 없습니다.";
        exit;
    }
} catch (Exception $e) {
    // DB 오류 처리
    echo "DB 오류: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css"> <!-- 기존 스타일 -->
    <link rel="stylesheet" href="css/community.css"> <!-- 공지사항 스타일 -->
    <link rel="stylesheet" href="css/header.css">   <!-- 헤더 스타일 -->
    <link rel="stylesheet" href="css/footer.css">   <!-- 푸터 스타일 -->
    <title><?= htmlspecialchars($notice['title']) ?> - 공지사항</title>
</head>

<body class="notice-page">
    <!-- 헤더 -->
    <?php include("inc/header.php"); ?>

    <!-- 공지사항 상세보기 섹션 -->
    <section class="notice-section">
        <div class="container">
            <h1 class="notice-title"><?= htmlspecialchars($notice['title']) ?></h1>
            <p class="notice-subtitle">
                작성자: <?= htmlspecialchars($notice['writer']) ?> |
                작성일: <?= date("Y-m-d", strtotime($notice['created_at'])) ?> |
                조회수: <?= htmlspecialchars($notice['views']) ?>
            </p>
            <div class="notice-content">
                <?= nl2br(htmlspecialchars($notice['content'])) ?>
            </div>
        </div>
    </section>

    <!-- 푸터 -->
    <?php include("inc/footer.php"); ?>
</body>

</html>
