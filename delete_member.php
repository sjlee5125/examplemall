<?php
// DB 연결 포함
include('inc/db.php');

// POST 요청 확인
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';

    // 유효성 검사
    if (empty($id)) {
        die("회원 ID가 제공되지 않았습니다.");
    }

    try {
        // 회원 삭제 쿼리 실행
        $query = "DELETE FROM members WHERE id = ?";
        $params = [$id];

        if (db_update_delete($query, $params)) {
            echo "회원이 성공적으로 삭제되었습니다.";
            header("Location: manager_member.php"); // 삭제 후 회원 목록 페이지로 리다이렉션
            exit;
        } else {
            echo "회원 삭제에 실패했습니다.";
        }
    } catch (Exception $e) {
        die("오류 발생: " . $e->getMessage());
    }
} else {
    die("잘못된 요청입니다.");
}
?>
