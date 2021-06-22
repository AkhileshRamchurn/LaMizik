<?php
	session_start();
	$user_id = null;
	if (isset($_SESSION["User_ID"])) {
		$user_id = $_SESSION["User_ID"];
	}

    if(isset($_SESSION["User_ID"])){
        //request from ajax
        //action is set
        if(isset($_POST['action'])){
            //alert('receiving till here');
            
            include '../dbconnect.php';
            $video_id = $_POST['video_id'];
            $action = $_POST['action'];

            try{
                $conn -> beginTransaction();

                switch($action){
                    case 'like':
                        $sql = "INSERT INTO rating (User_ID, Video_ID, Rate_Action)
                                VALUES($user_id, $video_id, 'Liked')
                                ON DUPLICATE KEY UPDATE Rate_Action ='Liked'";//perform an update instead
                            
                        break;
                    
                    case 'dislike':
                        $sql = "INSERT INTO rating (User_ID, Video_ID, Rate_Action)
                            VALUES($user_id, $video_id, 'Disliked')
                            ON DUPLICATE KEY UPDATE Rate_Action ='Disliked'";//perform an update instead
                        
                        break;
                    case 'unlike':
                        $sql ="DELETE FROM rating WHERE User_ID=$user_id AND Video_ID = $video_id";					
                        break;
                    case 'undislike':
                        $sql ="DELETE FROM rating WHERE User_ID=$user_id AND Video_ID = $video_id";					
                        break;	
                    default:
                        break;	
    
                }
    
                //execute query to effect changes in the database
                $stmt = $conn -> prepare($sql); 
                $stmt ->execute();

                $conn -> commit();

            }catch(PDOException $exception){
                $conn->rollBack();
                echo 'ERROR: '.$exception->getMessage();
            }
            
            
            //Get total number of likes and dislikes for a particular video
            /*Calculate likes and dislke....Then return back in JSON format */
            $rating = array();

            $likes_query = "SELECT COUNT(*) FROM rating WHERE Video_ID = $video_id AND Rate_Action='Liked'";
            $dislikes_query = "SELECT COUNT(*) FROM rating WHERE Video_ID = $video_id AND Rate_Action='Disliked'";

            $likes_rs = $conn -> prepare($likes_query); 
            $dislikes_rs = $conn -> prepare($dislikes_query); 

            $likes_rs ->execute();
            $dislikes_rs ->execute();

            $likes = $likes_rs->fetchColumn();
            $dislikes = $dislikes_rs->fetchColumn();

            $rating = [
            'likes' => $likes,
            'dislikes' => $dislikes
            ];


            echo json_encode($rating);

        }
		
	}

	
?>