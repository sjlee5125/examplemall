<?php
// DB 연결 포함
include('inc/db.php');

// 입력 데이터 가져오기
$title = $_POST['title'] ?? '';
$writer = $_POST['writer'] ?? '';
$content = $_POST['content'] ?? '';

// 데이터 삽입
$query = "INSERT INTO notice (title, writer, content, created_at) VALUES (:title, :writer, :content, CURRENT_TIMESTAMP)";
$params = [
    ':title' => $title,
    ':writer' => $writer,
    ':content' => $content
];
$result = db_insert($query, $params);

if ($result) {
    header("Location: manager_notice.php");
    exit;
} else {
    echo "<script>alert('등록 실패. 다시 시도해주세요.');</script>";
}
