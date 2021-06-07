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
        $sortValue = test_input($_POST['sortValue']);

        if ($searchKey != "") {
            $searchKey = " AND (p.Username LIKE '%$searchKey%' OR r.Username LIKE '%$searchKey%' OR Report_Description LIKE '%$searchKey%' OR Comment_Description LIKE '%$searchKey%')";
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
                    $sortValue = "Report_Timestamp";
                    $desc = "DESC";
                    break;
                }
                case "last" :
                {
                    $sortValue = "Report_Timestamp";
                    break;
                }
                case "p_nameAsc" :
                {
                    $sortValue = "p.Username";
                    break;
                }
                case "p_nameDesc" :
                {
                    $sortValue = "p.Username";
                    $desc = "DESC";
                    break;
                }
                case "r_nameAsc" :
                {
                    $sortValue = "r.Username";
                    break;
                }
                case "r_nameDesc" :
                {
                    $sortValue = "r.Username";
                    $desc = "DESC";
                    break;
                }
            }

            $sql = "SELECT Report_ID, Report_Description, report.Comment_ID, Comment_Description, p.Username AS poster, r.Username AS reporter
                    FROM report, comment, user p, user r
                    WHERE report.Comment_ID=comment.Comment_ID AND comment.User_ID=p.User_ID AND report.User_ID=r.User_ID AND Report_Read=0 $searchKey
                    ORDER BY $sortValue
                    $desc
                    LIMIT $start, $limit";

            $stmt = $conn->prepare($sql);
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