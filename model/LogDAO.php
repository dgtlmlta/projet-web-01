<?php 
	namespace Stampee;

	class LogDAO extends Gateway {
		public function storeAccessLog($url, $ip, $userId) {
			$bUrl = 
			$stmt = $this->prepareStmt(
				"INSERT into xpets.log
				(url, userId, userIP) 
				values(:url, :id, :ip)");
			
			if($stmt->execute(
				[
					":url" => $url,
					":id" => $userId,
					":ip" => $ip
				]
			)) {
				return $this->c->lastInsertId();
			} else {
				return false;
			}
		}
	}

?>