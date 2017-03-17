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
/** ---		RELEASE	: 01.04.2015																												--- **
/** ---																																					--- **
/** ---		VERSION	: 2.0																															--- **
/** ---																																					--- **
/** ---																																					--- **
/** --- 														-----------------------------														--- **
/** --- 															{ C H A N G E L O G } 															--- **
/** --- 														-----------------------------														--- **	
/** ---																																					--- **
/** ---																																					--- **
/** ---		VERSION 2.0 : 01.04.2015																											--- **
/** ---		------------------------																											--- **
/** ---			- Modification de la fonction pour fonctionner avec un nombre x fois de chemin vers des fichier de 	--- **
/** ---				langues																															--- **
/** ---																																					--- **
/** ---		VERSION 1.0 : 29.03.2015																											--- **
/** ---		------------------------																											--- **
/** ---			- Première release																												--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **

	Input Params :
	--------------
		string $lang_file :: Path vers le fichier de langue XML que l'on souhaite unpacker. Peut traiter autant de fichiers que nécessaire
	
	Output Params :
	---------------
	
	Role de la fonction :
	---------------------
		Traiter le fichier de lange XML et retournée un tableau KEY=>VALUE d'une part pour les page générée côté serveur
		et d'autre part en tant que variable côté client pour les scripts JS

/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **/
function lang_unpacker($lang_files){
	/** > Récupérer tout les paths donnée en paramètre (1 minimum) **/
	$lang_files = func_get_args();
		
	/** > Initialiser les tableaux de sortie **/
	$client_side_target = Array();
	$server_side_target = Array();
	
	foreach($lang_files as $key => $file){
		/** > Vérifier que le fichier existe **/
		if(file_exists($file)){
			/** > Ouvrir le fichier demandé **/
			$content = file_get_contents($file);
			
			/** > Parser le contenu (XML) **/
			$resources = new SimpleXMLElement($content);
		
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
		} else {
			die("lang_unpacker() failed; The lang file '$file' does not exist.");
		}
	}
	
	/** > Renvoyer les données triée **/
	return Array("client" => $client_side_target, "Serveur" => $server_side_target);
}
