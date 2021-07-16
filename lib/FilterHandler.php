<?php 

	namespace Stampee;

	class FilterHandler {
		private $queryString = "";
		private $queryValues = [];


		public function handleUserFilters($filters) {
			foreach($filters as $name => $filterValue) {
				$handlerMethod = "handle" . ucfirst($name) . "Filter";

				if(!method_exists($this, $handlerMethod) || empty($filterValue)) {
					continue;
				}

				$this->$handlerMethod($filterValue);
			}

			return [
				"filterQueryString" => $this->queryString,
				"filterValues" => $this->queryValues
			];
		}

		private function handleStampConditionFilter($conditionIds) {
			$tmpConditionString = "
				and stamp.conditionId in (";
			
			foreach($conditionIds as $index => $id) {
				$tmpConditionString .= " :stampCondition$index,";

				$this->queryValues[":stampCondition$index"] = $id;
			}

			$this->queryString .= rtrim($tmpConditionString, ",") . ")";

			return $this->queryString;
		}

		private function handleCenteringConditionFilter($conditionIds) {
			$tmpConditionString = "
				and stamp.centeringId in (";
			
			foreach($conditionIds as $index => $id) {
				$tmpConditionString .= " :centeringCondition$index,";

				$this->queryValues[":centeringCondition$index"] = $id;
			}

			$this->queryString .= rtrim($tmpConditionString, ",") . ")";

			return $this->queryString;
		}
		
		private function handleGumConditionFilter($conditionIds) {
			$tmpConditionString = "
				and stamp.gumId in (";
			
			foreach($conditionIds as $index => $id) {
				$tmpConditionString .= " :gumCondition$index,";

				$this->queryValues[":gumCondition$index"] = $id;
			}

			$this->queryString .= rtrim($tmpConditionString, ",") . ")";

			return $this->queryString;
		}

		private function handleMinAmountFilter($amount) {
			$this->queryString .= $this->compareHighestBidAgainstAmount(">=", $amount);
		}

		private function handleMaxAmountFilter($amount) {
			$this->queryString .= $this->compareHighestBidAgainstAmount("<=", $amount);
		}

		private function compareHighestBidAgainstAmount($comparator, $amount) {
			return "
				and highestBid $comparator $amount";
		}

		private function appendWhereStringConditionInIds($conditionName, $ids) {
			$tmpConditionString = "
				and stamp.gumId in (";
		}

		private function handleMinAmount($amount) {
			$this->appendCurrentQueryWithAmountFilter("min", $amount);
		}

		private function appendCurrentQueryWithAmountFilter() {

		}
	}


?>