<?php 

	/*Stablish connection to Database */
	
	class DB_Connect {
		
		private $conn;
		
		/* Connecting to DB */
		public function connect() {
			
			require_once 'config.php';
			$this->conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
			return $this->conn;
		}
	}

?>