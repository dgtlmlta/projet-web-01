<?php

	namespace Stampee;

	FileManager::model("AuctionDAO");
	FileManager::model("StampConditionDAO");
	FileManager::model("GumConditionDAO");
	FileManager::model("CenteringConditionDAO");

	class CatalogueController {
		private $auctionDAO;
		private $stampConditionDAO;
		private $centeringConditionDAO;
		private $gumConditionDAO;

		private $vars;

		public function __construct() {
			$this->auctionDAO = new AuctionDAO();
			$this->stampConditionDAO = new StampConditionDAO();
			$this->centeringConditionDAO = new CenteringConditionDAO();
			$this->gumConditionDAO = new GumConditionDAO();

			$this->vars = [
				"stampConditions"		=> $this->stampConditionDAO->selectAll(),
				"centeringConditions"	=> $this->centeringConditionDAO->selectAll(),
				"gumConditions"			=> $this->gumConditionDAO->selectAll(),
			];
		}
		
		public function index() {
			$this->vars["auctions"] = $this->auctionDAO->getNewestAuctionCards(6);

			return $this->getCatalogTemplate();
		}

		public function recherche($searchString) {
			$decodedSearchString = urldecode($searchString);

			$this->vars["auctions"] = $this->auctionDAO->getSearchedAuctionCards($decodedSearchString);
			$this->vars["breadcrumb"] = "Recherche : $decodedSearchString";
			$this->vars["searchString"] = $decodedSearchString;

			return $this->getCatalogTemplate();
		}

		private function getCatalogTemplate() {
			return TwigController::render(
				"catalog",
				$this->vars
			);
		}
	}

?>