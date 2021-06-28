<?php 

	namespace Stampee;

	require_once("DbConnect.php");

	abstract class Gateway extends DbConnect {
		protected $table;
		protected $primaryKey = "id";

		/**
		 * 
		 * @param $d Tableau associatif contenant les noms de colonnes et leurs valeurs associées
		 * @param $values Si l'on doit créer une chaine pour le "côté" valeur du binding
		 * 
		 */
		function getFieldsToString($d, $values = false) {
			if(!$values) {
				return implode(", ", array_keys($d));
			}

			return ':' . implode(", :", array_keys($d));
			
		}

		function getUpdateString($d) {
			$str = "";

			foreach($d as $key=>$v){
				$str .="$key=:$key,";
			}

			return rtrim($str, ",");
		}

		function getWhereCondition($c) {
			$str = "WHERE ";

			foreach($c as $field => $value) {
				$str .= "$field = $value AND ";
			}

			return rtrim($str, " AND ");
		}

		function prepareStmt($s) {
			return $this->c->prepare($s);
		}

		function insert($data) {
			$stmt = $this->prepareStmt(
				"INSERT INTO $this->table ({$this->getFieldsToString($data)})
				 VALUES ({$this->getFieldsToString($data, true)})"
			);
			
			foreach ($data as $key => $value) {
				$stmt->bindValue(":$key", $value);
			}

			if(!$stmt->execute()){
				echo "Erreur d'insertion";
				return implode(" :: ", $stmt->errorInfo());
			}else{
				return true;
			}
		}

		function update($data, $conditions) {

			$stmt = $this->prepareStmt(
				"UPDATE $this->table
				 SET {$this->getUpdateString($data)}
				 {$this->getWhereCondition($conditions)}"
			);
			
			foreach ($data as $key => $value) {
				$stmt->bindValue(":$key", $value);
			}

			if(!$stmt->execute()){
				echo "Erreur de mise à jour";
				return implode(" :: ", $stmt->errorInfo());
			}else{
				return true;
			}
		}

		function updateId($id, $data) {

			$stmt = $this->prepareStmt(
				"UPDATE $this->table
				 SET {$this->getUpdateString($data)}
				 WHERE $this->primaryKey = :id"
			);
			
			$stmt->bindValue(":id", $id);

			foreach ($data as $key => $value) {
				$stmt->bindValue(":$key", $value);
			}

			if(!$stmt->execute()){
				echo "Erreur de mise à jour";
				return implode(" :: ", $stmt->errorInfo());
			}else{
				return true;
			}
		}

		function selectAll($conditions = NULL, $orderBy = null, $order = "ASC") {
			$sql =
				"SELECT *
				 FROM $this->table";

			if(isset($conditions)) $sql .= " {$this->getWhereCondition($conditions)}";
			if(isset($orderBy)) $sql .= " ORDER BY $orderBy $order";

			return $this->c->query($sql)->fetchAll();
		}

		function selectById($id) {
			$stmt = $this->prepareStmt("SELECT * FROM $this->table WHERE $this->primaryKey = :id");

			if($stmt->execute([":id" => $id]))
				return $stmt->fetch();
			else {
				return false;
			}
		}

		function selectByIdSlug($id, $slug) {
			$stmt = $this->prepareStmt( 
				"SELECT *
				from $this->table
				where $this->table.$this->primaryKey = :id
					and $this->table.slug = :slug"
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

		function deleteById($id) {
			$stmt = $this->prepareStmt("DELETE from $this->table where $this->primaryKey = :id");

			$stmt->execute([":id" => $id]);
			return $stmt->rowCount() > 0;
		}
	}


?>