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
            $searchKey = " AND (Username LIKE '%$searchKey%' OR User_ID LIKE '%$searchKey%')";
        }

        if ((empty($start) && $start != 0) || empty($limit) || empty($sortValue)) {
            header('HTTP/1.0 403 Forbidden');
        }
        else {
            require_once '../dbconnect.php';
            $desc = "";
            switch($sortValue) {
                case "nameAsc" :
                {
                    $sortValue = "Username";
                    break;
                }
                case "nameDesc" :
                {
                    $sortValue = "Username";
                    $desc = "DESC";
                    break;
                }
                case "idAsc" :
                {
                    $sortValue = "User_ID";
                    break;
                }
                case "idDesc" :
                {
                    $sortValue = "User_ID";
                    $desc = "DESC";
                    break;
                }
            }

            $sql = "SELECT User_ID, Username
                    FROM user
                    WHERE isBanned=1 $searchKey
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