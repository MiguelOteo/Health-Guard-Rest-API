<?php

	require_once 'db_function.php';
	$db = new DB_Functions();
	
	// json response
	$response = array('error'=>FALSE);
	
	if(!empty($_POST['userEmail'])) {
		
		$userAccount = $db -> getUserbyEmail($_POST['userEmail']);
		
		if($userAccount) {
			
			$userParams = $db -> getUserParams($userAccount['userId']);
			$userBioStats = $db -> getUserBioStats($userAccount['bioStatsId']);
		
			if($userParams != null && $userBioStats != null) {
			
				$response["error"] = FALSE;
				$response["bioStats"]["userWeight"] = $userBioStats["userWeight"];
				$response["bioStats"]["userHeight"] = $userBioStats["userHeight"];
				$response["bioStats"]["userSex"] = $userBioStats["userSex"];
				
				$response["params"]["steps"] = $userParams["steps"];
				$response["params"]["saturation"] = $userParams["saturation"];
				$response["params"]["bpm"] = $userParams["bpm"];
				$response["params"]["sleepHours"] = $userParams["sleepHours"];
				$response["params"]["sweatAmount"] = $userParams["sweatAmount"];
				echo json_encode($response);
			
			} else {
			
				$response["error"] = TRUE;
				$response["errorMsg"] = "Error loading data";
				echo json_encode($response);
			}
		} else {
		
		$response["error"] = TRUE;
		$response["errorMsg"] = "Account error occurred";
		echo json_encode($response);
		}
	} else {
		
		$response["error"] = TRUE;
		$response["errorMsg"] = "Critical error";
		echo json_encode($response);
	}
?>