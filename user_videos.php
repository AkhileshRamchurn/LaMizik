<?php
	session_start();

    $user_id = null;
    if (isset($_SESSION['User_ID'])) {
        $user_id = $_SESSION['User_ID'];
    }

    $view_user_id = null;
    if (!empty($_GET['user_id'])) {
        $view_user_id = $_GET['user_id'];

        require 'dbconnect.php';
        $sql = "SELECT * FROM user WHERE User_ID=$view_user_id AND User_Type='NormalUser'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $username = "";
        if (count($row) == 0) {
            header('HTTP/1.1 404 Not Found'); 
            die();
        } 
        else {
            $username = $row[0]['Username'];
        }
    }

    if (empty($user_id) && empty($view_user_id)) {
        header('HTTP/1.1 404 Not Found'); 
        die();
    }

    if (isset($user_id) && empty($view_user_id)) {
        $view_user_id = $user_id;

        require 'dbconnect.php';
        $sql = "SELECT * FROM user WHERE User_ID=$view_user_id AND User_Type='NormalUser'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $username = "";
        if (count($row) == 0) {
            header('HTTP/1.1 404 Not Found'); 
            die();
        } 
        else {
            $username = $row[0]['Username'];
        }
    }

    $isSameUser = false;
    if ($user_id == $view_user_id) {
        $isSameUser = true;
    }

    $userType = null;
    if (isset($_SESSION['User_Type'])) {
        $userType = $_SESSION['User_Type'];
    }

    if ($userType == "Admin" && $isSameUser) {
        header('HTTP/1.1 404 Not Found'); 
        die();
    }

    $linkedCard = null;
    if (isset($_SESSION['LinkedCard'])) {
        $linkedCard = $_SESSION['LinkedCard'];
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Videos</title>
    <link rel="stylesheet" href="css/user_videos_style.css">
    <script src="https://kit.fontawesome.com/260e4ed8bc.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="scripts/user_videos_script.js"></script>

    <script type="text/javascript">
		var userId = <?php echo json_encode($view_user_id); ?>;
        var currentUserId = <?php echo json_encode($user_id); ?>;
        var sameUser = <?php echo json_encode($isSameUser); ?>;
        var userType = <?php echo json_encode($userType); ?>;
        var linkedCard = <?php echo json_encode($linkedCard); ?>;
	</script>
</head>
<?php include 'includes/navbar.php'; ?>
<body>
    <div class="main-container">
        <div class="top-container">
            <i class="fas fa-6x fa-user-circle"></i>
            <div class="user-details">
                <h2 class="username"><?php echo $username; ?></h2>
                <p class="video-count"></p>
            </div>
            <div class="button-container">
                <?php if ((!($isSameUser)) && ($userType == "NormalUser")) { echo "<button class='btn-donate'>Donate</button>"; } ?>
                <?php if ($userType == "Admin") { echo "<button class='btn-ban' value='$view_user_id'>Ban User</button>"; } ?>
                <?php if ($isSameUser) { echo "<button class='btn-add-video'><i class='fas fa-plus'></i></button>"; } ?>
            </div>
        </div>
        <span class="separator"></span>
        <div class="video-grid">
            <div class='user-videos'></div>
        </div>
    </div>

    <!-- Donation Popup -->
	<div class="donation-modal-overlay"></div>
	<div class="donation-modal">
		<div class="donation-modal-header">
			<h2 class="donation-modal-title">Donate</h2>
			<div class="close-btn"><i class="fas fa-times"></i></div>
		</div>
		<div class="donation-modal-body">
			<h3 class="donation-modal-subtitle">Donation amount</h3>
			<input type="text" id="donation-amount">
		</div>
		<div class="donation-modal-footer">
			<button id="donation-cancel-btn">Cancel</button>
			<button id="donation-submit-btn">Submit</button>
		</div>
	</div>

    <!-- Link Card Popup -->
	<div class="link-card-modal-overlay"></div>
	<div class="link-card-modal">
		<div class="link-card-modal-header">
			<h2 class="link-card-modal-title">Cannot Perform Action</h2>
			<div class="close-btn2"><i class="fas fa-times"></i></div>
		</div>
		<div class="link-card-modal-body">
			<h3 class="link-card-modal-subtitle">Payment method has not been set.</h3>
		</div>
		<div class="link-card-modal-footer">
			<button id="link-card-cancel-btn">Cancel</button>
			<button id="link-card-submit-btn">Manage Account</button>
		</div>
	</div>
</body>
</html>