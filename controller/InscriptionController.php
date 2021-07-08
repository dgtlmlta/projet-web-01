<?php 
	namespace Stampee;

use DateTime;

class InscriptionController {
		public function index() {			
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
			$dt = new DateTime();
			$_POST["dateCreated"] = $dt->format("Y-m-d H:i:s");

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
