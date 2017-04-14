<?php
/** -------------------------------------------------------------------------------------------------------------------- ** 
/** -------------------------------------------------------------------------------------------------------------------- ** 
/** ---																																					--- **
/** --- 											-----------------------------------------------											--- **
/** --- 															{ I N D E X . P H P } 															--- **
/** --- 											-----------------------------------------------											--- **
/** ---																																					--- **
/** ---		AUTEUR 	: Neoblaster																												--- **
/** ---																																					--- **
/** ---		RELEASE	: 29.03.2015																												--- **
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
/** --- 														-----------------------------														--- **
/** ---															{ S E S S I O N S }																--- **
/** --- 														-----------------------------														--- **
/** ---																																					--- **
/** ---		---	lang	: Langue a utiliser dans le site																					--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** ---																																					--- **
/** ---													PHASE 1 - INITIALISATION DU SCRIPT													--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** > Ouverture des SESSIONS Globales **/
	//session_start();
/** > Chargement des Configuration **/
	setup('/Setups', Array('application', 'sessions', 'pdo'), 'setup.$1.php');

/** > Chargement des Classes **/
/** > Chargement des Configs **/
	//require_once 'configs.php'; // A REVOIR
	require_once 'Configs/config.version.php';

/** > Chargement des Fonctions **/
	require_once 'Processors/Functions/Common/loadCSS.php';
	require_once 'Processors/Functions/Common/loadLESS.php';
	require_once 'Processors/Functions/Common/loadScriptsJS.php';
	
	require_once 'Processors/Functions/Index/load_articles.php';
	require_once 'Processors/Functions/Index/load_items.php';
	require_once 'Processors/Functions/Index/load_items_families.php';
	require_once 'Processors/Functions/Index/load_items_qualities.php';
	require_once 'Processors/Functions/Index/load_items_types.php';
	require_once 'Processors/Functions/Index/load_items_attachments.php';

	require_once 'Processors/Functions/Index/timestamp_to_date.php';



/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** ---																																					--- **
/** ---												PHASE 2 - INITIALISATION DES DONNEES													--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **/
/** > Déclaration des variables **/
	//$CONTENT_CLASS;	// STRING	: Classe à appliquer au div d'attribut content (corps) selon la section désirée
	$head_view_title;	// STRING	: Titre de la vue à afficher dans la balise title
	$view_title;		// STRING	: Titre de la vue à afficher en tant que titre visuel dans la page
	$lang;				// SYSLang	: Moteur de langue
	$lang_key;			// STRING	: Code de la langue utilisée
	$lang_lang;			// STRING	: Langue utilisé en version deux lettre - en-EN > en
	//$LANG_NAME;			// STRING	: Nom de la langue dans sa propre langue
	//$languages;			// ARRAY		: Liste des langues disponible pour Mobius World
	//$LANGUAGES;			// ARRAY		: Liste des langues à construire dans la page via le moteur
	//$LOAD_TEMPLATE;	// STRING	: Modèle à charger et inclure dans le corps du site selon la vue (contenu) que l'on désire afficher
	$moteur;				// Template	: Classe Template correspondant au moteur de compilation donnée/forme en un document finalisé
	$textes;				// ARRAY		: Textes correspondant à la langue désirée
	$view;				// STRING	: Partie du site à afficher
	//$views_allowed;	// ARRAY		: Valeur autorisée pour le paramètre GET 'view'
	$token;				// INTEGER	: Jeton de l'onglet du navigateur


/** > Initialisation des variables **/
	/** Controle de la vue demandée **/
	if(isset($_GET['view']) && defined("VIEW_ALLOWED_".strtoupper($_GET["view"]))){
		if(constant("VIEW_ALLOWED_".strtoupper($_GET["view"]))){
			if(defined("ADMIN_VIEW_".strtoupper($_GET["view"]))){
				if(isset($_SESSION['MGDG-ADMIN']) && $_SESSION['MGDG-ADMIN']){
					$view = $_GET['view'];
				} else {
					$view = strtolower(DEFAULT_VIEW);
				}
			} else {
				$view = strtolower($_GET['view']);
			}
		} else {
			$view = strtolower(DEFAULT_VIEW);
		}
	} else {
		$view = strtolower(DEFAULT_VIEW);
	}

	//$LOAD_TEMPLATE = $view;

	$lang = new SYSLang('Languages');
	$token = rand(1, 999);


/** > Déclaration et Intialisation des variables pour le moteur (référence) **/
	$vars = Array(
		// @Donnée simple
		"VERSION" => VERSION,
		"MGDG-ADMIN" => ((isset($_SESSION['MGDG-ADMIN']) && $_SESSION['MGDG-ADMIN']) ? 'true' : 'false'),
		"VIEW" => &$view,
		"HEAD_VIEW_TITLE" => &$head_view_title,
		"VIEW_TITLE" => &$view_title,
		"INCLUDE_CKEDITOR" => "false",
	//	"CONTENT_CLASS" => &$CONTENT_CLASS,
	//	"CONTENT_VIEW_CLASS" => &$CONTENT_VIEW_CLASS,
		"LANG_LANG" => &$lang_lang,
	//	"LANG_KEY" => &$LANG_KEY,
	//	"LANG_NAME" => &$LANG_NAME,
	//	"SKY_GRADIENT" => get_sky_gradient(), 
	//	"DEPLOYED" => 'false',
		"TOKEN" => $token,

		// @Jeu de donnée 
		"CSS_BLOCK" => loadCSS(false, 'CSS/Common', 'CSS/Index'),
		"LESS_BLOCK" => @loadLESS(false, 'LESS/Common', 'LESS/Index'),
		"SCRIPTS_BLOCK" => @loadScriptsJS(false, 'Scripts/Common', 'Scripts/Index', "Scripts/Index/$view")
	//	"LANGUAGES" => &$LANGUAGES,
	);

/** > Récupération de la liste des langues disponibles : **/
	//$languages = $lang->get_avail_languages();
	//$LANGUAGES = $languages['LIST'];

/** > Récupération de la langue définie **/
	$lang_key = $lang->get_lang();


/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** ---																																					--- **
/** ---													PHASE 3 - CHARGEMENT DES TEXTES														--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **/
/** > Charger et ajouter les textes dans la variables $vars **/
	$textes = @$lang->unpack('common.xml', "$view.xml");
	$vars = array_merge($vars, $textes['Serveur']);


	
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** ---																																					--- **
/** ---									PHASE 4 - EXECUTION DU SCRIPT DE TRAITEMENT DE DONNEES										--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** > Système de compteur de vue **/
if(defined('VIEW_COUNTER') && constant('VIEW_COUNTER')){
	/** A Ajouter l'exclude ip **/
	$views = intval(file_get_contents('views'));
	$nviews = $views + 1;

	file_put_contents('views', $nviews);
}


/** > Executer les instructions correspondant au contenu désiré **/
	if(file_exists("Includes/index.$view.php")){
		require_once "Includes/index.$view.php";
	}



/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** ---																																					--- **
/** ---											PHASE 5 - GENERATION DES DONNEES DE SORTIE												--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **/
/** > Gérer les titres de la vue **/
	$head_view_title = $vars["HEAD_VIEW_TITLE_".strtoupper($view)];
	$view_title = $vars["VIEW_TITLE_".strtoupper($view)];

/** > Obtenir la langue de la page **/
	$lang_lang = substr($lang_key, 0, 2);

/** > Récupérer le code de langue de l'utilisateur et son nom **/
	//$LANG_KEY = $lang->get_lang();
	//$LANG_NAME = $languages['KEYS'][$LANG_KEY];

/** > Ajouter la class au nom de la vue **/
	//$CONTENT_VIEW_CLASS = "view-$view";



/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** ---																																					--- **
/** ---												PHASE 5 - AFFICHER LES SORTIES GENEREE													--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **/
/** > Création du moteur **/
	$moteur = new Template();

/** > Configuration du moteur **/
	$moteur->set_temporary_repository('Temps');
	$moteur->set_template_file('Templates/index.master.tpl.html');	// Définition du modèle à utiliser
	$moteur->set_output_name('index.html');								// Nomination du document de sortie
		
/** > Envoie des données **/
	$moteur->set_vars($vars);

/** > Execution du moteur **/
	@$moteur->render()->display();
	
?>