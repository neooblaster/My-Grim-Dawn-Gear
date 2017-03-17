<?php

	setup('/Setups', Array('application', 'pdo', 'session'), 'setup.$1.php');

	$item_id = $_POST['ID'];
	$item_property = $_POST['property'];
	$item_property_value = $_POST['property_value'];

	$query = "
		UPDATE
			ITEMS
			
		SET $item_property = $item_property_value
		
		WHERE
				ID = $item_id
	";

	try {
		$PDO->query($query);
	} catch(Exception $e){
		trigger_error($e->getMessage().$query, E_USER_ERROR);
	}

?>