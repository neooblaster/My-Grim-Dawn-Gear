function load_items(form){
	/** Instancier le moteur AJAX **/
	var xQuery = new xhrQuery();
		xQuery.target('/XHR/Index/load_items.php');
		
		/** Envois des champs de donnée **/
		for(var e = 0; e < form.elements.length; e++){
			switch(form.elements[e].nodeName){
				case 'INPUT':
					xQuery.inputs(form.elements[e]);
				break;
				
				case 'SELECT':
					xQuery.values(form.elements[e].getAttribute('name')+'='+form.elements[e].value);
				break;
			}
		}
		
		xQuery.callbacks(
			function(d){
				try {
					var qualities = ['unset', 'common', 'magic', 'rare', 'elite', 'legendary'];
					
					var host = document.querySelector('.gear_panel_inventory_grid');
						host.innerHTML = '';
					var data = JSON.parse(d);
					
					for(var i = 0; i < data.items.length; i++){
						var size = 'size'+data.items[i].WIDTH+'x'+data.items[i].HEIGHT;
						
						/** <div class="gear_panel_inventory_grid_item size2x4" data-width="2" data-height="4">item_1 2x4</div> **/
						var item = document.createElement('div');
							item.classList.add('gear_panel_inventory_grid_item');
							item.classList.add('quality_'+qualities[data.items[i].QUALITY]);
							item.classList.add(size);
						
							item.setAttribute('data-item-id', data.items[i].ID);
							item.setAttribute('data-item-type', data.items[i].TYPE);
							item.setAttribute('data-width', data.items[i].WIDTH);
							item.setAttribute('data-height', data.items[i].HEIGHT);
							item.setAttribute('draggable', 'true');
							item.ondragstart = function(){gear().slots(this).show();};
							item.ondragend = function(){gear().slots(this).hide();};
						
							//item.onmouseover = show_item;
							//item.onmousemove = show_item;
							//item.onmouseout = hide_item;
							//item.onclick = activator;
						
							item.onmousemove = function(e){gear().item().showFrom(this).inventory(e);};
							item.onmouseout = function(e){gear().item().hide();};
							item.onclick = function(e){gear().item().activator();};
						
						var img = new Image();
							img.onerror = function(){
								//this.src = '/Images/Items/NotFound-'+size+'.png';
								this.src = '/Images/Items/NotFound.png';
								this.parentNode.setAttribute('data-show-tag-item', true);
							};
							
							img.src = '/Images/Items/'+data.items[i].TAG_NAME+'.png';
							img.setAttribute('data-item-id', data.items[i].ID);
							img.setAttribute('data-quality', qualities[data.items[i].QUALITY]);
							img.setAttribute('data-attachment', data.items[i].ATTACHMENT);
							img.classList.add(size);
						
						item.appendChild(img);
						host.appendChild(item);
						
						ITEMS['itm-'+data.items[i].ID] = {
							'NAME': data.items[i].NAME,
							'QUALITY': data.items[i].QUALITY,
							'TAG_NAME': data.items[i].TAG_NAME
						};
					}
					
					inventory();
					
				} catch(e){
					console.error("ERROR : ", e, d);
				}
			}
		);
	xQuery.send();
}