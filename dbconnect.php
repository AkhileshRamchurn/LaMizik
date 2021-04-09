<?php

	$serverName = "localhost";
	$dbUsername = "root";
	$dbPassword = "";
	$dbName = "lamizik";
	
	try
	{
	 $conn = new PDO("mysql:host=$serverName;dbname=$dbName", $dbUsername, $dbPassword);
	}
	catch(PDOException $e)
	 {
		echo "Error: " . $e->getMessage();
		die;
	 }

?>