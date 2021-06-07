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

        $userId = test_input($_POST['userId']);

        if (empty($userId) && $userId != 0) {
            header('HTTP/1.0 403 Forbidden');
        }
        else {
            require_once '../dbconnect.php';
            $sql = 'UPDATE user SET isBanned=0 WHERE User_ID = ?';
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(1, $userId,PDO::PARAM_INT);
            $stmt->execute();
        }
    }
    else {
        header('HTTP/1.0 403 Forbidden');
    }
?>