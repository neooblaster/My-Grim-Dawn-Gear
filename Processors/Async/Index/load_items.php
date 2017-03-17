<?php

	// Setups 
	setup('/Setups', Array('application', 'pdo'), 'setup.$1.php');

	//
	//require_once '../../../Classes/class.JavaScriptPacker.php';


	$item_name = $_POST['item_name'];
	$quality_common = $_POST['quality_common'];
	$quality_magic = $_POST['quality_magic'];
	$quality_rare = $_POST['quality_rare'];
	$quality_elite = $_POST['quality_elite'];
	$quality_legendary = $_POST['quality_legendary'];
	$item_family = $_POST['item_family'];
	$item_type = $_POST['item_type'];

	$SYSLang = new SYSLang('../../../Languages');

	//$item_namme = ['item_name'] => 
	//$ = ['quality_magic'] => true
	//$ = ['quality_rare'] => true
	//$ = ['quality_elite'] => true
	//$ = ['quality_legendary'] => true
	//$ = ['item_family'] => 0
	//$ = ['item_type'] => 0

	//$PDO->query('SELECT * FROM ');

	/**
		// Donnée recu :
			- item_name
			- quality_magic
			- quality_rare
			- quality_elite
			- quality_legendary
			- item_family
			- item_type
		
		
		// Tables de donnée
			ITEMS : ID, REL_FAMILY, REL_TYPE, REL_QUALITY, TAG_ITEM
			ITEMS_TAGS_NAMES : ID, LANG, TAG_NAME, NAME, MD5
			ITEMS_TYPES : ID, REL_FAMILY_TYPE, TAG_IDENTIFIER
			ITEMS_FAMILIES : ID, FAMILY
			ITEMS_QUALITIES : ID, QUALITY
			ATTRIBUTS : ID
			ATTRIBUTS_TYPES : ID, REF, TAG_NAME, PRIMARY, SECONDARY
			ATTRIBURES_TAGS_NAMES : ID, NAME, LANG, TAG_NAME
			SKILLS : ID	
		
		
		// Filtre de recherche :
			- Un nom donnée dans la langue définie : item_name   ==> ITEMS_TAGS_NAMES.NAME
			- Qualité d'objet                      : quality_x   ==> ITEMS.REL_QUALITY
			- Type d'objet                         : item_type   ==> ITEMS.REL_TYPE
			- Famille d'objet                      : item_family ==> ITEMS.REL_FAMILY
			
			
		// Jointure : 
			- ITEMS + ITEMS_TAGS_NAME ON ITN.TAG_NAME = ITEMS.TAG_NAME, si NAME est donnée
		
		
	**/

	/** Composition de la requête de recherche des objets **/
		$query_select = null;
		$query_from = null;
		$query_where = null;
		$query_tokens = Array();
		$query = null;


		// Composition de la zone de selection
		$query_select  = "
			Itm.ID, 
			Itm.WIDTH, 
			Itm.HEIGHT, 
			ItmTN.NAME, 
			Itm.REL_QUALITY, 
			Itm.REL_TYPE, 
			Itm.TAG_ITEM,
			Itm.SLOT_ATTACHMENT
		";


		// Composition de la zone cible
		$query_from = "
			ITEMS AS Itm INNER JOIN ITEMS_TAGS_NAMES AS ItmTN 
			ON Itm.TAG_ITEM = ItmTN.TAG_NAME
		";


		// Composition de la clause SQL
			// Gérer les qualité
			$qualities = Array();
				// Common = 1
				if($quality_common === 'true') $qualities[] = 1;
				// Magic = 2
				if($quality_magic === 'true') $qualities[] = 2;
				// Rare = 3
				if($quality_rare === 'true') $qualities[] = 3;
				// Elite = 4
				if($quality_elite === 'true') $qualities[] = 4;
				// Legendary = 5
				if($quality_legendary === 'true') $qualities[] = 5;
				
				if(count($qualities) > 0){
					$query_where = 'WHERE Itm.REL_QUALITY IN ('.implode(', ', $qualities).')';
				}
				
			// Gérer la famille d'objet
			if(intval($item_family) > 0){
				if($query_where !== null){
					$query_where .= " AND Itm.REL_FAMILY = $item_family";
				} else {
					$query_where = "WHERE Itm.REL_FAMILY = $item_family";
				}
			}
				
			// Gérer la famille d'objet
			if(intval($item_type) > 0){
				if($query_where !== null){
					$query_where .= " AND Itm.REL_TYPE = $item_type";
				} else {
					$query_where = "WHERE Itm.REL_TYPE = $item_type";
				}
			}
			
			// Gérer le nom 
			if($item_name){
				$lang = $SYSLang->get_lang();
				
				if($query_where !== null){
					$query_where .= " AND ItmTN.LANG = :lang AND ItmTN.NAME RLIKE :item_name";
				} else {
					$query_where = "WHERE ItmTN.LANG = :lang AND ItmTN.NAME RLIKE :item_name";
				}
				
				$query_tokens = Array(
					':lang' => $lang,
					':item_name' => $item_name,
				);
			}	
			
			// Ne pas afficher les item désactivé 
			if($query_where !== null){
				$query_where .= " AND Itm.ENABLED = 1";
			} else {
				$query_where = "WHERE Itm.ENABLED = 1";
			}


		// Compilation de la query
		$query = sprintf("SELECT %s FROM %s %s", $query_select, $query_from, $query_where);


		// Execution de la query
		try {
			$qData = $PDO->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			
			$start_time = microtime();
			
			$qData->execute($query_tokens);
			
			$end_time = microtime();
		} catch(Exception $e){
			trigger_error($e->getMessage(), E_USER_ERROR);
		}


		// Parcourir la liste des objets retenu
		$ITEMS = Array();
		$first = true;

		while($faData = $qData->fetch(PDO::FETCH_ASSOC)){
			$ITEMS[] = Array(
				"COMMA" => (($first) ? '' : ','),
				"ID" => $faData['ID'],
				"TYPE" => $faData['REL_TYPE'],
				"NAME" => $faData['NAME'],
				"TAG_NAME" => $faData['TAG_ITEM'],
				"WIDTH" => $faData['WIDTH'],
				"HEIGHT" => $faData['HEIGHT'],
				"QUALITY" => $faData['REL_QUALITY'],
				"ATTACHMENT" => $faData['SLOT_ATTACHMENT']
			);
			
			$first = false;
		}

		$moteur = new Template();
		$moteur->set_template_file('../../../Templates/Data/items.tpl.json');
		$moteur->set_output_name('items.json');
		$moteur->set_vars(
			Array(
				"ITEMS" => $ITEMS
			)
		);
		$moteur->render();

		$items = $moteur->get_render_content();

		$items = str_replace("\n", "", $items);
		$items = str_replace("\r", "", $items);
		$items = str_replace("\t", "", $items);

		//$packer = new JavaScriptPacker($items, 'Normal', true, false);
		
		//echo $packer->pack();

		//echo base64_encode($items);

		echo $items;

?>