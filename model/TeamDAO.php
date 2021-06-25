<?php

	namespace XPetsIntl;

	class TeamDAO extends Gateway {
		protected $table = "team";

		public function getAllTeams() {
			return $this->selectAll();
		}

		public function getTeamById($id) {
			return $this->selectById($id);
		}
	}

?>