<?php
/** -------------------------------------------------------------------------------------------------------------------- ** 
/** -------------------------------------------------------------------------------------------------------------------- ** 
/** ---																																					--- **
/** --- 										------------------------------------------------											--- **
/** ---														{ load_items_families.php }														--- **
/** --- 										------------------------------------------------											--- **
/** ---																																					--- **
/** ---		TAB SIZE			: 3																													--- **
/** ---																																					--- **
/** ---		AUTEUR			: Nicolas DUPRE																									--- **
/** ---																																					--- **
/** ---		RELEASE			: 18.03.2017																										--- **
/** ---																																					--- **
/** ---		FILE_VERSION	: 1.0 NDU																											--- **
/** ---																																					--- **
/** ---																																					--- **
/** --- 														---------------------------														--- **
/** ---															{ C H A N G E L O G }															--- **
/** --- 														---------------------------														--- **
/** ---																																					--- **
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
function load_items_families(){
	global $PDO;
	
	try {
		$qFamilies = $PDO->query("SELECT ID, FAMILY FROM FAMILIES");
		
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