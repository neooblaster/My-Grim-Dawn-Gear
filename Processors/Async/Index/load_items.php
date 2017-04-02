<?php
/** -------------------------------------------------------------------------------------------------------------------- ** 
/** -------------------------------------------------------------------------------------------------------------------- ** 
/** ---																																					--- **
/** --- 											-----------------------------------------------											--- **
/** --- {}--- **
/** --- 											-----------------------------------------------											--- **
/** ---																																					--- **
/** ---		AUTEUR 	: Nicolas DUPRE																											--- **
/** ---																																					--- **
/** ---		RELEASE	: xx.xx.2016																												--- **
/** ---																																					--- **
/** ---		VERSION	: 1.0																															--- **
/** ---																																					--- **
/** ---																																					--- **
/** --- 														-----------------------------														--- **
/** --- 															{ C H A N G E L O G } 															--- **
/** --- 														-----------------------------														--- **	
/** ---																																					--- **
/** ---																																					--- **
/** ---		VERSION 1.0 : xx.xx.2016																											--- **
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
 setup('/Setups', Array('application', 'pdo'), 'setup.$1.php');

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

	$query;					// STRING	:: Requête SQL final à executée
	$query_select;			// STRING	:: Partie SELECT de la requête
	$query_from;			// STRING	:: Partie FROM de la requête
	$query_where;			// STRING	:: Partie WHERE de la requête
	$query_tokens;			// ARRAY		:: Donnée relative aux paramètres linké (PDO)

	$ITEMS;					// ARRAY		:: Liste de tableau contenant les informations des objets
	$first;					// BOOLEAN	:: Indique si c'est le premier objet de la liste
	

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
/** > Composition de la requête SQL **/
	// [SELECT] Composition de la zone de selection
	$query_select = "
		I.ID,
		I.WIDTH,
		I.HEIGHT,
		I.FAMILY,
		I.TYPE, T.TAG AS TAG_TYPE,
		I.QUALITY,
		I.TAG,
		TN.NAME,
		A.ATTACHMENT,
		LEVEL, PHYSIQUE, CUNNING, SPIRIT,
		SKILLED
	";


	// [FROM] Composition de la zone cible
	$query_from = "
		ITEMS AS I
		INNER JOIN TAGS_NAMES AS TN
		ON I.TAG = TN.TAG
		INNER JOIN ATTACHMENTS AS A
		ON I.ATTACHMENT = A.ID
		INNER JOIN TYPES AS T
		ON I.TYPE = T.ID
	";


	// [WHERE] Composition de la clause SQL
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

		// Afficher les objets activé
		if($query_where){
			$query_where .= " AND I.ENABLED = 1 AND TN.LANG = :lang";
		} else {
			$query_where = "WHERE I.ENABLED = 1 AND TN.LANG = :lang";
		}
		
		$query_tokens[":lang"] = $lang;
	

/** > Compilation de la query **/
$query = sprintf("SELECT %s FROM %s %s", $query_select, $query_from, $query_where);


/** > Execution de la requête SQL **/
try {
	$qData = $PDO->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	$qData->execute($query_tokens);
} catch(Exception $e){
	error_log("[ MGDG ] :: load_items failed on query $query with error ".$e->getMessage());
}


/** > Parcourir les donnée **/
while($faData = $qData->fetch(PDO::FETCH_ASSOC)){
	$ITEMS[] = Array(
		"COMMA" => ($first ? '' : ','),
		"ID" => $faData["ID"],
		"WIDTH" => $faData["WIDTH"],
		"HEIGHT" => $faData["HEIGHT"],
		"FAMILY" => $faData["FAMILY"],
		"TYPE" => $faData["TYPE"],
		"QUALITY" => $faData["QUALITY"],
		"TAG" => $faData["TAG"],
		"TYPE_NAME" => $faData["TAG_TYPE"],// translation ici
		"NAME" => $faData["NAME"],
		"ATTACHMENT" => $faData["ATTACHMENT"],
		"LEVEL" => $faData["LEVEL"],
		"PHYSIQUE" => $faData["PHYSIQUE"],
		"CUNNING" => $faData["CUNNING"],
		"SPIRIT" => $faData["SPIRIT"],
		"SKILLED" => ord($faData["SKILLED"])
	);
	
	$first = false;
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
/** > Configuration du moteur **/
	$moteur->set_template_file("../../../Templates/Data/items.tpl.json");
	$moteur->set_output_name("items.json");
	$moteur->set_temporary_repository("../../../Temps");

/** > Envoie des données **/
	$moteur->set_vars(Array("ITEMS" => $ITEMS));
	
/** > Execution du moteur **/
	echo Template::strip_blank($moteur->render()->get_render_content());
?>