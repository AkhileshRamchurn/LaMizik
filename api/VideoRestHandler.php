<?php
use Opis\JsonSchema\{
	Validator, ValidationResult, ValidationError, Schema
};
require '../vendor/autoload.php';

require_once("SimpleRest.php");
require_once("Video.php");

class VideoRestHandler extends SimpleRest {
    function getAllVideos() {	

		$video = new Video();
		$rawData = $video->getAllVideo();

		$data["output"] = $rawData;
		$data1 = json_encode($data);

		$data2 = json_decode($data1);
		
		$schema = Schema::fromJsonString(file_get_contents('videoAPI_jsonSchema.json'));
		$validator = new Validator();

		/** @var ValidationResult $result */
		$validationResult = $validator->schemaValidation($data2, $schema); 

		if ($validationResult->isValid()) {
			$statusCode = 200;

		} else {
			$statusCode = 404;
			$rawData = array('success' => 0);
		}


		// if(empty($rawData)) {
		// 	$statusCode = 404;
		// 	$rawData = array('success' => 0);		
		// } else {
		// 	$statusCode = 200;
		// }

		//var_dump($rawData);
		
		$requestContentType = isset($_SERVER['HTTP_ACCEPT']) ? $_SERVER['HTTP_ACCEPT'] : null;
		
		$this->setHttpHeaders($requestContentType, $statusCode);
		
		$result["output"] = $rawData;
				
		// if(strpos($requestContentType,'json') !== false){
		
			//echo "sss";
			$response = $this->encodeJson($result);
			echo $response;
		// }
		
	}
    function add() {	
		$video = new Video();
		$rawData = $user->addVideo();
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
    function deleteVideoById() {	
		$video = new Video();
		$rawData = $video->deleteVideo();
		
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
    function editVideoById() {	
		$video = new Video();
		$rawData = $video->editVideo();
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