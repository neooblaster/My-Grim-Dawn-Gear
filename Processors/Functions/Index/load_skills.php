<?php
/** -------------------------------------------------------------------------------------------------------------------- ** 
/** -------------------------------------------------------------------------------------------------------------------- ** 
/** ---																																					--- **
/** --- 										------------------------------------------------											--- **
/** ---															{ load_skills.php }																--- **
/** --- 										------------------------------------------------											--- **
/** ---																																					--- **
/** ---		TAB SIZE			: 3																													--- **
/** ---																																					--- **
/** ---		AUTEUR			: Nicolas DUPRE																									--- **
/** ---																																					--- **
/** ---		RELEASE			: 18.04.2017																										--- **
/** ---																																					--- **
/** ---		FILE_VERSION	: 1.0 NDU																											--- **
/** ---																																					--- **
/** ---																																					--- **
/** --- 														---------------------------														--- **
/** ---																{ G I T H U B }																--- **
/** --- 														---------------------------														--- **
/** ---																																					--- **
/** ---		Automatize url?ts=3 :																												--- **
/** ---																																					--- **
/** ---			https://chrome.google.com/webstore/detail/tab-size-on-github/ofjbgncegkdemndciafljngjbdpfmbkn/related	--- **
/** ---																																					--- **
/** ---																																					--- **
/** --- 														---------------------------														--- **
/** ---															{ C H A N G E L O G }															--- **
/** --- 														---------------------------														--- **
/** ---																																					--- **
/** ---																																					--- **
/** ---		VERSION 1.0 : 18.04.2017 : NDU																									--- **
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
function load_skills($lang){
	/** > Access aux variables globales **/
	global $PDO;
	
	/** > Déclaration des variables **/
	$query;		// STRING			:: Requête SQL
	$pQuery;		// PDOStatement	:: Requête SQL Préparée
	$faQuery;	// ARRAY				:: Donnée parsé de $pQuery
	$skills;		// ARRAY				:: Liste des sets enregistrés
	
	/** > Initialisation des variables **/
	$skills = Array();
	
	
	/** > Execution **/
	//--- Elaboration de la requête SQL
	$query = "
	SELECT
		SN.ID,
		SN.TAG,
		SN.NAME
		
	FROM SKILLS_NAMES AS SN
	
	WHERE
		SN.LANG = :lang
	";
	
	//--- Récupération des donnée
	//──┐ Préparation SQL
	$pQuery = $PDO->prepare($query);
	//──┐ Execution SQL
	$pQuery->execute(Array(":lang" => $lang));
	
	//--- Traitement des données
	while($faQuery = $pQuery->fetch(PDO::FETCH_ASSOC)){
		$skills[] = Array(
			"SKILL_ID" => $faQuery["ID"],
			"SKILL_TAG" => $faQuery["TAG"],
			"SKILL_NAME" => $faQuery["NAME"]
		);
	}
	
	
	return $skills;
}
?>