<?php
    session_start();
	require_once 'dbconnect.php';

    $user_id = null;
	if (isset($_SESSION["User_ID"])) {
		$user_id = $_SESSION["User_ID"];
	}
?>

<!DOCTYPE html>
<html>
<head>

	<title>LaMizik</title>
	<link rel="stylesheet" href="css/nav_styling.css" type="text/css">
	<link rel="stylesheet" href="css/home_styling.css" type="text/css">
    

	<!-- link for owl carousel css -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" integrity="sha512-tS3S5qG0BlhnQROyJXvNjeEM4UpMXHrQfTGmbQ1gKmelCxlSEBUaxhRBj/EFTzpbP4RVSrpEikbmdJobCvhE3g==" crossorigin="anonymous" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" integrity="sha512-sMXtMNL1zRzolHYKEujM2AqCLUR9F2C4/05cdbxjjLSRvMQIciEPCQZo++nk7go3BtSuK9kfa/s+a4f4i5pLkw==" crossorigin="anonymous" />
    
	<!-- Used icons from kitawsome -->
	
	
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    <div class="main-container" data-id="<?php echo $user_id ?>">
        <div class="carousel-container"></div>
        <div class='recommended-container'>
        <?php

            if (isset($_SESSION['User_ID'])) {
                if ($_SESSION['User_Type'] == 'NormalUser') {

                    $url="http://localhost/Lamizik/recommended_videos.php?user_id=".$_SESSION['User_ID'];
                    $video_json= file_get_contents($url);
                    $json_data = json_decode($video_json, true);
                    $recommendedVideos = $json_data[0]["Recommended_Videos"]; 
                    $NumRecommendedVideos = count($recommendedVideos);
                    
                    $last_pos_rec=8;
                    if ($NumRecommendedVideos< 8) {
                        $last_pos_rec = $NumRecommendedVideos;
                    }

                    $display_reVideos="<h2>Recommended for you</h2><div class='recommended-videos'>";
                    for($i=0;$i<$last_pos_rec; $i++){
                        $display_reVideos= $display_reVideos."<div class='video-container' >";
                        $display_reVideos= $display_reVideos."<a href='view.php?video_id=".$recommendedVideos[$i]['Video_ID']."' target='_self'>";  
                        $display_reVideos= $display_reVideos."<img src='video/thumbnail/".$recommendedVideos[$i]['Video_ID']."t.jpg'>";
                        $display_reVideos= $display_reVideos."<div class='video-details'><h4>".$recommendedVideos[$i]['Title']."</h4></a><p>".$recommendedVideos[$i]['Username']."</p><p><span class='timestamp'>".$recommendedVideos[$i]['Upload_Timestamp']."</span><span class='midot3'>&#183;</span>".$recommendedVideos[$i]['views']." views</p></div>";  
                        $display_reVideos= $display_reVideos."</div>"; 
                    }
                    $display_reVideos = $display_reVideos."</div><span class='separator'></span>";

                    echo $display_reVideos;

                }
            }

        ?>
        </div>
        <div class='explore-container'>
            <h2>Explore</h2>
            <div class='explore-videos'></div>
        </div>
        <div class='moreVideosMessage'></div>
    </div>



    <!-- SCRIPTS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="scripts/home_script.js"></script>
    <script src="https://kit.fontawesome.com/260e4ed8bc.js" crossorigin="anonymous"></script>
	<!-- script for owl carousel -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js" integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw==" crossorigin="anonymous"></script>
    

    
</body>
</html>	