-- MySQL dump 10.13  Distrib 5.5.52, for debian-linux-gnu (armv7l)
--
-- Host: localhost    Database: DEV.MY-GRIMDAWN-GEAR
-- ------------------------------------------------------
-- Server version	5.5.52-0+deb8u1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `ARTICLES`
--

DROP TABLE IF EXISTS `ARTICLES`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ARTICLES` (
  `ID` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `TITLE` varchar(70) NOT NULL,
  `ARTICLE` text NOT NULL,
  `CREATE_DATE` int(10) unsigned NOT NULL,
  `LAST_MODIFIED` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ARTICLES`
--

LOCK TABLES `ARTICLES` WRITE;
/*!40000 ALTER TABLE `ARTICLES` DISABLE KEYS */;
/*!40000 ALTER TABLE `ARTICLES` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ATTACHMENTS`
--

DROP TABLE IF EXISTS `ATTACHMENTS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ATTACHMENTS` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ATTACHMENT` varchar(10) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ATTACHMENTS`
--

LOCK TABLES `ATTACHMENTS` WRITE;
/*!40000 ALTER TABLE `ATTACHMENTS` DISABLE KEYS */;
INSERT INTO `ATTACHMENTS` VALUES (1,'item'),(2,'comp'),(3,'enchant'),(4,'prefix'),(5,'suffix');
/*!40000 ALTER TABLE `ATTACHMENTS` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ATTRIBUTES`
--

DROP TABLE IF EXISTS `ATTRIBUTES`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ATTRIBUTES` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ITEM` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SKILL` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SET` smallint(5) unsigned NOT NULL DEFAULT '0',
  `TIER` smallint(5) unsigned NOT NULL DEFAULT '0',
  `MASTER_TAG` varchar(30) NOT NULL,
  `SLAVE_TAG` varchar(30) DEFAULT '',
  `BASIC` bit(1) NOT NULL DEFAULT b'0',
  `PET` bit(1) NOT NULL DEFAULT b'0',
  `PROBABILITY` decimal(3,2) NOT NULL DEFAULT '1.00',
  `MASTER_VALUE_1` smallint(6) NOT NULL DEFAULT '0',
  `MASTER_VALUE_2` smallint(6) NOT NULL DEFAULT '0',
  `SLAVE_VALUE_1` smallint(6) NOT NULL DEFAULT '0',
  `SLAVE_VALUE_2` smallint(6) NOT NULL DEFAULT '0',
  `ATTACHMENT` smallint(5) unsigned NOT NULL DEFAULT '0',
  `PRIORITY` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `ITEM` (`ITEM`),
  KEY `SKILL` (`SKILL`),
  KEY `SET` (`SET`),
  KEY `PRIORITY` (`PRIORITY`),
  KEY `ATTACHMENT` (`ATTACHMENT`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ATTRIBUTES`
--

LOCK TABLES `ATTRIBUTES` WRITE;
/*!40000 ALTER TABLE `ATTRIBUTES` DISABLE KEYS */;
/*!40000 ALTER TABLE `ATTRIBUTES` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ATTRIBUTES_ATTACHMENTS`
--

DROP TABLE IF EXISTS `ATTRIBUTES_ATTACHMENTS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ATTRIBUTES_ATTACHMENTS` (
  `ID` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `ATTACHMENT` varchar(40) NOT NULL,
  `TAG` varchar(40) NOT NULL,
  `GROUP` varchar(20) NOT NULL,
  `ORDER` smallint(5) unsigned NOT NULL,
  `PC` bit(1) NOT NULL DEFAULT b'0',
  `PLUS` int(1) DEFAULT '0',
  `LF` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=147 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ATTRIBUTES_ATTACHMENTS`
--

LOCK TABLES `ATTRIBUTES_ATTACHMENTS` WRITE;
/*!40000 ALTER TABLE `ATTRIBUTES_ATTACHMENTS` DISABLE KEYS */;
INSERT INTO `ATTRIBUTES_ATTACHMENTS` VALUES (1,'physique','tagCharAttributeName02','char_stats',1,'\0',0,0),(2,'cunning','tagCharAttributeName01','char_stats',2,'\0',0,0),(3,'spirit','tagCharAttributeName03','char_stats',3,'\0',0,0),(4,'health','tagCharAttributeName04','char_stats',4,'\0',0,0),(5,'energy','tagCharAttributeName05','char_stats',5,'\0',0,0),(6,'offensive_ability','tagCharStatsOA','combat_stats',1,'\0',0,0),(7,'defensive_ability','tagCharStatsDA','combat_stats',2,'\0',0,0),(8,'damage_per_second','tagCharStatsDPSTitle','combat_stats',3,'\0',0,0),(9,'armor_rating','tagCharStatsArmorTotal','combat_stats',4,'\0',0,0),(10,'fire_resist','tagStatsResistance01','primary_resistances',1,'\0',0,0),(11,'cold_resist','tagStatsResistance03','primary_resistances',2,'\0',0,0),(12,'lightning_resist','tagStatsResistance02','primary_resistances',3,'\0',0,0),(13,'poison_resist','tagStatsResistance04','primary_resistances',4,'\0',0,0),(14,'piercing_resist','tagStatsResistance05','primary_resistances',5,'\0',0,0),(15,'bleeding_resist','tagStatsResistance06','advanced_resistances',1,'\0',0,0),(16,'vitality_resist','tagStatsResistance07','advanced_resistances',2,'\0',0,0),(17,'aether_resist','tagStatsResistance08','advanced_resistances',3,'\0',0,0),(18,'stunt_resist','tagStatsResistance09','advanced_resistances',4,'\0',0,0),(19,'chaos_resist','tagStatsResistance10','advanced_resistances',5,'\0',0,0),(20,'attach_speed','tagCharStatsAttackSpeed','character',1,'',0,0),(21,'critical_strike','tagCharStatsCritDmg','character',2,'',1,1),(22,'run_speed','tagCharStatsRunSpeed','character',3,'',0,0),(23,'health_regeneration','tagCharStatsLifeRegen','character',4,'\0',0,0),(24,'energy_regeneration','tagCharStatsEnergyRegen','character',5,'\0',0,0),(25,'energy_absorption','tagCharStatsEnergyAbsorption','character',6,'',0,0),(26,'constitution_bonus','tagCharStatsConstitution','character',7,'',1,0),(27,'experience_gained','tagCharStatsExpGainedBonus','character',8,'',1,0),(28,'light_radius','tagCharStatsLightRadiusBonus','character',9,'',1,1),(29,'all_skills','tagCharStatsAllSkillsBonus','character',10,'\0',1,0),(30,'soldier_skills','tagCharStatsSoldierSkillsBonus','character',11,'\0',1,0),(31,'demolitionist_skills','tagCharStatsDemoSkillsBonus','character',12,'\0',1,0),(32,'occultist_skills','tagCharStatsOccuSkillsBonus','character',13,'\0',1,0),(33,'nightblade_skills','tagCharStatsNightbladeSkillsBonus','character',14,'\0',1,0),(34,'arcanist_skills','tagCharStatsArcanistSkillsBonus','character',15,'\0',1,0),(35,'shaman_skills','tagCharStatsShamanSkillsBonus','character',16,'\0',1,0),(36,'inquisitor_skills','','character',17,'\0',1,0),(37,'necromancer_skills','','character',18,'\0',1,0),(38,'physical_damage','tagCharStatsPhysicalAbsDmg','physical',1,'\0',0,0),(39,'physical_modifier','tagCharStatsPhysicalPercentDmg','physical',2,'',1,1),(40,'pierce_damage','tagCharStatsPierceAbsDmg','physical',3,'\0',0,0),(41,'pierce_modifier','tagCharStatsPiercePercentDmg','physical',4,'',1,1),(42,'bleed_damage','tagCharStatsBleedAbsDmg','physical',5,'\0',0,0),(43,'bleed_modifier','tagCharStatsBleedPercentDmg','physical',6,'',1,0),(44,'bleed_duration','tagCharStatsBleedDuration','physical',7,'',0,1),(45,'trauma_damage','tagCharStatsInternalTraumaAbsDmg','physical',8,'\0',0,0),(46,'trauma_modifier','tagCharStatsInternalTraumaPercentDmg','physical',9,'',1,0),(47,'trauma_duration','tagCharStatsInternalTraumaDuration','physical',10,'',0,1),(48,'life_steal','tagCharStatsDamageToHealth','physical',11,'',0,0),(49,'cast_speed','tagCharStatsCastSpeed','magical',1,'',0,0),(50,'cooldown_reduction','tagCharStatsCooldownReduction','magical',2,'',0,0),(51,'skill_energy_cost','tagCharStatsManaCostReduction','magical',3,'',0,1),(52,'health_damage','tagCharStatsPercentHealthDamage','magical',4,'',0,1),(53,'fire_damage','tagCharStatsFireAbsDmg','magical',5,'\0',0,0),(54,'fire_modifier','tagCharStatsFirePercentDmg','magical',6,'',1,1),(55,'cold_damage','tagCharStatsColdAbsDmg','magical',7,'\0',0,0),(56,'cold_modifier','tagCharStatsColdPercentDmg','magical',8,'',1,1),(57,'lightning_damage','tagCharStatsLightningAbsDmg','magical',9,'\0',0,0),(58,'lightning_modifier','tagCharStatsLightningPercentDmg','magical',10,'',1,1),(59,'acid_damage','tagCharStatsAcidAbsDmg','magical',11,'\0',0,0),(60,'acid_modifier','tagCharStatsAcidPercentDmg','magical',12,'',1,1),(61,'vitality_damage','tagCharStatsVitalityAbsDmg','magical',13,'\0',0,0),(62,'vitality_modifier','tagCharStatsVitalityPercentDmg','magical',14,'',1,1),(63,'aether_damage','tagCharStatsAetherAbsDmg','magical',15,'\0',0,0),(64,'aether_modifier','tagCharStatsAetherPercentDmg','magical',16,'',1,1),(65,'chaos_damage','tagCharStatsChaosAbsDmg','magical',17,'\0',0,0),(66,'chaos_modifier','tagCharStatsChaosPercentDmg','magical',18,'',1,1),(67,'burn_damage','tagCharStatsBurnAbsDmg','magical',19,'\0',0,0),(68,'burn_modifier','tagCharStatsBurnPercentDmg','magical',20,'',1,0),(69,'burn_duration','tagCharStatsBurnDuration','magical',21,'',0,1),(70,'frostburn_damage','tagCharStatsColdburnAbsDmg','magical',22,'\0',0,0),(71,'frostburn_modifier','tagCharStatsColdburnPercentDmg','magical',23,'',1,0),(72,'frostburn_duration','tagCharStatsColdburnDuration','magical',24,'',0,1),(73,'electrocute_damage','tagCharStatsElectrocuteAbsDmg','magical',25,'\0',0,0),(74,'electrocute_modifier','tagCharStatsElectrocutePercentDmg','magical',26,'',1,0),(75,'electrocute_duration','tagCharStatsElectrocuteDuration','magical',27,'',0,1),(76,'poison_damage','tagCharStatsPoisonAbsDmg','magical',28,'\0',0,0),(77,'poison_modifier','tagCharStatsPoisonPercentDmg','magical',29,'',1,0),(78,'poison_duration','tagCharStatsPoisonDuration','magical',30,'',0,1),(79,'vitality_decay_damage','tagCharStatsVitalityDecayAbsDmg','magical',31,'\0',0,0),(80,'vitality_decay_modifier','tagCharStatsVitalityDecayPercentDmg','magical',32,'',1,0),(81,'vitality_decay_duration','tagCharStatsVitalityDecayDuration','magical',33,'',0,1),(82,'pet_life','tagCharStatsPetHealth','pet_bonuses',1,'',1,0),(83,'pet_damage','tagCharStatsPetDamage','pet_bonuses',2,'',1,0),(84,'pet_attack_speed','tagCharStatsPetAttackSpeed','pet_bonuses',3,'',1,0),(85,'pet_cast_speed','tagCharStatsPetCastSpeed','pet_bonuses',4,'',1,0),(86,'pet_run_speed','tagCharStatsPetRunSpeed','pet_bonuses',5,'',1,0),(87,'pet_offense_ability','tagCharStatsPetOA','pet_bonuses',6,'',1,0),(88,'pet_defense_ability','tagCharStatsPetDA','pet_bonuses',7,'',1,0),(89,'pet_fire_resist','tagCharStatsPetFireResist','pet_bonuses',8,'',1,0),(90,'pet_cold_resist','tagCharStatsPetColdResist','pet_bonuses',9,'',1,0),(91,'pet_lightning_resist','tagCharStatsPetLightningResist','pet_bonuses',10,'',1,0),(92,'pet_poison_acid_resist','tagCharStatsPetPoisonResist','pet_bonuses',11,'',1,0),(93,'pet_pierce_resist','tagCharStatsPetPierceResist','pet_bonuses',12,'',1,0),(94,'pet_bleeding_resist','tagCharStatsPetBleedResist','pet_bonuses',13,'',1,0),(95,'pet_vitality_resist','tagCharStatsPetVitalityResist','pet_bonuses',14,'',1,0),(96,'pet_aether_resist','tagCharStatsPetAetherResist','pet_bonuses',15,'',1,0),(97,'pet_chaos_resist','tagCharStatsPetChaosResist','pet_bonuses',16,'',1,0),(98,'chance_to_block','tagCharStatsBlockChance','defense',1,'',0,0),(99,'damage_blocked','tagCharStatsBlockAmount','defense',2,'\0',0,0),(100,'block_recovery','tagCharStatsBlockRecovery','defense',3,'',0,0),(101,'dodge_chance','tagCharStatsDodgeChance','defense',4,'',0,0),(102,'deflect_change','tagCharStatsProjDeflectChance','defense',5,'',0,1),(103,'physical_resist','tagCharStatsPhysicalResist','defense',6,'',0,0),(104,'disruption_resist','tagCharStatsDisruptionResist','defense',7,'',0,0),(105,'life_leech_resist','tagCharStatsLifeLeechResist','defense',8,'',0,0),(106,'energy_leech_resist','tagCharStatsEnergyLeechResist','defense',9,'',0,0),(107,'trap_resist','tagCharStatsTrapResist','defense',10,'',0,0),(108,'petrify_resist','tagCharStatsPetrifyResist','defense',11,'',0,0),(109,'freeze_resist','tagCharStatsFreezeResist','defense',12,'',0,0),(110,'sleep_resist','tagCharStatsSleepResist','defense',13,'',0,0),(111,'slow_resist','tagCharStatsSlowResist','defense',14,'',0,0),(112,'damage_reflect','tagCharStatsDamageReflect','retaliation',1,'\0',0,0),(113,'life_retaliation','tagCharStatsCurrentLifeReflect','retaliation',2,'\0',0,1),(114,'physical_retaliation','tagCharStatsPhysicalRet','retaliation',3,'\0',0,0),(115,'physical_retaliation_modifier','tagCharStatsPhysicalPercentRet','retaliation',4,'',1,1),(116,'pierce_retaliation','tagCharStatsPierceRet','retaliation',5,'\0',0,0),(117,'pierce_retaliation_modifier','tagCharStatsPiercePercentRet','retaliation',6,'',1,1),(118,'bleed_retaliation','tagCharStatsBleedRet','retaliation',7,'\0',0,0),(119,'bleed_retaliation_modifier','tagCharStatsBleedPercentRet','retaliation',8,'',1,1),(120,'trauma_retaliation','tagCharStatsInternalTraumaRet','retaliation',9,'\0',0,0),(121,'trauma_retaliation_modifier','tagCharStatsInternalTraumaPercentRet','retaliation',10,'',1,1),(122,'fire_retaliation','tagCharStatsFireRet','retaliation',11,'\0',0,0),(123,'fire_retaliation_modifier','tagCharStatsFirePercentRet','retaliation',12,'',1,1),(124,'cold_retaliation','tagCharStatsColdRet','retaliation',13,'\0',0,0),(125,'cold_retaliation_modifier','tagCharStatsColdPercentRet','retaliation',14,'',1,1),(126,'lightning_retaliation','tagCharStatsLightningRet','retaliation',15,'\0',0,0),(127,'lightning_retaliation_modifier','tagCharStatsLightningPercentRet','retaliation',16,'',1,1),(128,'acid_retaliation','tagCharStatsAcidRet','retaliation',17,'\0',0,0),(129,'acid_retaliation_modifier','tagCharStatsAcidPercentRet','retaliation',18,'',1,1),(130,'vitality_retaliation','tagCharStatsVitalityRet','retaliation',19,'\0',0,0),(131,'vitality_retaliation_modifier','tagCharStatsVitalityPercentRet','retaliation',20,'',1,1),(132,'aether_retaliation','tagCharStatsAetherRet','retaliation',21,'\0',0,0),(133,'aether_retaliation_modifier','tagCharStatsAetherPercentRet','retaliation',22,'',1,1),(134,'chaos_retaliation','tagCharStatsChaosRet','retaliation',23,'\0',0,0),(135,'chaos_retaliation_modifier','tagCharStatsChaosPercentRet','retaliation',24,'',1,1),(136,'burn_retaliation','tagCharStatsBurnRet','retaliation',25,'\0',0,0),(137,'burn_retaliation_modifier','tagCharStatsBurnPercentRet','retaliation',26,'',1,1),(138,'frostburn_retaliation','tagCharStatsColdburnRet','retaliation',27,'\0',0,0),(139,'frostburn_retaliation_modifier','tagCharStatsColdburnPercentRet','retaliation',28,'',1,1),(140,'electrocute_retaliation','tagCharStatsElectrocuteRet','retaliation',29,'\0',0,0),(141,'electrocute_retaliation_modifier','tagCharStatsElectrocutePercentRet','retaliation',30,'',1,1),(142,'poison_retaliation','tagCharStatsPoisonRet','retaliation',31,'\0',0,0),(143,'poison_retaliation_modifier','tagCharStatsPoisonPercentRet','retaliation',32,'',1,1),(144,'vitality_decay_retaliation','tagCharStatsVitalityDecayRet','retaliation',33,'\0',0,0),(145,'vitality_decay_retaliation_modifier','tagCharStatsVitalityDecayPercentRet','retaliation',34,'',1,1),(146,'average_item_level','tagCharStatsItemLevel','stats',1,'\0',0,0);
/*!40000 ALTER TABLE `ATTRIBUTES_ATTACHMENTS` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ATTRIBUTES_NAMES`
--

DROP TABLE IF EXISTS `ATTRIBUTES_NAMES`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ATTRIBUTES_NAMES` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `LANG` char(5) NOT NULL,
  `TAG` varchar(40) NOT NULL,
  `GENDER` char(2) NOT NULL DEFAULT 'ms',
  `NAME` varchar(150) NOT NULL,
  `NAME_MD5` char(40) NOT NULL,
  `DESCRIPTION` text NOT NULL,
  `DESCRIPTION_MD5` char(40) NOT NULL,
  `LAST_CHANGE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  KEY `LANG` (`LANG`),
  KEY `TAG` (`TAG`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ATTRIBUTES_NAMES`
--

LOCK TABLES `ATTRIBUTES_NAMES` WRITE;
/*!40000 ALTER TABLE `ATTRIBUTES_NAMES` DISABLE KEYS */;
/*!40000 ALTER TABLE `ATTRIBUTES_NAMES` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `BUILDS`
--

DROP TABLE IF EXISTS `BUILDS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `BUILDS` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `CODE` char(13) NOT NULL,
  `NAME` varchar(100) NOT NULL,
  `PASSWORD` char(40) NOT NULL,
  `MD5` char(40) NOT NULL,
  `FOLD_STATS` bit(1) NOT NULL,
  `FOLD_INVENTORY` bit(1) NOT NULL,
  `VIEWS` int(10) unsigned NOT NULL DEFAULT '0',
  `SLOT_HEAD_PREFIX` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_HEAD_ITEM` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_HEAD_SUFFIX` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_HEAD_COMP` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_HEAD_ENCHANT` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_NECK_PREFIX` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_NECK_ITEM` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_NECK_SUFFIX` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_NECK_COMP` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_NECK_ENCHANT` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_LRING_PREFIX` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_LRING_ITEM` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_LRING_SUFFIX` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_LRING_COMP` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_LRING_ENCHANT` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_RRING_PREFIX` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_RRING_ITEM` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_RRING_SUFFIX` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_RRING_COMP` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_RRING_ENCHANT` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_MAIN_1_PREFIX` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_MAIN_1_ITEM` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_MAIN_1_SUFFIX` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_MAIN_1_COMP` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_MAIN_1_ENCHANT` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_OFF_1_PREFIX` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_OFF_1_ITEM` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_OFF_1_SUFFIX` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_OFF_1_COMP` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_OFF_1_ENCHANT` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_MAIN_2_PREFIX` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_MAIN_2_ITEM` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_MAIN_2_SUFFIX` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_MAIN_2_COMP` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_MAIN_2_ENCHANT` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_OFF_2_PREFIX` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_OFF_2_ITEM` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_OFF_2_SUFFIX` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_OFF_2_COMP` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_OFF_2_ENCHANT` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_CHEST_PREFIX` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_CHEST_ITEM` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_CHEST_SUFFIX` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_CHEST_COMP` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_CHEST_ENCHANT` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_SHOULDERS_PREFIX` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_SHOULDERS_ITEM` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_SHOULDERS_SUFFIX` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_SHOULDERS_COMP` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_SHOULDERS_ENCHANT` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_HANDS_PREFIX` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_HANDS_ITEM` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_HANDS_SUFFIX` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_HANDS_COMP` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_HANDS_ENCHANT` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_LEGS_PREFIX` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_LEGS_ITEM` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_LEGS_SUFFIX` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_LEGS_COMP` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_LEGS_ENCHANT` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_FEET_PREFIX` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_FEET_ITEM` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_FEET_SUFFIX` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_FEET_COMP` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_FEET_ENCHANT` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_RELIC_PREFIX` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_RELIC_ITEM` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_RELIC_SUFFIX` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_RELIC_COMP` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_RELIC_ENCHANT` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_WAIST_PREFIX` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_WAIST_ITEM` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_WAIST_SUFFIX` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_WAIST_COMP` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_WAIST_ENCHANT` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_MEDAL_PREFIX` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_MEDAL_ITEM` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_MEDAL_SUFFIX` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_MEDAL_COMP` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SLOT_MEDAL_ENCHANT` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `CODE` (`CODE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `BUILDS`
--

LOCK TABLES `BUILDS` WRITE;
/*!40000 ALTER TABLE `BUILDS` DISABLE KEYS */;
/*!40000 ALTER TABLE `BUILDS` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `EFFECTS`
--

DROP TABLE IF EXISTS `EFFECTS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `EFFECTS` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `EFFECTS`
--

LOCK TABLES `EFFECTS` WRITE;
/*!40000 ALTER TABLE `EFFECTS` DISABLE KEYS */;
/*!40000 ALTER TABLE `EFFECTS` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `FAMILIES`
--

DROP TABLE IF EXISTS `FAMILIES`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `FAMILIES` (
  `ID` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `FAMILY` varchar(11) NOT NULL,
  `TAG` varchar(20) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `FAMILIES`
--

LOCK TABLES `FAMILIES` WRITE;
/*!40000 ALTER TABLE `FAMILIES` DISABLE KEYS */;
INSERT INTO `FAMILIES` VALUES (1,'armors','F_ARMORS'),(2,'weapons','F_WEAPONS'),(3,'jewelries','F_JEWELRIES'),(4,'miscs','F_MISCS'),(5,'affixes','F_AFFIXES');
/*!40000 ALTER TABLE `FAMILIES` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `IDENTIFIERS`
--

DROP TABLE IF EXISTS `IDENTIFIERS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `IDENTIFIERS` (
  `ID` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `TAG` varchar(40) NOT NULL,
  `PRIORITY` tinyint(3) unsigned NOT NULL,
  `TABLE_DATA` varchar(30) NOT NULL COMMENT 'Table fonctionnel',
  `FAMILY` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Dest : TABLE_DATA',
  `TYPE` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Dest : TABLE_DATA',
  `QUALITY` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `ATTACHMENT` smallint(5) unsigned NOT NULL DEFAULT '0',
  `WIDTH` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `HEIGHT` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `TABLE_NAME` varchar(30) NOT NULL,
  `DESCRIPTION` varchar(30) NOT NULL,
  `EXTEND_DESC_TAG` text NOT NULL,
  `IGNORE` text NOT NULL COMMENT 'Ignore on TAG',
  `CLEAN` text NOT NULL COMMENT 'Clean on TAG',
  `REPLACE` text NOT NULL COMMENT 'Replace on TAG',
  `COMMENT` text NOT NULL,
  `VIGNORE` text NOT NULL COMMENT 'Ignore on VALUE',
  `VCLEAN` text NOT NULL COMMENT 'Clean on VALUE',
  `VREPLACE` text NOT NULL COMMENT 'Replace on VALUE',
  `VCOMMENT` text NOT NULL,
  `LAST_CHANGE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `IDENTIFIERS`
--

LOCK TABLES `IDENTIFIERS` WRITE;
/*!40000 ALTER TABLE `IDENTIFIERS` DISABLE KEYS */;
INSERT INTO `IDENTIFIERS` VALUES (1,'tagWeaponSword[A-Z]',0,'ITEMS',2,13,1,1,1,3,'TAGS_NAMES','Desc','','','','','0','','','','','0000-00-00 00:00:00'),(2,'tagWeaponBlunt[A-Z]',0,'ITEMS',2,12,1,1,1,3,'TAGS_NAMES','Desc','','','','','0','','','','','0000-00-00 00:00:00'),(3,'tagWeaponAxe[A-Z]',0,'ITEMS',2,11,1,1,2,2,'TAGS_NAMES','Desc','','','','','0','','','','','0000-00-00 00:00:00'),(4,'tagWeaponGun1h[A-Z]',0,'ITEMS',2,8,1,1,2,2,'TAGS_NAMES','Desc','','','','','0','','','','','0000-00-00 00:00:00'),(5,'tagWeaponGun2h[A-Z]',0,'ITEMS',2,9,1,1,1,4,'TAGS_NAMES','Desc','','','','','0','','','','','0000-00-00 00:00:00'),(6,'tagWeaponCrossbow2h[A-Z]',0,'ITEMS',2,10,1,1,2,2,'TAGS_NAMES','Desc','','','','','0','','','','','0000-00-00 00:00:00'),(7,'tagWeaponMelee2h[A-Z]',0,'ITEMS',2,14,1,1,1,4,'TAGS_NAMES','Desc','','','','','0','','','','','0000-00-00 00:00:00'),(8,'tagWeaponCaster1h[A-Z]',0,'ITEMS',2,17,1,1,1,3,'TAGS_NAMES','Desc','','','','','0','','','','','0000-00-00 00:00:00'),(9,'tagWeaponArcane[A-Z]',0,'ITEMS',2,19,1,1,2,3,'TAGS_NAMES','Desc','','','','','0','','','','','0000-00-00 00:00:00'),(10,'tagWeaponCrossbow1h[A-Z]',0,'ITEMS',2,18,1,1,2,2,'TAGS_NAMES','Desc','','','','','0','','','','','0000-00-00 00:00:00'),(11,'tagShield[A-Z]',0,'ITEMS',2,15,1,1,2,3,'TAGS_NAMES','Desc','','Info$','','','0','','','','','0000-00-00 00:00:00'),(12,'tagFocus[A-Z]',0,'ITEMS',2,16,1,1,2,2,'TAGS_NAMES','Desc','','','','','0','','','','','0000-00-00 00:00:00'),(13,'tagHead[A-Z]',0,'ITEMS',1,1,1,1,2,2,'TAGS_NAMES','Desc','','','','','0','','','','','0000-00-00 00:00:00'),(14,'tagShoulder[A-Z]',0,'ITEMS',1,2,1,1,2,2,'TAGS_NAMES','Desc','','','','','0','','','','','0000-00-00 00:00:00'),(15,'tagTorso[A-Z]',0,'ITEMS',1,3,1,1,2,3,'TAGS_NAMES','Desc','','','','','0','','','','','0000-00-00 00:00:00'),(16,'tagWaist[A-Z]',0,'ITEMS',1,7,1,1,2,1,'TAGS_NAMES','Desc','','','','','0','','','','','0000-00-00 00:00:00'),(17,'tagHands[A-Z]',0,'ITEMS',1,4,1,1,2,2,'TAGS_NAMES','Desc','','','','','0','','','','','0000-00-00 00:00:00'),(18,'tagLegs[A-Z]',0,'ITEMS',1,5,1,1,2,3,'TAGS_NAMES','Desc','','','','','0','','','','','0000-00-00 00:00:00'),(19,'tagFeet[A-Z]',0,'ITEMS',1,6,1,1,2,2,'TAGS_NAMES','Desc','','','','','0','','','','','0000-00-00 00:00:00'),(20,'tagRing[A-Z]',0,'ITEMS',3,22,1,1,1,1,'TAGS_NAMES','Desc','','','','','0','','','','','0000-00-00 00:00:00'),(21,'tagNecklace[A-Z]',0,'ITEMS',3,20,1,1,1,1,'TAGS_NAMES','Desc','','','','','0','','','','','0000-00-00 00:00:00'),(22,'tagMedal[A-Z]',0,'ITEMS',3,21,1,1,1,1,'TAGS_NAMES','Desc','','','','','0','','','','','0000-00-00 00:00:00'),(23,'tagRelic[A-Z]',0,'ITEMS',4,23,6,1,1,1,'TAGS_NAMES','Desc','','Error$','','','0','','','','','0000-00-00 00:00:00'),(24,'tagEnchant[A-Z]',0,'ITEMS',4,25,8,3,1,2,'TAGS_NAMES','Desc','','[BC]$','A$','','0','','','','','0000-00-00 00:00:00'),(25,'tagComp[A-Z]',0,'ITEMS',4,24,7,2,1,1,'TAGS_NAMES','Desc','','(Crystal|Box|Scrap)$','Name$','','0','','','','','0000-00-00 00:00:00'),(26,'tagPrefix[A-Z]',0,'ITEMS',5,26,2,4,2,1,'TAGS_NAMES','Desc','','','','','0','','','','','0000-00-00 00:00:00'),(27,'tagSuffix[A-Z]',0,'ITEMS',5,27,2,5,2,1,'TAGS_NAMES','Desc','','','','','0','','','','','0000-00-00 00:00:00'),(28,'tagItemSkill[A-Z]',0,'',0,0,0,0,0,0,'SKILLS_NAMES','Description|Desc','','','Name','','0','','','','','2017-04-18 13:18:49'),(29,'tagCompSkill[A-Z]',0,'',0,0,0,0,0,0,'SKILLS_NAMES','Desc','','','Name','','0','','','','','0000-00-00 00:00:00'),(30,'tagRelicSkill[A-Z]',0,'',0,0,0,0,0,0,'SKILLS_NAMES','Desc','','','Name','','0','','','','','0000-00-00 00:00:00'),(31,'tagStyleUniqueTier2',0,'',0,0,0,0,0,0,'OTHER_NAMES','','','','','/^.*/xE/i','Empowered to xE','','','','','2017-04-16 03:06:37'),(32,'tagStyleUniqueTier3',0,'',0,0,0,0,0,0,'OTHER_NAMES','','','','','/^.*/xM/i','Mythical to xM','','','','','2017-04-16 03:06:54'),(33,'tagItemSet[CD]',0,'SETS',0,0,0,0,0,0,'SETS_NAMES','Desc','B','','Name','','Une description pour deux set . Normal & Empowered','','','','','2017-04-17 07:04:30'),(34,'tagAutoSkillCondition',0,'',0,0,0,0,0,0,'PROCS_NAMES','','','','','','','','','','','2017-04-17 07:04:30'),(35,'tagTooltipSkillsHeader',0,'',0,0,0,0,0,0,'PROCS_NAMES','','','','','','','','','','','2017-04-17 07:04:30'),(36,'tagCharAttribute0',0,'',0,0,0,0,0,0,'ATTRIBUTES_NAMES','','','R$','','','Attributs primaire (PHy,CUN,SPI,HEA,EN) en ignorant les attributs \"Range\"','','','','','2017-04-24 19:08:34'),(37,'tagChar(Constitution|Life)',0,'',0,0,0,0,0,0,'ATTRIBUTES_NAMES','','','R$','','','','','','','','2017-04-24 19:22:44'),(38,'tagCharMana',0,'',0,0,0,0,0,0,'ATTRIBUTES_NAMES','','','R$','','','Ingore Ranged attributes','','','','','2017-04-24 19:08:38'),(39,'tagChar(.*)Speed(Modifier)?(R)?=',0,'',0,0,0,0,0,0,'ATTRIBUTES_NAMES','','','R$','=$','','Ingore Ranged attributes','','','','','2017-04-24 19:08:41'),(40,'tagChar(Offensive|Defensive)',0,'',0,0,0,0,0,0,'ATTRIBUTES_NAMES','','','R$','','','','','','','','2017-04-24 19:08:43'),(41,'tagCharStatsPet',1,'',0,0,0,0,0,0,'ATTRIBUTES_NAME','','','','','','','','','/(.*)/[Pet] $1/i','','2017-04-25 10:43:36');
/*!40000 ALTER TABLE `IDENTIFIERS` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ITEMS`
--

DROP TABLE IF EXISTS `ITEMS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ITEMS` (
  `ID` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `ENABLED` bit(1) NOT NULL DEFAULT b'0',
  `FAMILY` tinyint(4) unsigned NOT NULL DEFAULT '1',
  `TYPE` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `QUALITY` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `ATTACHMENT` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `SET` smallint(5) unsigned NOT NULL DEFAULT '0',
  `TAG` varchar(30) NOT NULL,
  `EXTEND` char(2) DEFAULT '',
  `WIDTH` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `HEIGHT` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `LEVEL` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `PHYSIQUE` smallint(5) unsigned NOT NULL DEFAULT '0',
  `CUNNING` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SPIRIT` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SKILLED` bit(1) NOT NULL DEFAULT b'0',
  `LAST_CHANGE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  KEY `FAMILY` (`FAMILY`),
  KEY `TYPE` (`TYPE`),
  KEY `ENABLED` (`ENABLED`),
  KEY `QUALITY` (`QUALITY`),
  KEY `ATTACHMENT` (`ATTACHMENT`),
  KEY `SET` (`SET`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ITEMS`
--

LOCK TABLES `ITEMS` WRITE;
/*!40000 ALTER TABLE `ITEMS` DISABLE KEYS */;
/*!40000 ALTER TABLE `ITEMS` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `OTHER_NAMES`
--

DROP TABLE IF EXISTS `OTHER_NAMES`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `OTHER_NAMES` (
  `ID` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `LANG` char(5) NOT NULL,
  `TAG` varchar(40) NOT NULL,
  `GENDER` char(2) NOT NULL DEFAULT 'ms',
  `NAME` text NOT NULL,
  `NAME_MD5` char(40) NOT NULL,
  `DESCRIPTION` text NOT NULL,
  `DESCRIPTION_MD5` char(40) NOT NULL,
  `LAST_CHANGE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  KEY `LANG` (`LANG`),
  KEY `TAG` (`TAG`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `OTHER_NAMES`
--

LOCK TABLES `OTHER_NAMES` WRITE;
/*!40000 ALTER TABLE `OTHER_NAMES` DISABLE KEYS */;
/*!40000 ALTER TABLE `OTHER_NAMES` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PROCS_NAMES`
--

DROP TABLE IF EXISTS `PROCS_NAMES`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PROCS_NAMES` (
  `ID` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `LANG` char(5) NOT NULL,
  `TAG` varchar(30) NOT NULL,
  `GENDER` char(2) NOT NULL DEFAULT 'ms',
  `NAME` varchar(100) NOT NULL,
  `NAME_MD5` char(40) NOT NULL,
  `DESCRIPTION` text NOT NULL,
  `DESCRIPTION_MD5` char(40) NOT NULL,
  `LAST_CHANGE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  KEY `LANG` (`LANG`),
  KEY `TAG` (`TAG`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PROCS_NAMES`
--

LOCK TABLES `PROCS_NAMES` WRITE;
/*!40000 ALTER TABLE `PROCS_NAMES` DISABLE KEYS */;
/*!40000 ALTER TABLE `PROCS_NAMES` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `QUALITIES`
--

DROP TABLE IF EXISTS `QUALITIES`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `QUALITIES` (
  `ID` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `QUALITY` varchar(12) NOT NULL,
  `TAG` char(20) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `QUALITIES`
--

LOCK TABLES `QUALITIES` WRITE;
/*!40000 ALTER TABLE `QUALITIES` DISABLE KEYS */;
INSERT INTO `QUALITIES` VALUES (1,'common','Q_COMMON'),(2,'magic','tagMagic'),(3,'rare','tagMagic'),(4,'elite','tagEpic'),(5,'legendary','tagLegend'),(6,'relic','Q_RELIC'),(7,'comp','Q_COMP'),(8,'enchant','Q_ENCHANT');
/*!40000 ALTER TABLE `QUALITIES` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `SETS`
--

DROP TABLE IF EXISTS `SETS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SETS` (
  `ID` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `TAG` varchar(30) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SETS`
--

LOCK TABLES `SETS` WRITE;
/*!40000 ALTER TABLE `SETS` DISABLE KEYS */;
/*!40000 ALTER TABLE `SETS` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `SETS_NAMES`
--

DROP TABLE IF EXISTS `SETS_NAMES`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SETS_NAMES` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `LANG` char(5) NOT NULL,
  `TAG` varchar(30) NOT NULL,
  `GENDER` char(2) NOT NULL DEFAULT 'ms',
  `NAME` varchar(100) NOT NULL,
  `NAME_MD5` char(40) NOT NULL,
  `DESCRIPTION` text NOT NULL,
  `DESCRIPTION_MD5` char(40) NOT NULL,
  `LAST_CHANGE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  KEY `LANG` (`LANG`),
  KEY `TAG` (`TAG`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SETS_NAMES`
--

LOCK TABLES `SETS_NAMES` WRITE;
/*!40000 ALTER TABLE `SETS_NAMES` DISABLE KEYS */;
/*!40000 ALTER TABLE `SETS_NAMES` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `SKILLS`
--

DROP TABLE IF EXISTS `SKILLS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SKILLS` (
  `ID` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `ITEM` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SET` smallint(5) unsigned NOT NULL DEFAULT '0',
  `TAG` varchar(30) NOT NULL,
  `PROC` varchar(30) NOT NULL,
  `CHANCE` tinyint(3) unsigned NOT NULL,
  `EXTRA` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `SET` (`SET`),
  KEY `ITEM` (`ITEM`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SKILLS`
--

LOCK TABLES `SKILLS` WRITE;
/*!40000 ALTER TABLE `SKILLS` DISABLE KEYS */;
/*!40000 ALTER TABLE `SKILLS` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `SKILLS_NAMES`
--

DROP TABLE IF EXISTS `SKILLS_NAMES`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SKILLS_NAMES` (
  `ID` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `LANG` char(5) NOT NULL,
  `TAG` varchar(30) NOT NULL,
  `GENDER` char(2) NOT NULL DEFAULT 'ms',
  `NAME` varchar(100) NOT NULL,
  `NAME_MD5` char(40) NOT NULL,
  `DESCRIPTION` text NOT NULL,
  `DESCRIPTION_MD5` char(40) NOT NULL,
  `LAST_CHANGE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  KEY `LANG` (`LANG`),
  KEY `TAG` (`TAG`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SKILLS_NAMES`
--

LOCK TABLES `SKILLS_NAMES` WRITE;
/*!40000 ALTER TABLE `SKILLS_NAMES` DISABLE KEYS */;
/*!40000 ALTER TABLE `SKILLS_NAMES` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `TAGS_NAMES`
--

DROP TABLE IF EXISTS `TAGS_NAMES`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TAGS_NAMES` (
  `ID` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `LANG` char(5) NOT NULL,
  `TAG` varchar(30) NOT NULL,
  `GENDER` char(2) NOT NULL DEFAULT 'ms',
  `NAME` varchar(100) NOT NULL,
  `NAME_MD5` char(40) NOT NULL,
  `DESCRIPTION` text NOT NULL,
  `DESCRIPTION_MD5` char(40) NOT NULL,
  `LAST_CHANGE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  KEY `LANG` (`LANG`),
  KEY `TAG` (`TAG`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `TAGS_NAMES`
--

LOCK TABLES `TAGS_NAMES` WRITE;
/*!40000 ALTER TABLE `TAGS_NAMES` DISABLE KEYS */;
/*!40000 ALTER TABLE `TAGS_NAMES` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `TYPES`
--

DROP TABLE IF EXISTS `TYPES`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TYPES` (
  `ID` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `FAMILY` tinyint(3) unsigned NOT NULL,
  `TYPE` varchar(20) NOT NULL,
  `TAG` varchar(20) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `TYPES`
--

LOCK TABLES `TYPES` WRITE;
/*!40000 ALTER TABLE `TYPES` DISABLE KEYS */;
INSERT INTO `TYPES` VALUES (1,1,'head','T_HEAD'),(2,1,'shoulders','T_SHOULDERS'),(3,1,'chest','T_CHEST'),(4,1,'hands','T_HANDS'),(5,1,'legs','T_LEGS'),(6,1,'feet','T_FEET'),(7,1,'waist','T_WAIST'),(8,2,'pistols','T_PISTOLS'),(9,2,'rifles','T_RIFLES'),(10,2,'crossbows','T_CROSSBOWS'),(11,2,'axes','T_AXES'),(12,2,'maces','T_MACES'),(13,2,'swords','T_SWORDS'),(14,2,'two_handers','T_TWO_HANDERS'),(15,2,'shields','T_SHIELDS'),(16,2,'focus','T_FOCUS'),(17,2,'scepters_daggers','T_SCEPTERS_DAGGERS'),(18,2,'light_crossbows','T_LIGHT_CROSSBOWS'),(19,2,'staffs','T_STAFFS'),(20,3,'amulets','T_AMULETS'),(21,3,'medals','T_MEDALS'),(22,3,'rings','T_RINGS'),(23,4,'relics','T_RELICS'),(24,4,'components','T_COMPONENTS'),(25,4,'augments','T_AUGMENTS'),(26,5,'prefixes','T_PREFIXES'),(27,5,'suffixes','T_SUFFIXES');
/*!40000 ALTER TABLE `TYPES` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-04-26 20:45:57
