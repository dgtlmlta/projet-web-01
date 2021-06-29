<?php 
	namespace Stampee;

	class AuthentificationController {
		public function index() {
			
			
			return TwigController::render(
				"login",
				[]
			);
		}

		public function authentifierMembre() {
			FileManager::model("UserDAO");
			
			$userDAO = new UserDAO();
			
			if($user = $userDAO->validateUser($_POST["username"], $_POST["password"])) {
				SessionManager::initSession($user);
				
				if(!empty($_SESSION["referer"])) {
					$ref = $_SESSION["referer"];
					unset($_SESSION["referer"]);
					FileManager::redirect(ltrim($ref, "/"));
				} else {
					FileManager::redirect();
				}
			} else {
				echo "Nope";
			}
		}
	}

?>