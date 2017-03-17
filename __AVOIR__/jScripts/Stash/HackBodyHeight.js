/** ----------------------------------------------------------------------------------------------------------------------- 
/** ----------------------------------------------------------------------------------------------------------------------- 
/** ---																																						---
/** --- 											----------------------------------------------- 											---
/** ---														{ H A C K B O D Y H E I G H T }														---
/** --- 											----------------------------------------------- 											---
/** ---																																						---
/** ---		AUTEUR 	: Neoblaster																													---
/** ---																																						---
/** ---		RELEASE	: xx.xx.2015																													---
/** ---																																						---
/** ---		VERSION	: 1.0																																---
/** ---																																						---
/** ---																																						---
/** --- 														-----------------------------															---
/** --- 															 { C H A N G E L O G } 																---
/** --- 														-----------------------------															---
/** ---																																						---
/** ---		VERSION 1.0 :																																---
/** ---		-------------																																---
/** ---			- Première release																													---
/** ---																																						---
/** --- 											--------------------------------------------------											---
/** ---													{ L I S T E  D E S  M E T H O D E S }													---
/** --- 											--------------------------------------------------											---
/** ---																																						---
/** -----------------------------------------------------------------------------------------------------------------------
/** ----------------------------------------------------------------------------------------------------------------------- 

	Objectif de la fonction :
	-------------------------
	
		Avec le DOCTYPE HTML5, il est impossible de travailler avec la propriété CSS Height en pourcentage tant
		qu'un élément parent n'a pas été au préalable déclarer de manière explicite.$
		
		Cette fonction calcul et applique en dur la taille du body afin de pouvoir utiliser la propriété CSS Height.
		
	Description fonctionnelle :
	---------------------------
	
		Est appliqué à l'objet HTML "body" la hauteur intérieur disponible dans la fenetre du navigateur (toolbar exclue)
		uniquement si l'état de chargement de la page est au statu "complete".
		
		Le fichier se greffe automatiquement au document et est déclencher à chaque changement d'état jusqu'à ce que l'état
		"readystate" est égale à "complete"

/** -----------------------------------------------------------------------------------------------------------------------
/** ----------------------------------------------------------------------------------------------------------------------- **/
/** > Declaration d'un indicateur globale **/
var HackBodyHeight_Injected = false;

/** > Declaration de la fonction **/
function HackBodyHeight(){
	if(document.readyState === 'complete'){
		if(!HackBodyHeight_Injected){
			document.body.onresize = HackBodyHeight;
			HackBodyHeight_Injected = true;
		}
		document.body.setAttribute('style', 'height: '+window.innerHeight+'px;');
	}
}

/** > Greffe automatique sur le document **/
document.addEventListener('readystatechange', HackBodyHeight);

