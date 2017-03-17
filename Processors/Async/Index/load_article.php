<?php

	/** Chargement des setups **/
	setup('/Setups', Array('application', 'pdo'), 'setup.$1.php');

	/** Déclaration des variables **/
	$id = $_POST['id'];

	/** Charger l'article **/
	try {
		$qArticle = $PDO->query("SELECT ID, TITLE, ARTICLE, CREATE_DATE, LAST_MODIFIED FROM ARTICLES WHERE ID = $id");
		
		$faArticle = $qArticle->fetch(PDO::FETCH_ASSOC);
	} catch (Exception $e) {
		//trigger_error($e->getMessage(), E_USER_ERROR);
	}

	$article = str_replace("\n", "", $faArticle['ARTICLE']);
	$article = str_replace("\r", "", $article);

	echo '{
		"ID": '.$id.',
		"TITLE": "'.$faArticle['TITLE'].'",
		"ARTICLE": "'.$article.'",
		"CREATE_DATE": "'.$faArticle['CREATE_DATE'].'"
	}';

?>