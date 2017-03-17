function gear(){
	var self = this;
	
	self.qualities = ['unset', 'common', 'magic', 'rare', 'elite', 'legendary'];
	self.qualities_name = ['Unqualified', 'Common', 'Magic', 'Rare', 'Epic', 'Legendary'];
	
	self.link = function(){
		var build_code = document.querySelector("#build_code").value;
		
		return {
			build: function(){
				prompt('Find below your link to share it :', document.location.hostname+'/build/'+build_code);
			},
			
			image: function(){
				prompt('Find below your link to embeded it as image :', document.location.hostname+'/img/'+build_code);
			}
		};
	};
	
	self.load = function(){
		return {
			build: function(){
				var to_load = document.querySelector('#build_id');
		
				if(to_load !== null){
					var xQuery = new xhrQuery();
						xQuery.target('/XHR/Index/load_build.php');
						xQuery.inputs(to_load);
						xQuery.callbacks(
							function(e){
								try {
									e = JSON.parse(e);
									
									for(var slot in e){
										self.load().slot(slot, e[slot]);
									}
									
								} catch (err){
									console.log("can not parse data");
								}
							}
						);
						xQuery.send();
				}
			},
			
			slot: function(slot, item_id){
				var xQuery = new xhrQuery();
					xQuery.target('/XHR/Index/load_slot.php');
					xQuery.values('item_id='+item_id);
					xQuery.callbacks(
						function(e){
							if(e !== ''){
								try {
									e = JSON.parse(e);
									
									var input_selector = slot.toLowerCase();
									var host_selector = slot.toLowerCase();
									var idx = host_selector.lastIndexOf('_');
										host_selector = host_selector.substr(0, idx);
									
									var host = document.querySelector('.'+host_selector);
										host.classList.add('quality_'+self.qualities[e.REL_QUALITY]);
										host.setAttribute('data-quality', self.qualities[e.REL_QUALITY]);
										host.setAttribute('data-item-id', item_id);
									
									var item = document.createElement('img');
										item.src = "/Images/Items/"+e.TAG_ITEM+'.png';
										item.setAttribute('data-item-id', item_id);
										item.setAttribute('data-quality', self.qualities[e.REL_QUALITY]);
										item.setAttribute('data-attachment', e.SLOT_ATTACHMENT);
										item.classList.add('size'+e.WIDTH+'x'+e.HEIGHT);
										
										host.onmousemove = function(e){self.items().showFrom(this).gear(e);};
										host.onmouseout = function(e){self.items().hide();};
										host.onclick = function(e){self.items().activator();};
										
										host.appendChild(item);
									
									var input = document.querySelector('#input_'+input_selector);
										input.value = item_id;
										
										
									ITEMS['itm-'+item_id] = {
										'NAME': e.NAME,
										'QUALITY': e.REL_QUALITY,
										'TAG_NAME': e.TAG_NAME
									};
								}catch (e){
									console.error("Can not parse data");
								}
							}
						}
					);
					xQuery.send();
			}
		};
	};
	
	self.save = function(){
		
		new xhrQuery().target('/XHR/Index/is_allow.php').callbacks(
			function(a){
				try {
					a = JSON.parse(a);
					
					if(a){
						var xQuery = new xhrQuery();
							xQuery.target('/XHR/Index/build_manager.php');
						
							for(var i = 0; i < document.forms.gear_form.elements.length; i++){
								xQuery.inputs(document.forms.gear_form.elements[i]);
							}
						
							xQuery.callbacks(
								function(e){
									try {
										e = JSON.parse(e);
										
										if(e.OPERATION === 'INSERT'){
											prompt('Find below your link to share it :', document.location.hostname+'/build/'+e.BUILD_CODE);
											history.replaceState("", "", "/build/"+e.BUILD_CODE);
											document.location.reload();
										}
										
									} catch(err){
										console.error("can not parse data", err, e);
									}
								}
							);
							
							xQuery.send();
					}
				} catch(arr){
					console.log("Can not parse data"/*, a*/);
				}
			}
		).send();
		
		

	};
	
	self.calc = function(){
		
	};
	
	self.slots = function(item){
		if(item !== undefined){
			var item_type = item.getAttribute('data-item-type');
		}
		
		return {
			drop: function(slot, e){
				/** Empecher l'evenement par default de se produire **/
				e.preventDefault();
				
				/** Récuéprer les données déposée **/
				var data = JSON.parse(e.dataTransfer.getData("text"));
				var item_id = data.item_id;
				var img = document.querySelector(".gear_panel_inventory_grid img[data-item-id='"+item_id+"']").cloneNode(true);
				var item_quality = img.getAttribute("data-quality");
				var item_attachment = img.getAttribute("data-attachment");
				var slot_name;
				var slot_name_pattern = /^slot_/gi;
				
				/** Mise à jour du slot **/
				// Cleansing
				slot.innerHTML = "";
				slot.classList.remove('quality_'+slot.getAttribute('data-quality'));
				
				// Update
					// Content
					slot.appendChild(img);
						
					// Attributes
					slot.setAttribute('data-item-id', item_id);
					slot.setAttribute('data-quality', item_quality);
					slot.classList.add('quality_'+item_quality);
						
					// Evenements
					slot.onmousemove = function(e){self.items().showFrom(this).gear(e);};
					slot.onmouseout = function(e){self.items().hide();};
					slot.onclick = function(e){self.items().activator();};
				
					// Mise à jour de l'input type hidden correspondant
					for(var c = 0; c < slot.classList.length; c++){
						if(slot_name_pattern.test(slot.classList[c])){
							slot_name = slot.classList[c];
							break;
						}
					}
					
					var input_data = document.querySelector('#input_'+slot_name+'_'+item_attachment);
						input_data.value = item_id;
			},
			
			clear: function(slot/**, part **/){/** L'objet a retirer - si item, to retiré item,comp,augment**/
				var part = 'item';
				
				/** retiré la quality **/
				slot.classList.remove("quality_"+slot.getAttribute('data-quality'));
				slot.setAttribute('data-quality', '');
				
				/** retiré l'objet **/
				slot.innerHTML = "";
				
				/** retiré les event si item **/
				slot.onmousemove = "";
				slot.onmouseout = "";
				slot.onclick = "";
				
				/** Mise à jour du champs input hidden correspondant **/
				var slot_name;
				var slot_name_pattern = /^slot_/gi;
				
				for(var c = 0; c < slot.classList.length; c++){
					
					if(slot_name_pattern.test(slot.classList[c])){
						slot_name = slot.classList[c];
						break;
					}
				}
				
				var input_data = document.querySelector('#input_'+slot_name+'_'+part);
					input_data.value = 0;
			},
			
			show: function(){
				var slots = document.querySelectorAll('.gear_panel_build_items_slot');
				
				for(var i = 0; i < slots.length; i++){
					var slot = slots[i];
					
					if(slot.getAttribute('hidden') === 'true'){
						continue;
					};
					
					var accept_item = slots[i].getAttribute('data-accept-item');
						accept_item = accept_item.split(',');
						
					var accepted = accept_item.lastIndexOf(item_type);
					
					if(accepted >= 0){
						slot.classList.add('droppable');
						
						slot.ondrop = function(ev){gear().slots().drop(this, ev);}.bind(slot);
						slot.ondragover = function(ev){ev.preventDefault(); return false;};
						slot.ondragenter = function(){self.slots().overlay(this).on();}.bind(slot);
						slot.ondragleave = function(){self.slots().overlay(this).off();}.bind(slot);
						
						//slot.addEventListener("dragover", allowDrop);
						//slot.addEventListener("drop", drop);
						//slot.ondragover = function(e){e.preventDefault();e.stopPropagation();};
						//slot.ondrop = function(evt){/*gear().slots().drop(this, evt);*/}.bind(slot);
						//slot.ondrop = function(ev){ev.preventDefault();}; OK
					} else {
						slot.classList.remove('droppable');
						
						slot.ondrop = "";
						slot.ondragover = "";
						slot.ondragenter = "";
						slot.ondragleave = "";
					} 
				}
			},
			
			hide: function(){
				var slots = document.querySelectorAll('.gear_panel_build_items_slot');
				
				for(var i = 0; i < slots.length; i++){
					var slot = slots[i];
					
					slot.classList.remove('droppable');
					slot.classList.remove('dropover');
					
					slot.ondrop = "";
					slot.ondragover = "";
					slot.ondragenter = "";
					slot.ondragleave = "";
				}
			},
			
			overlay: function(slot){
				return {
					on: function(){
						slot.classList.add('dropover');
					},
					
					off: function(){
						slot.classList.remove('dropover');
					}
				};
			}
		};
	};
	
	self.synchronize = function(src, target){
		document.querySelector('#'+target).value = src.value;
	};
	
	self.sign = function(){
		var xQuery = new xhrQuery();
		xQuery.target('/XHR/Index/sign.php');
		xQuery.values(
			"id="+document.querySelector('#build_id').value,
			"password="+prompt("Type your password")
		);
		xQuery.callbacks(
			function(e){
				try {
					e = JSON.parse(e);
					
					if(e.allow){
						var submit_input = document.createElement('input');
							submit_input.setAttribute('type', 'submit');
							submit_input.value = "Save";
							submit_input.title = "Update your gear";
						
						var sign_button = document.querySelector('#sign_button');
						
						sign_button.parentNode.replaceChild(submit_input, sign_button);
					} else {
						alert('Wrong Password. Try Again');
					}
				} catch (err){
					console.log("Can not parse data");
				}
			}
		);
		xQuery.send();
	};
	
	self.items = function(){
		return {
			window: function(title, id, name, quality){
				var window = document.createElement('div');
					window.classList.add('item_window');
					window.classList.add('quality_'+self.qualities[quality]);
				
					var item_name_title = document.createElement('h3');
						item_name_title.classList.add('item_name');
						item_name_title.textContent = title;
						
					var item_name_subtitle = document.createElement('h4');
						item_name_subtitle.textContent = self.qualities_name[quality];
						
						var edit_pattern = /#!edit$/gi;
						if(edit_pattern.test(document.location.href)){
							var edit = document.createElement('a');
								edit.classList.add('editer');
								edit.textContent = "Edit";
								edit.onclick = function(){self.items().activator();};
								edit.href = "/manage/edit/items/"+id;
								edit.target = name;
							
							item_name_subtitle.appendChild(edit);
						}
						
					var primaries = document.createElement('ul');
				
					var secondaries = document.createElement('ul');
				
					var petties = document.createElement('ul');
				
					var item_set_name = document.createElement('h3');
					var item_set_desc = document.createElement('h3');
					var item_set_tiers = document.createElement('h3');
				
					var requirements = document.createElement('ul');
				
					var editor = document.createElement('ul');
				
					window.appendChild(item_name_title);
					window.appendChild(item_name_subtitle);
					window.appendChild(primaries);
					window.appendChild(secondaries);
					window.appendChild(petties);
					window.appendChild(requirements);
					window.appendChild(editor);
				return window;
			},
			
			showFrom: function(item){
				/** Récupération de l'ID **/
				var id = item.getAttribute('data-item-id');
				var show_tag_item = item.getAttribute('data-show-tag-item');
				
				/** Donnée **/
				var data = ITEMS['itm-'+id];
				
				/** Récupération de toutes les fenêtre pour les supprimer **/
				self.items().hide();
				
				/** Titre de la fenêtre **/
				var name_title = (show_tag_item === 'true') ? (data.NAME+' '+data.TAG_NAME) : (data.NAME);
				
				var window = self.items().window(name_title, id, data.NAME, data.QUALITY);
				
				return {
					inventory: function(e){
						if(!ACTIVATED){
							window.classList.add('inventory_item');
							
							window.setAttribute("style", "left: "+(e.pageX + 32)+"px; top: "+e.pageY+"px;");
							document.body.appendChild(window);
						}
					}, 
					
					gear: function(e){
						if(!ACTIVATED){
							window.classList.add('gear_item');
						
							var remover = document.createElement('a');
								remover.classList.add('remover');
								remover.textContent = 'Remove';
								remover.href = "#";
								remover.onclick = function(){self.slots().clear(item); self.items().activator(); self.items().hide();};
							
							window.querySelector('h4').appendChild(remover);
							
							window.setAttribute("style", "left: "+(e.pageX + 32)+"px; top: "+e.pageY+"px;");
							document.body.appendChild(window);
						}
						/**> si compare, alors à gauche de item inv **/
						/**> compare if activated, or compare = true on move **/
					}
				};
			},
			
			hide: function(){
				if(!ACTIVATED){
					/** Récupération de toutes le fenêtre pour les supprimer **/
					var windows = document.querySelectorAll('.item_window');
					
					for(var w = 0; w < windows.length; w++){
						windows[w].parentNode.removeChild(windows[w]);
					}
				}
			},
			
			activator: function(){
				ACTIVATED = (ACTIVATED) ? false : true;
				
				var windows = document.querySelectorAll('.item_window');
				
				(ACTIVATED) ? windows.forEach(function(el){el.classList.add('active');}) : windows.forEach(function(el){el.classList.remove('active');});
			},
			
			load: function(form){
				/** Instancier le moteur AJAX **/
				var xQuery = new xhrQuery();
					xQuery.target('/XHR/Index/load_items.php');
				
				/** Envois des champs de données **/
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
							var host = document.querySelector(".gear_panel_inventory_grid");
								host.innerHTML = "";
							
							var data = JSON.parse(d);
							
							for(var i = 0; i < data.items.length; i++){
								var size = 'size'+data.items[i].WIDTH+'x'+data.items[i].HEIGHT;
								var id = data.items[i].ID;
								
								/** <div class="gear_panel_inventory_grid_item size2x4" data-width="2" data-height="4">item_1 2x4</div> **/
								var item = document.createElement('div');
									item.classList.add('gear_panel_inventory_grid_item');
									item.classList.add('quality_'+qualities[data.items[i].QUALITY]);
									item.classList.add(size);
									
									item.setAttribute('data-item-id', id);
									item.setAttribute('data-item-type', data.items[i].TYPE);
									item.setAttribute('data-width', data.items[i].WIDTH);
									item.setAttribute('data-height', data.items[i].HEIGHT);
									item.setAttribute('draggable', 'true');
									
									/** Au démarrage du glisser - Collecter les données de transfert **/
									item.ondragstart = function(id, ev){
										/** Envois des données en JSON **/
										ev.dataTransfer.setData("text", '{"item_id": '+id+'}'); 
										
										/** Afficher les slots pouvant accueillir l'objet en cours de glissement **/
										gear().slots(this).show();
									}.bind(item, id);
									
									item.ondragend = function(ev){gear().slots(this).hide();};
									
									//item.ondragstart = function(ev){ev.dataTransfer.setData("text", ev.target.id); gear().slots(this).show();};
									//item.ondragstart = function(ev){ev.dataTransfert.setData("text", "test"); alert(ods);alert(ods.dataTransfer);alert(ods.target);gear().slots(this).show();};
									//item.addEventListener("dragstart", function(ev){ev.dataTransfer.setData("text", ev.target.id); alert("next");});
									//item.ondragstart = function(ev){ev.dataTransfer.setData("text", ev.target.id); alert("next");};
								
									item.onmousemove = function(e){gear().items().showFrom(this).inventory(e);};
									item.onmouseout = function(e){gear().items().hide();};
									item.onclick = function(e){gear().items().activator();};
								
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
							console.log("ERROR : ", e, d);
						}
					}
				);
				
				xQuery.send();
			}
		};
	};
	
	return self;
}


document.onreadystatechange = function(){
	if(document.readyState === 'complete'){
		gear().load().build();
	}
};

var ACTIVATED = false;
var COMPARE = false;
var ITEMS = {};


function drag(ev){
	ev.dataTransfer.setData("text", ev.target.id);
   alert("next");
}

function allowDrop(ev) {
    ev.preventDefault();
}

function drop(ev) {
    ev.preventDefault();
    //var data = ev.dataTransfer.getData("text");
    //ev.target.appendChild(document.getElementById(data));
}