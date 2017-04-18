<?php
/** -------------------------------------------------------------------------------------------------------------------- ** 
/** -------------------------------------------------------------------------------------------------------------------- ** 
/** ---																																					--- **
/** --- 											-----------------------------------------------											--- **
/** ---															{ load_items.php }																--- **
/** --- 											-----------------------------------------------											--- **
/** ---																																					--- **
/** ---		AUTEUR 	: Nicolas DUPRE																											--- **
/** ---																																					--- **
/** ---		RELEASE	: 18.04.2017																												--- **
/** ---																																					--- **
/** ---		VERSION	: 1.1																															--- **
/** ---																																					--- **
/** ---																																					--- **
/** --- 														-----------------------------														--- **
/** --- 															{ C H A N G E L O G } 															--- **
/** --- 														-----------------------------														--- **	
/** ---																																					--- **
/** ---		VERSION 1.1 : 18.04.2017 : NDU																									--- **
/** ---		------------------------------																									--- **
/** ---			- Changement du format des données renvoyé pour etre json_encodable												--- **
/** ---																																					--- **
/** ---		VERSION 1.0 : 04.04.2017																											--- **
/** ---		------------------------																											--- **
/** ---			- Première release																												--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **

	Objectif du script :
	---------------------
	
	Description fonctionnelle :
	----------------------------
	
		// Donnée recu :
			- item_name
			- quality_magic
			- quality_rare
			- quality_elite
			- quality_legendary
			- item_family
			- item_type
		
		
		// Tables de donnée
			ITEMS      : ID, ENABLED, FAMILY, TYPE, QUALITY, ATTACHMENT, TAG, WIDTH, HEIGHT
			FAMILIES   : ID, FAMILY, TAG
			TYPES      : ID, TYPE, TAG
			QUALITIES  : ID, QUALITY, TAG
			
			TAGS_NAMES : ID, LANG, TAG, NAME, MD5
		
		
		// Filtre de recherche :
			- Un nom donnée dans la langue définie : item_name   ==> TAGS_NAME.NAME
			- Qualité d'objet                      : quality_x   ==> ITEMS.QUALITY
			- Type d'objet                         : item_type   ==> ITEMS.TYPE
			- Famille d'objet                      : item_family ==> ITEMS.FAMILY
			
			
		// Jointure : 
			- ITEMS + ITEMS_TAGS_NAME ON ITN.TAG_NAME = ITEMS.TAG_NAME, si NAME est donnée
		
		
	
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** ---																																					--- **
/** ---													PHASE 1 - INITIALISATION DU SCRIPT													--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** > Chargement des Paramètres **/
 setup('/Setups', Array('application', 'pdo', 'sessions'), 'setup.$1.php');

/** > Ouverture des SESSIONS Globales **/
/** > Chargement des Classes **/
/** > Chargement des Configs **/
/** > Chargement des Fonctions **/
	require_once __ROOT__."/Processors/Functions/Index/load_items.php";



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
	$item_name;				// STRING	:: Nom de l'objet recherché
	$item_family;			//	:: Familly de l'objet
	$item_type;				//	:: Type de l'objet
	$quality_common;		//	:: Indicateur d'état de la qualité common
	$quality_magic;		//	:: Indicateur d'état de la qualité magic
	$quality_rare;			//	:: Indicateur d'état de la qualité rare
	$quality_elite;		//	:: Indicateur d'état de la qualité elite
	$quality_legendary;	//	:: Indicateur d'état de la qualité legendary
	$quality_relic;		//	:: Indicateur d'état de la qualité relic
	$quality_comp;			//	:: Indicateur d'état de la qualité comp
	$quality_enchant;		//	:: Indicateur d'état de la qualité enchant
	$qualities;				// ARRAY		:: Liste des qualités à cherché

	$SYSLang;				// SYSLang	:: Moteur de langue
	$moteur;					// Template	:: Moteur de rendu

	$lang;					// STRING	:: Langue de l'utilisateur

	$query_where;			// STRING	:: Clause SQL à déterminée
	$query_tokens;			// ARRAY		:: Donnée relative aux paramètres linké (PDO)

	$ITEMS;					// ARRAY		:: Liste de tableau contenant les informations des objets
	$first;					// BOOLEAN	:: Indique si c'est le premier objet de la liste

	$enabled_only;			// STRING	:: Clause sur les objet activé par défaut
	

/** > Initialisation des variables **/
	$item_name = $_POST["item_name"];
	$item_family = $_POST["item_family"];
	$item_type = $_POST["item_type"];
	
	$quality_common = $_POST["quality_common"];
	$quality_magic = $_POST["quality_magic"];
	$quality_rare = $_POST["quality_rare"];
	$quality_elite = $_POST["quality_elite"];
	$quality_legendary = $_POST["quality_legendary"];
	$quality_relic = $_POST["quality_relic"];
	$quality_comp = $_POST["quality_comp"];
	$quality_enchant = $_POST["quality_enchant"];

	$SYSLang = new SYSLang("../../../Languages");
	$lang = $SYSLang->get_lang();
	$moteur = new Template();

	$query = null;
	$query_select = null;
	$query_from = null;
	$query_where = null;
	$query_tokens = Array();

	$ITEMS = Array();
	$first = true;


/** > Déclaration et Intialisation des variables pour le moteur (référence) **/


/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** ---																																					--- **
/** ---									PHASE 4 - EXECUTION DU SCRIPT DE TRAITEMENT DE DONNEES										--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **/
/** > Composition de la clause SQL WHERE **/
	// Gérer les qualitées 
	if($quality_common === "true") $qualities[] = 1;
	if($quality_magic === "true") $qualities[] = 2;
	if($quality_rare === "true") $qualities[] = 3;
	if($quality_elite === "true") $qualities[] = 4;
	if($quality_legendary === "true") $qualities[] = 5;
	if($quality_relic === "true") $qualities[] = 6;
	if($quality_comp === "true") $qualities[] = 7;
	if($quality_enchant === "true") $qualities[] = 8;
	
	if(count($qualities) > 0) $query_where = "WHERE I.QUALITY IN (".implode(",", $qualities).")";
	
	// Gérer les familles
	if(intval($item_family) > 0){
		if($query_where){
			$query_where .= " AND I.FAMILY = $item_family";
		} else {
			$query_where = "WHERE I.FAMILY = $item_family";
		}
	}
	
	// Gérer les types
	if(intval($item_type) > 0){
		if($query_where){
			$query_where .= " AND I.TYPE = $item_type";
		} else {
			$query_where = "WHERE I.TYPE = $item_type";
		}
	}
	
	// Gérer le nom
	if($item_name){
		if($query_where){
			$query_where .= " AND TN.NAME LIKE :item_name";
		} else {
			$query_where = "WHERE TN.NAME LIKE :item_name";
		}
		$query_tokens[":item_name"] = "%$item_name%";
	}
		
	// Afficher les objets activé (ou tous si admin)
	$enabled_only = (isset($_SESSION["MGDG-ADMIN"]) && $_SESSION["MGDG-ADMIN"]) ? "" : "I.ENABLED = 1 AND";

	if($query_where){
		$query_where .= " AND $enabled_only TN.LANG = :lang";
	} else {
		$query_where = "WHERE $enabled_only TN.LANG = :lang";
	}
	
	$query_tokens[":lang"] = $lang;



/** > Execution de la requête SQL **/
try {
	$ITEMS = load_items($query_where, $query_tokens);
	echo json_encode($ITEMS);
} catch(Exception $e){
	error_log("[ MGDG ] :: load_items.php failed on query $query with error ".$e->getMessage());
}



/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** ---																																					--- **
/** ---											PHASE 5 - GENERATION DES DONNEES DE SORTIE												--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **/
	//echo "load_items.php";

/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** ---																																					--- **
/** ---												PHASE 6 - AFFICHER LES SORTIES GENEREE													--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **/
/** > Configuration du moteur **/
	//$moteur->set_template_file("../../../Templates/Data/items.tpl.json");
	//$moteur->set_output_name("items.json");
	//$moteur->set_temporary_repository("../../../Temps");

/** > Envoie des données **/
	//$moteur->set_vars(Array("ITEMS" => $ITEMS));
	
/** > Execution du moteur **/
	//echo Template::strip_blank($moteur->render()->get_render_content());
?>