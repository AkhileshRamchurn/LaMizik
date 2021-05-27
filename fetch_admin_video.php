<?php
    session_start();

    if($_SERVER['REQUEST_METHOD'] == 'POST') {  // check if admin is accessing!!!!

        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $start = test_input($_POST['start']);
        $limit = test_input($_POST['limit']);
        $videoType = test_input($_POST['videoType']);
        $videoStatus = test_input($_POST['videoStatus']);
        $sortValue = test_input($_POST['sortValue']);

        if ((empty($start) && $start != 0) || empty($limit) || empty($videoType) || empty($videoStatus) || empty($sortValue)) {
            //echo "unexpected_error";
        }
        else {
            require_once 'dbconnect.php';
            $desc = "";
            switch($sortValue) {
                case "first" :
                {   
                    $sortValue = "Upload_Timestamp";
                    $desc = "DESC";
                    break;
                }
                case "last" :
                {
                    $sortValue = "Upload_Timestamp";
                    break;
                }
                case "titleAsc" :
                {
                    $sortValue = "Title";
                    break;
                }
                case "titleDesc" :
                {
                    $sortValue = "Title";
                    $desc = "DESC";
                    break;
                }
            }

            if ($videoType == "all") {

                $sql = "SELECT * FROM video
                        WHERE Status = ? 
                        ORDER BY $sortValue
                        $desc
                        LIMIT 15";

                $stmt = $conn->prepare($sql);
                $stmt->bindParam(1, $videoStatus,PDO::PARAM_STR);

            }
            else {

                $sql = "SELECT * FROM video
                        WHERE Status = ? AND Video_Type = ?
                        ORDER BY $sortValue
                        $desc
                        LIMIT 15";

                $stmt = $conn->prepare($sql);
                $stmt->bindParam(1, $videoStatus, PDO::PARAM_STR);
                $stmt->bindParam(2, $videoType, PDO::PARAM_STR);

            }

            $stmt->execute();

            $output = "";
            while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
                $videoId = $row['Video_ID'];
                $videoTitle = $row['Title'];

                $output .= "<div class = 'video-detail' id = $videoId>
                            <img src='video/thumbnail/$videoId"."t.jpg' width='200' height='112'>
                            <p>$videoTitle</p>
                            <button name='approve' class = 'submitBtn' value=$videoId>Approve</button>"
                            ;
                
                if ($videoStatus == "Pending") {
                    $output .= "<button name='reject' class = 'submitBtn' value=$videoId>Reject</button>
                                </div>"
                                ;
                }
                else {
                    $output .= "<button name='delete' class = 'submitBtn' value=$videoId>Delete Permanently</button>
                                </div>";
                }

            }
            echo $output;
        }
    }
    else {
        header('Location: home.php?error=unexpected_error');    //Add this error handling to home page
    }
?>