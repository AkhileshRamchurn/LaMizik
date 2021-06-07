<?php require_once 'includes/admin_auth.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Admin Account</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="scripts/generateAdminAccount.js"></script>
    <link rel="stylesheet" href="css/generateAdminAccount.css">
</head>
<body>
    <?php include "includes/admin_sidebar.html"; ?>
    <div class="main">
        <div class="form-container">
            <h1>Generate Admin Account</h1>
            <div class="username-field field">
                <input class="text-field" id="username" type = "text" name = "name" placeholder = "Username" disabled >
                <button id="btn-copy-username" class="btn-copy"><i class="fas fa-copy"></i></button>
            </div>
            <div class="-password-field field">
                <input class="text-field" id="password" type = "password" name = "password" placeholder = "Password" disabled >
                <button id="btn-copy-password" class="btn-copy"><i class="fas fa-copy"></i></button>
            </div>
            <button class="btn-generate" type = "submit" name = "generate">Generate</button>
        </div>
    </div>
</body>
</html>