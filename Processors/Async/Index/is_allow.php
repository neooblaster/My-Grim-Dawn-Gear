<?php

	setup('/Setups', Array("sessions"), 'setup.$1.php');

	if($_SESSION['BUILD_PROTECTED']){
		if($_SESSION['BUILD_SIGNED']){
			$grant = "true";
		} else {
			$grant =  "false";
		}
	} else {
		$grant = "true";
	}

	echo '{"allow": '.$grant.'}';

?>