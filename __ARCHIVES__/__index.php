<?php

	/** > Chargement des classes **/
	//require_once "Classes/template.class.php";

	$file;			// RESOURCE	:: Fichier source (Défini en code ou argument a définir)
	$moteur;			// TEMPLATE	:: Instance de la classe Template
	$template;		// STRING	:: Chemin vers le modèle / Modèle format text
	$recipient;		// STRING	:: (instance) Adresse mail du destinataire
	$sender;			// STRING	:: Adresse mail de l'émetteur pour le Reply_to
	$sender_name;	// STRING	:: Nom d'affichage de l'émetteur
	$subject;		// STRING	:: Objet du mail

	/** > Initialisation des variables **/
	//$moteur = new Template();

	$sender = "contact@inra.fr";
	$sender_name = "INRA Rennes";
	$subject = "Sondage";

	$template = "TEMPLATE BODY SAMPLE";


	/** > Lecture du fichier CSV de délimiteur "\t" **/
	//$csv = fopen($file, "r");


	/** > Configuration du moteur **/
		// Définition du fichier se sortie
			//$moteur->set_output_name("sondage.html");
			
		// Définition du modèle
			//$moteur->set_template_file($template)
			//$moteur->set_template_text($template);
			
		// Définition de l'émétteur
			// Sender Name
				//$moteur->set_mail_sender_name($sender_name);
			// Sender Address
				//$moteur->set_mail_sender($sender);
			
		// Défition de l'objet du mail
			//$moteur->set_mail_subject($subject);
				
				
	/** > Parcourir le fichier **/
		// Variable de l'instance
			$recipient = "neo-blaster@hotmail.fr";
			
		// Configuration du destinataire
			//$moteur->set_mail_recipients($recipient);
			
		// Envois des données
			//$moteur->set_vars($vars);
			
		// Compilation
			//$moteur->render();
			
		// Mailing
			//$moteur->sendMail();
			
			/** HEADER DEFINITION **/
			//ini_set('SMTP', 'smtp.free.fr');

			$headers = "From: Nicolas DUPRE<neo-blaster@hotmail.fr>\n";
			$headers .= "X-Mailer: PHP ".phpversion()."\n";
			$headers .= "Reply-To:neo-blaster@hotmail.fr\n";
			$headers .= "Organization: neoblaster.fr\n";
			$headers .= "X-Priority: 3 (Normal)\n";
			$headers .= "Mime-Version: 1.0\n";
			$headers .= "Content-Type: text/html; charset=\"UTF-8\"";
			$headers .= "Content-Transfer-Encoding: 8bit\n";
			$headers .= "Date:" . date("D, d M Y h:s:i" ) . " +0300\n";
			
			mail("nicolas.dupre@viseo.com", "debugg double sent", "debugg", $headers);


?>