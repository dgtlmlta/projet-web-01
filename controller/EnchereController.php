<?php 
	namespace Stampee;

	use DateTime;

	FileManager::model("StampConditionDAO");
	FileManager::model("GumConditionDAO");
	FileManager::model("CenteringConditionDAO");

	class EnchereController {
		public function formulaire() {
			// Clause gardienne.			
			if(!SessionManager::isMember()) {
				FileManager::redirect("authentification");
				exit();
			}

			$conditionDAO = new StampConditionDAO();
			$gumDAO = new GumConditionDAO();
			$centeringDAO = new CenteringConditionDAO();

			return TwigController::render(
				"enchere-formulaire",
				[
					// Textes
					"pageAction" => "d'ajout",

					// Items
					"conditions" => $conditionDAO->selectAll(),
					"gums" => $gumDAO->selectAll(),
					"centerings" => $centeringDAO->selectAll()
				]
			);
		}

		public function enregistrer() {
			// Clause gardienne.
			if(!SessionManager::canEditAuctions()) {
				FileManager::redirect("authentification");
				exit();
			}

			FileManager::model("AuctionDAO");
			FileManager::model("ImageDAO");
			FileManager::model("StampDAO");
			FileManager::lib("dateFunctions");
			FileManager::lib("stringFunctions");

			$auctionDAO = new AuctionDAO();
			$stampDAO = new StampDAO();
			$imageDAO = new ImageDAO();

			// Aiguiller les champs POST aux bonnes tables.

			// Table "stamp"
			$stampData = [
				"title"				=> $_POST["title"],
				"description"		=> $_POST["description"],
				"year"				=> $_POST["year"],
				"width"				=> $_POST["width"],
				"height"			=> $_POST["height"],
				"color"				=> $_POST["color"],
				"country"			=> $_POST["country"],
				"denomination"		=> $_POST["denomination"],
				"isCertified"		=> $_POST["isCertified"] ?? 0,
				"conditionId"		=> $_POST["conditionId"],
				"gumId"				=> $_POST["gumId"],
				"centeringId"		=> $_POST["centeringId"]
			];

			if(!$stampLastInsertedId = $stampDAO->insert($stampData)) {
				die();
			}

			
			// Table "image"
			$imageData = [
				"timeAdded"			=> getCurrentSQLDatetime(),
				"title"				=> $_POST["title"],
				"isMainImage"			=> 1,
				"stampId"			=> $stampLastInsertedId
			];

			try {
				$imageLastInsertedId = $imageDAO->insertImage($_FILES["mainImage"], $imageData);
			} catch(\exception $e) {
				var_dump($e);
				var_dump($stampLastInsertedId);
				$stampDAO->deleteById($stampLastInsertedId);
				die();
			}
			

			// Table "auction"
			$auctionData = [
				"timeStart" 		=> "{$_POST["dateStart"]} {$_POST["timeStart"]}",
				"timeEnd" 			=> "{$_POST["dateEnd"]} {$_POST["timeEnd"]}",
				"timeCreated" 		=> getCurrentSQLDatetime(),
				"startPrice" 		=> $_POST["startPrice"],
				// Par défaut, l'enchère est active
				"isActive" 			=> 1,
				"stampId"			=> $stampLastInsertedId,
				"sellerId"			=> $_SESSION["userId"]
			];

			try {
				$auctionLastInsertedId = $auctionDAO->insert($auctionData);
			} catch(\exception $e) {
				var_dump($e);
				$imageDAO->deleteById($imageLastInsertedId);
				$stampDAO->deleteById($stampLastInsertedId);		
				die();
			}

			FileManager::redirect("enchere/$auctionLastInsertedId");						
		}
	}

?>
