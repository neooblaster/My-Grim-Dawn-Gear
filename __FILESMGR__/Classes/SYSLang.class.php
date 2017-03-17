<?php
/** ----------------------------------------------------------------------------------------------------------------------- 
/** ----------------------------------------------------------------------------------------------------------------------- 
/** ---																																						---
/** --- 											----------------------------------------------- 											---
/** --- 													{ M Y S Q L . C L A S S . P H P }														---
/** --- 											----------------------------------------------- 											---
/** ---																																						---
/** ---		AUTEUR 	: Nicolas DUPRE																												---
/** ---																																						---
/** ---		RELEASE	: 30.07.2015																													---
/** ---																																						---
/** ---		VERSION	: 1.1																																---
/** ---																																						---
/** ---																																						---
/** --- 														-----------------------------															---
/** --- 															 { C H A N G E L O G } 																---
/** --- 														-----------------------------															---
/** ---																																						---
/** ---		VERSION 1.1 : 30.07.2015																												---
/** ---		-------------------------																												---
/** ---			- Ajout de la fonction addslashes pour échapper les caractère pour JavaScript [CST=true]						---
/** ---																																						---
/** ---		VERSION 1.0 : 23.06.2015																												---
/** ---		-------------------------																												---
/** ---			- Première release																													---
/** ---																																						---
/** --- 										-----------------------------------------------------											---
/** --- 											{ L I S T E      D E S      M E T H O D E S } 												---
/** --- 										-----------------------------------------------------											---
/** ---																																						---
/** ---		GETTERS :																																	---
/** ---	    ---------																																	---
/** ---																																						---
/** ---			- [Pub] xxxxx																															---
/** ---																																						---
/** ---		SETTERS :																																	---
/** ---	    ---------																																	---
/** ---																																						---
/** ---			- [Pub] xxxxxxxxxxx																													---
/** ---																																						---
/** ---		OUTPUTTERS :																																---
/** ---	    ------------																																---
/** ---																																						---
/** ---		WORKERS :																																	---
/** ---	    ---------																																	---
/** ---																																						---
/** ---			- [Pub] connect																														---
/** ---			- [Pub] disconnect																													---
/** ---																																						---
/** ---																																						---
/** -----------------------------------------------------------------------------------------------------------------------
/** ----------------------------------------------------------------------------------------------------------------------- **/
class SYSLang {
/** -----------------------------------------------------------------------------------------------------------------------
/** -----------------------------------------------------------------------------------------------------------------------
/** ---																																						---
/** ---															{ P R O P E R T I E S }																---
/** ---																																						---
/** -----------------------------------------------------------------------------------------------------------------------
/** ----------------------------------------------------------------------------------------------------------------------- **/
	protected $_avail_languages = null;
	protected $_files_repository = null;
	protected $_user_language = null;
	
/** ----------------------------------------------------------------------------------------------------------------------- 
/** -----------------------------------------------------------------------------------------------------------------------
/** ---																																						---
/** ---														{ C O N S T R U C T E U R S }															---
/** ---																																						---
/** -----------------------------------------------------------------------------------------------------------------------
/** ----------------------------------------------------------------------------------------------------------------------- **/
	function __construct($directory){
		/** Définition du dossier des pack de langues **/
		$this->_files_repository = $directory;
			
		/** Lister les pack de langue disponible **/
		$this->list_languages();
		
		/** Si la langue n'est pas définie à l'aide d'une session, alors à la création, la valeur est celle de l'utilisateur **/
		if(!isset($_SESSION['SYSLang_LANG'])){
			$this->get_user_language();
		} else {
			$this->_user_language = $_SESSION['SYSLang_LANG'];
		}
	}
	
	function __destruct(){
		
	}
	
/** ----------------------------------------------------------------------------------------------------------------------- 
/** ----------------------------------------------------------------------------------------------------------------------- 
/** ---																																						---
/** ---																{ G E T T E R S }																	---
/** ---																																						---
/** -----------------------------------------------------------------------------------------------------------------------
/** ----------------------------------------------------------------------------------------------------------------------- **/
	/** ------------------------------------------------------------------- **
	/** -- Fonction d'affichage des langue disponible dans l'application -- ** 
	/** ------------------------------------------------------------------- **/
	public function get_avail_languages(){
		print_r($this->_avail_languages);
	}
	
	/** ------------------------------------------------------ **
	/** -- Fonction qui permet d'afficher la langue définie -- **
	/** ------------------------------------------------------ **/
	public function get_lang(){
		return $this->_user_language;
	}
	
	/** --------------------------------------------------------------- **
	/** -- Fonction de récupération automatique de la langue système -- **
	/** --------------------------------------------------------------- **/
	private function get_user_language(){
		/** Connexion aux variables globales **/
  			global $_SERVER;	// Superglobale Server
		
		/** Déclaration des variables **/
			$accepted_languages;	// Language admis par le navigateur 
			$matches;				// Résultat des occurences trouvées
			$return;					// Valeur de retour
		
		/** Traitement des languages admis **/
			$accepted_languages = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
			$return = $accepted_languages[0];
		
			//foreach($accepted_languages as $accepted_lang){	
			//	preg_match ("/^(([a-zA-Z]+)(-([a-zA-Z]+)){0,1})(;q=([0-9.]+)){0,1}/" , $accepted_lang, $matches);
			//	
			//	print_r($matches);
			//	
			//	if(!$matches[6]) $matches[6] = 1;
			//	
			//	$result[$matches[1]] = Array(
			//		'lng_base'  => $matches[2],
			//		'lng_ext'   => $matches[4],
			//		'lng'       => $matches[1],
			//		'priority'  => $matches[6],
			//		'_str'      => $accept_language,
			//	);
			//}
		
			//foreach($result as $key => $value){
			//	$return = $key;
			//	break;
			//}
		
		if(!array_key_exists($return, $this->_avail_languages['KEYS'])){
			$this->_user_language = "fr-FR";
		} else {
			$this->_user_language = $return;
		}
	}
	
	
/** ----------------------------------------------------------------------------------------------------------------------- 
/** ----------------------------------------------------------------------------------------------------------------------- 
/** ---																																						---
/** ---																{ S E T T E R S }																	---
/** ---																																						---
/** -----------------------------------------------------------------------------------------------------------------------
/** ----------------------------------------------------------------------------------------------------------------------- **/
	/** **/
	public function set_files_repository($directory){
		/** Tester le dossier de dé$ot **/
		if(file_exists($directory)){
			/** Fixer le dossier de langue à la valeur donnée **/
			$this->_files_repository = $directory;
			
			/** Déclencher l'analyse du fichier des langues disponible **/
			$this->list_languages();
		} else {
			die("SYSLang->set_files_repository() failed :: The directory '$directory' not exist");
		}
			
		return true;
	}
	
	public function set_lang($lang=null){
		/** Si $lang = null, alors cela revient à demander d'utiliser la langue d'origine (si disponible) **/
		if($lang === null || !array_key_exists($lang, $this->_avail_languages['KEYS'])){
			$this->get_user_language();
		} else {
			if(isset($_SESSION)){
				$_SESSION['SYSLang_LANG'] = $lang;
			}
			$this->_user_language = $lang;
		}
	}
	
/** ----------------------------------------------------------------------------------------------------------------------- 
/** ----------------------------------------------------------------------------------------------------------------------- 
/** ---																																						---
/** ---															{ O U T P U T E R S }																---
/** ---																																						---
/** -----------------------------------------------------------------------------------------------------------------------
/** ----------------------------------------------------------------------------------------------------------------------- **/
	/** **/
	public function unpack($lang_files){
		/** Unpackage possible que si le dossier est défini **/
		if($this->_files_repository !== null){
			/** Récupérer tout les fichiers donnée en paramètre (1 minimum) **/
			$lang_files = func_get_args();
				
			/** Initialiser les tableaux de sortie **/
			$client_side_target = Array();
			$server_side_target = Array();
			
			foreach($lang_files as $key => $file){
				/** Chemin complet vers le fichier **/
				$path = $this->_files_repository.'/'.$this->_user_language.'/'.$file;
				
				/** Vérifier que le fichier existe **/
				if(file_exists($path)){
					/** Ouvrir le fichier demandé **/
					$content = file_get_contents($path);
					
					/** Parser le contenu (XML) **/
					$resources = new SimpleXMLElement($content);
					
					/** Parcourir les ressources **/
					for($i = 0; $i < count($resources); $i++){
						/** Extraction des attributs **/
						$key = strval($resources->resource[$i]->attributes()->KEY);
						$cst = strval($resources->resource[$i]->attributes()->CST);
						$sst = strval($resources->resource[$i]->attributes()->SST);
						$value = strval($resources->resource[$i]);
					
						if($cst === 'true'){
							$client_side_target[] = Array("VAR_KEY" => $key, "VAR_VALUE" => addslashes($value));
						}
					
						if($sst === 'true'){
							$server_side_target[$key] = $value;
						}
					}
				} else {
					die("SYSLang->unpack() failed; The lang file '$file' does not exist.");
				}
			}
			
			/** > Renvoyer les données triée **/
			return Array("Client" => $client_side_target, "Serveur" => $server_side_target);
		} else {
			die('SYSLang->unpack() failed; The file repository not defined. Use set_files_repository($directory)');
		}
	}
	
/** ----------------------------------------------------------------------------------------------------------------------- 
/** ----------------------------------------------------------------------------------------------------------------------- 
/** ---																																						---
/** ---																{ W O R K E R S }																	---
/** ---																																						---
/** -----------------------------------------------------------------------------------------------------------------------
/** ----------------------------------------------------------------------------------------------------------------------- **/
	/** **/
	private function list_languages(){
		/** Chercher l'existance du fichier "languages.xml" **/
		if(file_exists($this->_files_repository.'/languages.xml')){
			/** Charger le fichier **/
			$languages = new SimpleXMLElement(file_get_contents($this->_files_repository.'/languages.xml'));
			
			/** Lister les languages **/
			for($i = 0; $i < count($languages); $i++){
				$keys[strval($languages->language[$i]->attributes()->LANG)] = strval($languages->language[$i]);
				$list[] = Array(
					"LANG_NAME" => strval($languages->language[$i]),
					"LANG_KEY" => strval($languages->language[$i]->attributes()->LANG)
				);
			}
			
			$this->_avail_languages = Array("KEYS"=>$keys, "LIST"=>$list);
		} else {
			die('SYSLang->set_files_repository() failed :: missing file : languages.xml');
		}
	}
}

?>