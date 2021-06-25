<?php
    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $user_id = test_input($_POST['user_id']);
        
        if (empty($user_id) && $user_id != 0) {
            header('HTTP/1.0 403 Forbidden');
        }
        else {
            require '../dbconnect.php';

            $sql = "UPDATE user, video SET IsBanned=1, Status='Deleted' WHERE user.User_ID=? AND video.User_ID=? AND Status='Approved'";
            $result = $conn->prepare($sql);
            $result->bindParam(1, $user_id,PDO::PARAM_INT);
            $result->bindParam(2, $user_id,PDO::PARAM_INT);
            $result->execute();

            echo "success"; 
        }
    }
    else {
        header('HTTP/1.0 403 Forbidden');
    }

?>