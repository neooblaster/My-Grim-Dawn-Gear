<?php
/** -------------------------------------------------------------------------------------------------------------------- ** 
/** -------------------------------------------------------------------------------------------------------------------- ** 
/** ---																																					--- **
/** --- 											-----------------------------------------------											--- **
/** ---																{ load_skill.php }															--- **
/** --- 											-----------------------------------------------											--- **
/** ---																																					--- **
/** ---		AUTEUR 	: Nicolas DUPRE																											--- **
/** ---																																					--- **
/** ---		RELEASE	: 28.03.2017																												--- **
/** ---																																					--- **
/** ---		VERSION	: 1.0																															--- **
/** ---																																					--- **
/** ---																																					--- **
/** --- 														-----------------------------														--- **
/** --- 															{ C H A N G E L O G } 															--- **
/** --- 														-----------------------------														--- **	
/** ---																																					--- **
/** ---																																					--- **
/** ---		VERSION 1.0 : 28.03.2017																											--- **
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
	$pSkill;					// PDOStatement	:: Instance PDO contenant les données reçue (Skill)
	$skill_query;			// STRING			:: Requete SQL à jouer pour obtenir les informations du sort
	$pAttributes;			// PDOStatement	:: Instance PDO contenant les données reçue (Attributes)
	$attributes_query;	// STRING			:: Requete SQL à jouer pour obtenir les attributes du sort
	$ID;						// STRING			:: Identifiant de l'objet dont on souhaite récupérer les attributes
	$SYSLang;				// SYSLang			:: Moteur de langue
	$moteur;					// Template			:: Moteur de rendu
	$lang;					// STRING			:: Lang de l'utilisateur
	//--//$ATTRIBUTES;	// ARRAY				:: Liste des attributes obtenus
	$first;					// BOOLEAN			:: Indique si c'est la premiere entrée enregistrée
	$skillID;				// INTEGER			:: Identifiant du sort (nécessaire pour récupéré les attributs)
	$attachment;			// STRING			:: A quel élément le skill est attaché (ITEM ou SET)


/** > Initialisation des variables **/
	$first = true;

	$ID = $_POST["id"];
	$attachment = strtoupper($_POST["attachment"]);

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
/** > Composition de la requête SQL (SKILL) **/
	$skill_query = "
	SELECT
		S.ID,
		S.PROC,
		SN.NAME, SN.DESCRIPTION
		
	FROM SKILLS AS S
	INNER JOIN SKILLS_NAMES AS SN
	ON S.TAG = SN.TAG
	
	WHERE
		S.$attachment = :ID AND LANG = :lang
	";

/** > Récupération des informations **/
	$pSkill = $PDO->prepare($skill_query);
	$pSkill->execute(Array(":ID" => $ID, ":lang" => $lang));
	$faSkill = $pSkill->fetch(PDO::FETCH_ASSOC);
	$faSkill["NAME"] = preg_replace("#%s(%)#", "%s&#37;", $faSkill["NAME"]);


/** > Composition de la requête SQL (ATTRIBUTES) **/
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
		SKILL = :SID AND LANG = :lang
		
	ORDER BY
		PROBABILITY DESC
	";

/** > Récupération des informations **/
	$pAttributes = $PDO->prepare($attributes_query);
	$pAttributes->execute(Array(":SID" => $faSkill["ID"], ":lang" => $lang));



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
	$moteur->set_template_file("../../../Templates/Data/skill.tpl.json");
	$moteur->set_output_name("skill.tpl.json.json");
	$moteur->set_temporary_repository("../../../Temps");

/** > Envoie des données **/
	$moteur->set_vars(Array(
		"ID" => $faSkill["ID"],
		"NAME" => sprintf($faSkill["NAME"], $faSkill["PROC"]),
		"DESCRIPTION" => $faSkill["DESCRIPTION"],
		"ATTRIBUTES" => $ATTRIBUTES
	));

/** > Execution du moteur **/
	echo Template::strip_blank($moteur->render()->get_render_content());
?>