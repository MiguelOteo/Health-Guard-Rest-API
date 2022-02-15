<?php

	require_once 'db_function.php';
	$db = new DB_Functions();
	
	// json response
	$response = array("error"=>FALSE);

	if(!empty($_POST['userEmail'])) {
		
		$userAccount = $db -> getUserbyEmail($_POST['userEmail']);
		$friendsNameSteps = $db -> getFriendsCompetition($userAccount['userId']);
		if($friendsNameSteps) {
			
			$response['error'] = FALSE;
			$response['friendParams'] = $friendsNameSteps;
			echo json_encode($response);
		} else {
				
			$response['error'] = TRUE;
			$response['errorMsg'] = "No friends found";
			echo json_encode($response);	
		}
	} else {
		
		$response["error"] = TRUE;
		$response["errorMsg"] = "Critical error";
		echo json_encode($response);
	}

?>