<?php

	namespace Stampee;

	class SessionManager {
		const ADMIN_ID = 88;
		const MEMBER_ID = 22;
		
		static private $auctionEditors = [
			88,
			22
		];

		static public function initSession($user) {
			session_regenerate_id();

			$_SESSION["userId"] = $user->id;
			$_SESSION["userFName"] = $user->firstName;
			$_SESSION["roleId"] = $user->roleId;
			$_SESSION["fingerprint"] = SECRET_SPICE;
		}
		
		static public function isLoggedIn() {
			return 
				isset($_SESSION["userId"])
				&& $_SESSION["fingerprint"] === SECRET_SPICE;
		}

		static public function isAdmin() {
			return self::isLoggedIn() && $_SESSION["roleId"] == self::ADMIN_ID;
		}

		static public function isMember() {
			return self::isLoggedIn() && $_SESSION["roleId"] == self::MEMBER_ID;
		}
		
		static public function canEditAuctions() {
			return self::isLoggedIn() && in_array($_SESSION["roleId"], self::$auctionEditors);
		}

	}

?>