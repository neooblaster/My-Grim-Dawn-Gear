<?php
/** -------------------------------------------------------------------------------------------------------------------- ** 
/** -------------------------------------------------------------------------------------------------------------------- ** 
/** ---																																					--- **
/** --- 											-----------------------------------------------											--- **
/** ---																{ load_set.php }																--- **
/** --- 											-----------------------------------------------											--- **
/** ---																																					--- **
/** ---		TAB SIZE			: 3																													--- **
/** ---																																					--- **
/** ---		AUTEUR			: Nicolas DUPRE																									--- **
/** ---																																					--- **
/** ---		RELEASE			: 17.04.2017																										--- **
/** ---																																					--- **
/** ---		FILE_VERSION	: 1.1 NDU																											--- **
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
/** ---		VERSION 1.1 : 17.04.2017 : NDU																									--- **
/** ---		------------------------------																									--- **
/** ---			- Utilisation de la fonction load_attributes au lieu de dispose d'une copie du code							--- **
/** ---																																					--- **
/** ---																																					--- **
/** ---		VERSION 1.0 : 06.04.2017 : NDU																									--- **
/** ---		------------------------------																									--- **
/** ---			- Première release																												--- **
/** ---																																					--- **
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
	setup("/Setups", Array("application", "sessions", "pdo"), "setup.$1.php");

/** > Ouverture des SESSIONS Globales **/
/** > Chargement des Classes **/
/** > Chargement des Configs **/
/** > Chargement des Fonctions **/
	require_once __ROOT__."/Processors/Functions/Index/load_attributes.php";


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
	$set_id;				// STRING			:: Identifiant du set à charger
	$set_query;			// STRING			:: Requête de récupération des données du set
	$attributes_query;// STRING			:: Requête de récupération des attributs du set
	$pAttributes;		// PDOStatement	:: Requête préparée depuis attributes_query
	$pSet;				// PDOStatement	:: Requête préparée depuis set_query
	$lang;				// STRING			:: Langue de l'utilisateur
	$SYSLang;			// SYSLang			:: Moteur de langue
	$faSet;				// ARRAY				:: Donnée parsée de $pSet
	$first;				// BOOLEAN			:: Indique si c'est la premiere entrée enregistrée
	$moteur;				// Template			:: Moteur de rendu
	$faAttributes;		// ARRAY				:: Donnée parsée de $pAttributes

/** > Initialisation des variables **/
	$first = true;

	$set_id = $_POST["set_id"];
	
	$SYSLang = new SYSLang(__ROOT__."/Languages");
	$lang = $SYSLang->get_lang();

/** > Déclaration et Intialisation des variables pour le moteur (référence) **/
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** ---																																					--- **
/** ---									PHASE 4 - EXECUTION DU SCRIPT DE TRAITEMENT DE DONNEES										--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **/
/** Composition de la requête SQL (SET) **/
$set_query = "
SELECT
	S.ID,
   SN.NAME, SN.DESCRIPTION,
   GROUP_CONCAT(I.ID) AS ITEMS, 
	GROUP_CONCAT('\"',TN.NAME,'\"') AS NAMES
    
FROM SETS AS S
INNER JOIN SETS_NAMES AS SN
ON S.TAG = SN.TAG
INNER JOIN ITEMS AS I
ON I.SET = S.ID
INNER JOIN TAGS_NAMES AS TN
ON I.TAG = TN.TAG

WHERE S.ID = :id AND SN.LANG = :lang AND TN.LANG = :lang
";

/** Execution de la requête SQL (SET) **/
$pSet = $PDO->prepare($set_query);
$pSet->execute(Array(":id" => $set_id, ":lang" => $lang));
$faSet = $pSet->fetch(PDO::FETCH_ASSOC);


/** > Charger les attributes associés **/
$ATTRIBUTES = load_attributes($lang, "SET", $set_id);



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
	$moteur->set_template_file("../../../Templates/Data/set.tpl.json");
	$moteur->set_output_name("set.json.json");
	$moteur->set_temporary_repository("../../../Temps");

/** > Envoie des données **/
	$moteur->set_vars(Array(
		"ID" => $faSet["ID"],
		"NAME" => $faSet["NAME"],
		"DESCRIPTION" => $faSet["DESCRIPTION"],
		"ITEMS" => $faSet["ITEMS"],
		"NAMES" => $faSet["NAMES"],
		"ATTRIBUTES" => $ATTRIBUTES
	));

/** > Execution du moteur **/
	echo Template::strip_blank($moteur->render()->get_render_content());
?>