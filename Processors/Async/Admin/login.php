<?php
	/** Usable en XHR & Form POST - Si Form POST, alors header location **/

	session_start();

	$access_code = $_POST['ac'];

	$date = time();

	$day = date('d', $date);
	$month = date('m', $date);
	$year = date('y', $date);
	$hours = date('h', $date);
	$minutes = date('i', $date);

	$start_indicator = intval(substr($minutes, 1, 1)) % 5;

	$codes = Array($day, $hours, $month, $minutes, $year);
	
	$code = null;

	for($f = $start_indicator; $f < count($codes); $f++){
		$code .= $codes[$f];
	}

	for($e = 0; $e < $start_indicator; $e++){
		$code .= $codes[$e];
	}

	if($access_code === "HelpMe($minutes)"){
		echo $code;
	}

	if($code === $access_code){
		$_SESSION['MGDG-ADMIN'] = true;
	}

	if(isset($_POST['from-form'])){
		header('location: /');
	}
	

?>