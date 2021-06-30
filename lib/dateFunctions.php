<?php 
	declare(strict_types = 1);

	function getCurrentSQLDatetime(): string {
		$dt = new DateTime();
		return $dt->format("Y-m-d H:i:s");
	}

?>