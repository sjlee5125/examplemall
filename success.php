<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>가입 완료</title>
    <style>
        body {
            font-family: 'Noto Sans', sans-serif;
            background-color: #f7f8fc;
            color: #333;
            text-align: center;
            margin: 0;
            padding: 0;
        }

        .container {
            margin: 100px auto;
            padding: 30px;
            width: 500px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .success-title {
            font-size: 28px;
            font-weight: bold;
            color: #2a5db0;
            margin-bottom: 10px;
        }

        .success-message {
            font-size: 20px;
            font-weight: normal;
            color: #555;
            margin-bottom: 30px;
        }

        a {
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
            color: #fff;
            background-color: #2a5db0;
            padding: 12px 20px;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        a:hover {
            background-color: #1e4a8c;
            transform: translateY(-3px);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
        }

        #close {
            margin-top: 20px;
            cursor: pointer;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-title">🎉 축하합니다!</div>
        <div class="success-message">회원 가입이 정상적으로 완료되었습니다.</div>
        <a href="index.php">메인페이지로 돌아가기</a>
        <div id="close">창을 닫으려면 여기를 클릭하세요</div>
    </div>
</body>
</html>
