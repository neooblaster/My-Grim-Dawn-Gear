<?php
	/** > Inclure le script CKEDITOR **/
		$vars['INCLUDE_CKEDITOR'] = "true";
	
	
	/** > Chargement des jeux de donnée **/
		// Liste de données :: Array
		//$vars['ITEMS'] = load_items();
		$vars['ARTICLES'] = load_articles();
		$vars['ITEMS_FAMILIES'] = load_items_families();
		$vars['ITEMS_QUALITIES'] = load_items_qualities();
		$vars['ITEMS_TYPES'] = load_items_types();
		$vars['ITEMS_ATTACHMENTS'] = load_items_attachments();
		$vars['SETS'] = load_sets($lang_key);
		$vars['SKILLS'] = load_skills($lang_key);
		$vars['PROCS'] = load_procs($lang_key);
		$vars['ATTRIBUTES_NAMES'] = load_attributes_names($lang_key);

	
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
		$vars['ITEM_SKILLED'] = '0';
		$vars['SKILL_ATTACHED'] = 'false';
		$vars['ITEM_SKILL_ID'] = "";
		$vars['ITEM_SKILL_CHANCE'] = "";
		$vars['ITEM_SKILL_EXTRA'] = "";
		$vars['ITEM_ATTRIBUTES'] = Array();
		
		//--- Vue par défaut : ITEMS
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
						I.FAMILY, I.TYPE, I.QUALITY, I.ATTACHMENT, I.SET, I.SKILLED,
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
					
					$vars['ITEM_SKILLED'] = ord($faData['SKILLED']);
					
					$vars['ITEM_ATTRIBUTES'] = load_attributes($lang_key, "ITEM", $id);
					
					
					if(ord($faData['ENABLED'])){
						$vars['ENABLED_SETTED'] = "selected";
					} else {
						$vars['DISABLED_SETTED'] = "selected";
					}
					
					
					/** > Chercher l'existence d'un SKILL : peut etre disable bien qu'existant **/
					$pSkillQuery = $PDO->prepare("
					SELECT
						S.ID, S.TAG, S.PROC, S.CHANCE, S.EXTRA
					
					FROM SKILLS AS S
					
					WHERE
						ITEM = :id
					");
					$pSkillQuery->bindValue(":id", $id);
					$pSkillQuery->execute();
					
					$faSkillData = Array(
						"TAG" => null,
						"PROC" => null
					);
					
					if($pSkillQuery->rowCount()){
						$faSkillData = $pSkillQuery->fetch(PDO::FETCH_ASSOC);
						
						$vars['SKILL_ATTACHED'] = 'true';
						$vars['ITEM_SKILL_ID'] = $faSkillData["ID"];
						$vars['ITEM_SKILL_CHANCE'] = $faSkillData["CHANCE"];
						$vars['ITEM_SKILL_EXTRA'] = intval($faSkillData["EXTRA"]) ?: "";
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
					
					// ITEM SKILL NAME
					foreach($vars['SKILLS'] as $key => $value){
						if($value["SKILL_TAG"] === $faSkillData["TAG"]){
							$vars["SKILLS"][$key]["SELECTED"] = "selected";
						} else {
							$vars["SKILLS"][$key]["SELECTED"] = "";
						}
					}
					
					// ITEM SKILL PROC
					foreach($vars['PROCS'] as $key => $value){
						if($value["PROC_TAG"] === $faSkillData["PROC"]){
							$vars["PROCS"][$key]["SELECTED"] = "selected";
						} else {
							$vars["PROCS"][$key]["SELECTED"] = "";
						}
					}
			} catch(Exception $e){
				trigger_error($e->getMessage(), E_USER_ERROR);
			}
		}
?>