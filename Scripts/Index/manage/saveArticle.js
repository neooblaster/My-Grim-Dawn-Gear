function saveArticle(){
	/** Récupération des éléments **/
	var article_save_process = document.querySelector("#article_save_process");
	var article_title = document.querySelector("#article_title");
	var article_id = document.querySelector("#article_id");
	
	/** Communication AJAX **/
	var xQuery = new xhrQuery();
		xQuery.target('/XHR/Index/save_article.php');
		xQuery.inputs(article_save_process, article_id, article_title);
		xQuery.values("article_article="+CKEDITOR.instances.article_editor.getData());
		xQuery.callbacks(
			function(d){
				if(article_save_process.value === 'create'){
					setInterval(function(){document.location.reload();}, 1500);
				}
			}
		);
		xQuery.send();
}