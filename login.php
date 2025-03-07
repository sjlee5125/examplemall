<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
    <title>Ex-mall</title>
</head>

<body>
    <?php require_once("inc/header.php"); ?>

    <main class="login_wrapper">
        <div class="login-container">
            <h2 class="login-title">로그인</h2>
            <!-- 카카오 로그인 -->
            <button class="kakao-login-button">카카오 1초 로그인/회원가입</button>

            <!-- 일반 로그인 폼 -->
            <form name="login_form" method="POST" action="login.post.php" class="login_form">
                <input type="text" placeholder="아이디를 입력하세요." class="input-field" name="id">
                <input type="password" placeholder="비밀번호를 입력하세요." class="input-field" name="pass">
                <div class="login_keep_wrapper">
                    <label class="keep-text">
                        <input type="checkbox" class="checkbox"> 아이디 저장
                    </label>
                    <label class="keep-text">
                        <input type="checkbox" class="checkbox"> 로그인 상태 유지
                    </label>
                </div>
                <button type="submit" class="login-button">로그인</button>
            </form>

            <!-- SNS 로그인 -->
            <div class="sns-login-wrapper">
                <a href="naver_login.php" class="sns-login-button naver">네이버 로그인</a>
                <a href="google_login.php" class="sns-login-button google">구글 로그인</a>
            </div>

            <!-- 하단 링크 -->
            <div class="find-wrapper">
                <a href="sign_up.php" class="find-link">회원가입</a>
                <a href="find_id.php" class="find-link">아이디 찾기</a>
                <a href="find_password.php" class="find-link">비밀번호 찾기</a>
            </div>
        </div>
    </main>

    <?php require_once("inc/footer.php"); ?>

    <script src="https://kit.fontawesome.com/73fbcb87e6.js" crossorigin="anonymous"></script>
</body>

</html>