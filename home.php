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
    <div class="main-container" data-id="<?php echo $user_id ?>"">
        <div class="carousel-container"></div>
        <div class='recommended-container'></div>
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