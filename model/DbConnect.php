<?php 
	namespace Stampee;

	abstract class DbConnect {

		protected $hostname;
		protected $database;
		protected $username;
		protected $password;

		public function __construct() {
			$this->hostname = "localhost";
			$this->database = "xpets";
			$this->username = "stamper";
			$this->password = "stampino";
			
			try {
				$this->c = new \PDO("mysql:host=$this->hostname;dbname=$this->database", $this->username, $this->password);
				$this->c->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
				$this->c->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
			} catch (\PDOException $e) {
				echo 'Connection failed: ' . $e->getMessage();
			}
		}

	}

?>