<?php
/** -------------------------------------------------------------------------------------------------------------------- ** 
/** -------------------------------------------------------------------------------------------------------------------- ** 
/** ---																																					--- **
/** --- 											-----------------------------------------------											--- **
/** ---																{ load_slot.php }																--- **
/** --- 											-----------------------------------------------											--- **
/** ---																																					--- **
/** ---		AUTEUR 	: Nicolas DUPRE																											--- **
/** ---																																					--- **
/** ---		RELEASE	: 22.03.2016																												--- **
/** ---																																					--- **
/** ---		VERSION	: 1.0																															--- **
/** ---																																					--- **
/** ---																																					--- **
/** --- 														-----------------------------														--- **
/** --- 															{ C H A N G E L O G } 															--- **
/** --- 														-----------------------------														--- **	
/** ---																																					--- **
/** ---																																					--- **
/** ---		VERSION 1.0 : 22.03.2016																											--- **
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
	$ID;			// INTEGER	:: Identifiant de l'objet à charger
	$SYSLang;	// SYSLang	:: Moteur de langue
	$lang;		// STRING	:: Langue de l'utilisateur
	$query;		// STRING	:: Reqête SQL de récupération de données
	$moteur;		// Template	:: Moteur de rendu

/** > Initialisation des variables **/
	$ID = intval($_POST['item_id']);

	$SYSLang = new SYSLang("../../../Languages");
	$lang = $SYSLang->get_lang();


/** > Déclaration et Intialisation des variables pour le moteur (référence) **/
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** ---																																					--- **
/** ---									PHASE 4 - EXECUTION DU SCRIPT DE TRAITEMENT DE DONNEES										--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **/
/** > Execution la requête SQL de récupération **/
try {
	$query = "
		SELECT
			I.ID,
			I.WIDTH, I.HEIGHT,
			I.FAMILY, I.TYPE, I.QUALITY,
			I.TAG, TN.NAME,
			I.ATTACHMENT
			
		FROM ITEMS AS I
		INNER JOIN TAGS_NAMES AS TN
		ON I.TAG = TN.TAG
		
		WHERE I.ID = $ID AND LANG = '$lang'
	";
	
	
	$qItem = $PDO->query($query);
	$faItem = $qItem->fetch(PDO::FETCH_ASSOC);
	
	$moteur = new Template();
	$moteur->set_template_file("../../../Templates/Data/item.tpl.json");
	$moteur->set_output_name("item.json");
	$moteur->set_temporary_repository("../../../Temps");
	$moteur->set_vars(Array(
		"ID" => $faItem["ID"],
		"WIDTH" => $faItem["WIDTH"],
		"HEIGHT" => $faItem["HEIGHT"],
		"FAMILY" => $faItem["FAMILY"],
		"TYPE" => $faItem["TYPE"],
		"QUALITY" => $faItem["QUALITY"],
		"TAG" => $faItem["TAG"],
		"NAME" => $faItem["NAME"],
		"ATTACHMENT" => $faItem["ATTACHMENT"]
	));
	
	echo Template::strip_blank($moteur->render()->get_render_content());
	
} catch(Exception $e){
	//echo $e->getMessage();
	echo "{}";
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