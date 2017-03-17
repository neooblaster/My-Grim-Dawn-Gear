<?php

	/** Chargement des setups **/
	setup('/Setups', Array('application', 'pdo'), 'setup.$1.php');


	pprint($_POST);


	$save_process = $_POST['article_save_process'];
	$id = $_POST['article_id'];
	$title = $_POST['article_title'];
	$article = $_POST['article_article'];
	$date = time();
	

	switch(strtolower($save_process)){
		case "create":
			$query = "INSERT INTO ARTICLES (TITLE, ARTICLE, CREATE_DATE) VALUES('$title', '$article', $date)";
		
			try {
				$PDO->query($query);
			} catch(Exception $e){
				die($e->getMessage().$query);
			}
		break;
		
		case "update":
			$query = "UPDATE ARTICLES SET TITLE = '$title', ARTICLE = '$article', LAST_MODIFIED = $date WHERE ID = $id";
		
			try {
				$PDO->query($query);
			} catch(Exception $e){
				
			}
		break;
		
		case "delete":
			echo $query = "DELETE FROM ARTICLES WHERE ID = $id";
			
			try {
				$PDO->query($query);
			} catch(Exception $e){
				
			}
		break;
	}


?>