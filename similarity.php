<?php

    require 'dbconnect.php';
    $k = 5; //Number of most similar users to return

    if (!empty($_GET['user_id'])) {
        $query = "SELECT User_ID FROM user WHERE User_Type != 'Admin' AND User_ID = ".$_GET['user_id'];
    }
    else {
        $query = "SELECT User_ID FROM user WHERE User_Type != 'Admin'";
    }

    $result = $conn->query($query);
    $user_array = $result->fetchAll(PDO::FETCH_ASSOC);

    for($i=0; $i<count($user_array); $i++)
	{
		$user_id = $user_array[$i]['User_ID'];
		
		$innerquery = "SELECT User_ID FROM user WHERE User_ID != $user_id AND User_Type != 'Admin'";
		
		$result2 = $conn->query($innerquery);
		$user_array[$i]['Other_Users'] = $result2->fetchAll(PDO::FETCH_ASSOC);

		for($j=0; $j<count($user_array[$i]['Other_Users']); $j++)
		{
            //Calculating Similarity-----------------------------------------------------------------------------------------------------
			$user_id2 = $user_array[$i]['Other_Users'][$j]['User_ID'];

			$sql1 =  "SELECT a.Video_ID, b.View_ID FROM
                    (SELECT * FROM views WHERE User_ID = $user_id) a
                    LEFT JOIN (SELECT * FROM views WHERE User_ID = $user_id2) b
                    ON a.Video_ID = b.Video_ID";

			$result3 = $conn->query($sql1);
            $matchingViews = $result3->fetchAll(PDO::FETCH_ASSOC);

            if (count($matchingViews) == 0) {
                $similarity = 0;
            }
            else {
                $sumDistSq = 0;
                foreach($matchingViews as $row)
                {
                    $sql2 = "SELECT Rate_Action FROM rating WHERE Video_ID = ".$row['Video_ID']." AND User_ID = ".$user_id;
                    $result4 = $conn->query($sql2);
                    $ratingUser1SQL = $result4->fetchAll(PDO::FETCH_ASSOC);
                    
                    //User 1 has viewed the video but has not rated it
                    if (count($ratingUser1SQL) == 0) {
                        $ratingUser1 = 2;
                    }
                    else {
                        //User 1 has disliked the video
                        if($ratingUser1SQL[0]['Rate_Action'] == "Disliked") {
                            $ratingUser1 = 0;
                        }
                        //User 2 has liked the video
                        else if($ratingUser1SQL[0]['Rate_Action'] == "Liked") {
                            $ratingUser1 = 3;
                        }
                    }



                    //User 2 has not viewed the video
                    if ($row['View_ID'] == NULL) {
                        $ratingUser2 = 1;
                    }
                    else {
                        $sql3 = "SELECT Rate_Action FROM rating WHERE Video_ID = ".$row['Video_ID']." AND User_ID = ".$user_id2;
                        $result5 = $conn->query($sql3);
                        $ratingUser2SQL = $result5->fetchAll(PDO::FETCH_ASSOC);
                        
                        //User 2 has viewed the video but has not rated it
                        if (count($ratingUser2SQL) == 0) {
                            $ratingUser2 = 2;
                        }
                        else {
                            //User 2 has disliked the video
                            if($ratingUser2SQL[0]['Rate_Action'] == "Disliked") {
                                $ratingUser2 = 0;
                            }
                            //User 2 has liked the video
                            else if($ratingUser2SQL[0]['Rate_Action'] == "Liked") {
                                $ratingUser2 = 3;
                            }
                        }
                    }
                    $sumDistSq += pow(($ratingUser2 - $ratingUser1), 2);
                }
                $EuclDistance = sqrt($sumDistSq);

                if ($EuclDistance == 0) {
                    $similarity = 1;
                }
                else {
                    $similarity = 1/$EuclDistance;
                }
            }
            // Calculating Similarity END-----------------------------------------------------------------------------------------------------
            
			$user_array[$i]['Other_Users'][$j]['Similarity'] = $similarity;
		}
	}

    // Sorting similarity in descending order
    for($i=0; $i<count($user_array); $i++)
	{
        usort($user_array[$i]['Other_Users'], function ($item1, $item2) {
            return $item2['Similarity'] <=> $item1['Similarity'];
        });

        // Getting the top K similar users only
        $user_array[$i]['Other_Users'] = array_slice($user_array[$i]['Other_Users'], 0, $k);
    }

    // $jsonData = json_encode($user_array, JSON_NUMERIC_CHECK);
    $jsonData = json_encode($user_array, JSON_PRETTY_PRINT);

    header('Content-Type: application/json'); 
    echo $jsonData; 

?>