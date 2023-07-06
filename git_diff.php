diff --git a/common/models/elastic/FilterQueryConstructorElastic.php b/common/models/elastic/FilterQueryConstructorElastic.php
index 6d1dab3..c6fa8fc 100644
--- a/common/models/elastic/FilterQueryConstructorElastic.php
+++ b/common/models/elastic/FilterQueryConstructorElastic.php
@@ -96,9 +96,21 @@ class FilterQueryConstructorElastic extends BaseObject{
 
 		//Особенности
 		if($filter_data['key'] == 'specials.id'){
-			$this->query_arr = [
-				['match' => [$prefix.$filter_data['key'] => $filter_data['value']]]
-			];
+			// $this->query_arr = [
+			// 	['match' => [$prefix.$filter_data['key'] => $filter_data['value']]]
+			// ];
+			if(is_array($filter_data['value'])){
+				foreach ($filter_data['value'] as $key => $value) {
+					array_push($this->query_arr, ['match' => [$prefix.$filter_data['key'] => $value]]);
+				}
+			}
+			else{
+				$this->query_arr = [
+					["match" => [
+						$prefix.$filter_data['key'] => $filter_data['value']
+					]]
+				];
+			}
 		}
 
 		//Дополнительные пар-тры
@@ -156,6 +168,59 @@ class FilterQueryConstructorElastic extends BaseObject{
 				];
 			}
 		}
+		//Местоположение
+		elseif($filter_data['key'] == 'capacity'){
+			switch (substr($filter_data['value'], 0, 1)) {
+				case '<':
+					$this->query_arr = [
+						[
+							"range" => [
+								$prefix.'capacity_min' => [
+									'lte' => str_replace('<', '', $filter_data['value'])
+								]
+							]
+						]
+					];
+					break;
+				case '&':
+					$value_arr = explode(',', substr($filter_data['value'], 1));
+					$this->query_arr = [
+						'capacity' => [
+							'bool' => [
+								'must' => [],
+							]
+						]
+					];
+					array_push($this->query_arr['capacity']['bool']['must'], [
+						"range" => [
+							$prefix.$filter_data['key'] => [
+								'gte' => $value_arr[0]
+							]
+						]
+					]);
+					array_push($this->query_arr['capacity']['bool']['must'], [
+						"range" => [
+							$prefix.'capacity_min' => [
+								'lte' => $value_arr[1]
+							]
+						]
+					]);
+					break;
+				case '>':
+					$this->query_arr = [
+						[
+							"range" => [
+								$prefix.$filter_data['key'] => [
+									'gte' => str_replace('>', '', $filter_data['value'])
+								]
+							]
+						]
+					];
+					break;
+				default:
+					break;
+			}
+		}
 		//Остальные фильтры
 		else{
 			//Фильтр со сложными условиями
