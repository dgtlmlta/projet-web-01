<?php 

	namespace Stampee;

	class StampImageDAO extends Gateway {
		protected $table = "stampImage";		
		
		public function insertImage($image, $data) {
			try {
				$url = FileManager::storeImage($image, $data["stampId"]);
			}
			catch(\exception $e) {
				var_dump($e);
				die();
			}
			
			$data["url"] = $url;

			try {
				$imageLastInsertedId = $this->insert($data);
			} catch(\exception $e) {
				throw $e;
			}

			return $imageLastInsertedId;
		}
	}

?>