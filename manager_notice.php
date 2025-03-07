<?php
// DB 연결 포함
include('inc/db.php');

// 페이지네이션 설정
$items_per_page = 10; // 한 페이지에 표시할 항목 수
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // 현재 페이지
$page = $page > 0 ? $page : 1; // 페이지가 0 이하일 경우 1로 설정
$offset = ($page - 1) * $items_per_page; // 시작점 계산

// 검색 조건 처리
$search_text = $_GET['search_text'] ?? ''; // 검색어

// WHERE 절 생성
$where_sql = '';
$params = [];
if (!empty($search_text)) {
    $where_sql = "WHERE title LIKE :search_text";
    $params[':search_text'] = "%$search_text%";
}

// 총 공지 개수 가져오기
$total_items_query = "SELECT COUNT(*) AS total FROM notice $where_sql";
$total_items_result = db_select($total_items_query, $params);
$total_items = $total_items_result[0]['total'] ?? 0;

// 총 페이지 수 계산
$total_pages = ceil($total_items / $items_per_page);

// 공지 데이터 가져오기
$query = "SELECT notice_id, title, writer, content, created_at, views 
          FROM notice $where_sql ORDER BY created_at DESC LIMIT $offset, $items_per_page";
$notices = db_select($query, $params);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>공지사항 관리</title>
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
                <a href="manager_notice.php"><li class="active">공지사항관리</li></a>
                <a href="manager_review.php"><li>리뷰관리</li></a>
                <a href="manager_faq.php"><li>FAQ관리</li></a>
            </ul>
        </div>


        <!-- 검색 폼 -->
        <div class="content">
            <h1>공지사항 관리</h1>
            <form class="search-form" method="GET">
                <div class="search-bar-inline">
                    <label for="search_text">검색 조건</label>
                    <input type="text" name="search_text" placeholder="제목을 입력하세요" value="<?= htmlspecialchars($search_text) ?>">
                </div>
                <div class="form-actions">
                    <button type="submit">검색</button>
                    <button type="reset" onclick="window.location.href='?';">초기화</button>
                    <button type="button" onclick="window.location.href='notice_edit.php';">등록</button> <!-- 등록 버튼 -->
                </div>
            </form>
        </div>

        <!-- 공지 목록 -->
        <table class="notice-table">
            <thead>
                <tr>
                    <th>번호</th>
                    <th>제목</th>
                    <th>작성자</th>
                    <th>내용</th>
                    <th>작성일시</th>
                    <th>조회수</th>
                    <th>수정</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($notices && count($notices) > 0) {
                    $number = $total_items - $offset; // 역순 번호 계산
                    foreach ($notices as $notice) {
                        echo "<tr>";
                        echo "<td>" . $number-- . "</td>";
                        echo "<td>" . htmlspecialchars($notice['title']) . "</td>";
                        echo "<td>" . htmlspecialchars($notice['writer']) . "</td>";
                        echo "<td>" . htmlspecialchars(mb_substr($notice['content'], 0, 50)) . "...</td>";
                        echo "<td>" . htmlspecialchars($notice['created_at']) . "</td>";
                        echo "<td>" . htmlspecialchars($notice['views']) . "</td>";
                        echo "<td>
                                <form method='GET' action='notice_edit.php' style='margin: 0;'>
                                    <input type='hidden' name='notice_id' value='" . htmlspecialchars($notice['notice_id']) . "'>
                                    <button type='submit'>수정</button>
                                </form>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>공지사항이 없습니다.</td></tr>";
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
