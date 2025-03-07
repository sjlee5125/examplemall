<?php
require_once("inc/session.php");
require_once("inc/db.php");

// 비밀번호 확인 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    $member_id = $_SESSION['member_id'] ?? null;

    if ($member_id) {
        $user_info = db_select("SELECT pass FROM members WHERE id = ?", array($member_id));

        if ($user_info && password_verify($password, $user_info[0]['pass'])) {
            // 비밀번호가 맞으면 세션에 인증 플래그를 설정하고 프로필 수정 페이지로 이동
            $_SESSION['profile_access'] = true;
            header("Location: profile.php");
            exit;
        } else {
            $error = "비밀번호가 올바르지 않습니다.";
        }
    } else {
        header("Location: login.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <title>비밀번호 확인</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/confirm_password.css">
</head>

<body>
    <?php include("inc/header.php"); ?>

    <main>
        <form action="confirm_password.php" method="POST">
            <h2>회원 정보 수정을 위해 비밀번호를 입력하세요</h2>
            <div class="form_group">
                <label for="password">비밀번호</label>
                <input type="password" id="password" name="password" placeholder="비밀번호">
            </div>
            <button type="submit" class="submit_button">확인</button>
        </form>
    </main>
</body>

</html>