<?php
/** -------------------------------------------------------------------------------------------------------------------- ** 
/** -------------------------------------------------------------------------------------------------------------------- ** 
/** ---																																					--- **
/** --- 											-----------------------------------------------											--- **
/** ---														{ create_attributes.php }															--- **
/** --- 											-----------------------------------------------											--- **
/** ---																																					--- **
/** ---		TAB SIZE			: 3																													--- **
/** ---																																					--- **
/** ---		AUTEUR			: Nicolas DUPRE																									--- **
/** ---																																					--- **
/** ---		RELEASE			: 25.04.2017																										--- **
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
/** --- 														-----------------------------														--- **
/** --- 															{ C H A N G E L O G } 															--- **
/** --- 														-----------------------------														--- **	
/** ---																																					--- **
/** ---																																					--- **
/** ---		VERSION 1.0 : 25.04.2017 : NDU																									--- **
/** ---		------------------------------																									--- **
/** ---			- Première release																												--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **

	Objectif du script :
	---------------------
	
	Description fonctionnelle :
	----------------------------
	
		Structure de la table ATTRIBUTES :
			INT	ID
			INT	ITEM
			INT	SKILL
			INT	SET
			INT	TIER
			VCHAR	MASTER_TAG
			VCHAR	SLAVE_TAG
			BIT	BASIC
			BIT	PET
			DEC	PROBABILITY
			INT	MASTER_VALUE_1
			INT	MASTER_VALUE_2
			INT	SLAVE_VALUE_1
			INT	SLAVE_VALUE_2
			INT	ATTACHMENT
			INT	PRIORITY
		
		$_REQUEST = Array
		(
		    [0] => Array
		        (
		            [target] => ITEM 
		            [id] => 1
		            [spec] => 
		            [master_tag] => 
		            [probability] => 
		            [master_value_1] => 
		            [master_value_2] => 
		            [slave_tag] => 
		            [slave_value_1] => 
		            [slave_value_2] => 
		            [attachement] => 0
		        )
		
		)
	
	
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** ---																																					--- **
/** ---													PHASE 1 - INITIALISATION DU SCRIPT													--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** > Chargement des Paramètres **/
	setup("/Setups", Array("application", "pdo", "sessions"), "setup.$1.php");


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
if(!isset($_SESSION["MGDG-ADMIN"]) && !$_SESSION["MDGD-ADMIN"]) exit;



/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** ---																																					--- **
/** ---												PHASE 3 - INITIALISAITON DES DONNEES													--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **/
/** > Déclaration des variables **/
	//--- Variable du script
	$query;	// STRING			:: Requête SQL de création d'attribut
	$pQuery;	// PDOStatement	:: Requête SQL préparée depuis $query
	$cols;	// ARRAY				:: Colonne spécifiée pour la requête SQL
	$tokens;	// ARRAY				:: Donnée à lier pour l'execution de la requête SQL pour $pQuery
	$keys;	// ARRAY				:: Liste des clé permettant de liée colonne nommée - au token - a la valeur finale
	$skip;	// ARRAY				:: Liste des clé à ignorer dans l'assimialtion automatique des données

	//--- Variable avec donnée reçue par POST
	$spec;				// STRING	:: Specialisation de l'attribut (BASIC ou PET)
	$target;				// STRING	:: Cible de l'attribut (ITEM, SKILL, SET)
	$id;					// STRING	:: Identifiant de l'élément cible (ID de ITEM, SKILL, SET)


/** > Initialisation des variables **/
	//--- Booleans
	//--- Integers
	//--- Floats
	//--- String
	$spec = $_POST["spec"];
	$target = strtoupper($_POST["target"]);
	$id = $_POST["id"];

	//--- Array
	$tokens = Array();
	$cols = Array();
	$keys = Array();
	$skip = Array("id", "target", "spec");

	//--- Objects
	//--- Nulls


/** > Déclaration et Intialisation des variables pour le moteur (référence) **/



/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** ---																																					--- **
/** ---									PHASE 4 - EXECUTION DU SCRIPT DE TRAITEMENT DE DONNEES										--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **/
/** > Traitement des données reçues **/
//--- Attachement automatique des données
foreach($_POST as $col => $value){
	//──┐ Si cette donnée doit etre ignorée
	if(in_array($col, $skip)) continue;
	//──┐ Si pas de valeur, utilisé la valeur par défaut définie dans MySQL
	if(!$value) continue;
	
	//──┐ Mettre en majuscule pour correspondre aux champs de la table
	$col = strtoupper($col);
	
	//──┐ Insertion des données
	$cols[] = $col;
	$keys[] = ":".$col;
	$tokens[":$col"] = $value;
}

//--- Définition de la cible (ITEM | SKILL | SET)
//──┐ Valeur par défaut ITEM
if(!$target) $target = "ITEM";
//──┐ Dispatch des données
$cols[] = $target;
$keys[] = ":$target";
$tokens[":$target"] = $id;

//--- Si une spécification est requise
if($spec){
	$cols[] = $spec;
	$keys[] = ":$spec";
	$tokens[":$spec"] = 1;
}


/** > Elaboration de la requête SQL **/
$cols = implode(",", $cols);
$keys = implode(",", $keys);

$query = "INSERT INTO ATTRIBUTES ($cols) VALUES ($keys)";


/** > Execution de la requête SQL **/
try {
	$pQuery = $PDO->prepare($query);
	$pQuery->execute($tokens);
} catch (Exception $e){
	error_log($e->getMessage());
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
/** > Configuration du moteur **/
/** > Envoie des données **/
/** > Execution du moteur **/
?>