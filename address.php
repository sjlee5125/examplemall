<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>정기배송 관리</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/address.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <header>
        <?php include("inc/header.php"); ?>
    </header>

    <main>
        <div class="content">
            <h2>정기배송 관리</h2>
            <div class="card-registration">
    <div class="card-texts">
        <span class="card-title">정기배송 결제 카드</span>
        <span class="card-divider">|</span>
        <span class="card-subtitle">카드를 등록해주세요</span>
        <button class="register-button">결제카드 등록하기</button>
    </div>
    <p class="card-info">결제카드가 등록되어 있을 경우 빠른 정기배송 신청이 가능합니다.</p>
    <p class="card-warning">*결제예정일 (주기별 배송시작일 하루 전)에 위의 카드 정보로 정기배송 상품이 결제됩니다.</p>
</div>

            <table class="tabs">
                <thead>
                    <tr>
                        <th>신청 내역 (0)</th>
                        <th>해지 내역 (0)</th>
                    </tr>
                </thead>
            </table>
            <table>
                <thead>
                    <tr>
                        <th>신청일자 [신청번호]</th>
                        <th>배송주기</th>
                        <th>배송 시작일</th>
                        <th>상품정보</th>
                        <th>수량</th>
                        <th>금액</th>
                        <th>선택</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="7">신청 내역이 없습니다</td>
                    </tr>
                </tbody>
            </table>
            <div class="pagination">
                <span>&lt;&lt;</span>
                <span>1</span>
                <span>&gt;&gt;</span>
            </div>
        </div>
    </main>
    <?php include("inc/footer.php"); ?>
</body>
</html>
