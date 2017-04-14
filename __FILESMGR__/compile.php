<?php
/** -------------------------------------------------------------------------------------------------------------------- ** 
/** -------------------------------------------------------------------------------------------------------------------- ** 
/** ---																																					--- **
/** --- 											-----------------------------------------------											--- **
/** --- {}--- **
/** --- 											-----------------------------------------------											--- **
/** ---																																					--- **
/** ---		AUTEUR 	: Nicolas DUPRE																											--- **
/** ---																																					--- **
/** ---		RELEASE	: xx.xx.2015																												--- **
/** ---																																					--- **
/** ---		VERSION	: 1.0																															--- **
/** ---																																					--- **
/** ---																																					--- **
/** --- 														-----------------------------														--- **
/** --- 															{ C H A N G E L O G } 															--- **
/** --- 														-----------------------------														--- **	
/** ---																																					--- **
/** ---																																					--- **
/** ---		VERSION 1.0 : xx.xx.2015																											--- **
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
	require_once 'params.php';

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
	$_CONFIGS;			// Listes des configurations
	$_MANIFEST;			// Liste des données pour composer le manifest final pour le package
	$_MD5_BUFFERED;	// Stockage du dernier rapport sous forme d'array associatif
	$_ONLY_FOR_HASH;	// Tableau pour effectuée un hash sur les fichiers uniquement

	$cfg_file;		// Fichier de configuration
	$cfg_group;		// Ensemble name des paramètres
	$description;	// Description du package
	$moteur;			// Instance de renderisation
	$version;		// Version du package
	$preview_only;	// Indique si on désire uniquement une prévisualisation du package



/** > Initialisation des variables **/
	$_MANIFEST = Array(
		'INSERT' => Array(),
		'UPDATE' => Array(),
		'DELETE' => Array()
	);

	$_MD5_BUFFERED = Array();

	$version = $_POST['version'];
	$description = $_POST['description'];
	$preview_only = ($_POST['preview'] === 'true') ? true : false;


/** > Déclaration et Intialisation des variables pour le moteur (référence) **/
	$vars = Array(
		'VERSION' => &$version,
		'VERSION_MD5' => &$version_md5 ,
		'DESCRIPTION' => &$description
	);

/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** ---																																					--- **
/** ---									PHASE 4 - EXECUTION DU SCRIPT DE TRAITEMENT DE DONNEES										--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **/
/** ---							#1. Traitement du fichier de config							--- **/
/** -------------------------------------------------------------------------------- **/
	$cfg_file = fopen('configs.ini', 'r');

	while($buffer = fgets($cfg_file)){
		/** Detection de balise de configuration **/
		if(preg_match('#^\[[a-zA-Z0-9-_.]{1,}\]$#', $buffer)){
			$buffer = str_replace("\n", "", $buffer);
			$buffer = str_replace("\r", "", $buffer);
			
			$cfg_group = substr($buffer, 1, (strlen($buffer) - 2));
		} else {
			if($buffer !== "\n" && !preg_match('#^;|^\##', $buffer)){
				$buffer = str_replace("\n", "", $buffer);
				$buffer = str_replace("\r", "", $buffer);
				
				$_CONFIGS[$cfg_group][] = $buffer;
			}
		}
	}

	fclose($cfg_file);


/** -------------------------------------------------------------------------------- **/
/** ---							#2. Lecture du dernier snapshot								--- **/
/** -------------------------------------------------------------------------------- **/
	if(file_exists('Packages/LAST_MD5_SNAPSHOT')){
		
		$last_snap_file = fopen('Packages/LAST_MD5_SNAPSHOT', 'r');
		
		$last_snap_version = null;
		$last_snap_hash = null;
				
		while($buffer = fgets($last_snap_file)){
			$buffer = str_replace("\n", "", $buffer);
			
			if($buffer !== ''){
				/** > Déterminé la version du dernier shapshot en tant que dernière version  **/
				if(preg_match('#^snapshot_version_name#', $buffer)){
					$buffer = explode('=', $buffer);
					
					$last_snap_version = $buffer[1];
				}
				/** > Déterminé le hash du dernier shapshot en tant que dernier hash  **/
				else if(preg_match('#^snapshot_version_hash#', $buffer)){
					$buffer = explode('=', $buffer);
					
					$last_snap_hash = $buffer[1];
				}
			}
			
			if($last_snap_version !== null AND $last_snap_hash !== null){
				break;
			}
		}
		
		fclose($last_snap_file);
	}

	/** > Re-validation (cas fichier existant, mais purgé) **/
	$last_snap_version = ($last_snap_version === null) ? 'ROOT' : $last_snap_version;
	$last_snap_hash = ($last_snap_hash === null) ? 'ROOT' : $last_snap_hash;

/** -------------------------------------------------------------------------------- **/
/** ---							#3. Rapport MD5 des fichier actuel							--- **/
/** -------------------------------------------------------------------------------- **/
	/** > Création du rapport MD5 temporaire **/
	$md5_report = fopen('Temps/Compiling/TMP_CURRENT_MD5_SNAPSHOT', 'w+');

	/** > Initialisation du MD5 de référence **/
	fputs($md5_report, "[MD5_SNAPSHOT_HEADER]\n");
	fputs($md5_report, "snapshot_version_name=$version\n");
	fputs($md5_report, "snapshot_version_hash=VER_HASH\n");
	fputs($md5_report, "last_snapshot_version_name=$last_snap_version\n");
	fputs($md5_report, "last_snapshot_version_hash=$last_snap_hash\n\n");

	fputs($md5_report, "[MD5_SNAPSHOT_FILES]\n");

	/** > Génération du pattern d'exclusion **/
	$exclude_pattern = null;
	foreach($_CONFIGS['COMPILATOR_IGNORE_FILES'] as $key => $value){
		$exclude_pattern = ($exclude_pattern === null) ? ("($value)") : ($exclude_pattern."|($value)");
	}


	//if(preg_match("#(^(\.){2}$)#", ".")){
	//	echo "ok";
	//} else {
	//	echo "exclus";
	//}


	/** > Déclaration de la fonction de controle récurcive **/
	function md5_report_maker($scan, $path){
		global $md5_report;
		global $exclude_pattern;
		global $_CONFIGS;
		global $_ONLY_FOR_HASH;
		
		foreach($scan as $key => $value){
			//if(!in_array($value, $_CONFIGS['COMPILATOR_IGNORE_FILES']) && !preg_match('#^_#', $value)){
			if(!preg_match("#$exclude_pattern#", $value)){
				/** > Voir si c'est un dossier ou un fichier **/
				$full_path = $path.$value;
				
				if(is_dir($full_path)){
					$new_scan = scandir($full_path);
					md5_report_maker($new_scan, $full_path.'/');
				} else {
					$hash = md5_file($full_path);
					
					$md5_entry = "[".$hash."]\t".$full_path."\n";
					//fputs($md5_report, str_replace('../', '',$md5_entry));
					fputs($md5_report, $md5_entry);
					$_ONLY_FOR_HASH .= "[$hash].$full_path";
				}
			}
		}
	} // END_FUNCTION md5_report_maker()

	/** > Reporting MD5 des fichiers **/
	$scan = scandir('../');
	md5_report_maker($scan, '../');

	/** > Génération du MD5 de l'application **/
	//$version_md5 = md5_file('Temps/Compiling/CURRENT_MD5_SNAPSHOT');
	$version_md5 = md5($_ONLY_FOR_HASH);

	fclose($md5_report);


/** -------------------------------------------------------------------------------- **/
/** ---	#5. Mise à jour de %SNAPSHOT_HASH%	--- **/
/** -------------------------------------------------------------------------------- **/
	$md5_report_tmp = fopen('Temps/Compiling/TMP_CURRENT_MD5_SNAPSHOT', 'r');
	$md5_report = fopen('Temps/Compiling/CURRENT_MD5_SNAPSHOT', 'w+');

	while($buffer = fgets($md5_report_tmp)){
		if(preg_match('#VER_HASH#', $buffer)){
			$buffer = str_replace("VER_HASH", $version_md5, $buffer);
		}
		
		fputs($md5_report, $buffer);
	}

	fclose($md5_report_tmp);
	fclose($md5_report);




/** -------------------------------------------------------------------------------- **/
/** ---	#6. Vérification qu'un package correspondant au hash n'existe pas déjà	--- **/
/** -------------------------------------------------------------------------------- **/
	$packages = scandir('Packages');

	/** > Selon le type de dépende (ROOT = aucune = realse) sinon c'est un UPDATE **/
	$package_type = ($last_snap_version === 'ROOT') ? 'RE' : 'UD';
	
	foreach($packages as $key => $value){
		if(preg_match('#^Package#', $value) AND (preg_match("#$version_md5#", $value) AND preg_match("#$package_type#", $value))){
			echo "Création du package inutile\n";
			echo "MD5 des fichiers de l'application : $version_md5\n";
			echo "Package correspondant : $value\n";
			exit();
		}
	}


/** -------------------------------------------------------------------------------- **/
/** ---					#7. Buffurisation du dernier MD5 pour analyse					--- **/
/** -------------------------------------------------------------------------------- **/
	if(!file_exists('Packages/LAST_MD5_SNAPSHOT')){
		$md5_last_snap = fopen('Packages/LAST_MD5_SNAPSHOT', 'w+');
	} else {
		$md5_last_snap = fopen('Packages/LAST_MD5_SNAPSHOT', 'r');
	}

	$bufferisation = false;
	while($buffer = fgets($md5_last_snap)){
		if($bufferisation){
			$buffer = str_replace("\n", "", $buffer);
			$buffer = str_replace("\r", "", $buffer);
			
			$buffer = explode("\t", $buffer);
			
			$_MD5_BUFFERED[$buffer[1]] = $buffer[0];
		}
		
		if(!$bufferisation AND preg_match('#MD5_SNAPSHOT_FILES#', $buffer)){
			$bufferisation = true;
		}
	}

	fclose($md5_last_snap);

	//print_r($_MD5_BUFFERED);

	//exit();


/** -------------------------------------------------------------------------------- **/
/** ---			#8. Analyse entre le nouveau rapport et le dernier rapport			--- **/
/** -------------------------------------------------------------------------------- **/
	
/**

	Faire un copier du dernier md5 pour le comparer efficacement
	quand ligne trouver dans la copie : comparé puis supprimer du fichier, recommencé
	A la fin reste que les fichier non trouvé donc à supprimé
	faire en parallele le manifest
	enfin faire le package
	
	implementer une option d'update consécutive / update full comme les patch de BF2

**/
	$md5_report = fopen('Temps/Compiling/CURRENT_MD5_SNAPSHOT', 'r');

	/** > Parcourir le rapport MD5 obtenu **/
	$compare = false;
	while($buffer = fgets($md5_report)){
		if($compare){
			$buffer = str_replace("\n", "", $buffer);
			$buffer = str_replace("\r", "", $buffer);
			
			$buffer = explode("\t", $buffer);
			
			$hash = $buffer[0];
			$key = $buffer[1];
			
			/** > Si la clé existe - voir si modifié **/
			if(array_key_exists($key, $_MD5_BUFFERED)){
				if($hash !== $_MD5_BUFFERED[$key]){
					$_MANIFEST['UPDATE'][] = Array(
						'FILE_MD5' => $hash, 
						'FILE' => str_replace('../', '', $key)
					);
				}
				
				unset($_MD5_BUFFERED[$key]);
			}
			/** > Sinon, nouveau fichier ! **/
			else {
				$_MANIFEST['INSERT'][] = Array(
					'FILE_MD5' => $hash, 
					'FILE' => str_replace('../', '', $key)
				);
			}
		}
		
		if(!$compare AND preg_match('#MD5_SNAPSHOT_FILES#', $buffer)){
			$compare = true;
		}
	}


	/** > Recencer les fichiers à supprimer **/
	foreach($_MD5_BUFFERED as $key => $value){
		$_MANIFEST['DELETE'][] = Array(
			'FILE_MD5' => $value, 
			'FILE' => str_replace('../', '', $key)
		);
	}

	

/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** ---																																					--- **
/** ---													PHASE 5 - GENERATION DU MANIFEST														--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **/
/** > Création du moteur **/
	$moteur = new Template();

/** > Configuration du moteur **/
	$moteur->set_output_name('manifest');
	$moteur->set_template_file('Templates/manifest.tpl');
	$moteur->set_render_type('permanent');
	$moteur->set_output_directories('Temps/Compiling');

/** > Envoie des données **/
	//$moteur->set_vars();
	$vars = array_merge($vars, $_MANIFEST);

	$moteur->set_vars($vars);

/** > Execution du moteur **/
	$moteur->render();
	$moteur->cleansing_render_env();
	//$moteur->help();



/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** ---																																					--- **
/** ---													PHASE 6 - GÉNÉRATION DU PACKAGE														--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **/
if(!$preview_only){
	/** > Selon le type de dépende (ROOT = aucune = realse) sinon c'est un UPDATE **/
	$package_type = ($last_snap_version === 'ROOT') ? 'RE' : 'UD';
	
	/** > Création du répository **/
	$package = 'Packages/Package_'.$package_type.'_V_'.$version.'_'.$version_md5.'.zip';
	
	if(file_exists($package)){
		goto finalisation;
	}	

	/** > Création de l'archive **/
	$zip_package = new ZipArchive();

	$zip_package->open($package, ZipArchive::CREATE);
	
	/** > Intégration du manifest **/
	$zip_package->addFile('Temps/Compiling/manifest', '__MANIFEST__/manifest');
	$zip_package->addFile('Temps/Compiling/CURRENT_MD5_SNAPSHOT', '__MANIFEST__/THIS_MD5_SNAPSHOT');
	$zip_package->addFile('configs.ini', '__MANIFEST__/configs.ini');
	
	/** > Intégration des fichiers **/
	$manifest_data = fopen('Temps/Compiling/manifest', 'r');

	while($buffer = fgets($manifest_data)){
		/** Detection de balise de configuration **/
		if(preg_match('#^\[[a-zA-Z0-9-_.]{1,}\]$#', $buffer)){
			$buffer = str_replace("\n", "", $buffer);
			$buffer = str_replace("\r", "", $buffer);
			
			$cfg_group = substr($buffer, 1, (strlen($buffer) - 2));
		} else {
			if($buffer !== "\n" && !preg_match('#^;|^\##', $buffer)){
				if($cfg_group === 'INSERT' || $cfg_group === 'UPDATE'){
					$buffer = str_replace("\n", "", $buffer);
					$buffer = str_replace("\r", "", $buffer);
					$buffer = explode("\t", $buffer);
					
					$zip_package->addFile('../'.$buffer[1], $buffer[1]);
				}
			}
		}
	}

	fclose($manifest_data);
	
	/** > Fermeture de l'archive **/
	$zip_package->close();
	
	/** > Finalisation **/
	finalisation:
	
	/** > Sauvegarder la dernière lecture MD5 en guise de dernier snapshot **/
	copy('Temps/Compiling/CURRENT_MD5_SNAPSHOT', 'Packages/LAST_MD5_SNAPSHOT');
}

	/** > Afficher les sorties **/
	echo "Compilation terminée :\n\n";
	echo '<input type="button" value="Rafraichir" onclick="document.location.reload();"/>';
	echo "\n\n";
	echo file_get_contents('Temps/Compiling/manifest');
	echo "\n\n";
	echo '<input type="button" value="Rafraichir" onclick="document.location.reload();"/>';


	/** > Nettoyer les données temporaire **/
	@unlink('Temps/Compiling/TMP_CURRENT_MD5_SNAPSHOT');
	@unlink('Temps/Compiling/CURRENT_MD5_SNAPSHOT');
	@unlink('Temps/Compiling/manifest');

?>