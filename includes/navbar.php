<?php

    $user_type_nav = null;
    if (isset($_SESSION['User_Type'])) {
        $user_type_nav = $_SESSION['User_Type'];
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nav Bar</title>
    <link rel="stylesheet" href="css/nav_styling.css" type="text/css">
    <script src="https://kit.fontawesome.com/260e4ed8bc.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="scripts/navbar_script.js"></script>
</head>
<header>
    <div class="navbar">
        <div class="pagetitle">
            <h2 class="headingtitlename">LaMizik</h2>
        </div>
        <?php
            if (!preg_match("/search.php/", $_SERVER['PHP_SELF'])) {
                ?>
                <div class="search-container">
                    <form action="search.php" method="GET">
                        <input type="text" name="searchkey" placeholder="Search" class="search-bar">	
                        <button type="submit"><li class="fas fa-search search-icon"></li></button>
                    </form>
                </div>
                <?php
            }
        ?>
        <div class="navlinks">
            <ul>
                <li><a  href="home.php">Home</a></li>
                <li><a href="about.html">About</a></li>
                <li><a href="FAQ.php">FAQ</a></li>
                        
                <li class="profile-dropdown"><i class="fas fa-user-circle user-icon nav-icon-white"></i>
                    <div class= "profile-menu">
                        <div class="profile-menu-left">
                            <ul>
                                <li><i class="fas fa-user nav-icon-white"></i></li>
                                <li>Profile</li>			
                            <ul>	
                        </div>

                        <?php
                            if (empty($user_type_nav)) {
                                ?>
                                    
                                <div class="profile-menu-right">
                                    <ul>
                                        <li><i class="fas fa-sign-out-alt"></i></i></li>
                                        <li><a href="login.php">Log In</a></li>
                                </div>

                                <?php
                            }
                        ?>
                        
                        
                        <?php
                            if ($user_type_nav == "NormalUser") {
                                ?>

                                <div class="profile-menu-right">
                                    <ul>
                                        <li><i class="fas fa-video"></i></i></li>
                                        <li><a href="user_videos.php">My videos</a></li>
                                </div>
                                <div class="profile-menu-right">
                                    <ul>
                                        <li><i class="fas fa-cog"></i></li>
                                    <ul>
                                        <li><a href="user_profile.php">Settings</a></li>
                                    <ul>
                                </div>

                                <?php
                            }
                            else if ($user_type_nav == "Admin"){
                                ?>

                                <div class="profile-menu-right">
                                    <ul>
                                        <li><i class="fas fa-chart-line"></i></li>
                                    <ul>
                                        <li><a href="adminAnalytics.php">Admin Dashboard</a></li>
                                    <ul>
                                </div>

                                <?php
                            }
                        ?>

                        <?php
                            if (isset($user_type_nav)) {
                                ?>
                                    
                                <div class="profile-menu-right">
                                    <ul>
                                        <li><i class="fas fa-sign-out-alt"></i></li>
                                        <li><a href="logout.php">Log Out</a></li>
                                    <ul>
                                </div>

                                <?php
                            }
                        ?>
                    </div>		
                </li>
            </ul>
        </div>
    </div>
</header>
</html>