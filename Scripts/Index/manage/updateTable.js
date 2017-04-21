/** ----------------------------------------------------------------------------------------------------------------------- ** 
/** ----------------------------------------------------------------------------------------------------------------------- ** 
/** ---																																						--- **
/** --- 															------------------------															--- **
/** ---																{ updateItem.js }																	--- **
/** --- 															------------------------															--- **
/** ---																																						--- **
/** ---		TAB SIZE			: 3																														--- **
/** ---																																						--- **
/** ---		AUTEUR			: Nicolas DUPRE																										--- **
/** ---																																						--- **
/** ---		RELEASE			: 20.04.2017																											--- **
/** ---																																						--- **
/** ---		FILE_VERSION	: 1.1 NDU																												--- **
/** ---																																						--- **
/** ---																																						--- **
/** --- 														---------------------------															--- **
/** ---																{ G I T H U B }																	--- **
/** --- 														---------------------------															--- **
/** ---																																						--- **
/** ---		Automatize url?ts=3 :																													--- **
/** ---																																						--- **
/** ---			https://chrome.google.com/webstore/detail/tab-size-on-github/ofjbgncegkdemndciafljngjbdpfmbkn/related		--- **
/** ---																																						--- **
/** ---																																						--- **
/** --- 														-----------------------------															--- **
/** --- 															 { C H A N G E L O G }  															--- **
/** --- 														-----------------------------															--- **
/** ---																																						--- **
/** ---		VERSION 1.1 : 20.04.2017 : NDU																										--- **
/** ---		------------------------------																										--- **
/** ---			- Augmentation de la portée de la fonction en spécifiant la table à mettre à jour								--- **
/** ---				> updateItem devient updateTable fonctionnellement parlant															--- **
/** ---				> updateItem est concervé en tant qu'alias																				--- **
/** ---																																						--- **
/** ---			- Ajout de la notion de type de donnée (faculative) par défaut à STR													--- **
/** ---				> Les valeurs admise pour type sont les différente possibilité pour PDO::bindValue							--- **
/** ---				> Obligé pour manipuler des champs de type BIT  																		--- **
/** ---																																						--- **
/** ---		VERSION 1.0 : 17.04.2017 : NDU																										--- **
/** ---		------------------------------																										--- **
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
function updateTable(table, id, property, value, type){
	var types = ["BOOL", "NULL", "INT", "STR", "LOB", "STMT"];
	
	if(type === undefined) type = "STR";
	if(types.lastIndexOf(type.toUpperCase()) < 0) type = "STR";
	
	var xQuery = new xhrQuery();
		xQuery.target('/XHR/Admin/update_table.php');
		xQuery.values(
			'table='+table.toUpperCase(),
			'property='+property,
			'value='+value,
			'type='+type,
			'ID='+id
		);
			
		xQuery.callbacks(
			function(e){
				//console.log(e);
			}
		);
		
		xQuery.send();
}


/** Concervation en tant qu'alias **/
function updateItem(id, property, value){
	updateTable('ITEMS', id, property, value);
}