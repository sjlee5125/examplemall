<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/sign_up.css">
    <title>Ex-mall</title>
</head>

<body>
    <main class="main_wrapper sign_up">
        <span class="join_us_title">회원가입</span>
        <div class="join_box">
            <form name="member_form" method="POST" action="member_insert.php" class="member_form">
                <div class="member_form_col">
                    <!-- 필수입력 구간 -->
                    <div class="section_title required">필수입력</div>
                    <div class="member_form_row row1">
                        <div class="form">
                            <label for="id" class="col1">아이디</label>
                            <div class="col2">
                                <input type="text" name="id" id="id" placeholder="아이디를 입력하세요">
                            </div>
                        </div>
                        <div class="form">
                            <label for="pass" class="col1">비밀번호</label>
                            <div class="col2">
                                <input type="password" name="pass" id="pass" placeholder="비밀번호를 입력하세요">
                            </div>
                        </div>
                        <div class="form">
                            <label for="pass_confirm" class="col1">비밀번호 확인</label>
                            <div class="col2">
                                <input type="password" name="pass_confirm" id="pass_confirm" placeholder="비밀번호를 확인하세요">
                            </div>
                        </div>
                        <div class="form">
                            <label for="name" class="col1">이름</label>
                            <div class="col2">
                                <input type="text" name="name" id="name" placeholder="이름을 입력하세요">
                            </div>
                        </div>
                        <div class="form">
                            <label for="phone" class="col1">휴대전화</label>
                            <div class="col2">
                                <input type="text" name="phone" id="phone" placeholder="휴대전화 번호를 입력하세요">
                            </div>
                        </div>
                    </div>

                    <!-- 선택입력 구간 -->
                    <div class="section_title optional">선택입력</div>
                    <div class="member_form_row row2">
                        <div class="form">
                            <label for="birth" class="col1">생년월일</label>
                            <div class="col2">
                                <input type="text" name="birth" id="birth" placeholder="YYYY-MM-DD">
                            </div>
                        </div>
                        <div class="form email">
                            <label for="email1" class="col1">이메일</label>
                            <div class="col2">
                                <div class="email-group">
                                    <input type="text" name="email1" id="email1" placeholder="이메일 ID" class="email-input">
                                    <span class="at-symbol">@</span>
                                    <input type="text" name="email2" id="email2" placeholder="도메인" class="email-input">
                                </div>
                            </div>
                        </div>
                        <div class="form">
                            <label for="referrer" class="col1">추천인 아이디</label>
                            <div class="col2">
                                <input type="text" name="referrer" id="referrer" placeholder="추천인 아이디를 입력하세요">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <section>
            <button class="button_join" onclick="check_input()">가입</button>
            <button class="button_cancel">취소</button>
        </section>
    </main>

    <script src="js/member.js"></script>
</body>

</html>