<?php
session_start();
require_once("inc/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$input_password = $_POST['pass_member'];

// 데이터베이스에서 저장된 비밀번호 가져오기
$pdo = db_get_pdo();
$query = "SELECT pass FROM members WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id', $user_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($input_password, $user['pass'])) {
    // 비밀번호가 맞으면 회원정보 수정 페이지로 이동
    header("Location: my-page_mem_info.php");
    exit();
} else {
    // 비밀번호가 틀리면 오류 메시지 표시
    echo "<script>alert('비밀번호가 올바르지 않습니다. 다시 시도해주세요.'); history.back();</script>";
}
?>
