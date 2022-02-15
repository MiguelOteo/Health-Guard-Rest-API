<?php

	require_once 'db_function.php';
	$db = new DB_Functions();
	
	// json response
	$response = array("error"=>FALSE);
	
	if(!empty($_POST['userEmail']) && !empty($_POST['oldPassword']) && !empty($_POST['newPassRepeat']) && !empty($_POST['newPassword'])) {
		
		
		if($_POST['newPassword'] == $_POST['newPassRepeat']) {
			
			$userEmail = $_POST['userEmail'];
			$userAccount = $db -> getUserbyEmail($userEmail);
			
			if($userAccount['userPassword'] == $_POST['oldPassword']) {
				
				$userAccount['userPassword'] = $_POST['newPassword'];
				
				$result = $db -> updateUserPassword($userAccount);
				if($result == TRUE) {
		
					$response["error"] = FALSE;
					$response["userId"] = $userAccount["userId"];
					$response["user"]["userId"] = $userAccount["userId"];
					$response["user"]["userName"] = $userAccount["userName"];
					$response["user"]["userEmail"] = $userAccount["userEmail"];
					$response["user"]["userPassword"] = $userAccount["userPassword"];
					echo json_encode($response);
				} else {
			
					$response["error"] = TRUE;
					$response["errorMsg"] = "Error changing password";
					echo json_encode($response);
				}
			} else {
			
				$response['error'] = TRUE;
				$response['errorMsg'] = "Old password is not correct";
				echo json_encode($response);
			}
		} else {
			
			$response['error'] = TRUE;
			$response['errorMsg'] = "Password must be the same";
			echo json_encode($response);
		}	
	} else {
		
		$response['error'] = TRUE;
		$response['errorMsg'] = "Parameters missing";
		echo json_encode($response);
	}	
?>