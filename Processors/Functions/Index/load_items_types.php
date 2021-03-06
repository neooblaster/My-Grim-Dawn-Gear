<?php
/** -------------------------------------------------------------------------------------------------------------------- ** 
/** -------------------------------------------------------------------------------------------------------------------- ** 
/** ---																																					--- **
/** --- 										------------------------------------------------											--- **
/** ---														{ load_items_types.php }															--- **
/** --- 										------------------------------------------------											--- **
/** ---																																					--- **
/** ---		TAB SIZE			: 3																													--- **
/** ---																																					--- **
/** ---		AUTEUR			: Nicolas DUPRE																									--- **
/** ---																																					--- **
/** ---		RELEASE			: 19.03.2017																										--- **
/** ---																																					--- **
/** ---		FILE_VERSION	: 1.1 NDU																											--- **
/** ---																																					--- **
/** ---																																					--- **
/** --- 														---------------------------														--- **
/** ---															{ C H A N G E L O G }															--- **
/** --- 														---------------------------														--- **
/** ---																																					--- **
/** ---																																					--- **
/** ---		VERSION 1.1 : 19.03.2017 : NDU																									--- **
/** ---		------------------------------																									--- **
/** ---			- Ajout du tag pour récupération de la valeur dans les fichiers de lang via $vars							--- **
/** ---																																					--- **
/** ---		VERSION 1.0 : 18.03.2017 : NDU																									--- **
/** ---		------------------------------																									--- **
/** ---			- Première release																												--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **

	Requirements :
	--------------

	Input Params :
	--------------
	
	Output Params :
	---------------

	Objectif du script :
	---------------------
	
	Description fonctionnelle :
	----------------------------

/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **/
function load_items_types(){
	global $PDO;
	global $vars;
	
	try {
		$qItemsTypes = $PDO->query("SELECT ID, FAMILY, TYPE, TAG FROM TYPES");
		
		$ITEMS_TYPES = Array();
		
		while($faItemsTypes = $qItemsTypes->fetch(PDO::FETCH_ASSOC)){
			$ITEMS_TYPES[] = Array(
				"ID" => $faItemsTypes['ID'],
				"FAMILY" => $faItemsTypes['FAMILY'],
				"TYPE" => $faItemsTypes['TYPE'],
				"TYPE_NAME" => ($vars[$faItemsTypes['TAG']]) ?: $faItemsTypes['TAG']
			);
		}
		
		return $ITEMS_TYPES;
	} catch(Exception $e){
		return Array();
	}
}
?>