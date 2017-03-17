<?php

	function load_items(){
		global $PDO;
		
		try {
			$qItems = $PDO->query("SELECT ID, REL_FAMILY, REL_TYPE, REL_QUALITY, ITEM FROM ITEMS");
			
			$ITEMS = Array();
			
			while($faItems = $qItems->fetch(PDO::FETCH_ASSOC)){
				$ITEMS[] = Array(
					"ID" => $faItems['ID'],
					"REL_FAMILY" => $faItems['REL_FAMILY'],
					"REL_TYPE" => $faItems['REL_TYPE'],
					"REL_QUALITY" => $faItems['REL_QUALITY'],
					"ITEM" => $faItems['ITEM']
				);
			}
			
			return $ITEMS;
		} catch(Exception $e){
			return Array();
		}
	}

?>