//#!/compile = true
/** ----------------------------------------------------------------------------------------------------------------------- ** 
/** ----------------------------------------------------------------------------------------------------------------------- ** 
/** ---																																						--- **
/** --- 															------------------------															--- **
/** ---																{ inventory.js }																	--- **
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
		
		Cette fonction à pour rôle de ranger les objets dans la grille de l'inventaire


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
function inventory(){
	/** Déclaration des variables **/
	var slot_size;
	var matrix;
	var new_row;
	var matrix_index;
	var gear_panel;
	var gear_panel_inventory;
	var x_slots;
	var min_size_pattern;
	var items;
	var last_full_row;
	
	
	
	/** Initialisation des variables **/
		// Taille d'une case d'inventaire en pixel 
		slot_size = 32;
		
		// Représentation de l'occupation des case de l'inventaire 
		matrix = [];
		
		// Panneau principal contenant l'état d'affichage de l'inventaire
		gear_panel = document.querySelector('.gear_panel');
		
		// Panneau contenant les objets trouvé et affiché
		gear_panel_inventory = gear_panel.querySelector('.gear_panel_inventory_grid');
		
		// Déteriner le nombre de slots disponible horizontalement
		min_size_pattern = /stats_fold/g;
		
		x_slots = (min_size_pattern.test(gear_panel.className)) ? 12 : 20;
		
		// Ajouter une ligne de disponibilité à la matrice
		new_row = new Array(x_slots);
		matrix.push(new_row.fill(0, 0));
		
		// Récupération des items de l'inventaire 
		items = gear_panel_inventory.querySelectorAll('.gear_panel_inventory_grid_item');
		
		// La première ligne pleine n'existe pas au début donc est à -1
		last_full_row = -1;
	
		//console.log('Items to process', items);
	
	
	
	/** Arrangement des objets **/
		// Parcourir chaque objet
		for(var i = 0; i < items.length; i++){
			// Objet en cours
			var item = items[i];
			
			// Propriété de l'objet
			var width = parseInt(item.getAttribute('data-item-width'));
			var height = parseInt(item.getAttribute('data-item-height'));
			
			// Vérifier que la matrice est suffisamment grande pour accueillir l'objet en cours
				// Chercher l'index de la la première ligne vide
				var first_empty_row = 0;
				
				for(var a = (last_full_row + 1); a < matrix.length; a++){
					var not_empty = false;
					var full = true;
					
					// Analyser les cases 
					for(var c = 0; c < matrix[a].length; c++){
						// Si on trouve un 1, la ligne est rompue (pas vide)
						if(matrix[a][c] !== 0){
							not_empty = true;
						}
						
						// Si on trouve un 0, la ligne n'est pas pleine
						if(matrix[a][c] !== 1){
							full = false;
						}
					}
					
					// Si la ligne est pleinen on stocke pour optimisation
					if(full){
						last_full_row = a;
					}
					
					// Si la ligne est rompu, c'est au minimum la ligne suivante
					if(not_empty){
						first_empty_row = a + 1;
					} else {
						first_empty_row = a;
						break;
					}
				}
				
			
				//console.log('First Empty row : ', first_empty_row);
				// Vérifier que l'objet peu s'y inséré
				if((first_empty_row + height) > matrix.length){
					var delta = (first_empty_row + height) - matrix.length;
					
					//console.log('Required row :', (first_empty_row + height), 'Matrix Size', matrix.length, 'delta', delta);
					
					for(var d = 0; d < delta; d++){
						var extend_row = new Array(x_slots);
						matrix.push(extend_row.fill(0, 0));
					}
					
					//console.log('Matrix', matrix, 'size', matrix.length);
				}
				
				
			// Chercher les coordonnées d'insertion de l'objet
				// Initialisation
				var x = 0;
				var y = 0;
				
				// Parcourir la matrice à la recherche de place
					// Parcourir les lignes
					for(var mr = 0; mr < matrix.length; mr++){
						var break_required = false;
						
						//console.log('matrix row', mr, matrix[mr]);
						
						// Prcourir les case 
						for(var mc = 0; mc < matrix[mr].length; mc++){
							var slot_found = false;
							
							// Chercher une case vide pour analyse
							if(matrix[mr][mc] === 0 && (mc + width) <= matrix[mr].length){
								// Vérifier l'espace disponible
									// Checker la disponibilité verticale requise
									for(var cy = 0; cy < height; cy++){
										var analyse_broken = false;
										
										// Checker la disponibilité horizontale requise
										for(var cx = 0; cx < width; cx++){
											// Si la moindre case pleine, impossible d'inséré par rapport a la case vide identifié
											if(matrix[mr+cy][mc+cx] !== 0){
												analyse_broken = true;
												break;
											}
										}
										
										// Si l'analyse à été interrompu, on s'arrête ici pour cette case vide
										if(analyse_broken){
											break;
										}
									}
									
									// Si pas d'interuption, alors on peu inséré l'objet
									if(!analyse_broken){
										slot_found = true;
									}
							} 
							
							// Si le slot found est toujours vrai, il peu s'inséré, on sauvegarde les données et on s'arrête
							if(slot_found){
								x = mc;
								y = mr;
								
								break_required = true;
								break;
							}
						}
						
						// Si slot found, arrêt demandé 
						if(break_required){
							break;
						}
					}
					
				//console.log('Coords found', x, y);
				
				
				// Positionner l'objet
				var left = x * slot_size;
				var top = y * slot_size;
				
				item.setAttribute('style', 'left: '+left+'px; top: '+top+'px;');
				
				// Mise à jour de la matrice
					// Remplir sur l'axe Y
					for(var my = 0; my < height; my++){
						// Remplir sur l'axe X
						for(var mx = 0; mx < width; mx++){
							matrix[y+my][x+mx] = 1;
						}
					}
			
			//console.log('Coord in PX', left, top, matrix);
			//console.log('----------------------------------------------------------------------------------------------------');
			//console.log('----------------------------------------------------------------------------------------------------');
			//console.log('----------------------------------------------------------------------------------------------------');
		}
}