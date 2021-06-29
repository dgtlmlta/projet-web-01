<?php

	namespace Stampee;

	class SessionManager {
		static private $adminId = 88;
		static private $memberId = 22;

		static public function initSession($user) {
			session_regenerate_id();

			$_SESSION["userId"] = $user->id;
			$_SESSION["userFName"] = $user->firstName;
			$_SESSION["roleId"] = $user->roleId;
			$_SESSION["fingerprint"] = SECRET_SPICE;

			var_dump($_SESSION);
		}
		
		static public function isLoggedIn() {
			return 
				isset($_SESSION["userId"])
				&& $_SESSION["fingerprint"] === SECRET_SPICE;
		}

		static public function isAdmin() {
			return self::isLoggedIn() && $_SESSION["roleId"] == self::$adminId;
		}

		static public function isMember() {
			return self::isLoggedIn() && $_SESSION["roleId"] == self::$memberId;
		}
	}

?>