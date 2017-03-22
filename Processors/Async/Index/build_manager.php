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
	$code;				// STRING	:: Code du build à updater
	$name;				// STRING	:: Nom du build si création
	$password;			// STRING	:: Mot de passe crypté
	$query;				// STRING	:: SQL Query
	$unautomatized;	// ARRAY		:: Liste des colonnes à ne pas automatiser
	$automatized;		// ARRAY		:: Liste des colonnes automatisée
	$fold_stats;		// STRING	:: Panneau de stats affiché ou masqué
	$fold_inventory;	// STRING	:: Panneau d'inventaire affiché ou masqué
	$empty_build;		// BOOLEAN	:: Flag de sécurité pour empecher la création d'un build vide
	$bound_tokens;		// ARRAY		:: Donnée à passé pour PDO
	$statut;				// STRING	:: Status de l'opération du script (succèss / error)
	$message;			// STRING	:: MEssage de l'echec

/** > Initialisation des variables **/
	$base62 = Array(
		'0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
		'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j',
		'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't',
		'u', 'v', 'w', 'x', 'y', 'z',
		'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J',
		'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T',
		'U', 'V', 'W', 'X', 'Y', 'Z'
	);

	$unautomatized = Array("build_id", "build_code", "build_name", "build_passcode", "build_fold_stats", "build_fold_inventory");
	$automatized = Array();
	$bound_tokens = Array();

	$id = $_POST["build_id"];
	$name = $_POST["build_name"];
	$code = $_POST["build_code"];
	$fold_stats = $_POST["build_fold_stats"];
	$fold_inventory = $_POST["build_fold_inventory"];

	$empty_build = true;

/** > Déclaration et Intialisation des variables pour le moteur (référence) **/


/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** ---																																					--- **
/** ---									PHASE 4 - EXECUTION DU SCRIPT DE TRAITEMENT DE DONNEES										--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **/
/** > Vérifier s'il s'agit d'une dupplication **/
if($_POST["dupplicate"]){
	$id = null;
	unset($_POST["dupplicate"]);
}


/** > Déterminer le mot de passe **/
$password = (isset($_POST["build_passcode"]) && $_POST["build_passcode"] !== "") ? cryptpwd(cryptpwd($_POST["build_passcode"], CRYPT_KEY_1), CRYPT_KEY_2) : "";


/** > Parcourir les champs de donnée envoyé pour automatisation **/
foreach($_POST as $key => $value){
	/** > S'il s'agit pas d'une clé à automatisé **/
	if(!in_array($key, $unautomatized)){
		// Pour la requête d'INSERT
		$automatized["cols"][] = strtoupper($key);
		$automatized["values"][] = $value;
		
		// Pour la requête d'UPDATE
		$automatized["update"][] = strtoupper($key)."=".$value;
		
		if(intval($value) > 0){
			$empty_build = false;
		}
	}
}


/** > Déterminer la requête SQL qui convient **/
// Si ID est null c'est une création
if($id === null){
	$operation = "INSERT";
	
	/** Chercher un Code Disponible **/
	do{
		$build_code = date("ymd-", time());
		for($i = 0; $i < 6; $i++){
			$build_code .= $base62[rand(0, 61)];
		}
		
		try {
			$control = $PDO->query("SELECT ID FROM BUILDS WHERE CODE = '$build_code'");
		} catch (Exception $e){
			$statut = "error";
			$message = "Unable to generate build code.";
			break;
		}
	} while($control->rowCount() > 0);
	
	/** Finalisation de la requête SQL **/
	$query = "
		INSERT INTO BUILDS
		
		(CODE, NAME, PASSWORD, ".implode(",", $automatized["cols"]).")
		
		VALUES(:build_code, :build_name, :password, ".implode(",", $automatized["values"]).")
	";
	
	$bound_tokens = Array(
		":build_code" => $build_code,
		":build_name" => $name,
		":password" => $password
	);
}

// Sinon c'est une UPDATE
else {
	$operation = "UPDATE";
	
	$query = "
		UPDATE BUILDS
		
		SET ".implode(",", $automatized["update"])."
		
		WHERE ID = $id
	";
}


/** > Execution de la requête SQL **/
if(!$empty_build){
	try {
		$pQuery = $PDO->prepare($query);
		$pQuery->execute($bound_tokens);
		$statut = "success";
	} catch(Expcetion $e){
		$statut = "error";
		$message = "Build registration failed.";
	}
} else {
	$statut = "skip";
	$message = "Your build is empty.";
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
echo '{
	"STATUT": "'.$statut.'",
	"MESSAGE": "'.$message.'",
	"OPERATION": "'.$operation.'",
	"BUILD_CODE": "'.$build_code.'"
}';
?>
