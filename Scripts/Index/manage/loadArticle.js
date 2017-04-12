function loadArticle(src) {
	/** Récupération des éléments **/
	var article_id = src.value;

	var title_input = document.querySelector('#article_title');
	var date_input = document.querySelector('#article_date');
	var save_process_input = document.querySelector('#article_save_process');
	var article_id_input = document.querySelector('#article_id');
	var delete_input = document.querySelector('#article_delete');

	var article_submitor = document.querySelector('#article_submitor');

	var xQuery = new xhrQuery();
	xQuery.target('/XHR/Index/load_article.php');
	xQuery.values('id='+article_id);
	xQuery.callbacks(
		/** Application des données demandée **/
		function(d) {
			console.log(d);

			try {
				d = JSON.parse(d);
				
				/** Si article chargé **/
				if(d.ID > 0){
					title_input.value = d.TITLE;
					date_input.value = d.CREATE_DATE;
					CKEDITOR.instances.article_editor.setData(d.ARTICLE);
					
					article_submitor.value = "Save";
					save_process_input.value = "update";
					article_id_input.value = d.ID;
					
					delete_input.disabled = false;
					//src.disabled = true;
				} 
				
				/** Si article déchargé (new) **/
				else {
					title_input.value = "";
					date_input.value = null;
					CKEDITOR.instances.article_editor.setData("");
					
					article_submitor.value = "Create";
					save_process_input.value = "create";
					article_id_input.value = 0;
					
					
					delete_input.disabled = true;
					//src.disabled = false;
				}
				
			} catch (e) {
				console.error(e);
			}
		}

		/** Boutton Submit = Save **/

		/** Désactiver le champs select **/
	);
	xQuery.send();
}