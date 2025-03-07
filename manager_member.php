<?php
// DB 연결 포함
include('inc/db.php');

// 페이지네이션 설정
$items_per_page = 10; // 한 페이지에 표시할 항목 수
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // 현재 페이지
$page = $page > 0 ? $page : 1; // 페이지가 0 이하일 경우 1로 설정
$offset = ($page - 1) * $items_per_page; // 시작점 계산

// 검색 조건 처리
$search_type = $_GET['search_type'] ?? ''; // 검색 타입 (회원명, 아이디, 이메일)
$search_text = $_GET['search_text'] ?? ''; // 검색어

// WHERE 절 생성
$where_clauses = [];
if (!empty($search_text) && !empty($search_type)) {
    $allowed_types = ['name', 'id', 'email']; // 허용된 검색 타입
    if (in_array($search_type, $allowed_types)) {
        $search_type = addslashes($search_type); // SQL 인젝션 보호
        $search_text = addslashes($search_text); // SQL 인젝션 보호
        $where_clauses[] = "$search_type LIKE '%$search_text%'";
    }
}

$where_sql = '';
if (count($where_clauses) > 0) {
    $where_sql = 'WHERE ' . implode(' AND ', $where_clauses);
}

// 총 회원 수 가져오기
$total_items_query = "SELECT COUNT(*) AS total FROM members $where_sql";
$total_items_result = db_select($total_items_query);
$total_items = $total_items_result[0]['total'] ?? 0;

// 총 페이지 수 계산
$total_pages = ceil($total_items / $items_per_page);

// 회원 데이터 가져오기
$query = "SELECT id, name, phone, email, birth FROM members $where_sql LIMIT $offset, $items_per_page";
$members = db_select($query);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>회원 관리</title>
    <link rel="stylesheet" href="css/manager.css">
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

        <!-- 검색 폼 -->
        <div class="content">
            <h1>회원 관리</h1>
            <form class="search-form" method="GET">
                <div class="search-bar-inline">
                    <label for="search_type">검색 조건</label>
                    <select name="search_type" id="search_type">
                        <option value="name" <?= $search_type === 'name' ? 'selected' : '' ?>>회원명</option>
                        <option value="id" <?= $search_type === 'id' ? 'selected' : '' ?>>아이디</option>
                        <option value="email" <?= $search_type === 'email' ? 'selected' : '' ?>>이메일</option>
                    </select>
                    <input type="text" name="search_text" placeholder="검색어를 입력하세요" value="<?= htmlspecialchars($search_text) ?>">
                </div>
                <div class="form-actions">
                    <button type="submit">검색</button>
                    <button type="reset" onclick="window.location.href='?';">초기화</button>
                </div>
            </form>
        </div>

        <!-- 회원 목록 -->
        <table class="member-table">
            <thead>
                <tr>
                    <th>번호</th>
                    <th>회원명</th>
                    <th>아이디</th>
                    <th>전화번호</th>
                    <th>이메일</th>
                    <th>생년월일</th>
                    <th>수정</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($members && count($members) > 0): ?>
                    <?php $number = $total_items - $offset; // 역순 번호 계산 ?>
                    <?php foreach ($members as $member): ?>
                        <tr>
                            <td><?= $number-- ?></td>
                            <td><?= htmlspecialchars($member['name']) ?></td>
                            <td><?= htmlspecialchars($member['id']) ?></td>
                            <td><?= htmlspecialchars($member['phone']) ?></td>
                            <td><?= htmlspecialchars($member['email']) ?></td>
                            <td><?= htmlspecialchars($member['birth'] ?? '미입력') ?></td>
                            <td>
                                <form method="GET" action="edit_member.php"> <!-- 수정 버튼 -->
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($member['id']) ?>">
                                    <button type="submit">수정</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">회원이 없습니다.</td> <!-- colspan 수정 -->
                    </tr>
                <?php endif; ?>
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
