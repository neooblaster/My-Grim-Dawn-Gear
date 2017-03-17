<?php
/** -------------------------------------------------------------------------------------------------------------------- ** 
/** -------------------------------------------------------------------------------------------------------------------- ** 
/** ---																																					--- **
/** --- 														------------------------------													--- **
/** ---																{ cryptpwd.php }																--- **
/** --- 														------------------------------													--- **
/** ---																																					--- **
/** ---		AUTEUR 	: Nicolas DUPRE																											--- **
/** ---																																					--- **
/** ---		RELEASE	: 19.07.2015																												--- **
/** ---																																					--- **
/** ---		VERSION	: 1.2																															--- **
/** ---																																					--- **
/** ---																																					--- **
/** --- 														---------------------------														--- **
/** ---															{ C H A N G E L O G }															--- **
/** --- 														---------------------------														--- **
/** ---																																					--- **
/** ---																																					--- **
/** ---		VERSION 1.2 : 19.07.2015																											--- **
/** ---		------------------------																											--- **
/** ---			- Correction de la fonction pour PHP 5.6.9 où les offset négatif ne sont pas autorisé dans les Array	--- **
/** ---				> Ajout d'un test conditionnel pour éviter l'erreur de niveau notice : Undefined offset: -1(...) 	--- **
/** ---																																					--- **
/** ---		VERSION 1.1 : 28.11.2014																											--- **
/** ---		------------------------																											--- **
/** ---			- Correction de l'étape : 2. CONVERSION. Modification du modulo de 16 à 15										--- **
/** ---			- Suppresision d'un appel parasite																							--- **
/** ---																																					--- **
/** ---		VERSION 1.0 : xx.xx.xxx																												--- **
/** ---		------------------------																											--- **
/** ---			- Première release																												--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **

	Requirements :
	--------------

	Input Params :
	--------------
	
	Output Params :
	---------------

	Objectif du script :
	---------------------
	
		Le role de cette fonction est de fournir un mot de passe crypter non décryptable.
	Les fonctions crypt, sha1, et md5 sont facilement décryptable et ne peuvent être simplement utilisée.
		En cas de piratage de la base de données, les mot de passe reste sécurisé à mois d'avoir accès à ce document
		
		Noter que la fonction génère une erreur pseudo-volontaire rendant le décryptage partiellement impossible
	
	Description fonctionnelle :
	----------------------------

/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **/
function cryptpwd($password, $key){
	/***********************/
	/** 0. INITIALISATION **/
	/***********************/
	$password = sha1($password);
	$key = sha1($key);
	
	$kToV = Array(
		'0' => 0, '1' => 1, '2' => 2, '3' => 3,
		'4' => 4, '5' => 5, '6' => 6, '7' => 7,
		'8' => 8, '9' => 9, 'a' => 10, 'b' => 11,
		'c' => 12, 'd' => 13, 'e' => 14, 'f' => 15
	);		
	$vToK = Array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');	
	
	
	$sha1_calculated = null;
	$output = null;
	
	/********************/
	/** 1. CALCULATION **/
	/********************/
	for($i = 0; $i < strlen($password); $i++){
		$sha1_calculated .= ($sha1_calculated == null) ? ($kToV[$password[$i]] + $kToV[$key[$i]]) : '.'.($kToV[$password[$i]] + $kToV[$key[$i]]) ;
		$kToV[$password[$i]].' + '.$kToV[$key[$i]];
	}
	
	$sha1_calculated = explode('.', $sha1_calculated);
	
	/*******************/
	/** 2. CONVERTION **/
	/*******************/
	for($i = count($sha1_calculated) - 1; $i >= 0; $i--){
		$cr_value = $sha1_calculated[$i];
		
		do {
			if($cr_value > 15){
				$cr_value -= 16;
				if(($i - 1) > 0){
					$sha1_calculated[$i-1]++; 
				}
			}
		} while($cr_value > 15);
		$output .= $vToK[$cr_value];
	}
	
	return $output;
}	
?>