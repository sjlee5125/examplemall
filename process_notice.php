<?php
// DB 연결 포함
include('inc/db.php');

// POST 요청 확인
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $notice_id = $_POST['notice_id'] ?? null;
    $title = $_POST['title'] ?? null;
    $writer = $_POST['writer'] ?? null;
    $content = $_POST['content'] ?? null;

    // 등록 처리
    if ($action === 'insert') {
        if (!$title || !$writer || !$content) {
            die("모든 필드를 입력하세요.");
        }

        $query = "INSERT INTO notice (title, writer, content) VALUES (:title, :writer, :content)";
        $result = db_insert($query, [
            ':title' => $title,
            ':writer' => $writer,
            ':content' => $content,
        ]);

        if ($result) {
            header("Location: manager_notice.php?message=insert_success");
            exit;
        } else {
            die("공지 등록에 실패했습니다.");
        }
    }

    // 수정 처리
    if ($action === 'update') {
        if (!$notice_id || !$title || !$writer || !$content) {
            die("모든 필드를 입력하세요.");
        }

        $query = "UPDATE notice SET title = :title, writer = :writer, content = :content WHERE notice_id = :notice_id";
        $result = db_update_delete($query, [
            ':title' => $title,
            ':writer' => $writer,
            ':content' => $content,
            ':notice_id' => $notice_id,
        ]);

        if ($result) {
            header("Location: manager_notice.php?message=update_success");
            exit;
        } else {
            die("공지 수정에 실패했습니다.");
        }
    }

    // 삭제 처리
    if ($action === 'delete') {
        if (!$notice_id) {
            die("삭제할 공지 ID가 없습니다.");
        }

        $query = "DELETE FROM notice WHERE notice_id = :notice_id";
        $result = db_update_delete($query, [':notice_id' => $notice_id]);

        if ($result) {
            header("Location: manager_notice.php?message=delete_success");
            exit;
        } else {
            die("공지 삭제에 실패했습니다.");
        }
    }

    // 알 수 없는 작업
    die("알 수 없는 작업입니다.");
} else {
    die("잘못된 요청입니다.");
}
?>
