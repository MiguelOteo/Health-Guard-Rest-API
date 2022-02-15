<?php

	require_once 'db_function.php';
	$db = new DB_Functions();
	
	// json response
	$response = array('error'=>FALSE);
	
	if(!empty($_POST['userEmail']) && !empty($_POST['userPassword'])) {
		
		$userEmail = $_POST['userEmail'];
		$userPassword = $_POST['userPassword'];
		
		$user = $db->getUserByEmailAndPassword($userEmail, $userPassword);
		
		if($user != null) {
				
			$response['error'] = FALSE;
			$response['uId'] = $user['userId'];
			$response['user']['userName'] = $user['userName'];
			$response['user']['userEmail'] = $user['userEmail'];
			$response['user']['userPassword'] = $user['userPassword'];
			$response['user']['bioStatsId'] = $user['bioStatsId'];
			echo json_encode($response);
		} else {
		
			$response['error'] = TRUE;
			$response['errorMsg'] = "Wrong email or password";
			echo json_encode($response);
		}
	} else {
		$response['error'] = TRUE;
		onse['errorMsg'] = "Parameters missing";
		echo json_encode($response);
	}

?> 