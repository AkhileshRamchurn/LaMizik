<?php
    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $videoId = test_input($_POST['videoId']);
        $start = test_input($_POST['start']);
        $limit = test_input($_POST['limit']);
        $sortValue = test_input($_POST['sortValue']);
        
        if (empty($videoId) || (empty($start) && $start != 0) || empty($limit) || empty($sortValue)) {
            //echo "unexpected_error";
        }
        else {
            require 'dbconnect.php';

            if ($sortValue == "first") {
                $sortValue = "DESC";
            }
            else {
                $sortValue = "";
            }
            $sql = "SELECT Username, Comment_Description, Comment_Timestamp
                    FROM comment, user 
                    WHERE Video_ID = ? AND user.User_ID = comment.User_ID 
                    ORDER BY Comment_Timestamp $sortValue
                    LIMIT ?, ?";

            $result = $conn->prepare($sql);
            $result->bindParam(1, $videoId,PDO::PARAM_INT);
            $result->bindParam(2, $start,PDO::PARAM_INT);
            $result->bindParam(3, $limit,PDO::PARAM_INT);
            $result->execute();

            if ($result->rowCount() > 0) {
                $output = "";
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $output .= "<div class='main-comment'>
                                    <div class='comment-header'>
                                        <p class = 'username'>".$row['Username']."</p>
                                        <p class = 'time'>".$row['Comment_Timestamp']."</p>
                                    </div>
                                    <div class='comment-description'>
                                        <p>".$row['Comment_Description']."</p>
                                    </div>
                                </div>"
                                ;
                }
                echo $output;
            }
        }
    }
    else {
        header('Location: home.php?error=unexpected_error');    //Add this error handling to home page
    }

?>