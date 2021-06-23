<?php

//Adapted from https://phppot.com/php/php-restful-web-service/
require_once("UserRestHandler.php");
require_once("videoRestHandler.php");

$method = $_SERVER['REQUEST_METHOD'];
$view = "";

if(isset($_GET["resource"]))
	$resource = $_GET["resource"];
if(isset($_GET["page_key"]))
	$page_key = $_GET["page_key"];
/*
controls the RESTful services
URL mapping
*/


switch($resource){
	case "user":	
		switch($page_key){

			case "list":
				// to handle REST Url /user/list/
				
				//echo "list invoked from user";
				$userRestHandler = new UserRestHandler();
				$result = $userRestHandler->getAllUsers();
			break;

			case "listUserView":
				// to handle REST Url /user/list/
				
				//echo "list invoked from user";
				$userRestHandler = new UserRestHandler();
				$result = $userRestHandler->getAllViewsPerUser();
			break;
	
			case "create":
				// to handle REST Url /user/create/
				//echo "create invoked from user";
				$userRestHandler = new UserRestHandler();
				$userRestHandler->add();
			break;
		
			case "delete":
				// to handle REST Url /user/delete/<row_id>
				//echo "delete invoked from user";
				$userRestHandler = new UserRestHandler();
				$result = $userRestHandler->deleteUserById();
			break;
		
			case "update":
				//echo "update invoked from user";
				// to handle REST Url /user/update/<row_id>
				$userRestHandler = new UserRestHandler();
				$userRestHandler->editUserById();
			break;
		}
	break;

	case "video":
		switch($page_key){

			case "list":
				// to handle REST Url /user/list/
				
				//echo "list invoked from video";
				$videoRestHandler = new VideoRestHandler();
				$result = $videoRestHandler->getAllVideos();
			break;

			case "create":
				// to handle REST Url /video/create/
				//echo "create invoked from video";
				$videoRestHandler = new VideoRestHandler();
				$videoRestHandler->add();
			break;
		
			case "delete":
				// to handle REST Url /video/delete/<row_id>
				//echo "delete invoked from video";
				$videoRestHandler = new VideoRestHandler();
				$result = $videoRestHandler->deleteVideoById();
			break;
		
			case "update":
				//echo "update invoked from video";
				// to handle REST Url /video/update/<row_id>
				$videoRestHandler = new VideoRestHandler();
				$userRestHandler->editVideoById();
			break;
		}
	break;
		
}	
?>
