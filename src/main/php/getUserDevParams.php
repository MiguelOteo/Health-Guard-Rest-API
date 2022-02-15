<?php

	require_once 'db_function.php';
	$db = new DB_Functions();
	
	// json response
	$response = array("error"=>FALSE);

	if(!empty($_POST['userEmail'])) {
	
		$userAccount = $db -> getUserbyEmail($_POST['userEmail']);
		$userParams = $db -> getUserParams($userAccount['userId']);
		if($userParams) {
			
			$response['error'] = FALSE;
			$response['params'] = $userParams;
			echo json_encode($response);
		} else {
				
			$response['error'] = TRUE;
			$response['errorMsg'] = "No params found";
			echo json_encode($response);	
		}
		
	} else {
		
		$response["error"] = TRUE;
		$response["errorMsg"] = "Critical error";
		echo json_encode($response);
	}
	
?>