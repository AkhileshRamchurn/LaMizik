<?php
    session_start();
    require_once 'dbconnect.php';
    
    if (isset($_POST["approve"])){
        $videoId = $_POST["approve"];
        $sql = 'UPDATE video SET Status = "Approved" WHERE Video_ID = ?';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(1, $videoId,PDO::PARAM_INT);
        $stmt->execute();
    }
    else if (isset($_POST["reject"])){
        $videoId = $_POST["reject"];
        $sql = 'UPDATE video SET Status = "Rejected" WHERE Video_ID = ?';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(1, $videoId,PDO::PARAM_INT);
        $stmt->execute();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Video Uploads</title>
    
</head>
<body>
    <form action = "<?php echo $_SERVER["PHP_SELF"];?>" method = "POST">
    <?php
        $sql = 'SELECT * FROM video WHERE Status = "Pending" ORDER BY Video_ID DESC LIMIT 15';
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
            $videoId = $row['Video_ID'];
            $videoTitle = $row['Title'];

            echo '<div>';
            echo '<img src="video/thumbnail/'.$videoId.'t.jpg" width="200" height="112">';
            echo '<p>'.$videoTitle.'</p>';
            echo '<button type="submit" name="approve" value='.$videoId.'>Approve</button>';
            echo '<button type="submit" name="reject" value='.$videoId.'>Reject</button>';
            echo '</div>';
        }
    ?>
    </form>
</body>
</html>