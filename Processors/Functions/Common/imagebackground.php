<?php
/** -------------------------------------------------------------------------------------------------------------------- ** 
/** -------------------------------------------------------------------------------------------------------------------- ** 
/** ---																																					--- **
/** --- 										------------------------------------------------											--- **
/** ---														{ imagebackground.php }																--- **
/** --- 										------------------------------------------------											--- **
/** ---																																					--- **
/** ---		AUTEUR			: Nicolas DUPRE																									--- **
/** ---																																					--- **
/** ---		RELEASE			: 26.08.2016																										--- **
/** ---																																					--- **
/** ---		FILE_VERSION	: 1.0 NDU																											--- **
/** ---																																					--- **
/** ---																																					--- **
/** --- 														---------------------------														--- **
/** ---															{ C H A N G E L O G }															--- **
/** --- 														---------------------------														--- **
/** ---																																					--- **
/** ---																																					--- **
/** ---		VERSION 1.0 : 26.08.2016 : NDU																									--- **
/** ---		------------------------------																									--- **
/** ---			- Première release																												--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **

	Requirements :
	--------------
	
		Aucun
		

	Input Params :
	--------------
	
		Resource $dst_img,		// Image sur laquelle s'applique le fond
		Int $dst_x,					// Coordonnée x (horizontal) de début
		Int $dst_y,					// Coordonnée y (vertical) de début
		Int $dst_w,					// Largeur en pixel du fond
		Int $dst_h,					// hauteur en pixel du fond
		String $color				// Couleur de fond à appliquer
		
		
		Format de couleur accepté
			Normal:
				Décimal:
					rgb(r, g, b)
					r, g, b
				
				Hexadécimal
					#rgb
					rgb
					
			Transparence Alpha
				Décimal
					rgba(r, g, b, a);
					r, g, b, a
					
				Hexadécimal
					#rgba
					rgba

	
	Output Params :
	---------------
	
		Boolean


	Objectif du script :
	---------------------
	
		L'objectif du script est d'appliquer un fond de couleur sur l'image spécifié en paramètre sur l'étendu défini par la couleur donnée
		
	
	Description fonctionnelle :
	----------------------------

/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **/
function imagebackground($dst_img, $dst_x, $dst_y, $dst_w, $dst_h, $color, $deg=0){
	$RGBPattern = "#^(rgb\()?([0-9]{1,2}|[1][0-9]{2}|[2][0-4][0-9]|[2][5][0-5])\s*?,\s*?([0-9]{1,2}|[1][0-9]{2}|[2][0-4][0-9]|[2][5][0-5])\s*?,\s*?([0-9]{1,2}|[1][0-9]{2}|[2][0-4][0-9]|[2][5][0-5])(\))?$#";
	$RGBAPattern = "#^(rgba\()?([0-9]{1,2}|[1][0-9]{2}|[2][0-4][0-9]|[2][5][0-5])\s*?,\s*?([0-9]{1,2}|[1][0-9]{2}|[2][0-4][0-9]|[2][5][0-5])\s*?,\s*?([0-9]{1,2}|[1][0-9]{2}|[2][0-4][0-9]|[2][5][0-5])\s?,\s?([0-1](\.[0-9]+)?)(\))?$#";
	
	$HEXAPattern = "*^(#)?(([a-fA-F0-9]{2}){3}|([a-zA-Z0-9]){3})$*";
	$HEXALPHAPattern = "*^(#)?(([a-fA-F0-9]{2}){4}|([a-zA-Z0-9]){4})$*";
	
	/** Déterminer le format de la couleur et convertions **/
		$allocatealpha = false;
		$red = 0;
		$green = 0;
		$blue = 0;
		$alpha = 1;
			
		// RGB
		if(preg_match($RGBPattern, $color)){
			$color = preg_replace("#^(rgb\()?#", "", $color); 
			$color = preg_replace("#(\))?$#", "", $color); 
			
			list($red, $green, $blue) = preg_split("#\s?,\s?#", $color);
		}
		
		// RGBA
		else if (preg_match($RGBAPattern, $color)){
			$color = preg_replace("#^(rgba\()?#", "", $color); 
			$color = preg_replace("#(\))?$#", "", $color); 
			
			list($red, $green, $blue, $alpha) = preg_split("#\s?,\s?#", $color);
			
			$allocatealpha = true;
		}
		// HEXA
		else if (preg_match($HEXAPattern, $color)){
			$color = preg_replace("*^(#)?*", "", $color);
			
			list($red, $green,$blue) = str_split($color, (strlen($color) / 3));
			
			$red = hexdec($red);
			$green = hexdec($green);
			$blue = hexdec($blue);
			
		}
		// HEXALPHA
		else if (preg_match($HEXALPHAPattern, $color)){
			$color = preg_replace("*^(#)?*", "", $color);
			
			list($red, $green, $blue, $alpha) = str_split($color, (strlen($color) / 4));
			
			$red = hexdec($red);
			$green = hexdec($green);
			$blue = hexdec($blue);
			
			$alpha = hexdec($alpha) / 255;
			
			$allocatealpha = true;
		}
		// WRONG INPUT
		else {
		}
		
		
	/** Création de l'image **/
	$background_img = imagecreatetruecolor($dst_w, $dst_h);
		
		
	/** Creation de l'allocation de couleur **/
		// RGBA - HEXALPHA
		if($allocatealpha){
			$bg = imagecolorallocatealpha($background_img, $red, $green, $blue, intval($alpha * 127));
		}
		
		// RGB - HEXA
		else {
			$bg = imagecolorallocate($background_img, $red, $green, $blue);
		}
		
		
	/** Colorisation **/
	imagefill($background_img, 0, 0, $bg);
		
	/** Intégration **/
	imagecopy($dst_img, $background_img, $dst_x, $dst_y, 0, 0, $dst_w, $dst_h);
	//imagesavealpha($background_img, true);
	imagedestroy($background_img);
	
	return true;
}
?>