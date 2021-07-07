<?php 
	namespace Stampee;

	class MiseController {
		public function ajouter($data = null) {
			FileManager::model("BidDAO");

			$bidDAO = new BidDAO();


			if($_SERVER["CONTENT_TYPE"] == "application/json; charset=utf-8") {
				if(!SessionManager::isMember()) {
					echo $this->throwFetchError("not logged in");
					return;
				}


				$json = file_get_contents('php://input');
				$data = json_decode($json)->payload;
				
				if(!$data->amount) {
					echo $this->throwFetchError("empty amount");
					return;
				}

				if(!((double) $data->amount > (double) $bidDAO->getTopBidByAuctionId($data->auctionId)->bidAmount)) {
					echo $this->throwFetchError("amount too low");
					return;
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
					echo $this->throwFetchError("insert error");
					return;
				}

				return json_encode(
					[
						"status"		=> "success",
						"lastInsertId"	=> $insertedId
					]
				);
			}

			//echo json_encode("non");
		}

		private function throwFetchError($error = "error") {
			return json_encode(["status" => $error]);
		}
	}


?>