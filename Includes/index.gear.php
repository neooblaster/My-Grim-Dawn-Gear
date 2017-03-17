<?php
	/** Gestion des données de sessions **/
	$_SESSION['BUILD_PROTECTED'] = false;
	$_SESSION['BUILD_SIGNED'] = false;


	/** > Chargement des jeux de donnée **/
		$vars['ITEMS_FAMILIES'] = load_items_families();
		//$vars['ITEMS_QUALITIES'] = load_items_qualities();
		$vars['ITEMS_TYPES'] = load_items_types();
		//$vars['ITEMS'] = load_items();

	/** Indique que le volet des attributs est fermé (stats_fold) **/
	$vars['FOLD_STATS'] = "";

	/** Indique que le volet inventaire est fermé (inventory_fold) **/
	$vars['FOLD_INVENTORY'] = "";

	/** Indique si la page est en mode CREATE ou READ (demande de build existant) **/
	$vars['VIEW_MODE'] = 'CREATE';

	/** Mode READ : Indique l'ID du build **/
	$vars['BUILD_ID'] = '';

	/** Mode READ : Indique le nom du build**/
	$vars['BUILD_NAME'] = '';

	/** Mode READ : Indique si le build dispose d'un passcode **/
	$vars['BUILD_PROTECTED'] = false;



	/** Si une demande d'affichage est effectuer, alors charger les données du build **/
	if(isset($_GET['gear']) && $_GET['gear'] !== ''){
		$gear = $_GET['gear'];
		$clause = null;
		
		/** Identifier la clause SQL **/
		if(preg_match('#[a-zA-Z0-9]{6}#', $gear)){// Code de build
			$clause = "CODE = '$gear'";
		} else if(preg_match('#[0-9]+#', $gear)) {// Identifiant du build
			$clause = "ID = $gear";
		}
		
		if($clause){
			$query = "
				SELECT 
					ID,
					CODE,
					PASSWORD,
					NAME,
					FOLD_STATS,
					FOLD_INVENTORY,
					VIEWS
					
				FROM BUILDS 
				
				WHERE 
					$clause
			";
			
			try {
				$qGear = $PDO->query($query);
				$faGear = $qGear->fetch(PDO::FETCH_ASSOC);
				
				if($qGear->rowCount() > 0){
					$vars['VIEW_MODE'] = 'READ';
					$vars['BUILD_ID'] = $faGear['ID'];
					$vars['BUILD_CODE'] = $faGear['CODE'];
					$vars['BUILD_NAME'] = $faGear['NAME'];
					$vars['HEAD_VIEW_TITLE'] = $faGear['NAME'];
					unset($head_view_title);
					
					if($faGear['PASSWORD']){
						$vars['BUILD_PROTECTED'] = true;
						$_SESSION['BUILD_PROTECTED'] = true;
					}
					
					if($faGear['FOLD_STATS'] > 0){
						$vars['FOLD_STATS'] = "stats_fold";
					}
					
					if($faGear['FOLD_INVENTORY'] > 0){
						$vars['FOLD_INVENTORY'] = "inventory_fold";
					}
				}
			} catch (Exception $e){
				trigger_error("SQL Error ".$e->getMessage(), E_USER_ERROR);
			}
		}
	}

?>