<?php 

	require_once 'db_function.php';
	$db = new DB_Functions();
	
	// json response
	$response = array("error"=>FALSE);
	
	if(!empty($_POST['userWeight']) && !empty($_POST['userHeight']) && !empty($_POST['userSex']) && !empty($_POST['userDateBirth']) && !empty($_POST['userEmail'])) {
		
		$userWeight = (float) $_POST['userWeight'];
		$userHeight = (float) $_POST['userHeight'];
		$userSex = $_POST['userSex'];
		$userDateBirth = date("Y-m-d", strtotime($_POST['userDateBirth']));
		$userEmail = $_POST['userEmail'];
		
		$stats = $db -> storeBioStats($userHeight, $userWeight, $userDateBirth, $userSex); 
		if($stats) {
		
			//Insert bioStatsId to the user
			$user = $db -> updateUserBioStats($stats["statsId"], $userEmail);
			if($user) {
				$response["error"] = FALSE;
				$response["userId"] = $user["userId"];
				$response["user"]["userId"] = $user["userId"];
				$response["user"]["userName"] = $user["userName"];
				$response["user"]["userEmail"] = $user["userEmail"];
				$response["user"]["userPassword"] = $user["userPassword"];
				$response["user"]["bioStatsId"] = $user["bioStatsId"];
				$response["user"]["devParamsId"] = $user["devParamsId"];
				echo json_encode($response);
			} else {
				$response["error"] = TRUE;
				$response["errorMsg"] = "Error linking data to your account";
				echo json_encode($response);
			}
		} else {
				
			$response["error"] = TRUE;
			$response["errorMsg"] = "Error occured inserting data";
			echo json_encode($response);
		}
						
	} else {
		$response['error'] = TRUE;
		$response['errorMsg'] = "Parameters missing";
		echo json_encode($response);
	}

?>