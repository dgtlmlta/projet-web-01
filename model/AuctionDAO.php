<?php 

	namespace Stampee;

	class AuctionDAO extends Gateway {
		protected $table = "auction";		
		private $baseSelectCardQuery = 
			"SELECT 
				auction.id as auctionId,
				auction.timeStart,
				auction.timeEnd,
				auction.sellerId,
				auction.startPrice,
				stamp.title,
				stamp.description,
				stampImage.url as imageUrl,
				maxBid.highestBid
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

		public function getNewestAuctionCards($limit = null) {
			$query = $this->baseSelectCardQuery;

			$query .= " order by timeStart DESC";

			if($limit)
				$query .= " limit $limit";
			
			$stmt = $this->prepareStmt($query);

			if(!$stmt->execute()) 
				return false;
						
			
			return $stmt->fetchAll();		
		}
		
	}

?>