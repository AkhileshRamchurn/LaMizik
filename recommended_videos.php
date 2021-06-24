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

        $sql = "SELECT a.Video_ID FROM $sql1 a, $sql2 b WHERE a.Video_ID != b.Video_ID";

		$result2 = $conn->query($sql);
        $uncommon_video_array = $result2->fetchAll(PDO::FETCH_ASSOC);

        //calculating the predicted score of each video returned
        for($i=0; $i<count($similarity_obj[0]['Other_Users']); $i++)
	    {

        }


		// $user_array[$i]['Videos'] = $result3->fetchAll(PDO::FETCH_ASSOC);

		
	}
/*
    // Sorting the videos based on score in descending order
    for($i=0; $i<count($user_array); $i++)
	{
        usort($user_array[$i]['Videos'], function ($item1, $item2) {
            return $item2['Score'] <=> $item1['Score'];
        });

        // Getting the top n videos only
        $user_array[$i]['Videos'] = array_slice($user_array[$i]['Videos'], 0, $video_limit);
    }

    // $jsonData = json_encode($user_array, JSON_NUMERIC_CHECK);
    $jsonData = json_encode($user_array, JSON_PRETTY_PRINT);

    header('Content-Type: application/json'); 
    echo $jsonData; 
*/
?>