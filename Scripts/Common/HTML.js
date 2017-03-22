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
/** ---		RELEASE			: xx.xx.2017																											--- **
/** ---																																						--- **
/** ---		FILE_VERSION	: 1.0	NDU																												--- **
/** ---																																						--- **
/** ---																																						--- **
/** --- 														-----------------------------															--- **
/** --- 															 { C H A N G E L O G } 																--- **
/** --- 														-----------------------------															--- **
/** ---																																						--- **
/** ---		VERSION 1.0 : xx.xx.2017 : NDU																										--- **
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
		   name: String                              // Nom de la balise
		   ?classList: Array of String               // Liste des classes CSS à appliquer
		   ?attributes: Array of strListJSON         // Attribut à définir
		   ?properties: Array strListJSON            // Propriété JavaScript de l'objet à manipuler
		   ?children: strBuildHTML                   // structure des element HTML element
		   ?methods: Array of strListMethod          // Methode à executer
		   ?functions: Array of strListFunction      // Liste des fonctions à executer lors de la construction
		   ?host4triggers: Boolean                   // Indique que l'élément est l'hôte qui recevra les boutons
		                                                Doit etre présent une seul fois - Si absent, la structure de niveau 1 est l'h
		                                                Si absent, la structure de niveau 1 est l'h
		   ?host4title: Boolean                      // Indique que l'élément est l'hote qui recevra le titre
		                                                Doit etre présent une seul fois - Si absent, la structure de niveau 1 est l'h
		                                                Si absent, la structure de niveau 1 est l'h
		   ?host4content: Boolean                    // Indique que l'élément est l'hote qui recevra le contenu (message + html)
		                                                Doit etre présent une seul fois - Si absent, la structure de niveau 1 est l'h
		                                                Si absent, la structure de niveau 1 est l'h
		FIN STRUCTURE
		
		
		STRUCTURE strListJSON
		   name: String
		   value: Mixed
		FIN STRUCTURE
		  
		  
		STRUCTURE strListMethod
		   method: string
		   args: Array 
		FIN STRUCTURE
		  
		  
		STRUCTURE strListFunction
		   function: function
		   ?args: Array 
		FIN STRUCTURE
	

	Description fonctionnelle :
	---------------------------
		
	
	
	Exemples d'utilisations :
	-------------------------
		  
	

	
	
	
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
	
	self.compose = function(structure){
		var element = null;
		
		/** Au minimum, il faut une propriété name pour débuter **/
		if(structure.name !== undefined){
			element = document.createElement(structure.name);
		} else {
			console.error("HTML::compose() has received an invalid structure : ", structure);
			return element;
		}
		
		/** Parcourir les classes CSS à appliquer **/
		if(structure.classList !== undefined && Array.isArray(structure.classList)){
			for(var cl = 0; cl < structure.classList.length; cl++){
				element.classList.add(structure.classList[cl]);
			}
		}
		
		/** Parcourir les attributs à appliquer **/
		if(structure.attributes !== undefined && structure.attributes instanceof Object && !Array.isArray(structure.attributes)){
			for(var attribut in structure.attributes){
				element.setAttribute(attribut, structure.attributes[attribut]);
			}
		}
		
		/** Parcourir les propriétés à appliquer **/
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
		
		/** Parcourir les méthodes à executer (interne) **/
		//if(structure.methods !== undefined && Array.isArray(structure.methods)){
		//	for(var met = 0; met < structure.methods.length; met++){
		//		if(typeof(structure.methods[met].method) === 'string'){
		//			if(!Array.isArray(structure.methods[met].args)){
		//				structure.methods[met].args = [];
		//			}
		//			
		//			var abr = structure.methods[met].args;
		//			var invocation = '';
		//			for(var fna = 0; fna < abr.length; fna++){
		//				invocation += (invocation === '') ? 'abr['+fna+']' : ', abr['+fna+']';
		//			}
		//			
		//			eval('element[structure.methods[met].method]('+invocation+')');
		//		}
		//	}
		//}
		
		/** Parcourir les fonction à executer (externe) **/
		//if(structure.functions !== undefined && Array.isArray(structure.functions)){
		//	for(var fn = 0; fn < structure.functions.length; fn++){
		//		if(typeof(structure.functions[fn].function) === 'function'){
		//			/** Vérifier que la propriété args est valide **/
		//			if(!Array.isArray(structure.functions[fn].args)){
		//				structure.functions[fn].args = [];
		//			}
		//			
		//			/** A la maniere de Event, l'élement est transmis systématiquement **/
		//			structure.functions[fn].args.push(element);
		//			structure.functions[fn].function.apply('', structure.functions[fn].args);
		//		}
		//	}
		//}
		
		/** Controler si c'est un élément hôte **/
		//if(structure.host4title !== undefined && structure.host4title === true){
		//	self.host4title = element;
		//}
		
		/** Controler si c'est un élément hôte **/
		//if(structure.host4content !== undefined && structure.host4content === true){
		//	self.host4content = element;
		//}
		
		/** Controler si c'est un élément hôte **/
		//if(structure.host4triggers !== undefined && structure.host4triggers === true){
		//	self.host4triggers = element;
		//}
		
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