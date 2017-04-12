/** ----------------------------------------------------------------------------------------------------------------------- ** 
/** ----------------------------------------------------------------------------------------------------------------------- ** 
/** ---																																						--- **
/** --- 															------------------------															--- **
/** ---																	{ HTML.js }																		--- **
/** --- 															------------------------															--- **
/** ---																																						--- **
/** ---		TAB SIZE			: 3																														--- **
/** ---																																						--- **
/** ---		AUTEUR			: Nicolas DUPRE																										--- **
/** ---																																						--- **
/** ---		RELEASE			: 27.03.2017																											--- **
/** ---																																						--- **
/** ---		FILE_VERSION	: 1.0	NDU																												--- **
/** ---																																						--- **
/** ---																																						--- **
/** --- 														-----------------------------															--- **
/** --- 															 { C H A N G E L O G } 																--- **
/** --- 														-----------------------------															--- **
/** ---																																						--- **
/** ---		VERSION 1.0 : 27.03.2017 : NDU																										--- **
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
		


	Classe JavaScript requises :
	----------------------------
		


	variable Globales requises :
	----------------------------
		
	

	Déclaration des structure de donnée :
	-------------------------------------
		
		? signifie facultatif
		
		
		STRUCTURE strBuildHTML
		   name: String                               // Nom de la balise
		   ?element: HTMLElement                      // Elemnt HTML directement défini (prioritaire sur name)
		   ?classList: Array of String                // Liste des classes CSS à appliquer
		   ?attributes: List of strListJSON in Object // Attribut à définir
		   ?properties: List of strListJSON in Object // Propriété JavaScript de l'objet à manipuler
		   ?children: Array of strBuildHTML           // structure des element HTML element
		   ?functions: Array of strListFunction       // Liste des fonctions à executer lors de la construction
		FIN STRUCTURE
		
		
		STRUCTURE strListJSON
		   name: String
		   value: Mixed
		FIN STRUCTURE
		
	

	Description fonctionnelle :
	---------------------------
		
	
	
	Exemples d'utilisations :
	-------------------------
		  
		Structure minimale : {
			name: "div"
		}
		
		Return : <div></div>
		
		
		Structure: {
			name: "div",
			children: [
				{name: "h2"}
			]
		}
		
		Return :
		
		<div>
			<h2></h2>
		</div>
		
		
		Structure: {
			name: "div",
			properties: {
				innerHTML: "<strong>GRAS</strong>"
			}
		}
		
		Return : <div><strong>GRAS</strong></div>
	
	
	
/** ----------------------------------------------------------------------------------------------------------------------- **
/** ----------------------------------------------------------------------------------------------------------------------- **/

function HTML(){
	/** -------------------------------------------------------------------------------------------------------------------- **
	/** ---																																					--- **
	/** ---												Déclaration des propriétés de l'instance												--- **
	/** ---																																					--- **
	/** -------------------------------------------------------------------------------------------------------------------- **/
	var self = this;
	
	/** Inline described properties **/
	
	/** Block described properties **/
	/** -------------------------------------------------------------------------------------------------------------------- **
	/** ---																																					--- **
	/** ---															Pré-execution interne															--- **
	/** ---																																					--- **
	/** -------------------------------------------------------------------------------------------------------------------- **/
	
	/** -------------------------------------------------------------------------------------------------------------------- **
	/** ---																																					--- **
	/** ---												Déclaration des méthodes de l'instance													--- **
	/** ---																																					--- **
	/** -------------------------------------------------------------------------------------------------------------------- **/
	/** ------------------------------------------------------------------------ **
	/** --- Méthode de renderisation de la structure donnée en éléments HTML --- **
	/** ------------------------------------------------------------------------ **/
	self.compose = function(structure){
		var element = null;
		
		/** Controle de l'argument **/
		if(structure === undefined){
			console.error("HTML::compose() expects argument 1 to be object.");
			return null;
		}
		
		
		
		/** Au minimum, il faut une propriété "name" ou "element" pour débuter **/
		// Si name défini, alors créer l'élément HTML correspondant
		if(structure.name !== undefined){
			element = document.createElement(structure.name);
		}
		
		// Si element défini, alors remplacer l'éventuel élément créer à partir de NAME
		if(structure.element !== undefined && structure.element instanceof HTMLElement){
			element = structure.element;
		}
		
		// Si aucune des deux propriété n'à été déclarer, alors on ne peut pas aller plus loin
		if(element === null){
			console.error("HTML::compose() has received an invalid structure : ", structure);
			return null;
		}
		
		
		
		/** Parcourir les classes CSS à appliquer **/
		if(structure.classList !== undefined && Array.isArray(structure.classList)){
			for(var cl = 0; cl < structure.classList.length; cl++){
				element.classList.add(structure.classList[cl]);
			}
		}
		
		/** Parcourir les attributs à appliquer (via setAttributes) **/
		if(structure.attributes !== undefined && structure.attributes instanceof Object && !Array.isArray(structure.attributes)){
			for(var attribut in structure.attributes){
				element.setAttribute(attribut, structure.attributes[attribut]);
			}
		}
		
		/** Parcourir les propriétés à appliquer (accès direct via la notation pointée) **/
		if(structure.properties !== undefined && structure.properties instanceof Object && !Array.isArray(structure.properties)){
			for(var property in structure.properties){
				element[property] = structure.properties[property];
			}
		}
		
		/** Parcourir les childNodes et les ajouter **/
		if(structure.children !== undefined && Array.isArray(structure.children)){
			for(var ch = 0; ch < structure.children.length; ch++){
				element.appendChild(self.compose(structure.children[ch]));
			}
		}
		
		/** Parcourir les fonction à executer (externe) **/
		if(structure.functions !== undefined && Array.isArray(structure.functions)){
			for(var fn = 0; fn < structure.functions.length; fn++){
				if(typeof(structure.functions[fn].function) === 'function'){
					/** Vérifier que la propriété args est valide **/
					if(!Array.isArray(structure.functions[fn].args)){
						structure.functions[fn].args = [];
					}
					
					/** A la maniere de Event, l'élement est transmis systématiquement **/
					structure.functions[fn].args.push(element);
					structure.functions[fn].function.apply('', structure.functions[fn].args);
				}
			}
		}
		
		/** Renvois **/
		return element;
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
	
	/** -------------------------------------------------------------------------------------------------------------------- **
	/** ---																																					--- **
	/** ---																	Retour																		--- **
	/** ---																																					--- **
	/** -------------------------------------------------------------------------------------------------------------------- **/
	return self;
}