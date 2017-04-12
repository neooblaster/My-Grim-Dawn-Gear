<?php
/** -------------------------------------------------------------------------------------------------------------------- ** 
/** -------------------------------------------------------------------------------------------------------------------- ** 
/** ---																																					--- **
/** --- 														------------------------															--- **
/** ---																{ index.php }																	--- **
/** --- 														------------------------															--- **
/** ---																																					--- **
/** ---		AUTEUR 	: Nicolas DUPRE																											--- **
/** ---																																					--- **
/** ---		RELEASE	: 06.09.2015																												--- **
/** ---																																					--- **
/** ---		VERSION	: 1.0																															--- **
/** ---																																					--- **
/** ---																																					--- **
/** --- 														-----------------------------														--- **
/** --- 															{ C H A N G E L O G } 															--- **
/** --- 														-----------------------------														--- **	
/** ---																																					--- **
/** ---																																					--- **
/** ---		VERSION 1.0 : 06.09.2015																											--- **
/** ---		------------------------																											--- **
/** ---			- Première release																												--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **

	Objectif du script :
	---------------------
	
	Description fonctionnelle :
	----------------------------
	
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** ---																																					--- **
/** ---													PHASE 1 - INITIALISATION DU SCRIPT													--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** > Chargement des Paramètres **/
	require_once "params.php";		// Params du "module"
	//???// require_once "../params.php";	// Params de l'applis Master
	//exit;

/** > Ouverture des SESSIONS Globales **/
	//require_once "../session_start.php";

/** > Chargement des Classes **/
	//preprend// require_once "Classes/Template.class.php";

/** > Chargement des Configs **/
/** > Chargement des Fonctions **/

/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** ---																																					--- **
/** ---												PHASE 2 - CONTROLE DES AUTORISATIONS													--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **/
//if($_SESSION['USER_SESSION']['RANK_LEVEL'] < 4){
//	header("Location: ../index.php");
//}

/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** ---																																					--- **
/** ---												PHASE 3 - INITIALISAITON DES DONNEES													--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **/
/** > Déclaration des variables **/
	$mode;				// Défini le mode du gestionnaire (Dev:compilation | App:deployement)
	$moteur;				// Moteur de renderisation des pages
	$PACKAGES_LIST;	// Variable de référence pour générée le document final
	$will_depend;

/** > Initialisation des variables **/
	$PACKAGES_LIST = Array();
	$will_depend = 'FULL_RELEASE';

/** > Déclaration et Intialisation des variables pour le moteur (référence) **/
	$vars = Array(
		// @VARS
		'MODE' => FILES_MANAGER_MODE,
		'WILL_DEPEND' => &$will_depend,
		
		// @ARRAY
		"PACKAGES_LIST" => &$PACKAGES_LIST
	);


/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** ---																																					--- **
/** ---									PHASE 4 - EXECUTION DU SCRIPT DE TRAITEMENT DE DONNEES										--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **/
/** > Lecture des packages **/
	$packages = scandir('Packages', 1);

	foreach($packages as $key => $value){
		if(preg_match('#^Package#', $value)){
			$package = explode('_', $value); // Package_type_V_v.vv.vv_hash
			
			$hash = explode('.', $package[4]);
			
			$package_type = $package[1];
			$size = filesize('Packages/'.$value);
			
			if($size < 1024){
				$size .= ' Octets';
			} else if($size >= 1024 && $size < 1048576) {
				$size = round(($size / 1024), 2);
				$size .= ' Ko';
			} else if($size >= 1048576 && $size < 1073741824) {
				$size = round(($size / (1024 * 1024)), 2);
				$size .= ' Mo';
			} else if($size >= 1073741824 && $size < 1099511627776) {
				$size = round(($size / (1024 * 1024 * 1024)), 2);
				$size .= ' Go';
			}
			
			
			$PACKAGES_LIST[] = Array(
				'DATE' => date('Y.m.d', filemtime('Packages/'.$value)),
				'TYPE' => $package_type,
				'VERSION' => $package[3],
				'HASH' => $hash[0],
				'SIZE' => $size,
				'FILE' => $value
			);
		}
	}


/** > Recherche de la prochaine dépendance **/
	if(file_exists('Packages/LAST_MD5_SNAPSHOT')){
		$last_md5_snap = fopen('Packages/LAST_MD5_SNAPSHOT', 'r');
		
		while($buffer = fgets($last_md5_snap)){
			if(preg_match('#^snapshot_version_name=#', $buffer)){
				$buffer = str_replace("\n", "", $buffer);
				$buffer = explode("=", $buffer);
				$will_depend = $buffer[1];
			}
		}
		
		fclose($last_md5_snap);
	}


/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** ---																																					--- **
/** ---											PHASE 5 - GENERATION DES DONNEES DE SORTIE												--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **/

/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** ---																																					--- **
/** ---												PHASE 6 - AFFICHER LES SORTIES GENEREE													--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **/
/** > Création du moteur **/
	$moteur = new Template();

/** > Configuration du moteur **/
	$moteur->set_output_name('index.html');
	$moteur->set_template_file('Templates/index.master.tpl.html');
	$moteur->set_temporary_repository('Temps');

/** > Envoie des données **/
	$moteur->set_vars($vars);

/** > Execution du moteur **/
	$moteur->render()->display();
?>