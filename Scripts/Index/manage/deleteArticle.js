function deleteArticle(){
	var article_id = document.querySelector("#article_id").value;
	
	var xQuery = new xhrQuery();
		xQuery.target('/XHR/Index/save_article.php');
		xQuery.values('article_save_process=delete', 'article_id='+article_id);
		xQuery.callbacks(
			function(d){
				document.location.reload();
			}
		);
		xQuery.send();
}