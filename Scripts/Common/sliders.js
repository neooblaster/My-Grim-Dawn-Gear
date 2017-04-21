/** ----------------------------------------------------------------------------------------------------------------------- ** 
/** ----------------------------------------------------------------------------------------------------------------------- ** 
/** ---																																						--- **
/** --- 														------------------------------														--- **
/** ---																	{ sliders.js }																	--- **
/** --- 														------------------------------			 											--- **
/** ---																																						--- **
/** ---		AUTEUR 	: Nicolas DUPRE																												--- **
/** ---																																						--- **
/** ---		RELEASE	: 22.10.2015																													--- **
/** ---																																						--- **
/** ---		VERSION	: 1.1																																--- **
/** ---																																						--- **
/** ---																																						--- **
/** --- 														-----------------------------															--- **
/** --- 															 { C H A N G E L O G } 																--- **
/** --- 														-----------------------------															--- **
/** ---																																						--- **
/** ---		VERSION 1.1 : 22.10.2015																												--- **
/** ---		------------------------																												--- **
/** ---			- Amélioration pour prendre en charge des valeurs booleen textuelle (true/false) et	numérique (1/0) de	--- **
/** ---				manière autonome																													--- **
/** ---																																						--- **
/** ---		VERSION 1.0 : 20.10.2015																												--- **
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
		
	Description fonctionnelle :
	---------------------------
	
		Usage : <input type="slider" name="$name" value="{true|false}" hidden/>
		
		
		<slider default="1" class="hmSlider">
			<slide state="on">
				<onside></onside>
				<offside></offside>
				<btnslide></btnslide>
			</slide>
		
			<input type="hidden" value="1" onchange="user().save({params: {AUTOCOMPLETE_TIME: this.value}, callback: function(e){notify('my_account').write(e);}}); notify('my_account').wait();">
		</slider>
		
		
		
		>>>> Faire un mode readonly et un onchange, mettre a jour le slider
		
		

/** ----------------------------------------------------------------------------------------------------------------------- **
/** ----------------------------------------------------------------------------------------------------------------------- **/
/** > Déclaration du "compilatauer"
	/** 1. Retrieving sliders **/
	function slider_compilator(){
		if(document.readyState === 'complete'){
			/** 1. Récupération des sliders **/
			var iSliders = document.querySelectorAll('input[type="slider"]');

			/** 2. Création des slider à proprement parlé **/
			for(var i = 0; i < iSliders.length; i++){	
				/** > Création du slider **/
				var slider = document.createElement('slider');

				/** > Creation du shadow DOM Root **/
				//var root = slider.createShadowRoot();

				/** > Get Attributes **/
				iSliders[i].value = iSliders[i].value.toLowerCase();
				
				var name = iSliders[i].getAttribute('name');
				var value = iSliders[i].value;
				
				/** > Secure Value (Si null ou invalide alors vaut false) **/
				var valide_values = ['true', 'false', '1', '0'];
				if(value === null || valide_values.lastIndexOf(value) < 0){
					value = "false";
				}
				
				
				/** > Save init value (Default value) **/
				slider.setAttribute('default', value);

				/** > Calc State (Allowed typeof(string | boolean))**/
				var state = (['true', '1'].lastIndexOf(value) >= 0) ? "on" : "off";

				/** > Set Unique Class **/
				slider.classList.add('hmSlider');

				/** > Create Slide part (Movable) **/
				var slide = document.createElement('slide');
					slide.setAttribute('state', state);

				/** > Create onSide, offSide & btnSlide **/
				var onSide = document.createElement('onSide');
				var offSide = document.createElement('offSide');
				var btnSlide = document.createElement('btnSlide');

				/** > Assemblage **/
					slide.appendChild(onSide);
					slide.appendChild(offSide);
					slide.appendChild(btnSlide);
				slider.appendChild(slide);

				/** > Triggering with binding **/
				slider.addEventListener('click', function(slide){
					var slide_state = slide.getAttribute('state');
					var slide_value = this.value;
					
					/** > Déterminer le nouveau statut **/
					var new_state = (slide_state === "on") ? "off" : "on";
					
					/** > Determiner la valeur au bon format désiré **/
					var slider_value;
					
					if(['1', '0'].lastIndexOf(slide_value) >= 0){
						slider_value = (new_state === 'on') ? "1" : "0";
					} else {
						slider_value = (new_state === 'on') ? "true" : "false";
					}
					
					slide.setAttribute('state', new_state);
					this.setAttribute('value', slider_value);
					
					try {
						eval(this.getAttribute('onchange'));
					} catch (e){
						console.error(e, this.getAttribute('onchange'));
					}
				}.bind(iSliders[i], slide));
				
				slider.addEventListener('click', iSliders[i].getAttribute('onclick'));

				/** > Insérer le slider créer au niveau de l'input type slider **/
				iSliders[i].parentNode.insertBefore(slider, iSliders[i]);
				iSliders[i].setAttribute('type', 'hidden');
				slider.appendChild(iSliders[i]);
				
				/** > Search for HTMLFormObject to bind a reset function for sliders **/
				var parentNode = iSliders[i].parentNode;
				
				/** If slider has parent **/
				if(parentNode !== null){
					do {
						/** If parent nodeName is a FORM **/
						/**
						
							HTMLFormObject ::
							
							localName = form
							tagName = FORM
							nodeName = FORM
						
						**/
						if(parentNode.nodeName === 'FORM'){
							var parentForm = parentNode;
							
							/** If the function is not bound **/
							if(parentForm.getAttribute('slbound') !== 'true'){
								parentForm.addEventListener('reset', resetChildSliders.bind('', parentForm));
								parentForm.setAttribute('slbound', 'true');
							}
							
							/** No longer watching for FORM, cause FORM can't imbricated **/
							break;
						}
					} while(parentNode = parentNode.parentNode)
				}
			}
		}
	}
		
/** > Sliders Reset function triggered on real reset event for form **/
	function resetChildSliders(srcForm){
		/** > Retrieve All sliders **/
		var sliders = srcForm.querySelectorAll('slider');
		
		for(var i = 0; i < sliders.length; i++){
			var defValue = sliders[i].getAttribute('default');
			var setValue = sliders[i].querySelector('input').value;
			
			/** > When a different exist, trigger a click event for full animation setting **/
			if(defValue !== setValue){
				sliders[i].click();
			}
		}
	}

/** > Intégration pour déclenchement du "compilateur" **/
	document.addEventListener('readystatechange', slider_compilator);


	

