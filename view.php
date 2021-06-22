<?php
	session_start();
	$user_id = null;
	if (isset($_SESSION["User_ID"])) {
		$user_id = $_SESSION["User_ID"];
	}
	
	$user_type = null;
	if (isset($_SESSION["User_Type"])) {
		$user_type = $_SESSION["User_Type"];
	}
		
	//checks if user already like the post
	function userLiked($video_id, $user_id){				
		
		//Retrieve video from table rating
		include 'dbconnect.php';
		$sql = "SELECT COUNT(*) FROM rating WHERE USER_ID = $user_id
		AND VIDEO_ID = $video_id AND Rate_Action ='Liked'";

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
		AND VIDEO_ID = $video_id AND Rate_Action ='Disliked'";

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
		$sql = "SELECT COUNT(*) FROM rating WHERE VIDEO_ID = $id AND Rate_Action='Liked'";
		$stmt = $conn -> prepare($sql); 
		$stmt ->execute();

		$numOfLikes= $stmt -> fetchColumn();

		return $numOfLikes;

	}

	//Get total number of likes for a particular post
	function getDisLikes($id){

		include 'dbconnect.php';
		$sql = "SELECT COUNT(*) FROM rating WHERE VIDEO_ID = $id AND Rate_Action='Disliked'";
		$stmt = $conn -> prepare($sql); 
		$stmt ->execute();

		$numOfLikes= $stmt -> fetchColumn();
		
		return $numOfLikes;

	}
	
	$video_id = $_GET['video_id'] ;
	include 'dbconnect.php';

	// Retrieve information from table video 
	$query = "SELECT *, UNIX_TIMESTAMP(Upload_Timestamp) AS Unix_TS FROM video WHERE video_ID =? ";
	$stmt = $conn -> prepare($query); 
	$stmt ->execute([$video_id]);
	$row =$stmt -> fetch();

	//checks if the user has permission to view the video
	if (!(($user_type == "Admin" && $row['Status'] != "Permanently Deleted") || (($user_type == null || $user_type == "NormalUser") && $row['Status'] == "Approved"))) {
        header('HTTP/1.1 403 Forbidden'); 
        die();
    }

	if ($user_type == "NormalUser") {
		$sql = "SELECT * FROM views WHERE User_ID=? AND Video_ID=?";
		$stmt = $conn -> prepare($sql); 
		$stmt ->execute([$user_id, $video_id]);
		$viewExist =$stmt -> fetch();
		if (!($viewExist)) {
			$sql = "INSERT INTO views (Viewer_IP, Video_ID, User_ID) VALUES (?,?,?)";
			$stmt = $conn -> prepare($sql); 
			$stmt ->execute([$_SERVER['REMOTE_ADDR'], $video_id, $user_id]);
		}
	}
	else if ($user_type == null) {
		$sql = "SELECT * FROM views WHERE Viewer_IP=? AND Video_ID=? AND User_ID IS NULL";
		$stmt = $conn -> prepare($sql); 
		$stmt ->execute([$_SERVER['REMOTE_ADDR'], $video_id]);
		$viewExist =$stmt -> fetch();
		if (!($viewExist)) {
			$sql = "INSERT INTO views (Viewer_IP, Video_ID) VALUES (?,?)";
			$stmt = $conn -> prepare($sql); 
			$stmt ->execute([$_SERVER['REMOTE_ADDR'], $video_id]);
		}
	}

	$query = "SELECT COUNT(*) FROM views WHERE Video_ID = ?";
	$stmt = $conn -> prepare($query); 
	$stmt ->execute([$video_id]);
	$view =$stmt -> fetch();

	$query = "SELECT Username FROM user WHERE User_ID = ?";
	$stmt = $conn -> prepare($query); 
	$stmt ->execute([$row['User_ID']]);
	$uploader =$stmt -> fetch();

	$query = "SELECT COUNT(*) FROM comment WHERE Video_ID = ?";
	$stmt = $conn -> prepare($query); 
	$stmt ->execute([$video_id]);
	$commentCount =$stmt -> fetch();

?>


<!DOCTYPE html>
<html>
<head>
	<title>View</title>
	<link rel="stylesheet" href="css/view.css" type="text/css">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.5.0/mdb.min.css" rel="stylesheet"/>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.5.0/mdb.min.js"></script>
	<script src="https://kit.fontawesome.com/260e4ed8bc.js" crossorigin="anonymous"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script src="scripts/view_script.js"></script>
	<script src="scripts/view_scriptToDelete.js"></script>
</head>
<?php include 'includes/navbar.php'; ?>
<body>
	<div class="main">
		<div class="left-container">
			<div class ="middle-container">

				<div class="video-container">	
					<!-- DISPLAY VIDEO -->
					<video controls autoplay>
						<source src="<?php echo 'video/'.$video_id.'.mp4'; ?>" >
						<!-- Copy paste src() above to cater for all the supported extensions!!! Refer to upload.php for supported extensions -->
						<!-- or use glob function -- easier!!? -->	
					</video>

			
				</div>	
				<!-- VIDEO NAME -->
				<div class="video-title">
					<h3><?php echo $row['Title'] ?></h3>	
				</div>

				<div class="video-content">
					<div class="video-sub-heading">
						<div class="video-data">
							<p><span class="video-timestamp"><?php echo $row['Unix_TS']; ?></span><span class="midot">&#183;</span><?php echo $view[0]; ?> views</p>
						</div>

						<!-- LIKE AND DISLIKE BUTTON -->
						<div class= "video-rating" >

							<?php

								if ($user_type == "Admin" && $row['Status'] == "Approved") {
									echo 	"<form action='adminRejectVideo2.php' method='POST'>
												<input type='hidden' name='vid_id' value='".$video_id."'>
												<button class='admin-reject-video' type='submit'>Reject</button>
											 </form>";
								}

							?>

							<!-- To access session variable in view_script.js -->
							<input type="hidden" value=<?php echo $user_id?> id="user_id">	
							
							<!-- Checks if user has registered in order to like/dislike -->
							<?php if($user_id != null): ?>
								<div class="like-wrapper">
									<!-- if user liked video, style button differently -->
									<i <?php if(userLiked($video_id, $user_id)): ?>
									class="fa fa-thumbs-up like-btn"

									<?php else: ?>
										class="fa fa-thumbs-o-up like-btn"
									<?php endif ?>	
									data-id="<?php echo $video_id ?>"></i>
							
									<span class="likes"><?php echo getLikes($video_id); ?> </span>
								</div>
								
								<div class="dislike-wrapper">
									<!-- if user disliked video, style button differently -->
									<i <?php if(userDisliked($video_id, $user_id)): ?>
									class="fa fa-thumbs-down dislike-btn"

									<?php else: ?>
										class="fa fa-thumbs-o-down dislike-btn"
									<?php endif ?>	
									data-id="<?php echo $video_id ?>"></i>

									<span class="dislikes"><?php echo getDisLikes($video_id); ?></span>
								</div>
							
							<!-- else just display like/dislike. The user will only be able to view 
							number of likes and dislikes -->
							<?php else: ?>
								<div class="like-wrapper">
									<i	class="fa fa-thumbs-o-up like-btn"></i>
									<span class="likes"><?php echo getLikes($video_id); ?> </span>
								</div>
								
								<div class="dislike-wrapper">
									<i	class="fa fa-thumbs-o-down dislike-btn"></i>
									<span class="dislikes"><?php echo getDisLikes($video_id); ?> </span>
								</div>
							
							<?php endif ?>	

						</div>
					</div>
					<div class="video-description">
						<div class="video-uploader">
							<h2>By <span class="uploader"><a href="user_profile.php?user_id=<?php echo $row['User_ID']; ?>"><?php echo $uploader[0]; ?></a></span></h2>
						</div>
						<div class="full-description">
							<p><?php echo $row['Description']; ?></p>
						</div>
					</div>
				</div>
			</div>

			<!-- Comment System -->
			<div class = "whole-comment-container">
				<div class="enter-comment-container">
					<div class="comment-container-head">
						<h1 class = "comment-heading"><span class="comment-count"><?php echo $commentCount[0]; ?></span> Comments</h1>
						<div class="dropdown">
							<button class="btn btn-dark" type="button" id="dropdownMenuButton" data-mdb-toggle="dropdown" aria-expanded="false"><i class="fas fa-sort dropdown-icon"></i></button>
							<ul id="comment_Sort" class="dropdown-menu" aria-labelledby="dropdownMenuButton">
								<li value="first"><a class="dropdown-item" href="#">Newest</a></li>
								<li value="last"><a class="dropdown-item" href="#">Oldest</a></li>
							</ul>
						</div>
					</div>
					<div class="comment-box">
						<div class = "comment-textbox">
							<textarea id ="commentDesc" name="comment" placeholder="Write a comment"></textarea>
						</div>
						<div class = "comment-button">
							<button type = "button" id = "submit" value>Comment</button>
						</div>
					</div>
				</div>
				<div id="comment-container"></div>
			</div>
		</div>

		<div class="right-container"></div>
	</div>

	<!-- Report Popup -->
	<div class="my-modal-overlay"></div>
	<div class="my-modal">
		<div class="my-modal-header">
			<h2 class="my-modal-title">Report Comment</h2>
			<div class="close-btn"><i class="fas fa-times"></i></div>
		</div>
		<div class="my-modal-body">
			<h3 class="my-modal-subtitle">Report Reason</h3>
			<textarea id="reportReason"></textarea>
		</div>
		<div class="my-modal-footer">
			<button id="report-cancel-btn">Cancel</button>
			<button id="report-submit-btn">Submit</button>
		</div>
	</div>

	<!-- Log-in Popup -->
	<div class="my-modal-overlay2"></div>
	<div class="my-modal2">
		<div class="my-modal-header2">
			<h2 class="my-modal-title2">Cannot Perform Action</h2>
			<div class="close-btn2"><i class="fas fa-times"></i></div>
		</div>
		<div class="my-modal-body2">
			<h3 class="my-modal-subtitle2">Only registered users are allowed to perform this action.</h3>
		</div>
		<div class="my-modal-footer2">
			<button id="login-cancel-btn">Cancel</button>
			<button id="login-submit-btn">Log In</button>
		</div>
	</div>
	

	<!-- Scripts for comment --Passing PHP variables to JS -->
	<script type="text/javascript">
		var userId = <?php echo json_encode($user_id); ?>;
		var videoId = <?php echo json_encode($video_id); ?>;
		var username = <?php if (isset($_SESSION["Username"])) { echo json_encode($_SESSION["Username"]); } else { echo 'null'; } ?>;
		var userType = <?php if (isset($_SESSION["User_Type"])) { echo json_encode($_SESSION["User_Type"]); } else { echo 'null'; } ?>;
	</script>

</body>
</html>


