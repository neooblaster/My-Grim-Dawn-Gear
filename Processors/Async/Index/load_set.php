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
/** ---		RELEASE			: 06.04.2017																										--- **
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
/** ---		VERSION 1.0 : 06.04.2017 : NDU																									--- **
/** ---		------------------------------																									--- **
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
	setup("/Setups", Array("application", "sessions", "pdo"), "setup.$1.php");

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



/** Composition de la requête SQL (ATTRIBUTES) **/
$attributes_query = "
SELECT
	A.ID,
	A.BASIC, A.PET, A.PROBABILITY, A.TIER,
	A.MASTER_VALUE, A.SLAVE_VALUE, A.ATTACHMENT,
	AN.NAME
	
FROM ATTRIBUTES AS A
INNER JOIN ATTRIBUTES_NAMES AS AN
ON A.TAG = AN.TAG

WHERE
	A.SET = :id AND AN.LANG = :lang
	
ORDER BY
	A.TIER ASC, A.PROBABILITY DESC
";

/** Execution de la requête SQL (ATTRIBUTES) **/
$pAttributes = $PDO->prepare($attributes_query);
$pAttributes->execute(Array(":id" => $set_id, ":lang" => $lang));


/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** ---																																					--- **
/** ---											PHASE 5 - GENERATION DES DONNEES DE SORTIE												--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **/
/** > Parcourir les données reçues pour envois au client **/
while($faAttributes = $pAttributes->fetch(PDO::FETCH_ASSOC)){
	$name = $faAttributes["NAME"];
	
	$name = preg_replace("#%s(%)#", "%s&#37;", $name);
	$name = preg_replace("#(\+?\s*%s(&\#37;)?)#", "<span>$1</span>", $name);
	
	$attribut = @sprintf($name, $faAttributes["MASTER_VALUE"], $faAttributes["SLAVE_VALUE"]);
	
	$ATTRIBUTES[] = Array(
		"COMMA" => ($first) ? "" : ",",
		"ID" => $faAttributes["ID"],
		"BASIC" => ord($faAttributes["BASIC"]),
		"PET" => ord($faAttributes["PET"]),
		"TIER" => $faAttributes["TIER"],
		"ATTRIBUT" => $attribut,
		"ATTACHMENT" => $faAttributes["ATTACHMENT"],
		"PROBABILITY" => $faAttributes["PROBABILITY"] * 100,
		"MASTER_VALUE" => $faAttributes["MASTER_VALUE"],
		"SLAVE_VALUE" => $faAttributes["SLAVE_VALUE"]
	);
	
	$first = false;
}



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