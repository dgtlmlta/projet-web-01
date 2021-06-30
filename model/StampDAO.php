<?php 

	namespace Stampee;

	class StampDAO extends Gateway {
		protected $table = "stamp";		
		
		public function getXpetByIdSlug($id, $slug) {
			$stmt = $this->prepareStmt( 
				"$this->baseSelectQuery
				where xpet.$this->primaryKey = :id
					and xpet.slug = :slug"
			);
			
			if($stmt->execute(
				[
					":id" => $id,
					":slug" => $slug
				]
			)
			) {
				return $stmt->fetch();
			} else {
				return false;
			};
		}

		public function getXpetsByCategoryId($cat, $id) {
			$stmt = $this->prepareStmt( 
				"$this->baseSelectQuery
				where $cat.id = :id"					
			);

			if($stmt->execute(
					[
						":id" => $id
					]
				)
			) {
				return $stmt->fetchAll();
			} else {
				return false;
			};
		}
		
	}

?>