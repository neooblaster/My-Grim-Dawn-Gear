#!/usr/bin/php
<?php
/** -------------------------------------------------------------------------------------------------------------------- ** 
/** -------------------------------------------------------------------------------------------------------------------- ** 
/** ---																																					--- **
/** --- 											-----------------------------------------------											--- **
/** ---																{ dump_db.sql }																--- **
/** --- 											-----------------------------------------------											--- **
/** ---																																					--- **
/** ---		AUTEUR 	: Nicolas DUPRE																											--- **
/** ---																																					--- **
/** ---		RELEASE	: 15.04.2017																												--- **
/** ---																																					--- **
/** ---		VERSION	: 1.0																															--- **
/** ---																																					--- **
/** ---																																					--- **
/** --- 														-----------------------------														--- **
/** --- 															{ C H A N G E L O G } 															--- **
/** --- 														-----------------------------														--- **	
/** ---																																					--- **
/** ---																																					--- **
/** ---		VERSION 1.0 : 15.04.2017																											--- **
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
/** -------------------------------------------------------------------------------------------------------------------- **/
echo "#---------------------------------------------------------------------------------------------------#".PHP_EOL;
echo "#                                                                                                   #".PHP_EOL;
echo "#                                       Execution de dump.php                                       #".PHP_EOL;
echo "#                                                                                                   #".PHP_EOL;
echo "#---------------------------------------------------------------------------------------------------#".PHP_EOL;
echo "#".PHP_EOL;
echo "# Environnement de travail actuel : ".getcwd().PHP_EOL;
echo "#".PHP_EOL;

/** > Définition du Working Directory **/
echo "# Définition du nouvel environnement de travail à : ".dirname(__FILE__).PHP_EOL;
echo "#".PHP_EOL;

chdir(dirname(__FILE__));

/** > Chargement des Paramètres **/
echo "# Chargement des paramètres....".PHP_EOL;
echo "#".PHP_EOL;

require_once "/var/www/Prepends/Functions/json_to.php";
json_to(file_get_contents("../Configs/config.application.credentials.json"));

/** > Ouverture des SESSIONS Globales **/
/** > Chargement des Classes **/
/** > Chargement des Configs **/

/** > Chargement des Fonctions **/
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** ---																																					--- **
/** ---												PHASE 2 - CONTROLE DES AUTORISATIONS													--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **/
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** ---																																					--- **
/** ---												PHASE 3 - INITIALISAITON DES DONNEES													--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **/
/** > Déclaration des variables **/
$now;			// TIMESTAMP	:: Timestamp de l'heure d'execution de ce script
$day;			// STRING		:: Le jour de l'execution
$month;		// STRING		:: Le mois de l'execution
$year;		// STRING		:: L'année de l'execution
$hours;		// STRING		:: L'heure de l'execution
$minutes;	// STRING		:: Les minutes de l'execution
$lifetime;	// INTEGER		:: Durée de vie des Dump SQL

/** > Initialisation des variables **/
$lifetime = 15;

/** > Déclaration et Intialisation des variables pour le moteur (référence) **/
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** ---																																					--- **
/** ---									PHASE 4 - EXECUTION DU SCRIPT DE TRAITEMENT DE DONNEES										--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **/
/** Calcul des paramètres **/
	// Récupération de la date actuelle
	$now = time();

	// Calcul de : DAY, MONTH, YEAR
	$day = date('d', $now);
	$month = date('m', $now);
	$year = date('y', $now);
	$hours = date('H', $now);
	$minutes = date('i', $now);

	$datecode = date('Y_m_d_H_i', $now);
	$timecode = mktime(date('H', $now), 0, 0, date('n', $now), date('j', $now), date('Y', $now));
/** Lire le dossier **/
	$dumpfolder = scandir('.');

/** Supprimer les anciens fichier (durée de vie : xxx ) **/
	echo "# Suppression des dumps SQL agé de plus de $lifetime jours....".PHP_EOL;
	echo "#".PHP_EOL;

	for($i = 0; $i < count($dumpfolder); $i++){
		$file = $dumpfolder[$i];
		
		if(preg_match('#^SQLDump#', $file)){
			$date_params = explode('_', $file);
			preg_match('#[0-9]{1,}#' ,$date_params[count($date_params) - 1], $file_timestamp);
			
			if(($timecode - intval($file_timestamp[0])) >= ($lifetime * 24 * 3600)){
				echo "#	> Suppression du dump : $file ........ ";
				
				if(@unlink($file)){
					echo "terminée !".PHP_EOL;
				} else {
					echo "échouée !".PHP_EOL;
				}
			}
		}
	}

	echo "#".PHP_EOL;

/** Ecriture de la commande **/
	echo "# Génération de la commande de dump....".PHP_EOL;
	echo "#".PHP_EOL;

	$server = SQL_SERVER;
	$user = SQL_USER;
	$password = SQL_PASSWORD;
	$database = SQL_DATABASE;
	$target_file = "SQLDump_".$datecode."_".$timecode.".sql";

	$command = "mysqldump --add-drop-table -h $server -u $user -p$password $database > $target_file";

/** Execution de la commande **/
	echo "# Execution de la commande de dump [void mean success] :: ";

	$test = shell_exec($command);



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
/** > Configuration du moteur **/
/** > Envoie des données **/
/** > Execution du moteur **/
echo PHP_EOL.PHP_EOL;
?>