function updateType(src){
	/** Select Target **/
	var target = document.querySelector('#kind_type');
	
	/** Rel Family **/
	var rel_family = src.value;
	
	/** Déterminer les spécificité **/
		// Si item_families vaut 0, alors tout le mondre vaut vrai
		var show_all = false;
	
		if(rel_family === '0'){
			show_all = true;
		}
	
		// Récupération de l'index du type selectionné
		var type_selectedIndex = target.selectedIndex;
	
		var updateSelectedIndex = false;
	
	/** Parcourir les options **/
	for(var i = 1; i < target.options.length; i++){
		if(target.options[i].getAttribute('data-rel-family') !== rel_family && !show_all){
			if(i === type_selectedIndex){
				updateSelectedIndex = true;
			}
			
			target.options[i].setAttribute('hidden', '');
		} else {
			target.options[i].removeAttribute('hidden');
		}
	}
	
	/** Repositionner la selection si nécessaire **/
	if(updateSelectedIndex){
		target.selectedIndex = 0;
	}
}