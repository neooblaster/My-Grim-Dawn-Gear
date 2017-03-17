<?php
/** -------------------------------------------------------------------------------------------------------------------- ** 
/** -------------------------------------------------------------------------------------------------------------------- ** 
/** ---																																					--- **
/** --- 											-----------------------------------------------											--- **	
/** --- 																{ progress.php }  															--- **
/** --- 											-----------------------------------------------											--- **	
/** ---																																					--- **
/** ---		AUTEUR 	: Neoblaster																												--- **
/** ---																																					--- **
/** ---		RELEASE	: 13.07.2016																												--- **
/** ---																																					--- **
/** ---		VERSION	: 1.1																															--- **
/** ---																																					--- **
/** ---																																					--- **
/** --- 														-----------------------------														--- **
/** --- 															{ C H A N G E L O G } 															--- **
/** --- 														-----------------------------														--- **	
/** ---																																					--- **
/** ---																																					--- **
/** ---		VERSION 1.1 : 13.07.2016																											--- **
/** ---		------------------------																											--- **
/** ---			- Remise aux normes																												--- **
/** ---																																					--- **
/** ---		VERSION 1.0 : 27.03.2015																											--- **
/** ---		------------------------																											--- **
/** ---			- Première release																												--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** ---																																					--- **
/** ---														PREPARATION DU SCRIPT																--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** > Chargement des Classes **/
/** > Chargement des Paramètres **/
/** > Chargement des Configs **/
setup('Setups', Array('application'), 'setup.$1.php');



/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** ---																																					--- **
/** ---													INITIALISATION DU PROGRAMME															--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **	
/** > Initialisation des variables + description **/
	$query_str;							// STRING	: Déclaration de la requête SQL pour une meilleure lisibilité
	$store_version = Array();		// ARRAY		: Stockage des versions trouvée
	$store_update = Array();		// ARRAY		: Stockage des updates (pointeur vers versions)
	$store_milestone = Array();	// ARRAY		: Stockage des milestones (pointeur vers update)
	$update_ref_index = Array();	// Association ID <> Index entre Update et Milestones
	$version_ref_index = Array();	// ARRAY		: Association ID <> INDEX entre Version et Update
	$lang;								// SYSLang	: Moteur de langue
	$moteur; 							// Template	: Moteur de rendu
	$texts;								// ARRAY		: Textes extrait des packs de langue

/** > Initialisation des variables **/
	$lang = new SYSLang('Languages');


/** > Initialisation des variables pour le moteur **/	
	$VERSION_SECTION;					// Variable pour référence. Contient les données compilée.
	$VERSION_SECTION_INSTANCE;		// Donnée VERSION de l'instance en cours d'analyse.
	$UPDATE_SECTION;					// Donnée de UPDATE_SECTION compilé à passé dans VERSION_SECTION.
	$UPDATE_SECTION_INSTANCE;		// Donnée UPDATE de l'instance en cours d'analyse.

	
	$vars = Array(
		'VERSION_SECTION' =>&$VERSION_SECTION
	);
	
	
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** ---																																					--- **
/** ---															CHARGEMENT DES TEXTES															--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **/
	$texts = $lang->unpack('common.xml', 'progress.xml');
	$vars = array_merge($vars, $texts['Serveur']);
	
	
	
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** ---																																					--- **
/** ---											TRAITEMENT DU SCRIPT POUR GENERATION DE DONNEE											--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** > Récupération des donnée relative à l'application Mobius d'id : x **/
	/** 1. CONNEXION SQL **/
	$PDO = new PDO("mysql:host=".SQL_SERVER.";dbname=WEBDEVPROGRESS;charset=utf8;", "WEBDEVPROGRESS", "AB6135AAEB", array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));


	/** 2. DECLARATION DE LA REQUETE **/
	$query_str = "
		SELECT 
				
				DPV.ID AS VERSION_ID,
				DPV.Version AS VERSION_NAME,
				
				DPU.ID AS UPDATE_ID,
				DPU.Version AS UPDATE_VERSION,
				DPU.Name AS UPDATE_NAME,
				DPU.Statut AS UPDATE_STATUT,
				
				DPM.ID AS MILESTONE_ID,
				DPM.Update AS MILESTONE_UPDATE,
				DPM.Progress AS MILESTONE_PROGRESS,
				DPM.Weight AS MILESTONE_WEIGHT,
				DPM.LastUpdate AS MILESTONE_LASTUPDATE

			FROM

				UPDATES AS DPU

			INNER JOIN	

				VERSIONS AS DPV

			ON

				DPU.Version = DPV.ID

			INNER JOIN

				MILESTONES AS DPM

			ON

				DPM.Update = DPU.ID

			WHERE

				DPV.App = ".VERSION_APP_ID." AND
				DPU.Statut != 'Hidden'

			ORDER BY DPV.Version DESC, DPU.Name ASC
	"; /** END_DECLARE_QUERY **/


	/** 3. EXECUTION DE LA REQUETE SQL **/
	try {
		$qData = $PDO->query($query_str);
	} catch(Exception $e){
		trigger_error($e->getMesage(), E_USER_ERROR);
	}


	/** 4. DISPATCH DES DONNESS **/
	while($buffer = $qData->fetch(PDO::FETCH_ASSOC)){
		/** Ajouter la version et ses données dans le magasin si elle n'y existe pas **/
		/** Test d'existante à l'aide de gettype sur la table de reference correspondante **/
		if(gettype($version_ref_index[$buffer['VERSION_ID']]) == 'NULL'){
			$version_ref_index[$buffer['VERSION_ID']] = count($store_version);
			$store_version[] = Array(
				'VERSION_ID' => $buffer['VERSION_ID'],
				'VERSION_NAME' => $buffer['VERSION_NAME']
			);
		}
		
		/** Ajouter lupdate et ses données dans le magasin si elle n'y existe pas  **/
		/** Test d'existante à l'aide de gettype sur la table de reference correspondante **/
		if(gettype($update_ref_index[$buffer['UPDATE_ID']]) == 'NULL'){
			$update_ref_index[$buffer['UPDATE_ID']] = count($store_update);
			$store_update[] = Array(
				'UPDATE_ID' =>	$buffer['UPDATE_ID'],
				'UPDATE_VERSION' =>	$buffer['UPDATE_VERSION'],
				'UPDATE_NAME' => $buffer['UPDATE_NAME'],
				'UPDATE_STATUT' => $buffer['UPDATE_STATUT'],
				'UPDATE_PROGRESS' => 0,
				'UPDATE_WEIGHT' => 0,
				'UPDATE_LASTUPDATE' => 0
			);
		}
		
		/** Stockage de tout les jalons confondu **/
		$store_milestone[] = Array(
			'MILESTONE_ID' => $buffer['MILESTONE_ID'],
			'MILESTONE_UPDATE' => $buffer['MILESTONE_UPDATE'],
			'MILESTONE_PROGRESS' => intval($buffer['MILESTONE_PROGRESS']),
			'MILESTONE_WEIGHT' => intval($buffer['MILESTONE_WEIGHT']),
			'MILESTONE_LASTUPDATE' => $buffer['MILESTONE_LASTUPDATE']
		);
	} /** END_DISPATCH_DATA **/


	/** 5. CALCUL DES DONNEES DES UPDATE A PARTIR DES MILESTONE **/
	foreach($store_milestone as $key => $value){
		/** Incrémentation des valeur Progress et Weight **/
		$store_update[$update_ref_index[$store_milestone[$key]['MILESTONE_UPDATE']]]['UPDATE_PROGRESS'] += $store_milestone[$key]['MILESTONE_PROGRESS'];
		$store_update[$update_ref_index[$store_milestone[$key]['MILESTONE_UPDATE']]]['UPDATE_WEIGHT'] += $store_milestone[$key]['MILESTONE_WEIGHT'];
		
		/** Determination de la date la plus récente **/
		$timecode = $store_milestone[$key]['MILESTONE_LASTUPDATE'];
		$timecode = explode('.', $timecode);
		
		$timestamp = mktime(0, 0, 0, intval($timecode[1]), intval($timecode[2]), intval($timecode[0]));
		
		/** Mise à jour de LastUpdate de l'Update**/
		$store_update[$update_ref_index[$store_milestone[$key]['MILESTONE_UPDATE']]]['UPDATE_LASTUPDATE'] = 
			/** Si LastUpdate de l'update supérieur à timestamp **/
			($store_update[$update_ref_index[$store_milestone[$key]['MILESTONE_UPDATE']]]['UPDATE_LASTUPDATE'] > $timestamp) ?
				/** Alors concerver la valeur **/
				$store_update[$update_ref_index[$store_milestone[$key]['MILESTONE_UPDATE']]]['UPDATE_LASTUPDATE'] :
				/** Sinon, mettre à jour **/
				$timestamp
			;
	} /** END_DATA_CALCULATION **/




	/** 6. Affinage des donnée (timestamp to date & Progress/Weight sur echelle 100%) **/
	foreach($store_update as $key => $value){
		/** timestamp to date **/
		$store_update[$key]['UPDATE_LASTUPDATE'] = date('Y.m.d', $store_update[$key]['UPDATE_LASTUPDATE']);
		
		/** Converstion des valeurs Progress et Weight **/
		$coef = 100 / $store_update[$key]['UPDATE_WEIGHT'];
		
		$store_update[$key]['UPDATE_PROGRESS'] *= $coef;
		$store_update[$key]['UPDATE_WEIGHT'] *= $coef;
			
	} /** END_DATA_FINALISATION **/



	/** 7. COMPILATION DES DONNEES POUR LE MOTEUR **/
	foreach($store_version as $key_ver => $value_ver){
		$UPDATE_SECTION = Array();
		
		
		/** Parcourir les updates et associé ceux ratacher à la version en cours **/
		foreach($store_update as $key_ud => $value_ud){
			if($store_update[$key_ud]['UPDATE_VERSION'] == $store_version[$key_ver]['VERSION_ID']){
				$UPDATE_SECTION_INSTANCE = Array(
					'UPDATE_PROGRESS_VALUE' => floor($store_update[$key_ud]['UPDATE_PROGRESS']),
					'UPDATE_STATUT' => $store_update[$key_ud]['UPDATE_STATUT'],
					'UPDATE_LASTUPDATE' => $store_update[$key_ud]['UPDATE_LASTUPDATE'],
					'UPDATE_NAME' => $store_update[$key_ud]['UPDATE_NAME']
				);
			
			
				$UPDATE_SECTION[] = $UPDATE_SECTION_INSTANCE;
			}
		}
		
		
		
		/** Génération de l'instance de la version $key_ver **/
		$VERSION_SECTION_INSTANCE = Array(
			'VERSION_NAME' => $store_version[$key_ver]['VERSION_NAME'],
			'UPDATE_SECTION' => $UPDATE_SECTION
		);
		
		/** Push de la version générée **/		
		$VERSION_SECTION[] = $VERSION_SECTION_INSTANCE;
		
	} /** END_DATA_COMPILATION **/


/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** ---																																					--- **
/** ---												GENERATION DE LA PAGE DE VERSIONNING													--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** > Création du moteur **/
	$moteur = new Template();
	
/** > Configuration du moteur **/
//	//$moteur->set_blocks_vars($blocks_vars);
	$moteur->set_template_file('Templates/progress.master.tpl.html');
//	$moteur->set_temporary_repository('Temp');
	$moteur->set_output_name('progress.html');
	$moteur->set_vars($vars);
//	$moteur->set_blocks_vars($blocks_vars);
//	//$moteur->set_utf8_write_treatment('decode');
	
/** > Execution du moteur **/	
	$moteur->render();
	$moteur->display();
?>