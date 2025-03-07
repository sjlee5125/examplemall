<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>마이 쿠폰</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/coupon.css"> <!-- 쿠폰 CSS 파일 연결 -->
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</head>
<body>
<?php include("inc/header.php"); ?>
    

<main>
    <section class="coupon-summary">
        <h2>마이 쿠폰</h2>
        <table class="coupon-table">
            <thead>
                <tr>
                    <th>번호</th>
                    <th>쿠폰명</th>
                    <th>구매금액</th>
                    <th>결제수단</th>
                    <th>쿠폰 혜택</th>
                    <th>사용 가능 기간</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="6" class="empty-message">보유하고있는 쿠폰이 없습니다</td>
                </tr>
            </tbody>
        </table>
    </section>

    <section class="pagination">
        <div class="pagination-wrapper">
            << <a href="#">1</a> >>
        </div>
    </section>
</main>
<?php include("inc/footer.php"); ?>

</body>
</html>
