<?php

	setup('/Setups', Array('application', 'pdo'), 'setup.$1.php');

	require_once "../../Functions/Common/cryptpwd.php";

	$base62 = Array(
		'0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
		'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j',
		'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't',
		'u', 'v', 'w', 'x', 'y', 'z',
		'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J',
		'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T',
		'U', 'V', 'W', 'X', 'Y', 'Z'
	);
		
	$unautomatized = Array('build_id', 'build_code', 'build_name', 'build_passcode', 'build_fold_stats', 'build_fold_inventory');
	$automatized = Array();

	$id = $_POST['build_id'];
	$name = $_POST['build_name'];

	if($_POST['build_passcode'] !== ''){
		$password = cryptpwd(cryptpwd($_POST['build_passcode'], CRYPT_KEY_1), CRYPT_KEY_2);
	} else {
		$password = '';
	}

	$fold_stats = $_POST['build_fold_stats'];
	$fold_inventory = $_POST['build_fold_inventory'];

	foreach($_POST as $key => $value){
		if(!in_array($key, $unautomatized)){
			$automatized['cols'][] = strtoupper($key);
			$automatized['values'][] = $value;
			$automatized['update'][] = strtoupper($key).'='.$value;
		}
	}

	if($_POST['build_id'] !== null){
		$operation = "UPDATE";
		$build_code = $_POST['build_code'];
		
		$query = "
			UPDATE BUILDS
			
			SET ".implode(', ', $automatized['update'])."
			
			WHERE
			
				ID = $id
		";
		
		$bound_tokens = Array();
	} else {
		$operation = "INSERT";
		
		try {
			$qBuilds = $PDO->query('SELECT CODE FROM BUILDS');
			$faBuilds = $qBuilds->fetchAll(PDO::FETCH_COLUMN);
			
			do {
				$build_code = null;
				
				for($i = 0; $i < 6; $i++){
					$build_code .= $base62[rand(0, 61)];
				}
			}while(in_array($build_code, $faBuilds));
		} catch(Exception $e) {
			die($e->getMessage());
		}
		
		
		$query = "
			INSERT INTO BUILDS
			
			(CODE,NAME,PASSWORD,".implode(',', $automatized['cols']).")
			
			VALUES ('$build_code','$name','$password',".implode(',', $automatized['values']).")
			
		";
		
		$bound_tokens = Array();
	}
	
	try {
		$pQuery = $PDO->prepare($query);
		$qQuery = $pQuery->execute($bound_tokens);
		
		if($_POST['build_id'] === null){
			$qBuildId = $PDO->query("SELECT ID FROM BUILDS WHERE CODE = '$build_code'");
			$faBuildId = $qBuildId->fetch(PDO::FETCH_ASSOC);
			
			$build_id = $faBuildId['ID'];
		}
		
		echo '{
			"OPERATION": "'.$operation.'",
			"BUILD_ID": "'.$build_id.'",
			"BUILD_CODE": "'.$build_code.'"
		}';
		
	} catch(Exeception $e) {
		die($e->getMessage());
	}
?>