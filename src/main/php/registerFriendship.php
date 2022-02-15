<?php 

	require_once 'db_function.php';
	$db = new DB_Functions();
	
	// json response
	$response = array("error"=>FALSE);
	
	if(!empty($_POST['userEmail']) && !empty($_POST['friendEmail'])) {
		
		$userEmail = $_POST['userEmail'];
		$friendEmail = $_POST['friendEmail'];
		
		$userAccount = $db -> getUserbyEmail($userEmail);
		$friendAccount = $db -> getUserbyEmail($friendEmail);
		
		if(!empty($userAccount) && !empty($friendAccount)) {
			
			$result = $db -> searchFriendship($userAccount['userId'], $friendAccount['userId']);
			if($result == FALSE) {
			
				$result = $db -> registerNewFriend($userAccount['userId'], $friendAccount['userId']); 
				if($result) {
			
					$response["error"] = FALSE;
					echo json_encode($response);
				} else {
			
					$response["error"] = TRUE;
					$response["errorMsg"] = "Error inserting new friend";
					echo json_encode($response);
				}
			} else {
		
				$response["error"] = TRUE;
				$response["errorMsg"] = "Already your friend";
				echo json_encode($response);
			}	
		} else {
			
			$response["error"] = TRUE;
			$response["errorMsg"] = "Friend's account not found";
			echo json_encode($response);
		}
	} else {
			
		$response["error"] = TRUE;
		$response['errorMsg'] = "Parameters missing";
		echo json_encode($response);
	}
	
?>