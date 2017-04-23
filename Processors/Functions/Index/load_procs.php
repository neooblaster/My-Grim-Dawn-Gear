<?php
/** -------------------------------------------------------------------------------------------------------------------- ** 
/** -------------------------------------------------------------------------------------------------------------------- ** 
/** ---																																					--- **
/** --- 										------------------------------------------------											--- **
/** ---															{ load_procs.php }																--- **
/** --- 										------------------------------------------------											--- **
/** ---																																					--- **
/** ---		TAB SIZE			: 3																													--- **
/** ---																																					--- **
/** ---		AUTEUR			: Nicolas DUPRE																									--- **
/** ---																																					--- **
/** ---		RELEASE			: 24.04.2017																										--- **
/** ---																																					--- **
/** ---		FILE_VERSION	: 1.1 NDU																											--- **
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
/** ---		VERSION 1.1 : 24.04.2017 : NDU																									--- **
/** ---		------------------------------																									--- **
/** ---			- Ajout d'un triage ordonnée par nom pour simplifier la gestion sur le portail d'administration 		--- **
/** ---																																					--- **
/** ---		VERSION 1.0 : 20.04.2017 : NDU																									--- **
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
function load_procs($lang){
	/** > Access aux variables globales **/
	global $PDO;
	
	/** > Déclaration des variables **/
	$query;		// STRING			:: Requête SQL
	$pQuery;		// PDOStatement	:: Requête SQL Préparée
	$faQuery;	// ARRAY				:: Donnée parsé de $pQuery
	$procs;		// ARRAY				:: Liste des sets enregistrés
	
	/** > Initialisation des variables **/
	$procs = Array();
	
	
	/** > Execution **/
	//--- Elaboration de la requête SQL
	$query = "
	SELECT
		PN.ID,
		PN.TAG,
		PN.NAME
		
	FROM PROCS_NAMES AS PN
	
	WHERE
		PN.LANG = :lang
		
	ORDER BY NAME ASC
	";
	
	//--- Récupération des donnée
	//──┐ Préparation SQL
	$pQuery = $PDO->prepare($query);
	//──┐ Execution SQL
	$pQuery->execute(Array(":lang" => $lang));
	
	//--- Traitement des données
	while($faQuery = $pQuery->fetch(PDO::FETCH_ASSOC)){
		$procs[] = Array(
			"PROC_ID" => $faQuery["ID"],
			"PROC_TAG" => $faQuery["TAG"],
			"PROC_NAME" => $faQuery["NAME"]
		);
	}
	
	
	return $procs;
}
?>