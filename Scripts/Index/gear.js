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
/** ---		VERSION	: 1.0.0																															--- **
/** ---																																						--- **
/** ---																																						--- **
/** --- 														-----------------------------															--- **
/** --- 															 { C H A N G E L O G } 																--- **
/** --- 														-----------------------------															--- **
/** ---																																						--- **
/** ---		VERSION 1.0.0: xx.xx.2017																												--- **
/** ---		------------------------																												--- **
/** ---			- Première release																													--- **
/** ---																																						--- **
/** --- 											-----------------------------------------------------										--- **
/** --- 												{ L I S T E      D E S      M E T H O D E S } 											--- **
/** --- 											-----------------------------------------------------										--- **
/** ---																																						--- **
/** ----------------------------------------------------------------------------------------------------------------------- **
/** ----------------------------------------------------------------------------------------------------------------------- **


	Commentaire Graphique :
	-----------------------
	//──┐
	
		ALT+2500 = ─
		ALT+ 191 = ┐


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
	self.items = {};				// OBJECT	:: Liste des objets présente dans l'inventaire
	self.slots = {};				// OBJECT	:: Stockage des objets liés aux slots
	self.sets = {};				// OBJECT	:: Donnée relative au sets
	
	/** Block described properties **/
	// Liste des selecteurs utilisé dans l'application
	self.selectors = {
		inventory: ".gear_panel_inventory_grid",
		slots: ".gear_panel_build_items_slot"
	};
	
	// Différente qualités d'objet par ordre d'index
	self.qualities = ['unset', 'common', 'magic', 'rare', 'elite', 'legendary', 'relic', 'comp', 'enchant'];
	
	// Différente nom de qualités d'objet par ordre d'index
	self.qualities_names = ['Unqualified', 'Common', 'Magic', 'Rare', 'Epic', 'Legendary', 'Relic', 'Component', 'Augment'];
	
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
		attributes_loader: "/XHR/Index/load_attributes.php",
		build_loader:'/XHR/Index/load_build.php',
		build_manager: '/XHR/Index/build_manager.php',
		build_signer: '/XHR/Index/sign.php',
		items_loader: "/XHR/Index/load_items.php",
		item_loader: '/XHR/Index/load_item.php',
		set_loader: '/XHR/Index/load_set.php',
		skill_loader: '/XHR/Index/load_skill.php'
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
				
				document.querySelector("#submitSearch").disabled = false;
			}
		},
		
		/** Méthode de controle du build **/
		controle: function(){
			if(self.build.inputs){
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
			}
		},
		
		/** Liste des slots en cours de chargement. L'initialisation ne peux etre faites tant que des slots sont en cours de chargement**/
		loading: {
			
		}
	};
	
	
	/** ----------------------------------------------------- **
	/** --- Programme de gestion des attributs des objets --- **
	/** ----------------------------------------------------- **/
	self.attributes = function(attID){
		return {
			/** Méthode de chargement des attributs de l'objet demandé **/
			load: function(itemID){
				if(typeof(itemID) !== "number"){
					itemID = parseInt(itemID);
				}
				
				if(!isNaN(itemID)){
					var xQuery = new xhrQuery();
					xQuery.target(self.targets.attributes_loader);
					xQuery.values("item_id="+itemID);
					xQuery.callbacks(function(e){
						try {
							e = JSON.parse(e);
							
							/** Enregistrer tout les attribut **/
							self.items["itm-"+itemID].ATTRIBUTES = e;
							
							
						} catch (err){
							console.error("gear::attributes::load.callback failed on", e, "with error", err);
						}
					});
					xQuery.send();
				}
			},
			
			/** Méthode de composition de structure JSON pour le jeu d'attribut donnée **/
			make: function(attributes_list){
				var children = [];
				
				var basics = {
					"subtitle": "<span>%s</span>% Chance of :",
					"classes": {
						"title": "probability", "group": "probabilities"
					},
					"attributes": [],
					"structure": {name: "ul", classList: ["primaries"], children: []}
				};
				
				var defaults = {
					"subtitle": "<span>%s</span>% Chance of :",
					"classes": {
						"title": "probability", "group": "probabilities"
					},
					"attributes": [],
					"structure": {name: "ul", classList: ["secondaries"], children: []}
				};
				
				var petties = {
					"subtitle": "<span>%s</span>% Chance of :",
					"classes": {
						"title": "probability", "group": "probabilities"
					},
					"attributes": [],
					"structure": {name: "ul", classList: ["petties"], children: []}
				};
				
				var tiers = {
					"subtitle": "(%s) Set",
					"classes": {
						"title": "tier", "group": "bonus"
					},
					"attributes": [],
					"disabled": [],
					"structure": {name: "ul", classList: ["tiers"], children: []}
				};
				
				
				/** Parcourir les attributes **/
				for(var att = 0; att < attributes_list.length; att++){
					var attribut = attributes_list[att];
					
					if(attribut){
						if(attribut.TIER){
							if(!tiers.attributes[attribut.TIER+"P"]) tiers.attributes[attribut.TIER+"P"] = [];
							tiers.attributes[attribut.TIER+"P"].push(attribut);
							tiers.disabled[attribut.TIER+"P"] = attribut.DISABLED;
						} else {
							/** Identifier la cible **/
							var target = (attribut.BASIC) ? basics : ((attribut.PET) ? petties : defaults);
							
							if(!target.attributes[attribut.PROBABILITY+"%"]) target.attributes[attribut.PROBABILITY+"%"] = [];
							
							target.attributes[attribut.PROBABILITY+"%"].push(attribut);
						}
						
					}
				}
				
				/** trier par probabilité (100% en premier) **/
				basics.attributes.sort();
				defaults.attributes.sort();
				petties.attributes.sort();
				tiers.attributes.sort();
				
				
				/** Assemblage en parcourant les différente famille d'attribut **/
				var attr_families = [basics, defaults, petties, tiers];
				
				for(var i in attr_families){
					var family = attr_families[i];
					
					/** Parcourir chaque ensemble d'attributs **/
					for(var e in family.attributes){
						var ensemble = parseInt(e);
						var ensemble_attributes = family.attributes[e];
						var appendTarget = null;
						
						/** Si l'ensemble des attributs doivent etre groupé **/
						// Par probabilité (100)
						// Par tier de set (*)
						if(ensemble < 100) {
							/** Ajouter un titre de niveau **/
							var title = family.subtitle.replace(/%s/i, ensemble);
							var ensemble_title = {name: "li", classList:[family.classes.title], attributes: {"data-value": ensemble}, properties: {innerHTML: title}};
							
							family.structure.children.push(ensemble_title);
							
							
							/** Créer le groupe qui va recevoir les attributs **/
							var group = {
								name: "ul", classList: [family.classes.group], children: []
							};
							
							family.structure.children.push(group);
							
							
							/** Ajustements **/
							//--- Cas des tiers bonus de set
							//──┐ Si dans le groupe un disabled, alors le tier les aussi
							if(family.disabled && family.disabled[ensemble+"P"]) ensemble_title.classList.push("disabled");
							
							
							
							/** Définir le groupe comme la cible des prochain ajouts d'attributs **/
							appendTarget = group.children;
						} else {
							appendTarget = family.structure.children;
						}
						
						
						/** Parcourir les attributs pour les ajouter **/
						for(var a in ensemble_attributes){
							var attribut_data = ensemble_attributes[a];
							var attribut_entry = {name: "li", classList: [], attributes: {
								"data-ower-id": attribut_data.OWNER
							}, properties: {
								innerHTML: attribut_data.ATTRIBUT
							}};
							
							
							/** Ajustements **/
							//--- Cas des attributs bonus de set 
							//──┐ Si attribut est désactivé (attribut non acquis)
							if(attribut_data.DISABLED) attribut_entry.classList.push("disabled");
							
							/** Ajouter la structure générée **/
							appendTarget.push(attribut_entry);
						}
					}
				}
				
				
				/** Finalisation :: Ajouter Envoyer si des des attributs **/
				//--- Basiques
				if(basics.structure.children.length) children.push(basics.structure);
				//--- Defaults
				if(defaults.structure.children.length) children.push(defaults.structure);
				//--- Petties
				if(petties.structure.children.length) {
					// Ajouter le titre "petties"
					children.push({name: "h4", properties: {innerHTML: "Bonus to All Pets"}});
					// Ajouter la liste d'attribut
					children.push(petties.structure);
				}
				//--- Tiers
				if(tiers.structure.children.length) children.push(tiers.structure);
				
				return children;
			}
		};
	};
	
	
	/** ---------------------------------------- **
	/** --- Programmes de gestion des objets --- **
	/** ---------------------------------------- **/
	self.item = function(){
		return {
			/** Méthode pour figé la fenetre pour pouvoir la manipuler **/
			activator: function(){
				self.windows.activated = (self.windows.activated) ? false : true;
				
				var windows = document.querySelectorAll('.item_window');
				
				(self.windows.activated) ? windows.forEach(function(el){el.classList.add('active');}) : windows.forEach(function(el){el.classList.remove('active');});
			},
			
			/** Méthode de fermeture des fenêtre d'objet **/
			hide: function(){
				if(!self.windows.activated){
					/** Récupération de toutes le fenêtre pour les supprimer **/
					var windows = document.querySelectorAll('.item_window');
					
					for(var w = 0; w < windows.length; w++){
						windows[w].parentNode.removeChild(windows[w]);
					}
					
					/** Flusher les donnée mémorisée **/
					self.windows.id = null;
					self.windows.mode = null;
					self.windows.element = null;
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
									"data-item-attachment": item.ATTACHMENT,
									"data-item-width": item.WIDTH,
									"data-item-height": item.HEIGHT,
									"draggable": "true",
								},
								properties: {
									ondragstart: function(id, ev){
										/** Spécifié les donnée transféré **/
										ev.dataTransfer.setData("text", '{"item_id": '+id+'}');
										
										/** Montrer les slots compatible **/
										self.slot(ev.target.parentNode).show();
									}.bind(null, item.ID),
									ondragend: function(ev){self.slot(ev.target.parentNode).hide();},
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
											src: "/Images/Items/"+item.TAG_NAME+".png",
											onerror: function(){
												this.src = '/Images/Items/NotFound.png';
												this.parentNode.setAttribute('data-show-tag-item', true);
												this.setAttribute('data-show-tag-item', true);
											}
										}
									}
								]
							}));
							
							/** Stocker les données **/
							self.item().store(item);
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
					/** Déclaration des variables **/
					var ID = item.getAttribute("data-item-id");
					var show_tag_item = item.getAttribute("data-show-tag-item");
						show_tag_item = (show_tag_item) ? show_tag_item.toLowerCase() : null;
					
					var data = self.items["itm-"+ID];	// Donnée de l'objet de base
					var attributes_list = Array();
					var max_width = 0;						// Largeur de l'objet le plus large
					
					/** Si l'objet à changer ou le mode, alors créer une nouvelle fenêtre **/
					if(self.windows.id !== ID || self.windows.mode !== mode){
						/** Fermer les éventuelles fenêtre d'ouverte **/
						self.item().hide();
						
						
						/** Références**/
						//──┐ Titre principal
						var title = {name: "h3", classList: ["item_name"], properties: {}, children: []};
						
						//──┐ Description
						var description = {name: "p", classList: [], properties: {textContent: data.DESCRIPTION}};
						if(data.DESCRIPTION) description.classList.push("not_empty");
						
						//──┐ Sous Titre
						var subtitle = {name: "h4", classList: ["subtitle"], properties: {textContent: self.qualities_names[data.QUALITY]+" "+data.TYPE_NAME}, children: []};
						
						//──┐ Attribut principaux de l'objet (Primaries, Attribut, Petties)
						var attributes = {name: "div", children: []};
						
						//──┐ Sort de l'objet
						var skill = {name: "div", children: []};
						
						//──┐ Ensemble d'objet
						var set = {name: "div", children: []};
						
						//──┐ Composant Attaché
						var component ={name: "div", classList: [], children: []};
						
						//──┐ Augment Attaché
						var enchant = {name: "div", classList: [], children: []};
						
						//──┐ Pré-requis pour porter l'objet
						var requirements = {name: "div", classList: [], children: []};
						
						//──┐ Removes
						var removes = {name: "div", classList: ["removes"], children: []};
						
						
						/** Donnée commune entre les modes **/
						var window = {
							name: "div",
							classList: [mode+"_item", "item_window", "unsized"], //"quality_"+self.qualities[data.QUALITY]
							attributes: {style: "left: "+(e.pageX + 32)+"px; top: "+e.pageY+"px;"},
							children: [
								title, description, subtitle, attributes, skill, set, component, enchant, requirements, removes
							]
						};
						
						
						/** Le contenu de la fenêtre dépend du mode **/
						// Si c'est depuis l'inventaire, seul un objet est visualisé
						// Si c'est depuis l'équipement, c'est une combinaison d'objet appliqué au slot (data from slot)
						switch(mode){
							case "inventory":
								// La fenetre à la qualité de l'objet
								window.classList.push("quality_"+self.qualities[data.QUALITY]);
								
								// Titre simple
								title.properties.textContent = data.NAME;
								
								// Définition de la liste des attributes
								attributes_list = attributes_list.concat(data.ATTRIBUTES);
							break;
							
							case "gear":
								// Initialisation des variables 
								var slot;			// Nom du slot
								var prefix_id;		// Identifiant du prefix
								var item_id;		// Identifiant de l'objet de base
								var suffix_id;		// Identifiant du suffix
								var comp_id;		// Identifiant du composant
								var enchant_id;	// Identifiant de l'augment
								
								var item_name;		// Nom de l'objet
								var prefix_name;	// Nom du prefix
								var suffix_name;	// Nom du suffix
								
								
								// Initialisation des variables
								slot = self.slot().getName(item);
								item_id = self.slots[slot].ITEM;
								prefix_id = self.slots[slot].PREFIX;
								suffix_id = self.slots[slot].SUFFIX;
								comp_id = self.slots[slot].COMP;
								enchant_id = self.slots[slot].ENCHANT;
								
								item_name = "";
								prefix_name = "";
								suffix_name = "";
								
								
								// La fenetre à la qualité du slot
								window.classList.push("quality_"+item.getAttribute("data-item-quality"));
								
								
								// Parcourir l'ensemble des objets attaché au slot consulté
								for(var attachment in self.slots[slot]){
									var itmID = self.slots[slot][attachment];
									
									if(!parseInt(itmID)) continue;
									
									var itmData = self.items["itm-"+itmID];
									
									/** Traitement spécifique aux attachements **/
									switch(attachment){
										case "ITEM":
											// Nom de l'objet
											item_name = data.NAME;
											
											// Ajouter les attributs à la liste
											//--- AJouter l'ID du propriété de l'attribut pour mise en évidence
											itmData.ATTRIBUTES.forEach(function(el){
												el.OWNER = itmID;
											});
											//--- Ajout des attributs
											attributes_list = attributes_list.concat(itmData.ATTRIBUTES);
										break;
										case "PREFIX":
											// Nom du prefix
											prefix_name = itmData.NAME+" ";
											
											// Ajouter les attributs à la liste
											//--- AJouter l'ID du propriété de l'attribut pour mise en évidence
											itmData.ATTRIBUTES.forEach(function(el){
												el.OWNER = itmID;
											});
											//--- Ajout des attributs
											attributes_list = attributes_list.concat(itmData.ATTRIBUTES);
										break;
										case "SUFFIX":
											// Nom du suffix
											suffix_name = " "+itmData.NAME;
											
											// Ajouter les attributs à la liste
											//--- AJouter l'ID du propriété de l'attribut pour mise en évidence
											itmData.ATTRIBUTES.forEach(function(el){
												el.OWNER = itmID;
											});
											//--- Ajout des attributs
											attributes_list = attributes_list.concat(itmData.ATTRIBUTES);
										break;
										case "COMP":
											// Appliqué une classe 
											component.classList.push("component");
											
											// Nom du composent en titre
											component.children.push({
												name: "h3", properties: {textContent: itmData.NAME}
											});
											
											// Attribut du composant
											//--- AJouter l'ID du propriété de l'attribut pour mise en évidence
											itmData.ATTRIBUTES.forEach(function(el){
												el.OWNER = itmID;
											});
											//--- Ajout des attributs
											component.children.push({
												name: "div", children: self.attributes().make(itmData.ATTRIBUTES)
											});
										break;
										case "ENCHANT":
											// Appliqué une classe 
											enchant.classList.push("enchant");
											
											// Nom du composent en titre
											enchant.children.push({
												name: "h3", properties: {textContent: itmData.NAME}
											});
											
											// Sous-titre "The $faction Augment"
											
											// Attribut du composant
											//--- AJouter l'ID du propriété de l'attribut pour mise en évidence
											itmData.ATTRIBUTES.forEach(function(el){
												el.OWNER = itmID;
											});
											//--- Ajout des attributs
											enchant.children.push({
												name: "div", children: self.attributes().make(itmData.ATTRIBUTES)
											});
											
											// Modifier le sous-titre en ajoutant "Augmented..."
											subtitle.properties.textContent = "Augmented "+subtitle.properties.textContent;
										break;
									}
									
									/** Traitement commun **/
									removes.children.push({
										name: "a", properties: {
											href: "#",
											title: "Remove "+attachment.toLowerCase(),
											onclick: function(slt, atcht){
												self.slot().clear(slt, atcht);
											}.bind(self, slot, attachment),
											onmouseover: function(owner){
												self.item().highlight(owner).on();
											}.bind(self, itmID),
											onmouseout: function(owner){
												self.item().highlight(owner).off();
											}.bind(self, itmID)
										}, 
										children: [{
											name: "img", classList: ["size"+itmData.WIDTH+"x"+itmData.HEIGHT, "quality_"+self.qualities[itmData.QUALITY]],
											attributes: {
												"data-item-id": itmID,
												"data-item-quality": self.qualities[itmData.QUALITY],
												"data-item-attachment": attachment.toLowerCase(),
												"src": "/Images/Items/"+itmData.TAG_NAME+".png"
											},
											properties: {
												onerror: function(){
													this.src = "/Images/Items/NotFound.png";
												}
											}
										}]
									});
									
									
									/** Déterminé l'objet le plus large pour appliquer l'offset qui convient **/
									max_width = (itmData.WIDTH > max_width) ? itmData.WIDTH : max_width;
								}
								
								// Application du titre
								title.properties.textContent = prefix_name+item_name+suffix_name;
							break;
						}
						
						
						/** Enregistrer l'offset **/
						window.attributes["data-window-offset"] = (32 + (32 * max_width));
						
						/** Ajustement finaux **/
						if(show_tag_item === "true") title.properties.textContent += " "+data.TAG_NAME;
						
						/** Ajout des attributs **/
						attributes.children = self.attributes().make(attributes_list);
						
						/** Ajout du skill de l'objet **/
						if(data.SKILL) skill.children = self.skill().make(data.SKILL);
						
						/** Ajout du Set de l'objet **/
						if(data.SET) set.children = self.set().make(data.SET);
						
						/** Ajout de l'enchant attaché **/
						
						/** Ajout des requiremennts **/
						
						/** Création de la fenêtre **/
						window = HTML().compose(window);
						document.body.appendChild(window);
						
						
						/** Déteriner la largeur de la fenêtre et la fixé en dur pour les descriptions **/
						window.style.width = window.offsetWidth+"px";
						window.classList.remove("unsized");
						
						
						/** Mémorisation des données **/
						self.windows.id = ID;
						self.windows.element = window;
						self.windows.mode = mode;
					} else {
						window = self.windows.element;
					}
					
					
					/** Positionner la fenetre **/
					var offset_x = parseInt(window.getAttribute("data-window-offset"));
					
					//window.setAttribute("style", "left: "+(e.pageX + offset_x)+"px; top: "+(e.pageY + (-16))+"px;");
					window.style.left = (e.pageX + offset_x)+"px";
					window.style.top = (e.pageY + (-16))+"px";
					
					
				//		for(var att in data.ATTRIBUTES){
				//			var attribut = data.ATTRIBUTES[att];
				//			
				//			// Ne pas attacher les attribut basic (primaire)
				//			if(!attribut.BASIC){
				//				attributes.push({
				//					name: "li", properties: {innerHTML: attribut.ATTRIBUT}
				//				});
				//			}
				//		}
				//		
				//		var edit_pattern = /#!edit$/gi;
				//		
				//		if(edit_pattern.test(document.location.href)){
				//			structure.children[1].children.push({
				//				name: "a", classList: ["editer"],
				//				properties: {
				//					textContent: "Edit",
				//					onclick: function(){},
				//					href: "/manage/edit/items/"+ID,
				//					target: data.NAME
				//				}
				//			});
				//		}
				}
			},
			
			/** Méthode de stockage des donnée de l'objet **/
			store: function(item){
				/** Mémoriser l'objet si inconnu **/
				if(self.items['itm-'+item.ID] === undefined){
					self.items['itm-'+item.ID] = {
						'ATTRIBUTES': null,
						'SKILL': null
					};
					
					// Intégration automatique des champs
					for(var field in item){
						self.items['itm-'+item.ID][field] = item[field];
					}
				}
				
				/** Charger les attributs de l'objet si inconnus **/
				if(self.items["itm-"+item.ID].ATTRIBUTES === null){
					self.attributes().load(item.ID);
				}
				
				/** Charger les sorts s'il y a des sort attaché et si inconnu **/
				if(parseInt(item.SKILLED) && self.items["itm-"+item.ID].SKILL === null){
					self.skill().load(item.ID);
				}
				
				/** Chargé le set si appartient à un set et si inconnu **/
				if(item.SET && !self.sets["set-"+item.SET]){
					self.set().load(item.SET);
				}
			},
			
			/** Méthode pour mettre en avant les attributs concerné **/
			highlight: function(owner){
				/** Récupération de tous les éléments concerné **/
				var elements = self.windows.element.querySelectorAll("[data-owner-id='"+owner+"']");
				
				return {
					on: function(){
						elements.forEach(function(el){
							el.classList.add("highlight");
						});
					},
					off: function(){
						elements.forEach(function(el){
							el.classList.remove("highlight");
						});
					}
				};
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
											/** Identifier le slot pour stockage d'informations **/
											var slot_base_name = slot.substr(0, slot.lastIndexOf("_"));
											var slot_spec = slot.substr(slot.lastIndexOf("_") + 1);
											
											/** Ajouter une référence de chargement **/
											self.build.loading[slot] = true;
											
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
							/** Parser la chaine JSON **/
							e = JSON.parse(e);
							
							/** Stocker les données **/
							self.item().store(e);
							
							/** Récupération & création des éléments **/
							var host_selector = slot.toLowerCase();
							var index = host_selector.lastIndexOf("_");
							
							host_selector = host_selector.substr(0, index);
							
							var host = document.querySelector('.'+host_selector);
							
							var item = HTML().compose({
								name: "img",
								classList: ['size'+e.WIDTH+'x'+e.HEIGHT],
								attributes: {
									"data-item-id": e.ID,
									"data-item-quality": self.qualities[e.QUALITY],
									"data-item-attachment": e.ATTACHMENT,
									
								},
								properties: {
									src: "/Images/Items/"+e.TAG_NAME+'.png',
									onerror: function(){
										this.src = '/Images/Items/NotFound.png';
										this.setAttribute('data-show-tag-item', true);
									}
								}
							});
							
							/** Placer l'objet dans son slot **/
							self.slot().placeItem(host, item, e);
						} catch (err){
							console.log("gear::load::slot.callback failed on", e, "with error", err);
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
	
	
	/** ----------------------------------------------------------- **
	/** --- Programme de gestions des ensembles d'objets (sets) --- **
	/** ----------------------------------------------------------- **/
	self.set = function(){
		return {
			/** Méthode de chargement du set indiqué **/
			load: function(setId){
				if(typeof(setId) !== "number") setId = parseInt(setId);
				
				if(!isNaN(setId)){
					var xQuery = new xhrQuery();
					xQuery.target(self.targets.set_loader);
					xQuery.values("set_id="+setId);
					xQuery.callbacks(function(e){
						try {
							e = JSON.parse(e);
							
							e.SKILL = null;
							e.DISABLED = true;
							
							self.sets["set-"+e.ID] = e;
							
							self.skill().load(e.ID, 'set');
						} catch (err){
							console.log("gear::set::load.callback failed on", e, "with error", err);
						}
					});
					xQuery.send();
				}
			},
			
			/** Méthode de génération de la partie set pour la fenetre de visualisation **/
			make: function(setId){
				var data = self.sets["set-"+setId];
				var tiers = 0;
				
				/** Set title **/
				var title = {name: "h4", classList: ["set"], properties: {innerHTML: data.NAME}};
				
				/** Description du set **/
				var description = {name: "p", classList: [], properties: {innerHTML: data.DESCRIPTION}};
				if(data.DESCRIPTION) description.classList.push("not_empty");
				
				/** Set list **/
				var list = {name: "ul", classList: ["set"], children: []};
				
				//--- Parcourir la liste des objets pour créer une entrée dans un état actif/désactivé qui convient
				for(var idx in data.ITEMS.IDS){
					//──┐ Ligne d'objet de set
					var set = {name: "li", classList: ["disabled"], properties: {innerHTML: data.ITEMS.NAMES[idx]}};
					
					//──┐ Déterminer si l'objet est équipé
					var id = data.ITEMS.IDS[idx];
					for(var slot in self.slots){
						if(self.slots[slot].ITEM){
							if(parseInt(self.slots[slot].ITEM) === id){
								// Remove "disabled"
								set.classList = [];
								// Element trouvé :
								tiers++;
								break;
							}
						}
					}
					
					//──┐ Ajouter l'objet de set dans la liste
					list.children.push(set);
				}
				
				/** Set Attributes **/
				//--- Parcourir les attributs pour définir l'état disable pour les tiers supérieur à celui identifier précédement
				data.ATTRIBUTES.forEach(function(el){
					el.DISABLED = (el.TIER > tiers) ? true : false;
				});
				
				//--- Génération des attributes
				var attributes = {name: "ul", classList: [], children: self.attributes().make(data.ATTRIBUTES)};
				
				/** Set Skill **/
				//--- Génération de l'ensemble de sort
				var skill = {name: "div", classList: [], children: self.skill().make(data.SKILL)};
				
				//--- Si l'ensemble n'est pas complet, désactivé le skill
				if(data.ITEMS.IDS.length > tiers) skill.classList.push("disabled");
				
				return [title, description, list, attributes, skill];
			}
		};
	};
	
	
	/** --------------------------------------- **
	/** --- Programme de gestions des sorts --- **
	/** --------------------------------------- **/
	self.skill = function(){
		return {
			/** Méthode de chargement du sort de l'objet indiqué **/
			load: function(ID, attachment){
				/** Gérer l'attachement du skill (item | set) **/
				if(!attachment) attachment = "item";
				
				/** Traiter l'ID fournis **/
				if(typeof(ID) !== "number") ID = parseInt(ID);
				
				if(!isNaN(ID)){
					var xQuery = new xhrQuery();
					xQuery.target(self.targets.skill_loader);
					xQuery.values("id="+ID);
					xQuery.values("attachment="+attachment);
					xQuery.callbacks(function(e){
						try {
							e = JSON.parse(e);
							e.ATTACHMENT = attachment;
							
							switch(attachment){
								case 'item':
									self.items["itm-"+ID].SKILL = e;
								break;
								case 'set':
									self.sets["set-"+ID].SKILL = e;
								break;
							}
						} catch (err){
							console.log("gear::skills::load.callback failed on", e, "with error", err);
						}
					});
					xQuery.send();
				}
			},
			
			/** Méthode de génération de la partie Skill pour la fenetre de visualisation **/
			make: function(skill){
				var children = [];
				
				/** Granted Skill si c'est un item **/
				if(skill.ATTACHMENT === "item") children.push({name: "h4", properties: {innerHTML: "Granted Skills"}});
				
				/** Titre du sort **/
				children.push({name: "h4", classList: ["skill"], properties: {innerHTML: skill.NAME}});
				
				/** Description du sort **/
				var description = {name: "p", classList: [], properties: {innerHTML: skill.DESCRIPTION}};
				if(skill.DESCRIPTION) description.classList.push("not_empty");
				children.push(description);
				
				/** Attributs du sort **/
				children.push({name: "ul", children: self.attributes().make(skill.ATTRIBUTES)});
				
				return children;
			}
		};
	};
	
	
	/** ------------------------------------------------ **
	/** --- Programme des gestion des slots d'objets --- **
	/** ------------------------------------------------ **/
	self.slot = function(item){
		/** Déclaration des variables **/
		// Type de l'objet
		var item_type = null; 
		var item_attachment = null;
		
		// Liste des slots de l'application 
		var slots = document.querySelectorAll(self.selectors.slots);
		
		/** Controle de l'argument ITEM (objet manipuler) **/
		if(item !== undefined){
			item_type = item.getAttribute("data-item-type");
			item_attachment = item.getAttribute("data-item-attachment");
		}
		
		return {
			/** Méthode de suppression des objet attaché **/
			clear: function(slot, attachement, e){
				/** Arguments :
				//
				//   Slot        :: String :: SLOT_XXX
				//   Attachement :: String :: ITEM | PREFIX | SUFFIX | COMP | ENCHANT
				//   e           :: Event  ::
				//
				/** Déclaration des variables **/
				var htmlSlot; // HTMLDivElement   :: Objet HTML correspondant au slot
				var rebuild;  // Bollean          :: Indicateur de reconstruction de fenêtre
				var image;    // HTMLImageElement :: Image à supprimer
				var position; // String           :: Précédente position de la fenetre (style)
				
				
				/** Initialisation des variables **/
				htmlSlot = document.querySelector(".gear_panel_build_items_slot."+slot.toLowerCase());
				rebuild = true;
				
				
				/** Actions en fonctions de l'attachement **/
				switch(attachement){
					case "ITEM":
						//--- Supprimer la qualité appliqué
						htmlSlot.classList.remove("quality_"+htmlSlot.getAttribute("data-item-quality"));
						htmlSlot.setAttribute("data-item-quality", "");
						
						//--- Supprimer les évenements attaché
						htmlSlot.onmousemove = null;
						htmlSlot.onmouseout = null;
						htmlSlot.onclick = null;
						
						//--- Le slot n'est plus attachable
						htmlSlot.setAttribute("data-attachable", "false");
						
						//--- Pas de reconstruction de la fenêtre, puisque cloture sur le champs
						rebuild = false;
						self.item().activator();
						self.item().hide();
					break;
					case "PREFIX":
						console.log("clear attachement :: PREFIX");
						//--- Utiliser la méthode de placement pour reclaculer les paramètres du slots
						//self.slot().placeItem();
					break;
					case "SUFFIX":
						console.log("clear attachement :: SUFFIX");
						//--- Utiliser la méthode de placement pour reclaculer les paramètres du slots
						//self.slot().placeItem();
					break;
					case "COMP":
						console.log("clear attachement :: COMP");
					break;
					case "ENCHANT":
						console.log("clear attachement :: ENCHANT");
					break;
				}
				
				
				/** Supprimer l'image correspondant (s'il y en à une: ITEM, COMPO) **/
				image = htmlSlot.querySelector("img[data-item-attachment='"+attachement.toLowerCase()+"']");
				
				if(image){
					htmlSlot.removeChild(image);
				}
				
				
				/** Supprimer la donnée stockée **/
				self.slots[slot][attachement] = 0;
				
				/** Mettre à jour l'input caché correspondant **/
				self.slot().input(htmlSlot, attachement.toLowerCase(), 0);
				
				/** Toute modifications implique un controle de build **/
				self.build.controle();
				
				/** Reconstruire la fenetre au meme endroit **/
				if(rebuild){
					// Stocker le positionnement (en simulant l'event)
					position = document.querySelector("div.item_window").getAttribute("style");
					
					// reconstruire la fenetre
					self.item().activator();
					self.item().hide();
					self.item().show(htmlSlot, 'gear', {pageX: 0, pageY: 0});
					self.item().activator();
					
					// Réappliquer le positionnement
					document.querySelector("div.item_window").setAttribute("style", position);
				}
			},
			
			/** Méthode de dépose de l'objet dans le slot **/
			drop: function(slot, e){
				/** Empecher l'evenement par default de se produire (firefox) **/
				e.preventDefault();
				
				/** Récuéprer les données déposée **/
				var data = JSON.parse(e.dataTransfer.getData("text"));
				var item_id = data.item_id;
				
				var src = document.querySelector(".gear_panel_inventory_grid img[data-item-id='"+item_id+"']");
				var img = src.cloneNode(true);
				
				self.slot().placeItem(slot, img, data);
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
				/** Parcourir tous les slots **/
				for(var i = 0; i < slots.length; i++){
					var slot = slots[i];
					var attachable = slot.getAttribute("data-attachable");
						attachable = (attachable.toLowerCase() === "true") ? true: false;
					var slot_quality = slot.getAttribute("data-item-quality");
					
					/** Si slot masqué, suivant **/
					if(slot.getAttribute('hidden') === 'true'){
						continue;
					};
					
					
					/** Gestion de l'attachement **/
					// Si item_attachment !== item
					if(item_attachment !== "item"){
						// Si le slot est attachable, controler les attachements
						if(attachable){
							// Si attachement = prefix/suffix, vérifier la qualité de l'objet
							if(["prefix", "suffix"].lastIndexOf(item_attachment) >= 0 ){
								// Si qualité de l'objet vaut elite ou legendary, prefix/suffix non attachable
								if(["elite", "legendary"].lastIndexOf(slot.getAttribute("data-item-quality")) >= 0){
									continue;
								}
							}
						} else {
							continue;
						}
					}
					
					
					
					/** Récupération des types d'objet admis **/
					var accept_item = slots[i].getAttribute('data-accept-item');
						accept_item = accept_item.split(',');
					
					var accepted = accept_item.lastIndexOf(item_type);
					
					if(accepted >= 0){
						slot.classList.add('droppable');
						
						slot.ondrop = function(ev){self.slot().drop(this, ev);}.bind(slot);
						slot.ondragover = function(ev){ev.preventDefault(); return false;};
						slot.ondragenter = function(){self.slot().overlay(this).on();}.bind(slot);
						slot.ondragleave = function(){self.slot().overlay(this).off();}.bind(slot);
					} else {
						slot.classList.remove('droppable');
						
						slot.ondrop = "";
						slot.ondragover = "";
						slot.ondragenter = "";
						slot.ondragleave = "";
					} 
				}
			},
			
			/** Méthode pour récupérer le nom de base du slot **/
			getName: function(slot){
				var slot_pattern = /^slot_/i;
				
				for(var c = 0; c < slot.classList.length; c++){
					if(slot_pattern.test(slot.classList[c])){
						return slot.classList[c].toUpperCase();
						//var classe = slot.classList[c];
						//
						//var base_name = classe.substr(0, classe.lastIndexOf("_"));
						//var spec = classe.substr(classe.lastIndexOf("_") + 1);
						//
						//return {
						//	base_name: base_name,
						//	spec: spec
						//};
					}
				}
			},
			
			/** Méthode pour appliquer l'objet au slot (data facultatif) **/
			placeItem: function(slot, item, data){
				/** Arguments : **/
				//
				//   slot :: HTMLDivElement   :: Slot cible
				//   item :: HTMLImageElement :: Image de travail
				//
				/** Récuéprer les données déposée **/
				var item_id = item.getAttribute("data-item-id");
				
				//──┐ String : common | magic | rare | elite | legendary
				var item_quality = item.getAttribute("data-item-quality");
				//──┐ String : item | prefix | suffix | comp | enchant
				var item_attachment = item.getAttribute("data-item-attachment");
				var show_tag_item = item.getAttribute("data-show-tag-item");
				
				//──┐ String : HTMLDListElement >> slot_xxx 
				var slot_name = self.slot().getName(slot);
				var slot_quality = slot.getAttribute("data-item-quality");
				
				var prefix = (self.slots[slot_name]) ? ((self.slots[slot_name].PREFIX) ? self.slots[slot_name].PREFIX : 0) : 0;
				var suffix = (self.slots[slot_name]) ? ((self.slots[slot_name].SUFFIX) ? self.slots[slot_name].SUFFIX : 0) : 0;
				
				
				/** Retirer l'ancienne class CSS s'il s'agit d'un objet ou affix (ignorer comp/enchant) **/
				if(["item", "prefix", "suffix"].lastIndexOf(item_attachment) >= 0){
					slot.classList.remove("quality_"+slot_quality);
				}
				
				/** Traitement en fonctons des attachements **/
				switch(item_attachment){
					// Attachement d'un objet de base
					case "item":
						// Retirer l'ancienne image
						var old_item = slot.querySelector("img[data-item-attachment=item]");
						if(old_item) slot.removeChild(old_item);
						
						// Intégrer la nouvelle image au slot
						slot.appendChild(item);
						
						//--- S'il s'agit d'un objet de qualité elite ou legendaire, reset AFFIXES
						if(self.qualities.lastIndexOf(item_quality) > self.qualities.lastIndexOf("rare")){
							if(self.slots[slot_name] && self.slots[slot_name].PREFIX) self.slots[slot_name].PREFIX = 0;
							if(self.slots[slot_name] && self.slots[slot_name].SUFFIX) self.slots[slot_name].SUFFIX = 0;
							
							suffix = prefix = 0;
						}
						
						// Définition de la classe
						//--- Si pas d'Affix, l'objet fais fois
						if(!prefix && !suffix){
							slot.classList.add("quality_"+item_quality);
							slot.setAttribute('data-item-quality', item_quality);
						}
						//--- Sinon, utiliser la qualité la plus fort (cf après switch)
						
						// Définition des attributs du slot
						slot.setAttribute('data-item-id', item_id);
						slot.setAttribute('data-show-tag-item', show_tag_item);
						slot.setAttribute('data-attachable', "true");
						
						// Attachement des evenements
						slot.onmousemove = function(e){self.item().show(this, 'gear', e);};
						slot.onmouseout = function(e){self.item().hide();};
						slot.onclick = function(e){self.item().activator();};
					break;
					
					// Attachement d'un affix
					case "prefix":
						prefix = item_id;
						
					break;
					case "suffix":
						suffix = item_id;
					break;
						
					// Attachement d'un composant
					case "comp":
						// Retirer l'ancinne image du composant
						var old_component = slot.querySelector("img[data-item-attachment=comp]");
						if(old_component) slot.removeChild(old_component);
						
						// Ajouter le nouveau composant
						slot.appendChild(item);
						//slot.appendChild(HTML().compose(
						//	
						//));
					break;
					
					// Attachement d'un augment
					case "enchant":
					break;
				}
				
				
				/** Définition de la classe CSS si des affixes attaché **/
				// Si il n'y à qu'un seul affix, alors prendre celui dispo
				var quality = 0;
				
				if(!prefix ^ !suffix){
					var ref = prefix | suffix;
					
					quality = self.items["itm-"+ref].QUALITY;
					
					slot.classList.add("quality_"+self.qualities[quality]);
					slot.setAttribute('data-item-quality', self.qualities[quality]);
				} 
				// Si y en à deux, alors comparer
				else if (prefix && suffix){
					var prefix_quality = 0;
					var suffix_quality = 0;
					
					if(prefix) prefix_quality = self.items["itm-"+prefix].QUALITY;
					if(suffix) suffix_quality = self.items["itm-"+suffix].QUALITY;
					
					quality = Math.max(prefix_quality, suffix_quality);
					
					slot.classList.add("quality_"+self.qualities[quality]);
					slot.setAttribute('data-item-quality', self.qualities[quality]);
				}
				
				
				/** Mise à jour des données interne **/
				if(!self.slots[slot_name]) self.slots[slot_name]= {};
				self.slots[slot_name][item_attachment.toUpperCase()] = item_id;
				
				/** Mise à jour de l'input type hidden correspondant **/
				self.slot().input(slot, item_attachment, item_id);
				
				/** Déclencher un controle de build **/
				self.build.controle();
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