<?php
	
	/** > Inclure le script CKEDITOR **/
		$vars['INCLUDE_CKEDITOR'] = "true";


	/** > Chargement des jeux de donnée **/
		$vars['ARTICLES'] = load_articles();
		$vars['ITEMS_FAMILIES'] = load_items_families();
		$vars['ITEMS_QUALITIES'] = load_items_qualities();
		$vars['ITEMS_TYPES'] = load_items_types();
		$vars['ITEMS'] = load_items();

	/** > Initialisation des statut Active **/
		$vars['ACTIVE_ITEMS'] = '';
		$vars['ACTIVE_SKILLS'] = '';
		$vars['ACTIVE_ARTICLES'] = '';
		$vars['ACTIVE_GAME_DATA'] = '';

		$vars['ENABLED_SELECTED'] = '';
		$vars['DISABLED_SELECTED'] = '';

	/** > Initialisation des données **/
		$vars['ITEM_ID'] = '';
		$vars['ITEM_TAG_NAME'] = '';
		$vars['ITEM_NAME'] = '';
		$vars['ITEM_WIDTH'] = '';
		$vars['ITEM_HEIGHT'] = '';
		
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
				$query = "
					SELECT 
						COUNT(*) AS ITEM,
						Itm.TAG_ITEM AS ITEM_TAG_NAME,
						Itm.ENABLED AS ITEM_ENABLED,
						ItmTN.NAME AS ITEM_NAME,
						Itm.REL_QUALITY AS ITEM_QUALITY,
						Itm.REL_FAMILY AS ITEM_FAMILY,
						Itm.REL_TYPE AS ITEM_TYPE,
						Itm.WIDTH AS ITEM_WIDTH,
						Itm.HEIGHT AS ITEM_HEIGHT
						
					FROM ITEMS AS Itm
					
					INNER JOIN ITEMS_TAGS_NAMES AS ItmTN
					ON Itm.TAG_ITEM = ItmTN.TAG_NAME
					
					WHERE 
							Itm.ID = $id
						AND
							LANg = '$lang_key'
				";
				
				$qData = $PDO->query($query);
				
				$faData = $qData->fetch(PDO::FETCH_ASSOC);
				
				//pprints($faData);
				
				if($faData['ITEM'] > 0){
					$vars['ITEM_ID'] = $id;
					$vars['ITEM_TAG_NAME'] = $faData['ITEM_TAG_NAME'];
					$vars['ITEM_NAME'] = $faData['ITEM_NAME'];
					$vars['ITEM_WIDTH'] = $faData['ITEM_WIDTH'];
					$vars['ITEM_HEIGHT'] = $faData['ITEM_HEIGHT'];
					
					if($faData['ITEM_ENABLED']){
						$vars['ENABLED_SETTED'] = "selected";
					} else {
						$vars['DISABLED_SETTED'] = "selected";
					}
				}
				
				/** > Ajustement des jeu de données : **/
					// QUALITY
					foreach($vars['ITEMS_QUALITIES'] as $key => $value){
						if($value['ID'] === $faData['ITEM_QUALITY']){
							$vars['ITEMS_QUALITIES'][$key]['SELECTED'] = 'selected';
						} else {
							$vars['ITEMS_QUALITIES'][$key]['SELECTED'] = '';
						}
					}
					
					// FAMILY
					foreach($vars['ITEMS_FAMILIES'] as $key => $value){
						if($value['ID'] === $faData['ITEM_FAMILY']){
							$vars['ITEMS_FAMILIES'][$key]['SELECTED'] = 'selected';
						} else {
							$vars['ITEMS_FAMILIES'][$key]['SELECTED'] = '';
						}
					}
					
					// TYPES
					foreach($vars['ITEMS_TYPES'] as $key => $value){
						if($value['ID'] === $faData['ITEM_TYPE']){
							$vars['ITEMS_TYPES'][$key]['SELECTED'] = 'selected';
						} else {
							$vars['ITEMS_TYPES'][$key]['SELECTED'] = '';
						}
					}
			} catch(Exception $e){
				trigger_error($e->getMessage(), E_USER_ERROR);
			}
		}
?>