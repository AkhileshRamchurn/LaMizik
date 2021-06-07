<?php

    session_start();
    require_once '../dbconnect.php';

    $userType=null;
    if (isset($_SESSION['User_Type'])) {
        $userType=$_SESSION['User_Type'];
    }

    if ($userType=="Admin" && $_SERVER["REQUEST_METHOD"] == "POST") {

        $sql = "SELECT COUNT(*), MONTH(View_Timestamp) FROM views WHERE YEAR(NOW())=YEAR(View_Timestamp) GROUP BY MONTH(View_Timestamp)";
        $stmt = $conn->query($sql);
        $rows = $stmt->fetchAll();

        $arrayViews["Jan"] = 0;
        $arrayViews["Feb"] = 0;
        $arrayViews["Mar"] = 0;
        $arrayViews["Apr"] = 0;
        $arrayViews["May"] = 0;
        $arrayViews["Jun"] = 0;
        $arrayViews["Jul"] = 0;
        $arrayViews["Aug"] = 0;
        $arrayViews["Sep"] = 0;
        $arrayViews["Oct"] = 0;
        $arrayViews["Nov"] = 0;
        $arrayViews["Dec"] = 0;

        foreach($rows as $row) {

            switch ($row[1]) {
                case 1:
                {
                    $arrayViews["Jan"] = $row[0];
                    break;
                }
                case 2:
                {
                    $arrayViews["Feb"] = $row[0];
                    break;
                }
                case 3:
                {
                    $arrayViews["Mar"] = $row[0];
                    break;
                }
                case 4:
                {
                    $arrayViews["Apr"] = $row[0];
                    break;
                }
                case 5:
                {
                    $arrayViews["May"] = $row[0];
                    break;
                }
                case 6:
                {
                    $arrayViews["Jun"] = $row[0];
                    break;
                }
                case 7:
                {
                    $arrayViews["Jul"] = $row[0];
                    break;
                }
                case 8:
                {
                    $arrayViews["Aug"] = $row[0];
                    break;
                }
                case 9:
                {
                    $arrayViews["Sep"] = $row[0];
                    break;
                }
                case 10:
                {
                    $arrayViews["Oct"] = $row[0];
                    break;
                }
                case 11:
                {
                    $arrayViews["Nov"] = $row[0];
                    break;
                }
                case 12:
                {
                    $arrayViews["Dec"] = $row[0];
                    break;
                }
            }
            
        
        }
        
        $jsonData = json_encode($arrayViews, JSON_NUMERIC_CHECK);
        header('Content-Type: application/json'); 
        echo $jsonData;

    }
    else {
        header('HTTP/1.0 403 Forbidden');
    }

?>