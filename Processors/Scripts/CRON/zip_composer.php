<?php

	$list = fopen($_GET['list'], 'r');
	$zip_file_name = $_GET['lang'];

	$zipper = new ZipArchive();
	$zipper->open("Temps/composed-$zip_file_name.zip", ZipArchive::CREATE);

	while($buffer = fgets($list)){
		$file_name = substr($buffer, (strrpos($buffer, "/")+1));
		
		$buffer = str_replace("\n", "", $buffer);
		$buffer = str_replace("\t", "", $buffer);
		
		$output = fopen("Temps/$file_name", "w+");
		
		$cURL = curl_init($buffer);
		curl_setopt($cURL, CURLOPT_FILE, $output);
		curl_setopt($cURL, CURLOPT_TIMEOUT, 20);
		curl_exec($cURL);
		curl_close($cURL);
		
		fclose($output);
		
		$zipper->addFile("Temps/$file_name", $file_name);
	}

	fclose($list);

	$zipper->close();

	echo file_get_contents("Temps/composed-$zip_file_name.zip");
?>