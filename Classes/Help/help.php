<?php	
	function help($abs_path){	
		/*********************************************/
		/** 	- - - BEGIN INITIALISATION - - - 	**/	
		
		$method_declare = array();	
		$vars = array(
			'METHOD_DECLARE' => &$method_declare
		);
			
		/** 	- - -  END  INITIALISATION - - - 	**/
		/*********************************************/
		
		
		/*****************************************/
		/** 	- - - BEGIN PROCESSING - - - 	**/
		
			$scan_file = scandir($abs_path.'/Help/Files');
			
			for($i = 2; $i < count($scan_file); $i++){
				$tmp_file_res = fopen($abs_path.'/Help/Files/'.$scan_file[$i], 'r');
				
				$line = 1;
				$meth_declare_instance = array();
				$meth_declare_instance_params = array();
				
				while($buffer = fgets($tmp_file_res)){
					
					$meth_declare_instance['METH_ID'] = ($i - 2);
					
					if($line == 1){
						$meth_declare_instance['METH_MODIFIER'] = $buffer;
					} else if($line == 2){
						$meth_declare_instance['METH_TYPEOF'] = $buffer;
					} else if($line == 3){
						$meth_declare_instance['METH_NAME'] = $buffer;
					} else if($line == 4){
						
						$params = explode(';', $buffer);
						
						for($p = 0; $p < count($params); $p++){
						
							$params_instance = array();
							$params_data = explode(' ', $params[$p]);
							
							$params_instance['PARAM_TYPEOF'] = $params_data[0];
							$params_instance['PARAM_NAME'] = $params_data[1];
							
							$meth_declare_instance_params[] = $params_instance;
						}
					
						$meth_declare_instance['METH_PARAMS'] = $meth_declare_instance_params;
					} else {
						$buffer = nl2br($buffer);
						$meth_declare_instance['METH_DESC'] .= $buffer;
					}
					
					$line++;
				}
				$method_declare[] = $meth_declare_instance;
				fclose($tmp_file_res);
			}
		
			 //echo '<pre>';
			 //print_r($block_vars);
			 //echo '</pre>';

		/** 	- - -  END  PROCESSING - - - 	**/
		/*****************************************/
			
			
		/*************************************/
		/** 	- - - BEGIN OUTPUTS - - - 	**/
		
			 $moteur = new Template();
			 $moteur->set_template_file($abs_path.'/Help/help.tpl');
			 // $moteur->set_output_directories($abs_path.'/Help');
			 $moteur->set_output_name('help.html');
			 $moteur->set_vars($vars);
			 $moteur->set_utf8_write_treatment('decode');
			 $moteur->set_utf8_read_treatment('decode');
			 $moteur->render()->display();
			 //$moteur->cleansing_render_env(true);
			
		/** 	- - -  END  OUTPUTS - - - 	**/
		/*************************************/
	}

	
?>