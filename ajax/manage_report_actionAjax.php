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

        $commentId = test_input($_POST['commentId']);
        $action = test_input($_POST['action']);

        if (empty($commentId) || empty($action)) {
            header('Location: home.php?error=unexpected_error');
        }
        else {
            require_once '../dbconnect.php';
            if ($action == "ignore"){
                $sql = 'UPDATE report SET Report_Read = 1 WHERE Comment_ID = ?';
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(1, $commentId,PDO::PARAM_INT);
                $stmt->execute();
            }
            else if ($action == "delete"){
                $sql = 'DELETE FROM comment WHERE Comment_ID = ?';
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(1, $commentId,PDO::PARAM_INT);
                $stmt->execute();
            }
        }
    }
    else {
        header('HTTP/1.0 403 Forbidden');
    }
?>