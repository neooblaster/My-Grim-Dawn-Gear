/** ----------------------------------------------------------------------------------------------------------------------- ** 
/** ----------------------------------------------------------------------------------------------------------------------- ** 
/** ---																																						--- **
/** --- 													------------------------------------													--- **
/** ---															{ SSE Oberhaul Classe }																--- **
/** --- 													------------------------------------													--- **
/** ---																																						--- **
/** ---		AUTEUR 	: Nicolas DUPRE																												--- **
/** ---																																						--- **
/** ---		RELEASE	: 23.05.2015																													--- **
/** ---																																						--- **
/** ---		VERSION	: 2.0																																--- **
/** ---																																						--- **
/** ---																																						--- **
/** --- 														-----------------------------															--- **
/** --- 															 { C H A N G E L O G } 																--- **
/** --- 														-----------------------------															--- **
/** ---																																						--- **
/** ---		VERSION 2.0 : 23.05.2015																												--- **
/** ---		------------------------																												--- **
/** ---			- Révision du script et de son versionning																					--- **
/** ---			- Ajout de la gestion de déconnexion avec un auto-restart																--- **
/** ---			- Ajout d'une méthode permetant de définir une fonction de workaround (pour IE)									--- **
/** ---			- Mise à jour de toutes les valeurs de retour pour enchaîner les appeles											--- **
/** ---				> Renommage de setPathFile en target()																						--- ** 
/** ---				> Renommage de setDefaultCallback en callback()																			--- ** 
/** ---																																						--- **
/** ---		VERSION 1.2 : 12.09.2014																												--- **
/** ---		------------------------																												--- **
/** ---			- Modification de la method appendEvents() pour compatibilité FF et Chrome											--- **
/** ---			- Ajout de la méthode getFunctionName() > Retourne le nom (String) d'une fonction donnée(function)			--- **
/** ---			- Ajout du paramètre d'entrée "SSEName" dans "SSE" afin d'identifier l'instance									--- **
/** ---			- Ajout des méthodes sendLog(), sendWarn(), sendError()																	--- **
/** ---																																						--- **
/** ---		VERSION 1.1 : 13.09.2014																												--- **
/** ---		------------------------																												--- **
/** ---			- Ajout de la méthode restart()																									--- **
/** ---																																						--- **
/** ---		VERSION 1.0 : 12.09.2014																												--- **
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
	
		Cette classe est une révision améliorée des SSE. Elle met à disposition des méthode qui évite à l'utilisateur de la classe
	de développer ses propres lignes qui utilise l'API SSE
		
	Description fonctionnelle :
	---------------------------
	
		Functions :
		-----------
			
			SSE()
				addEvent()
				appendEvents()
				consoleOn()
				consoleOff()
				start()
				stop()
				restart()
				target() 	replace	setPathFile()
				callback()	replace	setDefaultCallback()
				removeEvent()
				getFunctionName()
				sendLog()
				sendWarn()
				sendError()
				timeout_watcher()
				workaround()


		Détails :
		---------
		
		[Object]	SSE( [String] SSEName, {[String] target}, {[function] defaultCallback})
		
			IN :
				[String] SSEName :: Nom désirée pour l'instance SSE
				[String] target :: Chemin vers le script qu'on désire écouter
				[Function] defaultCallback :: Function de traitement par défaut
				
			OUT :
				[Object] :: Retourne l'instance SSE
				
			ROLE :
				Créateur de l'instance SSE qui met a disposition les méthodes
				
				
				
		[Object] addEvent( [String] eventName, [Function] eventFunction)	
		
			IN :
				[String] eventName :: Nom de l'évenement qui doit être strictement identique lors de l'envois de l'event-stream
				[Function] evenFunction :: Function de traitement des données déstinée à l'évenement eventName
				
			OUT :
				[Object] :: Retourne l'instance SSE
				
			ROLE :
				Methode d'ajout d'écouteur d'évenement personnalisé qui traitera pas la suite les données qui lui est déstinée à l'aide de la function
				eventFunction
				
				
				
		[Object] appendEvents( )
		
			IN :
				NONE
				
			OUT :
				[Boolean] :: Retourne l'instance SSE
				
			ROLE :
				Cette méthode est de catégorie "private" et n'est pas à utilisé par l'utilisateur. Elle est appelé par la méthode start() dont le but est d'ajouter concretement
				les évenements qui sont lister dans le pool des event (qui lui est géré par addEvent et removeEvent)
				
				
				
		[Object] consoleOn( )
		
			IN :
				NONE
				
			OUT :
				[Boolean] :: Retourne l'instance SSE
				
			ROLE :
				Cette méthode active les sortie console. Par défaut c'est désactivé et n'affiche que les erreurs bloquantes. Les logs et warn sont omis
				
				
				
		[Object] consoleOff( )
		
			IN :
				NONE
				
			OUT :
				[Boolean] :: Retourne l'instance SSE
				
			ROLE :
				Cette méthode désactive les sortie console. Par défaut c'est désactivé et n'affiche que les erreurs bloquantes. Les logs et warn sont omis
				
				
				
		[String] getFunctionName ( [Function] functionIs )
		
			IN :
				[Function] functionIs :: Fonction dont il faut identifier le nom
				
			OUT :
				[String] :: Nom de la function passé en paramètres
				
			ROLE :
				Obtenir le nom au format string d'une fonction de type function passée en paramètre. Le rôle de cette fonction est de type "private" et sert dans les autre méthode
				
				
				
		[Object] removeEvent ( [String] eventName )	
		
			IN :
				[String] eventName :: Nom de l'évenement qui doit être supprimé de la liste
				
			OUT :
				[Object] :: Retourne l'instance SSE
				
			ROLE :
				Le rôle est de retiré un écouteur d'évenement. Si des données lui sont déstiné, il n'y aura plus de traitement du fait de sa suppression
				
				
				
		[Boolean] sendError( [String] record )
		
			IN :
				[String] record :: Le message qu'il faut emettre en console de type 'error'
				
			OUT :
				[Boolean] :: Indicateur de réussite
				
			ROLE :
				Sont rôle est d'emettre le message indiqué en paramètre. Fonctionne toujours, même si les sortie consoles sont désactivées
				
				
				
		[Object] setTimeoutDelay( [Number] delay )
		
			IN :
				[Number] delay :: Delay en milliseconde qui défini un état de deconnexion
				
			OUT :
				[Object] :: Retourne l'instance SSE
				
			ROLE :
				Sont rôle est de définir en millisecond le delays qui determine le délay entre deux réponse. Passé ce délay, une reconnexion est effectuée si non désactivé
				
				
				
		[Object] setTimeoutDelay( [Boolean] restart )
		
			IN :
				[Boolean] restart :: Indique si on effectue une reconnexion en cas de timeout detecté
				
			OUT :
				[Object] :: Retourne l'instance SSE
				
			ROLE :
				Sont rôle est de définir si oui ou non il faut procéder à une reconnexion automatique en cas de perte de connexion
				
				
				
		[Boolean] sendLog( [String] record )
		
			IN :
				[String] record :: Le message qu'il faut emettre en console de type 'log'
				
			OUT :
				[Boolean] :: Indicateur de réussite
				
			ROLE :
				Sont rôle est d'emettre le message indiqué en paramètre. Ne fonctionne que si les sortie console sont activées
				
				
				
		[Boolean] sendWarn( [String] record )
		
			IN :
				[String] record :: Le message qu'il faut emettre en console de type 'warn'
				
			OUT :
				[Boolean] :: Indicateur de réussite
				
			ROLE :
				Sont rôle est d'emettre le message indiqué en paramètre. Ne fonctionne que si les sortie console sont activées
				
				
				
		[Object] stop( )
		
			IN :
				NONE
				
			OUT :
				[Object] :: Retourne l'instance SSE
				
			ROLE :
				Mettre fin à la connexion active
				
				
				
		[Object] workaround( [Function] workaround )
		
			IN :
				[Function] workaround :: Fonction à executée si le navigateur ne gère pas les SSE. L'équivalent possible est une function bouclé avec appel AJAX
				
			OUT :
				[Object] :: Retourne l'instance SSE
				
			ROLE :
				Mettre fin à la connexion active
				

/** ----------------------------------------------------------------------------------------------------------------------- **
/** ----------------------------------------------------------------------------------------------------------------------- **/
function SSE(SSEName, target, defaultCallback){
	/** -------------------------------------------------------------------- **
	/** ---						Configuration des propriétés						--- **
	/** -------------------------------------------------------------------- **/
	this.SSEName = SSEName;
	this.SSE_target = (target !== undefined) ? target : null;
	this.defaultCallback = (defaultCallback !== undefined) ? defaultCallback : null;
	this.core = null;
	this.eventsToAppend = [];
	this.consoleActivated = false;
	this.SSE_workaround = null;
	
	/** Propriété de gestion de déconnexion **/
	this.timeout_autoDetection = true;		// Indique si le délais doit etre déterminé par le programme
	this.timeout_delay = 60000;				// Délais en ms à ne pas dépassé -> Reconnexion
	this.timeout_detected = false;			// Indique si le délais à été determiné
	this.timeout_restart = true;				// Indique si en cas de timeout, on effectue une reconnexion
	this.timeout_response = 0;					// Date de la dernière réponse
	this.timeout_sample = 0;					//	Date d'échantillonage pour calculer de timeout_delay
	this.timeout_watcherInstance = null;	// Instance de controlle du timeout;
	this.timeout_watcherUseSpeed = 1000;	// Vitesse de l'instance de controlle du timeout afin de se callé au delay
	this.timeout_watcherSetSpeed = 1000;	// Vitesse de l'instance en cours de traitement
	
	
	
	/** -------------------------------------------------------------------- **
	/** ---						Configuration des méthodes							--- **
	/** -------------------------------------------------------------------- **
	/** Méthode d'implémentation des évenement dans EventSource **/
	this.appendEvents = function(){
		/** Parcourir la liste des évenements enregistré **/
		for(var i = 0; i < this.eventsToAppend.length; i++){
			/** Transcription pour ne pas perdre le this dans function(){} **/
			var eventName = this.eventsToAppend[i].eventName;
			var eventFunction = this.eventsToAppend[i].eventFunction;
			
			/** Intégration de l'évenement **/
			this.core.addEventListener(eventName, eventFunction);
			
			/** A garder pour l'historique avec le .bind pour la déclaration par function(param){}.bind(this, param); **/
			// this.core.addEventListener(eventName, function(fn, e){fn(event);}.bind(this, eventFunction);
		}
		return true;
	};
	
	
	/** Méthode de configuration d'évenement - Ajoute d'évenement **/
	this.addEvent = function(eventName, eventFunction){
		/** Vérifier s'il n'existe pas déjà un évent de ce même nom **/
		var exist = false;
		
		/** Parcourir les évenements enregistré**/
		for(var i = 0; i < this.eventsToAppend.length; i++){
			/** Si l'évent existe déjà, le mettre à jour **/
			if(this.eventsToAppend[i].eventName === eventName){
				this.eventsToAppend[i].eventFunction = eventFunction;
				this.sendWarn('L\'évenement "'+eventName+'" à été mise à jour.');
				exist = true;
				break;
			}
		}
		
		/** S'il n'a pas été trouvé (n'existe pas et donc pas mis à jour) le créer **/
		if(!exist){
			this.eventsToAppend.push({"eventName" : eventName, "eventFunction" : eventFunction});
			this.sendLog('L\'évenement "'+eventName+'" à été ajouté à la liste.');
		}
		
		return this;
	};
	
	
	/** Méthode de configuration de la function de traitement par défaut **/
	this.callback = function(callback){
		this.defaultCallback = callback;
		this.sendLog('Définition de la fonction de traitement par défaut [defaultCallback].');
		return this;
	};
	
	
	/** Méthode de désactivation des sortie consoles **/
	this.consoleOff = function(){
		/** Désactivation des sorties "console" : false **/
		this.consoleActivated = false;
		console.log('Désactivation des sorties "console".');
		return this;
	};
	
	
	/** Méthode d'activation des sortie consoles **/
	this.consoleOn = function(){
		/** Activation des sorties "console" : true **/
		this.consoleActivated = true;
		this.sendLog('Activation des sorties "console".');
		return this;
	};
	
	
	/** Méthode de convertion de function en nom de function **/
	this.getFunctionName = function(functionIs){
		var functionName = null;
		
		/** Convertir la function au format String **/
		functionIs = String(functionIs);
		
		/** Supprimer la partie "function "**/
		functionIs = functionIs.substr(9);
		
		/** Trouver l'index de la première parenthese **/
		var parentheseIndex = functionIs.indexOf('(');
		
		/** Récupérer la chaine entre le debut et la première parenthese **/
		functionName = functionIs.substr(0, parentheseIndex);
		
		/** Retourne la valeur **/	
		return functionName;
	};
	
	
	/** Méthode de configuration d'évenement - Suppression d'évenement **/
	this.removeEvent = function(eventName){
		/** Recherche de l'évenement dans la liste **/
		var exist = false;
		
		for(var i = 0; i < this.eventsToAppend.length; i++){
			if(this.eventsToAppend[i].eventName === eventName){
				exist = true;
			}
		}
		
		/** Suppresion de la liste **/
		if(exist){
			var newEventsList = [];
			
			/** Retranscription de la liste avec l'eventName en moins (reconstruction des index) **/
			for(var i = 0; i < this.eventsToAppend.length; i++){	
				if(this.eventsToAppend[i].eventName != eventName){
					newEventsList.push({"eventName" : this.eventsToAppend[i].eventName, "eventFunction" : this.eventsToAppend[i].eventFunction});
				}
			}
			
			/** Mise à jour de la liste **/
			this.eventsToAppend = newEventsList;
			this.sendLog('L\'évenement "'+eventName+'" a bien été retiré de la liste des évenements.');
			return this;
		} else {
			this.sendWarn('L\'évenement "'+eventName+'" n\'existe pas dans la liste. Aucune suppresion n\'a été effectuée.');
			return this;
		}	
	};
	
	
	/** Méthode de redémarrage de la communication **/
	this.restart = function(){
		/** Effectuer un redémarrage uniquement si une connexion est en cours **/
		if(this.core !== null){
			this.stop();
			this.start();
			this.sendLog('Redémarrage terminé.');
			return this;
		}
		/** Sinon, envoyer un warning (non bloquant) qu'il n'y à pas de connexion active **/
		else {
			this.sendWarn('Aucune connexon active. Pas de redémarrage effectué.');
			return this;
		}
	};
	
	
	/** Méthode d'émission de console LOG **/
	this.sendLog = function(record){
		/** Si les sorties consoles sont activé alors éméttre la sortie "log" **/
		if(this.consoleActivated){
			console.log('SSE "'+this.SSEName+'" :: '+record);
			return true;
		}
		return false;
	};
	
	
	/** Méthode d'émission de console WARN **/
	this.sendWarn = function(record){
		/** Si les sorties consoles sont activé alors éméttre la sortie "warn" **/
		if(this.consoleActivated){
			console.warn('SSE "'+this.SSEName+'" :: '+record);
			return true;
		}
		return false;
	};
	
	
	/** Méthode d'émission de console ERROR **/
	this.sendError = function(record){
		console.error('SSE "'+this.SSEName+'" :: '+record);
		return true;
	};
	
	
	/** Méthode de définition du délais de timeout pour reconnexion **/
	this.setTimeoutDelay = function(delay){
		this.timeout_delay = delay;
		this.timeout_autoDetection = false;
		this.timeout_detected = true;
		
		this.timeout_response = Date.now();
		
		this.sendLog('Le délais du timeout à été définie à '+delay+'ms.');
		
		return this;
	};
	
	
	/** Méthode de définition du délais de timeout pour reconnexion **/
	this.setTimeoutRestart = function(restart){
		this.timeout_restart = restart;
		
		if(restart){
			this.sendLog('En cas de connexion aborted, une reconnexion automatique aura lieu.');
		} else {
			this.sendWarn('En cas de connexion aborted, il n\'y aura pas de reconnexion automatique.');
		}
			
		return this;
	};
	
	
	/** Méthode de déconnexion **/
	this.start = function(){
		/** Vérifier la compatibilité du navigateur avec les EventSource **/
		/** Si incompatible **/
		/*if(typeof(EventSource) === undefined){*/
		if(window.EventSource === undefined){
			this.sendWarn('Les Server-Sent Events sont incompatible avec votre navigateur.');
			if(this.SSE_workaround !== null){
				this.sendWarn('Le programme bascule sur la fonction alternative "'+this.getFunctionName(this.SSE_workaround)+'".');
				this.SSE_workaround();
			} else {
				this.sendError('Du à l\'incompatibilité de votre navigateur et à l\'absence de traitement alternatif, vous ne pouvez pas utiliser l\'application.');
				alert('Du à l\'incompatibilité de votre navigateur et à l\'absence de traitement alternatif, vous ne pouvez pas utiliser l\'application.');
			}
		} 
		/** Cas compatible **/
		else {
			var ready = true;
			
			/** Tester la configuration de la cible **/
			if (this.SSE_target === null){
				this.sendError('Connexion impossible, source non définie [target].');
				ready = false;
			}
			
			/** Tester la configuration de la defaultCallback **/
			if(this.defaultCallback === null){
				this.sendError('Connexion impossible, fonction de traitement (callback) non définie [defaultCallback].');
				ready = false;
			}
			
			/** Si OK executer l'EventSource **/
			if(ready){
				/** Si pas de connexion en cours **/
				if(this.core === null){
					/** Initialisation **/
					this.core = new EventSource(this.SSE_target);
					
					/** Gestion des message **/
					this.core.onmessage = function(event){
						if(!this.timeout_detected && this.timeout_autoDetection){
							if(this.timeout_sample !== 0){
								this.timeout_delay = Math.ceil(1.1 * (Date.now() - this.timeout_sample));
								this.timeout_detected = true;
								this.sendLog('La detection automatique du délais de timeout à été définie à '+this.timeout_delay+'ms.');
							} else {
								this.timeout_sample = Date.now();
							}
						}
						
						/** Mise à jour **/
						this.timeout_response = Date.now();
						
						/** Traiter les données **/
						this.defaultCallback(event);
					}.bind(this);
					
					/** Ajout des gestionnaire d'évenement **/
					this.appendEvents();
					
					/** Lancer le timeout_watcher si requis **/
					if(this.timeout_restart){
						this.timeout_watcherInstance = setInterval(this.timeout_watcher.bind(this), this.timeout_watcherUseSpeed);
					}
					
					/** Message de reussite de la connexion **/
					this.sendLog('Connexion à la cible "'+this.SSE_target+'" établie.');
				}
				/** Si déja une connexion **/
				else{
					this.sendWarn('Connexion déjà établie.');
				}
			}
		}
	};
	
	
	/** Méthode de déconnexion **/
	this.stop = function(){
		/** Vérification d'une connexion existante **/
		if(this.core === null){
			this.sendWarn('Aucune connexion à fermer.');
			return this;
		}
		/** Sinon, fermer la connexion à l'aide de la methode close() **/
		else {
			this.core.close();	/** EventSource.close()	**/
			this.core = null;		/** RAZ**/
			
			/** Stoper le watcher **/
			if(this.timeout_watcherInstance !== null){
				clearInterval(this.timeout_watcherInstance);
				this.timeout_watcherInstance = null;
			}
			
			this.sendLog('La connexion à bien été fermée.');
			return this;
		}
	};
	
	
	/** Méthode de configuraiton de la cible **/
	this.target = function(target){
		this.SSE_target = target;
		this.sendLog('Définition de la source à "'+target+'" [target].');
		return this;
	};
	
	
	/** Méthode de controle de deconnexion **/
	this.timeout_watcher = function(){
		/** Contrôler le timeout **/
		var now = Date.now();
		var delta = now - this.timeout_response;
		
		if(delta > this.timeout_delay && this.timeout_restart){
			this.sendWarn('Le délais entre deux réponses est passé. Le programme effectue une reconnexion.');
			this.timeout_response = now;
			this.restart();
		}
		
		
		/** Contrôle que le watcher tourne à la vitesse attendu **/
		if(this.timeout_watcherSetSpeed !== this.timeout_watcherUseSpeed){
			this.timeout_watcherSetSpeed = this.timeout_watcherUseSpeed;
			clearInterval(this.timeout_watcherInstance);
			this.timeout_watcherInstance = setInterval(this.timeout_watcher.bind(this), this.timeout_watcherUseSpeed);
		}
	};
	
	
	/** Méthode de définition de la function de traitement en workaround pour les navigateurs incompatible **/
	this.workaround = function(workaround){
		this.SSE_workaround = workaround;
		
		this.sendLog('La fonction "'+this.getFunctionName(workaround)+'" sera utilisé par les navigateur non compatible aux Server-Sent Events.');
		
		return this;
	};
	
	
	
	/** -------------------------------------------------------------------- **
	/** ---							Valeur de retour									--- **
	/** -------------------------------------------------------------------- **/
	return this;
} // END_FUNCTION