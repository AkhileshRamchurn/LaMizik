<?php

    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $start = test_input($_POST['start']);
        $limit = test_input($_POST['limit']);
        $searchKey = test_input($_POST['searchKey']);
        $sortValue = test_input($_POST['sortValue']);
        $filterValue = test_input($_POST['filterValue']);

        if ($searchKey != "") {
            $searchKey = " AND (Title LIKE '%$searchKey%' OR Username LIKE '%$searchKey%' OR Description LIKE '%$searchKey%')";
        }

        if ((empty($start) && $start != 0) || empty($limit) || empty($sortValue)) {
            header('HTTP/1.0 403 Forbidden');
        }
        else {
            require_once '../dbconnect.php';
            $desc = "";
            switch($sortValue) {
                case "first" :
                {   
                    $sortValue = "Upload_Timestamp";
                    $desc = "DESC";
                    break;
                }
                case "last" :
                {
                    $sortValue = "Upload_Timestamp";
                    break;
                }
                case "asc" :
                {
                    $sortValue = "Title";
                    break;
                }
                case "desc" :
                {
                    $sortValue = "Title";
                    $desc = "DESC";
                    break;
                }
            }

            if ($filterValue == "all") {

                $sql = "SELECT a.Video_ID, Title, Upload_TS, Description, Status, Username, Views FROM
                    (SELECT Video_ID, Title, UNIX_TIMESTAMP(Upload_Timestamp) AS Upload_TS, Description, Status, Username
                    FROM video, user
                    WHERE Status = 'Approved' AND video.User_ID=user.User_ID $searchKey
                    ORDER BY $sortValue
                    $desc
                    LIMIT $start, $limit) a
                    LEFT JOIN
                    (SELECT Video_ID, COUNT(*) AS Views FROM views GROUP BY Video_ID) b
                    ON a.Video_ID=b.Video_ID";

                $stmt = $conn->prepare($sql);

            }
            else {

                $sql = "SELECT a.Video_ID, Title, Upload_TS, Description, Status, Username, Views FROM
                    (SELECT Video_ID, Title, UNIX_TIMESTAMP(Upload_Timestamp) AS Upload_TS, Description, Status, Username
                    FROM video, user
                    WHERE Status = 'Approved' AND Video_Type = ? AND video.User_ID=user.User_ID $searchKey
                    ORDER BY $sortValue
                    $desc
                    LIMIT $start, $limit) a
                    LEFT JOIN
                    (SELECT Video_ID, COUNT(*) AS Views FROM views GROUP BY Video_ID) b
                    ON a.Video_ID=b.Video_ID";

                $stmt = $conn->prepare($sql);
                $stmt->bindParam(1, $filterValue, PDO::PARAM_STR);

            }
            
            $stmt->execute();
            $video_array = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $jsonData = json_encode($video_array, JSON_NUMERIC_CHECK);
   	
            header('Content-Type: application/json'); 
            echo $jsonData; 

        }
    }
    else {
        header('HTTP/1.0 403 Forbidden');
    }
?>