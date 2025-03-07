<?php
    $id   = $_POST["id"];
    $pass = $_POST["pass"];
    $name = $_POST["name"];
    $phone = $_POST["phone"];
    $birth = $_POST["birth"];
    $email1  = $_POST["email1"];
    $email2  = $_POST["email2"];
    $email = $email1."@".$email2;
    $refferer = $_POST["refferer"];
    $regist_day = date("Y-m-d (H:i)");  // 현재의 '년-월-일-시-분'을 저장              
    $con = mysqli_connect("localhost", "root", "", "exmall");

    // 비밀번호 암호화
    $bcrypt_pw = password_hash($pass, PASSWORD_BCRYPT);

	$sql = "insert into members(id, pass, name, phone, birth, email, refferer) ";
	$sql .= "values('$id', '$bcrypt_pw', '$name','$phone', '$birth', '$email','$refferer')";

	mysqli_query($con, $sql);  // $sql 에 저장된 명령 실행
    mysqli_close($con);     

    echo "
        <script>
            location.href = 'success.php';
        </script>";
?>
