<?php
	session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/login_style.css">
	<title>Log In</title>
</head>
<body>

	<?php

		if (isset($_SESSION["User_ID"])){	//if the user is already logged in, the login page cannot be accessed unless the user logs out first
			header("Location: home.php");
		}
		else if (isset($_POST["submit"])){

			function test_input($data) {
				$data = trim($data);
				$data = stripslashes($data);
				$data = htmlspecialchars($data);
				return $data;
			}

			function test_input2($data) {
				$data = stripslashes($data);
				$data = htmlspecialchars($data);
				return $data;
			}
			
			$usernameErr = '';
			$passwordErr = '';

			$username = $_POST["name"];
			$password = $_POST["password"];
			
			if (empty($username) || empty(trim($username))){
				$usernameErr = 'Username/Email required';
			}
			else {
				$username = test_input($username);
			}

			if (empty($password)){
				$passwordErr = 'Password required';
			}
			else {
				$password = test_input2($password);
			}

			if (empty($usernameErr) && empty($passwordErr)){
			
				require_once 'dbconnect.php';
				
				$sql = "SELECT * FROM user WHERE Username = ? OR Email = ?;";
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$stmt = $conn->prepare($sql);
				$stmt->bindParam(1, $username,PDO::PARAM_STR);
				$stmt->bindParam(2, $username,PDO::PARAM_STR);
				$stmt->execute();
				$row = $stmt->fetch(PDO::FETCH_ASSOC);
				
				if ($row !== false){
					
					$hashedPassword = $row["Password"];
					$checkPassword = password_verify($password, $hashedPassword);
						
					if ($checkPassword === false){
						$passwordErr = 'Incorrect Password!';
					}
					else{
						$_SESSION["User_ID"] = $row["User_ID"];
						$_SESSION["Username"] = $row["Username"];
						$_SESSION["User_Type"] = $row["User_Type"];
						$_SESSION["LinkedCard"] = $row["Linked_Card"];
						
						header("Location: home.php");
					}
					
				}
				else {
					$usernameErr = 'User not found!';
					$username = '';
				}
			}
		}

	?>

	<div class="right-panel-container">
		<div class="right-panel-signup">
			<h1>New To Lamizik?</h1>
			<p>Get connected to musicians by creating an account</p>
			<button class="button">Sign Up</button>
		</div>
		<div class="right-panel-guest">
			<h1>OR.....</h1>
			<p>Continue as a Guest!</p>
			<button class="button">Skip</button>
		</div>
	</div>
	<div class="form-container">
		<form action = "<?php echo $_SERVER["PHP_SELF"];?>" method = "POST">
			<h1>Log In</h1>
			<div>
				<?php if (isset($usernameErr)){ echo "<p>$usernameErr</p>"; } ?>
				<input type = "text" name = "name" placeholder = "Username/Email" <?php if (!empty($username)){echo "value = $username";} ?>>
			</div>

			<div>
				<?php if (isset($passwordErr)){ echo "<p>$passwordErr</p>"; } ?>
				<input type = "password" name = "password" placeholder = "Password" >
			</div>

			<div>
				<button type = "submit" name = "submit">Log In</button>
			</div>
		</form>
	</div>

</body>
<footer>

</footer>
</html>