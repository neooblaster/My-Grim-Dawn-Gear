<?php
	
	/** > Inclure le script CKEDITOR **/
		$vars['INCLUDE_CKEDITOR'] = "true";


	/** > Chargement des jeux de donnée **/
		$vars['ARTICLES'] = load_articles();
		$vars['ITEMS_FAMILIES'] = load_items_families();
		$vars['ITEMS_QUALITIES'] = load_items_qualities();
		$vars['ITEMS_TYPES'] = load_items_types();
		$vars['ITEMS_ATTACHMENTS'] = load_items_attachments();
		$vars['ITEMS'] = load_items();
		$vars['SETS'] = load_sets($lang_key);


	/** > Initialisation des statut Active **/
		$vars['ACTIVE_ITEMS'] = '';
		$vars['ACTIVE_SKILLS'] = '';
		$vars['ACTIVE_ARTICLES'] = '';
		$vars['ACTIVE_GAME_DATA'] = '';

		$vars['ENABLED_SELECTED'] = '';
		$vars['DISABLED_SELECTED'] = '';


	/** > Initialisation des données **/
		$vars['ITEM_ID'] = '';
		$vars['ITEM_TAG'] = '';
		$vars['ITEM_NAME'] = '';
		$vars['ITEM_DESCRIPTION'] = '';
		$vars['ITEM_WIDTH'] = '';
		$vars['ITEM_HEIGHT'] = '';
		$vars['ITEM_PHYSIQUE'] = '';
		$vars['ITEM_CUNNING'] = '';
		$vars['ITEM_SPIRIT'] = '';
		$vars['ITEM_LEVEL'] = '';
		
		// Si $_GET['edit'] 
		if(isset($_GET['edit'])){
			$vars['ACTIVE_'.strtoupper($_GET['edit'])] = 'active';
		} else {
			$vars['ACTIVE_ITEMS'] = 'active';
		}

	/** > Récupération des données correspondant à la demande **/
		// Donnée Item
		if(strtolower($_GET['edit']) === 'items' && $_GET['id'] !== ''){
			$id = $_GET['id'];
			
			try {
				/** > Eléboration de la requête SQL **/
				$query = " 
					SELECT
						I.ENABLED,
						CONCAT(I.TAG, I.EXTEND) AS TAG,
						TN.NAME, TN.DESCRIPTION,
						I.FAMILY, I.TYPE, I.QUALITY, I.ATTACHMENT, I.SET,
						I.WIDTH, I.HEIGHT,
						I.LEVEL, I.PHYSIQUE, I.CUNNING, I.SPIRIT
						
					FROM ITEMS AS I
					LEFT JOIN TAGS_NAMES AS TN
					ON I.TAG = TN.TAG
					
					WHERE
						I.ID = :id AND TN.LANG = :lang
				";
				
				
				/** > Execution de la requête SQL **/
				$pQuery = $PDO->prepare($query);
				$pQuery->execute(Array(
					":id" => $id,
					":lang" => $lang_key
				));
				
				
				/** > Traitement des données **/
				if($pQuery->rowCount()){
					$faData = $pQuery->fetch(PDO::FETCH_ASSOC);
					
					$vars['ITEM_ID'] = $id;
					$vars['ITEM_TAG'] = $faData['TAG'];
					$vars['ITEM_NAME'] = $faData['NAME'];
					$vars['ITEM_DESCRIPTION'] = $faData['DESCRIPTION'];
					$vars['ITEM_WIDTH'] = $faData['WIDTH'];
					$vars['ITEM_HEIGHT'] = $faData['HEIGHT'];
					
					$vars['ITEM_PHYSIQUE'] = $faData['PHYSIQUE'];
					$vars['ITEM_CUNNING'] = $faData['CUNNING'];
					$vars['ITEM_SPIRIT'] = $faData['SPIRIT'];
					$vars['ITEM_LEVEL'] = $faData['LEVEL'];
					
					if(ord($faData['ENABLED'])){
						$vars['ENABLED_SETTED'] = "selected";
					} else {
						$vars['DISABLED_SETTED'] = "selected";
					}
				}
				
				
				/** > Ajustement des jeu de données : **/
					// ITEM QUALITY
					foreach($vars['ITEMS_QUALITIES'] as $key => $value){
						if($value['ID'] === $faData['QUALITY']){
							$vars['ITEMS_QUALITIES'][$key]['SELECTED'] = 'selected';
						} else {
							$vars['ITEMS_QUALITIES'][$key]['SELECTED'] = '';
						}
					}
					
					// ITEM FAMILY
					foreach($vars['ITEMS_FAMILIES'] as $key => $value){
						if($value['ID'] === $faData['FAMILY']){
							$vars['ITEMS_FAMILIES'][$key]['SELECTED'] = 'selected';
						} else {
							$vars['ITEMS_FAMILIES'][$key]['SELECTED'] = '';
						}
					}
					
					// ITEM TYPES
					foreach($vars['ITEMS_TYPES'] as $key => $value){
						if($value['ID'] === $faData['TYPE']){
							$vars['ITEMS_TYPES'][$key]['SELECTED'] = 'selected';
						} else {
							$vars['ITEMS_TYPES'][$key]['SELECTED'] = '';
						}
					}
					
					// ITEM SLOT ATTACHMENTS
					foreach($vars['ITEMS_ATTACHMENTS'] as $key => $value){
						if($value['ID'] === $faData['ATTACHMENT']){
							$vars['ITEMS_ATTACHMENTS'][$key]['SELECTED'] = 'selected';
						} else {
							$vars['ITEMS_ATTACHMENTS'][$key]['SELECTED'] = '';
						}
					}
					
					// ITEM SET
					foreach($vars['SETS'] as $key => $value){
						if($value["SET_ID"] === $faData["SET"]){
							$vars['SETS'][$key]['SELECTED'] = 'selected';
						} else {
							$vars['SETS'][$key]['SELECTED'] = '';
						}
					}
			} catch(Exception $e){
				trigger_error($e->getMessage(), E_USER_ERROR);
			}
		}
?>