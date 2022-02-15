<?php

	require_once 'db_function.php';
	$db = new DB_Functions();
	
	// json response
	$response = array("error"=>FALSE);
	
	if(!empty($_POST['userName']) && !empty($_POST['userEmail']) && !empty($_POST['userPassword']) && !empty($_POST['userPasswordRepeat'])) {
	
		$userName =  $_POST['userName'];
		$userEmail = $_POST['userEmail'];
		$userPassword = $_POST['userPassword'];
		$userPasswordRepeat = $_POST['userPasswordRepeat'];
		
		if($userPassword == $userPasswordRepeat) {
			
			if($db->isUserExisted($userEmail)) {
			
				$response["error"] = TRUE;
				$response["errorMsg"] = "Email already used";
				echo json_encode($response);
			} else {
			
				$user = $db -> storeUser($userName, $userEmail, $userPassword); 
				if($user) {
				
					$response["error"]=FALSE;
					$response["userId"] = $user["userId"];
					$response["user"]["userId"] = $user["userId"];
					$response["user"]["userName"] = $user["userName"];
					$response["user"]["userEmail"] = $user["userEmail"];
					$response["user"]["userPassword"] = $user["userPassword"];
					$response["user"]["devParamsId"] = $user["devParamsId"];
					echo json_encode($response);
				} else {
				
					$response["error"] = TRUE;
					$response["errorMsg"] = "Critical error occurred";
					echo json_encode($response);
				}
			}	
		} else {
			
			$response["error"] = TRUE;
			$response["errorMsg"] = "Password is not the same";
			echo json_encode($response);
		}	
	} else {
		$response['error'] = TRUE;
		$response['errorMsg'] = "Parameters missing";
		echo json_encode($response);
	}	
?>