/** ----------------------------------------------------------------------------------------------------------------------- ** 
/** ----------------------------------------------------------------------------------------------------------------------- ** 
/** ---																																						--- **
/** --- 														------------------------------														--- **
/** ---															{ createAttributes.js }																--- **
/** --- 														------------------------------														--- **
/** ---																																						--- **
/** ---		TAB SIZE			: 3																														--- **
/** ---																																						--- **
/** ---		AUTEUR			: Nicolas DUPRE																										--- **
/** ---																																						--- **
/** ---		RELEASE			: 25.04.2017																											--- **
/** ---																																						--- **
/** ---		FILE_VERSION	: 1.0 NDU																												--- **
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
/** --- 															 { C H A N G E L O G } 																--- **
/** --- 														-----------------------------															--- **
/** ---																																						--- **
/** ---		VERSION 1.0 : 25.04.2017 : NDU																										--- **
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
function createItemAttribute(){
	var xQuery = new xhrQuery();
		xQuery.target("/XHR/Admin/create_attribute.php");
		xQuery.forms(document.forms.create_attribute_form);
		xQuery.callbacks(function(e){
			document.location.reload();
		});
		xQuery.send();
}