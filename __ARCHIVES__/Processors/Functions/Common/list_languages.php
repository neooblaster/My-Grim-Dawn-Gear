<?php
/** -------------------------------------------------------------------------------------------------------------------- ** 
/** -------------------------------------------------------------------------------------------------------------------- ** 
/** ---																																					--- **
/** --- 										------------------------------------------------											--- **
/** ---													{ L I S T _ L A N G U A G E S }														--- **
/** --- 										------------------------------------------------											--- **
/** ---																																					--- **
/** ---		AUTEUR 	: Neoblaster																												--- **
/** ---																																					--- **
/** ---		RELEASE	: 02.04.2015																												--- **
/** ---																																					--- **
/** ---		VERSION	: 1.0																															--- **
/** ---																																					--- **
/** ---																																					--- **
/** --- 														---------------------------														--- **
/** ---															{ C H A N G E L O G }															--- **
/** --- 														---------------------------														--- **
/** ---																																					--- **
/** ---																																					--- **
/** ---		VERSION 1.0 :																															--- **
/** ---		-------------																															--- **
/** ---			- Première release																												--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **

	Input Params :
	--------------
		$path_to_xml_file	[String]	: Chemin vers le fichier XML contenant la liste des langues existante
	
	Output Params :
	---------------

/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **/
function list_languages($path_to_xml_file){
	/** > Déclaration des variables **/
		$languages;	// Liste des langues disponible 
		$keys;		// Liste des languages dans leur format de code xx-XX (keys)
		$list;		// Liste des languages avec donnée xx-XX se nomme NAME
	
	/** > Chargement de la liste des langues disponible **/
		$languages = new SimpleXMLElement(file_get_contents($path_to_xml_file));
		
	/** > Lister les langues **/
	for($i = 0; $i < count($languages); $i++){
		$keys[strval($languages->language[$i]->attributes()->LANG)] = strval($languages->language[$i]);
		$list[] = Array(
			"LANG_NAME" => strval($languages->language[$i]),
			"LANG_KEY" => strval($languages->language[$i]->attributes()->LANG)
		);
	}
	
	return Array(
		"KEYS" => $keys,
		"LIST" => $list
	);
}
?>









