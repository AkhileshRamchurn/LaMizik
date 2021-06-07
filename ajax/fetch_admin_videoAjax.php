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

        $start = test_input($_POST['start']);
        $limit = test_input($_POST['limit']);
        $searchKey = test_input($_POST['searchKey']);
        $videoType = test_input($_POST['videoType']);
        $videoStatus = test_input($_POST['videoStatus']);
        $sortValue = test_input($_POST['sortValue']);

        if ($searchKey != "") {
            $searchKey = " AND (Title LIKE '%$searchKey%' OR Username LIKE '%$searchKey%' OR Description LIKE '%$searchKey%')";
        }

        if ((empty($start) && $start != 0) || empty($limit) || empty($videoType) || empty($videoStatus) || empty($sortValue)) {
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
                case "titleAsc" :
                {
                    $sortValue = "Title";
                    break;
                }
                case "titleDesc" :
                {
                    $sortValue = "Title";
                    $desc = "DESC";
                    break;
                }
            }

            if ($videoType == "all") {

                $sql = "SELECT Video_ID, Title, Description, Status, Username FROM video, user
                        WHERE Status = ? AND video.User_ID=user.User_ID $searchKey
                        ORDER BY $sortValue
                        $desc
                        LIMIT $start, $limit";

                $stmt = $conn->prepare($sql);
                $stmt->bindParam(1, $videoStatus,PDO::PARAM_STR);

            }
            else {

                $sql = "SELECT Video_ID, Title, Description, Status, Username FROM video, user
                        WHERE Status = ? AND Video_Type = ? AND video.User_ID=user.User_ID $searchKey
                        ORDER BY $sortValue
                        $desc
                        LIMIT $start, $limit";

                $stmt = $conn->prepare($sql);
                $stmt->bindParam(1, $videoStatus, PDO::PARAM_STR);
                $stmt->bindParam(2, $videoType, PDO::PARAM_STR);

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