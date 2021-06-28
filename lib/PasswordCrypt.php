<?php 

	namespace Stampee;

	class PasswordCrypt {

		static public function hashPassword($pass) {
			$opts = [
				"count" => 12
			];

			$hashed = password_hash($pass, PASSWORD_BCRYPT, $opts);

			return $hashed;
		}


		static function checkPassword($pass, $storedPass) {
			return password_verify($pass, $storedPass);
		}
	}

?>