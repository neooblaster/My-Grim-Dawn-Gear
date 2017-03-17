<?php
/** -------------------------------------------------------------------------------------------------------------------- ** 
/** -------------------------------------------------------------------------------------------------------------------- ** 
/** ---																																					--- **
/** --- 										------------------------------------------------											--- **
/** ---												{ L O A D S C R I P T S J S . P H P }													--- **
/** --- 										------------------------------------------------											--- **
/** ---																																					--- **
/** ---		AUTEUR 	: Nicolas DUPRE																											--- **
/** ---																																					--- **
/** ---		RELEASE	: 11.07.2015																												--- **
/** ---																																					--- **
/** ---		VERSION	: 3.1																															--- **
/** ---																																					--- **
/** ---																																					--- **
/** --- 														-----------------------------														--- **
/** --- 															{ C H A N G E L O G } 															--- **
/** --- 														-----------------------------														--- **	
/** ---																																					--- **
/** ---																																					--- **
/** ---		VERSION 3.1 : 11.07.2016 : NDU																									--- **
/** ---		------------------------------																									--- **
/** ---			- Ne pas tenir compte des fichiers commencant pas un point															--- **
/** ---			- Suppression de la fonction sortScandir qui ne servait à rien....												--- **
/** ---																																					--- **
/** ---		VERSION 3.0 : 31.03.2015																											--- **
/** ---		------------------------																											--- **
/** ---			- Remplacement du paramètre d'entrée $path pour permettre une utilisation par paramètre facultatif		--- **
/** ---																																					--- **
/** ---		VERSION 2.0 : 05.03.2015																											--- **
/** ---		------------------------																											--- **
/** ---			- Permet de spécifier la sortie de donnée : sortie direct, ou donnée rangées dans un array				--- **
/** ---																																					--- **
/** ---		VERSION 1.0 :																															--- **
/** ---		-------------																															--- **
/** ---			- Première release																												--- **
/** ---																																					--- **
/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **

	Input Params :
	--------------
		- $output	[String]	:	Format des données à l'issue de la fonction (soit echo, soit un tableau)
	
	Output Params :
	---------------
		- $Scripts		[Array]	:	Uniquement si $output = array;

/** -------------------------------------------------------------------------------------------------------------------- **
/** -------------------------------------------------------------------------------------------------------------------- **/	
function loadScriptsJS($output){
		/** > Analyser le nombre de paramètre envoyé à la fonction **/
		if(func_num_args() === 1){
			$path = Array('Scripts');
		} else {
			$path = func_get_args();	// Récupération ds argurments
			array_shift($path);			// Suppression du premier argument
		}
		
		
		/** > Analyser tout les dossiers demandé **/
		$Scripts = Array();
		
		foreach($path as $key => $value){
			/** > Scanner le dossier **/
			$scan = scandir($path[$key]);
			
			
			/** > Lecture du dossier **/
			foreach($scan as $fkey => $file){
				if(!is_dir($path[$key].'/'.$file) && !preg_match('#^\.#', $file)){
					/** > Traiter le nom du fichier **/
					$media = explode('_', $file);
					$media = explode('.', $media[1]);
					$media = $media[0];
					
					/** > Si output = true, alors emettre les sorties **/
					if($output){
						echo "<script type=\"text/javascript\" src=\"$path[$key]/$file\"></script>\r\t";
					}	
					
					/** > Ajouter les données au tableau **/
					$Scripts[] = Array(
						'SCRIPT_FILE' => $path[$key].'/'.$file,
						'SCRIPT_FILEMTIME' => filemtime($path[$key].'/'.$file)
					);
				}
			}
		}
		
		return $Scripts;
	}
?>


