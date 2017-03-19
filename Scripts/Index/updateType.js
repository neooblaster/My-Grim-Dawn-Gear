//#!/compile = true
/** ----------------------------------------------------------------------------------------------------------------------- ** 
/** ----------------------------------------------------------------------------------------------------------------------- ** 
/** ---																																						--- **
/** --- 															------------------------															--- **
/** ---																{ updateType.js }																	--- **
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
		
		Script qui gère la dépendance entre les types d'objet et la famille à laquelle ils sont associé

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
function updateType(src){
	/** Select Target **/
	var target = document.querySelector('#kind_type');
	
	/** Rel Family **/
	var rel_family = src.value;
	
	/** Déterminer les spécificité **/
		// Si item_families vaut 0, alors tout le monde vaut vrai
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