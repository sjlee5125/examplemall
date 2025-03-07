<?php
// 출력 버퍼링 시작
ob_start();

// DB 연결 파일 포함
require_once("inc/db.php");

// POST로 전달된 ID와 PW 가져오기
$login_id = isset($_POST['id']) ? trim($_POST['id']) : null;
$login_pw = isset($_POST['pass']) ? trim($_POST['pass']) : null;

// 입력 값 검증
if ($login_id == null || $login_pw == null) {    
    echo("<script>alert('모두 입력해주세요.'); window.location.href='login.php';</script>");
    exit();
}

// 회원 데이터 조회
$member_data = db_select("SELECT * FROM members WHERE id = ?", array($login_id));

// 회원 데이터가 없을 경우 처리
if ($member_data == null || count($member_data) == 0) {
    echo("<script>alert('회원가입을 먼저 진행해주세요.'); window.location.href='login.php';</script>");
    exit();
}

// 비밀번호 검증
$is_match_password = password_verify($login_pw, $member_data[0]['pass']);
if ($is_match_password === false) {
    echo("<script>alert('잘못된 아이디 또는 비밀번호입니다.'); window.location.href='login.php';</script>");
    exit();
}

// 세션 설정
require_once("inc/session.php");    
$_SESSION['member_id'] = $member_data[0]['id'];

// 메인 페이지로 이동
header("Location: index.php");
exit();
?>
