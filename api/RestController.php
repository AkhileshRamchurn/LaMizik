<?php

//Adapted from https://phppot.com/php/php-restful-web-service/
require_once("UserRestHandler.php");
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
}	
?>
