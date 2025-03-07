<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "exam_mall_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("연결 실패: " . $conn->connect_error);
}
?>
