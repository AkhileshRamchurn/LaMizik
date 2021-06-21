<?php
	session_start();
	require_once 'dbconnect.php';
?>

<!DOCTYPE html>
<html>
<head>

	<title>LaMizik</title>
	
		<link rel="stylesheet" href="css/styling.css" type="text/css">

	<!-- link for owl carousel css -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" integrity="sha512-tS3S5qG0BlhnQROyJXvNjeEM4UpMXHrQfTGmbQ1gKmelCxlSEBUaxhRBj/EFTzpbP4RVSrpEikbmdJobCvhE3g==" crossorigin="anonymous" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" integrity="sha512-sMXtMNL1zRzolHYKEujM2AqCLUR9F2C4/05cdbxjjLSRvMQIciEPCQZo++nk7go3BtSuK9kfa/s+a4f4i5pLkw==" crossorigin="anonymous" />

	<!-- script for search bar -->
	<!-- Used icons from kitawsome -->
	<script src="https://kit.fontawesome.com/260e4ed8bc.js" crossorigin="anonymous"></script>
	
</head>
<body>

	<div class="top-container">
		<div class ="top">
			<header>
				
				<a href="img/logo.png" class="logo">LaMizik</a>


				<div class="search-container">
					<input type="text" placeholder="search" class="search-bar">	
					<a href="">
						<li class="fas fa-search"></li>
					</a>
				</div>	
				

				<div class="navbar">
					<nav>
						<ul>
							<div class ="nav">	
								<li><a href="home.php">Home</a></li>
								<li><a href="about.html">About</a></li>
								<li><a href="contact.html">Contact</a></li>
							</div>
							
							<li class="profile-dropdown"><font size="6"><i class="fas fa-user-circle"></i></font>
								<div class= "profile-menu">
									<div class="profile-menu-left">
										<ul>
											<li><i class="fas fa-user"></i></li>
											<li><a style="padding:0;color:white" href="user_profile.php" >profile</a></li>		
										<ul>	
									</div>	
									<div class="profile-menu-right">
										<ul>
											<li><i class="fas fa-video"></i></i></li>
											<li>Your videos</li>
										<ul>
									</div>
									<div class="profile-menu-right">
										<ul>
											<li><i class="fas fa-cog"></i></li>
											<li>Settings</li>
										<ul>
									</div>
									<div class="profile-menu-right">
										<ul>
											<li><i class="fas fa-sign-out-alt"></i></li>
											
											<li><a style=" padding:0" href="logout.php" >Logout</a></li>
										<ul>
									</div>			
								</div>		
							</li>
						</ul>
					</nav>

				</div>

			

			</header>

			<div class= "top-content">
				<img src="img/logo.png" class="img-logo" alt="Logo cannot be loaded" >
				<h1>Welcome to LaMizik</h1>
				<p class="web-description">Connect yourself with other artists</p>
			</div>
		</div>
	</div>

	<div class="middle-container">
		<div class="middle">
			<h1>Picks of the day</h1>
			<div class="owl-carousel owl-theme">
			    <div class="item">
			    	<a href="view.php?video_id=23" target="_self" >
			    		<img src="video/thumbnail/23t.jpg"  >
			    	</a>	
			    </div>
			    <div class="item">
			    	<a href="view.php?video_id=24" target="_self" >
			    		<img src="video/thumbnail/24t.jpg" >
			    	</a>   		
			    </div>
				<div class="item">
			    	<a href="view.php?video_id=26" target="_self" >
			    		<img src="video/thumbnail/26t.jpg" >
			    	</a>   		
			    </div>
			</div>
			<!--
			<h1>Picks of the day</h1>
			
			<div class="img-wheelcarousel">
				<img src="video/thumbnail/1t.jpg" width="100%">
				<img src="video/thumbnail/2t.jpg" width="100%">
				<img src="video/thumbnail/3t.jpg" width="100%">
				<img src="video/thumbnail/4t.jpg" width="100%">
				<img src="video/thumbnail/5t.jpg" width="100%">

					<script src="js/jquery.min.js"></script>
				<script src="js/dnslide.js"></script>
				<script src="js/expandJS.js"></script>
				<script src="script.js"></script>
			
			</div>
			-->	
		</div>		
	</div>

	
	<div class="bottom-container ">
		
			

		<!--
		<div class="bottom">
			<h1>Most liked videos</h1>
			<div class="owl-carousel owl-theme" >
				<?php


					$query = "SELECT * FROM video
								INNER JOIN Rating
								ON video.Video_ID = rating.Video_ID
					 			where Video_Type ='lesson' AND Status = 'Approved' LIMIT 10";
					$stmt = $conn -> prepare($query); 
					$stmt -> execute(); 

					while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				?>	
						<div class="item" >
				    		<a href="view.php?video_id=<?php echo $row['video_ID'] ?>" target="_self">
				    			<img src="<?php echo 'video/thumbnail/'.$row['Video_ID'].'t.jpg' ?>">
				    			<h3><?php echo $row['Title'] ?></h3>
				    		</a>
						</div>	
				<?php		
					}
				?>	
			</div>		
		</div>

		-->

		<div class="bottom">
			<h1>Tutorial Videos</h1>
			<div class="video-display" >
				<?php

					$query = "SELECT * FROM video where Video_Type ='lesson' AND Status = 'Approved' LIMIT 4";
					$stmt = $conn -> prepare($query);
					$stmt -> execute();  

				
					$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
				?>	
					<div class="img">
						<a href="<?php echo 'view.php?video_id='.$row[0]['Video_ID']; ?>" target="_self">
							<img src="<?php echo 'video/thumbnail/'.$row[0]['Video_ID'].'t.jpg' ?>">
							<h3><?php echo $row[0]['Title'] ?></h3>
						</a>
					</div>	
					<div class="img">
						<a href="<?php echo 'view.php?video_id='.$row[1]['Video_ID']; ?>" target="_self">
							<img src="<?php echo 'video/thumbnail/'.$row[1]['Video_ID'].'t.jpg' ?>">
							<h3><?php echo $row[1]['Title'] ?></h3>
						</a>
					</div>
					<div class="img">
						<a href="<?php echo 'view.php?video_id='.$row[2]['Video_ID']; ?>" target="_self">
							<img src="<?php echo 'video/thumbnail/'.$row[2]['Video_ID'].'t.jpg' ?>">
							<h3><?php echo $row[2]['Title'] ?></h3>
						</a>
					</div>
					<div class="img">
						<a href="<?php echo 'view.php?video_id='.$row[3]['Video_ID']; ?>" target="_self">
							<img src="<?php echo 'video/thumbnail/'.$row[3]['Video_ID'].'t.jpg' ?>">
							<h3><?php echo $row[3]['Title'] ?></h3>
						</a>
					</div>
			</div>		
		</div>

		
		<div class="bottom">
			<h1>Cover Videos</h1>
			<div class="owl-carousel owl-theme" >
				<?php

					$query = "SELECT * FROM video where Video_Type ='performance' AND Status = 'Approved' LIMIT 10";
					$stmt = $conn -> prepare($query);
					$stmt -> execute();

					while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				?>	
						<div class="item" >
				    		<a href="<?php echo 'view.php?video_id='.$row['Video_ID']; ?>" target="_self">
				    			<img src="<?php echo 'video/thumbnail/'.$row['Video_ID'].'t.jpg' ?>">
				    			<h3><?php echo $row['Title'] ?></h3>
				    		</a>
						</div>	
				<?php		
					}
				?>	
			</div>		
		</div>

		

	<!-- script for owl carousel -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js" integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw==" crossorigin="anonymous"></script>

	<script type="text/javascript">
		  
		$(".middle .owl-carousel ").owlCarousel({
				    loop:true,
				    margin:10,
				    nav:true,
				    autoplay: true,
				    autoplayTimeout: 2000,
				   stagePadding:50,
				    responsive:{
				        0:{
				            items:1
				        },
				        600:{
				            items:1
				        },
				        1000:{
				            items:1
				        }
				    }
				})
	</script>

    <script type="text/javascript">
    	$(".bottom .owl-carousel").owlCarousel({
    				
				    loop:false,
				    margin:10,
				    nav:true,
				    stagePadding:50,
				    responsive:{
				        0:{
				            items:1
				        },
				        600:{
				            items:2
				        },
				        1000:{
				            items:4
				        }
				    }

		})		    
    	
    </script>

    
	<!-- script for profile dropdown menu -->
	
	<script type="text/javascript">
		var profile_dropdown = document.querySelector(".profile-dropdown");

		profile_dropdown.addEventListener("click", function(){
			this.classList.toggle("active");
		})
	</script>
</body>
</html>	