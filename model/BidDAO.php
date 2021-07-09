<?php 

	namespace Stampee;

	class BidDAO extends Gateway {
		protected $table = "bid";

		public function getTopBidByAuctionId($id) {
			$stmt = $this->prepareStmt( 
				"SELECT
					bid.auctionId,
					max(bid.amount) as bidAmount,
					user.id,
					user.firstName as highestBidder					
				from
					bid
				join user on user.id = bid.userId
				where bid.auctionId = :id
				group by bid.auctionId");

				if(!$stmt->execute([":id" => $id]))
					return false;

				return $stmt->fetch();
		}

		public function getStartPriceByAuctionId($id) {
			$stmt = $this->prepareStmt( 
				"SELECT
					auction.startPrice
				from
					auction
				where auction.id = :id");

				if(!$stmt->execute([":id" => $id]))
					return false;

				return $stmt->fetch()->startPrice;
		}

		public function getHighestBidsByAuctionId($id, $limit = 3) {
			$stmt = $this->prepareStmt( 
				"SELECT
					bid.auctionId,
					bid.amount,
					user.id,
					user.firstName					
				from
					bid
				join user on user.id = bid.userId
				where bid.auctionId = :id
				order by amount desc
				limit $limit");

				if(!$stmt->execute([":id" => $id]))
					return false;

				return $stmt->fetchAll();
		}
		

		public function getBidsByUserId($id) {

		}

		public function getBidsOnAuctionIdByUserId($auctionId, $userId) {

		}
	}


?>