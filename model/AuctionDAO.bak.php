<?php 

	namespace Stampee;

	class AuctionDAO extends Gateway {
		protected $table = "auction";

		private $currentQuery = "";
		private $currentValues = [];

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

		public function getAuctionById($id) {
			$stmt = $this->prepareStmt( 
				"$this->baseSelectDetailsQuery
				and $this->table.$this->primaryKey = :id"
			);
			
			if(!$stmt->execute([":id" => $id]))
				return false;
			
			
			return $stmt->fetch();
		}

		public function getSearchedAuctionCards($searchString, $limit = 12, $offset = 0) {
			$this->currentQuery = $this->baseSelectCardQuery;
						
			$searchQuery = "%" . strtolower($searchString) . "%";
			$this->currentQuery .= "
				and concat(lower(stamp.description), lower(stamp.title)) like :search";
			$this->currentValues[":search"] = $searchQuery;

			if(!empty($_GET)) {
				$this->currentQuery .= $this->handleUserFilters($_GET);
			}

			$this->currentQuery .= "
				order by timeStart DESC";

			// Limiter les retours.
			$this->currentQuery .= "
				limit $offset, $limit";
			
			$stmt = $this->prepareStmt($this->currentQuery);

			if(!$stmt->execute($this->currentValues)) {
				return false;
			}			
			
			return $stmt->fetchAll();		
		}

		public function getNewestAuctionCards($limit = 12, $offset = 0, $orderBy = "timeStart", $orderDirection = "desc") {
			$this->currentQuery = $this->baseSelectCardQuery;

			if(!empty($_GET)) {
				$this->currentQuery .= $this->handleUserFilters($_GET);
			}

			$this->currentQuery .= " order by timeStart DESC";

			if($limit)
				$this->currentQuery .= " limit $offset, $limit";
			
			$stmt = $this->prepareStmt($this->currentQuery);

			if(!$stmt->execute($this->currentValues))
				return false;
						
			
			return $stmt->fetchAll();		
		}

		private function handleUserFilters($filters) {
			foreach($filters as $name => $filterValue) {
				$handlerMethod = "handle" . ucfirst($name) . "Filter";

				if(!method_exists($this, $handlerMethod)) {
					continue;
				}

				$this->$handlerMethod($filterValue);
			}
		}

		private function handleStampConditionFilter($conditionIds) {
			$tmpConditionString = "
				and stamp.conditionId in (";
			
			foreach($conditionIds as $index => $id) {
				$tmpConditionString .= " :stampCondition$index,";

				$this->currentValues[":stampCondition$index"] = $id;
			}

			$this->currentQuery .= rtrim($tmpConditionString, ",") . ")";

			return $this->currentQuery;
		}

		private function handleCenteringConditionFilter($conditionIds) {
			$tmpConditionString = "
				and stamp.centeringId in (";
			
			foreach($conditionIds as $index => $id) {
				$tmpConditionString .= " :centeringCondition$index,";

				$this->currentValues[":centeringCondition$index"] = $id;
			}

			$this->currentQuery .= rtrim($tmpConditionString, ",") . ")";

			return $this->currentQuery;
		}
		
		private function handleGumConditionFilter($conditionIds) {
			$tmpConditionString = "
				and stamp.gumId in (";
			
			foreach($conditionIds as $index => $id) {
				$tmpConditionString .= " :gumCondition$index,";

				$this->currentValues[":gumCondition$index"] = $id;
			}

			$this->currentQuery .= rtrim($tmpConditionString, ",") . ")";

			return $this->currentQuery;
		}

		private function appendWhereStringConditionInIds($conditionName, $ids) {
			$tmpConditionString = "
				and stamp.gumId in (";
		}

		private function handleMinAmount($amount) {
			$this->appendCurrentQueryWithAmountFilter("min", $amount);
		}

		private function appendCurrentQueryWithAmountFilter() {

		}
	}

?>