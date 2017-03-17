<?php
/** -------------------------------------------------------------------------------------------------------------------- ** 
/** -------------------------------------------------------------------------------------------------------------------- ** 
/** ---																																					--- **
/** --- 										------------------------------------------------											--- **
/** ---												{ L A N G _ U N P A C K E R . P H P }													--- **
/** --- 										------------------------------------------------											--- **
/** ---																																					--- **
/** ---		AUTEUR 	: Neoblaster																												--- **
/** ---																																					--- **
/** ---		RELEASE	: 29.03.2015																												--- **
/** ---																																					--- **
/** ---		VERSION	: 1.0																															--- **
/** ---																																					--- **
/** ---																																					--- **
/** --- 														-----------------------------														--- **
/** --- 															{ C H A N G E L O G } 															--- **
/** --- 														-----------------------------														--- **	
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
		string $lang_file :: Path vers le fichier de langue XML que l'on souhaite unpacker
	
	Output Params :
	---------------
	
	Role de la fonction :
	---------------------
		Traiter le fichier de lange XML et retournée un tableau KEY=>VALUE d'une part pour les page générée côté serveur
		et d'autre part en tant que variable côté client pour les scripts JS

/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **/
function lang_unpacker($lang_file){
	/** > Vérifier que le fichier existe **/
	if(file_exists($lang_file)){
		/** > Ouvrir le fichier demandé **/
		$content = file_get_contents($lang_file);
		
		/** > Parser le contenu (XML) **/
		$resources = new SimpleXMLElement($content);
		
		/** > Initialiser les tableaux de sortie **/
		$client_side_target = Array();
		$server_side_target = Array();
		
		/** > Parcourir les ressources **/
		for($i = 0; $i < count($resources); $i++){
			/** > Extraction des attributs **/
			$key = strval($resources->resource[$i]->attributes()->KEY);
			$cst = strval($resources->resource[$i]->attributes()->CST);
			$sst = strval($resources->resource[$i]->attributes()->SST);
			$value = strval($resources->resource[$i]);
			
			if($cst === 'true'){
				$client_side_target[] = Array("VAR_KEY" => $key, "VAR_VALUE" => $value);
			}
			
			if($sst === 'true'){
				$server_side_target[$key] = $value;
			}
		}
		
		/** > Renvoyer les données triée **/
		return Array("client" => $client_side_target, "Serveur" => $server_side_target);
	} else {
		die("lang_unpacker() failed; The lang file does not exist.");
	}
}
