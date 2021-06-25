<?php 

	namespace XPetsIntl;

	FileManager::model("Gateway");
	FileManager::model("UserDAO");
	FileManager::model("RoleDAO");
	
	class UserController {
		private $controllerSlug = "user";

		public function index($id = null, $slug = null) {
			// $userDAO = new UserDAO();
			if(!SessionManager::loggedIn() || !SessionManager::canEdit()) {
				FileManager::redirect();
			}
			
			$roleDAO = new RoleDAO();
						
			$roles = $roleDAO->selectAll();

			return TwigController::render(
				"user-formulaire",
				[
					"roles" => $roles,

					// Textes personnalisés
					"headerText" => "Formulaire d'enregistrement d'usager",
					"action" => "Enregistrer"
				]
			);
		}

		public function formulaire($id = null) {
			if(isset($_SESSION["superpowerId"])) {
				unset($_SESSION["superpowerId"]);
			}
			
			if($id != null) {
				$_SESSION["superpowerId"] = $id;

				$spowerDAO = new SuperpowerDAO();
				$sp = $spowerDAO->getSuperpowerById($id);

				return TwigController::render(
					"category-formulaire",
					[
						"controllerSlug" => $this->controllerSlug,
						"entry" => $sp,
						"update" => true,
						"action" => "updateSuperpower",

						// Textes personnalisés
						"headerText" => "Formulaire de mise à jour"
					]
				);
			} else {
				return TwigController::render(
					"category-formulaire",
					[
						"controllerSlug" => $this->controllerSlug,
						"action" => "updateSuperpower",

						// Textes personnalisés
						"headerText" => "Formulaire d'enregistrement"
					]
				);
			}
		}

		public function updateUser() {
			
			$userDAO = new UserDAO();
			
			if(isset($_SESSION["userIdUpdate"], $_POST["delete"])) {
				$userDAO->deleteById($_SESSION["superpowerId"]);
				unset($_SESSION["userIdUpdate"]);
			} else {
				FileManager::lib("PasswordCrypt");
				
				// Ajouter les colonnes "calculées" au POST
				$_POST["username"] = $_POST["email"];
				$_POST["password"] = PasswordCrypt::hashPassword($_POST["password"]);
				
				$userDAO->insert($_POST);
			}

			FileManager::redirect($this->controllerSlug);
		}

	}


?>