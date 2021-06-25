<?php

	namespace XPetsIntl;

	class SessionManager {
		static private $editors = [2];

		static public function initSession($user) {
			session_regenerate_id();

			$_SESSION["userId"] = $user->id;
			$_SESSION["userFName"] = $user->firstName;
			$_SESSION["roleId"] = $user->roleId;
			$_SESSION["fingerprint"] = SECRET_SPICE;
		}
		
		static public function isLoggedIn() {
			if(isset($_SESSION["userId"]) && $_SESSION["fingerprint"] === SECRET_SPICE) {
				return true;
			}

			return false;
		}

		static public function canEdit() {
			return self::isLoggedIn() && in_array($_SESSION["roleId"], self::$editors);
		}
	}

?>