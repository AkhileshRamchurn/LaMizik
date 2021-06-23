<?php
	session_start();
	require_once '../dbconnect.php';

	$user_idCheck = null;
	if (isset($_SESSION["User_ID"])) {
		$user_idCheck = $_SESSION["User_ID"];
	}

	$user_typeCheck = null;
	if (isset($_SESSION["User_Type"])) {
		$user_typeCheck = $_SESSION["User_Type"];
	}

    if ($user_idCheck == null || $user_typeCheck != 'NormalUser') {
        header('HTTP/1.1 403 Forbidden'); 
        die();
    }

	//Handling form request

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
		
        $temp_file_name = explode(".", test_input($_FILES["video"]["name"]));
		$fileExtension = end($temp_file_name);
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

			move_uploaded_file($tempName, "../video/".$newVideoName);

			$command = "ffmpeg -ss 00:00:20 -i ../video/$newVideoName -vframes 1 -q:v 2 ../video/thumbnail/$thumbnailName";	//command to create thumbnail using ffmpeg tool
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

			$titleErr = '';
			$videoTypeErr='';
			$videoFileErr='';

			$title = '';
			$videoType = '';
			$description = '';

			echo "success";
		}
		else {
		}

?>