<?php

    include 'dbconnect.php';

    $sql = 'UPDATE video SET Status = "Deleted" WHERE Video_ID = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(1, $_POST['vid_id'],PDO::PARAM_INT);
    $stmt->execute();

    header("Location: view.php?video_id=".$_POST['vid_id']);

?>