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
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<body>

	<?php
		
		if (isset($_SESSION["User_ID"])){

			
			if (isset($_POST["submit"])){

				function test_input($data) {
					$data = trim($data);
					$data = stripslashes($data);
					$data = htmlspecialchars($data);
					return $data;
				}

				$titleErr = '';
				$videoTypeErr = '';
				$videoFileErr = '';

				$title = test_input($_POST["title"]);
				$videoType = test_input($_POST["videotype"]);
				$description = test_input($_POST["description"]);
				
				$userId = test_input($_SESSION["User_ID"]);

				if (empty($_POST['genre_list'])) {
					$genreList = '';
				}
				else {
					$genreList = $_POST['genre_list'];
					for ($x = 0; $x < count($genreList); $x++) {
						$genreList[$x] = test_input($genreList[$x]);
					}
				}
				

				$fileExtension = end(explode(".", test_input($_FILES["video"]["name"])));
				$test = strtolower($fileExtension);


				if (empty($title)){
					$titleErr = 'Video title required';
				}
				else if (strlen($title) > 100){
					$titleErr = 'Maximum number of characters allowed for the title is 100';
					$title = '';
				}

				if (empty($videoType)){
					$videoTypeErr = 'Video category required';
				}
				else if (($videoType != "performance") && ($videoType != "lesson")){
					$videoTypeErr = 'Invalid video category entered!';
					$videoType = '';
				}
				
				if (empty($test)){
					$videoFileErr = 'No file selected. Please select a video file!';
				}
				else if ($test != "ogm" && $test != "wmv" && $test != "mpg" && $test != "webm" && $test != "ogv" && $test != "mov" && $test != "asx" && $test != "mpeg" && $test != "mp4" && $test != "m4v" && $test != "avi"){
					$videoFileErr = 'Invalid file selected. Please select a video file!';
				}


				if (empty($titleErr) && empty($videoTypeErr) && empty($videoFileErr)){

					$sql = "INSERT INTO video (Title, Description, Video_Type, User_ID) VALUES (?, ?, ?, ?)";
					$stmt = $conn -> prepare($sql);

					$stmt ->bindParam(1,$title,PDO::PARAM_STR);
					$stmt ->bindParam(2,$description,PDO::PARAM_STR);
					$stmt ->bindParam(3,$videoType,PDO::PARAM_STR);
					$stmt ->bindParam(4,$userId,PDO::PARAM_INT);
					$stmt ->execute();

					$videoId = $conn->lastInsertId();
					$newVideoName = $videoId.".".$fileExtension;
					$thumbnailName = $videoId."t.jpg";
					$tempName = $_FILES["video"]["tmp_name"];

					move_uploaded_file($tempName, "video/".$newVideoName);

					$command = "ffmpeg -ss 00:00:20 -i video/$newVideoName -vframes 1 -q:v 2 video/thumbnail/$thumbnailName";	//command to create thumbnail using ffmpeg tool
					system($command);

					if(!empty($genreList)) {

						foreach($genreList as $genreId) {

							$sql = "INSERT INTO `video-genre`(Video_ID, Genre_ID) VALUES (?, ?)";
							$stmt = $conn->prepare($sql);
							
							$stmt->bindParam(1,$videoId,PDO::PARAM_INT);
							$stmt->bindParam(2,$genreId,PDO::PARAM_INT);
							$stmt->execute();
							
						}
					}


					echo "success!";
				}
			}
			
			?>
		
			<h1>Upload Video</h1>

			<form method="POST" enctype="multipart/form-data" action="<?php echo $_SERVER["PHP_SELF"];?>">
			
				<div>
					<label>Select video</label>
					<?php if (isset($videoFileErr)){ echo "<p>$videoFileErr</p>"; } ?>
					<input type="file" name="video"  accept="video/*" required>
				</div>
				
				<div>
					<label>Video Title</label>
					<?php if (isset($titleErr)){ echo "<p>$titleErr</p>"; } ?>
					<input type = "text" name = "title" maxlength = "100" <?php if (!empty($title)){echo "value = \"".$title."\"";} ?> required >
				</div>
				
				<div>
					<label>Video Description</label>
					<input type = "text" name = "description" <?php if (!empty($description)){echo "value = \"".$description."\"";} ?>>
				</div>
				
				<div>
					<label>Video Category</label>
					<?php if (isset($videoTypeErr)){ echo "<p>$videoTypeErr</p>"; } ?>
					<input type="radio" name="videotype" value="performance"  <?php if ((!empty($videoType)) && ($videoType == "performance")) {echo "checked";} ?> required >
					<label for="performance">Performance</label>
					<input type="radio" name="videotype" value="lesson"  <?php if ((!empty($videoType)) && ($videoType == "lesson")) {echo "checked";} ?> required >
					<label for="lesson">Lesson</label><br>
				</div>
				
				<div>
					<label>Music Genre</label>
					<?php
						
						$sql = "SELECT * FROM genre;";
						$stmt = $conn->prepare($sql);
						$stmt -> execute();

						$count = 0;
						
						while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

							if ((!empty($genreList[$count])) && ($genreList[$count] == $row["Genre_ID"])) {
								echo '<input type="checkbox" name="genre_list[]" value="'.$row["Genre_ID"].'" checked >';
								$count++;
							}
							else {
								echo '<input type="checkbox" name="genre_list[]" value="'.$row["Genre_ID"].'" >';
								
							}
							echo '<label for="genre_list[]">'.$row["Genre_Name"].'</label>';
						}
					
					?>
				</div>

				<div>
					<button type = "submit" name = "submit">Upload</button>
				</div>
					
			</form>

			<?php
			
		}
		else {
		
			header("location: login.php");
			exit();
		
		}

	?>

</body>
</html>