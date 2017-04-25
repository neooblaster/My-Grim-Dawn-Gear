<?php
/** -------------------------------------------------------------------------------------------------------------------- ** 
/** -------------------------------------------------------------------------------------------------------------------- ** 
/** ---																																					--- **
/** --- 										------------------------------------------------											--- **
/** ---													{ load_attributes_names.php } 														--- **
/** --- 										------------------------------------------------											--- **
/** ---																																					--- **
/** ---		TAB SIZE			: 3																													--- **
/** ---																																					--- **
/** ---		AUTEUR			: Nicolas DUPRE																									--- **
/** ---																																					--- **
/** ---		RELEASE			: 24.04.2017																										--- **
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
/** ---		VERSION 1.0 : 24.04.2017 : NDU																									--- **
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
function load_attributes_names($lang){
	/** > Accession au PDO **/
	global $PDO;
	
	/** > Déclaration des variables **/
	$query;					// STRING			:: Requête SQL à jouer
	$pQuery;					// PDOStatement	:: Requête SQL Préparée depuis $query
	$faQuery;				// ARRAY				:: Donnée fetchée de $pQuery
	$attributes_names;	// ARRAY				:: Liste des attributs
	
	/** > Initialisation des variables **/
	$attributes_names = Array();
	
	
	/** > Traitement **/
	//--- Composition de la requête SQL
	$query = "
	SELECT
		AN.ID,
		AN.TAG,
		AN.NAME
		
	FROM ATTRIBUTES_NAMES AS AN
	
	WHERE
		AN.LANG = :lang
		
	ORDER BY NAME ASC, TAG ASC
	";
	
	
	/** > Execution de la requête SQL **/
	$pQuery = $PDO->prepare($query);
	$pQuery->execute(Array(
		":lang" => $lang
	));
	
	
	/** > Traitement des données **/
	while($faQuery = $pQuery->fetch(PDO::FETCH_ASSOC)){
		//--- Création d'indicateur concernant l'attribut
		$flags = null;
		//──┐ Ajouter P si c'est pour un Pet
		if(preg_match("#Pet#", $faQuery["TAG"])) $flags .= "P";
		//──┐ Ajouter M si c'est pour un Modifier
		if(preg_match("#Modifier#", $faQuery["TAG"])) $flags .= "M";
		//──┐ Ajouter R si c'est une range
		if(preg_match("#R$#", $faQuery["TAG"])) $flags .= "R";
		
		//--- Ajouter les flag si existant
		if($flags) $faQuery["NAME"] .= " [$flags]";
		
		
		$attributes_names[] = Array(
			"ATTRIBUTE_ID" => $faQuery["ID"],
			"ATTRIBUTE_TAG" => $faQuery["TAG"],
			"ATTRIBUTE_NAME" => $faQuery["NAME"]
		);
	}
	
	return $attributes_names;
}
?>