<?php require_once("contents.import.php"); ?>
<?php ini_set('display_errors', '0'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>관리자 페이지-상품 검색</title>
</head>

<body id="manager_body">
    <main class="manager_wrapper product">
        <div class="main_menu_wrapper">
            <a href="manager_home.php">
                <div class="menu"> 홈 </div>
            </a>
            <a href="manager_notice.php">
                <div class="menu"> 공지사항 관리 </div>
            </a>
            <a href="manager_product.php">
                <div class="menu" style="background-color: rgb(74 173 255);"> 상품 관리 </div>
            </a>
            <a href="manager_event.php">
                <div class="menu"> 이벤트 관리 </div>
            </a>
            <a href="manager_inquiry.php">
                <div class="menu"> 고객 문의 관리 </div>
            </a>
        </div>

        <div class="main_display">
            <header>
                <div class="login_info">
                    <span class="on_id"> 접속 아이디: avbs345 </span>
                    <span class="on_dep"> 부서: 웹팀 </span>
                </div>
                <a href="index.php"><button class="logout"> logout </button></a>
            </header>
            <section class="contents">
                <section class="contents_header">
                    <div class="title_and_order">
                        <span class="title">상품 관리</span>
                        <div class="order_wrapper">
                            <div class="order_one">
                                <div class="registration"> 등록순 </div>
                                <div class="check"> 조회순 </div>
                            </div>
                            <div class="order_two">
                                <div class="ascending_order"> 오름차순 </div>
                                <div class="descending_order"> 내림차순 </div>
                            </div>
                        </div>
                    </div>
                    <form method="POST">
                        <input type="text" class="search_product" name="search" placeholder="상품명을 입력하세요">
                        <button type="submit" class="regist_product"> 검색 </button>
                    </form>
                    <?php $search = $_POST['search'] ?? ''; ?>
                </section>

                <article class="scroller">
                    <form action="" method="POST">
                        <section class="board product">
                            <div class="table_header">
                                <div class="table_col class">카테고리</div>
                                <div class="table_col title">상품명</div>
                                <div class="table_col code">상품코드</div>
                                <div class="table_col deliv">오늘배송</div>
                                <div class="table_col date">날짜</div>
                            </div>
                            <?php
                            // 각 카테고리별 데이터를 검색하여 출력
                            foreach ($result as $category => $items) { 
                                foreach ($items as $r) { 
                                    // content_code를 이용해 날짜 추출
                                    $content_code = $r['content_code'];
                                    $year = "20" . substr($content_code, 0, 2);
                                    $month = substr($content_code, 2, 2);
                                    $day = substr($content_code, 4, 2);
                                    $date = $year . '-' . $month . '-' . $day;

                                    // 검색어가 상품명에 포함되어 있는지 확인
                                    if ($search && strpos($r['content_name'], $search) === false) {
                                        continue; // 검색어가 포함되지 않은 경우 건너뜀
                                    }
                                    ?>
                                    <div class="table_row">
                                        <div class="table_col class"><?php echo htmlspecialchars($category); ?></div>
                                        <div class="table_col title"><?php echo htmlspecialchars($r['content_name']); ?></div>
                                        <div class="table_col code"><?php echo htmlspecialchars($r['content_code']); ?></div>
                                        <div class="table_col deliv"><?php echo htmlspecialchars($r['deliv_today']); ?></div>
                                        <div class="table_col date"><?php echo htmlspecialchars($date); ?></div>
                                    </div>
                            <?php
                                }
                            }
                            ?>
                        </section>
                    </form>
                </article>
            </section>
        </div>
    </main>
</body>

</html>
