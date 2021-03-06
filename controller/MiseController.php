<?php 
	namespace Stampee;

	class MiseController {
		public function ajoutFetch() {
			FileManager::model("BidDAO");

			$bidDAO = new BidDAO();

			if(!SessionManager::isMember()) {
				return $this->throwFetchError("not logged in");
			}

			$json = file_get_contents('php://input');
			$data = json_decode($json)->payload;

			if(!$data->amount) {
				return $this->throwFetchError("empty amount");
			}

			$currentBid = ($topBid = $bidDAO->getTopBidByAuctionId($data->auctionId)) ?
				$topBid->bidAmount :
				$bidDAO->getStartPriceByAuctionId($data->auctionId);

			if(!((double) $data->amount > (double) $currentBid)) {
				return $this->throwFetchError("amount too low");
			}

			$insertData = [
				"amount"		=> $data->amount,
				"timePlaced"	=> getCurrentSQLDatetime(),
				"userId"		=> $_SESSION["userId"],
				"auctionId"		=> $data->auctionId
			];

			try{
				$insertedId = $bidDAO->insert($insertData);
			}
			catch(\exception $exception) {
				return $this->throwFetchError("insert error");
			}

			return json_encode(
				[
					"status"		=> "success",
					"lastInsertId"	=> $insertedId,
					"test"			=> $currentBid
				]
			);
	
		}

		private function throwFetchError($message = "error") {
			return json_encode(
				[
					"status" 	=> "error",
					"message" 	=> $message
				]
			);
		}
	}


?>