<?php
// DB 연결 포함
include('inc/db.php');

// 입력 데이터 가져오기
$notice_id = $_POST['notice_id'] ?? '';
$title = $_POST['title'] ?? '';
$writer = $_POST['writer'] ?? '';
$content = $_POST['content'] ?? '';

// 데이터 업데이트
$query = "UPDATE notice SET title = :title, writer = :writer, content = :content, created_at = CURRENT_TIMESTAMP WHERE notice_id = :notice_id";
$params = [
    ':title' => $title,
    ':writer' => $writer,
    ':content' => $content,
    ':notice_id' => $notice_id
];
$result = db_update_delete($query, $params);

if ($result) {
    header("Location: manager_notice.php");
    exit;
} else {
    echo "<script>alert('수정 실패. 다시 시도해주세요.');</script>";
}
