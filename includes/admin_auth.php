<?php
    session_start();

    $userType=null;
    if (isset($_SESSION['User_Type'])) {
        $userType=$_SESSION['User_Type'];
    }

    if ($userType != "Admin") {
        header('HTTP/1.1 403 Forbidden'); 
        die();
    }
?>