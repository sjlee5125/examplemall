<?php require_once("session.php"); ?>

<header id="header">
    <div class="inner">
        <!-- 상단 영역: 아이콘, 실시간 키워드, 검색바, 장바구니, 프로필 -->
        <div class="all_width">
            <div class="top_nav"> <!-- 기존 선 제거, 패딩 추가 -->
                <div class="icon_section">
                    <a href="index.php">
                        <img src="img/icon1.png" alt="아이콘" class="header_icon">
                    </a>
                </div>

                <div class="right_top_section">
                    <div class="real_time_keyword" id="real_time_keyword">
                        <span>1. 패딩</span>
                    </div>

                    <form method="GET" action="search.php" class="search_wrapper">
                        <input type="text" placeholder="검색어를 입력하세요" class="search_input" name="keyword" />
                        <button type="submit" class="search_button">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>

                    <div class="icons">
                        <a href="cart.php" class="cart_nav">
                            <i class="fas fa-shopping-cart"></i>
                        </a>
                        <a href="mypage.php" class="profile_nav">
                            <i class="far fa-user"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- 하단 영역: 카테고리 메뉴와 유저 메뉴 -->
        <div class="bottom_cate_box">
            <div class="all_width">
                <div class="bottom_nav">
                    <div class="category_menu">
                        <a href="outer.php">OUTER</a>
                        <a href="top.php">TOP</a>
                        <a href="knit.php">KNIT</a>
                        <a href="shirts.php">SHIRTS</a>
                        <a href="pants.php">PANTS</a>
                    </div>

                    <div class="user_menu">
                        <?php
                        if (!isset($_SESSION['member_id'])) {
                        ?>
                            <a href="sign_up.php">회원가입</a>
                            <a href="login.php">로그인</a>
                        <?php
                        } else {
                        ?>
                            <a href="logout.php">로그아웃</a>
                            <a href="mypage.php">마이페이지</a>
                        <?php
                        }
                        ?>
                        <a href="community_notice.php">커뮤니티</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>

<!-- 실시간 키워드 변경을 위한 자바스크립트 추가 -->
<script>
    const keywords = ["1. 패딩", "2. 코트", "3. 니트트"];
    let keywordIndex = 0;

    function changeKeyword() {
        keywordIndex = (keywordIndex + 1) % keywords.length;
        document.getElementById("real_time_keyword").innerText = keywords[keywordIndex];
    }

    // 3초마다 키워드 변경
    setInterval(changeKeyword, 3000);
</script>