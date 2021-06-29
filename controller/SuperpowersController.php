<?php 

	namespace Stampee;

	FileManager::model("Gateway");
	FileManager::model("SuperpowerDAO");
	FileManager::model("XpetDAO");
	
	class SuperpowersController {
		private $controllerSlug = "superpowers";

		public function index($id = null, $slug = null) {
			$spowerDAO = new SuperpowerDAO();
						
			if($id != null && $slug != null) {
				if($spower = $spowerDAO->selectByIdSlug($id, $slug)) {
					$xpetDAO = new XpetDAO();					
					$xp = $xpetDAO->getXpetsByCategoryId("superpower", $id);
					
					return TwigController::render(
						"category-record",
						[
							"entry" => $spower,
							"xpets" => $xp,
							"controllerSlug" => $this->controllerSlug,

							// Textes personnalisés
							"entryLabelTextPlur" => "superpouvoirs",
							"entryLabelTextSing" => "superpouvoir",
							"animalsTitleText" => "Liste des animaux ayant ce superpouvoir"
						]
					);
				} else {
					FileManager::redirect($this->controllerSlug);
					return;
				}
			}
						
			$spowers = $spowerDAO->getAllSuperpowers();

			return TwigController::render(
				"categories-listing",
				[
					"controllerSlug" => $this->controllerSlug,
					"entries" => $spowers,

					// Textes personnalisés
					"entryLabelTextPlur" => "superpouvoirs",
					"entryLabelTextSing" => "superpouvoir"
				]
			);
		}

		public function formulaire($id = null) {
			SessionManager::canEdit();
			
			if($id != null) {
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

		public function updateSuperpower() {
			
			$teamDAO = new SuperpowerDAO();
			
			if(isset($_SESSION["superpowerId"], $_POST["delete"])) {
				$teamDAO->deleteById($_SESSION["superpowerId"]);
				unset($_SESSION["superpowerId"]);
			} else if (isset($_SESSION["superpowerId"])) {
				$sluger = new \Cocur\Slugify\Slugify();
				
				$_POST["slug"] = $sluger->slugify($_POST["name"]);

				$teamDAO->updateId($_SESSION["superpowerId"], $_POST);
				unset($_SESSION["superpowerId"]);
			} else {
				$sluger = new \Cocur\Slugify\Slugify();
				
				$_POST["slug"] = $sluger->slugify($_POST["name"]);
				
				$teamDAO->insert($_POST);
			}

			FileManager::redirect($this->controllerSlug);
		}

	}


?>