<?php

	function load_items_qualities(){
		global $PDO;
		
		try {
			$qQualities = $PDO->query("SELECT ID, QUALITY FROM ITEMS_QUALITIES");
			
			$QUALITIES = Array();
			
			while($faQualities = $qQualities->fetch(PDO::FETCH_ASSOC)){
				$QUALITIES[] = Array(
					"ID" => $faQualities['ID'],
					"QUALITY" => $faQualities['QUALITY']
				);
			}
			
			return $QUALITIES;
		} catch(Exception $e){
			return Array();
		}
	}

?>