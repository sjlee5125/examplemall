<?php
// DB 연결 포함
include('inc/db.php');

// 공지사항 삭제 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['notice_id'])) {
    // 입력된 공지사항 ID 가져오기
    $notice_id = $_POST['notice_id'];

    // 삭제 쿼리
    $query = "DELETE FROM notice WHERE notice_id = :notice_id";
    $result = db_update_delete($query, [':notice_id' => $notice_id]);

    if ($result) {
        // 삭제 성공 시 공지사항 관리 페이지로 리다이렉트
        header("Location: manager_notice.php");
        exit;
    } else {
        // 삭제 실패 시 메시지 표시
        echo "<script>alert('삭제 실패. 다시 시도해주세요.'); window.history.back();</script>";
    }
} else {
    // 잘못된 접근 시 공지사항 관리 페이지로 리다이렉트
    header("Location: manager_notice.php");
    exit;
}
