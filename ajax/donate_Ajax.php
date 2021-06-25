<?php
    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $receiver_id = test_input($_POST['user_id']);
        $sender_id = test_input($_POST['sender_id']);
        $dono_amount = test_input($_POST['dono_amount']);
        
        if ((empty($receiver_id) && $receiver_id != 0) || (empty($sender_id) && $sender_id != 0) || empty($dono_amount)) {
            header('HTTP/1.0 403 Forbidden');
        }
        else {
            require '../dbconnect.php';

            $sql = "INSERT INTO donation (Sender_ID, Receiver_ID, Amount) VALUES (?,?,?)";
            $result = $conn->prepare($sql);
            $result->bindParam(1, $sender_id,PDO::PARAM_STR);
            $result->bindParam(2, $receiver_id,PDO::PARAM_INT);
            $result->bindParam(3, $dono_amount,PDO::PARAM_INT);
            $result->execute();

            echo "success"; 
        }
    }
    else {
        header('HTTP/1.0 403 Forbidden');
    }

?>