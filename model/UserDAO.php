<?php

	namespace XPetsIntl;

	class UserDAO extends Gateway {
		protected $table = "user";

		public function addUser($data) {

		}

		private function checkUserExist($user) {
			$stmt = $this->prepareStmt(
				"SELECT *
				 from $this->table
				 where username = :username"
			);

			$stmt->execute(array(":username" => $user));

			return ($stmt->rowCount() == 1) ? $stmt->fetch() : false;				 
		}

		public function validateUser($u, $p) {
			FileManager::lib("PasswordCrypt");
			
			if($user = $this->checkUserExist($u)) {
				return (PasswordCrypt::checkPassword($p, $user->password)) ?
					$user :
					false;
			};

			return false;
		}
		
	}

?>