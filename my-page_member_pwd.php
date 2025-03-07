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

    <main class="main_wrapper my-page">
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
            <section class="table member">
                <table>
                    <div class="form">
                        <div class="pwd_col1">비밀번호 입력</div>
                        <div class="pwd_col2">
                            <input type="password" name="pass_member">
                        </div>
                        <a href="my-page_mem_info.php"><button>확인</button> </a>
                    </div>
                </table>
            </section>
        </section>
    </main>

    <?php require_once("inc/fast_move.php"); ?>
    <?php require_once("inc/footer.php"); ?>

    <script src="https://kit.fontawesome.com/73fbcb87e6.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
    <script src="js/hot_issue.js"></script>
    <script src="js/member.js"></script>

</body>

</html>