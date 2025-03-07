<?php
// DB 연결 포함
include('inc/db.php');

// 리뷰 삭제 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $delete_query = "DELETE FROM review WHERE review_id = :review_id";
    $delete_result = db_update_delete($delete_query, [':review_id' => $delete_id]);

    if ($delete_result) {
        header("Location: manager_review.php"); // 삭제 후 페이지 새로고침
        exit;
    } else {
        echo "<script>alert('삭제 실패. 다시 시도해주세요.');</script>";
    }
}

// 페이지네이션 설정
$items_per_page = 10; // 한 페이지에 표시할 항목 수
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // 현재 페이지
$page = $page > 0 ? $page : 1; // 페이지가 0 이하일 경우 1로 설정
$offset = ($page - 1) * $items_per_page; // 시작점 계산

// 검색 조건 처리
$search_field = $_GET['search_field'] ?? 'review_text'; // 검색 필드 (기본: 리뷰 내용)
$search_text = $_GET['search_text'] ?? ''; // 검색어

// WHERE 절 생성
$where_sql = '';
$params = [];
if (!empty($search_text)) {
    $where_sql = "WHERE $search_field LIKE :search_text";
    $params[':search_text'] = "%$search_text%";
}

// 총 리뷰 개수 가져오기
$total_items_query = "SELECT COUNT(*) AS total FROM review $where_sql";
$total_items_result = db_select($total_items_query, $params);
$total_items = $total_items_result[0]['total'] ?? 0;

// 총 페이지 수 계산
$total_pages = ceil($total_items / $items_per_page);

// 리뷰 데이터 가져오기
$query = "SELECT review_id, member_id, content_code, review_text, photo, rating, review_date 
          FROM review $where_sql ORDER BY review_id DESC LIMIT $offset, $items_per_page";
$reviews = db_select($query, $params);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>리뷰 관리</title>
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
                <a href="manager_review.php"><li class="active">리뷰관리</li></a>
                <a href="manager_faq.php"><li>FAQ관리</li></a>
            </ul>
        </div>

        <!-- 검색 폼 -->
        <div class="content">
            <h1>리뷰 관리</h1>
            <form class="search-form" method="GET">
                <div class="search-bar-inline">
                    <label for="search_field">검색 조건</label>
                    <select name="search_field" id="search_field">
                        <option value="content_code" <?= $search_field === 'content_code' ? 'selected' : '' ?>>상품 코드</option>
                        <option value="review_text" <?= $search_field === 'review_text' ? 'selected' : '' ?>>리뷰 내용</option>
                        <option value="member_id" <?= $search_field === 'member_id' ? 'selected' : '' ?>>작성자 ID</option>
                    </select>
                    <input type="text" name="search_text" placeholder="검색어를 입력하세요" value="<?= htmlspecialchars($search_text) ?>">
                </div>
                <div class="form-actions">
                    <button type="submit">검색</button>
                    <button type="reset" onclick="window.location.href='?';">초기화</button>
                </div>
            </form>
        </div>

        <!-- 리뷰 목록 -->
        <table class="review-table">
            <thead>
                <tr>
                    <th>번호</th>
                    <th>사진</th>
                    <th>리뷰 ID</th>
                    <th>작성자 ID</th>
                    <th>상품 코드</th>
                    <th>리뷰 내용</th>
                    <th>별점</th>
                    <th>등록일</th>
                    <th>삭제</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($reviews && count($reviews) > 0) {
                    $number = $total_items - $offset; // 역순 번호 계산
                    foreach ($reviews as $review) {
                        echo "<tr>";
                        echo "<td>" . $number-- . "</td>";
                        echo "<td><img src='" . htmlspecialchars($review['photo']) . "' alt='리뷰 이미지' style='width: 50px; height: 50px;'></td>";
                        echo "<td>" . htmlspecialchars($review['review_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($review['member_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($review['content_code']) . "</td>";
                        echo "<td>" . htmlspecialchars($review['review_text']) . "</td>";
                        echo "<td>" . htmlspecialchars($review['rating']) . "점</td>";
                        echo "<td>" . htmlspecialchars($review['review_date']) . "</td>";
                        echo "<td>
                                <form method='POST' style='margin: 0;'>
                                    <input type='hidden' name='delete_id' value='" . htmlspecialchars($review['review_id']) . "'>
                                    <button type='submit' onclick='return confirm(\"정말 삭제하시겠습니까?\")'>삭제</button>
                                </form>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>리뷰가 없습니다.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- 페이지네이션 -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>">이전</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?= $i ?>" class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
            <?php if ($page < $total_pages): ?>
                <a href="?page=<?= $page + 1 ?>">다음</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
