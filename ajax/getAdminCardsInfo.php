<?php

    session_start();
    require_once '../dbconnect.php';

    $userType=null;
    if (isset($_SESSION['User_Type'])) {
        $userType=$_SESSION['User_Type'];
    }


    if ($userType=="Admin" && $_SERVER["REQUEST_METHOD"] == "POST") {

        $sql = "SELECT COUNT(*) FROM user WHERE User_Type='NormalUser'";
        $stmt = $conn->query($sql);
        $rows = $stmt->fetchAll();
        $array[0]["Users"] = $rows[0][0];

        $sql = "SELECT COUNT(*) FROM user WHERE User_Type='NormalUser' AND IsBanned=1";
        $stmt = $conn->query($sql);
        $rows = $stmt->fetchAll();
        $array[0]["Banned"] = $rows[0][0];

        $sql = "SELECT COUNT(*) FROM video";
        $stmt = $conn->query($sql);
        $rows = $stmt->fetchAll();
        $array[0]["Videos"] = $rows[0][0];

        $sql = "SELECT COUNT(*) FROM views";
        $stmt = $conn->query($sql);
        $rows = $stmt->fetchAll();
        $array[0]["Views"] = $rows[0][0];

        $sql = "SELECT SUM(Amount) FROM donation WHERE Receiver_ID=0";
        $stmt = $conn->query($sql);
        $rows = $stmt->fetchAll();
        $array[0]["Donation"] = $rows[0][0];

        $sql = "SELECT SUM(Amount) FROM donation WHERE Receiver_ID=0 AND MONTH(NOW())=MONTH(Timestamp_Donated)";
        $stmt = $conn->query($sql);
        $rows = $stmt->fetchAll();
        $array[0]["DonationMonth"] = $rows[0][0];

        $sql = "SELECT (SELECT COUNT(*) FROM report WHERE Report_Read=0)+(SELECT COUNT(*) FROM video WHERE Status='Pending')";
        $stmt = $conn->query($sql);
        $rows = $stmt->fetchAll();
        $array[0]["Requests"] = $rows[0][0];

        $sql = "SELECT (SELECT COUNT(*) FROM report WHERE Report_Read=0 AND DATE(Report_Timestamp)=DATE(NOW()))+(SELECT COUNT(*) FROM video WHERE Status='Pending' AND DATE(Upload_Timestamp)=DATE(NOW()))";
        $stmt = $conn->query($sql);
        $rows = $stmt->fetchAll();
        $array[0]["RequestsToday"] = $rows[0][0];
        
        
        $jsonData = json_encode($array, JSON_NUMERIC_CHECK);
        header('Content-Type: application/json'); 
        echo $jsonData; 

    }
    else {
        header('HTTP/1.0 403 Forbidden');
    }

?>