<?php
    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $video_id = test_input($_POST['video_id']);
        
        if (empty($video_id) && $video_id != 0) {
            header('HTTP/1.0 403 Forbidden');
        }
        else {
            require '../dbconnect.php';

            $sql = "UPDATE video SET Status='Deleted' WHERE Video_ID=?";
            $result = $conn->prepare($sql);
            $result->bindParam(1, $video_id,PDO::PARAM_INT);
            $result->execute();

            echo "success"; 
        }
    }
    else {
        header('HTTP/1.0 403 Forbidden');
    }

?>