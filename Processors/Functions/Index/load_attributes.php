<?php
/** -------------------------------------------------------------------------------------------------------------------- ** 
/** -------------------------------------------------------------------------------------------------------------------- ** 
/** ---																																					--- **
/** --- 										------------------------------------------------											--- **
/** ---														{ load_attributes.php }																--- **
/** --- 										------------------------------------------------											--- **
/** ---																																					--- **
/** ---		TAB SIZE			: 3																													--- **
/** ---																																					--- **
/** ---		AUTEUR			: Nicolas DUPRE																									--- **
/** ---																																					--- **
/** ---		RELEASE			: 17.04.2017																										--- **
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
/** ---		VERSION 1.0 : 17.04.2017 : NDU																									--- **
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
function load_attributes($lang, $attachment=null, $id=null){
	/** > Accession au PDO **/
	global $PDO;
	
	/** > Déclaration des variables **/
	$query;			// STRING	:: Requête SQL à jouer
	$clause;			// STRING	:: Clause SQL de filtrage
	$tokens;			// ARRAY		:: Valeurs à passer à l'execution de la requête SQL
	$attributes;	// ARRAY		:: Liste des attributs
	$first;			// BOOLEAN	:: Indique si c'est la premier attributes
	
	
	/** > initialisation des variables **/
	$first = true;
	
	$clause = null;
	
	$attributes = Array();
	$tokens = Array(
		":lang" => $lang
	);
	
	
	/** > Traitement des arguments **/
	if($attachment && $id){
		$clause = "AND A.".strtoupper($attachment)." = :id";
		$tokens[":id"] = $id;
	};
	
	
	/** > Composition de la requête SQL **/
	$query = "
		SELECT
			A.ID,
			A.BASIC, A.PET, A.PROBABILITY, A.TIER, A.ATTACHMENT,
			A.MASTER_VALUE_1, A.MASTER_VALUE_2, A.SLAVE_VALUE_1, A.SLAVE_VALUE_2, 
			MAN.NAME AS MASTER_NAME, SAN.NAME AS SLAVE_NAME
			
		FROM ATTRIBUTES AS A
		LEFT JOIN ATTRIBUTES_NAMES AS MAN
		ON A.MASTER_TAG = MAN.TAG
		RIGHT JOIN ATTRIBUTES_NAMES AS SAN
		ON A.SLAVE_TAG = SAN.TAG
		
		WHERE 
			MAN.LANG = :lang
			AND SAN.LANG = :lang
			$clause
		
		ORDER BY
			A.TIER ASC, A.PROBABILITY DESC
	";
	
	
	/** > Execution de la requête SQL **/
	$pAttributes = $PDO->prepare($query);
	$pAttributes->execute($tokens);
	
	
	/** > Traitement des données **/
	while($faAttributes = $pAttributes->fetch(PDO::FETCH_ASSOC)){
		$master_name = $faAttributes["MASTER_NAME"];
		$slave_name = $faAttributes["SLAVE_NAME"];
		
		//──┐ Convertir le % en entité HTML
		$master_name = preg_replace("#%s(%)#", "%s&#37;", $master_name);
		$slave_name = preg_replace("#%s(%)#", "%s&#37;", $slave_name);
		
		//──┐ Ajouter la balise span autour des valeurs
		$master_name = preg_replace("#(\+?\s*%s(&\#37;)?)#", "<span>$1</span>", $master_name);
		$slave_name = preg_replace("#(\+?\s*%s(&\#37;)?)#", "<span>$1</span>", $slave_name);
		
		//──┐ Composer l'attribut a proprement parler
		$master_name = @sprintf($master_name, $faAttributes["MASTER_VALUE_1"], $faAttributes["MASTER_VALUE_2"]);
		$slave_name = @sprintf(" $slave_name", $faAttributes["SLAVE_VALUE_1"], $faAttributes["SLAVE_VALUE_2"]);
		
		$attribut = $master_name;
		if($faAttributes["SLAVE_NAME"]) $attribut .= $slave_name;
		
		$attributes[] = Array(
			"COMMA" => ($first) ? "" : ",",
			"ID" => $faAttributes["ID"],
			"BASIC" => ord($faAttributes["BASIC"]),
			"PET" => ord($faAttributes["PET"]),
			"TIER" => $faAttributes["TIER"],
			"ATTRIBUT" => $attribut,
			"ATTACHMENT" => $faAttributes["ATTACHMENT"],
			"PROBABILITY" => $faAttributes["PROBABILITY"] * 100,
			"MASTER_VALUE_1" => $faAttributes["MASTER_VALUE_1"],
			"MASTER_VALUE_2" => $faAttributes["MASTER_VALUE_2"],
			"SLAVE_VALUE_1" => $faAttributes["SLAVE_VALUE_1"],
			"SLAVE_VALUE_2" => $faAttributes["SLAVE_VALUE_2"]
		);
		
		$first = false;
	}
	
	return $attributes;
}
?>