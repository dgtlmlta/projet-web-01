<?php 
	namespace Stampee;


	class MembreController {
		public function authentification() {
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
				
				if(empty($_SESSION["referer"]))
					FileManager::redirect();
				
				$ref = $_SESSION["referer"];
				unset($_SESSION["referer"]);
				FileManager::redirect(ltrim($ref, "/"));
				
			} else {
				echo "Nope";
			}
		}

		public function inscription() {
			return TwigController::render(
				"signup",
				[]
			);
		}

		public function enregistrerMembre() {
			FileManager::model("UserDAO");

			$userDAO = new UserDAO();
			
			FileManager::lib("PasswordCrypt");
				
			// Ajouter les colonnes "calculées" au POST
			$_POST["username"] = $_POST["email"];
			
			// Encrypter le mot de passe.
			$_POST["password"] = PasswordCrypt::hashPassword($_POST["password"]);

			// Par défaut, les nouvelles inscriptions reçoivent les privilèges d'un membre de base.
			$_POST["roleId"] = 22;

			// Générer le datetime de création
			$_POST["dateCreated"] = getCurrentSQLDatetime();

			if(is_array($result = $userDAO->insert($_POST))) {
				return TwigController::render(
					"signup",
					[
						// Erreurs
						"hasError" => true,
						"errors" => $result
					]
				);				
			}

			FileManager::redirect("authentification");
		}
	}

?>