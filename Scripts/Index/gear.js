//#!/compile = true
/** ----------------------------------------------------------------------------------------------------------------------- ** 
/** ----------------------------------------------------------------------------------------------------------------------- ** 
/** ---																																						--- **
/** --- 															------------------------															--- **
/** ---																	{ gear.js }																		--- **
/** --- 															------------------------															--- **
/** ---																																						--- **
/** ---		AUTEUR 	: Nicolas DUPRE																												--- **
/** ---																																						--- **
/** ---		RELEASE	: xx.xx.2017																													--- **
/** ---																																						--- **
/** ---		VERSION	: 1.0																																--- **
/** ---																																						--- **
/** ---																																						--- **
/** --- 														-----------------------------															--- **
/** --- 															 { C H A N G E L O G } 																--- **
/** --- 														-----------------------------															--- **
/** ---																																						--- **
/** ---		VERSION 1.0 : xx.xx.2017																												--- **
/** ---		------------------------																												--- **
/** ---			- Première release																													--- **
/** ---																																						--- **
/** --- 											-----------------------------------------------------										--- **
/** --- 												{ L I S T E      D E S      M E T H O D E S } 											--- **
/** --- 											-----------------------------------------------------										--- **
/** ---																																						--- **
/** ----------------------------------------------------------------------------------------------------------------------- **
/** ----------------------------------------------------------------------------------------------------------------------- **


	Objectif de la fonction :
	-------------------------
		


	variable Globales requises :
	----------------------------
		
	

	Déclaration des structure de donnée :
	-------------------------------------
		
	

	Description fonctionnelle :
	---------------------------
		
	
	
	Exemples d'utilisations :
	-------------------------
		
	
	
	
/** ----------------------------------------------------------------------------------------------------------------------- **
/** ----------------------------------------------------------------------------------------------------------------------- **/
function gear(token){
	/** -------------------------------------------------------------------------------------------------------------------- **
	/** ---																																					--- **
	/** ---												Déclaration des propriétés de l'instance												--- **
	/** ---																																					--- **
	/** -------------------------------------------------------------------------------------------------------------------- **/
	var self = this;
	
	/** Inline described properties **/
	self.token = token;			// INTEGER	:: Token de liaison de donnée avec les session (multi onglet)
	
	self.item_previewed = null;// INTEGER	:: ID de l'objet en cours de consultation (permet d'optimiser le traitement)
	
	self.items = {};				// OBJECT	:: Liste des objets présente dans l'inventaire
	
	/** Block described properties **/
	// Liste des selecteurs utilisé dans l'application
	self.selectors = {
		inventory: ".gear_panel_inventory_grid",
		slots: ".gear_panel_build_items_slot"
	};
	
	// Différente qualités d'objet par ordre d'index
	self.qualities = ['unset', 'common', 'magic', 'rare', 'elite', 'legendary'];
	
	// Différente nom de qualités d'objet par ordre d'index
	self.qualities_names = ['Unqualified', 'Common', 'Magic', 'Rare', 'Epic', 'Legendary'];
	
	// Mémorisation des élements de consultation
	self.windows = {
		// Indique si un objet est activé (en cours de manipulation)
		activated: false,
		// Derniere fenêtre créer
		element: null,
		// Dernier objet Affiché (ID)
		id: null,
		// Mode de la fenêtre
		mode: null
	};
	
	// URLS de l'application
	self.targets = {
		build_loader:'/XHR/Index/load_build.php',
		build_manager: '/XHR/Index/build_manager.php',
		build_signer: '/XHR/Index/sign.php',
		items_loader: "/XHR/Index/load_items.php",
		item_loader: '/XHR/Index/load_item.php'
	};
	
	
	/** -------------------------------------------------------------------------------------------------------------------- **
	/** ---																																					--- **
	/** ---															Pré-execution interne															--- **
	/** ---																																					--- **
	/** -------------------------------------------------------------------------------------------------------------------- **/
	/** > Ajout du token aux URLS **/
	for(var url in self.targets){
		self.targets[url] += "?token="+self.token;
	}
	
	
	/** -------------------------------------------------------------------------------------------------------------------- **
	/** ---																																					--- **
	/** ---												Déclaration des méthodes de l'instance													--- **
	/** ---																																					--- **
	/** -------------------------------------------------------------------------------------------------------------------- **/
	/** ----------------------------------------------------------------- **
	/** --- Programme de gestion des contraintes fonctionnel du build --- **
	/** ----------------------------------------------------------------- **/
	self.build = {
		/** Capture du build à son état initial (onload) **/
		hash: {},
		
		/** Indicateur de modificaiton du build **/
		changed: false,
		
		/** Flag qui indique si le build est vide ou non **/
		empty: true,
		
		/** Référencement des inputs **/
		inputs: null,
		
		/** Méthode d'assimiliation du build **/
		init: function(){
			var isLoading = false;
			
			for(var i in self.build.loading){
				isLoading = true;
				break;
			}
			
			if(!isLoading){
				self.build.inputs = document.forms.gear_form.querySelectorAll("input[name*=slot_]");
				
				for(var i = 0; i < self.build.inputs.length; i++){
					var input = self.build.inputs[i];
					var value = parseInt(input.value);
					
					self.build.hash[input.name] = value;
					
					if(value){
						self.build.empty = false;
					}
				}
			}
		},
		
		/** Méthode de controle du build **/
		controle: function(){
			var change_break = false;
			var empty_break = false;
			
			for(var i = 0; i < self.build.inputs.length; i++){
				var input = self.build.inputs[i];
				var value = parseInt(input.value);
				
				/** Si une valeur positive, un objet est présent **/
				if(value){
					self.build.empty = false;
					empty_break = true;
				}
				
				/** Si la valeur stockée en hash est différent alors le build a changé **/
				if(self.build.hash[input.name] !== value){
					self.build.changed = true;
					change_break = true;
				}
				
				if(change_break && empty_break){
					break;
				}
			}
			
			/** Si le flag change_break n'a pas été déclencher, le build est tel qu'à l'initiale **/
			if(!change_break){
				self.build.changed = false;
			}
			
			/** Si le flag emptry_break n'à pas été déclencher, le build est vide **/
			if(!empty_break){
				self.build.empty = true;
			}
		},
		
		/** Liste des slots en cours de chargement. L'initialisation ne peux etre faites tant que des slots sont en cours de chargement**/
		loading: {
			
		}
	};
	
	
	/** ---------------------------------------- **
	/** --- Programmes de gestion des objets --- **
	/** ---------------------------------------- **/
	self.item = function(){
		return {
			/** **/
			activator: function(){
				self.windows.activated = (self.windows.activated) ? false : true;
				
				var windows = document.querySelectorAll('.item_window');
				
				(self.windows.activated) ? windows.forEach(function(el){el.classList.add('active');}) : windows.forEach(function(el){el.classList.remove('active');});
			},
			
			/** Méthode de fermeture des fene^tre d'objet **/
			hide: function(){
				if(!self.windows.activated){
					/** Récupération de toutes le fenêtre pour les supprimer **/
					var windows = document.querySelectorAll('.item_window');
					
					for(var w = 0; w < windows.length; w++){
						windows[w].parentNode.removeChild(windows[w]);
					}
				}
			},
			
			/** Méthode de chargement des objets **/
			load: function(form){
				/** Création d'un moteur AJAX **/
				var xQuery = new xhrQuery();
				
				/** Définition de la cible de chargement des objets **/
				xQuery.target(self.targets.items_loader);
				
				/** Parcourir les éléments du formulaire pour envoyer les informations **/
				xQuery.forms(form);
				
				/** Méthode de rappel **/
				xQuery.callbacks(function(self, d){
					try {
						// Récupération de l'hote
						var host = document.querySelector(self.selectors.inventory);
						
						// Cleansing
						host.innerHTML = null;
						
						// Parser les donnée
						var data = JSON.parse(d);
						
						// Parcourir l'ensemble des objets
						for(var i = 0; i < data.items.length; i++){
							/** Variable Locale **/
							var item = data.items[i];
							var size;
							
							/** Calculer les paramètres suivant **/
							// Dimension de l'objet
							size = "size"+item.WIDTH+"x"+item.HEIGHT;
							
							/** Création de l'objet depuis la structure JSON suivante **/
							host.appendChild(HTML().compose({
								name: "div", 
								classList: ["gear_panel_inventory_grid_item", "quality_"+self.qualities[item.QUALITY], size],
								attributes: {
									"data-item-id": item.ID,
									"data-item-type": item.TYPE,
									"data-item-width": item.WIDTH,
									"data-item-height": item.HEIGHT,
									"draggable": "true",
								},
								properties: {
									ondragstart: function(id, ev){
										/** Spécifié les donnée transféré **/
										ev.dataTransfer.setData("text", '{"item_id": '+id+'}');
										
										/** Montrer les slots compatible **/
										self.slots(ev.target.parentNode).show();
									}.bind(null, item.ID),
									ondragend: function(ev){self.slots(ev.target.parentNode).hide();},
									onmousemove: function(e){self.item().show(this, 'inventory', e);},
									onmouseout: function(){self.item().hide();},
									onclick: function(){self.item().activator();}
								},
								children: [
									{
										name: "img",
										classList: [size],
										attributes: {
											"data-item-id": item.ID,
											"data-item-quality": self.qualities[item.QUALITY],
											"data-item-attachment": item.ATTACHMENT,
										},
										properties: {
											src: "/Images/Items/"+item.TAG+".png",
											onerror: function(){
												this.src = '/Images/Items/NotFound.png';
												this.parentNode.setAttribute('data-show-tag-item', true);
											}
										}
									}
								]
							}));
							
							/** Mémoriser les objets **/
							self.items['itm-'+item.ID] = {
								'NAME': item.NAME,
								'QUALITY': item.QUALITY,
								'TAG_NAME': item.TAG
							};
						}
						
						// Ordonnée les objets 
						inventory();
					} catch (err){
						console.error("gear::item::load.callback failed on", d, "with error", err);
					}
				}.bind(null, self));
				
				/** Envois des données **/
				xQuery.send();
			},
			
			/** Méthode d'affichage des propriété de l'objet **/
			show: function(item, mode, e){
				/** Si la fenêtre n'est pas activée **/
				if(!self.windows.activated){
					/** Récupération des informations de l'objet **/
					var ID = item.getAttribute("data-item-id");
					var window = null;
					
					/** Si l'objet à changer ou le mode, alors créer une nouvelle fenêtre **/
					if(self.windows.id !== ID || self.windows.mode !== mode){
						var show_tag_item = item.getAttribute("data-show-tag-item");
							show_tag_item = (show_tag_item) ? show_tag_item.toLowerCase() : null;
						var data = self.items["itm-"+ID];
					
						/** Fermer les éventuelles fenêtre d'ouverte **/
						self.item().hide();
					
						/** Déterminer le titre de la fenêtre **/
						var title = (show_tag_item === 'true') ? (data.NAME+' '+data.TAG_NAME) : (data.NAME);
					
						var structure = {
							name: "div", 
							classList: ["item_window", "quality_"+self.qualities[data.QUALITY]],
							children: [
								{name: "h3", classList:["item_name"], properties: {textContent: title}},
								{name: "h4", properties: {textContent: self.qualities_names[data.QUALITY]}, children: []},
								{name: "ul"},
								{name: "ul"},
								{name: "ul"},
								{name: "h3"},
								{name: "h3"},
								{name: "h3"},
								{name: "ul"},
								{name: "ul"}
							]
						};
					
						var edit_pattern = /#!edit$/gi;
					
						if(edit_pattern.test(document.location.href)){
							structure.children[1].children.push({
								name: "a", classList: ["editer"],
								properties: {
									textContent: "Edit",
									onclick: function(){},
									href: "/manage/edit/items/"+ID,
									target: data.NAME
								}
							});
						}
						
						window = HTML().compose(structure);
					
						/** Ajustement selon le mode **/
						switch(mode){
							case "inventory":
								window.classList.add("inventory_item");
							break;
							
							case "gear":
								window.classList.add("gear_item");
								
								var remover = HTML().compose({
									name: "a", classList: ["remover"],
									properties: {
										textContent: 'Remove', href: "#",
										onclick: function(){
											self.slots().clear(item);
											self.item().activator();
											self.item().hide();
										}
									}
								});
								
								window.querySelector("h4").appendChild(remover);
							break;
						}
					} else {
						window = self.windows.element;
					}
					
					/** Positionnement **/
					window.setAttribute("style", "left: "+(e.pageX + 32)+"px; top: "+e.pageY+"px;");
					
					document.body.appendChild(window);
					
					/** Mémoriser les informations**/
					self.windows.id = ID;
					self.windows.element = window;
					self.windows.mode = mode;
				}
			}
		};
	};
	
	
	/** ------------------------------------------------- **
	/** --- Programme de gestion des liens des builds --- **
	/** ------------------------------------------------- **/
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
	
	
	/** ------------------------------------------------------ **
	/** --- Programme de gestion des chargement asynchrone --- **
	/** ------------------------------------------------------ **/
	self.load = function(){
		return {
			/** Méthode de chargement des références qui compose un build **/
			build: function(){
				var xQuery = new xhrQuery();
					xQuery.target(self.targets.build_loader);
					xQuery.callbacks(
						function(e){
							try {
								e = JSON.parse(e);
								
								if(e.statut === "loaded"){
									/** Charger chaque slot **/
									for(var slot in e.slots){
										/** Si le slots n'est pas vide **/
										if(e.slots[slot]){
											console.log(self.build);
											
											/** Ajouter une référence de chargement **/
											self.build.loading[slot] = true;
											console.log(self.build);
											
											/** Charger l'objet **/
											self.load().slot(slot, e.slots[slot]);
										}
									}
								} else if(e.statut === "empty") {
									self.build.init();
								}
							} catch (err){
								console.log("gear::load::build.callback failed on", e, "with error", err);
							}
						}
					);
					xQuery.send();
			},
			
			/** Méthode de chargement des données du slots donnée **/
			slot: function(slot, item_id){
				if(item_id > 0){
					var xQuery = new xhrQuery();
					
					xQuery.target(self.targets.item_loader);
					xQuery.values('item_id='+item_id);
					xQuery.callbacks(function(e){
						e = e || {};
						try {
							e = JSON.parse(e);
							
							var input_selector = slot.toLowerCase();
							var host_selector = slot.toLowerCase();
							var index = host_selector.lastIndexOf("_");
							
							host_selector = host_selector.substr(0, index);
							
							var host = document.querySelector('.'+host_selector);
							host.classList.add('quality_'+self.qualities[e.QUALITY]);
							host.setAttribute('data-item-quality', self.qualities[e.QUALITY]);
							host.setAttribute('data-item-id', e.ID);
							
							var item = document.createElement('img');
							item.src = "/Images/Items/"+e.TAG+'.png';
							item.setAttribute('data-item-id', e.ID);
							item.setAttribute('data-item-quality', self.qualities[e.QUALITY]);
							item.setAttribute('data-item-attachment', e.ATTACHMENT);
							item.classList.add('size'+e.WIDTH+'x'+e.HEIGHT);
							item.onerror = function(){
								this.src = '/Images/Items/NotFound.png';
								this.parentNode.setAttribute('data-show-tag-item', true);
							};
								
							host.onmousemove = function(e){self.item().show(this, 'gear', e);};
							host.onmouseout = function(e){self.item().hide();};
							host.onclick = function(e){self.item().activator();};
								
							host.appendChild(item);
							
							var input = document.querySelector('#input_'+input_selector);
							input.value = e.ID;
								
								
							self.items['itm-'+e.ID] = {
								'NAME': e.NAME,
								'QUALITY': e.QUALITY,
								'TAG_NAME': e.TAG
							};
						} catch (err){
							
						}
						
						/** Déférencer le chargement en cours **/
						delete self.build.loading[slot];
						
						/** Lancer une eventuelle initialisation du build **/
						self.build.init();
					});
					xQuery.send();
				}
			}
		};
	};
	
	
	/** -------------------------------------- **
	/** --- Méthode de sauvegarde du build --- **
	/** -------------------------------------- **/
	self.save = function(copy){
		/** > Si le build n'est pas vide... **/
		if(!self.build.empty){
			/** > Si le build à changé alors sauvegarder, sinon ignorer */
			if(self.build.changed){
				var xQuery = new xhrQuery();
				xQuery.target(self.targets.build_manager);
				if(copy) xQuery.values("copy=true");
				xQuery.forms(document.forms.gear_form);
				xQuery.callbacks(function(e){
					try {
						e = JSON.parse(e);
						
						if(e.statut === "success"){
							// Si pas de code reçu, c'était une mise à jour
							if(e.code !== ""){
								prompt("Find below your link to share it :", document.location.hostname+'/build/'+e.code);
								
								if(!copy){
									history.replaceState("", "", "/build/"+e.code);
									document.location.reload();
								} else {
									// ouvrir un nouvelle onglet sur la copie
									var newTab = window.open('/build/'+e.code, e.code);
									newTab.focus();
								}
							} else {
								
							}
						} else {
							alert(e.message);
						}
					} catch(err){
						console.error("gear::save.callback failed on", e, "with error", err);
					}
				});
				xQuery.send();
			}
			else {
				alert("Nothing changed");
			}
		} 
		/** Sinon envoyer une notification **/
		else {
			alert("Your build is empty");
		}
	};
	
	
	/** ------------------------------------------------ **
	/** --- Programme des gestion des slots d'objets --- **
	/** ------------------------------------------------ **/
	self.slots = function(item){
		/** Déclaration des variables **/
		// Type de l'objet
		var item_type = null; 
		// Liste des slots de l'application 
		var slots = document.querySelectorAll(self.selectors.slots);
		
		/** Controle de l'argument ITEM (objet manipuler) **/
		if(item !== undefined){
			item_type = item.getAttribute("data-item-type");
		}
		
		return {
			/** Méthode de suppression des objet attaché **/
			clear: function(slot){/** part : L'objet a retirer - si item, to retiré item,comp,augment**/
				var attachment = 'item';
				
				/** Retirer la quality **/
				slot.classList.remove("quality_"+slot.getAttribute('data-item-quality'));
				slot.setAttribute('data-item-quality', '');
				
				/** Retirer l'objet **/
				slot.innerHTML = "";
				
				/** retiré les event si item **/
				slot.onmousemove = "";
				slot.onmouseout = "";
				slot.onclick = "";
				
				/** Mise à jour du champs input hidden correspondant **/
				self.slots().input(slot, attachment, 0);
				
				/** Déclencher un controle de build **/
				self.build.controle();
			},
			
			/** Méthode de dépose de l'objet dans le slot **/
			drop: function(slot, e){
				/** Empecher l'evenement par default de se produire (firefox) **/
				e.preventDefault();
				
				/** Récuéprer les données déposée **/
				var data = JSON.parse(e.dataTransfer.getData("text"));
				var item_id = data.item_id;
				
				var img = document.querySelector(".gear_panel_inventory_grid img[data-item-id='"+item_id+"']").cloneNode(true);
				var item_quality = img.getAttribute("data-item-quality");
				var item_attachment = img.getAttribute("data-item-attachment");
				var show_tag_item = img.getAttribute("data-show-tag-item");
				
				var slot_name;
				var slot_name_pattern = /^slot_/gi;
				
				/** Mise à jour du slot **/
				// Cleansing
				slot.innerHTML = "";
				slot.classList.remove('quality_'+slot.getAttribute('data-item-quality'));
				
				// Update
				//--- Content
				slot.appendChild(img);
				
				//--- Attributes
				slot.setAttribute('data-item-id', item_id);
				slot.setAttribute('data-item-quality', item_quality);
				slot.setAttribute('data-show-tag-item', show_tag_item);
				slot.classList.add('quality_'+item_quality);
				
				//--- Evenements
				slot.onmousemove = function(e){self.item().show(this, 'gear', e);};
				slot.onmouseout = function(e){self.item().hide();};
				slot.onclick = function(e){self.item().activator();};
				
				//--- Mise à jour de l'input type hidden correspondant
				self.slots().input(slot, item_attachment, item_id);
				
				/** Déclencher un controle de build **/
				self.build.controle();
			},
			
			/** Méthode pour masquer les slots compatible **/
			hide: function(){
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
			
			/** Méthode de manipulation de l'input hidden contenant les données **/
			input: function(slot, attachment, value){
				var slot_name;
				var slot_name_pattern = /^slot_/gi;
				
				/** Récupérer le nom du slot **/
				for(var c = 0; c < slot.classList.length; c++){
					if(slot_name_pattern.test(slot.classList[c])){
						slot_name = slot.classList[c];
						break;
					}
				}
				
				/** Mise à jour de l'input **/
				var input_data = document.querySelector('#input_'+slot_name+'_'+attachment);
					input_data.value = value;
			},
			
			/** Méthode de gestion de l'overlay **/
			overlay: function(slot){
				return {
					on: function(){
						slot.classList.add('dropover');
					},
					
					off: function(){
						slot.classList.remove('dropover');
					}
				};
			},
			
			/** Méthode pour montrer les slots compatible à l'objet **/
			show: function(){
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
						
						slot.ondrop = function(ev){self.slots().drop(this, ev);}.bind(slot);
						slot.ondragover = function(ev){ev.preventDefault(); return false;};
						slot.ondragenter = function(){self.slots().overlay(this).on();}.bind(slot);
						slot.ondragleave = function(){self.slots().overlay(this).off();}.bind(slot);
					} else {
						slot.classList.remove('droppable');
						
						slot.ondrop = "";
						slot.ondragover = "";
						slot.ondragenter = "";
						slot.ondragleave = "";
					} 
				}
			}
		};
	};
	
	
	/** ------------------------------------------- **
	/** --- Méthode d'authentification de build --- **
	/** ------------------------------------------- **/
	self.sign = function(){
		var password = prompt("Type your password");
		
		if(password !== null || password !== ""){
			var xQuery = new xhrQuery();
			xQuery.target(self.targets.build_signer);
			xQuery.values("password="+password);
			xQuery.callbacks(function(e){
				console.log(e);
				
				try {
					e = JSON.parse(e);
					
					/** > Si authentification effectuée avec succèss **/
					if(e.statut === "success"){
						/** > Le bouton save, devient le bouton update (no changement fonctionnel) **/
						var save_input = document.querySelector("#save_button");
							save_input.value = "UPDATE";
							save_input.title = "Update your build";
						
						/** > Le bouton sign devient le bouton save as copy (changement fonctionnel) **/
						var sign_button = document.querySelector("#sign_button");
							sign_button.parentNode.replaceChild(HTML().compose({
								name: "input",
								attributes: {
									type: "button",
									value: "save as copie"
								},
								properties: {
									onclick: function(p){self.save(p);}.bind(self, true)
								}
							}), sign_button);
					}
					else {
						alert(e.message);
					}
					
				} catch(err){
					console.error("gear::sign.callback failed on", e, "with error", err);
				}
			});
			xQuery.send();
		}
	};
	
	
	/** ------------------------------------------------------- **
	/** --- Programme des gestion du panneau de statistique --- **
	/** ------------------------------------------------------- **/
	self.stats = function(){
		// calculation
	};
	
	
	/** -------------------------------------------- **
	/** --- Méthode de synchronisation de champs --- **
	/** -------------------------------------------- **/
	self.synchronize = function(src, target){
		var target_input;
		
		if(typeof(target) !== "object"){
			target_input = document.getElementById(target);
		}
		
		if(target !== null){
			target_input.value = src.value;
		} else {
			console.error("gear::synchronize can not update target :", target);
		}
	};
	
	
	/** -------------------------------------------------------------------------------------------------------------------- **
	/** ---																																					--- **
	/** ---													Déclaration des alias de l'instance													--- **
	/** ---																																					--- **
	/** -------------------------------------------------------------------------------------------------------------------- **/
	
	
	/** -------------------------------------------------------------------------------------------------------------------- **
	/** ---																																					--- **
	/** ---															Post-Execution interne															--- **
	/** ---																																					--- **
	/** -------------------------------------------------------------------------------------------------------------------- **/
	/** > Initialisation de la section **/
	self.load().build();
	
	
	/** -------------------------------------------------------------------------------------------------------------------- **
	/** ---																																					--- **
	/** ---																	Retour																		--- **
	/** ---																																					--- **
	/** -------------------------------------------------------------------------------------------------------------------- **/
	return self;
}


var GEAR = null;
document.onreadystatechange = function(){
	if(document.readyState === 'complete'){
		if(document.forms.gear_form) GEAR = new gear(TOKEN);
	}
};