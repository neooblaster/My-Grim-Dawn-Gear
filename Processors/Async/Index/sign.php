<?php

	setup('/Setups', Array("application", "pdo", "sessions"), 'setup.$1.php');

	require_once "../../Functions/Common/cryptpwd.php";

	try {
		$ID = $_POST['id'];
		$password = cryptpwd(cryptpwd($_POST['password'], CRYPT_KEY_1), CRYPT_KEY_2);
		
		$qAuth = $PDO->query("SELECT CODE FROM BUILDS WHERE ID = $ID AND PASSWORD = '$password'");
		
		if($qAuth->rowCount() > 0){
			$grant = "true";
			$_SESSION['BUILD_SIGNED'] = true;
		} else {
			$grant = "false";
		}
	} catch (Exception $e){
		
	}

	echo '{"allow": '.$grant.'}';

?>