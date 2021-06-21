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
        
        if (empty($videoId) || (empty($start) && $start != 0) || empty($limit)) {
            header('HTTP/1.0 403 Forbidden');
        }
        else {
            require '../dbconnect.php';

            $sql1 ="SELECT video.Video_ID, Title, Description, UNIX_TIMESTAMP(Upload_Timestamp) AS Upload_Timestamp, video.User_ID AS Uploader_ID, Username AS Uploader
                    FROM video, user
                    WHERE video.Video_ID != ? AND user.User_ID = video.User_ID AND Status='Approved'
                    LIMIT ?, ?";

            $sql2 ="SELECT Video_ID, COUNT(*) AS Views
                    FROM views
                    GROUP BY Video_ID";

            $sql = "SELECT a.Video_ID, Title, Description, Upload_Timestamp, Uploader_ID, Uploader, Views
                    FROM ($sql1) a LEFT JOIN ($sql2) b
                    ON a.Video_ID=b.Video_ID";

            $result = $conn->prepare($sql);
            $result->bindParam(1, $videoId,PDO::PARAM_INT);
            $result->bindParam(2, $start,PDO::PARAM_INT);
            $result->bindParam(3, $limit,PDO::PARAM_INT);
            $result->execute();

            $video_array = $result->fetchAll(PDO::FETCH_ASSOC);
            $jsonData = json_encode($video_array, JSON_PRETTY_PRINT);
   	
            header('Content-Type: application/json'); 
            echo $jsonData; 
        }
    }
    else {
        header('HTTP/1.0 403 Forbidden');
    }

?>