<?php
/** -------------------------------------------------------------------------------------------------------------------- ** 
/** -------------------------------------------------------------------------------------------------------------------- ** 
/** ---																																					--- **
/** --- 										------------------------------------------------											--- **
/** ---															{ load_items.php }																--- **
/** --- 										------------------------------------------------											--- **
/** ---																																					--- **
/** ---		TAB SIZE			: 3																													--- **
/** ---																																					--- **
/** ---		AUTEUR			: Nicolas DUPRE																									--- **
/** ---																																					--- **
/** ---		RELEASE			: xx.xx.2017																										--- **
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
/** --- 														---------------------------														--- **
/** ---															{ C H A N G E L O G }															--- **
/** --- 														---------------------------														--- **
/** ---																																					--- **
/** ---																																					--- **
/** ---		VERSION 1.0 : xx.xx.2017 : NDU																									--- **
/** ---		------------------------------																									--- **
/** ---			- Première release																												--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **

	Requirements :
	--------------
		
		Setup : setup.pdo.php => $PDO


	Input Params :
	--------------
		
	
	
	Output Params :
	---------------
		


	Objectif du script :
	---------------------
		
		L'objectif de cette fonction est de chargé les objets en fonction d'une clause SQL donnée et un jeu de donnée lié
		Elle fonctionne en adéquation avec le modèle de donnée item.tpl.json.
		Simplifie la maintenance entre les différents scripts sur la partie des selection des champs de donné.
	
	
	Description fonctionnelle :
	----------------------------
		



/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **/
function load_items($clause="", $bound_tokens=Array()){
	/** Déclaration des variables **/
	global $PDO;	// PDO				:: Socket de connexion DB PDO
	$query;			// STRING			:: Requête SQL à jouer
	$pQuery;			// PDOStatement	:: Requête SQL Préparée
	$ITEMS;			// ARRAY				:: Liste des objets obtenu
	$first;			// BOOLEAN			:: Flag indiquant le traitement de la premier ligne
	
	/** Initialisation des variables **/
	
	
	/** Composition de la requête SQL **/
	$query = sprintf("
	SELECT
		I.ID,
		I.WIDTH, I.HEIGHT,
		I.FAMILY, I.TYPE, T.TAG AS TAG_TYPE, I.QUALITY, A.ATTACHMENT,
		I.TAG, TN.NAME, TN.DESCRIPTION,
		I.SET, I.SKILLED, 
		I.LEVEL, I.PHYSIQUE, I.CUNNING, I.SPIRIT
		
	FROM ITEMS AS I
	INNER JOIN TAGS_NAMES AS TN
	ON I.TAG = TN.TAG
	INNER JOIN TYPES AS T
	ON I.TYPE = T.ID
	INNER JOIN ATTACHMENTS AS A
	ON I.ATTACHMENT = A.ID
	
	%s
	
	", $clause);
	
	
	/** Execution de la requête SQL **/
	try {
		/** Jouer la requête SQL **/
		$pQuery = $PDO->prepare($query);
		$pQuery->execute($bound_tokens);
		
		/** Parser les données **/
		$first = true;
		while($faData = $pQuery->fetch(PDO::FETCH_ASSOC)){
			$ITEMS[] = Array(
				"COMMA" => ($first) ? "" : ",",
				"ID" => $faData["ID"],
				
				"WIDTH" => $faData["WIDTH"],
				"HEIGHT" => $faData["HEIGHT"],
				
				"FAMILY" => $faData["FAMILY"],
				"TYPE" => $faData["TYPE"],
				"TYPE_NAME" => $faData["TAG_TYPE"],// translation ici
				"QUALITY" => $faData["QUALITY"],
				"ATTACHMENT" => $faData["ATTACHMENT"],
				
				"TAG" => $faData["TAG"],
				"NAME" => $faData["NAME"],
				"DESCRIPTION" => $faData["DESCRIPTION"],
				
				"SET" => ord($faData["SET"]),
				"SKILLED" => ord($faData["SKILLED"]),
				
				"LEVEL" => $faData["LEVEL"],
				"PHYSIQUE" => $faData["PHYSIQUE"],
				"CUNNING" => $faData["CUNNING"],
				"SPIRIT" => $faData["SPIRIT"],
			);
			
			$first = false;
		}
		
		/** Renvoyer les objets trouvés  **/
		return $ITEMS;
	} catch (Exception $e){
		error_log(sprintf("[ MGDG ] :: load_items() failed on %s with error %s", $query, $e->getMessage()));
		return false;
	}
}
?>