/** ----------------------------------------------------------------------------------------------------------------------- ** 
/** ----------------------------------------------------------------------------------------------------------------------- ** 
/** ---																																						--- **
/** --- 															------------------------															--- **
/** ---																	{ folder.js }																	--- **
/** --- 															------------------------															--- **
/** ---																																						--- **
/** ---		AUTEUR 	: Nicolas DUPRE																												--- **
/** ---																																						--- **
/** ---		RELEASE	: 19.03.2017																													--- **
/** ---																																						--- **
/** ---		VERSION	: 1.0																																--- **
/** ---																																						--- **
/** ---																																						--- **
/** --- 														-----------------------------															--- **
/** --- 															 { C H A N G E L O G } 																--- **
/** --- 														-----------------------------															--- **
/** ---																																						--- **
/** ---		VERSION 1.0 : 19.03.2017																												--- **
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
		
		Programme qui affiche/masque le volet des stats et l'inventaire


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
function folder(){
	var host = document.querySelector('.gear_panel');
	
	return {
		stats: function(){
			var class_pattern = /stats_fold/gi;
			
			if(class_pattern.test(host.className)){
				host.classList.remove('stats_fold');
			} else {
				host.classList.add('stats_fold');
			}
		},
		
		inventory: function(){
			var class_pattern = /inventory_fold/gi;
			
			if(class_pattern.test(host.className)){
				host.classList.remove('inventory_fold');
			} else {
				host.classList.add('inventory_fold');
			}
		}
	};
}