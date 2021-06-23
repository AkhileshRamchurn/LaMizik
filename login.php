<?php
	session_start();

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

				if ($row["IsBanned"] == 0) {
				
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

						if ($row["User_Type"] == "Admin") {
							header("Location: adminAnalytics.php");
						}
						else {
							header("Location: home.php");
						}
					}

				}
				else {
					$usernameErr = 'User not found!';
					$username = '';
				}
				
			}
			else {
				$usernameErr = 'User not found!';
				$username = '';
			}
		}
	}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/login_style.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="scripts/login_script.js"></script>
	<title>Log In</title>
</head>
	<body>

		<div class="form-container">
			<form action = "<?php echo $_SERVER["PHP_SELF"];?>" method = "POST">
				<h1 class="title">Log In</h1>
				<div class = "input-username-container">
					<p id='username-error-msg' class='error-msg'><?php if (!empty($usernameErr)){ echo $usernameErr; } ?></p>
					<input class="user-input input-username <?php if (!empty($usernameErr)){ echo " invalid-field"; } ?>" type = "text" name = "name" placeholder = "Username/Email" <?php if (!empty($username)){echo "value = $username";} ?>>
				</div>

				<div class = "input-password-container">
					<p id='password-error-msg' class='error-msg'><?php if (!empty($passwordErr)){ echo $passwordErr; } ?></p>
					<input class="user-input input-password <?php if (!empty($passwordErr)){ echo " invalid-field"; } ?>" type = "password" name = "password" placeholder = "Password" >
				</div>

				<div>
					<button class="log-in-btn" type = "submit" name = "submit">Log In</button>
				</div>
			</form>
		</div>
		<div class="right-panel-container">
			<div class="right-panel-signup">
				<h1 class="title title-right">New To Lamizik?</h1>
				<p class="subtitle">Get connected to musicians by creating an account</p>
				<button id="signup-btn" class="right-panel-button">Sign Up</button>
			</div>
			<div class="right-panel-guest">
				<h1 class="title title-right">OR.....</h1>
				<p class="subtitle">Continue as a Guest!</p>
				<button id="skip-btn" class="right-panel-button">Skip</button>
			</div>
		</div>

	</body>
<footer>

</footer>
</html>