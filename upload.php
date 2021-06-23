<?php
	session_start();
	require_once 'dbconnect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Upload Video</title>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.css" rel="stylesheet"/>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
	<link rel="stylesheet" href="css/upload_style.css">
	<script src="scripts/upload_video_script.js"></script>
</head>
<?php include 'includes/navbar.php'; ?>
<body>
	<div class="main-container">
		<h1 class="main-title">Upload Video</h1>

		<form id="myform" class="upload-form" method="POST" enctype="multipart/form-data">

			<div class="left-side-form">
				<div class='input-element'>
					<label>Video Title</label>
					<?php if (isset($titleErr)){ echo "<p>$titleErr</p>"; } ?>
					<input class="text-input" type = "text" name = "title" maxlength = "100" <?php if (!empty($title)){echo "value = \"".$title."\"";} ?> required >
				</div>
				
				<div class='input-element'>
					<label>Video Description</label>
					<textarea class="text-input textarea-input" name="description" <?php if (!empty($description)){echo "value = \"".$description."\"";} ?>></textarea>
				</div>

				<div class="genre-container">
					<label>Music Genre</label>

					<div class="dropdown">
						<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Select <span class="caret"></span></button>
						<ul class="dropdown-menu checkbox-menu allow-focus" aria-labelledby="dropdownMenu1">
						
							<?php
								
								$sql = "SELECT * FROM genre;";
								$stmt = $conn->prepare($sql);
								$stmt -> execute();

								$count = 0;
								
								while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

									if ((!empty($genreList[$count])) && ($genreList[$count] == $row["Genre_ID"])) {
										echo '<li><label><input type="checkbox" name="genre_list[]" value="'.$row["Genre_ID"].'" checked >'.$row["Genre_Name"].'</label></li>';
										$count++;
									}
									else {
										echo '<li><label><input type="checkbox" name="genre_list[]" value="'.$row["Genre_ID"].'" >'.$row["Genre_Name"].'</label></li>';	
									}
								}
							
							?>
							
						</ul>
					</div>
				</div>
				
				<div class='input-element radio-input-element'>
					<label>Video Category</label>
					<?php if (isset($videoTypeErr)){ echo "<p>$videoTypeErr</p>"; } ?>
					<div class="radio-option-container">
						<div>
							<input id="lesson" class="radio-btn" type="radio" name="videotype" value="lesson"  <?php if ((!empty($videoType)) && ($videoType == "lesson")) {echo "checked";} ?> required >
							<label class="radio-option" for="lesson">Lesson</label><br>
						</div>
						<div>
							<input id="performance" class="radio-btn" type="radio" name="videotype" value="performance"  <?php if ((!empty($videoType)) && ($videoType == "performance")) {echo "checked";} ?> required >
							<label class="radio-option" for="performance">Performance</label>
						</div>
					</div>
				</div>

				<div>
					<button class="submit-btn" type = "submit" name = "submit">Upload</button>
				</div>
			</div>

			<div class="right-side-form">
				<div class='input-element input-right'>
					<div class="file-upload-box">
						<div class="upload-box-content">
							<i class="fas fa-9x fa-cloud-upload-alt upload-icon"></i>
							<h4 class="upload-box-text">Drag and Drop or Click to select video file</h4>
						</div>
					</div>
					<?php if (isset($videoFileErr)){ echo "<p>$videoFileErr</p>"; } ?>
					<input id="file-upload" class="real-input-box" type="file" name="video"  accept="video/*" required>
				</div>
			</div>
				
		</form>
	</div>
</body>
</html>