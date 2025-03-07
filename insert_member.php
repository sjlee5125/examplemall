<?php
// DB 연결 포함
include('inc/db.php');

// POST 요청 확인
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // POST 데이터 받기
    $id = $_POST['id'] ?? '';
    $password = $_POST['password'] ?? '';
    $name = $_POST['name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email_local = $_POST['email_local'] ?? '';
    $email_domain = $_POST['email_domain'] ?? '';
    $email_custom_domain = $_POST['email_custom_domain'] ?? '';
    $birth = !empty($_POST['birth']) ? $_POST['birth'] : null;
    $refferer = $_POST['refferer'] ?? ''; // refferer 필드 추가

    // 이메일 조합
    $email = $email_domain === 'custom' ? 
        "$email_local@$email_custom_domain" : 
        "$email_local@$email_domain";

    // 데이터 유효성 검사
    if (empty($id) || empty($password) || empty($name) || empty($phone) || 
        empty($email_local) || (empty($email_domain) && empty($email_custom_domain))) {
        die("모든 필드를 올바르게 입력해 주세요.");
    }

    // 데이터 길이 확인
    if (strlen($id) > 15) die("ID는 최대 15자까지 입력 가능합니다.");
    if (strlen($name) > 10) die("Name은 최대 10자까지 입력 가능합니다.");
    if (strlen($phone) > 20) die("Phone은 최대 20자까지 입력 가능합니다.");
    if (strlen($email) > 80) die("Email은 최대 80자까지 입력 가능합니다.");
    if (!empty($birth) && strlen($birth) > 20) die("Birth는 최대 20자까지 입력 가능합니다.");
    if (strlen($refferer) > 15) die("Refferer는 최대 15자까지 입력 가능합니다.");

    try {
        // 비밀번호 해싱
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // ID 중복 확인
        $query_check = "SELECT COUNT(*) AS count FROM members WHERE id = ?";
        $result_check = db_select($query_check, [$id]);
        
        if ($result_check && $result_check[0]['count'] > 0) {
            die("이미 존재하는 아이디입니다.");
        }

        // 회원 등록 쿼리 (모든 필드 포함)
        $query = "INSERT INTO members (id, pass, name, phone, email, birth, refferer) 
                 VALUES (?, ?, ?, ?, ?, ?, ?)";
        $params = [$id, $hashed_password, $name, $phone, $email, $birth, $refferer];

        // 디버깅용 출력
        echo "실행 쿼리: " . $query . "<br>";
        echo "파라미터: ";
        print_r($params);
        echo "<br>";

        // 데이터 삽입 실행
        $insert_result = db_insert($query, $params);
        
        if ($insert_result !== false) {
            header("Location: manager_member.php");
            exit;
        } else {
            // db_insert가 실패했을 때의 처리
            echo "회원 등록에 실패했습니다. 관리자에게 문의해주세요.";
            
            // 디버깅을 위한 PDO 객체 직접 사용
            $pdo = db_get_pdo();
            $error = $pdo->errorInfo();
            echo "<br>오류 정보: ";
            print_r($error);
        }

    } catch (PDOException $e) {
        echo "SQL 실행 오류: " . $e->getMessage();
        die();
    }
} else {
    die("잘못된 요청입니다.");
}
?>