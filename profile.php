<?php
require_once("inc/session.php");
require_once("inc/db.php");

// 프로필 접근 권한 확인
if (!isset($_SESSION['profile_access']) || $_SESSION['profile_access'] !== true) {
    header("Location: confirm_password.php");
    exit;
}

// 로그인된 사용자의 정보 가져오기
$member_id = $_SESSION['member_id'] ?? null;

if ($member_id) {
    $member_info = db_select("SELECT name, email, phone, birth FROM members WHERE id = ?", array($member_id));
    if ($member_info) {
        $member = $member_info[0];
    } else {
        echo "사용자 정보를 가져올 수 없습니다.";
        exit;
    }
} else {
    header("Location: login.php");
    exit;
}

// 회원 정보 수정 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $birth = $_POST['birth'];
    $password = $_POST['password'] ?? null;
    
    // 비밀번호가 입력된 경우만 비밀번호를 업데이트
    if ($password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        db_query("UPDATE members SET name = ?, email = ?, phone = ?, birth = ?, pass = ? WHERE id = ?", array($name, $email, $phone, $birth, $hashed_password, $member_id));
    } else {
        db_query("UPDATE members SET name = ?, email = ?, phone = ?, birth = ? WHERE id = ?", array($name, $email, $phone, $birth, $member_id));
    }

    // 수정 완료 후 마이페이지로 이동
    unset($_SESSION['profile_access']); // 수정 후 접근 권한 제거
    header("Location: mypage.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/profile.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">


    <title>회원 정보 수정</title>
</head>
<body>
    <?php include("inc/header.php"); ?>

    <main class="profile_main">
        <h1>회원 정보 수정</h1>

        <form action="profile.php" method="POST" class="profile_form">
            <div class="form_group">
                <label for="name">이름</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($member['name']); ?>" required>
            </div>
            <div class="form_group">
                <label for="email">이메일</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($member['email']); ?>" required>
            </div>
            <div class="form_group">
                <label for="phone">전화번호</label>
                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($member['phone']); ?>" required>
            </div>
            <div class="form_group">
                <label for="birth">생년월일</label>
                <input type="text" id="birth" name="birth" value="<?php echo htmlspecialchars($member['birth']); ?>" required>
            </div>
            <div class="form_group">
                <label for="password">비밀번호 변경 (선택)</label>
                <input type="password" id="password" name="password" placeholder="변경하려면 새 비밀번호 입력">
            </div>
            <button type="submit" class="update_button">정보 업데이트</button>
        </form>
    </main>
</body>
</html>
