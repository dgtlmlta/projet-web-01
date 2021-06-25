<?php 
	namespace XPetsIntl;

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
				} else {
					$ref = "xpets";
				}
				
				FileManager::redirect($ref);
			} else {
				echo "Nope";
			}
		}
		
	}


?>