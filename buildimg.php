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
/** ---		RELEASE	: xx.xx.2016																												--- **
/** ---																																					--- **
/** ---		VERSION	: 1.0																															--- **
/** ---																																					--- **
/** ---																																					--- **
/** --- 														-----------------------------														--- **
/** --- 															{ C H A N G E L O G } 															--- **
/** --- 														-----------------------------														--- **	
/** ---																																					--- **
/** ---																																					--- **
/** ---		VERSION 1.0 : xx.xx.2016																											--- **
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

	$gear_size;	// ARRAY		:: Dimmension du panneau "GEAR"

	$img_width;	// INTEGER	:: Largeur de l'image finale
	$img_height;// INTEGER	:: Hauteur de l'image finale

	$img;			// Resource	:: Image de travail qui sera rendue à la fin
	$gear_img;	// Resource :: Panneau gear en image php


/** > Initialisation des variables **/
	$qualities = Array("", "COMMON", "MAGIC", "RARE", "ELITE", "LEGENDARY", "RELIC", "COMP", "ENCHANT");
	$slots = Array();
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
				// Si la valeur est différente de 0
				if(intval($value)){
					// Récupérer la base du slot
					$slot_base_name = substr($col, 0, strrpos($col, "_"));
					$slot_spec = substr($col, strrpos($col, "_") + 1);
					
					if(!isset($slots[$slot_base_name])) $slots[$slot_base_name] = Array();
					
					$slots[$slot_base_name][$slot_spec] = $value;
					
					$items["LIST"][] = $value;
					$items["DATA"][$value] = Array();
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


/** > Intégration des objets **/
foreach($slots as $slot => $spec){
	// #1. Récupérer les paramètres du slots
	$slot_x = constant($slot."_X");
	$slot_y = constant($slot."_Y");
	$slot_w = constant($slot."_W");
	$slot_h = constant($slot."_H");
	
	
	// #2. Commencer par le dégradé correspondant à la qualité de l'objet
	//--- Récupération de l'ID quality
	$quality = $items["DATA"][$spec["ITEM"]]["QUALITY"];
	//--- Récupération de la couleur
	$quality_color = constant("COLOR_{$qualities[$quality]}")."B2"; // B2 = alpha value
	//--- Application du Background
	imagebackground($img, BACKGROUND_GEAR_X + $slot_x, BACKGROUND_GEAR_Y + $slot_y, $slot_w, $slot_h, $quality_color);
	
	
	// #3. Commencer par inséré l'objet
	//--- Récupération du tag (nom de fichier)
	$tag = $items["DATA"][$spec["ITE%"]]["TAG"];
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
	
	
	// #4. Insérer les attachements (comp & enchant)
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