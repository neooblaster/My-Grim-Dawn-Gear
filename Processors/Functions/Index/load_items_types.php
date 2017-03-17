<?php

	function load_items_types(){
		global $PDO;
		
		try {
			$qItemsTypes = $PDO->query("SELECT ID, REL_FAMILY, TYPE FROM ITEMS_TYPES");
			
			$ITEMS_TYPES = Array();
			
			while($faItemsTypes = $qItemsTypes->fetch(PDO::FETCH_ASSOC)){
				$ITEMS_TYPES[] = Array(
					"ID" => $faItemsTypes['ID'],
					"REL_FAMILY" => $faItemsTypes['REL_FAMILY'],
					"TYPE" => $faItemsTypes['TYPE']
				);
			}
			
			return $ITEMS_TYPES;
		} catch(Exception $e){
			return Array();
		}
	}

?>