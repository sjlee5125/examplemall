<?php
//https://wikidocs.net/117002
//https://www.codingfactory.net/10075
// https://programmerdaddy.tistory.com/232
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

//가져오기
function db_select($query, $param = array())
{
    $pdo = db_get_pdo();
    try {
        $st = $pdo->prepare($query);
        $st->execute($param);
        $result = $st->fetchAll(PDO::FETCH_ASSOC);
        $pdo = null;
        return $result;
    } catch (PDOException $ex) {
        return false;
    } finally {
        $pdo = null;
    }
}

function db_insert($query, $param = array())
{
    $pdo = db_get_pdo();
    try {
        $st = $pdo->prepare($query);
        $result = $st->execute($param);
        $last_id = $pdo->lastInsertId();
        $pdo = null;
        if ($result) {
            return $last_id;
        } else {
            return false;
        }
    } catch (PDOException $ex) {
        return "SQL 오류: " . $ex->getMessage(); // 오류 메시지 반환
    } finally {
        $pdo = null;
    }
}

function db_update_delete($query, $param = array())
{
    $pdo = db_get_pdo();
    try {
        $st = $pdo->prepare($query);
        $result = $st->execute($param);
        $pdo = null;
        return $result;
    } catch (PDOException $ex) {
        return false;
    } finally {
        $pdo = null;
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
function db_get_last_insert_id()
{
    try {
        $pdo = db_get_pdo(); // db_get_pdo는 PDO 연결 객체를 반환하는 함수여야 함
        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}
