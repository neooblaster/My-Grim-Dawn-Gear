/** ----------------------------------------------------------------------------------------------------------------------- ** 
/** ----------------------------------------------------------------------------------------------------------------------- ** 
/** ---																																						--- **
/** --- 															------------------------															--- **
/** ------ **
/** --- 															------------------------															--- **
/** ---																																						--- **
/** ---		AUTEUR 	: Nicolas DUPRE																												--- **
/** ---																																						--- **
/** ---		RELEASE	: xx.xx.2016																													--- **
/** ---																																						--- **
/** ---		VERSION	: 1.0																																--- **
/** ---																																						--- **
/** ---																																						--- **
/** --- 														-----------------------------															--- **
/** --- 															 { C H A N G E L O G } 																--- **
/** --- 														-----------------------------															--- **
/** ---																																						--- **
/** ---		VERSION 1.0 : xx.xx.2016																												--- **
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
function charSheetSwitcher(src){
	/** -------------------------------------- **
	/** --- Gestion du bouton dans le menu --- **
	/** -------------------------------------- **/
	/** Récupération du contenu cible à affiché **/
	var sheet_target = src.getAttribute('data-sheet-target');
	
	/** Retiré l'effet active du bouton du menu **/
	var char_menu = document.querySelectorAll('.gear_panel_build_stats_menu_button');
	
	for(var cm = 0; cm < char_menu.length; cm++){
	var active_pattern = /active/gi;
		
		if(active_pattern.test(char_menu[cm].className)){
			char_menu[cm].classList.remove('active');
			break;
		}
	}
	
	/** Rendre la source comme étant le bouton actif **/
	src.classList.add('active');
	
	
	/** -------------------------------------------------------------- **
	/** --- Gestion d'affichage de la feuille statistique du perso --- **
	/** -------------------------------------------------------------- **/
	/** Masquer l'ancienne feuille du perso **/
	var sheets = document.querySelectorAll('.gear_panel_build_stats_content_sheet');
	
	for(var i = 0; i < sheets.length; i++){
		var active_pattern = /active/gi;
		
		if(active_pattern.test(sheets[i].className)){
			sheets[i].classList.remove('active');
			break;
		}
	}
	
	/** Afficher le nouveau contenu **/
	var sheetToActive = document.querySelector('.gear_panel_build_stats_content_sheet[data-sheet='+sheet_target+']');
		sheetToActive.classList.add('active');
}