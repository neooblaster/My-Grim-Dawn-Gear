<?php

	/**
	Chargement des fonctions
		json_too
		
		
	Chargement des configuration :
		setup.document_root
		json_to(config.application.json)
		setup.pdo
		
		
	Initialisation des variables
	
	
	Chargement des URLS des pack de langue GD
		xx-XX:http://urls
			=> Lang : xx-XX
			=> URL : http://urls
	
	
	Chargement des Identifiers pour lister les objets
		Query SQL =>
			Array($identifier) => Array(data())
			
			+
			
			Regexp : tag(identifier)[A]{1}[0-9]{3}(name)?
			
	
	
	Chargment des objets connu
		Query SQL =>
			Array(tag_name) => Array(data(id, Rel_*))
	
	
	Parcourir le premier pack à la recherche des tag Item pour les enregistrer
		> Découpage selon un pattern determiné à l'aide des identifier
		> Si l'objet ne semble pas en base, alors l'ajouter
		> Break
		
		
	Récupérer l'ensemble des ITEM CONNU (a jour)
		Associer le tagname à l'id
		
		
	Récupérer l'ensemble des ITEM_TAG_NAME connu
		
		
	Parcourir l'ensemble des packs de langue
		> Récupérer le texte correspondant au tag name
		> Comparer le MD5 si connu,
		> Si MD5 différent on le met a jour
		> Si pas connu on l'ajoute
	
	
	**/


	// Call pour php -f et cron **/
	require_once '../../../../Prepends/Functions/json_to.php';
	require_once '../../../../Prepends/Setups/setup.const.document_root.php';

	// Config
	//setup('Setups', Array('application', 'pdo'), "setup.$1.php");

	json_to(file_get_contents('../../../Configs/config.application.json'));

	require_once '../../../Setups/setup.pdo.php';

	$urls = Array();
	$tags_files = Array('tags_items.txt', 'tags_ui.txt');
	$tags_identifier = Array();
	$tags_identifier_pattern = null;
	$tags_items_known = Array();
	$tags_items_names = Array();

	$usleep = 30000;


	/** Charger les URLS **/
	$config_file = fopen('../../../Configs/config.lang-urls.txt', 'r');

	while($buffer = fgets($config_file)){
		if(!preg_match('#^\##', $buffer)){
			$lang = substr($buffer, 0, 5);
			$url = substr($buffer, 6);
			
			$urls[$lang] = $url;
		}
	}

	fclose($config_file);
	
	
	/** Chargement des identifiers **/
	$qIdentifier = $PDO->query("SELECT ID, REL_FAMILY, TYPE, TAG_IDENTIFIER FROM ITEMS_TYPES");

	while($faIdentifier = $qIdentifier->fetch(PDO::FETCH_ASSOC)){
		$tags_identifier[$faIdentifier['TAG_IDENTIFIER']] = Array(
			"ID" => $faIdentifier['ID'],
			"REL_FAMILY" => $faIdentifier['REL_FAMILY'],
			"TYPE" => $faIdentifier['TYPE']
		);
			
		$identifier = $faIdentifier['TAG_IDENTIFIER'];
			
		$pattern = "(^tag".$identifier."[A-Z][0-9]{3}((Name)?)(?!_?Desc))";
		
		$tags_identifier_pattern = ($tags_identifier_pattern === null) ? $pattern : ($tags_identifier_pattern."|$pattern");
	};
	
	/** Chargement des objets connu **/
	$qItems = $PDO->query("SELECT ID, REL_FAMILY, REL_TYPE, REL_QUALITY, TAG_ITEM FROM ITEMS");

	while($faItems = $qItems->fetch(PDO::FETCH_ASSOC)){
		$tags_items_known[$faItems['TAG_ITEM']] = Array(
			"ID" => $faItems['ID'],
			"REL_FAMILY" => $faItems['REL_FAMILY'],
			"REL_TYPE" => $faItems['REL_TYPE'],
			"REL_QUALITY" => $faItems['REL_QUALITY']
		);
	}

	
	/** Découverte des Objets **/
	foreach($urls as $lang => $url){
		/** Téléchargement du fichier **/
		$file = fopen("Temps/$lang.zip", 'w+');
		
		$url = str_replace("\n", "", $url);
		$url = str_replace("\r", "", $url);
		$url = str_replace("\t", "", $url);
		
		$cURL = curl_init($url);
		
		//curl_setopt($cURL, CURLOPT_URL, $url);
		curl_setopt($cURL, CURLOPT_FILE, $file);
 		//curl_setopt($cURL, CURLOPT_HEADER, 0); 
   	//curl_setopt($cURL, CURLOPT_FAILONERROR,1);
   	//curl_setopt($cURL, CURLOPT_FOLLOWLOCATION,1);
   	//curl_setopt($cURL, CURLOPT_RETURNTRANSFER,1);
 		curl_setopt($cURL, CURLOPT_TIMEOUT, 60); 
 		//curl_setopt($cURL, CURLOPT_BINARYTRANSFER, true); 
		curl_exec($cURL);
		curl_close($cURL);
		
		
		/** Lecture du Zip **/
		$reader = new ZipArchive();
		$reader->open("Temps/$lang.zip", ZipArchive::CHECKCONS);
		$reader->extractTo("Temps/$lang", $tags_files);
		$reader->close();
		
		
		/** Lecture des Fichiers **/
		foreach($tags_files as $key => $file){
			$tag_file = fopen("Temps/$lang/$file", 'r');
			
			while($buffer = fgets($tag_file)){
				/** Est un tag si la ligne commence par tag **/
				if(preg_match("#^tag#", $buffer)){
					if(preg_match("#$tags_identifier_pattern#", $buffer)){
						$eqpos = strpos($buffer, "=");
						$tag_name = substr($buffer, 0, $eqpos);
						
						/** Contrôler son existance **/
						if(!array_key_exists($tag_name, $tags_items_known)){
							/** Identifier le type d'objet **/
							$rel_identifier = preg_replace("#tag#", "", $tag_name);
							$rel_identifier = preg_replace("#[A-Z][0-9]{3}((Name|[A-Z])?)#", "", $rel_identifier);
							
							$rel_type = $tags_identifier[$rel_identifier]['ID'];
							$rel_family = $tags_identifier[$rel_identifier]['REL_FAMILY'];
							
							/** Ajouter en DB **/
							$query = "INSERT INTO ITEMS (REL_FAMILY, REL_TYPE, TAG_ITEM) VALUES($rel_family, $rel_type, '$tag_name')";
							
							try {
								$PDO->query($query);
								echo "Executing : $query".PHP_EOL;
							} catch(Exception $e){
								die($e->getMessage());
							}
						} else {
							echo "$tag_name already exist in database\n";
						}
						//echo $buffer;
						
						usleep($usleep);
					}
				}
			}
		}
		
		break;
	}


	/** Re-récupération des objets connu **/
	$tags_items_known = Array();
	
	$qItems = $PDO->query("SELECT ID, REL_FAMILY, REL_TYPE, REL_QUALITY, TAG_ITEM FROM ITEMS");

	while($faItems = $qItems->fetch(PDO::FETCH_ASSOC)){
		$tags_items_known[$faItems['TAG_ITEM']] = Array(
			"ID" => $faItems['ID'],
			"REL_FAMILY" => $faItems['REL_FAMILY'],
			"REL_TYPE" => $faItems['REL_TYPE'],
			"REL_QUALITY" => $faItems['REL_QUALITY']
		);
	}


	/** Récupération des ITEM_TAG_NAME **/
	$qItemsTagNames = $PDO->query("SELECT ID, LANG, TAG_NAME, NAME, MD5 FROM ITEMS_TAGS_NAMES");

	while($faItemsTagNames = $qItemsTagNames->fetch(PDO::FETCH_ASSOC)){
		$tags_items_names[$faItemsTagNames['LANG']][$faItemsTagNames['TAG_NAME']] = Array(
			"ID" => $faItemsTagNames['ID'],
			"NAME" => $faItemsTagNames['NAME'],
			"MD5" => $faItemsTagNames['MD5']
		);
	}



	
	/** Découverte des Objets **/
	foreach($urls as $lang => $url){
		/** Téléchargement du fichier **/
		$file = fopen("Temps/$lang.zip", 'w+');
		
		$url = str_replace("\n", "", $url);
		$url = str_replace("\r", "", $url);
		$url = str_replace("\t", "", $url);
		
		$cURL = curl_init($url);
		
		curl_setopt($cURL, CURLOPT_FILE, $file);
		curl_exec($cURL);
		curl_close($cURL);
		
		/** Lecture du Zip **/
		$reader = new ZipArchive();
		$reader->open("Temps/$lang.zip");
		$reader->extractTo("Temps/$lang", $tags_files);
		$reader->close();
		
		/** Lecture des Fichiers **/
		foreach($tags_files as $key => $file){
			$tag_file = fopen("Temps/$lang/$file", 'r');
			
			while($buffer = fgets($tag_file)){
				/** Est un tag si la ligne commence par tag **/
				if(preg_match("#^tag#", $buffer)){
					if(preg_match("#$tags_identifier_pattern#", $buffer)){
						$eqpos = strpos($buffer, "=");
						$tag_name = substr($buffer, 0, $eqpos);
						$tag_value = substr($buffer, ($eqpos+1));
						$tag_md5 = md5($tag_value);
						$query = null;
						$operation = null;
						$bound_tokens = Array();
						
						$tag_value = str_replace("\n", '', $tag_value);
						$tag_value = str_replace("\r", '', $tag_value);
						$tag_value = str_replace("\t", '', $tag_value);
						
						$tag_value = str_replace("[ms]", '', $tag_value);
						$tag_value = str_replace("[mp]", '', $tag_value);
						$tag_value = str_replace("[fs]", '', $tag_value);
						$tag_value = str_replace("[fp]", '', $tag_value);
						
						$tag_value = str_replace("{^K}", '', $tag_value);
						$tag_value = str_replace("{^k}", '', $tag_value);
						$tag_value = str_replace("^k", '', $tag_value);
						
						//echo $tag_name.' >>> '.$tag_md5.$tag_value;
						//echo PHP_EOL;
						
						if($tag_value !== ''){
							/** Si la langue est connu il y à des donneés**/
							if(array_key_exists($lang, $tags_items_names)){
								/** Si le tag existe pour cette lang, controler le MD5 **/
								if(array_key_exists($tag_name, $tags_items_names[$lang])){
									if($tags_items_names[$lang][$tag_name]['MD5'] !== $tag_md5){
										$id = $tags_items_names[$lang][$tag_name]['ID'];
										//$query = "UPDATE ITEMS_TAGS_NAMES SET NAME = '$tag_value', MD5 = '$tag_md5' WHERE ID = $id";
										$query = "UPDATE ITEMS_TAGS_NAMES SET NAME = :tag_value, MD5 = :tag_md5 WHERE ID = :id";
										$bound_tokens = Array(
											':id' => $id,
											':tag_value' => $tag_value,
											':tag_md5' => $tag_md5
										);
										$operation = "Update SQL data";
									}
								} 
								/** Il faut l'ajouter **/
								else {
									//$query = "INSERT INTO ITEMS_TAGS_NAMES (LANG, TAG_NAME, NAME, MD5) VALUES('$lang', '$tag_name', '$tag_value', '$tag_md5')";
									$query = "INSERT INTO ITEMS_TAGS_NAMES (LANG, TAG_NAME, NAME, MD5) VALUES(:lang, :tag_name, :tag_value, :tag_md5)";
									$bound_tokens = Array(
										':lang' => $lang,
										':tag_name' => $tag_name,
										':tag_value' => $tag_value,
										':tag_md5' => $tag_md5
									);
									$operation = "Insert SQL data on existing language";
								}
							}
							/** Sinon il n'y à que des ajout **/
							else {
								//$query = "INSERT INTO ITEMS_TAGS_NAMES (LANG, TAG_NAME, NAME, MD5) VALUES('$lang', '$tag_name', '$tag_value', '$tag_md5')";
								$query = "INSERT INTO ITEMS_TAGS_NAMES (LANG, TAG_NAME, NAME, MD5) VALUES(:lang, :tag_name, :tag_value, :tag_md5)";
								$bound_tokens = Array(
									':lang' => $lang,
									':tag_name' => $tag_name,
									':tag_value' => $tag_value,
									':tag_md5' => $tag_md5
								);
								$operation = "Insert SQL data for new language";
							}
						}
						
						if($query !== null){
							try {
								echo sprintf("%s - Operation SQL : %s\n", $tag_name, $operation);
								$statement = $PDO->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
								$statement->execute($bound_tokens);
							} catch(Exception $e) {
								echo sprintf("%s - Operation failed : %s --- %s\n", $tag_name, $e->getMessage(), $query);
								print_r($bound_tokens);
							}
						} else {
							echo sprintf("%s - Nothing to do.\n", $tag_name);
						}
						
						usleep($usleep);
					}
				}
			}
		}
	}

?>