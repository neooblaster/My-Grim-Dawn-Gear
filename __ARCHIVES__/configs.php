<?php
///** -------------------------------------------------------------------------------------------------------------------- ** 
///** -------------------------------------------------------------------------------------------------------------------- ** 
///** ---																																					--- **
///** --- 											-----------------------------------------------											--- **	
///** --- 														{ C O N F I G . P H P } 															--- **
///** --- 											-----------------------------------------------											--- **	
///** ---																																					--- **
///** ---		AUTEUR 	: Neoblaster																												--- **
///** ---																																					--- **
///** ---		RELEASE	: 27.03.2015																												--- **
///** ---																																					--- **
///** ---		VERSION	: 1.0																															--- **
///** ---																																					--- **
///** ---																																					--- **
///** --- 														-----------------------------														--- **
///** --- 															{ C H A N G E L O G } 															--- **
///** --- 														-----------------------------														--- **	
///** ---																																					--- **
///** ---																																					--- **
///** ---		VERSION 1.0 :																															--- **
///** ---		-------------																															--- **
///** ---			- Première release																												--- **
///** ---																																					--- **
///** -------------------------------------------------------------------------------------------------------------------- **
///** -------------------------------------------------------------------------------------------------------------------- **/
//
//	require_once 'Classes/MySQL.class.php';
//
///** -------------------------------------------------------------------------------------------------------------------- **
///** -------------------------------------------------------------------------------------------------------------------- **
///** ---																																					--- **
///** ---													CONFIGURATION DES COMPTES SQL															--- **
///** ---																																					--- **
///** -------------------------------------------------------------------------------------------------------------------- **
///** -------------------------------------------------------------------------------------------------------------------- **/
///** > Création du moteur SQL **/
//	$MySQL = new MySQL();
//
//	/** > Configuration du compte SQL Principale Version de DEV : MAIN-DEV **/
//	$MySQL->add_account('MAIN-DEV');
//		$MySQL->account('MAIN-DEV')->set_host('mysql51-70.perso');
//		$MySQL->account('MAIN-DEV')->set_credentials('neoblastdtb', 'M5001270357');
//		$MySQL->account('MAIN-DEV')->set_database('neoblastdtb');
//		$MySQL->account('MAIN-DEV')->set_tables('');
//
//	/** > Configuration du compte SQL Principale Version de PRE-PROD : MAIN-PRP **/
//	$MySQL->add_account('MAIN-PRP');
//		$MySQL->account('MAIN-PRP')->set_host('mysql51-70.perso');
//		$MySQL->account('MAIN-PRP')->set_credentials('neoblastdtb', 'M5001270357');
//		$MySQL->account('MAIN-PRP')->set_database('neoblastdtb');
//		$MySQL->account('MAIN-PRP')->set_tables('');
//
//	/** > Configuration du compte SQL Principale Version de Production : MAIN-PRD **/
//	$MySQL->add_account('MAIN-PRD');
//		$MySQL->account('MAIN-PRD')->set_host('mysql51-70.perso');
//		$MySQL->account('MAIN-PRD')->set_credentials('neoblastdtb', 'M5001270357');
//		$MySQL->account('MAIN-PRD')->set_database('neoblastdtb');
//		$MySQL->account('MAIN-PRD')->set_tables('');
//
//	/** > Configuration du compte d'accès au guide **/
//	$MySQL->add_account('GUIDE-DEV');
//		$MySQL->account('GUIDE-DEV')->set_host('mysql51-70.perso');
//		$MySQL->account('GUIDE-DEV')->set_credentials('neoblastdtb', 'M5001270357');
//		$MySQL->account('GUIDE-DEV')->set_database('neoblastdtb');
//		$MySQL->account('GUIDE-DEV')->set_tables('');
//
//	/** > Configuration du compte d'accès au guide **/
//	$MySQL->add_account('GUIDE-PRP');
//		$MySQL->account('GUIDE-PRP')->set_host('mysql51-70.perso');
//		$MySQL->account('GUIDE-PRP')->set_credentials('neoblastdtb', 'M5001270357');
//		$MySQL->account('GUIDE-PRP')->set_database('neoblastdtb');
//		$MySQL->account('GUIDE-PRP')->set_tables('');
//
//	/** > Configuration du compte d'accès au guide **/
//	$MySQL->add_account('GUIDE-PRD');
//		$MySQL->account('GUIDE-PRD')->set_host('mysql51-70.perso');
//		$MySQL->account('GUIDE-PRD')->set_credentials('neoblastdtb', 'M5001270357');
//		$MySQL->account('GUIDE-PRD')->set_database('neoblastdtb');
//		$MySQL->account('GUIDE-PRD')->set_tables('');
//
//	/** > Configuration du compte d'accès au versionning **/
//	$MySQL->add_account('VERSION');
//		$MySQL->account('VERSION')->set_host('mysql51-70.perso');
//		$MySQL->account('VERSION')->set_credentials('neoblastdtb', 'M5001270357');
//		$MySQL->account('VERSION')->set_database('neoblastdtb');
//		$MySQL->account('VERSION')->set_tables('DEV_PROGRESS_VERSION', 'DEV_PROGRESS_UPDATE', 'DEV_PROGRESS_MILESTONES');
//
?>