#!/usr/bin/php
<?php
/** -------------------------------------------------------------------------------------------------------------------- ** 
/** -------------------------------------------------------------------------------------------------------------------- ** 
/** ---																																					--- **
/** --- 											-----------------------------------------------											--- **
/** ---																{ DBUpdater.php }																--- **
/** --- 											-----------------------------------------------											--- **
/** ---																																					--- **
/** ---		TAB SIZE			: 3																													--- **
/** ---																																					--- **
/** ---		AUTEUR			: Nicolas DUPRE																									--- **
/** ---																																					--- **
/** ---		RELEASE			: 12.04.2017																										--- **
/** ---																																					--- **
/** ---		FILE_VERSION	: 1.3 NDU																											--- **
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
/** --- 														-----------------------------														--- **
/** --- 															{ C H A N G E L O G } 															--- **
/** --- 														-----------------------------														--- **	
/** ---																																					--- **
/** ---																																					--- **
/** ---		VERSION 1.3 : 12.04.2017 : NDU																									--- **
/** ---		------------------------------																									--- **
/** ---			- Ajout de données pré-défini pour les cible de donnée ITEMS : 													--- **
/** ---																																					--- **
/** ---		VERSION 1.2 : 12.04.2017 : NDU																									--- **
/** ---		------------------------------																									--- **
/** ---			- Ajout de données pré-défini pour les cible de donnée ITEMS : 													--- **
/** ---				> WIDTH  																														--- **
/** ---				> HEIGHT 																														--- **
/** ---																																					--- **
/** ---		VERSION 1.1 : 11.04.2017 : NDU																									--- **
/** ---		------------------------------																									--- **
/** ---			- Ajout de traitement de remplacement sur les tags																		--- **
/** ---																																					--- **
/** ---		VERSION 1.0 : 10.04.2017 : NDU																									--- **
/** ---		------------------------------																									--- **
/** ---			- Première release																												--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **

	Objectif du script :
	---------------------
		
		#!/usr/bin/php
	
	Description fonctionnelle :
	----------------------------
	
		Root :
			/var/www/dev.my-grimdawn-gear.com/Processors/Scripts/CRON/
		
		Path D'inclusion :
						 ./	/var/www/dev.my-grimdawn-gear.com/Processors/Scripts/CRON/
						../	/var/www/dev.my-grimdawn-gear.com/Processors/Scripts/
					../../	/var/www/dev.my-grimdawn-gear.com/Processors/
				../../../	/var/www/dev.my-grimdawn-gear.com/
			../../../../	/var/www/
			
		Flag pour trouver où ajouter du traitement décisionnel en fonction des DB :: [FLAG::HERE]
		
		
		Flags trouvés
		--------------
			
			[ms] Masculin Singulier
			[fs] Feminin Singulier
			
			{^W}
	
	
	
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** ---																																					--- **
/** ---													PHASE 1 - INITIALISATION DU SCRIPT													--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** > Chargement des Fonctions **/
	//--- Normalement Prepended
	//──┐ Classes
	require_once "../../../../Prepends/Classes/Template.class.php";
	require_once "../../../../Prepends/Classes/SYSLang.class.php";

	//──┐ Functions
	require_once "../../../../Prepends/Functions/echos.php";
	require_once "../../../../Prepends/Functions/json_to.php";
	require_once "../../../../Prepends/Functions/pprint.php";
	require_once "../../../../Prepends/Functions/pprints.php";
	require_once "../../../../Prepends/Functions/setup.php";

	//──┐ Setups
	const __ROOT__ = "/var/www/dev.my-grimdawn-gear.com/";
	require_once "../../../../Prepends/Setups/setup.const.special_char.php";


	//--- Applicative à MGDM


/** > Chargement des Paramètres **/
	require_once "../../../Setups/setup.application.php";
	require_once "../../../Setups/setup.sessions.php";
	require_once "../../../Setups/setup.pdo.php";

/** > Ouverture des SESSIONS Globales **/
/** > Chargement des Classes **/
/** > Chargement des Configs **/



/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** ---																																					--- **
/** ---												PHASE 2 - DECLARATION DES FUNCTIONS														--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **/
//[FLAG::HERE]
/** > Function de récupération des TAGS **/
function load_tags(&$array, $table){
	global $PDO;
	
	$pQuery = $PDO->prepare("SELECT TAG FROM $table");
	$pQuery->execute(Array());
	
	$array[$table] = Array();
	
	while($faQuery = $pQuery->fetch(PDO::FETCH_ASSOC)){
		$array[$table][] = $faQuery["TAG"];
	}
}



/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** ---																																					--- **
/** ---												PHASE 3 - INITIALISAITON DES DONNEES													--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **/
/** > Déclaration des variables **/
	$usleep;				// INTEGER			:: Delais d'attente pour afficher l'instruction suivante : Permet la lecture
	$urls;				// ARRAY				:: Liste des adresses où se trouvent les packages de langues
	$cfg_file_urls;	// Resource			:: Pointeur dans le fichier de config des URLS
	$pIdentifiers;		// PDOStatement	:: Requete SQL Préparée contenant les identifiers de langue
	$pItems;				// PDOStatement	:: Requete SQL Préparée contenant les objet déjà enregistré (indiférent à la langue)
	$faIdentifiers;	// ARRAY				:: Données fetchées depuis $pIdentifier
	$faItems;			// ARRAY				:: Données fetchées depuis $pItems
	$identifiers;		// ARRAY				:: Liste des tag d'identification
	$langs;				// ARRAY				:: Liste des langues trouvée
	$CURL;				// CURL				:: Pointeur CURL
	$tags_files;		// ARRAY				:: Liste des fichiers à traiter (Cf Configs/config.cron.tags-files.php)
	$zip_path;			// STRING			:: Emplacement vers l'archive
	$temp_path;			// STRING			:: Emplacement du dossier temporaire

	$datas;				// ARRAY				:: Liste des données déjà enregistrée
	$names;				// ARRAY				:: Liste des texte déjà enregistrée

	$inserts;			// ARRAY				:: Liste des texts à insérer
	$updates;			// ARRAY				:: Liste des texts à updater

	$cmt;					// STRING			:: Color Modifier Tag
	$cmtb;				// STRING			:: Color Modifier table
	$cml;					// STRING			:: Color Modifier Lang
	$cms;					// STRING			:: Color Modifier Successfull
	$cmw;					// STRING			:: Color Modifier Warning
	$cmerr;				// STRING			:: Color Modifier Error
	$cme;					// STRING			:: Color Modifier End

	$first_lang;		// BOOLEAN			:: Indique qu'il s'agit de la première langue traité.

	


/** > Initialisation des variables **/
	$first_lang = true;

	$cmt = "\e[38;5;113m";
	$cmtb = "\e[38;5;117m";
	$cml = "\e[38;5;220m";
	$cms = "\e[38;5;118m";
	$cmw = "\e[38;5;208m";
	$cmerr = "\e[38;5;160m";
	$cme = "\e[0m";

	$temp_path = __ROOT__."/Temps";
	$zip_path = __ROOT__."/Temps/%s.zip";

	$usleep = 25000;

	$urls = Array();
	$langs = Array();
	$identifiers = Array();

	$datas = Array();
	$names = Array();

	$inserts = Array();
	$updates = Array();

	// Chargement de tags_files
	require_once "../../../Configs/config.cron.tags-files.php";


/** > Déclaration et Intialisation des variables pour le moteur (référence) **/



/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** ---																																					--- **
/** ---													PHASE 4 - RECUPERATION DES DONNEES													--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **/
/** --- Phase 4.1 :: Récupération des URLs définies dans le fichier de configuration --- **/
/** ------------------------------------------------------------------------------------ **/
$cfg_file_urls = fopen(__ROOT__."/Configs/config.cron.lang-urls.txt", "r");

while($buffer = fgets($cfg_file_urls)){
	// Ignorer les lignes commentées
	if(!preg_match("#^\##", $buffer)){
		list($lang, $url) = explode("::", $buffer);
		
		$urls[$lang] = preg_replace("#\s$#", "", $url);
		$langs[] = $lang;
	}
}

fclose($cfg_file_urls);



/** ------------------------------------------------------------------------------------ **/
/** --- Phase 4.2 :: Récupération des fichiers ZIP                                   --- **/
/** ------------------------------------------------------------------------------------ **/
foreach($urls as $lang => $url){
	echo sprintf("Retrieving ZIP File of language :: $cml%s$cme\n", $lang);
	
	/** Création du fichier ZIP cible pour CURL **/
	$zip = fopen(sprintf($zip_path, $lang), "w+");
	
	/** Initialisation du pointeur CURL **/
	$CURL = curl_init($url);
	
	/** Configuration du pointeur **/
	//──┐ Rediriger le resultat vers un fichier
	curl_setopt($CURL, CURLOPT_FILE, $zip);
	//──┐ Délais d'attente maximal
	curl_setopt($CURL, CURLOPT_TIMEOUT, 60);
	
	/** Execution du pointeur & fermeture **/
	curl_exec($CURL);
	curl_close($CURL);
}



/** ------------------------------------------------------------------------------------ **/
/** --- Phase 4.3 :: Récupération des identifiers                                    --- **/
/** ------------------------------------------------------------------------------------ **/
$pIdentifiers = $PDO->prepare("SELECT * FROM IDENTIFIERS ORDER BY TAG ASC");
$pIdentifiers->execute(Array());

while($faIdentifiers = $pIdentifiers->fetch(PDO::FETCH_ASSOC)){
	$identifiers[] = $faIdentifiers;
}



/** ------------------------------------------------------------------------------------ **/
/** --- Phase 4.4 :: Récupération des objets connu                                   --- **/
/** ------------------------------------------------------------------------------------ **///[FLAG::HERE]
/** > 4.4.1. Objet ITEMS **/
load_tags($datas, "ITEMS");




/** ------------------------------------------------------------------------------------ **/
/** --- Phase 4.5 :: Récupération des texts                                          --- **/
/** ------------------------------------------------------------------------------------ **///[FLAG::HERE]
/** > 4.5.1. Texts des objets (TAGS_NAMES) **/
$pItems = $PDO->prepare("SELECT * FROM TAGS_NAMES");
$pItems->execute(Array());

while($faItems = $pItems->fetch(PDO::FETCH_ASSOC)){
	if(!isset($names["TAGS_NAMES"])) $names["TAGS_NAMES"] = Array();
	if(!isset($names["TAGS_NAMES"][$faItems["LANG"]])) $names["TAGS_NAMES"][$faItems["LANG"]] = Array();
	
	$names["TAGS_NAMES"][$faItems["LANG"]][$faItems["TAG"]] = $faItems;
}




/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** ---																																					--- **
/** ---									PHASE 5 - EXECUTION DU SCRIPT DE TRAITEMENT DE DONNEES										--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **///pprints($identifiers, $datas, $names);
/** --- Phase 5.1 :: Procéder à la découverte des entrée (ITEMS, ATTRIBUTES) & Assimlilation des textes --- **
/** ------------------------------------------------------------------------------------------------------- **/
foreach($langs as $index => $lang){
	/** Extraction des fichiers **/
	$zip = new ZipArchive();
	$zip->open(sprintf($zip_path, $lang), ZipArchive::CHECKCONS);
	$zip->extractTo($temp_path."/$lang", $tags_files);
	$zip->close();
	
	
	/** Parcourir les fichiers **/
	foreach($tags_files as $index => $file){
		$handler = fopen($temp_path."/$lang/$file", "r");
		
		/** Parcourir le fichier **/
		while($buffer = fgets($handler)){
			/** Parcourir les identifiers **/
			foreach($identifiers as $index => $identifier){
				/** Initialisation préalable en cas de non définition. Prévenir des erreur PHP E_NOTICE **/
				if(!isset($names[$identifier["TABLE_NAME"]])) $names[$identifier["TABLE_NAME"]] = Array();
				if(!isset($names[$identifier["TABLE_NAME"]][$lang])) $names[$identifier["TABLE_NAME"]][$lang] = Array();
				
				if(!isset($datas[$identifier["TABLE_DATA"]])) $datas[$identifier["TABLE_DATA"]] = Array();
				if(!isset($datas[$identifier["TABLE_DATA"]][$lang])) $datas[$identifier["TABLE_DATA"]][$lang] = Array();
				
				
				
				/** Définition du pattern de recherche **/
				$pattern = "#^".$identifier["TAG"]."#";
				
				/** Si le modèle est identifié **/
				if(preg_match($pattern, $buffer)){
					/** Séparation du tag de sa valeur **/
					$eqpos = strpos($buffer, "=");
					$tag = substr($buffer, 0, $eqpos);
					$value = preg_replace("#\s$#", "", substr($buffer, $eqpos+1));
					
					
					/** Si aucune valeur n'est défini, on passe **/
					if($value === "") continue;
					
					
					/** Processing sur le tag **/
					//──┐ Faut-il l'ignorer ?
					if($identifier["IGNORE"] !== ""){if(preg_match("#".$identifier["IGNORE"]."#", $tag)) break;}
					//──┐ Cleansing à l'aide du modèle
					if($identifier["CLEAN"]){$tag = preg_replace("#".$identifier["CLEAN"]."#", "", $tag);}
					//──┐ Operer des modifications sur le taf
					if($identifier["REPLACE"]) {}
					
					
					
					/** Procssing sur la valeur **/
					//──┐ Supprimer les codes spéciaux {^[A-Z]}
					$value = preg_replace("#\{?\^[a-zA-Z]\}?#", "", $value);
					//──┐ Supprimer les guillemets de début et de fin
					$value = preg_replace('#^"|"$#', "", $value);
					//\{%(\+)?\.?[0-9]?[a-z][0-9]\}
					
					
					/** Est-ce une description ? **/
					if(preg_match("#".$identifier["DESCRIPTION"]."#", $tag)){
						// Dans le cas d'un description, alors il faut identifier le tag name de base
						$base_tag = preg_replace("#".$identifier["DESCRIPTION"]."#", "", $tag);
						
						// Si le tag de base est connu alors...
						if(array_key_exists($base_tag, $names[$identifier["TABLE_NAME"]][$lang])){
							// Controler le MD5
							if(md5($value) !== $names[$identifier["TABLE_NAME"]][$lang][$base_tag]["DESCRIPTION_MD5"]){
								if(!isset($updates[$identifier["TABLE_NAME"]])) $updates[$identifier["TABLE_NAME"]] = Array();
								if(!isset($updates[$identifier["TABLE_NAME"]][$lang])) $updates[$identifier["TABLE_NAME"]][$lang] = Array();
								if(!isset($updates[$identifier["TABLE_NAME"]][$lang][$base_tag])) $updates[$identifier["TABLE_NAME"]][$lang][$base_tag] = Array();
								
								$updates[$identifier["TABLE_NAME"]][$lang][$base_tag]["ID"] = $names[$identifier["TABLE_NAME"]][$lang][$base_tag]["ID"];
								$updates[$identifier["TABLE_NAME"]][$lang][$base_tag]["DESCRIPTION"] = $value;
								$updates[$identifier["TABLE_NAME"]][$lang][$base_tag]["DESCRIPTION_MD5"] = md5($value);
							}
						}
						// Sinon, c'est un ajout
						else {
							if(!isset($inserts[$identifier["TABLE_NAME"]])) $inserts[$identifier["TABLE_NAME"]] = Array();
							if(!isset($inserts[$identifier["TABLE_NAME"]][$lang])) $inserts[$identifier["TABLE_NAME"]][$lang] = Array();
							if(!isset($inserts[$identifier["TABLE_NAME"]][$lang][$base_tag])) $inserts[$identifier["TABLE_NAME"]][$lang][$base_tag] = Array();
							
							$inserts[$identifier["TABLE_NAME"]][$lang][$base_tag]["TAG"] = $base_tag;
							$inserts[$identifier["TABLE_NAME"]][$lang][$base_tag]["DESCRIPTION"] = $value;
							$inserts[$identifier["TABLE_NAME"]][$lang][$base_tag]["DESCRIPTION_MD5"] = md5($value);
						}
					}
					
					/** Sinon, traitement par défaut **/
					else {
						/** Identifier le genre de l'objet  **/
						$gender = "ms";
						$matches = Array();
						preg_match("#\[([a-zA-Z]{2})\]#", $value, $matches);
						
						if(isset($matches[1])) $gender = $matches[1];
						
						
						/** Regardé du côté des langues (autre cas que la description) **/
						// Si le tag est connu alors...
						if(array_key_exists($tag, $names[$identifier["TABLE_NAME"]][$lang])){
							// Controler le MD5
							if(md5($value) !== $names[$identifier["TABLE_NAME"]][$lang][$tag]["NAME_MD5"]){
								if(!isset($updates[$identifier["TABLE_NAME"]])) $updates[$identifier["TABLE_NAME"]] = Array();
								if(!isset($updates[$identifier["TABLE_NAME"]][$lang])) $updates[$identifier["TABLE_NAME"]][$lang] = Array();
								if(!isset($updates[$identifier["TABLE_NAME"]][$lang][$tag])) $updates[$identifier["TABLE_NAME"]][$lang][$tag] = Array();
								
								$updates[$identifier["TABLE_NAME"]][$lang][$tag]["ID"] = $names[$identifier["TABLE_NAME"]][$lang][$tag]["ID"];
								$updates[$identifier["TABLE_NAME"]][$lang][$tag]["GENDER"] = $gender;
								$updates[$identifier["TABLE_NAME"]][$lang][$tag]["NAME"] = $value;
								$updates[$identifier["TABLE_NAME"]][$lang][$tag]["NAME_MD5"] = md5($value);
							}
						}
						// Sinon, c'est un ajout
						else {
							if(!isset($inserts[$identifier["TABLE_NAME"]])) $inserts[$identifier["TABLE_NAME"]] = Array();
							if(!isset($inserts[$identifier["TABLE_NAME"]][$lang])) $inserts[$identifier["TABLE_NAME"]][$lang] = Array();
							if(!isset($inserts[$identifier["TABLE_NAME"]][$lang][$tag])) $inserts[$identifier["TABLE_NAME"]][$lang][$tag] = Array();
							
							$inserts[$identifier["TABLE_NAME"]][$lang][$tag]["TAG"] = $tag;
							$inserts[$identifier["TABLE_NAME"]][$lang][$tag]["GENDER"] = $gender;
							$inserts[$identifier["TABLE_NAME"]][$lang][$tag]["NAME"] = $value;
							$inserts[$identifier["TABLE_NAME"]][$lang][$tag]["NAME_MD5"] = md5($value);
						}
						
						
						/** Regardé du côté des données **/
						if(!in_array($tag, $datas[$identifier["TABLE_DATA"]])){
							switch($identifier["TABLE_DATA"]){
								//[FLAG::HERE]
								case "ITEMS":
									// Composition de la requête SQL
									$query = "INSERT INTO ITEMS (FAMILY, TYPE, QUALITY, TAG, ATTACHMENT, WIDTH, HEIGHT) VALUES(:FAMILY, :TYPE, :QUALITY, :TAG, :ATTACHMENT, :WIDTH, :HEIGHT)";
									$bound_tokens = Array(
										":FAMILY" => $identifier["FAMILY"],
										":TYPE" => $identifier["TYPE"],
										":QUALITY" => $identifier["QUALITY"],
										":TAG" => $tag,
										":ATTACHMENT" => $identifier["ATTACHMENT"],
										":WIDTH" => $identifier["WIDTH"],
										":HEIGHT" => $identifier["HEIGHT"]
									);
									
									// Envoyer un message 
									echo sprintf("TAG '$cmt% 40s$cme' ADDED INTO '$cmtb"."ITEMS$cme'.".LF, $tag);
									usleep($usleep);
									
									// Execution de la requête 
									$pQuery = $PDO->prepare($query);
									$pQuery->execute($bound_tokens);
								break;
								default:
									// N'emettre l'information uniquement pour la premiere langue traité (normalement en-EN la référence)
									if($first_lang){
										echo sprintf("TAG '$cmt% 40s$cme' $cmw"."HAS NO TABLE_DATA DEFINED$cme.".LF, $tag);
										usleep(0.5 * $usleep);
									}
								break;
							}
						} else {
							// N'emettre l'information uniquement pour la premiere langue traité (normalement en-EN la référence)
							if($first_lang){
								echo sprintf("TAG '$cmt% 40s$cme' ALREADY EXISTS INTO '$cmtb"."ITEMS$cme'.".LF, $tag);
								usleep(0.20 * $usleep);
							}
						}
					}
					
					/** Modèle trouvé, inutile de parcourir les autre identifier **/
					break;
				}
			}
		}
	}
	
	
	/** Recharger les tags **///[FLAG::HERE]
	echo sprintf("RELOADING TAGS FOR TABLE 'ITEMS'".LF, $tag);
	load_tags($datas, "ITEMS");
	
	$first_lang = false;
}




/** ------------------------------------------------------------------------------------------------------- **
/** --- Phase 5.2 :: Traitement des textes                                                              --- **
/** ------------------------------------------------------------------------------------------------------- **/
$operations = Array(
	"INSERT" => $inserts,
	"UPDATE" => $updates
);

//pprints($operations);

/** Procéder aux différentes opération SQL **/
foreach($operations as $sql_op => $array){
	/** Parcourir chaque tableau **/
	foreach($array as $table => $data){
		/** Parcourir chaque langue **/
		foreach($data as $lang => $texts){
			/** Parcourir chaque entrée **/
			foreach($texts as $tag => $fields){
				/** Elaboration de la requête SQL **/
				//──┐ Ajouter le champs LANG
				$fields_to_process = Array("LANG");
				//──┐ Ajouter le token :LANG
				$tokens_to_process = Array(":LANG");
				//──┐ Ajouter la valeur $lang au toke :LANG
				$values_to_process = Array(
					":LANG" => $lang
				);
				//──┐ Lang non requis pour les updates 
				$uvalues_to_process = Array();
				
				// Identification du tag
				$tag = $fields["TAG"];
				
				foreach($fields as $field => $value){
					//──┐ Ignorer le champs ID qui va servire pour la clause WHERE
					if($field === "ID") continue;
					
					//──┐ Cas insertion, pour nommer les tables
					$fields_to_process[] = $field;
					//──┐ Cas insertion, pour nommer les valeurs
					$tokens_to_process[] = ":$field";
					//──┐ Cas insertion & update, pour lier les valeurs
					$values_to_process[":$field"] = $value;
					//──┐ Cas update, Ensemble à mettre à jour sous la forme : field = :field_value
					$uvalues_to_process[] = "$field = :$field";
				}
				
				
				switch($sql_op){
					case "INSERT":
						$query = "INSERT INTO $table (".implode(", ", $fields_to_process).") VALUES (".implode(", ", $tokens_to_process).")";
						$message = "INSERT QUERY DONE $cms"."SUCESSFULLY$cme FOR LANG '$cml$lang$cme' FOR TAG '$cmt$tag$cme' IN TABLE $cmtb'$table'$cme";
					break;
					case "UPDATE":
						// Retirer :LANG des tokens 
						array_shift($values_to_process);
						$query = "UPDATE $table SET ".implode(", ", $uvalues_to_process)." WHERE ID = ".$fields["ID"];
						$message = "UPDATE QUERY DONE $cms"."SUCESSFULLY$cme FOR LANG '$cml$lang$cme' FOR TAG '$cmt$tag$cme' IN TABLE $cmtb'$table'$cme";
					break;
				}
				
				
				/** Execution de la requête SQL **/
				try {
					$pQuery = $PDO->prepare($query);
					$pQuery->execute($values_to_process);
					
					echo $message.LF;
					usleep($usleep);
				} catch (Exception $e){
					echo "SQL QUERY $cmerr"."FAILED$cme WITH ERROR ".$e->getMessage().LF;
					echo "QUERY IS :: $query".LF;
					echo "TOKEN ARE :: ".print_r($values_to_process).LF;
					usleep($usleep);
				}
			}
		}
	}
}



/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** ---																																					--- **
/** ---											PHASE 6 - GENERATION DES DONNEES DE SORTIE												--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **/



/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** ---																																					--- **
/** ---												PHASE 7 - AFFICHER LES SORTIES GENEREE													--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **/
/** > Création du moteur **/
/** > Configuration du moteur **/
/** > Envoie des données **/
/** > Execution du moteur **/
?>