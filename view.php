<?php
	session_start();
	$user_id = null;
	if (isset($_SESSION["User_ID"])) {
		$user_id = $_SESSION["User_ID"];
	}
	
	if(isset($_SESSION["User_ID"])){
		 
		
		//checks if user already like the post
		function userLiked($video_id, $user_id){				
			
			//Retrieve video from table rating
			include 'dbconnect.php';
			$sql = "SELECT COUNT(*) FROM rating WHERE USER_ID = $user_id
			AND VIDEO_ID = $video_id AND Rate_Action ='like'";

			$stmt = $conn -> prepare($sql); 
			$stmt ->execute();
			$already_liked =$stmt -> fetchColumn();

			if($already_liked > 0){
				return true;
			}
			else{
				return false;
			}
		}

		//checks if user already dislike the post
		function userDisLiked($video_id, $user_id){				
			
			//Retrieve video from table rating
			include 'dbconnect.php';
			$sql = "SELECT COUNT(*) FROM rating WHERE USER_ID = $user_id
			AND VIDEO_ID = $video_id AND Rate_Action ='dislike'";

			$stmt = $conn -> prepare($sql); 
			$stmt ->execute();
			$already_liked =$stmt -> fetchColumn();

			if($already_liked > 0){
				return true;
			}
			else{
				return false;
			}
		}

		//Get total number of likes for a particular post
		function getLikes($id){

			include 'dbconnect.php';
			$sql = "SELECT COUNT(*) FROM rating WHERE VIDEO_ID = $id AND Rate_Action='like'";
			$stmt = $conn -> prepare($sql); 
			$stmt ->execute();

			$numOfLikes= $stmt -> fetchColumn();

			return $numOfLikes;

		}

		//Get total number of likes for a particular post
		function getDisLikes($id){

			include 'dbconnect.php';
			$sql = "SELECT COUNT(*) FROM rating WHERE VIDEO_ID = $id AND Rate_Action='dislike'";
			$stmt = $conn -> prepare($sql); 
			$stmt ->execute();

			$numOfLikes= $stmt -> fetchColumn();
			
			return $numOfLikes;

		}

		//request from ajax
		//if user clicks like or dislike button
		if(isset($_POST['action'])){
			//alert('receiving till here');
			
			include 'dbconnect.php';
			$video_id = $_POST['video_id'];
			$action = $_POST['action'];

			try{
				$conn -> beginTransaction();

				switch($action){
					case 'like':
						$sql = "INSERT INTO rating (User_ID, Video_ID, Rate_Action)
								VALUES($user_id, $video_id, 'like')
								ON DUPLICATE KEY UPDATE Rate_Action ='like'";
							
						break;
					
					case 'dislike':
						$sql = "INSERT INTO rating (User_ID, Video_ID, Rate_Action)
							VALUES($user_id, $video_id, 'dislike')
							ON DUPLICATE KEY UPDATE Rate_Action ='dislike'";
						
						break;
					case 'unlike':
						$sql ="DELETE FROM rating WHERE User_ID=$user_id AND Video_ID = $video_id";					
						break;
					case 'undislike':
						$sql ="DELETE FROM rating WHERE User_ID=$user_id AND Video_ID = $video_id";					
						break;	
					default:
						break;	
	
				}
	
				//execute query to effect changes in the database
				 $stmt = $conn -> prepare($sql); 
				 $stmt ->execute();

				 $conn -> commit();

			}catch(PDOException $exception){
				$conn->rollBack();
				echo 'ERROR: '.$exception->getMessage();
			}
			
			
			 //Get total number of likes and dislikes for a particular video
			/*Calculate likes and dislke....Then return back in JSON format */
			 $rating = array();

			 $likes_query = "SELECT COUNT(*) FROM rating WHERE Video_ID = $video_id AND Rate_Action='like'";
			 $dislikes_query = "SELECT COUNT(*) FROM rating WHERE Video_ID = $video_id AND Rate_Action='dislike'";

			 $likes_rs = $conn -> prepare($likes_query); 
			 $dislikes_rs = $conn -> prepare($dislikes_query); 

			 $likes_rs ->execute();
			 $dislikes_rs ->execute();

			$likes = $likes_rs->fetchColumn();
			$dislikes = $dislikes_rs->fetchColumn();

			$rating = [
		 	'likes' => $likes,
			'dislikes' => $dislikes
			];

			
		

			echo json_encode($rating);
			exit(0);

		}
		
	}

	
?>


<!DOCTYPE html>
<html>
<head>
	<title>view</title>

	<link rel="stylesheet" href="css/styling.css" type="text/css">
	<link rel="stylesheet" href="css/view.css" type="text/css">
	<link rel="stylesheet" href="css/comment_style.css">
	<script src="https://kit.fontawesome.com/260e4ed8bc.js" crossorigin="anonymous"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<!-- <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script> -->
	<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ajaxy/1.6.1/scripts/jquery.ajaxy.min.js" integrity="sha512-bztGAvCE/3+a1Oh0gUro7BHukf6v7zpzrAb3ReWAVrt+bVNNphcl2tDTKCBr5zk7iEDmQ2Bv401fX3jeVXGIcA==" crossorigin="anonymous"></script> -->
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
				
				// Retrieve information from table video 
				$query = "SELECT Title FROM video
							where video_ID =? ";
				$stmt = $conn -> prepare($query); 

				$stmt ->execute([$video_id]);

				$row =$stmt -> fetch();

			?>	
			<!-- DISPLAY VIDEO -->
			<!-- should only display video if it is approved, else video not found -->
			<!-- should allow admin to remove the video -->
			<!-- should allow users to report the video -->
			<!-- should allow users to donate money to the uploader -- Could be added to view profile page too if we decide make one! -->
			<video controls autoplay>
				<source src="<?php echo 'video/'.$video_id.'.mp4'; ?>" >
				<!-- Copy paste src() above to cater for all the supported extensions!!! Refer to upload.php for supported extensions -->
				<!-- or use glob function -- easier!!? -->	
			</video>

	
		</div>	
		<!-- VIDEO NAME -->
		<div class="video-content">
			<h3><?php echo $row['Title'] ?></h3>	
		</div>

		<!-- LIKE AND DISLIKE BUTTON -->
		<div class= "video-rating" >

			<!-- if user likes post, style button differently -->
			<i <?php if(userLiked($video_id, $user_id)): ?>
				 class="fa fa-thumbs-up like-btn"

				<?php else: ?>
					class="fa fa-thumbs-o-up like-btn"
				<?php endif ?>	
				data-id="<?php echo $video_id ?>"></i>

			<span class="likes"><?php echo getLikes($video_id); ?> </span>
			
			&nbsp;&nbsp;&nbsp;&nbsp;			
			<!-- if user dislikes post, style button differently -->
			<i <?php if(userDisliked($video_id, $user_id)): ?>
				 class="fa fa-thumbs-down dislike-btn"

				<?php else: ?>
					class="fa fa-thumbs-o-down dislike-btn"
				<?php endif ?>	
				data-id="<?php echo $video_id ?>"></i>

			<span class="dislikes"><?php echo getDisLikes($video_id); ?> </span>
		</div>

	</div>

	<!-- Comment System -->
	<!-- should only display comments if video is approved, else video not found -->
	<!-- Should allow admin to remove comments -->
	<!-- Ajax scripts could be reused -->

	<div class = "whole-comment-container">
		<div class="enter-comment-container">
			<h1 class = "comment-heading">Comment</h1>
			<div class="comment-sort">
				<p>Sort By</p>
				<select name="commentSort" id="comment_Sort">
					<option value="first">Newest</option>
					<option value="last">Oldest</option>
				</select>
			</div>
			<div class = "comment-textbox">
				<input type="text" id ="commentDesc" name="comment" placeholder="Write a comment" >
			</div>
			<div class = "comment-button">
				<input type = "button" id = "submit" value = "Comment">
			</div>
		</div>
		<div id="comment-container"></div>
	</div>

	<script type="text/javascript">		//script for inserting comments
		$(document).ready(function() {
			$('#submit').click(function(e) {
				e.preventDefault();
				var userId = <?php echo json_encode($user_id); ?>;
				var comment = $('#commentDesc').val();
				console.log(comment.trim());
				if (userId == null) {
					alert("Must be logged in to post comments you fool!!");	//to change later you fool!!!!
				}
				else if (comment.trim() != "") {
					var videoId = <?php echo json_encode($video_id); ?>;
					$.ajax({
						url: "insert_comment.php",
						type: "POST",
						data: {
							videoId: videoId,
							userId: userId,
							comment: comment,
						},
						cache:false,
						success: function(response){
							if (response == "unexpected_error") {
								window.location = "home.php?error=unexpected_error";
							}
							else {
								$("#comment-container").prepend(response);
								$('#commentDesc').val("");
							}
						}
					});
				}
				else {
					alert('Empty comment you fool!!');	//hmmmmmmmmmmmm
				}
			});

		});
	</script>

	<script type="text/javascript">	//script for fetching comments

		var videoId = <?php echo json_encode($video_id); ?>;
		var sortValue = $('#comment_Sort').val();

		function fetchInitialComment() {
			$.ajax({
				url: "fetch_comment.php",
				type: "POST",
				data: {
					limit: 15,
					start: 0,
					videoId: videoId,
					sortValue: sortValue
				},
				cache:false,
				success:function(data) {
					$("#comment-container").html(data);
				}
			});
		}

		fetchInitialComment();
		
		$(document).ready(function() {
			
			var limit = 15;
			var start = 15;
			
			var timeout;
			$(window).scroll(function(){
				clearTimeout(timeout);
				timeout = setTimeout(function() {
					if ($(window).scrollTop() >= $('#comment-container').offset().top + $('#comment-container').outerHeight() - window.innerHeight) {
						load_comment();
					}
				}, 50);
			});

			function load_comment() {
				$.ajax({
					url: "fetch_comment.php",
					type: "POST",
					data: {
						limit: limit,
						start: start,
						videoId: videoId,
						sortValue: sortValue
					},
					cache:false,
					success:function(data) {
						if (data != '') {
							$("#comment-container").append(data);
							start += limit;
						}
					}
				});
			}

			$('#comment_Sort').change(function() {
				start = 15;
				sortValue = $('#comment_Sort').val();
				fetchInitialComment();
			});
		
		});
		
	</script>

	<script src="scripts/view.js"></script>
	<!-- script for profile dropdown menu -->
	
	<script type="text/javascript">
		var profile_dropdown = document.querySelector(".profile-dropdown");

		profile_dropdown.addEventListener("click", function(){
			this.classList.toggle("active");
		})
	</script>


</body>
</html>


