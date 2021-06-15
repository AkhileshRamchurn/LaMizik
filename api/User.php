<?php
require_once("dbcontroller.php");
/* 
A domain Class to demonstrate RESTful web services
*/
Class User {
	private $users = array();

	public function getAllUser(){
		if(isset($_GET['user_id'])){
			$user_id = $_GET['user_id'];
			$query = "SELECT User_ID, Username, First_Name, Last_Name, Email, Linked_Card, Register_Timestamp, IsBanned FROM user WHERE User_Type='NormalUser' AND User_ID=$user_id";
		} else {
			$query = "SELECT User_ID, Username, First_Name, Last_Name, Email, Linked_Card, Register_Timestamp, IsBanned FROM user WHERE User_Type='NormalUser'";
		}
		$dbcontroller = new DBController();
		$this->users = $dbcontroller->executeSelectQuery($query);
		return $this->users;
	}

	public function getAllViewPerUser(){
		if(isset($_GET['user_id'])){
			$user_id = $_GET['user_id'];
			$query = "SELECT User_ID, Username, First_Name, Last_Name, Email FROM user WHERE User_Type='NormalUser' AND User_ID=$user_id";
		} else {
			$query = "SELECT User_ID, Username, First_Name, Last_Name, Email FROM user WHERE User_Type='NormalUser'";
		}
		$dbcontroller = new DBController();
		$this->users = $dbcontroller->executeSelectQuery($query);

		for($i=0; $i<count($this->users); $i++)
		{
			$user_id = $this->users[$i]['User_ID'];
			$innerquery = "SELECT a.Video_ID, Title, View_Timestamp, Rate_Action FROM (SELECT views.Video_ID, Title, View_Timestamp FROM views, video WHERE views.User_ID=$user_id AND video.Video_ID=views.Video_ID) a LEFT JOIN rating ON rating.User_ID=$user_id AND rating.Video_ID=a.Video_ID";
			$this->users[$i]['Views'] = $dbcontroller->executeSelectQuery($innerquery);
		}
		return $this->users;
	}

	public function addUser(){
		if(isset($_POST['Username']) && isset($_POST['First_Name']) && isset($_POST['Last_Name'])  && isset($_POST['Email']) && isset($_POST['Password'])){
			$username = $_POST['Username'];
			$fname = $_POST['First_Name'];
			$lname = $_POST['Last_Name'];
			$email = $_POST['Email'];
			$pwd = $_POST['Password'];
			$hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
			
			$query = "INSERT INTO user(Username,First_name,Last_name,Email,Password) VALUES(?,?,?,?,?)";
			$data = [$username, $fname , $lname, $email , $hashedPwd];
			$dbcontroller = new DBController();
			$result = $dbcontroller->executeQuery($query, $data );
			if($result != 0){
				$result = array('success'=>1);
				return $result;
			}
		}
	}
	
	public function deleteUser(){
		if(isset($_GET['user_id'])){
			$user_id = $_GET['user_id'];
			$query = 'DELETE FROM user WHERE User_ID = ?';
			$data = [$user_id];
			$dbcontroller = new DBController();
			$result = $dbcontroller->executeQuery($query, $data);
			if($result != 0){
				$result = array('success'=>1);
				return $result;
			}
		}
	}
	
	public function editUser(){
		if(isset($_GET['user_id'])){
			$user_id = $_GET['user_id'];
			$username = $_POST['Username'];
			$fname = $_POST['First_Name'];
			$lname = $_POST['Last_Name'];
			$email = $_POST['Email'];
			$pwd = $_POST['Password'];
			$hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
			$query = "UPDATE user SET Username = ?, First_Name = ? , Last_Name = ? , Email = ? , Password = ? WHERE User_ID = ? ";
			$data = [$username, $fname , $lname, $email , $hashedPwd, $user_id];
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