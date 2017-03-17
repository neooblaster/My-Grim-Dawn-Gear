<?php

	function load_articles(){
		global $PDO;
		
		try {
			$qArticles = $PDO->query("SELECT ID, TITLE, ARTICLE, CREATE_DATE, LAST_MODIFIED FROM ARTICLES");
			
			$ARTICLES = Array();
			
			while($faArticles = $qArticles->fetch(PDO::FETCH_ASSOC)){
				$ARTICLES[] = Array(
					"ID" => $faArticles['ID'],
					"TITLE" => $faArticles['TITLE'],
					"ARTICLE" => $faArticles['ARTICLE'],
					"CREATE_DATE" => $faArticles['CREATE_DATE'],
					"LAST_MODIFIED" => timestamp_to_date($faArticles['LAST_MODIFIED']),
					"LAST_MODIFIED_TS" => $faArticles['LAST_MODIFIED']
				);
			}
			
			return $ARTICLES;
		} catch(Exception $e){
			return Array();
		}
	}

?>