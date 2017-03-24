<?php
/** ----------------------------------------------------------- **
/** --- Initialisation des données de la section GEAR|BUILD --- **
/** ----------------------------------------------------------- **/
/** > Générer un token de page pour lier les donnée de session **/
$gear = null;
$query = null;

/** > Maintenance des données de session **/
foreach($_SESSION as $key => $value){
	if(isset($value["LAST_CHANGE"])){
		if((time() - $value["LAST_CHANGE"]) > SESSION_TTL){
			unset($_SESSION[$key]);
		}
	}
}

/** > Initialisation des données de session **/
// Donnée globale de liaison entre onglets
$_SESSION["TOKEN_$token"] = Array(
	"LAST_CHANGE" => time(),
	"WATCHING_BUILD" => ""
);

// Donnée relative aux builds inter-onglet
if(!isset($_SESSION["BUILDS"])){
	$_SESSION["BUILDS"] = Array();
}

/** > Initialisation des donnée du modèle **/
// Blocks
$vars["ITEMS_FAMILIES"] = load_items_families();
$vars["ITEMS_TYPES"] = load_items_types();

// Textes
$vars["BUILD_TOKEN"] = $token;
$vars["BUILD_CODE"] = "";
$vars["BUILD_ID"] = "";
$vars["BUILD_NAME"] = "";
$vars["BUILD_FOLD_STATS"] = "";
$vars["BUILD_FOLD_INVENTORY"] = "";

$vars["SUBMIT_NAME"] = "Save";
$vars["SUBMIT_TITLE"] = "Create build";

// Conditions
$vars["IS_BUILD_PROTECTED"] = "false";
$vars["IS_BUILD_SIGNED"] = "false";
$vars["IS_WATCHING_BUILD"] = "false";


/** --------------------------------------------------------------- **
/** ---              Traitement des données reçues              --- **
/** --------------------------------------------------------------- **/
/** > Si l'argument build est défini et non null **/
if(isset($_GET["gear"]) && $_GET["gear"] !== ""){
	/** > Sécurisation :: Controler la validité du build **/
	$gear = $_GET["gear"];
	
	/** > Si le code est valide vérifier l'existance du dit build **/
	if(preg_match("#[0-9]{6}-[a-zA-Z0-9]{6}#", $gear)){
		$query = "SELECT ID, PASSWORD, NAME, FOLD_STATS, FOLD_INVENTORY, VIEWS FROM BUILDS WHERE CODE = :code";
		
		$pQuery = $PDO->prepare($query);
		$pQuery->execute(Array(":code" => $gear));
		
		/** > Si on à un résultat **/
		if($pQuery->rowCount() > 0){
			$faQuery = $pQuery->fetch(PDO::FETCH_ASSOC);
			
			/** Si non n'avons aucune information sur ce build, Initialisation **/
			if(!isset($_SESSION["BUILDS"][$gear])){
				$_SESSION["BUILDS"][$gear] = Array(
					"ID" => $faQuery["ID"],
					"PROTECTED" => ($faQuery["PASSWORD"]) ? true : false,
					"SIGNED" => false,
					"HASH" => $faQuery["MD5"]
				);
			}
			
			/** Ajustement des donnée initialisée **/
			$_SESSION["TOKEN_$token"]["WATCHING_BUILD"] = $gear;
			
			$vars["BUILD_ID"] = $faQuery["ID"];
			$vars["BUILD_CODE"] = $gear;
			$vars["BUILD_NAME"] = $faQuery["NAME"];
			
			$vars["IS_WATCHING_BUILD"] = "true";
			$vars["IS_BUILD_PROTECTED"] = ($faQuery["PASSWORD"]) ? "true" : "false";
			
			/** > Ajustement lorsqu'on est déja authentifié au build **/
			if($_SESSION["BUILDS"][$gear]["SIGNED"]){
				$vars["SUBMIT_NAME"] = "UPDATE";
				$vars["SUBMIT_TITLE"] = "UPDATE YOUR BUILD";
				$vars["IS_BUILD_SIGNED"] = "true";
			} else {
				$vars["IS_BUILD_SIGNED"] = "false";
			}
			
			/** > Si le build dispose d'un nom alors le mettre dans le titre **/
			if($faQuery["NAME"]){
				$vars["HEAD_VIEW_TITLE"] = $faQuery["NAME"];
				unset($head_view_title); // Destruction de la référence
			}
			
		}
	}
}


pprint($_SESSION);
//session_destroy();
echo "</pre>";


//--//					$vars["SUBMIT_NAME"] = "Save as copie";
//--//					$vars["SUBMIT_TITLE"] = "Create an edited build";
//--//						if($_SESSION["BUILDS"][$gear]["SIGNED"]){
//--//							$vars['BUILD_ID'] = $faGear['ID'];
//--//							$vars['BUILD_SIGNED'] = "true";
//--//							$vars['SUBMIT_NAME'] = "Update";
//--//							$vars['SUBMIT_TITLE'] = "Update your build";
//--//						}
//--//					if($faGear['FOLD_STATS'] > 0){
//--//						$vars['BUILD_FOLD_STATS'] = "stats_fold";
//--//					}
//--//					if($faGear['FOLD_INVENTORY'] > 0){
//--//						$vars['BUILD_FOLD_INVENTORY'] = "inventory_fold";
//--//					}

?>