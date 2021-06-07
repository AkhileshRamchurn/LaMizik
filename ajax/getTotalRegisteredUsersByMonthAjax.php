<?php

    session_start();
    require_once '../dbconnect.php';

    $userType=null;
    if (isset($_SESSION['User_Type'])) {
        $userType=$_SESSION['User_Type'];
    }


    if ($userType=="Admin" && $_SERVER["REQUEST_METHOD"] == "POST") {

        $sql = "SELECT COUNT(*), MONTH(Register_Timestamp) FROM user WHERE YEAR(NOW())=YEAR(Register_Timestamp) GROUP BY MONTH(Register_Timestamp)";
        $stmt = $conn->query($sql);
        $rows = $stmt->fetchAll();

        $arrayUsers["Jan"] = 0;
        $arrayUsers["Feb"] = 0;
        $arrayUsers["Mar"] = 0;
        $arrayUsers["Apr"] = 0;
        $arrayUsers["May"] = 0;
        $arrayUsers["Jun"] = 0;
        $arrayUsers["Jul"] = 0;
        $arrayUsers["Aug"] = 0;
        $arrayUsers["Sep"] = 0;
        $arrayUsers["Oct"] = 0;
        $arrayUsers["Nov"] = 0;
        $arrayUsers["Dec"] = 0;

        foreach($rows as $row) {

            switch ($row[1]) {
                case 1:
                {
                    $arrayUsers["Jan"] = $row[0];
                    break;
                }
                case 2:
                {
                    $arrayUsers["Feb"] = $row[0];
                    break;
                }
                case 3:
                {
                    $arrayUsers["Mar"] = $row[0];
                    break;
                }
                case 4:
                {
                    $arrayUsers["Apr"] = $row[0];
                    break;
                }
                case 5:
                {
                    $arrayUsers["May"] = $row[0];
                    break;
                }
                case 6:
                {
                    $arrayUsers["Jun"] = $row[0];
                    break;
                }
                case 7:
                {
                    $arrayUsers["Jul"] = $row[0];
                    break;
                }
                case 8:
                {
                    $arrayUsers["Aug"] = $row[0];
                    break;
                }
                case 9:
                {
                    $arrayUsers["Sep"] = $row[0];
                    break;
                }
                case 10:
                {
                    $arrayUsers["Oct"] = $row[0];
                    break;
                }
                case 11:
                {
                    $arrayUsers["Nov"] = $row[0];
                    break;
                }
                case 12:
                {
                    $arrayUsers["Dec"] = $row[0];
                    break;
                }
            }
            
        
        }
        
        $jsonData = json_encode($arrayUsers, JSON_NUMERIC_CHECK);
        header('Content-Type: application/json'); 
        echo $jsonData; 

    }
    else {
        header('HTTP/1.0 403 Forbidden');
    }

?>