<?php 
	namespace Stampee;

	FileManager::model("Gateway");
	FileManager::model("UserDAO");

	class LoginController {
		
		public function index() {
			return TwigController::render(
				"login-formulaire",
				[
					"pageTitle" => "X-Pets :: Authentification",
					"pageDesc" => "Bâtir un panneau administratif pour les différents types d'usagers"
				]
			);
		}
		
		public function authenticateUser() {
			$userDAO = new UserDAO();
			
			if($user = $userDAO->validateUser($_POST["username"], $_POST["password"])) {
				SessionManager::initSession($user);
				
				if(!empty($_SESSION["referer"])) {
					$ref = $_SESSION["referer"];
					unset($_SESSION["referer"]);
					FileManager::redirect($ref);
				} else {
					FileManager::redirect();
				}
			} else {
				echo "Nope";
			}
		}
		
	}


?>