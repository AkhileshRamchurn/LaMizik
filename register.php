<?php
	session_start();
	if (isset($_SESSION["User_ID"])){	//if the user is already logged in, the register page cannot be accessed unless the user logs out first
		header("Location: home.php");
		exit();
	}

	if(isset($_POST['register-submit'])){

		require 'dbconnect.php';

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
		$firstnameErr = '';
		$lastnameErr = '';
		$emailErr = '';
		$passwordErr = '';
		$phoneNumErr = '';


		$username = test_input($_POST['username']);
		$firstname = ucwords(test_input($_POST['firstname']));
		$lastname = ucwords(test_input($_POST['lastname']));
		$email = test_input($_POST['email']);
		$password = test_input2($_POST['pwd']);
		$passwordRepeat = test_input2($_POST['pwd-repeat']);
		$phone_num = test_input($_POST['phone_num']);
		$linkedCard = 0;
		if (!empty($_POST['cardNumber']) && !empty($_POST['cardExpDate']) && !empty($_POST['cardCCV'])) {
			$linkedCard = 1;
		}

		if (empty($username)){
			$usernameErr = 'Username required';
		}
		else if (!preg_match("/^[a-zA-Z0-9_]*$/", $username)){
			$usernameErr = 'Invalid Username! Username can only contain letters, numbers and underscore';
			$username = '';
		}

		if (empty($firstname)){
			$firstnameErr = 'First name required';
		}
		else if (!preg_match("/^[a-z ,.'-]+$/i", $firstname)){
			$firstnameErr = 'Invalid name!';
			$firstname = '';
		}

		if (empty($lastname)){
			$lastnameErr = 'Last name required';
		}
		else if (!preg_match("/^[a-z ,.'-]+$/i", $lastname)){
			$lastnameErr = 'Invalid name!';
			$lastname = '';
		}

		if (empty($email)){
			$emailErr = 'Email required';
		}
		else if (!filter_var($email,FILTER_VALIDATE_EMAIL)){
			$emailErr = 'Invalid email!';
			$email = '';
		}

		if (empty($password) || empty($passwordRepeat)){
			$passwordErr = 'Password required';
		}
		else if ( $password !== $passwordRepeat){
			$passwordErr = 'The passwords do not match!';
		}

		if (empty($phone_num)){
			$phoneNumErr = 'Phone number required';
		}
		else if ($phone_num < 50000000 || $phone_num > 59999999){
			$phoneNumErr = 'Invalid phone number!';
			$phone_num = '';
		}
		
		if (empty($usernameErr) && empty($firstnameErr) && empty($lastnameErr) && empty($emailErr) && empty($passwordErr) && empty($phoneNumErr)){
			
			//if username or email (which must  be unique) already exists in database, an error is produced
			$query = "SELECT COUNT(*) FROM user where Username = ? ";
			$stmt = $conn->prepare($query);
			
			$stmt ->bindParam(1,$username,PDO::PARAM_STR);
			$stmt -> execute();
			$numOfRowUsername = $stmt -> fetchColumn();
			
			
			
			$query = "SELECT COUNT(*) FROM user where  Email = ? ";
			$stmt = $conn->prepare($query);
	
			$stmt ->bindParam(1,$email,PDO::PARAM_STR);
			$stmt -> execute();
			$numOfRowEmail = $stmt -> fetchColumn();
			
			if($numOfRowUsername > 0){	
				$usernameErr = 'Username already taken';
				$username = '';
			}
			if($numOfRowEmail > 0){	
				$emailErr = 'Email already taken';
				$email = '';
			}
			if ($numOfRowEmail == 0 && $numOfRowUsername == 0){
			
				//hash password before insert data into database
				$hashedPwd = password_hash($password, PASSWORD_DEFAULT);
				
				$sql = "INSERT INTO user(Username,First_name,Last_name,Email,Password, Linked_Card) VALUES(?,?,?,?,?,?)";
				$stmt = $conn -> prepare($sql);
	
				$stmt ->bindParam(1,$username,PDO::PARAM_STR);
				$stmt ->bindParam(2,$firstname,PDO::PARAM_STR);
				$stmt ->bindParam(3,$lastname,PDO::PARAM_STR);
				$stmt ->bindParam(4,$email,PDO::PARAM_STR);
				$stmt ->bindParam(5,$hashedPwd,PDO::PARAM_STR);
				$stmt ->bindParam(6,$linkedCard,PDO::PARAM_INT);
				$stmt ->execute();
	
				$last_id = $conn->lastInsertId();
				
				$sql= "INSERT INTO contact(Phone_Number,User_ID) VALUES(?,?)";
				$stmt = $conn -> prepare($sql);
	
				$stmt ->bindParam(1,$phone_num,PDO::PARAM_INT);
				$stmt ->bindParam(2,$last_id,PDO::PARAM_INT);
				$stmt ->execute();
				
	
				header("Location: login.php");
				exit();
				
			}
		}
			
	}				

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<link rel="stylesheet" href="css/register_style.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="scripts/signup_script.js"></script>
	<title>Sign Up</title>
</head>

<body>
	<div class="left-panel-container">
		<div class="left-panel-signin">
			<h1 class="title title-left">Already Part of Lamizik?</h1>
			<p class="subtitle">Log into your account</p>
			<button id="signin-btn" class="left-panel-button">Log In</button>
		</div>
		<div class="left-panel-guest">
			<h1 class="title title-left">OR.....</h1>
			<p class="subtitle">Continue as a Guest!</p>
			<button id="skip-btn" class="left-panel-button">Skip</button>
		</div>
	</div>
	<div class="form-container">
		<div class="form-header">
			<h1 class="main-title">Sign-Up</h1>
			<div class="progress-bar">
				<div class="step">
					<p>Basic Info</p>
					<div class="bullet">
						<span>1</span>
					</div>
					<div class="check fas fa-check"></div>
				</div>
				<div class="step">
					<p>Personal Info</p>
					<div class="bullet">
						<span>2</span>
					</div>
					<div class="check fas fa-check"></div>
				</div>
				<div class="step">
					<p>Payment Details</p>
					<div class="bullet">
						<span>3</span>
					</div>
					<div class="check fas fa-check"></div>
				</div>
			</div>
		</div>
		<div class="form-outer">
			<form class="main-form" action="<?php echo $_SERVER["PHP_SELF"];?>"  method="post">
				
				<div class="page slide-page">
					<div class="title">Basic Info:</div>
					<div class="field">
						<div class="label-container">
							<div class="label">Username</div>
							<p class='error-msg username-error-msg'><?php if (!empty($usernameErr)){ echo $usernameErr; } ?></p>
						</div>
						<input class="input-username" type="text" name="username" <?php if (!empty($username)){echo "value = $username";} ?> required >
					</div>
					<div class="field">
						<div class="label-container">
							<div class="label">Password</div>
							<p class='error-msg password-error-msg'><?php if (!empty($passwordErr)){ echo $passwordErr; } ?></p>
						</div>
						<input class="input-password" type="password" name="pwd" required >
					</div>
					<div class="field">
						<div class="label-container">
							<div class="label">Repeat Password</div>
							<p class='error-msg password-repeat-error-msg'></p>
						</div>
						<input class="input-password-repeat" type="password" name="pwd-repeat" required >
					</div>
					<div class="field">
						<button type="button" class="firstNext next">Next</button>
					</div>
				</div>

				<div class="page">
					<div class="title">Personal Info:</div>
					<div class="field">
						<div class="label-container">
							<div class="label">First Name</div>
							<p class='error-msg fname-error-msg'><?php if (!empty($firstnameErr)){ echo $firstnameErr; } ?></p>
						</div>
						<input class="input-first-name" type="text" name="firstname" <?php if (!empty($firstname)) {echo "value = \"".$firstname."\""; } ?> required >
					</div>
					<div class="field">
						<div class="label-container">
							<div class="label">Last Name</div>
							<p class="error-msg lname-error-msg"><?php if (!empty($lastnameErr)){ echo $lastnameErr; } ?></p>
						</div>
						<input class="input-last-name" type="text" name="lastname" <?php if (!empty($lastname)){echo "value = \"".$lastname."\"";} ?> required >
					</div>
					<div class="field">
						<div class="label-container">
							<div class="label">Email Address</div>
							<div class="error-msg email-error-msg"><?php if (!empty($emailErr)){ echo $emailErr; } ?> </div>
						</div>
						<input class="input-email" type="email" name="email" <?php if (!empty($email)){echo "value = $email";} ?> required >
					</div>
					<div class="field">
						<div class="label-container">
							<div class="label">Phone Number</div>
							<p class="error-msg phone-number-error-msg"><?php if (!empty($phoneNumErr)){ echo $phoneNumErr; } ?></p>
						</div>
						<input class="input-phone-number" type="text" name="phone_num" <?php if (!empty($phone_num)){echo "value = $phone_num";} ?> required >
					</div>
					<div class="field btns">
						<button type="button" class="prev-1 prev">Previous</button>
						<button type="button" class="next-1 next">Next</button>
					</div>
				</div>

				<div class="page">
					<div class="title">Payment Details:</div>
						<div class="field">
							<div class="label-container">
								<div class="label">Card Number</div>
								<p class="error-msg card-number-error-msg"></p>
							</div>
							<input class="input-card-number" type="text" name="cardNumber">
						</div>
					<div class="field">
						<div class="label-container">
							<div class="label">Expiration Date</div>
							<p class="error-msg card-exp-error-msg"></p>
						</div>
						<input class="input-card-exp" type="text" name="cardExpDate" placeholder="Month/Year">
					</div>
					<div class="field">
						<div class="label-container">
							<div class="label">CCV</div>
							<p class="error-msg card-ccv-error-msg"></p>
						</div>
						<input class="input-card-ccv" type="text" name="cardCCV">
					</div>
					<div class="field btns">
						<button type="button" class="prev-2 prev">Previous</button>
						<button type="button" class="next-skip next">Skip</button>
						<button type="button" class="next-2 fullNext next">Next</button>
					</div>
				</div>

				<div class="page">
					<div class="title">That's All!</div>
					<div class="label msg-label">All the required information has been entered.</div>
					<div class="label msg-label">You can still go back and change your inputs.</div>
					<div class="field btns">
						<button type="button" class="prev-3 prev">Previous</button>
						<button class="next btn-submit" type="submit" name="register-submit">Submit</button>
					</div>
				</div>
			</form>
		</div>
	</div>		
</body>

</html>
