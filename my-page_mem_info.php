<!-- <?php require_once("pay.import.php");?> -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>HARU</title>
</head>

<body>
    <?php require_once("inc/header.php"); ?>

    <main class="main_wrapper my-page member_info">
        <section class="menus">
            <div class="my-page_title"><span>MY PAGE</span></div>
            <ul class="navs">
                <a href="my-page_member_pwd.php"><li class="nav" style="background-color: rgb(255, 139, 139); width: 165px;"><span>회원정보 수정</span></li></a>
                <a href="my-page_order.php"><li class="nav" style="width: 165px;"><span>주문배송</span></li></a>
                <li class="nav"><span>쿠폰/적립금</span></li>
                <li class="nav"><span>교환/반품</span></li>
                <li class="nav"><span>좋아요</span></li>
                <li class="nav"><span>최근 본 상품</span></li>
                <li class="nav"><span>회원탈퇴</span></li>
            </ul>
        </section>
        <section class="view">
            <div class="member_pwd_title"><span>회원 정보 수정</span></div>
            <div class="join_box">
                <form name="member_form" method="POST" action="member_insert.php" class="member_form">
                    <div class="member_form_col">
                        <div class="member_form_row row1">
                            <div class="form id">
                                <div class="col1">아이디</div>
                                <div class="col2">
                                    <input type="text" name="id" class = "input_text">
                                </div>
                            </div>
                            <div class="clear"></div>
                            <div class="form">
                                <div class="col1">이름</div>
                                <div class="col2">
                                    <input type="text" name="name" class = "input_text">
                                </div>
                            </div>
                            <div class="form">
                                <div class="col1">휴대전화</div>
                                <div class="col2">
                                    <input type="text" name="phone" class = "input_text">
                                </div>
                            </div>
                            <div class="clear"></div>
                            <div class="form">
                                <div class="col1">생년월일</div>
                                <div class="col2">
                                    <input type="text" name="birth" class = "input_text">
                                </div>
                            </div>
                            <div class="form email">
                                <div class="col1">이메일</div>
                                <div class="col2">
                                    <input type="text" name="email1" class = "input_text"> &nbsp;@&nbsp;<input type="text" name="email2" class = "input_text">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="member_form_col check_box">
                        <div class="ref">수신 동의 여부</div>
                        <div class="form">
                            <div class="col1">SNS 수신여부</div>
                            <div class="col2">
                                <input type="radio" name="sns" checked>예
                                <input type="radio" name="sns">아니오
                            </div>
                        </div>
                        <div class="form">
                            <div class="col1">이메일 수신여부</div>
                            <div class="col2">
                                <input type="radio" name="email_receive" checked>예
                                <input type="radio" name="email_receive">아니오
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <section>
                <button class="button_join" onclick="">완료</button>
                <button class="button_cancel">취소</button>
            </section>
        </section>

    </main>
    
    <?php require_once("inc/footer.php"); ?>

    <script src="https://kit.fontawesome.com/73fbcb87e6.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
    <script src="js/hot_issue.js"></script>
    <script src="js/member.js"></script>

</body>

</html>