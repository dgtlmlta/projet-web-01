<?php 

	namespace Stampee;

	class AuctionDAO extends Gateway {
		protected $table = "auction";		
		private $baseSelectQuery = 
			"SELECT xpet.name, xpet.id, xpet.description, xpet.classId, xpet.slug, xpet.superpowerId, xpet.teamId, xpet.imageUrl, team.name as teamName, team.slug as teamSlug, superpower.name as superpowerName, superpower.slug as superpowerSlug, class.name as class, class.slug as classSlug
			from xpet
			join class on class.id = xpet.classId
			join team on team.id = xpet.teamId
			join superpower on superpower.id = xpet.superpowerId";

		public function getAuctionById($id) {
			$stmt = $this->prepareStmt( 
				"$this->baseSelectQuery
				where $this->table.$this->primaryKey = :id"
			);
			
			if($stmt->execute([":id" => $id]))
				return $stmt->fetch();
			else {
				return false;
			};
		}

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