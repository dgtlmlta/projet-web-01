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
				stampImage.url as imageUrl
			from auction
			join stamp on stamp.id = auction.stampId
			join stampImage on stampImage.stampId = stamp.id
			where stampImage.isMainImage = 1
				and timeStart < now()
				and timeEnd > now()";

		public function getAuctionById($id) {
			$stmt = $this->prepareStmt( 
				"$this->baseSelectQuery
				where $this->table.$this->primaryKey = :id"
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