<?php
// DB 연결 포함
include('inc/db.php');

// POST 데이터 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $name = $_POST['name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email_local = $_POST['email_local'] ?? '';
    $email_domain = $_POST['email_domain'] ?? '';
    $email_custom_domain = $_POST['email_custom_domain'] ?? '';
    $birth = $_POST['birth'] ?? '';
    $password = $_POST['password'] ?? ''; // 새 비밀번호

    // 이메일 조합
    $email = $email_domain === 'custom' ? "$email_local@$email_custom_domain" : "$email_local@$email_domain";

    // 데이터 유효성 검사
    if (empty($id) || empty($name) || empty($phone) || empty($email_local) || (empty($email_domain) && empty($email_custom_domain))) {
        die("모든 필드를 올바르게 입력해 주세요.");
    }

    try {
        // 비밀번호가 입력된 경우
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $query = "UPDATE members SET name = ?, phone = ?, email = ?, birth = ?, password = ? WHERE id = ?";
            $params = [$name, $phone, $email, $birth, $hashed_password, $id];
        } else {
            // 비밀번호가 입력되지 않은 경우 (기존 비밀번호 유지)
            $query = "UPDATE members SET name = ?, phone = ?, email = ?, birth = ? WHERE id = ?";
            $params = [$name, $phone, $email, $birth, $id];
        }

        // 업데이트 실행
        if (db_update_delete($query, $params)) {
            echo "회원 정보가 성공적으로 수정되었습니다.";
            header("Location: manager_member.php");
            exit;
        } else {
            echo "회원 정보 수정에 실패했습니다.";
        }
    } catch (Exception $e) {
        die("오류 발생: " . $e->getMessage());
    }
}
?>
