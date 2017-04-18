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
/** ---		RELEASE			: 18.04.2017																										--- **
/** ---																																					--- **
/** ---		FILE_VERSION	: 1.2 NDU																											--- **
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
/** ---		VERSION 1.2 : 18.04.2017 : NDU																									--- **
/** ---		------------------------------																									--- **
/** ---			- Changement du format des données renvoyé pour etre json_encodable												--- **
/** ---																																					--- **
/** ---		VERSION 1.1 : 17.04.2017 : NDU																									--- **
/** ---		------------------------------																									--- **
/** ---			- Suppression de la fonction ORD. Le champ SET n'était pas un BIT													--- **
/** ---				>  Erreur de copie																											--- **
/** ---																																					--- **
/** ---		VERSION 1.0 : 04.04.2017 : NDU																									--- **
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
		Elle fonctionne en adéquation avec le modèle de donnée __ROO__/Templates/Data/item.tpl.json.
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
	$DATAS;			// ARRAY				:: Donnée combinée HEADER + ITEMS
	$HEADERS;		// ARRAY				:: Entête correspondants
	$ITEMS;			// ARRAY				:: Liste des objets obtenu
	$first;			// BOOLEAN			:: Flag indiquant le traitement de la premier ligne
	
	/** Initialisation des variables **/
	
	
	/** Composition de la requête SQL **/
	$query = sprintf("
	SELECT
		I.ID, I.ENABLED,
		I.WIDTH, I.HEIGHT,
		I.FAMILY, I.TYPE, T.TAG AS TAG_TYPE, I.QUALITY, A.ATTACHMENT,
		CONCAT(I.TAG, I.EXTEND) AS TAG,
		TN.NAME, TN.DESCRIPTION,
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
			/** SI c'est la premiere fois, on récupère les champs **/
			if($first) $HEADERS = array_keys($faData);
			
			
			/** Opérer des conversion sur les données **/
			//--- Format Numérique
			$faData["ID"] = intval($faData["ID"]);
			
			$faData["FAMILY"] = intval($faData["FAMILY"]);
			$faData["TYPE"] = intval($faData["TYPE"]);
			
			$faData["PHYSIQUE"] = intval($faData["PHYSIQUE"]);
			$faData["CUNNING"] = intval($faData["CUNNING"]);
			$faData["SPIRIT"] = intval($faData["SPIRIT"]);
			$faData["LEVEL"] = intval($faData["LEVEL"]);
			
			$faData["WIDTH"] = intval($faData["WIDTH"]);
			$faData["HEIGHT"] = intval($faData["HEIGHT"]);
			
			$faData["SET"] = intval($faData["SET"]);
			
			//--- Format Binaire
			$faData["ENABLED"] = ord($faData["ENABLED"]);
			$faData["SKILLED"] = ord($faData["SKILLED"]);
			
			
			$ITEMS[] = array_values($faData);
			
			$first = false;
		}
		
		/** Assembler les données **/
		$DATAS = Array(
			"HEADERS" => $HEADERS,
			"ITEMS" => $ITEMS
		);
		
		
		/** Renvoyer les objets trouvés  **/
		return $DATAS;
	} catch (Exception $e){
		error_log(sprintf("[ MGDG ] :: load_items() failed on %s with error %s", $query, $e->getMessage()));
		return false;
	}
}
?>