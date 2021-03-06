<?php 
    session_start();

    $userType = null;
    if (isset($_SESSION['User_Type'])) {
        $userType = $_SESSION['User_Type'];
    }

    if ($userType != 'NormalUser') {
        header('HTTP/1.1 403 Forbidden'); 
        die();
    }

    $userId = null;

    $linkedCard = null;
    if (isset($_SESSION['LinkedCard'])) {
        $linkedCard = $_SESSION['LinkedCard'];
    }

    require 'dbconnect.php';
    if(isset($_SESSION["User_ID"])){
        $userId = $_SESSION["User_ID"];
        
        /*CHANGE PERSONAL INFO*/
        if(isset($_POST['username']) && isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['email']) && isset($_POST['phonenum'])){
            function test_input($data) {
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            }
            $username = test_input($_POST['username']);
            $firstname = ucwords(test_input($_POST['firstname']));
            $lastname = ucwords(test_input($_POST['lastname']));
            $email = test_input($_POST['email']);
            $phone_num = test_input($_POST['phonenum']);
            $validation_error = false; 
            
            // echo $username.$firstname ;
            if (!preg_match("/^[a-zA-Z0-9_]*$/", $username)){
                $validation_error= true;
                echo 'Invalid Username!';       
                exit(0);
            }
            if (!preg_match("/^[a-z ,.'-]+$/i", $firstname)){
                $validation_error= true;
                echo 'Invalid first name!';  
                exit(0);
            }
            if (!preg_match("/^[a-z ,.'-]+$/i", $lastname)){
                $validation_error= true;
                echo 'Invalid last name!';
                exit(0);
            }
            if (!filter_var($email,FILTER_VALIDATE_EMAIL)){
                $validation_error= true;
                echo 'Invalid email!'; 
                exit(0);
            }

            if ($phone_num < 50000000 || $phone_num > 59999999){
                $validation_error= true;
                echo 'Invalid phone number!';  
                exit(0); 
            }

            if($validation_error == false){
                //if username or email (which must  be unique) already exists in database, an error is produced
                $query = "SELECT COUNT(*) FROM user where Username = ? AND User_ID != ? ";
                $stmt = $conn->prepare($query);
                
                $stmt ->bindParam(1,$username,PDO::PARAM_STR);
                $stmt ->bindParam(2,$userId,PDO::PARAM_INT);
                $stmt -> execute();
                $numOfRowUsername = $stmt -> fetchColumn();
                
                
                $query = "SELECT COUNT(*) FROM user where  Email = ? AND User_ID <>?";
                $stmt = $conn->prepare($query);
        
                $stmt ->bindParam(1,$username,PDO::PARAM_STR);
                $stmt ->bindParam(2,$userId,PDO::PARAM_INT);
                $stmt -> execute();
                $numOfRowEmail = $stmt -> fetchColumn();
                
                if($numOfRowUsername > 0){	
                    echo 'Invalid - Username already taken!';
                    exit(0);
                }
                if($numOfRowEmail > 0){	
                    echo 'Invalid - Email already taken!';
                    exit(0);
                }
                if ($numOfRowEmail == 0 && $numOfRowUsername == 0){
				    $sql = "UPDATE user SET Username = ?, First_name= ?, Last_name=?, Email=? WHERE User_ID = ?";
                    $stmt = $conn -> prepare($sql);
                    $stmt ->bindParam(1,$username,PDO::PARAM_STR);
                    $stmt ->bindParam(2,$firstname,PDO::PARAM_STR);
                    $stmt ->bindParam(3,$lastname,PDO::PARAM_STR);
                    $stmt ->bindParam(4,$email,PDO::PARAM_STR);
                    $stmt ->bindParam(5,$userId,PDO::PARAM_INT);
                    $stmt ->execute();

                    $sql2 = "UPDATE contact SET Phone_Number = ? WHERE User_ID = ?";
                    $stmt2 = $conn -> prepare($sql2);
                    $stmt2 ->bindParam(1,$phone_num,PDO::PARAM_INT);
                    $stmt2 ->bindParam(2,$userId,PDO::PARAM_INT);
                    $stmt2 ->execute();
                    $_SESSION['Username'] = $username;
                    echo $username." ".$firstname." ".$lastname." ".$email." ".$phone_num;
                    
                }
            }
            
            exit(0);
        }
        
        /* RESET PASSWORD*/
        if(isset($_POST['old_pw']) && isset($_POST['new_pw']) && isset($_POST['repeat_pw'])){
            
            function validate($data) {
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            }

            $oldPwd = validate($_POST['old_pw']);
            $newPwd = validate($_POST['new_pw']);
            $repeatPwd = validate($_POST['repeat_pw']);

            if(empty($oldPwd)){
                echo "Old password is required!";
                exit(0);
            }
            else if(empty($newPwd)){
                echo "New password is required!";
                exit(0);
            }
            else if($newPwd !== $repeatPwd){
                echo "Repeated password does not match!";
                exit(0);
            }
            else{
                //hash new password
                $hashedNewPwd = password_hash($newPwd, PASSWORD_DEFAULT);
                
                /*
                Check if user old password that has been entered by 
                user matches with the password in database*/
                $sql = "SELECT Password FROM user WHERE User_ID = ?";
                $stmt = $conn -> prepare($sql);
                $stmt -> bindParam(1,$userId,PDO::PARAM_INT);
                $stmt -> execute();

                
                $hashedOldPwd = $stmt -> fetch();

                if(password_verify($oldPwd,$hashedOldPwd[0])){
                    // echo 'Password is valid!';
                    $sql2 = "UPDATE user SET Password = ? WHERE User_ID = ?";
                    $stmt = $conn -> prepare($sql2);
                    $stmt -> bindParam(1,$hashedNewPwd,PDO::PARAM_STR);
                    $stmt -> bindParam(1,$userId,PDO::PARAM_INT);
                    $stmt ->execute();
                    echo 'success';
                }
                else{
                    echo 'Incorrect or same old password inserted!';
                }

                exit(0);
            }  
        }

        /* ADD/REMOVE CARD*/
        if(isset($_POST['card_action'])){
            
            if ($_POST['card_action'] == "add") {
                $sql = "UPDATE user SET Linked_Card = 1 WHERE User_ID = ?";
                $_SESSION["LinkedCard"] = 1;
            }
            else if ($_POST['card_action'] == "remove") {
                $sql = "UPDATE user SET Linked_Card = 0 WHERE User_ID = ?";
                $_SESSION["LinkedCard"] = 0;
            }

            $stmt = $conn -> prepare($sql);
            $stmt -> bindParam(1,$userId,PDO::PARAM_INT);
            $stmt -> execute();
            
            echo 'success';

            exit(0); 
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=1.0, initial-scale=1.0">
    <link rel="stylesheet" href="css/user_profile.css">
    <title>Profile</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="scripts/user_profile.js"></script>
</head>
<?php include 'includes/navbar.php'; ?>
<body>
    <div class="main-container">
            
            <div class="front-left-container">
                <div class="header">
                    <h1 class="main-title">Profile</h1>
                    <div class="left-navigation-panel">
                        <div class="navigation-button" id="info-btn">
                            <span></span>
                            <span></span>
                            <h4>Personal info</h4>
                        </div>
                        <div class="navigation-button" id="reset-btn">
                            <span></span>
                            <span></span>
                            <h4>Change Password</h4>
                        </div>
                        <div class="navigation-button" id="wallet-btn">
                            <span></span>
                            <span></span>
                            <h4>Manage Card</h4>
                        </div>
                    </div>
                </div> 
                
                <div class="left-contents-panel">
                    <div class="personal-info-panel" >
                        <h3 class="title">Edit your personal information</h3>
                        
                        <?php
                                $sql3="SELECT * FROM user WHERE User_ID=?";
                                $stmt = $conn -> prepare($sql3);
                                $stmt -> bindParam(1,$userId,PDO::PARAM_INT);
                                $stmt -> execute();

                                $result = $stmt -> fetch(PDO::FETCH_ASSOC);

                                $sql4="SELECT Phone_Number FROM contact WHERE User_id=?";
                                $stmt1 = $conn -> prepare($sql4);
                                $stmt1 -> bindParam(1,$userId,PDO::PARAM_INT);
                                $stmt1 -> execute();

                                $result1 = $stmt1 ->fetch(PDO::FETCH_ASSOC);  
                        ?>
                        
                        <div class="personal-info-field">
                            <label>Username</label>
                            <input type="text" name="username" id="username" placeholder="<?php if(isset($_SESSION["User_ID"])){ echo $result['Username'];}?> ">  
                        </div>       
                        <div class="personal-info-field">
                            <label>First Name</label>
                            <input type="text" name="first_name" id="first_name" placeholder="<?php if(isset($_SESSION["User_ID"])){ echo $result['First_Name'];}?>"> 
                        </div> 
                        <div class="personal-info-field">
                            <label>Last Name</label>
                            <input type="text" name="last_name" id="last_name" placeholder="<?php if(isset($_SESSION["User_ID"])){ echo $result['Last_Name'];}?>"> 
                        </div>
                        <div class="personal-info-field">
                            <label>Email</label>
                            <input type="text" name="email" id="email" placeholder="<?php if(isset($_SESSION["User_ID"])){ echo $result['Email'];}?>"> 
                        </div> 
                        <div class="personal-info-field">
                            <label>Phone Number</label>
                            <input type="text" name="phone_num" id="phone_num" placeholder="<?php if(isset($_SESSION["User_ID"])){ echo $result1['Phone_Number'] ;}?>"> 
                        </div>  
                        <div class="personal-info-field">
                            <div id="change-personal-info-response"></div>
                        </div>     
                        <div class="personal-info-field">
                            <div class="update-info-btn">
                                <button type="button" id="update-btn" data-id="<?php echo $userId ?>">UPDATE</button>   
                            </div>
                        </div>      
                    </div>
                    <div class="resetPassword-panel">
                        <h3 class="title title2">Edit your password</h3>
                        <form action = "<?php echo $_SERVER["PHP_SELF"];?>" method = "POST" id="password-form">
                            
                            <div class="change-password-field">
                                <label>Old Password</label>
                                <input type="password" name="old_password" id="old_password">        
                            </div>

                            <div class="change-password-field">
                                <label>New Password</label>
                                <input type="password" name="new_password" id="new_password"> 
                            </div>        
                            
                            <div class="change-password-field">
                                <label>Repeat Password</label>
                                <input type="password" name="repeat_password" id="repeat_password"> 
                            </div>
                            
                            <div class="change-password-field">
                                <div id="change-password-response"></div>                            
                            </div>
                            <div class="change-password-field">
                                <div class="change-password-btn">
                                    <button type="submit" id="pw-btn" data-id="<?php echo $userId ?>">CHANGE</button>   
                                </div>
                            </div>                        
                        </form>
                    </div>
                    <div class="manage-wallet-panel">
                        <h3 class="title title3 <?php if ($linkedCard == 1) {echo "title4";} ?>">Manage payment option</h3>
                        <?php
                            if ($linkedCard == 0) {
                                ?>

                                <div>
                                    <label>Card Number</label>
                                    <input class="input-card-number" type="text" name="cardNumber">    
                                </div>
                                <div>
                                    <label>Expiration Date</label>
                                    <input class="input-card-exp" type="text" name="cardExpDate" placeholder="Month/Year"> 
                                </div>
                                <div>
                                    <label>CCV</label>
                                    <input class="input-card-ccv" type="text" name="cardCCV">
                                </div>
                                <button id="btn-add-card" data-id="<?php echo $userId ?>">ADD CARD</button>

                                <?php
                            }
                            else {
                                ?>

                                <p class="label-p">A card has already been linked to your account</p>
                                <button id="btn-remove-card" data-id="<?php echo $userId ?>">REMOVE CARD</button>
                                <?php
                            }
                        ?>
                    </div>
                </div>
            </div>

            <div class="front-right-container">
                <div class="left-panel-signin">
                    <h1 class="title title-right">Manage Account</h1>
                    <p class="subtitle">Modify your account details</p>
                </div>
            </div>

            <!-- <div class="front-right-container">
                <div class="profile-container">
                    <div class="profile-pic">
                        <img src="img/user.png">
                        <input type="file" id="file">
                        <label for="file" id="uploadBtn">Choose Photo</label>
                    </div>
                </div>    
            </div> -->

    </div>
</body>
</html>