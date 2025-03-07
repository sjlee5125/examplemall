<?php
// DB 연결 포함
include('inc/db.php');

// 초기값 설정
$id = '';
$name = '';
$phone = '';
$email = '';
$birth = '';
$is_update_mode = false; // 등록/수정 모드 구분

// GET 요청으로 전달된 회원 ID로 데이터 조회 (수정 모드)
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // 회원 데이터 조회
    $query = "SELECT * FROM members WHERE id = ?";
    $member = db_select($query, [$id]);

    if ($member && count($member) > 0) {
        $is_update_mode = true; // 수정 모드
        $member = $member[0];
        $name = $member['name'];
        $phone = $member['phone'];
        $email = $member['email'];
        $birth = $member['birth'];
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $is_update_mode ? '회원 수정' : '회원 등록' ?></title>
    <link rel="stylesheet" href="css/manager.css">
    <script>
        function handleCustomDomainChange() {
            const emailDomainSelect = document.querySelector('select[name="email_domain"]');
            const emailCustomDomainInput = document.querySelector('input[name="email_custom_domain"]');
            if (emailDomainSelect.value === 'custom') {
                emailCustomDomainInput.style.display = 'inline-block';
                emailCustomDomainInput.required = true;
            } else {
                emailCustomDomainInput.style.display = 'none';
                emailCustomDomainInput.value = '';
                emailCustomDomainInput.required = false;
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <!-- 상단 메뉴 -->
        <div class="header">
            <ul>
                <a href="index.php"><li>홈</li></a>
                <a href="manager_member.php"><li class="active">회원관리</li></a>
                <a href="manager_product.php"><li>상품관리</li></a>
                <a href="manager_notice.php"><li>공지사항관리</li></a>
                <a href="manager_review.php"><li>리뷰관리</li></a>
                <a href="manager_faq.php"><li>FAQ관리</li></a>
            </ul>
        </div>

        <div class="content">
            <h1><?= $is_update_mode ? '회원 수정' : '회원 등록' ?></h1>
            <form method="POST" action="<?= $is_update_mode ? 'update_member.php' : 'insert_member.php' ?>">
                <table class="form-table">
                    <!-- 아이디 -->
                    <tr>
                        <th>아이디</th>
                        <td>
                            <input type="text" name="id" value="<?= htmlspecialchars($id) ?>" <?= $is_update_mode ? 'readonly' : 'required' ?>>
                        </td>
                    </tr>

                    <!-- 비밀번호 -->
                    <tr>
                        <th>비밀번호</th>
                        <td>
                            <input type="password" name="password" placeholder="비밀번호를 입력하세요" <?= $is_update_mode ? '' : 'required' ?>>
                        </td>
                    </tr>

                    <!-- 이름 -->
                    <tr>
                        <th>회원명</th>
                        <td>
                            <input type="text" name="name" value="<?= htmlspecialchars($name) ?>" required>
                        </td>
                    </tr>

                    <!-- 전화번호 -->
                    <tr>
                        <th>전화번호</th>
                        <td>
                            <input type="text" name="phone" value="<?= htmlspecialchars($phone) ?>" required>
                        </td>
                    </tr>

                    <!-- 이메일 -->
                    <tr>
                        <th>이메일</th>
                        <td>
                            <div style="display: flex; align-items: center; gap: 5px;">
                                <input type="text" name="email_local" placeholder="이메일 앞부분" 
                                       value="<?= htmlspecialchars(explode('@', $email)[0] ?? '') ?>" 
                                       style="width: 150px;" required>
                                @
                                <select name="email_domain" onchange="handleCustomDomainChange()" style="width: 150px;" required>
                                    <option value="" disabled <?= empty(explode('@', $email)[1] ?? '') ? 'selected' : '' ?>>선택</option>
                                    <option value="gmail.com" <?= (explode('@', $email)[1] ?? '') === 'gmail.com' ? 'selected' : '' ?>>gmail.com</option>
                                    <option value="naver.com" <?= (explode('@', $email)[1] ?? '') === 'naver.com' ? 'selected' : '' ?>>naver.com</option>
                                    <option value="daum.net" <?= (explode('@', $email)[1] ?? '') === 'daum.net' ? 'selected' : '' ?>>daum.net</option>
                                    <option value="custom" <?= !in_array(explode('@', $email)[1] ?? '', ['gmail.com', 'naver.com', 'daum.net', 'yahoo.com']) ? 'selected' : '' ?>>직접 입력</option>
                                </select>
                                <input type="text" name="email_custom_domain" placeholder="직접 입력" 
                                       value="<?= htmlspecialchars(!in_array(explode('@', $email)[1] ?? '', ['gmail.com', 'naver.com', 'daum.net', 'yahoo.com']) ? explode('@', $email)[1] ?? '' : '') ?>" 
                                       style="display: <?= !in_array(explode('@', $email)[1] ?? '', ['gmail.com', 'naver.com', 'daum.net', 'yahoo.com']) ? 'inline-block' : 'none' ?>; width: 150px;">
                            </div>
                        </td>
                    </tr>

                    <!-- 생년월일 -->
                    <tr>
                        <th>생년월일</th>
                        <td><input type="date" name="birth" value="<?= htmlspecialchars($birth) ?>"></td>
                    </tr>
                </table>
                <div class="form-actions">
    <!-- 등록/수정 버튼 -->
                    <button type="submit" 
                            formaction="<?= $is_update_mode ? 'update_member.php' : 'insert_member.php' ?>" 
                            class="save-btn">
                        <?= $is_update_mode ? '수정' : '등록' ?>
                    </button>

                    <!-- 삭제 버튼 -->
                    <?php if ($is_update_mode): ?>
                        <button type="submit" 
                                formaction="delete_member.php" 
                                class="delete-btn" 
                                onclick="return confirm('정말로 삭제하시겠습니까? 이 작업은 되돌릴 수 없습니다.');">
                            삭제
                        </button>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
