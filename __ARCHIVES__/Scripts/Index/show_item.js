function show_item(e){
	
	if(!ACTIVATED){
		var qualities = ['unset', 'common', 'magic', 'rare', 'elite', 'legendary'];
		var qualities_name = ['Unqualified', 'Common', 'Magic', 'Rare', 'Epic', 'Legendary'];
		
		/** Récupération de l'ID **/
		var id = this.getAttribute('data-item-id');
		var show_tag_item = this.getAttribute('data-show-tag-item');
		
		/** Donnée **/
		var data = ITEMS['itm-'+id];
		
		/** Récupération de toutes les fenêtre pour les supprimer **/
		hide_item();
		
		/** **/
		var name_title = (show_tag_item === 'true') ? (data.NAME+' '+data.TAG_NAME) : (data.NAME);
		
		var window = document.createElement('div');
			window.classList.add("item_window");
			window.classList.add("quality_"+qualities[data.QUALITY]);
		
			var item_name_title = document.createElement('h3');
				item_name_title.classList.add('item_name');
				item_name_title.textContent = name_title;
		
			var item_name_subtitle = document.createElement('h4');
				item_name_subtitle.textContent = qualities_name[data.QUALITY];
		
				var edit_pattern = /#!edit$/gi;
				if(edit_pattern.test(document.location.href)){
					var edit = document.createElement('a');
						edit.textContent = "Edit";
						edit.onclick = activator;
						edit.href = "/manage/edit/items/"+id;
						edit.target = data.TAG_NAME;
					
					item_name_subtitle.appendChild(edit);
				}
		
			var primaries = document.createElement('ul');
		
			var secondaries = document.createElement('ul');
		
			var petties = document.createElement('ul');
		
			var item_set_name = document.createElement('h3');
			var item_set_desc = document.createElement('h3');
			var item_set_tiers = document.createElement('h3');
			var item_set_tiers_two = document.createElement('h3');
		
			var requirements = document.createElement('ul');
		
			var editor = document.createElement('ul');
			
			
			window.appendChild(item_name_title);
			window.appendChild(item_name_subtitle);
			window.appendChild(primaries);
			window.appendChild(secondaries);
			window.appendChild(petties);
			window.appendChild(requirements);
			window.appendChild(editor);
		document.body.appendChild(window);
		
		window.setAttribute("style", "left: "+(e.pageX + 32)+"px; top: "+e.pageY+"px;");
	}
}

function activator(){
	ACTIVATED = (ACTIVATED) ? false : true;
		
	var windows = document.querySelector('.item_window');
	
	if(ACTIVATED){
		windows.classList.add('active');
	} else {
		windows.classList.remove('active');
	}
}

function hide_item(){
	if(!ACTIVATED){
		/** Récupération de toutes les fenêtre pour les supprimer **/
		var windows = document.querySelectorAll('.item_window');
		
		for(var w = 0; w < windows.length; w++){
			windows[w].parentNode.removeChild(windows[w]);
		}
	}
}

//function edit_item(){
//	/** Récupération de toutes les fenêtre pour les supprimer **/
//	var windows = document.querySelector('.item_window');
//	
//	windows.classList.add('edit');
//}

/** 

<div class="item_window quality_$qly $active?">
</div>

**/