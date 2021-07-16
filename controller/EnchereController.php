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
				FileManager::redirect("/authentification");
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
					"gums"		 => $gumDAO->selectAll(),
					"centerings" => $centeringDAO->selectAll()
				]
			);
		}

		public function enregistrer() {
			// Clause gardienne.
			if(!SessionManager::canEditAuctions()) {
				FileManager::redirect("/authentification");
				exit();
			}

			FileManager::model("AuctionDAO");
			FileManager::model("ImageDAO");
			FileManager::model("StampDAO");

			$auctionDAO = new AuctionDAO();
			$stampDAO = new StampDAO();
			$imageDAO = new StampImageDAO();

			// Aiguiller les champs POST aux bonnes tables.
			// Table "stamp"
			$stampData = $this->extractStampColumnsFromData($_POST);

			if(!$stampLastInsertedId = $stampDAO->insert($stampData)) {
				die();
			}
			
			// Table "image"
			$imageData = $this->extractImageColumnsFromData($_POST, $stampLastInsertedId);

			try {
				$imageLastInsertedId = $imageDAO->insertImage($_FILES["mainImage"], $imageData);
			} catch(\exception $e) {
				$stampDAO->deleteById($stampLastInsertedId);
				var_dump($e);
				die();
			}			

			// Table "auction"
			$auctionData = $this->extractAuctionColumnsFromData($_POST, $stampLastInsertedId);

			try {
				$auctionLastInsertedId = $auctionDAO->insert($auctionData);
			} catch(\exception $e) {
				$imageDAO->deleteById($imageLastInsertedId);
				$stampDAO->deleteById($stampLastInsertedId);
				var_dump($e);		
				die();
			}

			FileManager::redirect("/enchere/fiche/$auctionLastInsertedId");						
		}

		private function extractStampColumnsFromData($data) {
			return [
				"title"				=> $data["title"],
				"description"		=> $data["description"],
				"year"				=> $data["year"],
				"width"				=> $data["width"],
				"height"			=> $data["height"],
				"color"				=> strtolower($data["color"]),
				"country"			=> $data["country"],
				"denomination"		=> $data["denomination"],
				"isCertified"		=> $data["isCertified"] ?? 0,
				"conditionId"		=> $data["conditionId"],
				"gumId"				=> $data["gumId"],
				"centeringId"		=> $data["centeringId"]
			];
		}

		private function extractImageColumnsFromData($data, $id) {
			return [
				"timeAdded"			=> getCurrentSQLDatetime(),
				"title"				=> $data["title"],
				"isMainImage"		=> 1,
				"stampId"			=> $id
			];
		}

		private function extractAuctionColumnsFromData($data, $id) {
			return [
				"timeStart" 		=> "{$data["dateStart"]} {$data["timeStart"]}",
				"timeEnd" 			=> "{$data["dateEnd"]} {$data["timeEnd"]}",
				"timeCreated" 		=> getCurrentSQLDatetime(),
				"startPrice" 		=> $data["startPrice"],
				// Par défaut, l'enchère est active
				"isActive" 			=> 1,
				"stampId"			=> $id,
				"sellerId"			=> $_SESSION["userId"]
			];
		}

		public function fiche($id) {
			FileManager::model("AuctionDAO");
			FileManager::model("BidDAO");

			$auctionDAO = new AuctionDAO();
			$bidDAO = new BidDAO();

			$queryOptions = [
				"limit" => 4
			];

			return TwigController::render(
				"enchere-details",
				[
					// Textes
					
					// Items
					"canSubmitBid" 	=> SessionManager::isMember() && !SessionManager::isAuctionCreator($id),
					"auction" 		=> $auctionDAO->getAuctionById($id),
					"highestBids"	=> $bidDAO->getHighestBidsByAuctionId($id, 5),
					"auctions" 		=> $auctionDAO->getNewestAuctionCards($queryOptions)
				]
			);
		}
	}

?>
