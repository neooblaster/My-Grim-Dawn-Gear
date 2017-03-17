<?php

	function imagegradient($dst_img, $dst_x, $dst_x, $dst_w, $dst_h, $deg, $start_color){
		
	}


	/** Configuration de l'environnement **/
		// Paramètre de configuration
		setup('/Setups', Array('application', 'pdo'), 'setup.$1.php');

		// Chargement des couleurs
		json_to(file_get_contents('Configs/config.buildimg.json'));

		// Chargement des fonctions
		require_once "Processors/Functions/Common/imagebackground.php";



	/** Déclaration des variables **/
		// Déclaration de las liste des objets
			$items = Array();
			
		// Déclaration des qualités
			$qualities = Array('', 'COMMON', 'MAGIC', 'RARE', 'ELITE', 'LEGENDARY', 'RELIC', 'COMP', 'AUGMENT');
			
		// Association SLOT < > ITEM_ID
			$slot_item_id = Array();
			
		// Déclaration des PATHS
			// Panneau OBJETS
			$gear_bg_path = 'Images/Backgrounds/gear.png';
			
			
		// Déclaration des ressources
		$img;
			
			
		// Déclaration des propriété
		$img_width = 0;
		$img_height = 0;



	/** Initialisation **/
		// Lecture des images utilisé dans la composition
			// Panneau OBJETS
			$gear_bg_size = getimagesize($gear_bg_path);
			
			// Panneau STATS
			//$stats_bg_size = getimagesize($stats_bg_path);
			



	/** Traitement de base **/
		// Calcul des dimension de l'image
		$img_width = $gear_bg_size[0] + $stats_bg_size[0];
		$img_height = $gear_bg_size[1];
		
		// Creation de l'image
		$img = imagecreatetruecolor($img_width, $img_height);



	/** Incrusation des backgrounds **/
		// Incrustation de OBJETS
		$gear_bg_img = imagecreatefrompng($gear_bg_path);
		imagecopy($img, $gear_bg_img, BACKGROUND_GEAR_X, BACKGROUND_GEAR_Y, 0, 0, imagesx($gear_bg_img), imagesy($gear_bg_img));

		// Incrustation des STATS
		//$stats_bg_img = imagecreatefrompng($stats_bg_path);
		//imagecopy($img, $stats_bg_img, imagesx($gear_bg_img), BACKGROUND_GEAR_Y, 0, 0, imagesx($stats_bg_img), imagesy($stats_bg_img));
		
	

	/** Chargement du build **/
		// Récupérer le code demandé
		$build = $_GET['gear'];
		
		// Récupération des données
		$qBuild = $PDO->query("SELECT * FROM BUILDS WHERE CODE = '$build'");
		
		// S'il existe
		if($qBuild->rowCount() > 0){
			// Récupérer les ID des items
			$items_id = Array();
			
			while($faBuild = $qBuild->fetch(PDO::FETCH_ASSOC)){
				foreach($faBuild as $fkey => $fvalue){
					if(preg_match('#^SLOT#', $fkey)){
						$slot_item_id[$fkey] = $fvalue;
						$items_id[] = $fvalue;
						
						$items[$fvalue] = Array(
							"ID" => $fvalue,
							"DEFINED" => false
						);
					}
				}
			}
			
			// Récupérer les objets
			$qItems = $PDO->query("SELECT ID, REL_QUALITY, TAG_ITEM, WIDTH, HEIGHT, SLOT_ATTACHMENT FROM ITEMS WHERE ID IN (".implode(',', $items_id).")");
			
			while($faItems = $qItems->fetch(PDO::FETCH_ASSOC)){
				$items[$faItems['ID']]['QUALITY'] = $faItems['REL_QUALITY'];
				$items[$faItems['ID']]['TAG_NAME'] = $faItems['TAG_ITEM'];
				$items[$faItems['ID']]['WIDTH'] = $faItems['WIDTH'];
				$items[$faItems['ID']]['HEIGHT'] = $faItems['HEIGHT'];
				$items[$faItems['ID']]['ATTACHMENT'] = $faItems['SLOT_ATTACHMENT'];
				$items[$faItems['ID']]['DEFINED'] = true;
			}
			
			$qItems->rowCount();
		}


	/** Intégration des objets **/
	$items_img = Array();
	
	foreach($slot_item_id as $islot => $idata){
		if($idata){
			$tag_name = $items[$idata]['TAG_NAME'];
			
			$to_remove = "_".$items[$idata]['ATTACHMENT'];
			
			$slot = str_replace(strtoupper($to_remove), "", $islot);
			
			$item_img = imagecreatefrompng("Images/Items/$tag_name.png");
			
			//$item_img_size = getimagesize("Images/Items/$tag_name.png");
			
			
			/** déterminer les coordonée de destination **/
				// Déterminer si l'image rentre dans le slot 
				$slot_w = constant($slot."_W");
				$slot_h = constant($slot."_H");
			
				$item_w = imagesx($item_img);
				$item_h = imagesy($item_img);
			
				if($item_w > $slot_w || $item_h > $slot_h){
					if($item_w > $slot_w){
						$ratio = $slot_w / $item_w;
						$item_img = imagescale($item_img, $slot_w);
					} else {
						$ratio = $slot_h / $item_h;
						$required_w = floor($item_w * $ratio);
						$item_img = imagescale($item_img, $required_w);
					}
					
					$item_w *= $ratio;
					$item_h *= $ratio;
				}
			
				// Déterminer les coordonnée du centre du slot
				$dx = $dy = 0;
			
				$dx = floor(BACKGROUND_GEAR_X + constant($slot."_X") + (constant($slot."_W") / 2)); 
				$dy = floor(BACKGROUND_GEAR_Y + constant($slot."_Y") + (constant($slot."_H") / 2)); 
			
				// Imputer la moitier de l'objet pour un placement parfait
				$dx -= floor($item_w / 2);
				$dy -= floor($item_h / 2);
				
				
			/** Insertion des images **/
				// Background
				$color = constant('COLOR_'.$qualities[$items[$idata]['QUALITY']])."B2";
				imagebackground($img, BACKGROUND_GEAR_X + constant($slot."_X"), BACKGROUND_GEAR_Y + constant($slot."_Y"), $slot_w, $slot_h, $color);
				
				// Gradient
				
				// Item
				imagecopy($img, $item_img, $dx, $dy, 0, 0, $item_w, $item_h);
				imagedestroy($item_img);
		}
	}

	imagesavealpha($img, true);

	/** Diffusion de l'image **/
//exit;/**>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>**/
	header('content-type: image/png');
	imagepng($img);
	imagedestroy($img);

?>