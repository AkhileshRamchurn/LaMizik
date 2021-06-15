<?php
require_once("SimpleRest.php");
require_once("User.php");
		
class UserRestHandler extends SimpleRest {

	function getAllUsers() {	

		$user = new User();
		$rawData = $user->getAllUser();

		if(empty($rawData)) {
			$statusCode = 404;
			$rawData = array('success' => 0);		
		} else {
			$statusCode = 200;
		}

		//var_dump($rawData);
		
		$requestContentType = isset($_SERVER['HTTP_ACCEPT']) ? $_SERVER['HTTP_ACCEPT'] : null;
		
		$this->setHttpHeaders($requestContentType, $statusCode);
		
		$result["output"] = $rawData;
				
		if(strpos($requestContentType,'json') !== false){
		
			//echo "sss";
			$response = $this->encodeJson($result);
			echo $response;
		}
		
	}

	function getAllViewsPerUser() {	

		$user = new User();
		$rawData = $user->getAllViewPerUser();

		if(empty($rawData)) {
			$statusCode = 404;
			$rawData = array('success' => 0);		
		} else {
			$statusCode = 200;
		}

		//var_dump($rawData);
		
		$requestContentType = isset($_SERVER['HTTP_ACCEPT']) ? $_SERVER['HTTP_ACCEPT'] : null;
		
		$this->setHttpHeaders($requestContentType, $statusCode);
		
		$result["output"] = $rawData;
				
		if(strpos($requestContentType,'json') !== false){
		
			//echo "sss";
			$response = $this->encodeJson($result);
			echo $response;
		}
		
	}

	function getAllRatingsPerUser() {	

		$user = new User();
		$rawData = $user->getAllRatingPerUser();

		if(empty($rawData)) {
			$statusCode = 404;
			$rawData = array('success' => 0);		
		} else {
			$statusCode = 200;
		}

		//var_dump($rawData);
		
		$requestContentType = isset($_SERVER['HTTP_ACCEPT']) ? $_SERVER['HTTP_ACCEPT'] : null;
		
		$this->setHttpHeaders($requestContentType, $statusCode);
		
		$result["output"] = $rawData;
				
		if(strpos($requestContentType,'json') !== false){
		
			//echo "sss";
			$response = $this->encodeJson($result);
			echo $response;
		}
		
	}
	
	function add() {	
		$user = new User();
		$rawData = $user->addUser();
		if(empty($rawData)) {
			$statusCode = 404;
			$rawData = array('success' => 0);		
		} else {
			$statusCode = 200;
		}
		
		$requestContentType = $_SERVER['HTTP_ACCEPT'];
		$this ->setHttpHeaders($requestContentType, $statusCode);
		$result = $rawData;
				
		if(strpos($requestContentType,'application/json') !== false){
			$response = $this->encodeJson($result);
			echo $response;
		}
	}

	function deleteUserById() {	
		$user = new User();
		$rawData = $user->deleteUser();
		
		if(empty($rawData)) {
			$statusCode = 404;
			$rawData = array('success' => 0);		
		} else {
			$statusCode = 200;
		}
		
		$requestContentType = $_SERVER['HTTP_ACCEPT'];
		$this ->setHttpHeaders($requestContentType, $statusCode);
		$result = $rawData;
				
		if(strpos($requestContentType,'application/json') !== false){
			$response = $this->encodeJson($result);
			echo $response;
		}
	}
	
	function editUserById() {	
		$user = new User();
		$rawData = $user->editUser();
		if(empty($rawData)) {
			$statusCode = 404;
			$rawData = array('success' => 0);		
		} else {
			$statusCode = 200;
		}
		
		$requestContentType = $_SERVER['HTTP_ACCEPT'];
		$this ->setHttpHeaders($requestContentType, $statusCode);
		$result = $rawData;
				
		if(strpos($requestContentType,'application/json') !== false){
			$response = $this->encodeJson($result);
			echo $response;
		}
	}
	
	public function encodeJson($responseData) {
		$jsonResponse = json_encode($responseData);
		return $jsonResponse;		
	}
}
?>