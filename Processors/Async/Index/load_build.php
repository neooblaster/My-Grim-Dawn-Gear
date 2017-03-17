<?php

	setup('/Setups', Array('application', 'pdo'), 'setup.$1.php');

	try {
		$ID = $_POST['build_id'];
		$qBuild = $PDO->query("SELECT * FROM BUILDS WHERE ID = $ID");
		$faBuild = $qBuild->fetch(PDO::FETCH_ASSOC);
		
		$SLOTS = Array();
		$first = true;
		
		foreach($faBuild as $key => $value){
			if(preg_match('#^SLOT#', $key)){
				$SLOTS[] = Array(
					'COMMA' => (!$first) ? ',' : '',
 					'SLOT' => $key,
					'ITEM' => $value
				);
				
				$first = false;
			}
		}
		
		$moteur = new Template();
		$moteur->set_output_name('build.json');
		$moteur->set_template_file('../../../Templates/Data/build.tpl.json');
		$moteur->set_vars(
			Array(
				"SLOTS" => $SLOTS
			)
		);
		$slots = $moteur->render()->get_render_content();
		
		$slots = str_replace("\n", "", $slots);
		$slots = str_replace("\r", "", $slots);
		$slots = str_replace("\t", "", $slots);
		
		echo $slots;
		
	} catch (Exception $e){
	}

?>