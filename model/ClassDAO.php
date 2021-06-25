<?php 

	namespace XPetsIntl;

	class ClassDAO extends Gateway {
		protected $table = "class";
		
		public function getAllClasses() {
			return $this->selectAll();
		}
	
		public function getClassById($id) {
			return $this->selectById($id);
		}
	}
?>