<?php 
    use Opis\JsonSchema\{
        Validator, ValidationResult, ValidationError, Schema
    };
    require '../vendor/autoload.php';

    $user_id = $_POST['User_ID'];

    $url="http://localhost/Lamizik/video/list?user_id=$user_id";
    $video_json= file_get_contents($url);
  
     $obj = json_decode($video_json, false);
    
    $schema = Schema::fromJsonString(file_get_contents('video_jsonSchema.json'));
    $validator = new Validator();

	/** @var ValidationResult $result */
	$result = $validator->schemaValidation($obj, $schema); 

    if ($result->isValid()) {
	    //echo '$data is valid', PHP_EOL;
		header('Content-Type: application/json'); 
		echo $video_json;
	} else {
	    /** @var ValidationError $error */
	    $error = $result->getErrors();
	    echo '$data is invalid', PHP_EOL;
	    
	    foreach ($error as $key => $value) {
	    	# code...
	    	echo "Error: ", $value->keyword(), PHP_EOL;
	    	echo json_encode($value->keywordArgs(), JSON_PRETTY_PRINT), PHP_EOL;
	    }
	}

?>
