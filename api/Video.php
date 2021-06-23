<?php
require_once("dbcontroller.php");

Class Video{
    private $video = array();

    public function getAllVideo(){
		if(isset($_GET['video_id'])){
			$video_id = $_GET['video_id'];
			$query = "SELECT Video_ID, Title, Description, Video_Type, Status, Upload_Timestamp, User_ID FROM video WHERE Video_ID = $video_id";
		} else {
			$query = "SELECT Video_ID, Title, Description, Video_Type, Status, Upload_Timestamp, User_ID FROM video";
		}
		$dbcontroller = new DBController();
		$this->users = $dbcontroller->executeSelectQuery($query);
		return $this->users;
	}

    public function addVideo(){
		if(isset($_POST['Title']) && isset($_POST['Description']) && isset($_POST['Video_Type'])  && isset($_POST['User_ID'])){
			$title = $_POST['Title'];
			$description = $_POST['Description'];
			$video_type = $_POST['Video_Type'];
			$user_id = $_POST['User_ID'];
			
			
			$query = "INSERT INTO user(Title,Description,Video_Type,User_ID) VALUES(?,?,?,?)";
			$data = [$title, $description , $video_type, $user_id];
			$dbcontroller = new DBController();
			$result = $dbcontroller->executeQuery($query, $data );
			if($result != 0){
				$result = array('success'=>1);
				return $result;
			}
		}
	}

    public function deleteVideo(){
		if(isset($_GET['video_id'])){
			$video_id = $_GET['video_id'];
			$query = 'DELETE FROM video WHERE Video_ID = ?';
			$data = [$video_id];
			$dbcontroller = new DBController();
			$result = $dbcontroller->executeQuery($query, $data);
			if($result != 0){
				$result = array('success'=>1);
				return $result;
			}
		}
	}

    public function editVideo(){
		if(isset($_GET['video_id'])){
			$video_id = $_GET['video_id'];
            $title = $_POST['Title'];
			$description = $_POST['Description'];
			$video_type = $_POST['Video_Type'];
			$user_id = $_POST['User_ID'];

			$query = "UPDATE video SET Title = ?, Description = ? , Video_Type = ? , User_ID = ?  WHERE Video_ID = ? ";
			$data = [$title, $description, $video_type,$user_id ,$video_id];
			$dbcontroller = new DBController();
			$result= $dbcontroller->executeQuery($query, $data);
			if($result != 0){
				$result = array('success'=>1);
				return $result;
			}
		}
		
	}
}

?>