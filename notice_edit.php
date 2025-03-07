<?php
// DB 연결 포함
include('inc/db.php');

// 초기값 설정
$is_update_mode = false; // 등록/수정 모드 구분
$notice_id = '';
$title = '';
$writer = '';
$content = '';

// 공지 ID가 전달된 경우 해당 데이터 불러오기 (수정 모드)
if (isset($_GET['notice_id'])) {
    $is_update_mode = true; // 수정 모드
    $notice_id = $_GET['notice_id'];
    $query = "SELECT * FROM notice WHERE notice_id = ?";
    $notice = db_select($query, [$notice_id]);

    if ($notice && count($notice) > 0) {
        $notice = $notice[0];
        $notice_id = $notice['notice_id'];
        $title = $notice['title'];
        $writer = $notice['writer'];
        $content = $notice['content'];
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $is_update_mode ? '공지 수정' : '공지 등록' ?></title>
    <link rel="stylesheet" href="css/manager.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <ul>
                <a href="#"><li>홈</li></a>
                <a href="manager_member.php"><li>회원관리</li></a>
                <a href="manager_product.php"><li>상품관리</li></a>
                <a href="manager_notice.php"><li class="active">공지사항관리</li></a>
                <a href="manager_review.php"><li>리뷰관리</li></a>
                <a href="manager_faq.php"><li>FAQ관리</li></a>
            </ul>
        </div>

        <div class="content">
            <h1><?= $is_update_mode ? '공지 수정' : '공지 등록' ?></h1>
            <form method="POST" action="process_notice.php">
                <input type="hidden" name="action" value="<?= $is_update_mode ? 'update' : 'insert' ?>">
                <?php if ($is_update_mode): ?>
                    <input type="hidden" name="notice_id" value="<?= htmlspecialchars($notice_id) ?>">
                <?php endif; ?>
                
                <table class="form-table">
                    <!-- 제목 -->
                    <tr>
                        <th>제목</th>
                        <td><input type="text" name="title" placeholder="제목을 입력하세요" value="<?= htmlspecialchars($title) ?>" required></td>
                    </tr>

                    <!-- 작성자 -->
                    <tr>
                        <th>작성자</th>
                        <td><input type="text" name="writer" placeholder="작성자를 입력하세요" value="<?= htmlspecialchars($writer) ?>" required></td>
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
