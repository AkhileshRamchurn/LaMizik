<?php
    session_start();

    $userType=null;
    if (isset($_SESSION['User_Type'])) {
        $userType=$_SESSION['User_Type'];
    }

    if($userType=="Admin" && $_SERVER['REQUEST_METHOD'] == 'POST') {

        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $username = test_input($_POST['username']);
        $password = test_input($_POST['password']);

        if (empty($username) || empty($password)) {
            header('HTTP/1.0 403 Forbidden');
        }
        else {
            require_once '../dbconnect.php';
            $sql = 'INSERT INTO user(Username,First_name,Last_name,Email,Password,User_Type) VALUES(?,"admin","admin","admin@lamizik.com",?,"Admin")';
            $stmt = $conn->prepare($sql);
            $hashedPwd = password_hash($password, PASSWORD_DEFAULT);
            $stmt ->bindParam(1,$username,PDO::PARAM_STR);
            $stmt ->bindParam(2,$hashedPwd,PDO::PARAM_STR);
            $stmt->execute();
        }
    }
    else {
        header('HTTP/1.0 403 Forbidden');
    }
?>