<?php

	class DB_Functions {
		
		private $conn;
		
		// Constructor
		function __construct() {
			
			require_once 'db_connect.php';
			$db = new DB_Connect();
			$this->conn = $db->connect();
		}
		
		// Destructor
		function __destruct() {
		
			//TODO
		}
		
		//Stores new user
		//Return user details
		public function storeUser($userName, $userEmail, $userPassword) {
						
			//Encryption of the password
			
				//$hash = $this->hashSSHA($password);
				//$encryptedPassword = $hash["encrypted"];
			
				//Salt must be stored in the Users tables
				//$salt = $hash["salt"];
			
			$stmt = $this->conn->prepare("INSERT INTO Users (userName, userEmail, userPassword) VALUES (?, ?, ?);");
			$stmt->bind_param("sss", $userName, $userEmail, $userPassword);
			$result = $stmt->execute();
			$stmt->close();
			
			if($result) {
				$stmt = $this->conn->prepare("SELECT * FROM Users WHERE userEmail = ?;");
				$stmt->bind_param("s", $userEmail);
				$stmt->execute();
				$user = $stmt->get_result()->fetch_assoc();
				$stmt->close();
			
				$stmt = $this->conn->prepare("INSERT INTO deviceParams (paramsId) VALUES (?);");
				$stmt->bind_param("s", $user['userId']);
				$stmt->execute();
				$stmt->close();
			
				$stmt = $this->conn->prepare("UPDATE Users SET devParamsId = ? WHERE userEmail = ?;");
				$stmt->bind_param("ss", $user["userId"], $userEmail);
				$stmt->execute();
				$stmt->close();
			
				$user["devParamsId"] = $user["userId"];
				return $user;
			} else {
				
				return false;
			}
		}
		
		//Search user by email and password
		//Returns user dobject
		public function getUserByEmailAndPassword($userEmail, $userPassword) {
		
			$stmt = $this->conn->prepare("SELECT * FROM users WHERE userEmail = ?");
			$stmt->bind_param("s", $userEmail);
			$stmt->execute();
			$user = $stmt->get_result()->fetch_assoc();
			$stmt->close();
			
			if($user != null) {
				
				$password = $user['userPassword'];
				
				if($userPassword == $password) {
					
					return $user;
				} else {
					
					return null;
				}
			} else {
				return null;
			}
		}
		
		//Stores new bioStats
		//Returns boolean expression
		public function isUserExisted($userEmail) {
		
			$stmt = $this->conn->prepare("SELECT userEmail FROM Users WHERE userEmail = ?");
			$stmt->bind_param("s", $userEmail);
			$stmt->execute();
			$stmt->store_result();
			
			if($stmt->num_rows > 0) {
				
				$stmt->close();
				return true;
			} else {
				
				$stmt->close();
				return false;
			}
		}
		
		// Insert a new bio_stats item into the table
		// Returns the bioStats object
		public function storeBioStats($userHeight, $userWeight, $userDateBirth, $userSex) {
			
			$stmt = $this->conn->prepare("INSERT INTO healthstats (userHeight, userWeight, userDateBirth, userSex) VALUES (?, ?, ?, ?);");
			$stmt->bind_param("ssss", $userHeight, $userWeight, $userDateBirth, $userSex);
			$result = $stmt->execute();
			$stmt->close();
			
			if($result) {
				
				$lastStatsId = $this->conn->insert_id;
				
				$stmt = $this->conn->prepare("SELECT * FROM healthstats WHERE statsId = ?");
				$stmt->bind_param("s", $lastStatsId);
				$stmt->execute();
				$bioStats = $stmt->get_result()->fetch_assoc();
				$stmt->close();
			
				return $bioStats;
			} else {
				
				return false;
			}
		}
		
		public function updateUserBioStats($bioStatsId, $userEmail) {
			
			$stmt = $this->conn->prepare("UPDATE users SET bioStatsId = ? WHERE userEmail = ?");
			$stmt->bind_param("ss", $bioStatsId, $userEmail);
			$stmt->execute();
			
			if($stmt) {
				
				$stmt->close();
				$user = $this -> getUserbyEmail($userEmail);
				return $user;
			} else {
				
				$stmt->close();
				return false;
			}
		}
		
		public function registerNewFriend($userId, $friendId) {
			
			$stmt = $this->conn->prepare("INSERT INTO friendslist (userId, friendId) VALUES (?,?);");
			$stmt->bind_param("ss", $userId, $friendId);
			$stmt->execute();
			
			if($stmt) {
				
				$stmt->close();
				return true;
			} else {
				
				$stmt->close();
				return false;
			}
		}
		
		public function searchUserById($userId) {
			
			$stmt = $this->conn->prepare("SELECT * FROM users WHERE userId = ?");
			$stmt->bind_param("s", $userId);
			$stmt->execute();
			$user = $stmt->get_result()->fetch_assoc();
			$stmt->close();
			
			if($user != null) {
				
				return $user;
			} else {
				
				return null;
			}
		}
		
		public function searchFriendship($userId, $friendId) {
			
			$stmt = $this->conn->prepare("SELECT joinId FROM friendslist WHERE userId = ? AND friendId = ?;");
			$stmt->bind_param("ss", $userId, $friendId);
			$stmt->execute();
			$friendshipExist = $stmt->get_result()->fetch_assoc();
			$stmt->close();
			
			if($friendshipExist) {

				return TRUE;
			} else {
				
				return FALSE;
			}
		}
		
		public function searchAllFriends($userId) {
			
			$stmt = "SELECT friendId FROM friendslist WHERE userId = $userId";
			$usersIdList = mysqli_query($this->conn, $stmt);
			
			if($usersIdList != null) {
				
				$usersList = array();
				while($userRow = mysqli_fetch_assoc($usersIdList)){
					$usersList[] = $this->searchUserById($userRow['friendId']);
				}	
				return $usersList;
			} else {
				
				return null;
			}
		}
		
		public function getUserbyEmail($userEmail) {
			
			$stmt = $this->conn->prepare("SELECT * FROM users WHERE userEmail = ?");
			$stmt->bind_param("s", $userEmail);
			$stmt->execute();
			$user = $stmt->get_result()->fetch_assoc();
			$stmt->close();
			
			if($user != null) {
				
				return $user;
			} else {
				
				return null;
			}
		}
		
		public function updateUserName($userAccount) {
			
			$stmt = $this->conn->prepare("UPDATE users SET userName = ? WHERE userId = ?;");
			$stmt->bind_param("ss", $userAccount['userName'], $userAccount['userId']);
			$stmt->execute();
			
			if($stmt) {
				$stmt->close();
				return TRUE;
			} else {
				$stmt->close();
				return FALSE;
			}
		}
		
		public function updateUserPassword($userAccount){
			
			$stmt = $this->conn->prepare("UPDATE users SET userPassword = ? WHERE userId = ?;");
			$stmt->bind_param("ss", $userAccount['userPassword'], $userAccount['userId']);
			$stmt->execute();
			
			if($stmt) {
				$stmt->close();
				return TRUE;
			} else {
				$stmt->close();
				return FALSE;;
			}
		}
		
		public function deleteFriend($userId, $friendId) {
			
			$stmt = $this->conn->prepare("DELETE FROM friendslist WHERE userId = ? AND friendId = ?;");
			$stmt->bind_param("ss", $userId, $friendId);
			$stmt->execute();
			
			if($stmt) {
				$stmt->close();
				return TRUE;
			} else {
				$stmt->close();
				return FALSE;;
			}
		}
		
		public function getFriendsCompetition($userId) {
			
			$stmt = "SELECT friendId FROM friendslist WHERE userId = $userId";
			$usersIdList = mysqli_query($this->conn, $stmt);
			
			if($usersIdList != null) {
				
				$friendsList = array();
				while($userRow = mysqli_fetch_assoc($usersIdList)){
					$friendsList[] = $this->searchUserById($userRow['friendId']);
				}	
				
				$friendsNameSteps = array();
				$count_var = 0;
				foreach($friendsList as &$friend){
					$friendsNameSteps[] = $this -> searchNameSteps($friend, $count_var);
					$count_var++;
				}
				return $friendsNameSteps;
			} else {
				
				return null;
			}
		}
		
		public function searchNameSteps($friendUser, $count_var) {
				
			$stmt = $this->conn-> prepare("SELECT steps FROM deviceParams WHERE paramsId = ?");
			$stmt->bind_param("s", $friendUser['devParamsId']);
			$stmt->execute();
			$steps = $stmt->get_result()->fetch_assoc();
			$stmt->close();
			if($steps) {
				$friendNameSteps = (object) array('joinId' => $count_var,'friendName' => $friendUser['userName'], 'steps' => $steps['steps']);
			} else {
				return null;
			}
			return $friendNameSteps;
		}
		
		public function getUserParams($userId) {
			
			$stmt = $this->conn-> prepare("SELECT * FROM deviceParams WHERE paramsId = ?");
			$stmt->bind_param("s", $userId);
			$stmt->execute();
			$params = $stmt->get_result()->fetch_assoc();
			$stmt->close();
			
			if($stmt) {
		
				return $params;
			} else {

				return null;
			}
		}
		
		public function getUserBioStats($bioStatsId) {
			
			$stmt = $this->conn-> prepare("SELECT * FROM healthstats WHERE statsId = ?");
			$stmt->bind_param("s", $bioStatsId);
			$stmt->execute();
			$bioStats = $stmt->get_result()->fetch_assoc();
			$stmt->close();
			
			if($stmt) {
		
				return $bioStats;
			} else {

				return null;
			}
		}
	}

?>