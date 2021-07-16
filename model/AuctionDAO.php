<?php 

	namespace Stampee;

	FileManager::lib("FilterHandler");

	class AuctionDAO extends Gateway {
		protected $table = "auction";

		private $currentQuery = "";
		private $currentValues = [];
		private $currentQueryOptions = [];
		private $defaultQueryOptions = [];
		private $filterHandler;
		

		private $baseSelectCardQuery = 
			"SELECT 
				auction.id as auctionId,
				auction.timeStart,
				auction.timeEnd,
				auction.sellerId,
				stamp.title,
				stamp.description,
				stampImage.url as imageUrl,
				coalesce(maxBid.highestBid, auction.startPrice) as highestBid
			from auction
			left join (
				select
					auctionId,
					max(bid.amount) as highestBid
				from
					bid
				group by bid.auctionId) maxBid
				on maxBid.auctionId = auction.id
			join stamp on stamp.id = auction.stampId
			join stampImage on stampImage.stampId = stamp.id
			where stampImage.isMainImage = 1
				and timeStart < now()
				and timeEnd > now()";

		private $baseSelectDetailsQuery = 
			"SELECT 
				auction.id as auctionId,
				auction.timeStart,
				auction.timeEnd,
				auction.sellerId,
				auction.startPrice,
				user.firstName as sellerName,
				stamp.title,
				stamp.description,
				stampImage.url as imageUrl,
				stamp.year as stampYear,
				stamp.height as stampHeight,
				stamp.width as stampWidth,
				stamp.denomination as stampDenomination,
				stamp.color as stampColor,
				stamp.isCertified as stampIsCertified,
				stamp.country as stampCountry,
				stampCondition.name as conditionLabel,
				gumCondition.name as gumLabel,
				centeringCondition.name as centeringLabel
			from auction
			join stamp on stamp.id = auction.stampId
			join user on user.id = auction.sellerId
			join stampImage on stampImage.stampId = stamp.id
			join stampCondition on stampCondition.id = stamp.conditionId
			join gumCondition on gumCondition.id = stamp.gumId
			join centeringCondition on centeringCondition.id = stamp.centeringId
			where stampImage.isMainImage = 1";

			/* left join (
				select
					auctionId,
					user.id,
					user.firstName as highestBidder,
					max(bid.amount) as highestBid
				from
					bid
				join user on user.id = bid.userId
				group by bid.auctionId) maxBid
				on maxBid.auctionId = auction.id
				join user on user.id = auction.sellerId */

		public function __construct() {
			parent::__construct();

			$this->filterHandler = new FilterHandler();
			$this->defaultQueryOptions = [
				"limit"				=> 12,
				"offset"			=> 0,
				"orderBy"			=> "timeStart",
				"orderDirection"	=> "desc"
			];
		}

		public function getAuctionById($id) {
			$stmt = $this->prepareStmt( 
				"$this->baseSelectDetailsQuery
				and $this->table.$this->primaryKey = :id"
			);
			
			if(!$stmt->execute([":id" => $id]))
				return false;
			
			
			return $stmt->fetch();
		}

		public function getNewestAuctionCards($options = [
			"orderBy" => "startDate",
			"orderDirection" => "desc"]) {
				$this->initCurrentQuery($options);
				$this->appendCurrentOptions();

				return $this->runQuery();
			}

		public function getSearchedAuctionCards($searchString, $options = []) {
			$this->initCurrentQuery($options);

			if(empty($searchString)) {
				return;				
			}

			$this->handleSearch($searchString);
			$this->appendCurrentOptions();
			
			return $this->runQuery();
		}

		public function getAuctionCardsByCountry($countryString, $options = []) {
			$this->initCurrentQuery($options);

			if(empty($countryString)) {
				return;
			}

			return $this	->handleCategory("country", $countryString)
							->appendCurrentOptions()
							->runQuery();			
		}

		public function getAuctionCardsByYear($yearString, $options = []) {
			$this->initCurrentQuery($options);

			if(empty($yearString) || !is_numeric($yearString)) {
				echo $yearString;
				exit();
			}

			return $this	->handleNumericCategory("year", "=", $yearString)
							->appendCurrentOptions()
							->runQuery();			
		}

		private function initCurrentQuery($options) {
			$this->currentQueryOptions = array_merge($this->defaultQueryOptions, $options);

			$this->currentQuery = $this->baseSelectCardQuery;		

			if(!empty($_GET)) {
				$this->handleFilters();
			}
		}

		private function appendCurrentOptions() {
			$this->currentQuery .= "
				order by {$this->currentQueryOptions["orderBy"]} {$this->currentQueryOptions["orderDirection"]}
				limit {$this->currentQueryOptions["offset"]}, {$this->currentQueryOptions["limit"]}";

			return $this;
		}
		
		private function handleFilters() {
			extract($this->filterHandler->handleUserFilters($_GET));

			$this->currentQuery .= $filterQueryString;
			$this->currentValues = array_merge($this->currentValues, $filterValues);

			return $this;
		}

		private function handleSearch($searchString) {
			$searchQuery = "%" . strtolower($searchString) . "%";
			$this->currentQuery .= "
				and concat(lower(stamp.description), lower(stamp.title), lower(stamp.country), lower(stamp.year)) like :search";
			$this->currentValues[":search"] = $searchQuery;

			return $this;
		}

		private function handleCategory($category, $value) {
			$this->currentQuery .= "
				and lower(stamp.{$category}) like :category";
			$this->currentValues[":category"] = $value;

			return $this;
		}

		private function handleNumericCategory($category, $comparator, $value) {
			$this->currentQuery .= "
				and $category $comparator :$category";
			$this->currentValues[":$category"] = $value;

			return $this;
		}

		private function runQuery() {
			$stmt = $this->prepareStmt($this->currentQuery);

			if(!$stmt->execute($this->currentValues))
				return false;						
			
			return $stmt->fetchAll();
		}

		public function getAuctionCreatorId($auctionId) {
			$stmt = $this->prepareStmt( 
				"SELECT auction.sellerId as sellerId
				from auction
				where auction.id = :id"
			);
			
			if(!$stmt->execute([":id" => $auctionId]))
				return false;
			
			
			return $stmt->fetch()->sellerId;
		}
	}

?>