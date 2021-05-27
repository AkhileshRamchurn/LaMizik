<?php
    if($_SERVER['REQUEST_METHOD'] == 'POST') {  // check if admin is accessing!!!!

        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $videoId = test_input($_POST['videoId']);
        $action = test_input($_POST['action']);

        if (empty($videoId) || empty($action)) {
            echo "unexpected_error";
        }
        else {
            require_once 'dbconnect.php';
            if ($action == "approve"){
                $sql = 'UPDATE video SET Status = "Approved" WHERE Video_ID = ?';
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(1, $videoId,PDO::PARAM_INT);
                $stmt->execute();
            }
            else if ($action == "reject"){
                $sql = 'UPDATE video SET Status = "Rejected" WHERE Video_ID = ?';
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(1, $videoId,PDO::PARAM_INT);
                $stmt->execute();
            }
            else if ($action == "delete"){
                $sql = 'UPDATE video SET Status = "Permanently Deleted" WHERE Video_ID = ?';
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(1, $videoId,PDO::PARAM_INT);
                $stmt->execute();

                $filepath = glob("video/$videoId.*");
                unlink($filepath[0]);
                $filepath = glob("video/thumbnail/$videoId"."t.*");
                unlink($filepath[0]);
            }
        }
    }
    else {
        header('Location: home.php?error=unexpected_error');    //Add this error handling to home page
    }
?>