<?php
/** -------------------------------------------------------------------------------------------------------------------- ** 
/** -------------------------------------------------------------------------------------------------------------------- ** 
/** ---																																					--- **
/** --- 											-----------------------------------------------											--- **
/** ---																{ build_img.php }																--- **
/** --- 											-----------------------------------------------											--- **
/** ---																																					--- **
/** ---		AUTEUR 	: Nicolas DUPRE																											--- **
/** ---																																					--- **
/** ---		RELEASE	: 06.04.2016																												--- **
/** ---																																					--- **
/** ---		VERSION	: 1.0																															--- **
/** ---																																					--- **
/** ---																																					--- **
/** --- 														-----------------------------														--- **
/** --- 															{ C H A N G E L O G } 															--- **
/** --- 														-----------------------------														--- **	
/** ---																																					--- **
/** ---																																					--- **
/** ---		VERSION 1.0 : 06.04.2016																											--- **
/** ---		------------------------																											--- **
/** ---			- Première release																												--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **

	Objectif du script :
	---------------------
	
	Description fonctionnelle :
	----------------------------
	
		imagescale(img_src, new_width [,new_height]);
	
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** ---																																					--- **
/** ---													PHASE 1 - INITIALISATION DU SCRIPT													--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** > Chargement des Paramètres **/
	setup('/Setups', Array('application', 'img', 'pdo'), 'setup.$1.php');

/** > Ouverture des SESSIONS Globales **/
/** > Chargement des Classes **/
/** > Chargement des Configs **/
/** > Chargement des Fonctions **/
	require_once "Processors/Functions/Common/imagebackground.php";

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
	$build;		// STRING	:: Code du build
	$pBuild;		// PDO		:: Instance PDO contenant les donnée du build
	$qualities;	// ARRAY		:: Liste des différente qualitée
	$slots;		// ARRAY		:: Donnée stockée concernant les slots
	$items;		// ARRAY		:: Liste des objets à charger/chargé
	$empties;	// ARRAY		:: Liste des slots avec l'état vide ou non

	$gear_size;	// ARRAY		:: Dimmension du panneau "GEAR"

	$img_width;	// INTEGER	:: Largeur de l'image finale
	$img_height;// INTEGER	:: Hauteur de l'image finale

	$img;			// Resource	:: Image de travail qui sera rendue à la fin
	$gear_img;	// Resource :: Panneau gear en image php


/** > Initialisation des variables **/
	$qualities = Array("", "COMMON", "MAGIC", "RARE", "ELITE", "LEGENDARY", "RELIC", "COMP", "ENCHANT");
	$slots = Array();
	$empties = Array();
	$items = Array(
		"LIST" => Array(),
		"DATA" => Array()
	);

	$build = $_GET["gear"];

/** > Déclaration et Intialisation des variables pour le moteur (référence) **/


/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** ---																																					--- **
/** ---									PHASE 4 - EXECUTION DU SCRIPT DE TRAITEMENT DE DONNEES										--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **/
/** > Charger les données du build (si n'existe pas, pas de donnée) **/
	$pBuild = $PDO->prepare("SELECT * FROM BUILDS WHERE CODE = :code");
	$pBuild->execute(Array(":code" => $build));

	// Si un build répond à ce code
	if($pBuild->rowCount()){
		// Récupération des informations des slots
		$faBuild = $pBuild->fetch(PDO::FETCH_ASSOC);
		
		foreach($faBuild as $col => $value){
			// S'il s'agit d'un slot SLOT_$BASE_$SPEC
			if(preg_match("#^SLOT_#", $col)){
				// Récupérer la base du slot
				$slot_base_name = substr($col, 0, strrpos($col, "_"));
				$slot_spec = substr($col, strrpos($col, "_") + 1);
				
				// Engistré le slots dans les empties
				if(!array_key_exists($slot_base_name, $empties)) $empties[$slot_base_name] = true;
				
				// Si la valeur est différente de 0
				if(intval($value)){
					if(!isset($slots[$slot_base_name])) $slots[$slot_base_name] = Array();
					
					$slots[$slot_base_name][$slot_spec] = $value;
					
					$items["LIST"][] = $value;
					$items["DATA"][$value] = Array();
					
					// Si c'est la spec ITEM alors le slot n'est pas vide
					if($slot_spec === "ITEM"){
						$empties[$slot_base_name] = false;
					}
				}
			}
		}
		
		// Charger les données des objet qui constitues le build
		$pItems = $PDO->prepare("SELECT ID, QUALITY, TAG, WIDTH, HEIGHT FROM ITEMS WHERE ID IN (".implode(",", $items["LIST"]).")");
		$pItems->execute();
		
		while($faItems = $pItems->fetch(PDO::FETCH_ASSOC)){
			$ID = $faItems["ID"];
			
			$items["DATA"][$ID]["QUALITY"] = $faItems["QUALITY"];
			$items["DATA"][$ID]["TAG"] = $faItems["TAG"];
			$items["DATA"][$ID]["WIDTH"] = $faItems["WIDTH"];
			$items["DATA"][$ID]["HEIGHT"] = $faItems["HEIGHT"];
		}
	}



/** > Calculs préliminaire pour la composition de l'image **/
	// Récupération des dimensions des images
	$gear_size = getimagesize(BACKGROUND_GEAR_PATH);
	//$stats_size = getimagesize(BACKGROUND_STATS_PATH);

	// Largeur de l'image finale
	$img_width = $gear_size[0];

	// Hauteur de l'image finale
	$img_height = $gear_size[1];

	

//pprints($slots, $items, $gear_size);
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** ---																																					--- **
/** ---											PHASE 5 - GENERATION DES DONNEES DE SORTIE												--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **/
/** > Composition de l'image **/
$img = imagecreatetruecolor($img_width, $img_height);


/** > Insertion des bagrounds **/
// Récupération au format Resource
$gear_img = imagecreatefrompng(BACKGROUND_GEAR_PATH);

// Insertion
imagecopy($img, $gear_img, BACKGROUND_GEAR_X, BACKGROUND_GEAR_X, 0, 0, imagesx($gear_img), imagesy($gear_img));



/** > Remplissage des slots VIDE **/
foreach($empties as $slot => $empty){
	if(!$empty) continue;
	
	$slot_x = constant($slot."_X");
	$slot_y = constant($slot."_Y");
	
	$image_file = "Images/Backgrounds/$slot.png";
	
	// TEMPORAIRE : ignoré les _2
	if(preg_match("#_2$#i", $slot)) continue;
	
	//--- Si l'image n'est pas trouvé, alors on passe
	if(!file_exists($image_file)) continue;
	
	//--- Créer une copie du slot pour intégration
	$slot_image = imagecreatefrompng($image_file);
	
	//--- Intégrationde l'image
	imagecopy($img, $slot_image, $slot_x, $slot_y, 0, 0, imagesx($slot_image), imagesy($slot_image));
	
	//--- Destruction de la ressource
	imagedestroy($slot_image);
}



/** > Intégration des objets **/
foreach($slots as $slot => $specs){
	//pprints("ITEMS:", $items, "SLOTS:", $slots, "SLOT:", $slot, "SPEC:", $spec);
	
	// #1. Récupérer les paramètres du slots
	$slot_x = constant($slot."_X");
	$slot_y = constant($slot."_Y");
	$slot_w = constant($slot."_W");
	$slot_h = constant($slot."_H");
	
	
	
	// #2. Commencer par le dégradé correspondant à la qualité de l'objet
	//--- Récupération de l'ID quality
	$quality = $items["DATA"][$specs["ITEM"]]["QUALITY"];
	//--- Récupération de la couleur
	$quality_color = constant("COLOR_{$qualities[$quality]}")."B2"; // B2 = alpha value
	//--- Application du Background
	imagebackground($img, BACKGROUND_GEAR_X + $slot_x, BACKGROUND_GEAR_Y + $slot_y, $slot_w, $slot_h, $quality_color);
	
	
	// #3. Commencer par inséré l'objet
	//--- Récupération du tag (nom de fichier)
	$tag = $items["DATA"][$specs["ITEM"]]["TAG"];
	
	//--- Sécurisation si le fichier n'existe pas
	$item_file = (file_exists("Images/Items/$tag.png")) ? "Images/Items/$tag.png" : "Images/Items/NotFound.png";
	
	//--- "Imagisation"
	$item_img = imagecreatefrompng($item_file);
	
	//--- Déterminer les coordonnée d'emplacement de l'image (centre)
	$item_w = imagesx($item_img); // Largeur
	$item_h = imagesy($item_img); // Hauteur
	
	//--- Faut-il redimensionner l'image ?
	if($item_w > $slot_w || $item_h > $slot_h){
		if($item_w > $slot_w){
			$ratio = $slot_w / $item_w;
			$item_img = imagescale($item_img, $slot_w);
		} else {
			$ratio = $slot_h / $slot_w;
			$item_img = imagescale($item_img, (floor($item_w * $ratio)));
		}
		
		// Nouvelles dimensions 
		$item_w *= $ratio;
		$item_h *= $ratio;
	}
	
	//--- Déterminer le centre du slot : Position du slot + la moitier de sa largeur/hauteur
	$dx = floor(BACKGROUND_GEAR_X + $slot_x) + ($slot_w / 2);
	$dy = floor(BACKGROUND_GEAR_Y + $slot_y) + ($slot_h / 2);
	
	//--- Position de l'image : centre du slot - la moitié de la largeur/hauteur de l'image
	$dx -= floor($item_w / 2);
	$dy -= floor($item_h / 2);
	
	//--- Inséré l'image
	imagecopy($img, $item_img, $dx, $dy, 0, 0, $item_w, $item_h);
	
	//--- Détruire l'objet
	imagedestroy($item_img);
	
	
	// #4. Insérer le composant (si présent)
	if($specs["COMP"]){
		//--- Récupération du tag (nom de fichier)
		$comp = $items["DATA"][$specs["COMP"]]["TAG"];
		
		//--- Sécurisation si le fichier n'existe pas
		$comp_file = (file_exists("Images/Items/$comp.png")) ? "Images/Items/$comp.png" : "Images/Items/NotFound.png";
		
		//--- "Imagisation"
		$comp_img = imagecreatefrompng($comp_file);
		$comp_img = imagescale($comp_img, (imagesx($comp_img) * 0.75));
		
		//--- Déterminer les coordonnée d'emplacement de l'image (en bas à droit)
		$comp_x = 0;
		$comp_y = 0;
		//───┐ Départ via l'emplacement du slot
		$comp_x += $slot_x;
		$comp_y += $slot_y;
		//───┐ Aller en bas à droite
		$comp_x += $slot_w;
		$comp_y += $slot_h;
		//───┐ Décaller vers la gauche de la largeur de l'image du composant et autant en hauteur
		$comp_x -= imagesx($comp_img);
		$comp_y -= imagesy($comp_img);
		
		
		//--- Inséré l'image
		imagecopy($img, $comp_img, $comp_x, $comp_y, 0, 0, imagesx($comp_img), imagesy($comp_img));
		
		//--- Détruire l'objet
		imagedestroy($comp_img);
	}
	
	
	// #5. Insérer l'enchant (si présent)
	if($specs["ENCHANT"]){
		//--- Récupération du tag (nom de fichier)
		$enchant = $items["DATA"][$specs["ENCHANT"]]["TAG"];
		
		//--- Sécurisation si le fichier n'existe pas
		$enchant_file = (file_exists("Images/Items/$enchant.png")) ? "Images/Items/$enchant.png" : "Images/Items/NotFound.png";
		
		//--- "Imagisation"
		$enchant_img = imagecreatefrompng($enchant_file);
		$enchant_img = imagescale($enchant_img, (imagesx($enchant_img) * 0.75));
		
		//--- Déterminer les coordonnée d'emplacement de l'image (en bas à droit)
		$enchant_x = 0;
		$enchant_y = 0;
		//───┐ Départ via l'emplacement du slot
		$enchant_x += $slot_x;
		$enchant_y += $slot_y;
		//───┐ Aller en bas à gauche
		$enchant_x += 0;
		$enchant_y += $slot_h;
		//───┐ Décaller vers le haut de la hauteur de l'image de l'enchant
		$enchant_x -= 0;
		$enchant_y -= imagesy($enchant_img);
		
		
		//--- Inséré l'image
		imagecopy($img, $enchant_img, $enchant_x, $enchant_y, 0, 0, imagesx($enchant_img), imagesy($enchant_img));
		
		//--- Détruire l'objet
		imagedestroy($enchant_img);
	}
}




/** > Finalisation de l'image **/
imagesavealpha($img, true);



/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** ---																																					--- **
/** ---												PHASE 6 - AFFICHER LES SORTIES GENEREE													--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **/
/** > Envois des entête HTTP **/
	header('content-type: image/png');

/** > Affichage de l'image **/
	imagepng($img);

/** > Supprimer l'image générée **/
	imagedestroy($img);

?>