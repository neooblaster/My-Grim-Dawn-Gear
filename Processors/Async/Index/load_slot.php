<?php

	setup('/Setups', Array('application', 'pdo'), 'setup.$1.php');
	$ID = intval($_POST['item_id']);

	if($ID > 0){
		try {
			$SYSLang = new SYSLang('../../../Languages');
			$lang = $SYSLang->get_lang();
			
			$query = "
				SELECT
					REL_FAMILY,
					REL_TYPE,
					REL_QUALITY,
					TAG_ITEM,
					NAME,
					WIDTH,
					HEIGHT,
					SLOT_ATTACHMENT
					
				FROM ITEMS AS Itm
				INNER JOIN ITEMS_TAGS_NAMES AS ItmTN
					ON TAG_ITEM = TAG_NAME
				
				WHERE 
						Itm.ID = $ID
					AND
						LANG = '$lang'
			";
			
			$qSlotItem = $PDO->query($query);
			$faSlotItem = $qSlotItem->fetch(PDO::FETCH_ASSOC);
			
			$moteur = new Template();
			$moteur->set_output_name('slot.json');
			$moteur->set_template_file('../../../Templates/Data/slot.tpl.json');
			$moteur->set_vars(
				Array(
					"REL_FAMILY" => $faSlotItem['REL_FAMILY'],
					"REL_TYPE" => $faSlotItem['REL_TYPE'],
					"REL_QUALITY" => $faSlotItem['REL_QUALITY'],
					"TAG_ITEM" => $faSlotItem['TAG_ITEM'],
					"NAME" => $faSlotItem['NAME'],
					"WIDTH" => $faSlotItem['WIDTH'],
					"HEIGHT" => $faSlotItem['HEIGHT'],
					"SLOT_ATTACHMENT" => $faSlotItem['SLOT_ATTACHMENT']
				)
			);
			$slot = $moteur->render()->get_render_content();
			
			$slot = str_replace("\n", "", $slot);
			$slot = str_replace("\r", "", $slot);
			$slot = str_replace("\t", "", $slot);
			
			echo $slot;
		} catch(Exception $e){
			echo $e->getMessage();
		}
	}


?>