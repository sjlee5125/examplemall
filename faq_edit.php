<?php
// DB 연결 포함
include('inc/db.php');

// 초기값 설정
$is_update_mode = false; // 등록/수정 모드 구분
$faq_id = '';
$title = '';
$content = '';

// FAQ ID가 전달된 경우 해당 데이터 불러오기 (수정 모드)
if (isset($_GET['faq_id'])) {
    $is_update_mode = true; // 수정 모드
    $faq_id = $_GET['faq_id'];

    // FAQ 데이터 가져오기
    $query = "SELECT * FROM faq WHERE faq_id = :faq_id";
    $faq = db_select($query, [':faq_id' => $faq_id]);

    if ($faq && count($faq) > 0) {
        $faq = $faq[0];
        $faq_id = $faq['faq_id'];
        $title = $faq['title'];
        $content = $faq['content'];
    } else {
        echo "<script>alert('존재하지 않는 FAQ입니다.'); window.location.href='manager_faq.php';</script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $is_update_mode ? 'FAQ 수정' : 'FAQ 등록' ?></title>
    <link rel="stylesheet" href="css/manager.css">
</head>
<body>
    <div class="container">
        <!-- 상단 메뉴 -->
        <div class="header">
            <ul>
                <a href="index.php"><li>홈</li></a>
                <a href="manager_member.php"><li>회원관리</li></a>
                <a href="manager_product.php"><li>상품관리</li></a>
                <a href="manager_notice.php"><li>공지사항관리</li></a>
                <a href="manager_review.php"><li>리뷰관리</li></a>
                <a href="manager_faq.php"><li class="active">FAQ관리</li></a>
            </ul>
        </div>

        <!-- FAQ 등록/수정 폼 -->
        <div class="content">
            <h1><?= $is_update_mode ? 'FAQ 수정' : 'FAQ 등록' ?></h1>
            <form method="POST" action="process_faq.php">
                <input type="hidden" name="action" value="<?= $is_update_mode ? 'update' : 'insert' ?>">
                <?php if ($is_update_mode): ?>
                    <input type="hidden" name="faq_id" value="<?= htmlspecialchars($faq_id) ?>">
                <?php endif; ?>

                <table class="form-table">
                    <!-- 제목 -->
                    <tr>
                        <th>제목</th>
                        <td><input type="text" name="title" placeholder="제목을 입력하세요" value="<?= htmlspecialchars($title) ?>" required></td>
                    </tr>

                    <!-- 내용 -->
                    <tr>
                        <th>내용</th>
                        <td><textarea name="content" placeholder="내용을 입력하세요" required><?= htmlspecialchars($content) ?></textarea></td>
                    </tr>
                </table>

                <div class="form-actions">
                    <!-- 등록/수정 버튼 -->
                    <button type="submit" class="save-btn">
                        <?= $is_update_mode ? '수정' : '등록' ?>
                    </button>

                    <!-- 삭제 버튼 -->
                    <?php if ($is_update_mode): ?>
                        <button type="submit" 
                                name="action" 
                                value="delete" 
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
