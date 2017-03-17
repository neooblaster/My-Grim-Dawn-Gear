<?php

	function load_items_families(){
		global $PDO;
		
		try {
			$qFamilies = $PDO->query("SELECT ID, FAMILY FROM ITEMS_FAMILIES");
			
			$FAMILIES = Array();
			
			while($faFamilies = $qFamilies->fetch(PDO::FETCH_ASSOC)){
				$FAMILIES[] = Array(
					"ID" => $faFamilies['ID'],
					"FAMILY" => $faFamilies['FAMILY']
				);
			}
			
			return $FAMILIES;
		} catch(Exception $e){
			return Array();
		}
	}

?>