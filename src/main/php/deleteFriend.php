<?php

	require_once 'db_function.php';
	$db = new DB_Functions();
	
	// json response
	$response = array("error"=>FALSE);
	
	if(!empty($_POST['userEmail']) && !empty($_POST['friendEmail'])) {
		
		$userAccount = $db -> getUserbyEmail($_POST['userEmail']);
		$friendAccount = $db -> getUserbyEmail($_POST['friendEmail']);
		
		$result = $db -> deleteFriend($userAccount['userId'], $friendAccount['userId']);
		
		if($result) {

			$response['error'] = FALSE;
			echo json_encode($response);
		} else {
			
			$response['error'] = TRUE;
			$response['errorMsg'] = "Error deleting friend";
			echo json_encode($response);
		}
	} else {
		
		$response['error'] = TRUE;
		$response['errorMsg'] = "Critical error";
		echo json_encode($response);
	}
	
?>