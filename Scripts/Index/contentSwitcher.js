//#!/compile = true
/** ----------------------------------------------------------------------------------------------------------------------- ** 
/** ----------------------------------------------------------------------------------------------------------------------- ** 
/** ---																																						--- **
/** --- 															---------------------------														--- **
/** ---																{ contentSwitcher.js }															--- **
/** --- 															---------------------------														--- **
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
		
		Programme de gestion de contenu. Affiche/ masque les différents contenu à l'aide de bouton "menu"


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
function contentSwitcher(src, contextParent){
	/** ------------------------------------------- **
	/** --- Récupération du contexte de travail --- **
	/** ------------------------------------------- **/
	var context_level = src.getAttribute('data-level');
	var host_context = src.parentNode;
	
	for(var cp = 1; cp < contextParent; cp++){
		host_context = host_context.parentNode;
	}
	
	/** -------------------------------------- **
	/** --- Gestion du bouton dans le menu --- **
	/** -------------------------------------- **/
	/** Récupération du contenu cible à affiché **/
	var content_target = src.getAttribute('data-target');
	
	/** Retiré l'effet active du bouton du menu **/
	var menu_entries = src.parentNode.querySelectorAll('.active[data-level="'+context_level+'"]');
	
	for(var me = 0; me < menu_entries.length; me++){
	var active_pattern = /active/gi;
		
		if(active_pattern.test(menu_entries[me].className)){
			menu_entries[me].classList.remove('active');
			break;
		}
	}
	
	/** Rendre la source comme étant le bouton actif **/
	src.classList.add('active');
	
	
	/** -------------------------------------------------------------- **
	/** --- Gestion d'affichage de la feuille statistique du perso --- **
	/** -------------------------------------------------------------- **/
	/** Masquer l'ancienne content **/
	var contents = host_context.querySelectorAll('[data-content][data-level="'+context_level+'"]');
	
	for(var i = 0; i < contents.length; i++){
		var active_pattern = /active/gi;
		
		if(active_pattern.test(contents[i].className)){
			contents[i].classList.remove('active');
			break;
		}
	}
	
	/** Afficher le nouveau contenu **/
	var contentToActive = host_context.querySelector('[data-content="'+content_target+'"][data-level="'+context_level+'"]');
		contentToActive.classList.add('active');
}