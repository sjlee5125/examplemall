<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>적립금</title>
    <link rel="stylesheet" href="css/mileage.css"> <!-- 별도 CSS 파일 연결 -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</head>
<body>
    <?php include("inc/header.php"); ?>
    

<main>
    <section class="mileage-summary">
        <h2>적립금</h2>
        <table class="mileage-table">
            <tr>
                <td>총 적립금</td>
                <td>0원</td>
                <td>사용 가능한 적립금</td>
                <td>0원</td>
            </tr>
            <tr>
                <td>사용된 적립금</td>
                <td>0원</td>
                <td>미가용 적립금</td>
                <td>0원</td>
                <td><button class="query-button">조회</button></td>
            </tr>
            <tr>
                <td>환불 예정 적립금</td>
                <td>0원</td>
            </tr>
        </table>
    </section>

    <section class="mileage-history">
        <div class="history-buttons">
            <button>적립 내역 보기</button>
            <button>미가용 적립 내역 보기</button>
            <button>미가용 쿠폰/회원등급 적립 내역</button>
        </div>
        <table>
            <thead>
                <tr>
                    <th>주문일자 [주문번호]</th>
                    <th>적립금</th>
                    <th>관련 주문</th>
                    <th>내용</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="4">적립금 내역이 없습니다</td>
                </tr>
            </tbody>
        </table>
    </section>
</main>
<?php include("inc/footer.php"); ?>
</body>
</html>
