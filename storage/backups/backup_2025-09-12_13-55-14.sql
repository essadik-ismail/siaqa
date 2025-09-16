-- Laravel Database Backup
-- Generated: 2025-09-12 13:55:14

SET FOREIGN_KEY_CHECKS=0;

-- Table structure for table `activitylog`
DROP TABLE IF EXISTS `activitylog`;
CREATE TABLE `activitylog` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint(20) unsigned DEFAULT NULL,
  `log_name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activitylog_tenant_id_foreign` (`tenant_id`),
  KEY `activitylog_user_id_foreign` (`user_id`),
  CONSTRAINT `activitylog_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`),
  CONSTRAINT `activitylog_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `agences`
DROP TABLE IF EXISTS `agences`;
CREATE TABLE `agences` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint(20) unsigned DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `nom_agence` varchar(255) DEFAULT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `ville` varchar(255) DEFAULT NULL,
  `rc` varchar(255) DEFAULT NULL,
  `patente` varchar(255) DEFAULT NULL,
  `IF` varchar(255) DEFAULT NULL,
  `n_cnss` varchar(255) DEFAULT NULL,
  `ICE` varchar(255) DEFAULT NULL,
  `n_compte_bancaire` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `agences_tenant_id_foreign` (`tenant_id`),
  CONSTRAINT `agences_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `agences`
LOCK TABLES `agences` WRITE;
INSERT INTO `agences` VALUES ('1','1','agence1.png','Agence Centrale','123 Rue de la Paix','Paris','RC123456','PAT789012','IF345678','CNSS901234','ICE567890','FR7630001007941234567890185','1','2025-08-26 11:08:18','2025-08-29 12:06:35');
INSERT INTO `agences` VALUES ('2','1','agence2.png','Agence Aéroport','Terminal 2F, Aéroport Charles de Gaulle','Roissy','RC654321','PAT210987','IF876543','CNSS432109','ICE098765','FR7630001007941234567890186','1','2025-08-26 11:08:18','2025-08-29 12:06:35');
INSERT INTO `agences` VALUES ('3','1','agence1.png','Agence Centrale','123 Rue de la Paix','Paris','RC123456','PAT789012','IF345678','CNSS901234','ICE567890','FR7630001007941234567890185','1','2025-08-26 11:59:25','2025-08-29 12:06:35');
INSERT INTO `agences` VALUES ('4','1','agence2.png','Agence Aéroport','Terminal 2F, Aéroport Charles de Gaulle','Roissy','RC654321','PAT210987','IF876543','CNSS432109','ICE098765','FR7630001007941234567890186','1','2025-08-26 11:59:25','2025-08-29 12:06:35');
INSERT INTO `agences` VALUES ('5','1',NULL,'Main Office','123 Main Street, City Center','City Center',NULL,NULL,NULL,NULL,NULL,NULL,'1','2025-08-28 10:46:02','2025-08-28 10:46:02');
INSERT INTO `agences` VALUES ('6','2',NULL,'Bryan Brown LLC','Amet ut rerum duis',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1','2025-09-02 14:56:34','2025-09-02 14:56:34');
INSERT INTO `agences` VALUES ('7','3',NULL,'Silva and Leach Plc','Veniam ut illo dolo',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1','2025-09-02 15:02:26','2025-09-02 15:02:26');
INSERT INTO `agences` VALUES ('8','4',NULL,'Gamble and Wolf Associates','Quo dignissimos volu',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1','2025-09-02 15:08:03','2025-09-02 15:08:03');
INSERT INTO `agences` VALUES ('9','5',NULL,'Gamble and Wolf Associates','Quo dignissimos volu',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1','2025-09-02 15:12:30','2025-09-02 15:12:30');
INSERT INTO `agences` VALUES ('10','6',NULL,'Owen and Boone Associates','At suscipit quo eum',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1','2025-09-02 15:13:49','2025-09-02 15:13:49');
INSERT INTO `agences` VALUES ('11','7',NULL,'Mcintosh and Daugherty Co','Est quam fugit quia',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1','2025-09-12 12:13:45','2025-09-12 12:13:45');
UNLOCK TABLES;

-- Table structure for table `assurances`
DROP TABLE IF EXISTS `assurances`;
CREATE TABLE `assurances` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `vehicule_id` bigint(20) unsigned NOT NULL,
  `tenant_id` bigint(20) unsigned DEFAULT NULL,
  `numero_assurance` varchar(255) NOT NULL,
  `numero_police` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `date_prochaine` date NOT NULL,
  `date_reglement` date NOT NULL,
  `periode` varchar(255) DEFAULT NULL,
  `prix` double NOT NULL,
  `fichiers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`fichiers`)),
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `assurances_vehicule_id_foreign` (`vehicule_id`),
  KEY `assurances_tenant_id_foreign` (`tenant_id`),
  CONSTRAINT `assurances_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `assurances_vehicule_id_foreign` FOREIGN KEY (`vehicule_id`) REFERENCES `vehicules` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `assurances`
LOCK TABLES `assurances` WRITE;
INSERT INTO `assurances` VALUES ('1','1','1','ASS-001-2024','POL123456','2023-01-01','2024-12-31','2023-01-15','Annuelle','800',NULL,'Assurance complète','2025-08-26 11:08:19','2025-08-26 11:08:19');
INSERT INTO `assurances` VALUES ('2','2','1','ASS-002-2024','POL789012','2023-02-01','2024-01-31','2023-02-15','Annuelle','750',NULL,'Assurance standard','2025-08-26 11:08:19','2025-08-26 11:08:19');
INSERT INTO `assurances` VALUES ('9','2','1','Quaerat numquam nequ','A elit distinctio','2025-09-11','2025-09-26','2025-09-24','Amet ut nihil magna','15',NULL,NULL,'2025-09-11 10:25:59','2025-09-11 10:25:59');
UNLOCK TABLES;

-- Table structure for table `cache`
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `cache`
LOCK TABLES `cache` WRITE;
INSERT INTO `cache` VALUES ('car-rental-saas-cache-health_check','s:2:\"ok\";','1757685314');
UNLOCK TABLES;

-- Table structure for table `cache_locks`
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `charges`
DROP TABLE IF EXISTS `charges`;
CREATE TABLE `charges` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `designation` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `montant` double NOT NULL DEFAULT 0,
  `statut` enum('en_cours','termine','annule') NOT NULL DEFAULT 'en_cours',
  `fichier` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tenant_id` bigint(20) unsigned DEFAULT NULL,
  `vehicule_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `charges_tenant_id_foreign` (`tenant_id`),
  KEY `charges_vehicule_id_foreign` (`vehicule_id`),
  CONSTRAINT `charges_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`),
  CONSTRAINT `charges_vehicule_id_foreign` FOREIGN KEY (`vehicule_id`) REFERENCES `vehicules` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `charges`
LOCK TABLES `charges` WRITE;
INSERT INTO `charges` VALUES ('1','Maintenance véhicule','2025-08-16','150','en_cours',NULL,'Vidange et révision','2025-08-26 11:08:19','2025-08-26 11:08:19',NULL,NULL);
INSERT INTO `charges` VALUES ('2','Assurance mensuelle','2025-08-21','200','en_cours',NULL,'Paiement assurance AXA','2025-08-26 11:08:19','2025-08-26 11:08:19',NULL,NULL);
INSERT INTO `charges` VALUES ('3','Maintenance véhicule','2025-08-16','150','en_cours',NULL,'Vidange et révision','2025-08-26 11:59:31','2025-08-26 11:59:31',NULL,NULL);
INSERT INTO `charges` VALUES ('4','Assurance mensuelle','2025-08-21','200','en_cours',NULL,'Paiement assurance AXA','2025-08-26 11:59:31','2025-08-26 11:59:31',NULL,NULL);
INSERT INTO `charges` VALUES ('5','Assurance','2025-08-27','57855','en_cours',NULL,NULL,'2025-08-27 10:29:30','2025-08-27 10:29:30',NULL,NULL);
INSERT INTO `charges` VALUES ('6','Réparation','2025-09-12','74','en_cours','Reprehenderit dolor','Est vel voluptates','2025-09-04 13:12:21','2025-09-04 13:12:21','1',NULL);
INSERT INTO `charges` VALUES ('7','Maintenance','2019-06-23','8','en_cours','Similique molestiae','Accusamus tenetur ma','2025-09-04 13:13:05','2025-09-04 13:13:59','1',NULL);
INSERT INTO `charges` VALUES ('8','Carburant','2004-01-16','79','en_cours','Deserunt incididunt','Tempor expedita magn','2025-09-11 10:52:01','2025-09-11 10:52:01','1',NULL);
INSERT INTO `charges` VALUES ('9','Maintenance','2022-07-01','82','en_cours','Mollitia id sed cons','Accusantium impedit','2025-09-11 10:53:57','2025-09-11 10:53:57','1',NULL);
UNLOCK TABLES;

-- Table structure for table `clients`
DROP TABLE IF EXISTS `clients`;
CREATE TABLE `clients` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint(20) unsigned DEFAULT NULL,
  `type` enum('client','societe') NOT NULL DEFAULT 'client',
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) DEFAULT NULL,
  `ice_societe` varchar(255) DEFAULT NULL,
  `nom_societe` varchar(255) DEFAULT NULL,
  `date_naissance` date DEFAULT NULL,
  `lieu_de_naissance` varchar(255) DEFAULT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `telephone` varchar(255) DEFAULT NULL,
  `ville` varchar(255) DEFAULT NULL,
  `postal_code` varchar(255) DEFAULT NULL,
  `code_postal` varchar(255) DEFAULT NULL,
  `pays` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `nationalite` varchar(255) DEFAULT NULL,
  `numero_cin` varchar(255) DEFAULT NULL,
  `date_cin_expiration` date DEFAULT NULL,
  `numero_permis` varchar(255) DEFAULT NULL,
  `date_permis` date DEFAULT NULL,
  `date_obtention_permis` date DEFAULT NULL,
  `passport` varchar(255) DEFAULT NULL,
  `date_passport` date DEFAULT NULL,
  `numero_piece_identite` varchar(255) DEFAULT NULL,
  `type_piece_identite` varchar(255) DEFAULT NULL,
  `date_expiration_piece` date DEFAULT NULL,
  `profession` varchar(255) DEFAULT NULL,
  `employeur` varchar(255) DEFAULT NULL,
  `revenu_mensuel` decimal(10,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `bloquer` tinyint(1) NOT NULL DEFAULT 0,
  `is_blacklisted` tinyint(1) NOT NULL DEFAULT 0,
  `is_blacklist` tinyint(1) NOT NULL DEFAULT 0,
  `motif_blacklist` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `document` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `clients_tenant_id_foreign` (`tenant_id`),
  CONSTRAINT `clients_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `clients`
LOCK TABLES `clients` WRITE;
INSERT INTO `clients` VALUES ('1',NULL,'client','Dupont','Jean',NULL,NULL,'1985-03-15',NULL,'456 Avenue des Champs','06 12 34 56 78','Paris','75008',NULL,NULL,'jean.dupont@email.com','Française','123456789','2030-12-31','123456789012345','2003-06-20',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Client fidèle',NULL,'0','1','0',NULL,NULL,NULL,NULL,'2025-08-26 11:08:18','2025-08-29 09:54:19');
INSERT INTO `clients` VALUES ('2',NULL,'client','Martin','Marie',NULL,NULL,'1990-07-22',NULL,'789 Boulevard Saint-Germain','06 98 76 54 32','Paris','75006',NULL,NULL,'marie.martin@email.com','Française','987654321','2028-05-15','987654321098765','2008-09-15',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Nouveau client',NULL,'0','0','0',NULL,NULL,NULL,NULL,'2025-08-26 11:08:18','2025-08-26 11:08:18');
INSERT INTO `clients` VALUES ('3',NULL,'client','Dupont','Jean',NULL,NULL,'1985-03-15',NULL,'456 Avenue des Champs','06 12 34 56 78','Paris',NULL,'75008','France','jean.dupont@email.com','Française','123456789','2030-12-31','123456789012345',NULL,'2003-06-20',NULL,NULL,'CN123456789','carte_nationale','2030-12-31','Ingénieur','TechCorp','4500.00','Client fidèle','Client régulier, toujours ponctuel','0','0','0',NULL,NULL,NULL,NULL,'2025-08-26 11:59:26','2025-08-26 11:59:26');
INSERT INTO `clients` VALUES ('4',NULL,'client','Martin','Marie',NULL,NULL,'1990-07-22',NULL,'789 Boulevard Saint-Germain','06 98 76 54 32','Paris',NULL,'75006','France','marie.martin@email.com','Française','987654321','2028-05-15','987654321098765',NULL,'2008-09-15',NULL,NULL,'PP987654321','passeport','2028-05-15','Avocate','Cabinet Legal','5200.00','Nouveau client','Client professionnel, demande des véhicules haut de gamme','0','0','0',NULL,NULL,NULL,NULL,'2025-08-26 11:59:27','2025-08-26 11:59:27');
INSERT INTO `clients` VALUES ('6','1','client','Et provident ullamc','Et',NULL,NULL,NULL,NULL,'Ducimus aut nisi te','+1 (444) 785-3347','Unknown',NULL,'00000','Unknown','kupibesu@mailinator.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0','0','0',NULL,NULL,NULL,NULL,'2025-08-28 12:13:51','2025-09-03 14:14:06');
INSERT INTO `clients` VALUES ('7','1','client','Zia Baker',NULL,NULL,NULL,NULL,NULL,'Minim veritatis adip','+1 (205) 229-1013',NULL,NULL,NULL,NULL,'kevoremem@mailinator.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0','0','0',NULL,NULL,NULL,NULL,'2025-09-02 14:35:48','2025-09-04 09:23:41');
INSERT INTO `clients` VALUES ('8','1','client','Martin Fisher',NULL,NULL,NULL,NULL,NULL,'Repudiandae non inci','+1 (113) 128-2211',NULL,NULL,NULL,NULL,'xagoqyxidy@mailinator.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0','0','0',NULL,NULL,NULL,NULL,'2025-09-02 14:44:50','2025-09-02 14:44:50');
INSERT INTO `clients` VALUES ('9','1','client','Ulysses','Ghannam',NULL,NULL,'2004-03-03','Casablanca','Neque ad qui nihil m','+1 (114) 365-8114','CASABLANCA','20000',NULL,NULL,'hash@mailinator.com','Marocain',NULL,NULL,'60923',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0','0','0',NULL,NULL,NULL,NULL,'2025-09-03 09:12:00','2025-09-04 09:37:04');
INSERT INTO `clients` VALUES ('10','6','client','Wallace','Flavia',NULL,NULL,'2016-01-25',NULL,'Et autem molestiae q','+1 (352) 206-9841','Velit beatae officia',NULL,'292','Earum pariatur Aut','noke@mailinator.com',NULL,NULL,NULL,'65736',NULL,'1976-12-19',NULL,NULL,'41229','permis_conduire','2027-06-08','Vel soluta a at labo','Ipsam et esse quae e','974.00',NULL,'Rerum quas autem exe','0','0','0',NULL,NULL,NULL,NULL,'2025-09-03 11:58:49','2025-09-03 11:58:49');
INSERT INTO `clients` VALUES ('11','1','client','Holland','Justine',NULL,NULL,'1977-08-13',NULL,'Quibusdam rerum est','+1 (825) 262-1176','Dolores duis consect',NULL,NULL,NULL,'heqikam@mailinator.com',NULL,NULL,NULL,'42272',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0','0','0',NULL,NULL,NULL,NULL,'2025-09-04 09:40:55','2025-09-04 09:40:55');
INSERT INTO `clients` VALUES ('12','1','client','Clio Herring',NULL,NULL,NULL,NULL,NULL,'Quam iure magni non','+1 (863) 459-7309',NULL,NULL,NULL,NULL,'wurumexomi@mailinator.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0','0','0',NULL,NULL,NULL,NULL,'2025-09-09 11:33:12','2025-09-12 10:36:40');
INSERT INTO `clients` VALUES ('13','1','client','Autem laboris totam','Deserunt et ullamco',NULL,NULL,'2002-02-17',NULL,'Tenetur possimus ul','+1 (235) 499-3456','Sint non qui volupta',NULL,NULL,NULL,'bexilomuje@mailinator.com',NULL,NULL,NULL,'Labore animi quae i',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0','0','0',NULL,NULL,NULL,NULL,'2025-09-12 10:53:46','2025-09-12 10:53:46');
INSERT INTO `clients` VALUES ('14','1','client','Id est nihil quia q','Sit quia earum rem e',NULL,NULL,'1973-02-23',NULL,'Natus dolore a accus','+1 (701) 356-7279','Explicabo Deserunt',NULL,NULL,NULL,'jezexamis@mailinator.com',NULL,NULL,NULL,'Quidem ut molestiae',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0','0','0',NULL,NULL,NULL,NULL,'2025-09-12 11:00:02','2025-09-12 11:00:02');
UNLOCK TABLES;

-- Table structure for table `contrats`
DROP TABLE IF EXISTS `contrats`;
CREATE TABLE `contrats` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `vehicule_id` bigint(20) unsigned NOT NULL,
  `client_one_id` bigint(20) unsigned NOT NULL,
  `number_contrat` varchar(255) NOT NULL,
  `numero_document` varchar(255) NOT NULL,
  `etat_contrat` enum('en cours','termine') DEFAULT NULL,
  `statut` enum('en_cours','termine','annule') NOT NULL DEFAULT 'en_cours',
  `date_contrat` date DEFAULT NULL,
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  `heure_contrat` time DEFAULT NULL,
  `km_depart` varchar(255) DEFAULT NULL,
  `heure_depart` time DEFAULT NULL,
  `lieu_depart` varchar(255) DEFAULT NULL,
  `date_retour` date DEFAULT NULL,
  `heure_retour` time DEFAULT NULL,
  `lieu_livraison` varchar(255) DEFAULT NULL,
  `nbr_jours` int(11) DEFAULT NULL,
  `prix` double DEFAULT NULL,
  `total_ht` double DEFAULT NULL,
  `total_ttc` double DEFAULT NULL,
  `montant_total` double NOT NULL DEFAULT 0,
  `remise` double NOT NULL DEFAULT 0,
  `mode_reglement` enum('cheque','espece','tpe','versement') DEFAULT NULL,
  `caution_assurance` varchar(255) DEFAULT NULL,
  `position_resrvoir` enum('0','1/4','2/4','3/4','4/4') NOT NULL DEFAULT '0',
  `prolongation` varchar(255) DEFAULT NULL,
  `documents` tinyint(1) NOT NULL DEFAULT 1,
  `cric` tinyint(1) NOT NULL DEFAULT 1,
  `siege_enfant` tinyint(1) NOT NULL DEFAULT 0,
  `roue_secours` tinyint(1) NOT NULL DEFAULT 1,
  `poste_radio` tinyint(1) NOT NULL DEFAULT 1,
  `plaque_panne` tinyint(1) NOT NULL DEFAULT 1,
  `gillet` tinyint(1) NOT NULL DEFAULT 1,
  `extincteur` tinyint(1) NOT NULL DEFAULT 1,
  `client_two_id` bigint(20) unsigned DEFAULT NULL,
  `autre_fichier` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tenant_id` bigint(20) unsigned DEFAULT NULL,
  `reservation_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `contrats_vehicule_id_foreign` (`vehicule_id`),
  KEY `contrats_client_one_id_foreign` (`client_one_id`),
  KEY `contrats_client_two_id_foreign` (`client_two_id`),
  KEY `contrats_tenant_id_foreign` (`tenant_id`),
  KEY `contrats_reservation_id_foreign` (`reservation_id`),
  CONSTRAINT `contrats_client_one_id_foreign` FOREIGN KEY (`client_one_id`) REFERENCES `clients` (`id`),
  CONSTRAINT `contrats_client_two_id_foreign` FOREIGN KEY (`client_two_id`) REFERENCES `clients` (`id`),
  CONSTRAINT `contrats_reservation_id_foreign` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `contrats_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`),
  CONSTRAINT `contrats_vehicule_id_foreign` FOREIGN KEY (`vehicule_id`) REFERENCES `vehicules` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `contrats`
LOCK TABLES `contrats` WRITE;
INSERT INTO `contrats` VALUES ('1','3','3','CON-2025-001','DOC-2025-001','en cours','en_cours','1998-12-05',NULL,NULL,'18:28:00','Adipisci exercitatio','06:51:00','Nulla odit voluptate','2023-01-12','23:55:00','Quo reprehenderit e','82','96','83','42','0','71','tpe','Consequuntur consequ','1/4','Quia aut aute amet','0','1','1','1','1','1','0','1','4','Sit dolorum fugiat e','Non in duis officiis','2025-08-27 09:29:42','2025-08-27 09:29:42',NULL,NULL);
UNLOCK TABLES;

-- Table structure for table `failed_jobs`
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `interventions`
DROP TABLE IF EXISTS `interventions`;
CREATE TABLE `interventions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `vehicule_id` bigint(20) unsigned NOT NULL,
  `type_intervention` varchar(255) DEFAULT NULL,
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  `statut` varchar(255) NOT NULL DEFAULT 'planifiée',
  `priorite` varchar(255) DEFAULT NULL,
  `cout` decimal(10,2) DEFAULT NULL,
  `technicien` varchar(255) DEFAULT NULL,
  `kilometrage_intervention` int(11) DEFAULT NULL,
  `duree_estimee` decimal(8,2) DEFAULT NULL,
  `pieces_utilisees` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `tenant_id` bigint(20) unsigned DEFAULT NULL,
  `date` date DEFAULT NULL,
  `prix` decimal(8,2) DEFAULT 0.00,
  `fichier` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`fichier`)),
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `interventions_vehicule_id_foreign` (`vehicule_id`),
  KEY `interventions_tenant_id_foreign` (`tenant_id`),
  CONSTRAINT `interventions_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `interventions_vehicule_id_foreign` FOREIGN KEY (`vehicule_id`) REFERENCES `vehicules` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `interventions`
LOCK TABLES `interventions` WRITE;
INSERT INTO `interventions` VALUES ('1','11','reparation','2025-09-09','2025-10-09','planifiee','basse','93.00','Vel consequatur iure','68','57.00','Dolor earum eligendi','Et atque inventore d','1',NULL,'0.00',NULL,'Repellendus Expedit','2025-09-08 13:10:13','2025-09-08 13:25:39');
INSERT INTO `interventions` VALUES ('2','10','revision','2025-09-09','2025-10-09','planifiée','elevee','30.00','Maxime sint labore l','7','35.00','Sed architecto nostr','Cupiditate reiciendi','1',NULL,'0.00',NULL,'Molestiae consequunt','2025-09-08 13:16:46','2025-09-08 13:16:46');
UNLOCK TABLES;

-- Table structure for table `invoices`
DROP TABLE IF EXISTS `invoices`;
CREATE TABLE `invoices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint(20) unsigned NOT NULL,
  `subscription_id` bigint(20) unsigned NOT NULL,
  `stripe_invoice_id` varchar(255) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'USD',
  `status` enum('draft','open','paid','void','uncollectible') NOT NULL,
  `due_date` date NOT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoices_tenant_id_foreign` (`tenant_id`),
  KEY `invoices_subscription_id_foreign` (`subscription_id`),
  CONSTRAINT `invoices_subscription_id_foreign` FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `invoices_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `job_batches`
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `jobs`
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `marques`
DROP TABLE IF EXISTS `marques`;
CREATE TABLE `marques` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint(20) unsigned DEFAULT NULL,
  `marque` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `marques_tenant_id_foreign` (`tenant_id`),
  CONSTRAINT `marques_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `marques`
LOCK TABLES `marques` WRITE;
INSERT INTO `marques` VALUES ('1','1','Renault','renault.png','1','2025-08-26 11:08:18','2025-08-29 12:06:34');
INSERT INTO `marques` VALUES ('2','1','Peugeot','peugeot.png','1','2025-08-26 11:08:18','2025-08-29 12:06:34');
INSERT INTO `marques` VALUES ('3','1','Citroën','citroen.png','1','2025-08-26 11:08:18','2025-08-29 12:06:34');
INSERT INTO `marques` VALUES ('4','1','Volkswagen','volkswagen.png','1','2025-08-26 11:08:18','2025-08-29 12:06:34');
INSERT INTO `marques` VALUES ('5','1','BMW','bmw.png','1','2025-08-26 11:08:18','2025-08-29 12:06:34');
INSERT INTO `marques` VALUES ('6','1','Mercedes-Benz','mercedes.png','1','2025-08-26 11:08:18','2025-08-29 12:06:34');
INSERT INTO `marques` VALUES ('7','1','Toyota','toyota.png','1','2025-08-26 11:08:18','2025-08-29 12:06:34');
INSERT INTO `marques` VALUES ('8','1','Honda','honda.png','1','2025-08-26 11:08:18','2025-08-29 12:06:34');
INSERT INTO `marques` VALUES ('9','1','Renault','renault.png','1','2025-08-26 11:59:26','2025-08-29 12:06:34');
INSERT INTO `marques` VALUES ('10','1','Peugeot','peugeot.png','1','2025-08-26 11:59:26','2025-08-29 12:06:34');
INSERT INTO `marques` VALUES ('11','1','Citroën','citroen.png','1','2025-08-26 11:59:26','2025-08-29 12:06:34');
INSERT INTO `marques` VALUES ('12','1','Volkswagen','volkswagen.png','1','2025-08-26 11:59:26','2025-08-29 12:06:34');
INSERT INTO `marques` VALUES ('13','1','BMW','bmw.png','1','2025-08-26 11:59:26','2025-08-29 12:06:34');
INSERT INTO `marques` VALUES ('14','1','Mercedes-Benz','mercedes.png','1','2025-08-26 11:59:26','2025-08-29 12:06:34');
INSERT INTO `marques` VALUES ('15','1','Toyota','toyota.png','1','2025-08-26 11:59:26','2025-08-29 12:06:34');
INSERT INTO `marques` VALUES ('16','1','Honda','honda.png','1','2025-08-26 11:59:26','2025-08-29 12:06:34');
INSERT INTO `marques` VALUES ('18','1','Audi',NULL,'1','2025-08-28 10:46:01','2025-08-28 10:46:01');
INSERT INTO `marques` VALUES ('19','1','Ford',NULL,'1','2025-08-28 10:46:01','2025-08-28 10:46:01');
INSERT INTO `marques` VALUES ('20','1','Nissan',NULL,'1','2025-08-28 10:46:01','2025-08-28 10:46:01');
INSERT INTO `marques` VALUES ('21','1','Toyota',NULL,'1','2025-08-28 10:50:21','2025-08-28 10:50:21');
INSERT INTO `marques` VALUES ('22','1','Honda',NULL,'1','2025-08-28 10:53:01','2025-08-28 10:53:01');
INSERT INTO `marques` VALUES ('23','1','BMW',NULL,'1','2025-08-28 10:53:11','2025-08-28 10:53:11');
INSERT INTO `marques` VALUES ('24','1','Mercedes-Benz',NULL,'1','2025-08-28 10:56:18','2025-08-28 10:56:18');
INSERT INTO `marques` VALUES ('25','1','Audi',NULL,'1','2025-08-28 10:57:23','2025-08-28 10:57:23');
INSERT INTO `marques` VALUES ('26','1','Volkswagen',NULL,'1','2025-08-28 10:57:37','2025-08-28 10:57:37');
INSERT INTO `marques` VALUES ('27','1','Ford',NULL,'1','2025-08-28 11:01:26','2025-08-28 11:01:26');
INSERT INTO `marques` VALUES ('28','1','Nissan',NULL,'1','2025-08-28 11:03:23','2025-08-28 11:03:23');
INSERT INTO `marques` VALUES ('29','1','Peugeot',NULL,'1','2025-08-28 11:03:33','2025-08-28 11:03:33');
INSERT INTO `marques` VALUES ('30','1','Renault',NULL,'1','2025-08-28 11:03:55','2025-08-28 11:03:55');
INSERT INTO `marques` VALUES ('31','1','Hyundai',NULL,'1','2025-08-28 11:04:22','2025-08-28 11:04:22');
INSERT INTO `marques` VALUES ('32','1','Kia',NULL,'1','2025-08-28 11:05:22','2025-08-28 11:05:22');
INSERT INTO `marques` VALUES ('33','6','Similique laboris qu',NULL,'1','2025-09-03 14:22:34','2025-09-03 14:22:34');
INSERT INTO `marques` VALUES ('34','6','Irure ea temporibus',NULL,'1','2025-09-03 14:22:54','2025-09-03 14:22:54');
INSERT INTO `marques` VALUES ('35','1','Eu et nihil fugiat q',NULL,'1','2025-09-08 13:30:18','2025-09-08 13:30:18');
INSERT INTO `marques` VALUES ('36','1','Pariatur Aut sunt a',NULL,'1','2025-09-09 13:43:32','2025-09-09 13:43:32');
INSERT INTO `marques` VALUES ('37','1','Itaque vero omnis ei',NULL,'1','2025-09-09 13:43:49','2025-09-09 13:43:49');
UNLOCK TABLES;

-- Table structure for table `migrations`
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `migrations`
LOCK TABLES `migrations` WRITE;
INSERT INTO `migrations` VALUES ('1','0001_00_00_000000_create_saas_tables','1');
INSERT INTO `migrations` VALUES ('2','0001_00_01_000000_create_agences_table','1');
INSERT INTO `migrations` VALUES ('3','0001_00_02_000000_create_roles_table','1');
INSERT INTO `migrations` VALUES ('4','0001_00_03_000000_create_permissions_table','1');
INSERT INTO `migrations` VALUES ('5','0001_01_01_000000_create_users_table','1');
INSERT INTO `migrations` VALUES ('6','0001_01_01_000001_create_cache_table','1');
INSERT INTO `migrations` VALUES ('7','0001_01_01_000002_create_jobs_table','1');
INSERT INTO `migrations` VALUES ('8','2025_06_06_143533_create_personal_access_tokens_table','1');
INSERT INTO `migrations` VALUES ('9','2025_06_06_151001_create_clients_table','1');
INSERT INTO `migrations` VALUES ('10','2025_06_06_151251_create_marques_table','1');
INSERT INTO `migrations` VALUES ('11','2025_06_06_183931_create_vehicules_table','1');
INSERT INTO `migrations` VALUES ('12','2025_06_06_184321_create_reservations_table','1');
INSERT INTO `migrations` VALUES ('13','2025_06_06_184716_create_assurances_table','1');
INSERT INTO `migrations` VALUES ('14','2025_06_06_184829_create_vidanges_table','1');
INSERT INTO `migrations` VALUES ('15','2025_06_06_184926_create_visites_table','1');
INSERT INTO `migrations` VALUES ('16','2025_06_06_185501_create_contrats_table','1');
INSERT INTO `migrations` VALUES ('17','2025_06_06_190737_create_interventions_table','1');
INSERT INTO `migrations` VALUES ('18','2025_06_06_190825_create_notifications_table','1');
INSERT INTO `migrations` VALUES ('19','2025_06_06_190933_create_retour_contrats_table','1');
INSERT INTO `migrations` VALUES ('20','2025_06_06_191031_create_charges_table','1');
INSERT INTO `migrations` VALUES ('21','2025_06_06_191212_create_activitylog_table','1');
INSERT INTO `migrations` VALUES ('22','2025_08_01_103744_update_roles_table_structure','1');
INSERT INTO `migrations` VALUES ('23','2025_08_01_103831_update_permissions_table_structure','1');
INSERT INTO `migrations` VALUES ('24','2025_08_01_104428_create_role_permissions_table','1');
INSERT INTO `migrations` VALUES ('25','2025_08_01_104953_create_user_roles_table','1');
INSERT INTO `migrations` VALUES ('26','2025_08_01_105020_create_user_permissions_table','1');
INSERT INTO `migrations` VALUES ('27','2025_08_01_105623_add_is_active_to_users_table','1');
INSERT INTO `migrations` VALUES ('28','2025_08_26_113304_add_missing_columns_to_clients_table','2');
INSERT INTO `migrations` VALUES ('29','2025_08_26_113304_add_image_field_to_clients_table','3');
INSERT INTO `migrations` VALUES ('30','2025_08_26_115158_add_image_field_to_clients_table','3');
INSERT INTO `migrations` VALUES ('31','2025_08_26_120000_add_tenant_id_to_contrats_table','4');
INSERT INTO `migrations` VALUES ('32','2025_08_27_113623_add_missing_columns_to_charges_table','5');
INSERT INTO `migrations` VALUES ('33','2025_08_27_113716_add_missing_columns_to_contrats_table','5');
INSERT INTO `migrations` VALUES ('34','2025_08_27_135846_add_phone_and_address_to_users_table','6');
INSERT INTO `migrations` VALUES ('35','2025_08_29_091927_add_agency_id_to_users_table','7');
INSERT INTO `migrations` VALUES ('36','2025_01_01_000000_add_landing_display_to_vehicules_table','8');
INSERT INTO `migrations` VALUES ('38','2025_09_01_145046_update_tenants_table_structure','9');
INSERT INTO `migrations` VALUES ('39','2025_09_02_151116_fix_roles_table_unique_constraint','10');
INSERT INTO `migrations` VALUES ('40','2025_09_02_151729_fix_users_table_email_unique_constraint','11');
INSERT INTO `migrations` VALUES ('41','2025_09_04_091011_create_sessions_table','12');
INSERT INTO `migrations` VALUES ('42','2025_09_04_114023_fix_assurances_numero_police_column','13');
INSERT INTO `migrations` VALUES ('43','2025_09_04_114459_add_tenant_id_to_assurances_table','14');
INSERT INTO `migrations` VALUES ('44','2025_09_04_122259_add_missing_columns_to_vidanges_table','15');
INSERT INTO `migrations` VALUES ('45','2025_09_04_123040_rename_vidanges_columns_to_match_form','16');
INSERT INTO `migrations` VALUES ('46','2025_09_04_123324_fix_vidanges_prix_column_default','17');
INSERT INTO `migrations` VALUES ('47','2025_09_04_124603_fix_visites_table_structure','18');
INSERT INTO `migrations` VALUES ('48','2025_09_04_141112_fix_interventions_table_structure','19');
INSERT INTO `migrations` VALUES ('49','2025_09_08_121056_add_cout_column_to_interventions_table','20');
INSERT INTO `migrations` VALUES ('50','2025_09_08_121556_fix_interventions_table_structure','21');
INSERT INTO `migrations` VALUES ('51','2025_09_09_114622_add_reservation_id_to_contrats_table','22');
UNLOCK TABLES;

-- Table structure for table `notifications`
DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `type` varchar(255) NOT NULL,
  `read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `notifications`
LOCK TABLES `notifications` WRITE;
INSERT INTO `notifications` VALUES ('1','Nouvelle réservation','Une nouvelle réservation a été créée pour le véhicule AB-123-CD','info','0','2025-08-26 11:08:19','2025-08-26 11:08:19');
INSERT INTO `notifications` VALUES ('2','Maintenance prévue','Le véhicule EF-456-GH nécessite une maintenance','warning','0','2025-08-26 11:08:19','2025-08-26 11:08:19');
INSERT INTO `notifications` VALUES ('3','Nouvelle réservation','Une nouvelle réservation a été créée pour le véhicule AB-123-CD','info','0','2025-08-26 11:59:31','2025-08-26 11:59:31');
INSERT INTO `notifications` VALUES ('4','Maintenance prévue','Le véhicule EF-456-GH nécessite une maintenance','warning','0','2025-08-26 11:59:32','2025-08-26 11:59:32');
UNLOCK TABLES;

-- Table structure for table `password_reset_tokens`
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `permissions`
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `module` varchar(255) NOT NULL,
  `tenant_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_unique` (`name`),
  KEY `permissions_tenant_id_foreign` (`tenant_id`),
  CONSTRAINT `permissions_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `permissions`
LOCK TABLES `permissions` WRITE;
INSERT INTO `permissions` VALUES ('1','dashboard.view','View Dashboard','Can view the main dashboard','dashboard',NULL,'2025-08-26 11:08:09','2025-08-26 11:08:09');
INSERT INTO `permissions` VALUES ('2','dashboard.analytics','View Analytics','Can view detailed analytics and reports','dashboard',NULL,'2025-08-26 11:08:09','2025-08-26 11:08:09');
INSERT INTO `permissions` VALUES ('3','users.view','View Users','Can view user list','users',NULL,'2025-08-26 11:08:09','2025-08-26 11:08:09');
INSERT INTO `permissions` VALUES ('4','users.create','Create Users','Can create new users','users',NULL,'2025-08-26 11:08:09','2025-08-26 11:08:09');
INSERT INTO `permissions` VALUES ('5','users.edit','Edit Users','Can edit existing users','users',NULL,'2025-08-26 11:08:09','2025-08-26 11:08:09');
INSERT INTO `permissions` VALUES ('6','users.delete','Delete Users','Can delete users','users',NULL,'2025-08-26 11:08:09','2025-08-26 11:08:09');
INSERT INTO `permissions` VALUES ('7','users.roles','Manage User Roles','Can assign roles to users','users',NULL,'2025-08-26 11:08:09','2025-08-26 11:08:09');
INSERT INTO `permissions` VALUES ('8','roles.view','View Roles','Can view role list','roles',NULL,'2025-08-26 11:08:09','2025-08-26 11:08:09');
INSERT INTO `permissions` VALUES ('9','roles.create','Create Roles','Can create new roles','roles',NULL,'2025-08-26 11:08:09','2025-08-26 11:08:09');
INSERT INTO `permissions` VALUES ('10','roles.edit','Edit Roles','Can edit existing roles','roles',NULL,'2025-08-26 11:08:09','2025-08-26 11:08:09');
INSERT INTO `permissions` VALUES ('11','roles.delete','Delete Roles','Can delete roles','roles',NULL,'2025-08-26 11:08:09','2025-08-26 11:08:09');
INSERT INTO `permissions` VALUES ('12','roles.permissions','Manage Role Permissions','Can assign permissions to roles','roles',NULL,'2025-08-26 11:08:09','2025-08-26 11:08:09');
INSERT INTO `permissions` VALUES ('13','clients.view','View Clients','Can view client list','clients',NULL,'2025-08-26 11:08:09','2025-08-26 11:08:09');
INSERT INTO `permissions` VALUES ('14','clients.create','Create Clients','Can create new clients','clients',NULL,'2025-08-26 11:08:09','2025-08-26 11:08:09');
INSERT INTO `permissions` VALUES ('15','clients.edit','Edit Clients','Can edit existing clients','clients',NULL,'2025-08-26 11:08:09','2025-08-26 11:08:09');
INSERT INTO `permissions` VALUES ('16','clients.delete','Delete Clients','Can delete clients','clients',NULL,'2025-08-26 11:08:09','2025-08-26 11:08:09');
INSERT INTO `permissions` VALUES ('17','clients.blacklist','Manage Blacklist','Can manage client blacklist','clients',NULL,'2025-08-26 11:08:09','2025-08-26 11:08:09');
INSERT INTO `permissions` VALUES ('18','agencies.view','View Agencies','Can view agency list','agencies',NULL,'2025-08-26 11:08:09','2025-08-26 11:08:09');
INSERT INTO `permissions` VALUES ('19','agencies.create','Create Agencies','Can create new agencies','agencies',NULL,'2025-08-26 11:08:09','2025-08-26 11:08:09');
INSERT INTO `permissions` VALUES ('20','agencies.edit','Edit Agencies','Can edit existing agencies','agencies',NULL,'2025-08-26 11:08:09','2025-08-26 11:08:09');
INSERT INTO `permissions` VALUES ('21','agencies.delete','Delete Agencies','Can delete agencies','agencies',NULL,'2025-08-26 11:08:09','2025-08-26 11:08:09');
INSERT INTO `permissions` VALUES ('22','vehicles.view','View Vehicles','Can view vehicle list','vehicles',NULL,'2025-08-26 11:08:09','2025-08-26 11:08:09');
INSERT INTO `permissions` VALUES ('23','vehicles.create','Create Vehicles','Can create new vehicles','vehicles',NULL,'2025-08-26 11:08:09','2025-08-26 11:08:09');
INSERT INTO `permissions` VALUES ('24','vehicles.edit','Edit Vehicles','Can edit existing vehicles','vehicles',NULL,'2025-08-26 11:08:09','2025-08-26 11:08:09');
INSERT INTO `permissions` VALUES ('25','vehicles.delete','Delete Vehicles','Can delete vehicles','vehicles',NULL,'2025-08-26 11:08:10','2025-08-26 11:08:10');
INSERT INTO `permissions` VALUES ('26','vehicles.status','Manage Vehicle Status','Can change vehicle status','vehicles',NULL,'2025-08-26 11:08:10','2025-08-26 11:08:10');
INSERT INTO `permissions` VALUES ('27','brands.view','View Brands','Can view brand list','brands',NULL,'2025-08-26 11:08:10','2025-08-26 11:08:10');
INSERT INTO `permissions` VALUES ('28','brands.create','Create Brands','Can create new brands','brands',NULL,'2025-08-26 11:08:10','2025-08-26 11:08:10');
INSERT INTO `permissions` VALUES ('29','brands.edit','Edit Brands','Can edit existing brands','brands',NULL,'2025-08-26 11:08:10','2025-08-26 11:08:10');
INSERT INTO `permissions` VALUES ('30','brands.delete','Delete Brands','Can delete brands','brands',NULL,'2025-08-26 11:08:10','2025-08-26 11:08:10');
INSERT INTO `permissions` VALUES ('31','reservations.view','View Reservations','Can view reservation list','reservations',NULL,'2025-08-26 11:08:10','2025-08-26 11:08:10');
INSERT INTO `permissions` VALUES ('32','reservations.create','Create Reservations','Can create new reservations','reservations',NULL,'2025-08-26 11:08:10','2025-08-26 11:08:10');
INSERT INTO `permissions` VALUES ('33','reservations.edit','Edit Reservations','Can edit existing reservations','reservations',NULL,'2025-08-26 11:08:10','2025-08-26 11:08:10');
INSERT INTO `permissions` VALUES ('34','reservations.delete','Delete Reservations','Can delete reservations','reservations',NULL,'2025-08-26 11:08:10','2025-08-26 11:08:10');
INSERT INTO `permissions` VALUES ('35','reservations.confirm','Confirm Reservations','Can confirm reservations','reservations',NULL,'2025-08-26 11:08:10','2025-08-26 11:08:10');
INSERT INTO `permissions` VALUES ('36','reservations.cancel','Cancel Reservations','Can cancel reservations','reservations',NULL,'2025-08-26 11:08:10','2025-08-26 11:08:10');
INSERT INTO `permissions` VALUES ('37','contracts.view','View Contracts','Can view contract list','contracts',NULL,'2025-08-26 11:08:10','2025-08-26 11:08:10');
INSERT INTO `permissions` VALUES ('38','contracts.create','Create Contracts','Can create new contracts','contracts',NULL,'2025-08-26 11:08:10','2025-08-26 11:08:10');
INSERT INTO `permissions` VALUES ('39','contracts.edit','Edit Contracts','Can edit existing contracts','contracts',NULL,'2025-08-26 11:08:10','2025-08-26 11:08:10');
INSERT INTO `permissions` VALUES ('40','contracts.delete','Delete Contracts','Can delete contracts','contracts',NULL,'2025-08-26 11:08:10','2025-08-26 11:08:10');
INSERT INTO `permissions` VALUES ('41','contracts.sign','Sign Contracts','Can sign contracts','contracts',NULL,'2025-08-26 11:08:10','2025-08-26 11:08:10');
INSERT INTO `permissions` VALUES ('42','contracts.terminate','Terminate Contracts','Can terminate contracts','contracts',NULL,'2025-08-26 11:08:10','2025-08-26 11:08:10');
INSERT INTO `permissions` VALUES ('43','insurances.view','View Insurances','Can view insurance list','insurances',NULL,'2025-08-26 11:08:10','2025-08-26 11:08:10');
INSERT INTO `permissions` VALUES ('44','insurances.create','Create Insurances','Can create new insurances','insurances',NULL,'2025-08-26 11:08:10','2025-08-26 11:08:10');
INSERT INTO `permissions` VALUES ('45','insurances.edit','Edit Insurances','Can edit existing insurances','insurances',NULL,'2025-08-26 11:08:10','2025-08-26 11:08:10');
INSERT INTO `permissions` VALUES ('46','insurances.delete','Delete Insurances','Can delete insurances','insurances',NULL,'2025-08-26 11:08:10','2025-08-26 11:08:10');
INSERT INTO `permissions` VALUES ('47','insurances.renew','Renew Insurances','Can renew insurances','insurances',NULL,'2025-08-26 11:08:11','2025-08-26 11:08:11');
INSERT INTO `permissions` VALUES ('48','maintenance.view','View Maintenance','Can view maintenance records','maintenance',NULL,'2025-08-26 11:08:11','2025-08-26 11:08:11');
INSERT INTO `permissions` VALUES ('49','maintenance.create','Create Maintenance','Can create maintenance records','maintenance',NULL,'2025-08-26 11:08:11','2025-08-26 11:08:11');
INSERT INTO `permissions` VALUES ('50','maintenance.edit','Edit Maintenance','Can edit maintenance records','maintenance',NULL,'2025-08-26 11:08:11','2025-08-26 11:08:11');
INSERT INTO `permissions` VALUES ('51','maintenance.delete','Delete Maintenance','Can delete maintenance records','maintenance',NULL,'2025-08-26 11:08:11','2025-08-26 11:08:11');
INSERT INTO `permissions` VALUES ('52','maintenance.complete','Complete Maintenance','Can mark maintenance as complete','maintenance',NULL,'2025-08-26 11:08:11','2025-08-26 11:08:11');
INSERT INTO `permissions` VALUES ('53','charges.view','View Charges','Can view charges','financial',NULL,'2025-08-26 11:08:11','2025-08-26 11:08:11');
INSERT INTO `permissions` VALUES ('54','charges.create','Create Charges','Can create charges','financial',NULL,'2025-08-26 11:08:11','2025-08-26 11:08:11');
INSERT INTO `permissions` VALUES ('55','charges.edit','Edit Charges','Can edit charges','financial',NULL,'2025-08-26 11:08:11','2025-08-26 11:08:11');
INSERT INTO `permissions` VALUES ('56','charges.delete','Delete Charges','Can delete charges','financial',NULL,'2025-08-26 11:08:11','2025-08-26 11:08:11');
INSERT INTO `permissions` VALUES ('57','financial.reports','View Financial Reports','Can view financial reports','financial',NULL,'2025-08-26 11:08:11','2025-08-26 11:08:11');
INSERT INTO `permissions` VALUES ('58','notifications.view','View Notifications','Can view notifications','notifications',NULL,'2025-08-26 11:08:11','2025-08-26 11:08:11');
INSERT INTO `permissions` VALUES ('59','notifications.create','Create Notifications','Can create notifications','notifications',NULL,'2025-08-26 11:08:11','2025-08-26 11:08:11');
INSERT INTO `permissions` VALUES ('60','notifications.edit','Edit Notifications','Can edit notifications','notifications',NULL,'2025-08-26 11:08:11','2025-08-26 11:08:11');
INSERT INTO `permissions` VALUES ('61','notifications.delete','Delete Notifications','Can delete notifications','notifications',NULL,'2025-08-26 11:08:11','2025-08-26 11:08:11');
INSERT INTO `permissions` VALUES ('62','settings.view','View Settings','Can view system settings','settings',NULL,'2025-08-26 11:08:11','2025-08-26 11:08:11');
INSERT INTO `permissions` VALUES ('63','settings.edit','Edit Settings','Can edit system settings','settings',NULL,'2025-08-26 11:08:11','2025-08-26 11:08:11');
INSERT INTO `permissions` VALUES ('64','saas.tenants','Manage Tenants','Can manage SaaS tenants','saas',NULL,'2025-08-26 11:08:11','2025-08-26 11:08:11');
INSERT INTO `permissions` VALUES ('65','saas.subscriptions','Manage Subscriptions','Can manage SaaS subscriptions','saas',NULL,'2025-08-26 11:08:11','2025-08-26 11:08:11');
INSERT INTO `permissions` VALUES ('66','saas.billing','Manage Billing','Can manage SaaS billing','saas',NULL,'2025-08-26 11:08:11','2025-08-26 11:08:11');
UNLOCK TABLES;

-- Table structure for table `personal_access_tokens`
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `reservations`
DROP TABLE IF EXISTS `reservations`;
CREATE TABLE `reservations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `vehicule_id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `agence_id` bigint(20) unsigned DEFAULT NULL,
  `tenant_id` bigint(20) unsigned DEFAULT NULL,
  `numero_reservation` varchar(255) NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `heure_debut` time DEFAULT NULL,
  `heure_fin` time DEFAULT NULL,
  `lieu_depart` varchar(255) NOT NULL,
  `lieu_retour` varchar(255) NOT NULL,
  `nombre_passagers` int(11) NOT NULL DEFAULT 1,
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`options`)),
  `prix_total` decimal(10,2) NOT NULL,
  `caution` decimal(10,2) NOT NULL DEFAULT 0.00,
  `statut` enum('en_attente','confirmee','annulee','terminee') NOT NULL DEFAULT 'en_attente',
  `notes` text DEFAULT NULL,
  `motif_annulation` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reservations_numero_reservation_unique` (`numero_reservation`),
  KEY `reservations_vehicule_id_foreign` (`vehicule_id`),
  KEY `reservations_client_id_foreign` (`client_id`),
  KEY `reservations_agence_id_foreign` (`agence_id`),
  KEY `reservations_tenant_id_foreign` (`tenant_id`),
  CONSTRAINT `reservations_agence_id_foreign` FOREIGN KEY (`agence_id`) REFERENCES `agences` (`id`),
  CONSTRAINT `reservations_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`),
  CONSTRAINT `reservations_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`),
  CONSTRAINT `reservations_vehicule_id_foreign` FOREIGN KEY (`vehicule_id`) REFERENCES `vehicules` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `reservations`
LOCK TABLES `reservations` WRITE;
INSERT INTO `reservations` VALUES ('1','1','1','1',NULL,'RES-001-2024','2025-08-27','2025-08-29',NULL,NULL,'Agence Centrale','Agence Centrale','2',NULL,'135.00','200.00','confirmee','Réservation pour weekend',NULL,'2025-08-26 11:08:18','2025-08-26 11:59:31');
INSERT INTO `reservations` VALUES ('2','2','2','2',NULL,'RES-002-2024','2025-08-31','2025-09-02',NULL,NULL,'Agence Aéroport','Agence Aéroport','1',NULL,'165.00','250.00','en_attente','Réservation pour voyage d\'affaires',NULL,'2025-08-26 11:08:19','2025-08-26 11:59:31');
INSERT INTO `reservations` VALUES ('3','1','4','4',NULL,'RES-2025-003','2025-12-06','2025-12-08','13:25:00','03:34:00','Reprehenderit dolor','Rem harum obcaecati','6','[\"gps\",\"si\\u00e8ge_b\\u00e9b\\u00e9\"]','5.00','85.00','confirmee','Enim qui voluptas si',NULL,'2025-08-26 14:41:06','2025-08-26 14:41:06');
INSERT INTO `reservations` VALUES ('4','15','6','5','1','RES-2025-004','2025-08-29','2025-08-31',NULL,NULL,'Ducimus aut nisi te','Ducimus aut nisi te','1',NULL,'195.00','0.00','en_attente',NULL,NULL,'2025-08-28 12:13:51','2025-08-28 12:13:51');
INSERT INTO `reservations` VALUES ('5','10','7',NULL,'1','RES-20250902-0005','2025-09-03','2025-09-04',NULL,NULL,'Main Office','Main Office','1',NULL,'200.00','0.00','en_attente',NULL,NULL,'2025-09-02 14:35:48','2025-09-02 14:35:48');
INSERT INTO `reservations` VALUES ('6','10','6',NULL,'1','RES-20250902-0006','2025-09-19','2025-09-27',NULL,NULL,'Main Office','Main Office','1',NULL,'900.00','0.00','en_attente',NULL,NULL,'2025-09-02 14:37:42','2025-09-12 09:53:48');
INSERT INTO `reservations` VALUES ('7','8','8',NULL,'1','RES-20250902-0007','2025-09-20','2025-09-21',NULL,NULL,'Main Office','Main Office','1',NULL,'240.00','0.00','confirmee',NULL,NULL,'2025-09-02 14:44:50','2025-09-12 09:53:37');
INSERT INTO `reservations` VALUES ('8','2','9',NULL,'1','RES-20250903-0008','2025-09-03','2025-09-12',NULL,NULL,'Main Office','Main Office','1',NULL,'550.00','0.00','annulee',NULL,'v,klgjdslmj','2025-09-03 09:12:01','2025-09-12 09:53:32');
INSERT INTO `reservations` VALUES ('9','15','12',NULL,'1','RES-20250909-0009','2025-09-12','2025-09-18',NULL,NULL,'Main Office','Main Office','1',NULL,'650.00','0.00','terminee',NULL,'bghit','2025-09-09 11:33:12','2025-09-12 12:11:05');
UNLOCK TABLES;

-- Table structure for table `retour_contrats`
DROP TABLE IF EXISTS `retour_contrats`;
CREATE TABLE `retour_contrats` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `contrat_id` bigint(20) unsigned NOT NULL,
  `km_retour` double NOT NULL,
  `kilm_parcoru` varchar(255) NOT NULL,
  `heure_retour` time NOT NULL,
  `date_retour` date NOT NULL,
  `position_resrvoir` enum('0','1/4','2/4','3/4','4/4') NOT NULL DEFAULT '0',
  `lieu_livraison` varchar(255) NOT NULL,
  `observation` text NOT NULL,
  `etat_regelement` enum('paye','non paye') NOT NULL DEFAULT 'paye',
  `prolongation` enum('non','oui') NOT NULL DEFAULT 'non',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `retour_contrats_contrat_id_foreign` (`contrat_id`),
  CONSTRAINT `retour_contrats_contrat_id_foreign` FOREIGN KEY (`contrat_id`) REFERENCES `contrats` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `role_permissions`
DROP TABLE IF EXISTS `role_permissions`;
CREATE TABLE `role_permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` bigint(20) unsigned NOT NULL,
  `permission_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_permissions_role_id_permission_id_unique` (`role_id`,`permission_id`),
  KEY `role_permissions_permission_id_foreign` (`permission_id`),
  CONSTRAINT `role_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=135 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `role_permissions`
LOCK TABLES `role_permissions` WRITE;
INSERT INTO `role_permissions` VALUES ('1','1','1',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('2','1','2',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('3','1','3',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('4','1','4',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('5','1','5',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('6','1','6',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('7','1','7',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('8','1','8',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('9','1','9',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('10','1','10',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('11','1','11',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('12','1','12',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('13','1','13',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('14','1','14',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('15','1','15',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('16','1','16',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('17','1','17',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('18','1','18',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('19','1','19',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('20','1','20',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('21','1','21',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('22','1','22',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('23','1','23',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('24','1','24',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('25','1','25',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('26','1','26',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('27','1','27',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('28','1','28',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('29','1','29',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('30','1','30',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('31','1','31',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('32','1','32',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('33','1','33',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('34','1','34',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('35','1','35',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('36','1','36',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('37','1','37',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('38','1','38',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('39','1','39',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('40','1','40',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('41','1','41',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('42','1','42',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('43','1','43',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('44','1','44',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('45','1','45',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('46','1','46',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('47','1','47',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('48','1','48',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('49','1','49',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('50','1','50',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('51','1','51',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('52','1','52',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('53','1','53',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('54','1','54',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('55','1','55',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('56','1','56',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('57','1','57',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('58','1','58',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('59','1','59',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('60','1','60',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('61','1','61',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('62','1','62',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('63','1','63',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('64','1','64',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('65','1','65',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('66','1','66',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('67','2','1',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('68','2','2',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('69','2','13',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('70','2','14',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('71','2','15',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('72','2','16',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('73','2','17',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('74','2','18',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('75','2','19',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('76','2','20',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('77','2','21',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('78','2','22',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('79','2','23',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('80','2','24',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('81','2','25',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('82','2','26',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('83','2','27',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('84','2','28',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('85','2','29',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('86','2','30',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('87','2','31',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('88','2','32',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('89','2','33',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('90','2','34',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('91','2','35',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('92','2','36',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('93','2','37',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('94','2','38',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('95','2','39',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('96','2','40',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('97','2','41',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('98','2','42',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('99','2','43',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('100','2','44',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('101','2','45',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('102','2','46',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('103','2','47',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('104','2','48',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('105','2','49',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('106','2','50',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('107','2','51',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('108','2','52',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('109','2','53',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('110','2','54',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('111','2','55',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('112','2','56',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('113','2','57',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('114','2','58',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('115','2','59',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('116','2','60',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('117','2','61',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('118','2','62',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('119','2','63',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('120','3','1',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('121','3','2',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('122','3','3',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('123','3','8',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('124','3','13',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('125','3','18',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('126','3','22',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('127','3','27',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('128','3','31',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('129','3','37',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('130','3','43',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('131','3','48',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('132','3','53',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('133','3','58',NULL,NULL);
INSERT INTO `role_permissions` VALUES ('134','3','62',NULL,NULL);
UNLOCK TABLES;

-- Table structure for table `roles`
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `tenant_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_tenant_unique` (`name`,`tenant_id`),
  KEY `roles_tenant_id_foreign` (`tenant_id`),
  CONSTRAINT `roles_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `roles`
LOCK TABLES `roles` WRITE;
INSERT INTO `roles` VALUES ('1','super_admin','Super Administrator','Full system access with SaaS management capabilities',NULL,'2025-08-26 11:08:11','2025-08-26 11:08:11');
INSERT INTO `roles` VALUES ('2','admin','Administrator','Full access to all car rental operations within the tenant',NULL,'2025-08-26 11:08:11','2025-08-26 11:08:11');
INSERT INTO `roles` VALUES ('3','consultant','Consultant','Limited access for viewing and basic operations',NULL,'2025-08-26 11:08:12','2025-08-26 11:08:12');
INSERT INTO `roles` VALUES ('5','admin','Administrator','Administrator role for Owen and Boone Associates','6','2025-09-02 15:13:50','2025-09-02 15:13:50');
INSERT INTO `roles` VALUES ('6','admin','Administrator','Administrator role for Mcintosh and Daugherty Co','7','2025-09-12 12:13:46','2025-09-12 12:13:46');
UNLOCK TABLES;

-- Table structure for table `sessions`
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `subscriptions`
DROP TABLE IF EXISTS `subscriptions`;
CREATE TABLE `subscriptions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint(20) unsigned NOT NULL,
  `plan_name` varchar(255) NOT NULL,
  `stripe_subscription_id` varchar(255) DEFAULT NULL,
  `starts_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ends_at` timestamp NULL DEFAULT NULL,
  `trial_ends_at` timestamp NULL DEFAULT NULL,
  `status` enum('active','canceled','past_due','unpaid') NOT NULL,
  `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`features`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subscriptions_tenant_id_foreign` (`tenant_id`),
  CONSTRAINT `subscriptions_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `subscriptions`
LOCK TABLES `subscriptions` WRITE;
INSERT INTO `subscriptions` VALUES ('1','2','enterprise',NULL,'2025-09-02 14:56:34',NULL,NULL,'active','{\"max_users\":-1,\"max_vehicles\":-1,\"storage_gb\":100,\"api_calls\":-1,\"support_level\":\"dedicated\"}','2025-09-02 14:56:34','2025-09-02 14:56:34');
INSERT INTO `subscriptions` VALUES ('2','3','enterprise',NULL,'2025-09-02 15:02:26',NULL,NULL,'active','{\"max_users\":-1,\"max_vehicles\":-1,\"storage_gb\":100,\"api_calls\":-1,\"support_level\":\"dedicated\"}','2025-09-02 15:02:26','2025-09-02 15:02:26');
INSERT INTO `subscriptions` VALUES ('3','6','starter',NULL,'2025-09-02 15:13:50',NULL,NULL,'active','{\"max_users\":5,\"max_vehicles\":10,\"storage_gb\":5,\"api_calls\":1000,\"support_level\":\"email\"}','2025-09-02 15:13:50','2025-09-02 15:13:50');
INSERT INTO `subscriptions` VALUES ('4','7','starter',NULL,'2025-09-12 12:13:46',NULL,NULL,'active','{\"max_users\":5,\"max_vehicles\":10,\"storage_gb\":5,\"api_calls\":1000,\"support_level\":\"email\"}','2025-09-12 12:13:46','2025-09-12 12:13:46');
UNLOCK TABLES;

-- Table structure for table `tenants`
DROP TABLE IF EXISTS `tenants`;
CREATE TABLE `tenants` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `contact_phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `max_users` int(11) DEFAULT NULL,
  `max_vehicles` int(11) DEFAULT NULL,
  `domain` varchar(255) NOT NULL,
  `trial_ends_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `database` varchar(255) DEFAULT NULL,
  `subscription_plan` varchar(255) DEFAULT NULL,
  `stripe_customer_id` varchar(255) DEFAULT NULL,
  `stripe_subscription_id` varchar(255) DEFAULT NULL,
  `subscription_ends_at` timestamp NULL DEFAULT NULL,
  `settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`settings`)),
  PRIMARY KEY (`id`),
  UNIQUE KEY `tenants_domain_unique` (`domain`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `tenants`
LOCK TABLES `tenants` WRITE;
INSERT INTO `tenants` VALUES ('1','Main Tenant',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'localhost',NULL,'1','2025-08-28 10:45:25','2025-08-28 10:45:25',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO `tenants` VALUES ('2','Bryan Brown LLC','Bryan Brown LLC','tijymi@mailinator.com','+1 (787) 773-7398','Amet ut rerum duis','Reprehenderit aut a',NULL,NULL,'Temporibus mollit ut','2020-11-06 00:00:00','1','2025-09-02 14:56:34','2025-09-02 14:56:34',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO `tenants` VALUES ('3','Silva and Leach Plc','Silva and Leach Plc','nyruvufyp@mailinator.com','+1 (906) 978-1609','Veniam ut illo dolo','Voluptatem Incidunt',NULL,NULL,'Sit exercitationem a','2014-12-08 00:00:00','1','2025-09-02 15:02:26','2025-09-02 15:02:26',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO `tenants` VALUES ('4','Gamble and Wolf Associates','Gamble and Wolf Associates','qaqa@mailinator.com','+1 (131) 716-8291','Quo dignissimos volu','Deleniti officia qui','15','50','Cum soluta voluptate','2017-01-25 00:00:00','1','2025-09-02 15:08:03','2025-09-02 15:08:03',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO `tenants` VALUES ('5','Gamble and Wolf Associates','Gamble and Wolf Associates','qaqa@mailinator.com','+1 (131) 716-8291','Quo dignissimos volu','Deleniti officia qui','15','50','hi','2017-01-25 00:00:00','1','2025-09-02 15:12:30','2025-09-02 15:12:30',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO `tenants` VALUES ('6','Owen and Boone Associates','Owen and Boone Associates','mypapyky@mailinator.com','+1 (218) 125-8888','At suscipit quo eum','Aliqua Quos do aut','5','10','Quia molestiae ipsum','2003-07-29 00:00:00','1','2025-09-02 15:13:49','2025-09-03 09:19:25',NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO `tenants` VALUES ('7','Mcintosh and Daugherty Co','Mcintosh and Daugherty Co','fyzy@mailinator.com','+1 (794) 831-9662','Est quam fugit quia','Magni enim accusanti','5','10','my','2000-11-15 00:00:00','1','2025-09-12 12:13:45','2025-09-12 12:13:45',NULL,NULL,NULL,NULL,NULL,NULL);
UNLOCK TABLES;

-- Table structure for table `usage`
DROP TABLE IF EXISTS `usage`;
CREATE TABLE `usage` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint(20) unsigned NOT NULL,
  `feature` varchar(255) NOT NULL,
  `usage_count` int(11) NOT NULL DEFAULT 0,
  `limit` int(11) NOT NULL,
  `period` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `usage_tenant_id_foreign` (`tenant_id`),
  CONSTRAINT `usage_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `user_permissions`
DROP TABLE IF EXISTS `user_permissions`;
CREATE TABLE `user_permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `permission_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_permissions_user_id_permission_id_unique` (`user_id`,`permission_id`),
  KEY `user_permissions_permission_id_foreign` (`permission_id`),
  CONSTRAINT `user_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_permissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for table `user_roles`
DROP TABLE IF EXISTS `user_roles`;
CREATE TABLE `user_roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_roles_user_id_role_id_unique` (`user_id`,`role_id`),
  KEY `user_roles_role_id_foreign` (`role_id`),
  CONSTRAINT `user_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_roles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `user_roles`
LOCK TABLES `user_roles` WRITE;
INSERT INTO `user_roles` VALUES ('1','1','1',NULL,NULL);
INSERT INTO `user_roles` VALUES ('2','2','2',NULL,NULL);
INSERT INTO `user_roles` VALUES ('3','3','3',NULL,NULL);
INSERT INTO `user_roles` VALUES ('4','6','5',NULL,NULL);
INSERT INTO `user_roles` VALUES ('5','7','6',NULL,NULL);
UNLOCK TABLES;

-- Table structure for table `users`
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `tenant_id` bigint(20) unsigned DEFAULT NULL,
  `agency_id` bigint(20) unsigned DEFAULT NULL,
  `role_id` bigint(20) unsigned DEFAULT NULL,
  `agence_id` bigint(20) unsigned DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_tenant_unique` (`email`,`tenant_id`),
  KEY `users_tenant_id_foreign` (`tenant_id`),
  KEY `users_role_id_foreign` (`role_id`),
  KEY `users_agence_id_foreign` (`agence_id`),
  KEY `users_agency_id_foreign` (`agency_id`),
  CONSTRAINT `users_agence_id_foreign` FOREIGN KEY (`agence_id`) REFERENCES `agences` (`id`),
  CONSTRAINT `users_agency_id_foreign` FOREIGN KEY (`agency_id`) REFERENCES `agences` (`id`) ON DELETE SET NULL,
  CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
  CONSTRAINT `users_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `users`
LOCK TABLES `users` WRITE;
INSERT INTO `users` VALUES ('1','Super Administrator','superadmin@rental.com',NULL,NULL,NULL,'$2y$12$JHNW94wwdDt8YRpwQ9PDnOMCiLq1oehpEH19/TrzN.G39gL39BPga','1','1',NULL,NULL,NULL,NULL,'2025-08-26 11:08:17','2025-08-29 12:06:35');
INSERT INTO `users` VALUES ('2','Administrator','admin@rental.com',NULL,NULL,NULL,'$2y$12$qgBU5FThIx3JUJf8WnDw0uxG4ENS5virh47mIa1Fmh.P2ErqHpkRG','1','1',NULL,NULL,NULL,NULL,'2025-08-26 11:08:17','2025-08-29 12:06:35');
INSERT INTO `users` VALUES ('3','Consultant','consultant@rental.com',NULL,NULL,NULL,'$2y$12$KBVthJwP2lj3hyZ8jozc7OPVbLJWjh3S9q1mS/DCmZaPZ92qFJ2d2','1','1',NULL,NULL,NULL,NULL,'2025-08-26 11:08:18','2025-08-29 12:06:35');
INSERT INTO `users` VALUES ('4','Admin','qaqa@mailinator.com',NULL,NULL,'2025-09-02 15:08:04','$2y$12$iuCxAX8tG0sYY31s2pv0pO1RCLy8l1NS.oh5ruKZ5lD5X54WXR0yO','1','4',NULL,NULL,'8',NULL,'2025-09-02 15:08:04','2025-09-02 15:08:04');
INSERT INTO `users` VALUES ('6','Admin','mypapyky@mailinator.com',NULL,NULL,'2025-09-02 15:13:50','$2y$12$T.UptfINCosDWI5wRT5qMudjbZZlZJ55kOytEvqEL3hxAD.Rxbyya','1','6',NULL,NULL,'10',NULL,'2025-09-02 15:13:50','2025-09-02 15:13:50');
INSERT INTO `users` VALUES ('7','Admin','fyzy@mailinator.com',NULL,NULL,'2025-09-12 12:13:46','$2y$12$YQNKBYweBhuQxt8EzWBN1OtfLtj3XaatTtMydq/POjtMpzYXq.QxG','1','7',NULL,NULL,'11',NULL,'2025-09-12 12:13:46','2025-09-12 12:13:46');
UNLOCK TABLES;

-- Table structure for table `vehicules`
DROP TABLE IF EXISTS `vehicules`;
CREATE TABLE `vehicules` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint(20) unsigned DEFAULT NULL,
  `agence_id` bigint(20) unsigned DEFAULT NULL,
  `marque_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `immatriculation` varchar(255) NOT NULL,
  `statut` enum('disponible','en_location','en_maintenance','hors_service') NOT NULL DEFAULT 'disponible',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `landing_display` tinyint(1) NOT NULL DEFAULT 0,
  `landing_order` int(11) NOT NULL DEFAULT 0,
  `type_carburant` varchar(255) DEFAULT 'essence',
  `nombre_cylindre` int(11) NOT NULL DEFAULT 0,
  `nbr_place` int(11) NOT NULL DEFAULT 0,
  `reference` varchar(255) DEFAULT NULL,
  `serie` varchar(255) DEFAULT NULL,
  `fournisseur` varchar(255) DEFAULT NULL,
  `numero_facture` varchar(255) DEFAULT NULL,
  `prix_achat` decimal(10,2) NOT NULL DEFAULT 0.00,
  `prix_location_jour` decimal(10,2) NOT NULL DEFAULT 0.00,
  `duree_vie` varchar(255) DEFAULT NULL,
  `kilometrage_actuel` int(11) NOT NULL DEFAULT 0,
  `categorie_vehicule` enum('A','B','C','D','E') DEFAULT NULL,
  `couleur` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `kilometrage_location` varchar(255) DEFAULT NULL,
  `type_assurance` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vehicules_immatriculation_unique` (`immatriculation`),
  KEY `vehicules_tenant_id_foreign` (`tenant_id`),
  KEY `vehicules_agence_id_foreign` (`agence_id`),
  KEY `vehicules_marque_id_foreign` (`marque_id`),
  CONSTRAINT `vehicules_agence_id_foreign` FOREIGN KEY (`agence_id`) REFERENCES `agences` (`id`),
  CONSTRAINT `vehicules_marque_id_foreign` FOREIGN KEY (`marque_id`) REFERENCES `marques` (`id`),
  CONSTRAINT `vehicules_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `vehicules`
LOCK TABLES `vehicules` WRITE;
INSERT INTO `vehicules` VALUES ('1','1','1','1','Clio','AB-123-CD','en_location','1','0','0','essence','4','5',NULL,NULL,NULL,NULL,'0.00','45.00',NULL,'15000','B','Blanc',NULL,NULL,NULL,NULL,'Véhicule en excellent état','2025-08-26 11:08:18','2025-08-29 12:06:34');
INSERT INTO `vehicules` VALUES ('2','1','2','2','208','EF-456-GH','disponible','1','0','1','diesel','4','5',NULL,NULL,NULL,NULL,'0.00','55.00',NULL,'22000','B','Bleu',NULL,NULL,NULL,NULL,'Véhicule confortable','2025-08-26 11:08:18','2025-09-08 13:54:58');
INSERT INTO `vehicules` VALUES ('3','1','3','2','Lacy Langley','Mollit earum consequ','hors_service','1','0','0','gpl','12','17',NULL,NULL,NULL,NULL,'56.00','55.00',NULL,'28','D','Quia tempore fugit',NULL,NULL,NULL,NULL,'Maxime aspernatur fu','2025-08-26 14:24:37','2025-08-29 12:06:34');
INSERT INTO `vehicules` VALUES ('4','1','4','1','Brody Paul','Non reprehenderit n','en_maintenance','1','0','0','hybride','13','12',NULL,NULL,NULL,NULL,'90.00','14.00',NULL,'30','A','Facilis optio do pe',NULL,NULL,NULL,NULL,'Sed accusamus nihil','2025-08-27 12:11:27','2025-08-29 12:06:34');
INSERT INTO `vehicules` VALUES ('5','1','4','4','Erica Atkins','Non laboris officia','en_location','1','0','0','diesel','9','8',NULL,NULL,NULL,NULL,'44.00','65.00',NULL,'25','D','Nostrud eos pariatur',NULL,NULL,NULL,NULL,'Quia enim duis archi','2025-08-27 12:11:38','2025-08-29 12:06:34');
INSERT INTO `vehicules` VALUES ('6','1','5','7','Toyota Camry 2023','ABC-123','disponible','1','1','1','essence','4','5',NULL,NULL,NULL,NULL,'0.00','80.00',NULL,'15000',NULL,'Blanc',NULL,NULL,NULL,NULL,'Berline confortable et économique','2025-08-28 11:06:04','2025-09-10 13:55:27');
INSERT INTO `vehicules` VALUES ('7','1','5','8','Honda Civic 2022','DEF-456','disponible','1','0','1','essence','4','5',NULL,NULL,NULL,NULL,'0.00','75.00',NULL,'22000',NULL,'Gris',NULL,NULL,NULL,NULL,'Compacte sportive et fiable','2025-08-28 11:06:04','2025-09-08 13:54:14');
INSERT INTO `vehicules` VALUES ('8','1','5','5','BMW X3 2023','GHI-789','disponible','1','0','1','essence','6','5',NULL,NULL,NULL,NULL,'0.00','120.00',NULL,'8000',NULL,'Noir',NULL,NULL,NULL,NULL,'SUV luxueux et performant','2025-08-28 11:06:04','2025-09-08 13:54:21');
INSERT INTO `vehicules` VALUES ('9','1','5','6','Mercedes C-Class 2022','JKL-012','disponible','1','0','1','essence','4','5',NULL,NULL,NULL,NULL,'0.00','110.00',NULL,'18000',NULL,'Argent',NULL,NULL,NULL,NULL,'Berline premium et élégante','2025-08-28 11:06:04','2025-09-08 13:54:26');
INSERT INTO `vehicules` VALUES ('10','1','5','18','Audi A4 2023','MNO-345','disponible','1','0','1','essence','4','5',NULL,NULL,NULL,NULL,'0.00','100.00',NULL,'12000',NULL,'Bleu',NULL,NULL,NULL,NULL,'Berline technologique et confortable','2025-08-28 11:06:04','2025-09-08 13:54:29');
INSERT INTO `vehicules` VALUES ('11','1','5','4','Volkswagen Golf 2022','PQR-678','disponible','1','0','0','diesel','4','5',NULL,NULL,NULL,NULL,'0.00','70.00',NULL,'25000',NULL,'Rouge',NULL,NULL,NULL,NULL,'Compacte polyvalente et économique','2025-08-28 11:06:04','2025-09-08 13:54:33');
INSERT INTO `vehicules` VALUES ('12','1','5','19','Ford Mustang 2023','STU-901','disponible','1','0','0','essence','8','4',NULL,NULL,NULL,NULL,'0.00','150.00',NULL,'5000',NULL,'Jaune',NULL,NULL,NULL,NULL,'Coupé sportif et puissant','2025-08-28 11:06:04','2025-09-08 13:54:53');
INSERT INTO `vehicules` VALUES ('13','1','5','20','Nissan Qashqai 2022','VWX-234','disponible','1','0','0','essence','4','5',NULL,NULL,NULL,NULL,'0.00','85.00',NULL,'19000',NULL,'Vert',NULL,NULL,NULL,NULL,'SUV compact et familial','2025-08-28 11:06:04','2025-09-04 10:01:31');
INSERT INTO `vehicules` VALUES ('14','1','5','2','Peugeot 308 2023','YZA-567','disponible','1','0','0','essence','4','5',NULL,NULL,NULL,NULL,'0.00','75.00',NULL,'10000',NULL,'Orange',NULL,NULL,NULL,NULL,'Berline française moderne et élégante','2025-08-28 11:06:04','2025-08-28 11:06:04');
INSERT INTO `vehicules` VALUES ('15','1','5','1','Renault Clio 2023','BCD-890','disponible','1','1','0','essence','4','5',NULL,NULL,NULL,NULL,'0.00','65.00',NULL,'28000','C','Rose','vehicules/images/pt9eMOea68Rt2M7oRWgDtENOEKhdZwZtpVVhpa2a.png',NULL,NULL,NULL,'nadya','2025-08-28 11:06:05','2025-09-08 13:55:06');
INSERT INTO `vehicules` VALUES ('17','1','5','32','Kia Sportage 2022','HIJ-456','disponible','1','1','0','essence','4','5',NULL,NULL,NULL,NULL,'0.00','85.00',NULL,'15000',NULL,'Gris',NULL,NULL,NULL,NULL,'SUV compact et polyvalent','2025-08-28 11:06:05','2025-09-09 15:30:22');
INSERT INTO `vehicules` VALUES ('18','6','10','33','Honorato Simon','Pariatur Cumque odi','hors_service','1','0','0','electrique','16','9',NULL,NULL,NULL,NULL,'47.00','73.00',NULL,'0','A','Voluptate in volupta',NULL,NULL,NULL,NULL,'Corrupti est facere','2025-09-03 14:22:34','2025-09-03 14:22:34');
INSERT INTO `vehicules` VALUES ('19','6','10','34','Gay Franklin','Soluta illum simili','disponible','1','0','0','diesel','2','13',NULL,NULL,NULL,NULL,'64.00','75.00',NULL,'29','D','Asperiores nulla fug',NULL,NULL,NULL,NULL,'Modi non corrupti f','2025-09-03 14:22:54','2025-09-03 14:22:54');
INSERT INTO `vehicules` VALUES ('20','1','5','35','Kiara Hodges','Quibusdam eligendi o','en_maintenance','1','1','0','diesel','11','11',NULL,NULL,NULL,NULL,'36.00','69.00',NULL,'39','B','Excepteur expedita e',NULL,NULL,NULL,NULL,'Facere aut sequi del','2025-09-08 13:30:18','2025-09-10 13:55:25');
INSERT INTO `vehicules` VALUES ('21','1','5','37','Imani Mcleod','Vitae duis ducimus','en_maintenance','1','0','0','electrique','9','7',NULL,NULL,NULL,NULL,'28.00','95.00',NULL,'43','C','Dolorum et ipsum rem',NULL,NULL,NULL,NULL,'Eligendi minus moles','2025-09-09 13:43:32','2025-09-11 10:16:29');
UNLOCK TABLES;

-- Table structure for table `vidanges`
DROP TABLE IF EXISTS `vidanges`;
CREATE TABLE `vidanges` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `vehicule_id` bigint(20) unsigned NOT NULL,
  `date_prevue` date NOT NULL,
  `kilometrage_actuel` int(11) NOT NULL DEFAULT 0,
  `kilometrage_prochaine` int(11) NOT NULL DEFAULT 0,
  `type_huile` varchar(255) DEFAULT NULL,
  `quantite_huile` decimal(8,2) DEFAULT NULL,
  `filtre_huile` varchar(255) DEFAULT NULL,
  `filtre_air` varchar(255) DEFAULT NULL,
  `filtre_carburant` varchar(255) DEFAULT NULL,
  `cout_estime` decimal(8,2) DEFAULT NULL,
  `statut` varchar(255) NOT NULL DEFAULT 'planifiee',
  `notes` text DEFAULT NULL,
  `tenant_id` bigint(20) unsigned DEFAULT NULL,
  `prix` decimal(8,2) DEFAULT 0.00,
  `fichier` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`fichier`)),
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vidanges_vehicule_id_foreign` (`vehicule_id`),
  KEY `vidanges_tenant_id_foreign` (`tenant_id`),
  CONSTRAINT `vidanges_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `vidanges_vehicule_id_foreign` FOREIGN KEY (`vehicule_id`) REFERENCES `vehicules` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `vidanges`
LOCK TABLES `vidanges` WRITE;
INSERT INTO `vidanges` VALUES ('1','15','2025-09-17','50','50','autre',NULL,'premium','standard','haute_performance','64.00','planifiee','Omnis id id ut nemo','1','64.00',NULL,NULL,'2025-09-04 12:36:15','2025-09-04 12:36:15');
INSERT INTO `vidanges` VALUES ('3','6','2018-07-29','7','22','Est minus cum id am',NULL,'Atque enim maiores f','Commodo porro eos qu','Blanditiis lorem et','64.00','annulee','Eum duis explicabo','1','64.00',NULL,NULL,'2025-09-08 11:51:33','2025-09-08 11:52:03');
INSERT INTO `vidanges` VALUES ('4','21','2026-02-21','48','62','autre',NULL,'premium','sport','haute_performance','45.00','annulee','Ut cillum mollitia s','1','45.00',NULL,NULL,'2025-09-11 10:27:24','2025-09-11 10:27:24');
UNLOCK TABLES;

-- Table structure for table `visites`
DROP TABLE IF EXISTS `visites`;
CREATE TABLE `visites` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `vehicule_id` bigint(20) unsigned NOT NULL,
  `date_visite` date NOT NULL,
  `type_visite` varchar(255) DEFAULT NULL,
  `resultat` varchar(255) DEFAULT NULL,
  `observations` text DEFAULT NULL,
  `statut` varchar(255) NOT NULL DEFAULT 'en_attente',
  `tenant_id` bigint(20) unsigned DEFAULT NULL,
  `kilometrage_actuel` int(11) NOT NULL DEFAULT 0,
  `prochaine_visite` int(11) NOT NULL DEFAULT 0,
  `prix` decimal(8,2) DEFAULT 0.00,
  `fichier` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`fichier`)),
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `visites_vehicule_id_foreign` (`vehicule_id`),
  KEY `visites_tenant_id_foreign` (`tenant_id`),
  CONSTRAINT `visites_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `visites_vehicule_id_foreign` FOREIGN KEY (`vehicule_id`) REFERENCES `vehicules` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `visites`
LOCK TABLES `visites` WRITE;
INSERT INTO `visites` VALUES ('1','6','2007-06-10','visite_technique',NULL,NULL,'terminée','1','0','0','0.00',NULL,NULL,'2025-09-04 12:50:24','2025-09-04 12:50:24');
INSERT INTO `visites` VALUES ('2','14','1972-07-13','verification_securite',NULL,'Esse aliquid saepe a','annulée','1','0','0','0.00',NULL,NULL,'2025-09-04 12:52:34','2025-09-08 12:07:29');
UNLOCK TABLES;

SET FOREIGN_KEY_CHECKS=1;
