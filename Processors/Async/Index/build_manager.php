<?php
/** -------------------------------------------------------------------------------------------------------------------- ** 
/** -------------------------------------------------------------------------------------------------------------------- ** 
/** ---																																					--- **
/** --- 											-----------------------------------------------											--- **
/** ---															{ build_manager.php }															--- **
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
	
	
		Ajout d'un nouveau build quand :
			Nouveau build (pas de code de build)
			Si build, mais non protegé
			si build protegé mais non signé
			Si build Signé AVEC copy
			
		Update un build si
			build signé sans copy
	
	
	
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
	require_once "../../Functions/Common/cryptpwd.php";


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
	$base62;				// ARRAY		:: Tableau de 62 caractère
	$id;					// STRING	:: Identifiant du build si spécifié
	$build;				// STRING	:: Code du build à updater
	$name;				// STRING	:: Nom du build si création
	$password;			// STRING	:: Mot de passe crypté
	$query;				// STRING	:: SQL Query
	$unautomatized;	// ARRAY		:: Liste des colonnes à ne pas automatiser
	$automatized;		// ARRAY		:: Liste des colonnes automatiser (slots recevant des integers)
	$fold_stats;		// STRING	:: Panneau de stats affiché ou masqué
	$fold_inventory;	// STRING	:: Panneau d'inventaire affiché ou masqué
	$empty_build;		// BOOLEAN	:: Flag de sécurité pour empecher la création d'un build vide
	$bound_tokens;		// ARRAY		:: Donnée à passé pour PDO
	$statut;				// STRING	:: Status de l'opération du script (succèss / error)
	$message;			// STRING	:: MEssage de l'echec
	$token;				// STRING	:: Jeton correspondant à l'onglet du navigateur communiquant
	$copy;				// BOOLEAN	:: Indique qu'il faut copier le build
	$md5;					// STRING	:: Hash constituant le build
	$new_build_code;	// STRING	:: Code du nouveau build

/** > Initialisation des variables **/
	$md5 = null;

	$base62 = Array(
		'0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
		'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j',
		'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't',
		'u', 'v', 'w', 'x', 'y', 'z',
		'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J',
		'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T',
		'U', 'V', 'W', 'X', 'Y', 'Z'
	);
	$unautomatized = Array("build_id", "build_code", "build_name", "build_password", "build_fold_stats", "build_fold_inventory", "copy");
	$automatized = Array(
		"cols" => Array(),
		"values" => Array(),
		"sets" => Array() 
	);
	$bound_tokens = Array();

	$token = $_GET["token"];
	$name = $_POST["build_name"];

	$password = (isset($_POST["build_password"]) && $_POST["build_password"] !== "") ? cryptpwd(cryptpwd($_POST["build_password"], CRYPT_KEY_1), CRYPT_KEY_2) : "";
	$build = $_SESSION["TOKEN_$token"]["WATCHING_BUILD"];
	$copy = (isset($_POST["copy"]) && $_POST["copy"] === "true") ? true : false;

//$fold_stats = $_POST["build_fold_stats"];
//$fold_inventory = $_POST["build_fold_inventory"];


/** > Déclaration et Intialisation des variables pour le moteur (référence) **/


/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** ---																																					--- **
/** ---									PHASE 4 - EXECUTION DU SCRIPT DE TRAITEMENT DE DONNEES										--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **/
/** > Rechercher les clées automatisée **/
foreach($_POST as $key => $value){
	if(!in_array($key, $unautomatized)){
		$automatized["cols"][] = strtoupper($key);
		$automatized["values"][] = intval($value);
		$automatized["sets"][] = strtoupper($key)."=".intval($value);
		
		$md5 .= intval($value).".";
	}
}

/** > Composition du hash MD5 **/
$md5 = md5($md5);

/** > Composition de la requête SQL **/
// Nouveau Build
if(
	!$build || 
	($build && !$_SESSION["BUILDS"][$build]["PROTECTED"]) || 
	($build && $_SESSION["BUILDS"][$build]["PROTECTED"] && !$_SESSION["BUILDS"][$build]["SIGNED"]) ||
	($build && $_SESSION["BUILDS"][$build]["PROTECTED"] && $_SESSION["BUILDS"][$build]["SIGNED"] && $copy) 
){
	/** > Générer un code de build unique **/
	do {
		// Augmentation des chance de succès + possilité à l'aide de la date
		$new_build_code = date("ymd-", time());
		
		for($i = 0; $i < 6; $i++){
			$new_build_code .= $base62[rand(0, 61)];
		}
		
		// Vérification de la disponibilité
		try {
			$pAvailibility = $PDO->prepare("SELECT ID FROM BUILDS WHERE CODE = :code");
			$pAvailibility->execute(Array(":code" => $new_build_code));
		} catch (Exception $e){
			$statut = "error";
			$message = "Unable to generate build code";
			break;
		}
	} while ($pAvailibility->rowCount() > 0);
	
	/** > Generation de la requête SQL **/
	$query = "
		INSERT INTO BUILDS
		
		(CODE, NAME, PASSWORD, MD5, ".implode(",", $automatized["cols"]).")
		
		VALUES(:code, :name, :password, :md5, ".implode(",", $automatized["values"]).")
	";
	
	/** > Bound tokens **/
	$bound_tokens = Array(
		":code" => $new_build_code,
		":name" => $name,
		":password" => $password,
		":md5" => $md5
	);
}
// Mise à jour du build
else {
	/** > Generation de la requête SQL **/
	$query = "
	UPDATE BUILDS
	
	SET ".implode(",", $automatized["sets"])."
	
	WHERE CODE = :code
	";
	
	/** > Bound tokens **/
	$bound_tokens = Array(
		":code" => $build
	);
}

/** > Execution de le requête SQL **/
try {
	$pQuery = $PDO->prepare($query);
	$pQuery->execute($bound_tokens);
	
	$statut = "success";
	
} catch (Exception $e){
	$statut = "error";
	$message = "Operation failed.";
	error_log("[MGDG] :: build_manager.php failed with error :: ".$e->getMessage()." on query ".$query);
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

echo '{"statut": "'.$statut.'", "message": "'.$message.'", "code": "'.$new_build_code.'"}';

?>
