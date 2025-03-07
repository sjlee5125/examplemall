<?php
// DB 연결 포함
include('inc/db.php');

// POST 요청 확인
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $faq_id = $_POST['faq_id'] ?? null;
    $title = $_POST['title'] ?? null;
    $content = $_POST['content'] ?? null;

    // 등록 처리
    if ($action === 'insert') {
        if (!$title || !$content) {
            die("모든 필드를 입력하세요.");
        }

        $query = "INSERT INTO faq (title, content) VALUES (:title, :content)";
        $result = db_insert($query, [
            ':title' => $title,
            ':content' => $content,
        ]);

        if ($result) {
            header("Location: manager_faq.php?message=insert_success");
            exit;
        } else {
            die("FAQ 등록에 실패했습니다.");
        }
    }

    // 수정 처리
    if ($action === 'update') {
        if (!$faq_id || !$title || !$content) {
            die("모든 필드를 입력하세요.");
        }

        $query = "UPDATE faq SET title = :title, content = :content WHERE faq_id = :faq_id";
        $result = db_update_delete($query, [
            ':title' => $title,
            ':content' => $content,
            ':faq_id' => $faq_id,
        ]);

        if ($result) {
            header("Location: manager_faq.php?message=update_success");
            exit;
        } else {
            die("FAQ 수정에 실패했습니다.");
        }
    }

    // 삭제 처리
    if ($action === 'delete') {
        if (!$faq_id) {
            die("삭제할 FAQ ID가 없습니다.");
        }

        $query = "DELETE FROM faq WHERE faq_id = :faq_id";
        $result = db_update_delete($query, [':faq_id' => $faq_id]);

        if ($result) {
            header("Location: manager_faq.php?message=delete_success");
            exit;
        } else {
            die("FAQ 삭제에 실패했습니다.");
        }
    }

    // 알 수 없는 작업
    die("알 수 없는 작업입니다.");
} else {
    die("잘못된 요청입니다.");
}
