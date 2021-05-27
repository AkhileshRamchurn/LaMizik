<?php
    session_start();

    if (($_SERVER['REQUEST_METHOD'] == 'POST') && (isset($_SESSION["Username"]))) {
        
        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $username = $_SESSION["Username"];
        $videoId = test_input($_POST['videoId']);
        $userId = test_input($_POST['userId']);
        $comment = test_input($_POST['comment']);
        

        if (empty($videoId) || empty($userId) || empty($comment)) {
            echo "unexpected_error";
        }
        else {
            require 'dbconnect.php';

            $sql = "INSERT INTO comment(Comment_Description, User_ID, Video_ID) VALUES (?, ?, ?)";
            $stmt = $conn -> prepare($sql);

            $stmt ->bindParam(1,$comment,PDO::PARAM_STR);
            $stmt ->bindParam(2,$userId,PDO::PARAM_INT);
            $stmt ->bindParam(3,$videoId,PDO::PARAM_INT);
            $stmt ->execute();

            $lastId = $conn->lastInsertId();
            $sql = "SELECT Comment_Timestamp FROM comment WHERE Comment_ID = $lastId";
            $timestamp = $conn->query($sql);

            echo    "<div class='main-comment'>
                        <div class='comment-header'>
                            <p class = 'username'>".$username."</p>
                            <p class = 'time'>Just now</p>
                        </div>
                        <div class='comment-description'>
                            <p>".$comment."</p>
                        </div>
                    </div>"
                ;
        }
    }
    else {
        header("Location: home.php?error=unexpected_error");    //Add this error handling to home page
    }

?>