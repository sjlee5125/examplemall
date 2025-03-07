<?php 
// 데이터베이스 연결 설정
function db_get_pdo()
{
    $host = 'localhost';
    $port = '3306';
    $dbname = 'exmall';
    $charset = 'utf8';
    $username = 'root';
    $db_pw = "";
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=$charset";
    try {
        $pdo = new PDO($dsn, $username, $db_pw);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // 오류 모드 설정
        return $pdo;
    } catch (PDOException $ex) {
        die("DB 연결 오류: " . $ex->getMessage()); // 오류 메시지 출력
    }
}

// 일반 쿼리 실행 함수 (추가된 db_query)
function db_query($query, $params = array())
{
    $pdo = db_get_pdo();
    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        return $stmt;
    } catch (PDOException $ex) {
        die("Database error: " . $ex->getMessage());
    } finally {
        $pdo = null;
    }
}

// SELECT 쿼리 함수
function db_select($query, $param = array())
{
    $stmt = db_query($query, $param);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// INSERT 쿼리 함수 (lastInsertId 반환)
function db_insert($query, $param = array())
{
    $pdo = db_get_pdo();
    try {
        $stmt = $pdo->prepare($query);
        $result = $stmt->execute($param);
        $last_id = $pdo->lastInsertId();
        return $result ? $last_id : false;
    } catch (PDOException $ex) {
        die("Insert Error: " . $ex->getMessage());
    } finally {
        $pdo = null;
    }
}

// UPDATE 및 DELETE 쿼리 함수
function db_update_delete($query, $param = array())
{
    $stmt = db_query($query, $param);
    return $stmt->rowCount() > 0;
}
function db_last_insert_id($pdo) {
    return $pdo->lastInsertId();
}
function db_select_one($query, $params = [])
{
    try {
        $pdo = db_get_pdo(); // PDO 연결 가져오기
        $stmt = $pdo->prepare($query);

        if (!$stmt) {
            throw new PDOException("쿼리 준비 실패.");
        }

        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC); // 한 행만 가져오기
        
        return $result ?: null; // 결과가 없으면 null 반환
    } catch (PDOException $e) {
        // 에러를 로그 파일에 기록
        error_log("Database error: " . $e->getMessage());
        return null; // null 반환으로 실패 처리
    }
}
function db_get_last_insert_id() {
    try {
        $pdo = db_get_pdo(); // db_get_pdo는 PDO 연결 객체를 반환하는 함수여야 함
        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}
function db_connect() {
    $host = 'localhost'; // 데이터베이스 호스트
    $dbname = 'exmall'; // 데이터베이스 이름
    $username = 'root'; // 데이터베이스 사용자
    $password = ''; // 데이터베이스 비밀번호

    try {
        $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    } catch (PDOException $e) {
        die("Database connection error: " . $e->getMessage());
    }
}


?>
