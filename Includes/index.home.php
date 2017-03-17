<?php
	/** > Chargement de la liste des articles **/
	try {
		$qArticles = $PDO->query("SELECT TITLE, ARTICLE, CREATE_DATE, LAST_MODIFIED FROM ARTICLES ORDER BY ID DESC LIMIT 5");
	} catch(Exception $e){
		trigger_error($e->getMessage(), E_USER_ERROR);
	}

	while($faArticles = $qArticles->fetch(PDO::FETCH_ASSOC)){
		$vars['ARTICLES'][] = Array(
			"ARTICLE_TITLE" => $faArticles['TITLE'],
			"ARTICLE_ARTICLE" => $faArticles['ARTICLE'],
			"ARTICLE_CREATE_DATE" => timestamp_to_date($faArticles['CREATE_DATE']),
			"ARTICLE_CREATE_DATE_TS" => $faArticles['CREATE_DATE'],
			"ARTICLE_LAST_MODIFIED" => timestamp_to_date($faArticles['LAST_MODIFIED']),
			"ARTICLE_LAST_MODIFIED_TS" => $faArticles['LAST_MODIFIED']
		);
	}

?>