<?php
    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $videoId = test_input($_POST['videoId']);
        $start = test_input($_POST['start']);
        $limit = test_input($_POST['limit']);
        $sortValue = test_input($_POST['sortValue']);
        
        if (empty($videoId) || (empty($start) && $start != 0) || empty($limit) || empty($sortValue)) {
            header('HTTP/1.0 403 Forbidden');
        }
        else {
            require '../dbconnect.php';

            if ($sortValue == "first") {
                $sortValue = "DESC";
            }
            else {
                $sortValue = "";
            }
            $sql = "SELECT Comment_ID, comment.User_ID, Username, Comment_Description, UNIX_TIMESTAMP(Comment_Timestamp) AS Comment_Timestamp
                    FROM comment, user 
                    WHERE Video_ID = ? AND user.User_ID = comment.User_ID 
                    ORDER BY Comment_Timestamp $sortValue
                    LIMIT ?, ?";

            $result = $conn->prepare($sql);
            $result->bindParam(1, $videoId,PDO::PARAM_INT);
            $result->bindParam(2, $start,PDO::PARAM_INT);
            $result->bindParam(3, $limit,PDO::PARAM_INT);
            $result->execute();

            $comment_array = $result->fetchAll(PDO::FETCH_ASSOC);
            $jsonData = json_encode($comment_array, JSON_NUMERIC_CHECK);
   	
            header('Content-Type: application/json'); 
            echo $jsonData; 
        }
    }
    else {
        header('HTTP/1.0 403 Forbidden');
    }

?>