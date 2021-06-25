<?php

    session_start();
    require 'dbconnect.php';

    $video_limit=8;

    if (!empty($_GET['user_id'])) {
        $query = "SELECT User_ID FROM user WHERE User_Type = 'NormalUser' AND User_ID = ".$_GET['user_id'];
    }
    else {
        $query = "SELECT User_ID FROM user WHERE User_Type = 'NormalUser'";
    }

    $result = $conn->query($query);
    $user_array = $result->fetchAll(PDO::FETCH_ASSOC);

    // print_r($user_array);

    for($i=0; $i<count($user_array); $i++)
	{
		$user_id = $user_array[$i]['User_ID'];

        $similarity_json = file_get_contents("http://localhost/Lamizik/similarity.php?user_id=".$user_id);
        $similarity_obj = json_decode($similarity_json, true);

        //finding all videos that the main user has not watched but at least one of the similar users have watched
        $condition_simUser = '(';
        for($i=0; $i<count($similarity_obj[0]['Other_Users']); $i++)
		{
            if ($i == 0) {
                $condition_simUser .= $similarity_obj[0]['Other_Users'][$i]['User_ID'];
            }
            else {
                $condition_simUser = $condition_simUser.", ".$similarity_obj[0]['Other_Users'][$i]['User_ID'];
            }
		}
        $condition_simUser .= ")";
		
		$sql1 = "(SELECT Video_ID FROM views WHERE User_ID IN $condition_simUser GROUP BY Video_ID)";
        $sql2 = "(SELECT Video_ID FROM views WHERE User_ID = $user_id GROUP BY Video_ID)";

        $sql3 = "(SELECT a.Video_ID FROM $sql1 a, $sql2 b WHERE a.Video_ID != b.Video_ID GROUP BY a.Video_ID)";
        
        $sqlfinal ="SELECT  video.Video_ID, Title, Description, Video_Type, Status, UNIX_TIMESTAMP(Upload_Timestamp) AS Upload_Timestamp, user.Username FROM $sql3 a, video, user WHERE video.Video_ID = a.Video_ID AND video.User_ID = user.User_ID";


		$result2 = $conn->query($sqlfinal);
        $uncommon_video_array = $result2->fetchAll(PDO::FETCH_ASSOC);
      
       
        //calculating the predicted score of each video returned
    
        for($i=0;$i<count($uncommon_video_array); $i++){
            $unwatched_videoid=$uncommon_video_array[$i]['Video_ID'];
            $video_weightmean=0;
            $total_video_weightmean=0;
            $total_similairty=0;
            
            for($j=0;$j<count($similarity_obj[0]['Other_Users']); $j++){
               

                $sim_userid =$similarity_obj[0]['Other_Users'][$j]['User_ID'];
                // echo $sim_userid.' ';
                $query1 = "SELECT * FROM views WHERE USER_ID=$sim_userid AND Video_ID=$unwatched_videoid";
                $result3 = $conn ->query($query1);

                $unwatched_videoViewed = $result3->fetchAll(PDO::FETCH_ASSOC);

                $video_score;
                if(count($unwatched_videoViewed)){//user has not viewed the video
                    $video_score=1;

                }
                else{//user has viewed the video
                    $query1= "SELECT Rate_Action FROM rating WHERE Video_ID =$unwatched_videoid  AND User_ID = ".$sim_userid;
                    $result4 = $conn->query($query1);
                    $videoRating =$result4->fetchAll(PDO::FETCH_ASSOC);

                    
                    if (count($videoRating) == 0) {
                        $video_score = 2;
                    }
                    else {
                        if($$videoRating[0]['Rate_Action'] == "Disliked") {
                            $video_score = 0;
                        }
                        else if($$videoRating[0]['Rate_Action'] == "Liked") {
                            $video_score = 3;
                        }
                    }

                }
               
                $total_video_weightmean += $similarity_obj[0]['Other_Users'][$j]['Similarity'] * $video_score;
                $total_similairty+=$similarity_obj[0]['Other_Users'][$j]['Similarity'];
            }
            $video_weightmean = $total_video_weightmean/$total_similairty;
            $uncommon_video_array[$i]['video_weightmean'] = $video_weightmean;

            $views_query ="SELECT count(*) AS numOfViews FROM views WHERE Video_ID=$unwatched_videoid";
            $result5 = $conn -> query($views_query);
            $video_views= $result5->fetchAll(PDO::FETCH_ASSOC);

            $uncommon_video_array[$i]['views'] = $video_views[0]['numOfViews'];
          
        }
        
	
	}


  
    usort($uncommon_video_array, function ($item1, $item2) {
        return $item2['video_weightmean'] <=> $item1['video_weightmean'];
    });

    // Getting the top n videos only
     $uncommon_video_array = array_slice($uncommon_video_array, 0, $video_limit);


    $jsonData = json_encode($uncommon_video_array, JSON_NUMERIC_CHECK);
    $jsonData = json_encode($uncommon_video_array, JSON_PRETTY_PRINT);


    header('Content-Type: application/json'); 
    echo $jsonData; 

?>