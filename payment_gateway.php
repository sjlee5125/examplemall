<?php
require_once("inc/session.php");
require_once("inc/db.php");

// 주문 ID 확인
$order_id = $_GET['order_id'] ?? null;
if (!$order_id) {
    die("주문 정보가 없습니다.");
}

// TODO: 여기에 PG 결제 게이트웨이 호출 코드를 추가하세요.

?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>결제 중</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include("inc/header.php"); ?>

    <main class="main_wrapper">
        <h1>결제 중입니다...</h1>
        <!-- 필요한 경우 결제 게이트웨이 스크립트 또는 폼을 추가하세요 -->
    </main>
</body>
</html>
