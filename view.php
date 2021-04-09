<!DOCTYPE html>
<html>
<head>
	<title>view</title>

	<link rel="stylesheet" href="css/styling.css">
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
											<li>Your Profile</li>			
										<ul>	
									</div>	
									<div class="profile-menu-right">
										<ul>
											<li><i class="fas fa-video"></i></i></li>
											<li>Your videos</li>
									</div>
									<div class="profile-menu-right">
										<ul>
											<li><i class="fas fa-cog"></i></li>
										<ul>
											<li>Settings</li>
										<ul>
									</div>

									<div class="profile-menu-right">
										<ul>
											<li><i class="fas fa-sign-out-alt"></i></li>
											<li>Logout</li>
										<ul>
									</div>
								</div>		
							</li>
						</ul>
					</nav>

				</div>	

			</header>
	</div>
	<div class ="middle-container">
		<div class="video-container">
			
			<?php

				 $video_id =$_GET['video_id'] ;
				include 'dbconnect.php';

				$query = "SELECT Title FROM video
							where video_ID =? ";
				$stmt = $conn -> prepare($query); 

				$stmt ->execute([$video_id]);

				$row =$stmt -> fetch();

			?>	
	
			<video controls autoplay>
				<source src="<?php echo 'video/'.$video_id.'.mp4'; ?>" >	
			</video>

	
		</div>	

		<div class="video-content">
			<h3><?php echo $row['Title'] ?></h3>	
		</div>
			
		
	</div>
	




	<!-- script for profile dropdown menu -->
	
	<script type="text/javascript">
		var profile_dropdown = document.querySelector(".profile-dropdown");

		profile_dropdown.addEventListener("click", function(){
			this.classList.toggle("active");
		})
	</script>



</body>
</html>