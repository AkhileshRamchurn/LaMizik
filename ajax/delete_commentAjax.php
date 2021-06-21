<?php
    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $commentId = test_input($_POST['comment_id']);
        
        if (empty($commentId)) {
            header('HTTP/1.0 403 Forbidden');
        }
        else {
            require '../dbconnect.php';

            $sql = "DELETE FROM comment WHERE Comment_ID = ?";
            $result = $conn->prepare($sql);
            $result->bindParam(1, $commentId,PDO::PARAM_INT);
            $result->execute();

            echo "success"; 
        }
    }
    else {
        header('HTTP/1.0 403 Forbidden');
    }

?>