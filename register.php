<?php
	session_start();
	if (isset($_SESSION["User_ID"])){	//if the user is already logged in, the register page cannot be accessed unless the user logs out first
		header("Location: home.php");
		exit();
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Register</title>
</head>

<?php

	require "header.php"

?>

<body>
	<main>
		<div>
			<?php 
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

					if (empty($username)){
						$usernameErr = 'Username required';
					}
					else if (!preg_match("/^[a-zA-Z0-9_]*$/", $username)){
						$usernameErr = 'Invalid Username! Username can only contain letters, numbers and underscore';
						$username = '';
					}

					if (empty($firstname)){
						$firstnameErr = 'Fist name required';
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
							
							$sql = "INSERT INTO user(Username,First_name,Last_name,Email,Password) VALUES(?,?,?,?,?)";
							$stmt = $conn -> prepare($sql);
				
							$stmt ->bindParam(1,$username,PDO::PARAM_STR);
							$stmt ->bindParam(2,$firstname,PDO::PARAM_STR);
							$stmt ->bindParam(3,$lastname,PDO::PARAM_STR);
							$stmt ->bindParam(4,$email,PDO::PARAM_STR);
							$stmt ->bindParam(5,$hashedPwd,PDO::PARAM_STR);
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


			<form action="<?php echo $_SERVER["PHP_SELF"];?>"  method="post" style="text-align: center" >
		  		<fieldset>
					<legend>Sign-Up</legend>
						
						<?php if (isset($usernameErr)){ echo "<p>$usernameErr</p>"; } ?>
					    <input type="text" name="username"  placeholder="Username" pattern ="[a-zA-Z0-9_]*$" <?php if (!empty($username)){echo "value = $username";} ?> required ><br><br>
						
						<?php if (isset($firstnameErr)){ echo "<p>$firstnameErr</p>"; } ?>
					    <input type="text" name="firstname"  placeholder="firstname" pattern = "[a-zA-Z ,.'-]+$" <?php if (!empty($firstname)) {echo "value = \"".$firstname."\""; } ?> required ><br><br>
					    
						<?php if (isset($lastnameErr)){ echo "<p>$lastnameErr</p>"; } ?>
						<input type="text" name="lastname"  placeholder="lastname" pattern = "[a-zA-Z ,.'-]+$" <?php if (!empty($lastname)){echo "value = \"".$lastname."\"";} ?> required ><br><br>

						<?php if (isset($emailErr)){ echo "<p>$emailErr</p>"; } ?> 
					    <input type="email" name="email" placeholder="E-mail" <?php if (!empty($email)){echo "value = $email";} ?> required ><br><br>

						<?php if (isset($passwordErr)){ echo "<p>$passwordErr</p>"; } ?>
					    <input type="password" name="pwd" placeholder="Password" required ><br><br>
					    <input type="password" name="pwd-repeat" placeholder="Repeat password" required ><br><br>

						<?php if (isset($phoneNumErr)){ echo "<p>$phoneNumErr</p>"; } ?>
					    <input type="text" name="phone_num"  placeholder="phone number" pattern = "[5][0-9]{7}$" <?php if (!empty($phone_num)){echo "value = $phone_num";} ?> required ><br><br></div>
						    
					    <input type="submit" name="register-submit" value="Create Account" style="margin-left: 51em;" >	    
				</fieldset>
			</form>			


		</div>
	</main>
</body>

<?php
	require "footer.php"	
?>	

</html>
