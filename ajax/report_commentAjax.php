<?php
    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $comment_id = test_input($_POST['comment_id']);
        $user_id = test_input($_POST['user_id']);
        $report_reason = test_input($_POST['report_reason']);
        
        if (empty($comment_id) || empty($user_id)) {
            header('HTTP/1.0 403 Forbidden');
        }
        else {
            require '../dbconnect.php';

            $sql = "INSERT INTO report (Report_Description, User_ID, Comment_ID) VALUES (?,?,?)";
            $result = $conn->prepare($sql);
            $result->bindParam(1, $report_reason,PDO::PARAM_STR);
            $result->bindParam(2, $user_id,PDO::PARAM_INT);
            $result->bindParam(3, $comment_id,PDO::PARAM_INT);
            $result->execute();

            echo "success"; 
        }
    }
    else {
        header('HTTP/1.0 403 Forbidden');
    }

?>