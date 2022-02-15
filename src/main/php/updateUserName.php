<?php

	require_once 'db_function.php';
	$db = new DB_Functions();
	
	// json response
	$response = array("error"=>FALSE);
	
	if(!empty($_POST['userEmail']) && !empty($_POST['userName'])) {
		
		$userEmail = $_POST['userEmail'];
		$userAccount = $db -> getUserbyEmail($userEmail);
		
		$userAccount['userName'] = $_POST['userName'];
		
		$result = $db -> updateUserName($userAccount);
		if($result == TRUE) {
		
			$response["error"]=FALSE;
			$response["userId"] = $userAccount["userId"];
			$response["user"]["userId"] = $userAccount["userId"];
			$response["user"]["userName"] = $userAccount["userName"];
			$response["user"]["userEmail"] = $userAccount["userEmail"];
			$response["user"]["userPassword"] = $userAccount["userPassword"];
			echo json_encode($response);
		} else {
			
			$response["error"] = TRUE;
			$response["errorMsg"] = "Error updating user name";
			echo json_encode($response);
		}
	} else {
		
		$response['error'] = TRUE;
		$response['errorMsg'] = "Parameters missing";
		echo json_encode($response);
	}
	
?>