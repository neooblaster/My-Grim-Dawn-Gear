<?php
/** -------------------------------------------------------------------------------------------------------------------- ** 
/** -------------------------------------------------------------------------------------------------------------------- ** 
/** ---																																					--- **
/** --- 										---------------------------------------------------										--- **
/** ---											{ G E T _ U S E R _ L A N G U A G E . P H P }											--- **
/** --- 										---------------------------------------------------										--- **
/** ---																																					--- **
/** ---		AUTEUR 	: Neoblaster																												--- **
/** ---																																					--- **
/** ---		RELEASE	: 27.03.2015																												--- **
/** ---																																					--- **
/** ---		VERSION	: 1.0																															--- **
/** ---																																					--- **
/** ---																																					--- **
/** --- 														-----------------------------														--- **
/** --- 															{ C H A N G E L O G } 															--- **
/** --- 														-----------------------------														--- **	
/** ---																																					--- **
/** ---																																					--- **
/** ---		VERSION 1.0 :																															--- **
/** ---		-------------																															--- **
/** ---			- Première release																												--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **

	Input Params :
	--------------
	
	Output Params :
	---------------
		$result	[Array]	:	Liste des language admis

/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **/
function get_user_language() {
	/** > Connexion aux variables globales **/
  		global $_SERVER;	// Superglobale Server
	
	/** > Déclaration des variables **/
		$accepted_languages;	// Language admis par le navigateur 
		$matches;				// Résultat des occurences trouvées
		$return;					// Valeur de retour
	
	/** > Traitement des languages admis **/
		$accepted_languages = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
	
		foreach($accepted_languages as $accepted_lang){
			preg_match ("/^(([a-zA-Z]+)(-([a-zA-Z]+)){0,1})(;q=([0-9.]+)){0,1}/" , $accepted_lang, $matches);
			
			if(!$matches[6]) $matches[6] = 1;
			
			$result[$matches[1]] = Array(
				'lng_base'  => $matches[2],
				'lng_ext'   => $matches[4],
				'lng'       => $matches[1],
				'priority'  => $matches[6],
				'_str'      => $accept_language,
			);
		}
	
	foreach($result as $key => $value){
		$return = $key;
		break;
	}
	
	return $return;
}
?>
