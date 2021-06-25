<?php

	namespace XPetsIntl;

	class SuperpowerDAO extends Gateway {
		protected $table = "superpower";

		public function getAllSuperpowers() {
			return $this->selectAll();
		}

		public function getSuperpowerById($id) {
			return $this->selectById($id);
		}
	}

?>