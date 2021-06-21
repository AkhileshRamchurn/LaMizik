<?php
    session_start();

    if (($_SERVER['REQUEST_METHOD'] == 'POST') && (isset($_SESSION["Username"]))) {
        
        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $videoId = test_input($_POST['videoId']);
        $userId = test_input($_POST['userId']);
        $comment = test_input($_POST['comment']);
        

        if (empty($videoId) || empty($userId) || empty($comment)) {
            header('HTTP/1.0 403 Forbidden');
        }
        else {
            require '../dbconnect.php';

            $sql = "INSERT INTO comment(Comment_Description, User_ID, Video_ID) VALUES (?, ?, ?)";
            $stmt = $conn -> prepare($sql);

            $stmt ->bindParam(1,$comment,PDO::PARAM_STR);
            $stmt ->bindParam(2,$userId,PDO::PARAM_INT);
            $stmt ->bindParam(3,$videoId,PDO::PARAM_INT);
            $stmt ->execute();

            $lastId = $conn->lastInsertId();
            $sql = "SELECT Comment_Timestamp FROM comment WHERE Comment_ID = $lastId";
            $timestamp = $conn->query($sql);

            echo $lastId;
        }
    }
    else {
        header('HTTP/1.0 403 Forbidden');
    }

?>