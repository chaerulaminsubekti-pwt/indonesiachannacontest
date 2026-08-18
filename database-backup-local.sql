-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: 127.0.0.1    Database: portal-icc
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `bank_accounts`
--

DROP TABLE IF EXISTS `bank_accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bank_accounts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint(20) unsigned NOT NULL,
  `nama_bank` varchar(255) NOT NULL,
  `nomor_rekening` varchar(255) NOT NULL,
  `atas_nama` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bank_accounts_event_id_foreign` (`event_id`),
  CONSTRAINT `bank_accounts_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bank_accounts`
--

LOCK TABLES `bank_accounts` WRITE;
/*!40000 ALTER TABLE `bank_accounts` DISABLE KEYS */;
INSERT INTO `bank_accounts` VALUES (1,13,'Mandiri','1390028794216','Chaerudin Ahmad',1,'2026-08-02 11:15:31','2026-08-02 11:15:31');
/*!40000 ALTER TABLE `bank_accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('laravel-cache-0716d9708d321ffb6a00818614779e779925365c','i:1;',1787039143),('laravel-cache-0716d9708d321ffb6a00818614779e779925365c:timer','i:1787039143;',1787039143),('laravel-cache-spatie.permission.cache','a:3:{s:5:\"alias\";a:0:{}s:11:\"permissions\";a:0:{}s:5:\"roles\";a:0:{}}',1787125061);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `certificates`
--

DROP TABLE IF EXISTS `certificates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `certificates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `winner_id` bigint(20) unsigned DEFAULT NULL,
  `participant_id` bigint(20) unsigned DEFAULT NULL,
  `nomor_sertifikat` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `kode_verifikasi` varchar(255) DEFAULT NULL,
  `generated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `certificates_winner_id_foreign` (`winner_id`),
  KEY `certificates_participant_id_foreign` (`participant_id`),
  CONSTRAINT `certificates_participant_id_foreign` FOREIGN KEY (`participant_id`) REFERENCES `participants` (`id`) ON DELETE SET NULL,
  CONSTRAINT `certificates_winner_id_foreign` FOREIGN KEY (`winner_id`) REFERENCES `winners` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `certificates`
--

LOCK TABLES `certificates` WRITE;
/*!40000 ALTER TABLE `certificates` DISABLE KEYS */;
INSERT INTO `certificates` VALUES (4,1,NULL,'ICC/7/1/20260709','certificates/7/1.pdf','FVCPHY349IVE','2026-07-09 12:54:07','2026-07-09 12:54:07','2026-07-09 12:54:08'),(17,6,NULL,'ICC/7/6/20260710','certificates/7/6.pdf','UZBT9JVLQZ8N','2026-07-09 20:59:30','2026-07-09 20:59:30','2026-07-09 20:59:30'),(18,7,NULL,'ICC/7/7/20260710','certificates/7/7.pdf','QBMWCBSFFB46','2026-07-09 21:32:44','2026-07-09 21:32:44','2026-07-09 21:32:44'),(19,8,NULL,'ICC/9/8/20260710','certificates/9/8.pdf','IH3JJKTVVARR','2026-07-09 21:59:42','2026-07-09 21:59:42','2026-07-09 21:59:42'),(20,9,NULL,'ICC/10/9/20260710','certificates/10/9.pdf','GHPEQWXA2G7M','2026-07-09 22:54:24','2026-07-09 22:54:24','2026-07-09 22:54:25');
/*!40000 ALTER TABLE `certificates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contacts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `jabatan` varchar(255) DEFAULT NULL,
  `no_wa` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contacts`
--

LOCK TABLES `contacts` WRITE;
/*!40000 ALTER TABLE `contacts` DISABLE KEYS */;
/*!40000 ALTER TABLE `contacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event_classes`
--

DROP TABLE IF EXISTS `event_classes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_classes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint(20) unsigned NOT NULL,
  `nama_kelas` varchar(255) NOT NULL,
  `harga_tiket` decimal(12,2) DEFAULT NULL,
  `biaya_pendaftaran` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `event_classes_event_id_foreign` (`event_id`),
  CONSTRAINT `event_classes_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_classes`
--

LOCK TABLES `event_classes` WRITE;
/*!40000 ALTER TABLE `event_classes` DISABLE KEYS */;
INSERT INTO `event_classes` VALUES (1,13,'Andrao',200000.00,0.00,'2026-08-02 20:32:24','2026-08-02 20:32:24'),(2,13,'Asiatica',200000.00,0.00,'2026-08-02 23:52:59','2026-08-02 23:52:59'),(3,13,'Limbata Junior',225000.00,0.00,'2026-08-03 01:01:59','2026-08-03 01:01:59'),(4,13,'Limbata Beginner',200000.00,0.00,'2026-08-03 01:02:32','2026-08-03 01:02:32'),(5,13,'Auranti Beginner',200000.00,0.00,'2026-08-03 01:02:45','2026-08-03 01:02:45'),(6,13,'Auranti Junior',225000.00,0.00,'2026-08-03 01:02:52','2026-08-03 01:02:52'),(7,13,'Auranti Senior',225000.00,0.00,'2026-08-03 01:03:10','2026-08-03 01:03:10'),(8,13,'Red Progres',200000.00,0.00,'2026-08-03 01:03:18','2026-08-03 01:03:18'),(9,13,'Red Beginner',225000.00,0.00,'2026-08-03 01:03:29','2026-08-03 01:03:29'),(10,13,'Stewarti Beginner',200000.00,0.00,'2026-08-03 01:03:41','2026-08-03 01:03:41'),(11,13,'Pulchra Beginner',200000.00,0.00,'2026-08-03 01:04:04','2026-08-03 01:04:04'),(12,13,'Pulchra Junior',225000.00,0.00,'2026-08-03 01:04:13','2026-08-03 01:04:13'),(13,13,'Yellow Progres',200000.00,0.00,'2026-08-03 01:04:23','2026-08-03 01:04:23'),(14,13,'Yellow Beginner',225000.00,0.00,'2026-08-03 01:04:30','2026-08-03 01:04:30'),(15,13,'Yellow Juvenile',250000.00,0.00,'2026-08-03 01:04:40','2026-08-03 01:04:40');
/*!40000 ALTER TABLE `event_classes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event_cps`
--

DROP TABLE IF EXISTS `event_cps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_cps` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint(20) unsigned NOT NULL,
  `nama` varchar(255) NOT NULL,
  `no_wa` varchar(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `event_cps_event_id_foreign` (`event_id`),
  CONSTRAINT `event_cps_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_cps`
--

LOCK TABLES `event_cps` WRITE;
/*!40000 ALTER TABLE `event_cps` DISABLE KEYS */;
INSERT INTO `event_cps` VALUES (2,13,'Chaerul','6285171001596','2026-07-30 04:14:30','2026-07-30 04:14:30'),(3,13,'Bang Igo','6285201166787','2026-07-30 04:16:53','2026-07-30 04:16:53');
/*!40000 ALTER TABLE `event_cps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event_flyers`
--

DROP TABLE IF EXISTS `event_flyers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_flyers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint(20) unsigned NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `event_flyers_event_id_foreign` (`event_id`),
  CONSTRAINT `event_flyers_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_flyers`
--

LOCK TABLES `event_flyers` WRITE;
/*!40000 ALTER TABLE `event_flyers` DISABLE KEYS */;
INSERT INTO `event_flyers` VALUES (5,7,'events/flyers/01KX42DSJVHHB77NPVD7C1WFY3.jpg','Flayer Event','2026-07-09 11:32:16','2026-07-09 11:32:16'),(6,7,'events/flyers/01KX42E84GQXDDC3ZXXTNZTE6X.jpg','Flayer Sponsor','2026-07-09 11:32:31','2026-07-09 11:32:31'),(7,9,'events/flyers/01KX56856ZDENPPGF9F1C5KPXK.jpeg','Flayer Event','2026-07-09 21:58:20','2026-07-09 21:58:20'),(8,9,'events/flyers/01KX569FE4KX42P3W0XAZ3SMJQ.jpeg',NULL,'2026-07-09 21:59:04','2026-07-09 21:59:04'),(9,10,'events/flyers/01KX59C06CHRZ9E34YFNXB0RTD.jpg','Flayer Event','2026-07-09 22:52:52','2026-07-09 22:52:52'),(10,10,'events/flyers/01KX59CHMZSYC5Z5YTYVHGXEEV.jpg','Flayer Sponsor','2026-07-09 22:53:10','2026-07-09 22:53:10'),(12,13,'events/flyers/01KYSBA92HPJ3VPN9YVB3CB4FP.jpeg','Flayer Event','2026-07-30 04:07:23','2026-07-30 04:07:23'),(13,13,'events/flyers/01KZ1W3PTZEBQZ2K77HWBXP6DK.jpeg','Flayer Sponsor','2026-08-02 11:34:49','2026-08-02 11:34:49');
/*!40000 ALTER TABLE `event_flyers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event_galleries`
--

DROP TABLE IF EXISTS `event_galleries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_galleries` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint(20) unsigned NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `event_galleries_event_id_foreign` (`event_id`),
  CONSTRAINT `event_galleries_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_galleries`
--

LOCK TABLES `event_galleries` WRITE;
/*!40000 ALTER TABLE `event_galleries` DISABLE KEYS */;
INSERT INTO `event_galleries` VALUES (1,7,'event-galleries/01KX42NME7ZPY0DKBEJF3YMADG.jpg','Team Ontran-Ontran','2026-07-09 11:36:33','2026-07-09 11:36:33'),(2,7,'event-galleries/01KX42P6AXAPC5MBT0C5Y1ABQY.jpg','Big Bos Citra Jaya Snakehead','2026-07-09 11:36:51','2026-07-09 11:36:51');
/*!40000 ALTER TABLE `event_galleries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event_judge`
--

DROP TABLE IF EXISTS `event_judge`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_judge` (
  `event_id` bigint(20) unsigned NOT NULL,
  `judge_id` bigint(20) unsigned NOT NULL,
  `urutan` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`event_id`,`judge_id`),
  UNIQUE KEY `event_judge_event_id_urutan_unique` (`event_id`,`urutan`),
  KEY `event_judge_judge_id_foreign` (`judge_id`),
  CONSTRAINT `event_judge_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  CONSTRAINT `event_judge_judge_id_foreign` FOREIGN KEY (`judge_id`) REFERENCES `judges` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_judge`
--

LOCK TABLES `event_judge` WRITE;
/*!40000 ALTER TABLE `event_judge` DISABLE KEYS */;
INSERT INTO `event_judge` VALUES (7,2,1),(7,1,2),(7,5,3),(9,1,1),(9,2,2),(9,4,3),(9,3,4),(10,1,1),(10,5,2),(10,4,3),(13,1,1),(13,4,2);
/*!40000 ALTER TABLE `event_judge` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organizer_id` bigint(20) unsigned DEFAULT NULL,
  `nama_event` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `venue` varchar(255) DEFAULT NULL,
  `kategori` varchar(255) DEFAULT NULL,
  `tema` varchar(255) DEFAULT NULL,
  `wilayah_kota` varchar(255) DEFAULT NULL,
  `flyer` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `google_sheet_url` varchar(255) DEFAULT NULL,
  `no_wa_cp` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `events_slug_unique` (`slug`),
  KEY `events_organizer_id_foreign` (`organizer_id`),
  CONSTRAINT `events_organizer_id_foreign` FOREIGN KEY (`organizer_id`) REFERENCES `organizers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events`
--

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
INSERT INTO `events` VALUES (7,7,'Purwokerto Channa Party #1','purwokerto-channa-party-1-En2EA0','2025-07-24','2025-07-27','Gedung Kelurahan Rempoah Baturaden','mini_contest','Pantang Pulang Sebelum Menang','Jawa Tengah',NULL,NULL,'selesai','https://docs.google.com/spreadsheets/d/1CP_Df-WTRokRXZxN2tKtozNXT7T_zRVDjlzaIfAq7gE','6285171001596','2026-07-09 10:53:44','2026-07-10 20:14:45'),(9,9,'Nusatic International Channa Contest 2026','nusatic-international-channa-contest-2026-Y2vSOK','2026-06-11','2026-06-14','ICE BSD Hall 9 Tanggerang','Nasional',NULL,'DKI Jakarta',NULL,NULL,'approved','https://docs.google.com/spreadsheets/d/1uKe60VoMkQm6P8Z8Sr4tXcwA84of2_2ca5BugT0Qixo/edit?gid=110256422#gid=110256422',NULL,'2026-07-09 21:55:33','2026-07-09 22:40:55'),(10,10,'Aqua Culture Festival - Gelaran Channa Ontran Ontran','aqua-culture-festival-gelaran-channa-ontran-ontran-zJWSBe','2026-04-02','2026-04-05','Gedung Auditorium UPS Tegal','mini_contest',NULL,'Jawa Tengah',NULL,NULL,'approved','https://docs.google.com/spreadsheets/u/2/d/19WlqW4bozNxUDFf1VtSUKAgWug_YDKLERcKsR09Qu8E/edit?gid=1277878169#gid=1277878169',NULL,'2026-07-09 22:51:18','2026-07-09 22:54:57'),(13,13,'Kutuk Meresahkan #5','kutuk-meresahkan-5-5WQz6S','2026-09-10','2026-09-13','Hotel Grand Mega Guci','series_icc','Kutuk Meresahkan #5','Jawa tengah','events/flyers/01KYSB5GD0AK23S64EFS568WJK.jpeg','≡ƒöÑ KUTUK MERESAHKAN GANG5AL ≡ƒöÑ \nSeries INDONESIA CHANNA CONTEST \nSeason #1 \nHallo CHANNA LOVERS !!  \nKutuk Meresahkan hadir lagi nih, kita gasskan acara kontes ikan channa KUTUK MERESAHKAN GANG5AL ! \nAyo IKUTI, SAKSIKAN dan DAFTARKAN ikan channa kalian di acara KUTUK MERESAHKAN GANG5AL yang akan diselenggarakan pada 10-13 September 2026 bertempat di Hotel Grand Mega Guci Kabupaten Tegal .\nSiapkan diri serta gaco kalian dan segera daftarkan ikan kalian. Awasss jangan sampai kehabisan slot ! \nKami tunggu kehadiran kalian semua ≡ƒöÑ\n≡ƒô▒ Info lebih lanjut hubungi kontak pada pamflet ya ≡ƒæî≡ƒÅ╝','approved','https://docs.google.com/spreadsheets/d/1YQtZ6L-P7BF765HPnaZ5wrd4zQnMwuvQ9qnD1e5wBDc/edit?usp=sharing','6285171001596','2026-07-30 04:04:08','2026-08-17 23:55:55');
/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `icc_galleries`
--

DROP TABLE IF EXISTS `icc_galleries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `icc_galleries` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `judul_album` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `icc_galleries`
--

LOCK TABLES `icc_galleries` WRITE;
/*!40000 ALTER TABLE `icc_galleries` DISABLE KEYS */;
INSERT INTO `icc_galleries` VALUES (2,'Purwokerto Channa Party #1','icc-galleries/01KX56WJDNWC7FG8ZDN8QCRAER.jpg',NULL,NULL,'2026-07-09 22:09:29','2026-07-09 22:09:29'),(3,'Nusatic Internation Channa Contest 2026','icc-galleries/01KX59MHYKF44V3EY27SPWE7QG.jpg','Nusatic Internation Channa Contest 2026',NULL,'2026-07-09 22:57:32','2026-07-09 22:57:32'),(4,'Nusatic International Channa Contest 2026','icc-galleries/01KX59N9KP0DRZGX3Z7PVR8WDK.jpg','Nusatic International Channa Contest 2026',NULL,'2026-07-09 22:57:56','2026-07-09 22:57:56'),(5,'Nusatic International Channa Contest 2026','icc-galleries/01KX59NRPN7D7W310PEBQ1MH6D.jpg','Nusatic International Channa Contest 2026',NULL,'2026-07-09 22:58:12','2026-07-09 22:58:12');
/*!40000 ALTER TABLE `icc_galleries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
INSERT INTO `jobs` VALUES (2,'default','{\"uuid\":\"4e8ab682-ed55-4a2f-aec2-5b26733889dc\",\"displayName\":\"App\\\\Notifications\\\\EventApproved\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:3;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:31:\\\"App\\\\Notifications\\\\EventApproved\\\":2:{s:5:\\\"event\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Event\\\";s:2:\\\"id\\\";i:2;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"a3667fd6-99ae-47d7-a03b-f205df7d631d\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\",\"batchId\":null},\"createdAt\":1783576300,\"delay\":null}',0,NULL,1783576300,1783576300),(3,'default','{\"uuid\":\"9e4d47e6-7954-4692-a910-8789e54a95d3\",\"displayName\":\"App\\\\Notifications\\\\EventApproved\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:4;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:31:\\\"App\\\\Notifications\\\\EventApproved\\\":2:{s:5:\\\"event\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Event\\\";s:2:\\\"id\\\";i:3;s:9:\\\"relations\\\";a:2:{i:0;s:9:\\\"organizer\\\";i:1;s:14:\\\"organizer.user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"31c9972e-c8ee-41da-9f68-22c4527c500b\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\",\"batchId\":null},\"createdAt\":1783599536,\"delay\":null}',0,NULL,1783599536,1783599536),(4,'default','{\"uuid\":\"88bd2d95-0976-4f13-aca7-6d3caee4875b\",\"displayName\":\"App\\\\Notifications\\\\EventApproved\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:5;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:31:\\\"App\\\\Notifications\\\\EventApproved\\\":2:{s:5:\\\"event\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Event\\\";s:2:\\\"id\\\";i:4;s:9:\\\"relations\\\";a:2:{i:0;s:9:\\\"organizer\\\";i:1;s:14:\\\"organizer.user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"08c6772e-b819-42a2-867b-261e2dfb0c75\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\",\"batchId\":null},\"createdAt\":1783601025,\"delay\":null}',0,NULL,1783601025,1783601025),(5,'default','{\"uuid\":\"ebfd6485-9cd1-4f5e-b54d-de226fa312f8\",\"displayName\":\"App\\\\Notifications\\\\EventApproved\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:5;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:31:\\\"App\\\\Notifications\\\\EventApproved\\\":2:{s:5:\\\"event\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Event\\\";s:2:\\\"id\\\";i:4;s:9:\\\"relations\\\";a:2:{i:0;s:9:\\\"organizer\\\";i:1;s:14:\\\"organizer.user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"ed356141-632a-489a-938d-f308fdb76b91\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\",\"batchId\":null},\"createdAt\":1783602301,\"delay\":null}',0,NULL,1783602301,1783602301),(6,'default','{\"uuid\":\"4bb6c3da-efab-4cdc-b072-3649a742ed91\",\"displayName\":\"App\\\\Notifications\\\\EventApproved\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:6;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:31:\\\"App\\\\Notifications\\\\EventApproved\\\":2:{s:5:\\\"event\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Event\\\";s:2:\\\"id\\\";i:5;s:9:\\\"relations\\\";a:2:{i:0;s:9:\\\"organizer\\\";i:1;s:14:\\\"organizer.user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"5534bfb4-556c-4aef-adf4-dc2a61fbd0cb\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\",\"batchId\":null},\"createdAt\":1783617663,\"delay\":null}',0,NULL,1783617663,1783617663),(7,'default','{\"uuid\":\"d2f9db41-e56f-4558-9bd1-b38adf8b09fe\",\"displayName\":\"App\\\\Notifications\\\\EventApproved\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:6;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:31:\\\"App\\\\Notifications\\\\EventApproved\\\":2:{s:5:\\\"event\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Event\\\";s:2:\\\"id\\\";i:5;s:9:\\\"relations\\\";a:2:{i:0;s:9:\\\"organizer\\\";i:1;s:14:\\\"organizer.user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"af36ed37-b1e4-4813-8dc0-165b978c21eb\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\",\"batchId\":null},\"createdAt\":1783617871,\"delay\":null}',0,NULL,1783617871,1783617871),(8,'default','{\"uuid\":\"9c0ac0e3-c30a-4c7a-ac6e-7add0f3a16fb\",\"displayName\":\"App\\\\Notifications\\\\EventApproved\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:7;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:31:\\\"App\\\\Notifications\\\\EventApproved\\\":2:{s:5:\\\"event\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Event\\\";s:2:\\\"id\\\";i:6;s:9:\\\"relations\\\";a:2:{i:0;s:9:\\\"organizer\\\";i:1;s:14:\\\"organizer.user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"78885086-4f8b-4386-adf2-a0d979e38384\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\",\"batchId\":null},\"createdAt\":1783618701,\"delay\":null}',0,NULL,1783618701,1783618701),(9,'default','{\"uuid\":\"ecdb2170-1b58-497e-ad24-647f3113db25\",\"displayName\":\"App\\\\Notifications\\\\EventApproved\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:8;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:31:\\\"App\\\\Notifications\\\\EventApproved\\\":2:{s:5:\\\"event\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Event\\\";s:2:\\\"id\\\";i:7;s:9:\\\"relations\\\";a:2:{i:0;s:9:\\\"organizer\\\";i:1;s:14:\\\"organizer.user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"b7cf5aff-9287-472a-8372-8b64d27efc4a\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\",\"batchId\":null},\"createdAt\":1783619644,\"delay\":null}',0,NULL,1783619644,1783619644),(10,'default','{\"uuid\":\"4870e2a6-403c-409e-b6be-32882a8c73d5\",\"displayName\":\"App\\\\Jobs\\\\GenerateCertificateJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\GenerateCertificateJob\",\"command\":\"O:31:\\\"App\\\\Jobs\\\\GenerateCertificateJob\\\":1:{s:6:\\\"winner\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:17:\\\"App\\\\Models\\\\Winner\\\";s:2:\\\"id\\\";i:1;s:9:\\\"relations\\\";a:1:{i:0;s:11:\\\"certificate\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}}\",\"batchId\":null},\"createdAt\":1783625920,\"delay\":null}',0,NULL,1783625920,1783625920),(11,'default','{\"uuid\":\"ff03ad7b-e30f-4a01-977d-4ce4f1872cff\",\"displayName\":\"App\\\\Jobs\\\\GenerateCertificateJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\GenerateCertificateJob\",\"command\":\"O:31:\\\"App\\\\Jobs\\\\GenerateCertificateJob\\\":1:{s:6:\\\"winner\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:17:\\\"App\\\\Models\\\\Winner\\\";s:2:\\\"id\\\";i:1;s:9:\\\"relations\\\";a:1:{i:0;s:11:\\\"certificate\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}}\",\"batchId\":null},\"createdAt\":1783626182,\"delay\":null}',0,NULL,1783626182,1783626182),(12,'default','{\"uuid\":\"20a0a1dd-16ce-42fd-afd1-667b17069a09\",\"displayName\":\"App\\\\Jobs\\\\GenerateCertificateJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\GenerateCertificateJob\",\"command\":\"O:31:\\\"App\\\\Jobs\\\\GenerateCertificateJob\\\":1:{s:6:\\\"winner\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:17:\\\"App\\\\Models\\\\Winner\\\";s:2:\\\"id\\\";i:1;s:9:\\\"relations\\\";a:1:{i:0;s:11:\\\"certificate\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}}\",\"batchId\":null},\"createdAt\":1783626194,\"delay\":null}',0,NULL,1783626194,1783626194);
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `judges`
--

DROP TABLE IF EXISTS `judges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `judges` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `kota` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `judges`
--

LOCK TABLES `judges` WRITE;
/*!40000 ALTER TABLE `judges` DISABLE KEYS */;
INSERT INTO `judges` VALUES (1,'Tio Arhes Pradesetya','D.I. Yogyakarta','2026-07-09 22:26:37','2026-07-09 22:26:37'),(2,'Ilham Rahmani Riyadi','Jawa Tengah','2026-07-09 22:26:59','2026-07-09 22:26:59'),(3,'Rahul','Jawa Timur','2026-07-09 22:27:09','2026-07-09 22:27:09'),(4,'Niko','Jawa Timur','2026-07-09 22:27:19','2026-07-09 22:27:19'),(5,'Andreas','Jawa Barat','2026-07-09 22:27:54','2026-07-09 22:27:54'),(6,'Gusti','Jawa Tengah','2026-07-10 04:56:19','2026-07-10 04:56:19'),(7,'Tobi','Jawa Barat','2026-07-10 04:56:39','2026-07-10 04:56:39'),(8,'Arul','Jawa Timur','2026-07-10 04:57:25','2026-07-10 04:57:25'),(9,'Ricky','Kalimantan Selatan','2026-07-10 04:57:36','2026-07-10 04:57:36'),(10,'Rezky','Kalimantan Selatan','2026-07-10 04:57:47','2026-07-10 04:57:47'),(11,'Didit','Kalimantan Timur','2026-07-10 04:57:56','2026-07-10 04:57:56'),(12,'Perry Shaq','Bangka Belitung','2026-07-10 04:58:12','2026-07-10 04:58:12'),(13,'Fadhel','Bali','2026-07-10 04:58:20','2026-07-10 04:58:20');
/*!40000 ALTER TABLE `judges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `judges_lists`
--

DROP TABLE IF EXISTS `judges_lists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `judges_lists` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `file_path` varchar(255) NOT NULL,
  `tipe` varchar(255) DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `judges_lists_updated_by_foreign` (`updated_by`),
  CONSTRAINT `judges_lists_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `judges_lists`
--

LOCK TABLES `judges_lists` WRITE;
/*!40000 ALTER TABLE `judges_lists` DISABLE KEYS */;
INSERT INTO `judges_lists` VALUES (3,'documents/juri/01KX2N044QQ2SGTNT0BS277XDN.png','png',NULL,'2026-07-08 22:18:22','2026-07-08 22:18:22');
/*!40000 ALTER TABLE `judges_lists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_07_08_115414_create_permission_tables',2),(5,'2026_07_08_115501_add_role_and_status_to_users_table',2),(6,'2026_07_08_115536_create_portal_tables',3),(7,'2026_07_09_041128_add_activation_token_to_users_table',4),(8,'2026_07_09_050546_add_nama_to_regulations_table',5),(9,'2026_07_09_092243_create_event_flyers_table',6),(10,'2026_07_09_094428_add_google_sheet_url_to_events_table',7),(11,'2026_07_09_104153_add_site_settings_columns',8),(13,'2026_07_09_150701_add_sambutan_pembina_to_site_settings_table',9),(14,'2026_07_09_184215_create_winner_predikats_table',10),(15,'2026_07_09_184311_add_winner_predikat_id_to_winners_table',10),(16,'2026_07_10_045213_create_notifications_table',11),(17,'2026_07_10_051942_create_judges_table',12),(18,'2026_07_10_051948_create_event_judge_table',12),(19,'2026_07_11_031032_add_no_wa_cp_to_events_table',13),(20,'2026_07_11_150000_add_biaya_pendaftaran_to_event_classes_table',14),(21,'2026_07_11_151000_create_registration_orders_table',14),(22,'2026_07_11_152000_expand_participants_for_registration_table',14),(23,'2026_07_30_100000_create_event_cps_table',15),(24,'2026_07_30_110000_add_popup_to_site_settings_table',16),(25,'2026_07_30_120000_add_meta_description_to_site_settings_table',17),(26,'2026_08_03_000001_enrich_participants_registration_table',18),(27,'2026_08_03_000003_create_bank_accounts_table',18),(28,'2026_08_03_025027_add_harga_tiket_to_event_classes_table',19),(29,'2026_08_03_025038_add_columns_to_participants_table',20),(30,'2026_08_03_034029_add_dp_amount_to_participants_table',21),(31,'2026_08_03_040000_add_participant_registration_columns_and_bank_accounts_table',22),(32,'2026_08_03_050000_add_participant_certificates',22),(33,'2026_08_03_060000_make_nama_peserta_nullable',23);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` VALUES (1,'App\\Models\\User',1),(3,'App\\Models\\User',3),(3,'App\\Models\\User',4),(3,'App\\Models\\User',5),(3,'App\\Models\\User',6),(3,'App\\Models\\User',7),(3,'App\\Models\\User',8),(3,'App\\Models\\User',9),(3,'App\\Models\\User',10),(3,'App\\Models\\User',11),(3,'App\\Models\\User',12),(3,'App\\Models\\User',16),(3,'App\\Models\\User',17);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) unsigned NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `organization_structures`
--

DROP TABLE IF EXISTS `organization_structures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `organization_structures` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `file_path` varchar(255) NOT NULL,
  `tipe` varchar(255) DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `organization_structures_updated_by_foreign` (`updated_by`),
  CONSTRAINT `organization_structures_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `organization_structures`
--

LOCK TABLES `organization_structures` WRITE;
/*!40000 ALTER TABLE `organization_structures` DISABLE KEYS */;
INSERT INTO `organization_structures` VALUES (1,'documents/struktur/01KX2C84F84JD4Y2T6NTMKRT2B.png','jpg',NULL,'2026-07-08 19:45:28','2026-07-08 19:45:28');
/*!40000 ALTER TABLE `organization_structures` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `organizers`
--

DROP TABLE IF EXISTS `organizers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `organizers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `nama_organisasi` varchar(255) NOT NULL,
  `jabatan_pic` varchar(255) DEFAULT NULL,
  `no_wa` varchar(255) NOT NULL,
  `no_ktp` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `organizers_user_id_foreign` (`user_id`),
  CONSTRAINT `organizers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `organizers`
--

LOCK TABLES `organizers` WRITE;
/*!40000 ALTER TABLE `organizers` DISABLE KEYS */;
INSERT INTO `organizers` VALUES (7,8,'Chaerul Amin Subekti','Penanggung Jawab','085171001596','3301151501960001','2026-07-09 10:53:44','2026-07-09 10:53:44'),(9,10,'PT. Zeex Aquatic','Penanggung Jawah','985200263388','123456789','2026-07-09 21:55:33','2026-07-09 21:55:33'),(10,11,'Kharis','Penanggung Jawab','085200263388','12345678998723','2026-07-09 22:51:18','2026-07-09 22:51:18'),(11,12,'Chaerul','Penanggung Jawab','088973920068','3301111111111','2026-07-09 23:06:04','2026-07-09 23:06:04'),(12,16,'Chaerul Amin Subekti','Penanggung Jawab','085112344321','3301151501231','2026-07-11 01:39:56','2026-07-11 01:39:56'),(13,17,'Chaerudin Ahmad','Penanggung Jawab','852-0026-3388','','2026-07-30 04:04:08','2026-07-30 04:04:08');
/*!40000 ALTER TABLE `organizers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `participants`
--

DROP TABLE IF EXISTS `participants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `participants` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `event_id` bigint(20) unsigned NOT NULL,
  `event_class_id` bigint(20) unsigned DEFAULT NULL,
  `nama_pemilik` varchar(255) DEFAULT NULL,
  `team_sf` varchar(255) DEFAULT NULL,
  `registration_order_id` bigint(20) unsigned DEFAULT NULL,
  `nama_peserta` varchar(255) DEFAULT NULL,
  `nama_ikan` varchar(255) DEFAULT NULL,
  `jenis_ikan` varchar(255) DEFAULT NULL,
  `nama_team` varchar(255) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `kota_asal` varchar(255) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `no_wa` varchar(255) DEFAULT NULL,
  `no_urut` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `biaya` decimal(15,2) DEFAULT NULL,
  `dp_amount` decimal(12,2) DEFAULT NULL,
  `bukti_pembayaran` varchar(255) DEFAULT NULL,
  `fishin` tinyint(1) NOT NULL DEFAULT 0,
  `fishout` tinyint(1) NOT NULL DEFAULT 0,
  `transaction_id` varchar(255) DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `participants_event_id_foreign` (`event_id`),
  KEY `participants_event_class_id_foreign` (`event_class_id`),
  KEY `participants_user_id_foreign` (`user_id`),
  KEY `participants_registration_order_id_foreign` (`registration_order_id`),
  CONSTRAINT `participants_event_class_id_foreign` FOREIGN KEY (`event_class_id`) REFERENCES `event_classes` (`id`) ON DELETE SET NULL,
  CONSTRAINT `participants_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  CONSTRAINT `participants_registration_order_id_foreign` FOREIGN KEY (`registration_order_id`) REFERENCES `registration_orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `participants_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `participants`
--

LOCK TABLES `participants` WRITE;
/*!40000 ALTER TABLE `participants` DISABLE KEYS */;
INSERT INTO `participants` VALUES (10,17,13,1,'Fadil GMC','GMC Kediri',NULL,'Fadil GMC','-',NULL,NULL,NULL,'Kediri','085608717174',NULL,'1','menunggu_bayar',200000.00,0.00,'bukti-pembayaran/GPVDggiC2pISypFDYZTbaCQEeWuvuG4txS8CVxuv.jpg',0,0,NULL,NULL,'2026-08-03 00:42:29','2026-08-03 01:00:27'),(11,17,13,1,'Fadil GMC','GMC Kediri',NULL,'Fadil GMC','-',NULL,NULL,NULL,'Kediri','085608717174',NULL,'2','menunggu_verifikasi',200000.00,NULL,'bukti-pembayaran/PQ0TAhwYGWS2nvQc0qiVUjAC4McwokXYAYgqXqgF.jpg',0,0,NULL,NULL,'2026-08-03 01:01:33','2026-08-03 01:01:33'),(12,17,13,1,'Fadil GMC','GMC Kediri',NULL,'Fadil GMC','-',NULL,NULL,NULL,'Kediri','085608717174',NULL,'3','menunggu_verifikasi',200000.00,NULL,'bukti-pembayaran/PQ0TAhwYGWS2nvQc0qiVUjAC4McwokXYAYgqXqgF.jpg',0,0,NULL,NULL,'2026-08-03 01:01:33','2026-08-03 01:01:33'),(14,17,13,3,'Andi Barong','Single Fighter',NULL,'Andi Barong','-',NULL,NULL,NULL,'Bekasi','087784202800',NULL,'1','menunggu_verifikasi',225000.00,NULL,'bukti-pembayaran/l9H4gL6aWQCJh3FpeHByK07vDUChNZtf2KYhGfQl.jpg',0,0,NULL,NULL,'2026-08-03 01:06:29','2026-08-03 01:06:29'),(15,17,13,9,'Yanuar Fajar Eka','Single Fighter',NULL,'Yanuar Fajar Eka','Zeus',NULL,NULL,NULL,'Tegal','082329643551',NULL,'1','menunggu_verifikasi',225000.00,100000.00,'bukti-pembayaran/01KZ3DF2D06PBNBBGYXVF2SEQ4.jpeg',0,0,NULL,NULL,'2026-08-03 01:07:52','2026-08-03 01:57:22'),(17,17,13,1,'Fadil GMC','GMC Kediri',NULL,'Fadil GMC','-',NULL,NULL,NULL,'Kediri','085608717174',NULL,'4','menunggu_verifikasi',200000.00,NULL,'bukti-pembayaran/5SZbhp6OFVgGXWGlBOsGa8R8E8HYouNMlJvbWIg4.jpg',0,0,NULL,NULL,'2026-08-03 01:53:07','2026-08-03 01:53:07'),(19,17,13,1,'GVRL','Gogo Team',NULL,'GVRL','Te Amo',NULL,NULL,NULL,'','-',NULL,'5','menunggu_verifikasi',200000.00,NULL,'bukti-pembayaran/h6jK9bGwNXzy3h6I6LQaIWUohhrAmAaaftLv22Kz.jpg',0,0,NULL,NULL,'2026-08-18 00:01:26','2026-08-18 00:01:26'),(20,17,13,5,'GVRL','Gogo Team',NULL,'GVRL','-',NULL,NULL,NULL,'','-',NULL,'1','menunggu_verifikasi',200000.00,NULL,'bukti-pembayaran/h6jK9bGwNXzy3h6I6LQaIWUohhrAmAaaftLv22Kz.jpg',0,0,NULL,NULL,'2026-08-18 00:01:26','2026-08-18 00:01:26'),(21,17,13,10,'GVRL','Gogo Team',NULL,'GVRL','-',NULL,NULL,NULL,'','-',NULL,'1','menunggu_verifikasi',200000.00,NULL,'bukti-pembayaran/h6jK9bGwNXzy3h6I6LQaIWUohhrAmAaaftLv22Kz.jpg',0,0,NULL,NULL,'2026-08-18 00:01:26','2026-08-18 00:01:26'),(22,17,13,10,'GVRL','Gogo Team',NULL,'GVRL','Giok',NULL,NULL,NULL,NULL,'-',NULL,'2','menunggu_verifikasi',200000.00,NULL,'bukti-pembayaran/h6jK9bGwNXzy3h6I6LQaIWUohhrAmAaaftLv22Kz.jpg',0,0,NULL,NULL,'2026-08-18 00:01:26','2026-08-18 00:42:38'),(23,17,13,12,'GVRL','Gogo Team',NULL,'GVRL','-',NULL,NULL,NULL,'','-',NULL,'1','menunggu_verifikasi',225000.00,NULL,'bukti-pembayaran/h6jK9bGwNXzy3h6I6LQaIWUohhrAmAaaftLv22Kz.jpg',0,0,NULL,NULL,'2026-08-18 00:01:26','2026-08-18 00:01:26'),(24,17,13,6,'GVRL','Gogo Team',NULL,'GVRL','Weling',NULL,NULL,NULL,'','-',NULL,'1','menunggu_verifikasi',225000.00,NULL,'bukti-pembayaran/h6jK9bGwNXzy3h6I6LQaIWUohhrAmAaaftLv22Kz.jpg',0,0,NULL,NULL,'2026-08-18 00:01:26','2026-08-18 00:01:26'),(25,17,13,6,'GVRL','Gogo Team',NULL,'GVRL','Tipsy',NULL,NULL,NULL,'','-',NULL,'2','menunggu_verifikasi',225000.00,NULL,'bukti-pembayaran/h6jK9bGwNXzy3h6I6LQaIWUohhrAmAaaftLv22Kz.jpg',0,0,NULL,NULL,'2026-08-18 00:01:26','2026-08-18 00:01:26'),(26,17,13,7,'GVRL','Gogo Team',NULL,'GVRL','Kuntil',NULL,NULL,NULL,'','-',NULL,'1','menunggu_verifikasi',225000.00,NULL,'bukti-pembayaran/h6jK9bGwNXzy3h6I6LQaIWUohhrAmAaaftLv22Kz.jpg',0,0,NULL,NULL,'2026-08-18 00:01:26','2026-08-18 00:01:26'),(28,17,13,5,'Rafaza','Single Fighter',NULL,'Rafaza','Goblin',NULL,NULL,NULL,'Blitar','085733190128',NULL,'2','menunggu_verifikasi',200000.00,NULL,'bukti-pembayaran/JXWdaSmA4Z42Q2woaMWPqLlyRQDrGJv5BcLGMeEV.jpg',0,0,NULL,NULL,'2026-08-18 00:04:40','2026-08-18 00:04:40'),(29,17,13,13,'Gatzfish','Single Fighter',NULL,'Gatzfish','Loki',NULL,NULL,NULL,'Batang','083820691844',NULL,'1','menunggu_verifikasi',200000.00,NULL,'bukti-pembayaran/Cdk1MxsfsWpyM48dDbqiSqDqbgwJjsQuEa4fGZki.jpg',0,0,NULL,NULL,'2026-08-18 00:05:38','2026-08-18 00:05:38'),(30,17,13,15,'Marqisya Fish','Single Fighter',NULL,'Marqisya Fish','Djono',NULL,NULL,NULL,'Kutoarjo','-',NULL,'1','menunggu_verifikasi',250000.00,NULL,'bukti-pembayaran/VfPezVrmoyJwSsUvYjDYBzLXLLYbmlU23yTq5qSQ.jpg',0,0,NULL,NULL,'2026-08-18 00:06:31','2026-08-18 00:06:31'),(31,17,13,12,'Aqil Snakehead','Single Fighter',NULL,'Aqil Snakehead','Raden Pachul',NULL,NULL,NULL,'Pemalang','ΓÇ¬087872539738',NULL,'2','lunas',225000.00,NULL,'bukti-pembayaran/0gEJzrbwIL4qG9T5FTmBOl0XEIeobvrUlFwVXYc5.webp',0,0,NULL,NULL,'2026-08-18 00:07:45','2026-08-18 00:08:26'),(32,17,13,11,'Aqil Snakehead','Single Fighter',NULL,'Aqil Snakehead','SKY',NULL,NULL,NULL,'Pemalang','ΓÇ¬087872539738',NULL,'1','lunas',200000.00,NULL,'bukti-pembayaran/0gEJzrbwIL4qG9T5FTmBOl0XEIeobvrUlFwVXYc5.webp',0,0,NULL,NULL,'2026-08-18 00:07:45','2026-08-18 00:08:31'),(33,17,13,4,'ACP','Gogo Team',NULL,'ACP','D J',NULL,NULL,NULL,'Jepara','083162130105',NULL,'1','menunggu_verifikasi',200000.00,NULL,'bukti-pembayaran/dcInrc3gFPh1S7i5fA06m3aaMURw5ITaGHhbqhuc.jpg',0,0,NULL,NULL,'2026-08-18 00:09:30','2026-08-18 00:09:30'),(34,17,13,4,'Ramadhan L_J','Limbata Jawa',NULL,'Ramadhan L_J','Tuosen',NULL,NULL,NULL,'Tegal','083140741649',NULL,'2','menunggu_verifikasi',200000.00,NULL,'bukti-pembayaran/LFM7qX8P03g1G7BkgV0eBF7xKA2vmsfzVDX5UBcJ.jpg',0,0,NULL,NULL,'2026-08-18 00:10:12','2026-08-18 00:10:12'),(35,17,13,15,'Chio Fish','Gogo Team',NULL,'Chio Fish','-',NULL,NULL,NULL,'Purworejo','-',NULL,'2','menunggu_verifikasi',250000.00,NULL,'bukti-pembayaran/uhexDMPdtBCBbzFMlD3wETO4paYFMsj5yTGywQIJ.jpg',0,0,NULL,NULL,'2026-08-18 00:13:18','2026-08-18 00:13:18'),(36,17,13,12,'Chio Fish','Gogo Team',NULL,'Chio Fish','-',NULL,NULL,NULL,'Purworejo','-',NULL,'3','menunggu_verifikasi',225000.00,NULL,'bukti-pembayaran/uhexDMPdtBCBbzFMlD3wETO4paYFMsj5yTGywQIJ.jpg',0,0,NULL,NULL,'2026-08-18 00:13:18','2026-08-18 00:13:18'),(37,17,13,12,'Chio Fish','Gogo Team',NULL,'Chio Fish','-',NULL,NULL,NULL,'Purworejo','-',NULL,'4','menunggu_verifikasi',225000.00,NULL,'bukti-pembayaran/uhexDMPdtBCBbzFMlD3wETO4paYFMsj5yTGywQIJ.jpg',0,0,NULL,NULL,'2026-08-18 00:13:18','2026-08-18 00:13:18'),(38,17,13,12,'Chio Fish','Gogo Team',NULL,'Chio Fish','-',NULL,NULL,NULL,'Purworejo','-',NULL,'5','menunggu_verifikasi',225000.00,NULL,'bukti-pembayaran/uhexDMPdtBCBbzFMlD3wETO4paYFMsj5yTGywQIJ.jpg',0,0,NULL,NULL,'2026-08-18 00:13:18','2026-08-18 00:13:18'),(39,17,13,6,'Chio Fish','Gogo Team',NULL,'Chio Fish','-',NULL,NULL,NULL,'Purworejo','-',NULL,'3','menunggu_verifikasi',225000.00,NULL,'bukti-pembayaran/uhexDMPdtBCBbzFMlD3wETO4paYFMsj5yTGywQIJ.jpg',0,0,NULL,NULL,'2026-08-18 00:13:18','2026-08-18 00:13:18'),(40,17,13,7,'Chio Fish','Gogo Team',NULL,'Chio Fish','-',NULL,NULL,NULL,'Purworejo','-',NULL,'2','menunggu_verifikasi',225000.00,NULL,'bukti-pembayaran/uhexDMPdtBCBbzFMlD3wETO4paYFMsj5yTGywQIJ.jpg',0,0,NULL,NULL,'2026-08-18 00:13:18','2026-08-18 00:13:18'),(41,17,13,11,'Chio Fish','Gogo Team',NULL,'Chio Fish','-',NULL,NULL,NULL,'Purworejo','-',NULL,'2','menunggu_verifikasi',200000.00,NULL,'bukti-pembayaran/uhexDMPdtBCBbzFMlD3wETO4paYFMsj5yTGywQIJ.jpg',0,0,NULL,NULL,'2026-08-18 00:13:18','2026-08-18 00:13:18'),(42,17,13,14,'Chio Fish','Gogo Team',NULL,'Chio Fish','-',NULL,NULL,NULL,'Purworejo','-',NULL,'1','menunggu_verifikasi',225000.00,NULL,'bukti-pembayaran/uhexDMPdtBCBbzFMlD3wETO4paYFMsj5yTGywQIJ.jpg',0,0,NULL,NULL,'2026-08-18 00:13:18','2026-08-18 00:13:18'),(43,17,13,2,'Chio Fish','Gogo Team',NULL,'Chio Fish','-',NULL,NULL,NULL,'Purworejo','-',NULL,'1','menunggu_verifikasi',200000.00,NULL,'bukti-pembayaran/uhexDMPdtBCBbzFMlD3wETO4paYFMsj5yTGywQIJ.jpg',0,0,NULL,NULL,'2026-08-18 00:13:18','2026-08-18 00:13:18'),(44,17,13,2,'Chio Fish','Gogo Team',NULL,'Chio Fish','-',NULL,NULL,NULL,'Purworejo','-',NULL,'2','menunggu_verifikasi',200000.00,NULL,'bukti-pembayaran/uhexDMPdtBCBbzFMlD3wETO4paYFMsj5yTGywQIJ.jpg',0,0,NULL,NULL,'2026-08-18 00:13:18','2026-08-18 00:13:18'),(45,17,13,11,'B R','Gogo Team',NULL,'B R','Arsenal',NULL,NULL,NULL,'Tegal','082333197119',NULL,'3','menunggu_verifikasi',200000.00,NULL,'bukti-pembayaran/LqOwJwUcMhPooWwdg8sJF13pNI09Up7DRM9psvHP.jpg',0,0,NULL,NULL,'2026-08-18 00:14:42','2026-08-18 00:14:42'),(46,17,13,7,'Coco Snakehead','Gogo Tean',NULL,'Coco Snakehead','Contrue',NULL,NULL,NULL,'Jakarta','-',NULL,'3','menunggu_verifikasi',225000.00,NULL,'bukti-pembayaran/BG8B0epIb0bDUciUToLwMPLUsqz3CObrBxDnW8r1.jpg',0,0,NULL,NULL,'2026-08-18 00:15:45','2026-08-18 00:15:45'),(47,17,13,15,'GCS','Banana Snakehead',NULL,'GCS','Harimaumu',NULL,NULL,NULL,'Subang','087783841530',NULL,'3','menunggu_verifikasi',250000.00,NULL,'bukti-pembayaran/v0aKhYtBrYCx4GlWSuPHmidAeCIizyDWQEH3tAio.jpg',0,0,NULL,NULL,'2026-08-18 00:17:09','2026-08-18 00:17:09'),(48,17,13,11,'GCS','Banana Snakehead',NULL,'GCS','Ipin',NULL,NULL,NULL,'Subang','087783841530',NULL,'4','menunggu_verifikasi',200000.00,NULL,'bukti-pembayaran/v0aKhYtBrYCx4GlWSuPHmidAeCIizyDWQEH3tAio.jpg',0,0,NULL,NULL,'2026-08-18 00:17:09','2026-08-18 00:17:09'),(49,17,13,2,'GCS','Banana Snakehead',NULL,'GCS','Bronis',NULL,NULL,NULL,'Subang','087783841530',NULL,'3','menunggu_verifikasi',200000.00,NULL,'bukti-pembayaran/v0aKhYtBrYCx4GlWSuPHmidAeCIizyDWQEH3tAio.jpg',0,0,NULL,NULL,'2026-08-18 00:17:09','2026-08-18 00:17:09'),(52,17,13,11,'Adila Fish','Single Fighter',NULL,'Adila Fish','Lix',NULL,NULL,NULL,'Jakarta','083892775957',NULL,'5','menunggu_verifikasi',200000.00,NULL,'bukti-pembayaran/AsEovCaMzEtKMbb1Gs8WBUCdzNAx500oBQmPMwnV.jpg',0,0,NULL,NULL,'2026-08-18 00:44:54','2026-08-18 00:44:54'),(53,17,13,12,'Adila Fish','Single Fighter',NULL,'Adila Fish','Jack',NULL,NULL,NULL,'Jakarta','083892775957',NULL,'6','menunggu_verifikasi',225000.00,NULL,'bukti-pembayaran/AsEovCaMzEtKMbb1Gs8WBUCdzNAx500oBQmPMwnV.jpg',0,0,NULL,NULL,'2026-08-18 00:44:54','2026-08-18 00:44:54');
/*!40000 ALTER TABLE `participants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `registration_orders`
--

DROP TABLE IF EXISTS `registration_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `registration_orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `event_id` bigint(20) unsigned NOT NULL,
  `order_id` varchar(255) NOT NULL,
  `total_biaya` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `snap_token` varchar(255) DEFAULT NULL,
  `midtrans_response` text DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `registration_orders_order_id_unique` (`order_id`),
  KEY `registration_orders_user_id_foreign` (`user_id`),
  KEY `registration_orders_event_id_foreign` (`event_id`),
  CONSTRAINT `registration_orders_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  CONSTRAINT `registration_orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `registration_orders`
--

LOCK TABLES `registration_orders` WRITE;
/*!40000 ALTER TABLE `registration_orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `registration_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `regulations`
--

DROP TABLE IF EXISTS `regulations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `regulations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `tipe` varchar(255) DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `regulations_updated_by_foreign` (`updated_by`),
  CONSTRAINT `regulations_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `regulations`
--

LOCK TABLES `regulations` WRITE;
/*!40000 ALTER TABLE `regulations` DISABLE KEYS */;
INSERT INTO `regulations` VALUES (5,'Ketentuan dan Syarat Regulasi ICC','documents/regulasi/01KX2MM3MSYT00G5BH29X4EQQH.pdf',NULL,NULL,'2026-07-08 22:11:49','2026-07-08 22:11:49');
/*!40000 ALTER TABLE `regulations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'super_admin','web','2026-07-08 04:57:42','2026-07-08 04:57:42'),(2,'editor','web','2026-07-08 04:57:42','2026-07-08 04:57:42'),(3,'penyelenggara','web','2026-07-08 04:57:42','2026-07-08 04:57:42');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('2TVkDwVdsf5tJDrZRwETDdYF8lForyPOGutsRR9T',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.22621.6133','YTozOntzOjY6Il90b2tlbiI7czo0MDoielF6cG5leWs0cEp2M0tSTVZaZnZjMDlSdlNEa3BBOVF1aUJzajR6cSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwNSI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1783525586),('3MoTeZPIAHjV7xGyLVjgO5IIEulXx9a8VVUzqM7K',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.22621.6133','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQk5IUnlYZ3NaMUtKc0FnV0I0ZHBub2FYOFQyVFU1eE5ZbkVKeDdFeCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1783521310),('3sNrK0vKktSFrkFT4sfAuvmSaJY7EwgEDM90Zd9r',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.22621.6133','YTozOntzOjY6Il90b2tlbiI7czo0MDoiYlhWSHFma3lUR0laS3J3MFp2c2Q1WG5PMDA3QVc1U2owV2lSQ1dRcSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwNi9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1783526446),('3UpjfJX6CNknw4YVG3mN4Nz4MhIpufgGy8WAgn2r',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.22621.6133','YTozOntzOjY6Il90b2tlbiI7czo0MDoiakI1R3d5QUVxdTlNam5ucHhZS09XV1VrS0N3dkFhbEhQNTd0RjZoWiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9wZW5nYWp1YW4iO3M6NToicm91dGUiO3M6OToicGVuZ2FqdWFuIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1783523351),('4DR9nsGdkH4bb12TnumcerpvWK6eVLbOVUKiPOMP',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.22621.6133','YTozOntzOjY6Il90b2tlbiI7czo0MDoiek9ETnZ5UWY4MjI5Q2VzdVQ1N2g3ZG5KWEIyTm0xajM4VnNaTWV0bSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1783521325),('4JDUVEg3NcLHCRPPWps52hT0OjPXMegCTkPoC3N3',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.22621.6133','YTozOntzOjY6Il90b2tlbiI7czo0MDoiS0p0UU94S2tsc0JtOHlPajZtQXRQZktMYk5TT0laOHU1ajR0NVptQiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwNCI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1783521764),('51mj8Xp7S9apQT4qcWP6fu64mFvt92I3EqubpnI6',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.22621.6133','YTozOntzOjY6Il90b2tlbiI7czo0MDoieDJVVHZEMEZQZWN6bGpGb0k2THkwcmRKY2RSMTRiUDQ4UWVtRGU5ayI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMyI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1783521379),('B2j1p1PXMVe5LSmff4I3qLsfGd3bQnEVB6TvJC07',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.22621.6133','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMVhsQVRNaUFUcm5yNVdOMnl1TURzOXQ1WlJqemU0RkNReVNGV1puYyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwNi9kYWZ0YXItanVyaSI7czo1OiJyb3V0ZSI7czo0OiJqdXJpIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1783526444),('BDASOTPpDPFiNMLfgojDkc7Zq2DuumpGP1xIbyfJ',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.22621.6133','YTozOntzOjY6Il90b2tlbiI7czo0MDoib01wRHF4MWtCRWt0YVB5VHVmQkE2TXg0R2hTaGplRFNVZWpCZllIbiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwNi9wZW5nYWp1YW4iO3M6NToicm91dGUiO3M6OToicGVuZ2FqdWFuIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1783526443),('bUrZgPyqsi3l7XBdF1eu1tcAtXVdDQIPgIT3cFS3',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.22621.6133','YTozOntzOjY6Il90b2tlbiI7czo0MDoiOEtGWnRwTEwwUWw3ZUd2N2h1d3UzZ3VhNWI0T1Y4d28xZFpESmdvTSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMi9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1783521352),('dcPBI7mN7Cf3D30xdQacdaS7knctObEBMV9VdKpP',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiU1FjaHJJYUFuVWM0YXZxaTdZMlpScXhXZ0h0ZE1tb2hRWGExZUVPZCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==',1783529263),('EQ1taCJlE5nE9JdJtXrGrrbHn62gxxyDQeecG7JL',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.22621.6133','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMldHUXo5amhHOWNsNlFtMlNnU0FTQlJmUTRpaVpGZlFrT3pJSU1JTCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwNS9wZW5nYWp1YW4iO3M6NToicm91dGUiO3M6OToicGVuZ2FqdWFuIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1783525587),('FxACDRYkK0aXSvXa4nY3v2i0zKPcXEy9RKK3d5YX',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.22621.6133','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZFFQdmZIMFpMd3BDZ21zMWJUN004WFc5ZGFCMmxMb3BVdkFaU1c4byI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwNS9ldmVudCI7czo1OiJyb3V0ZSI7czoxMToiZXZlbnQuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1783522045),('GD7pZlSWbj307SR3tjv2zzsromTECIXtHNtkZ1QS',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.22621.6133','YTozOntzOjY6Il90b2tlbiI7czo0MDoiS2JnZEFpSkdWZEdLZmQwVDhTSmlPVVJmdkhQOEV5WVV6NDdYZG1jTiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwOC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoyNToiZmlsYW1lbnQuYWRtaW4uYXV0aC5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1783527471),('grkQdYl4fOIHms7ohNYMoudduyAdCx0K1WgKAalO',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.22621.6133','YTozOntzOjY6Il90b2tlbiI7czo0MDoicDBINllZaGxFelhqUnR1dGtpZ0JybEg0MDZFZHpiY0JEWE05SUtrSiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMSI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1783521307),('hoU6SWmLPn6enzLjX5JabYnN2DpTzy2JzrsW0R26',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.22621.6133','YTozOntzOjY6Il90b2tlbiI7czo0MDoiS09zWkRBRDRMeUlHTXNzeEJWVG1QNllTT3I5Q245TmRvUVZ2Zk1pcyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwNiI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1783522231),('kAnmLr2OzR63WiLTxHYQMQVIC6K84MqUnMJrDheu',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.22621.6133','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRE9LMkt0MVg4RVJydFhyendoVnlwTGpyb0NMSXdBYk5KVkpQclVCZiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwNiI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1783526441),('lrTkPpemFlmqCzo8gYTBY5hrxlQXaKxNzAmxGrqy',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.22621.6133','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSjVJcFZVdlR6enYxT2FUSXVIRm5yREhaNGVmRnQzS1FKWU5SakVWRCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMy9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1783521380),('Lyjyh8zrhHjvqSafP5NGuX8qqVYFHdJd8pFySwnN',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.22621.6133','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVThzMGd2bHFObE9nazlBaW5hSU9DWEt2RUpuZzhFTkZleEZGYjduciI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwNS9ldmVudCI7czo1OiJyb3V0ZSI7czoxMToiZXZlbnQuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1783525586),('n9zwdTkLFVhBDJl8VGAZ2YDV2JEyGkwp9cB5E0Fq',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.22621.6133','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWkozcW1qbmZBQjBibW5xTElRclNtcGwxazRybFcwU0ZpdmE4bThMaiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwNS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1783525588),('nnTrlCL5HW1jdZOppWNgCq6HQGmxe7VLUIPs4z5c',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.22621.6133','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNVVjTThMbDNFR0NaWFl3bkNWcmpDV1o2UEVkanJZU1NTaVZXNno3ViI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwNi9zdHJ1a3R1ci1vcmdhbmlzYXNpIjtzOjU6InJvdXRlIjtzOjg6InN0cnVrdHVyIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1783526443),('pbumj9r1zfRNh5RXLWWafyGTK8pUXdUGeukRB3h6',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.22621.6133','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSkQwc3dHaXNmWVNtVHF4RmowV0xOSVR1M05sRmVxWTFXUVVzZW9aTCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwNi9ldmVudCI7czo1OiJyb3V0ZSI7czoxMToiZXZlbnQuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1783526442),('pyncNLYbQXg1opi94rFLJcQnmD1nRClNz9tfG2ps',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.22621.6133','YTozOntzOjY6Il90b2tlbiI7czo0MDoiOXB5OE96SmJ5eUNuZmoyOE5YTGV2ZnV5R29ta3pjb2JEU1VWZkxPbCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwNC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1783521766),('rMTKvqKXyQ2eVlLATX14T778hEtvt15KwGlW3rV7',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.22621.6133','YTozOntzOjY6Il90b2tlbiI7czo0MDoibXJYYXlMM3NsNXVMRWpLQ2tNUjd4M2QyWGpHTmtzUENCQ0NsS3VqTCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwOS92ZXJpZmlrYXNpL0FCQ0QxMjM0IjtzOjU6InJvdXRlIjtzOjEwOiJ2ZXJpZmlrYXNpIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1783528427),('SohoGMzReTjHgaHgrUZZpBTYurUMxB33Go438KGq',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.22621.6133','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRkVNMVdjam0xenZtVUxGeW5EcHNzc0JzZ2tVVWF1QVZuWkRMWmJHOSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwNi9yZWd1bGFzaSI7czo1OiJyb3V0ZSI7czo4OiJyZWd1bGFzaSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1783526445),('U6CqfzCeeiYnMMk4WktxS0lnSp1FY0xPbrswrJ7Z',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.22621.6133','YTozOntzOjY6Il90b2tlbiI7czo0MDoiV2Y2MWhTZHBCdmtvemU4Z3JqZkt0cDlrZkViTVdwdWc0d2hQWGlqYyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMi9wZW5nYWp1YW4iO3M6NToicm91dGUiO3M6OToicGVuZ2FqdWFuIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1783523928),('VhKRJiJSX0byBkJKjNHvOtW3O2G9WaRz9XsN8miN',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','YToyOntzOjY6Il90b2tlbiI7czo0MDoiT1ZVbVpaSmtJMFltZzlZdjl4T3RQY2pFeGdVQlZ4TEtaVlM1NEpkNCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1783529263),('vlXExzAEqi9zMoCW2lMD5C691o7t8KQVNSKo5Pyi',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.22621.6133','YTozOntzOjY6Il90b2tlbiI7czo0MDoiY21UU0ppdUVSeXV2ZUliUm41TVcxcG1Wb1FaSkxpM3ZCWHJZNjlWYyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwNS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1783522046),('vsN8td50d11s1WlOUPzSF4ce1cBbTEHEXP1HH6Ut',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.22621.6133','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNTduWmVRSU9rOTlJUEo0WHl4aDVGcHNKSzJIUlZIeUJuT0Y0bU9EOCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9wZW5nYWp1YW4iO3M6NToicm91dGUiO3M6OToicGVuZ2FqdWFuIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1783522509),('VylS0DfoMu0VbU2npP5vtbw59L7eUCAua8cmpUdH',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.22621.6133','YTozOntzOjY6Il90b2tlbiI7czo0MDoiakhYempjbU9nVDNzZHZFZzNPNjVPU1Z0NTNGQmxNb29UcXFwbnNueCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwNi9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1783522232),('WUQdfBzourY4bgZvJpbutAof3cugqESbFYpnraBI',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.22621.6133','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNjk0OXEzazVYc1A3M1BoaXNObW1zVjlMNldjSEwwR0kwbjVyN2J6TyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMy9wZW5nYWp1YW4iO3M6NToicm91dGUiO3M6OToicGVuZ2FqdWFuIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1783524861),('yahB0GmBE09a7iqy5PHtKZvPpL1v4iQI8pi8jBxJ',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.22621.6133','YTozOntzOjY6Il90b2tlbiI7czo0MDoialVLZWNPNm4xeFBXMklPVzE1blpRa0lkUWhBdmpsWGJWQzlFdUZ0ZSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwNSI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1783522044);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_settings`
--

DROP TABLE IF EXISTS `site_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `logo_header` varchar(255) DEFAULT NULL,
  `favicon` varchar(255) DEFAULT NULL,
  `nama_website` varchar(255) DEFAULT NULL,
  `tagline` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `warna_primary` varchar(255) DEFAULT NULL,
  `warna_secondary` varchar(255) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `link_instagram` varchar(255) DEFAULT NULL,
  `link_facebook` varchar(255) DEFAULT NULL,
  `link_youtube` varchar(255) DEFAULT NULL,
  `link_tiktok` varchar(255) DEFAULT NULL,
  `email_pengirim_notifikasi` varchar(255) DEFAULT NULL,
  `email_kontak` varchar(255) DEFAULT NULL,
  `no_wa_kontak` varchar(255) DEFAULT NULL,
  `teks_copyright` varchar(255) DEFAULT NULL,
  `nama_pembina` varchar(255) DEFAULT NULL,
  `jabatan_pembina` varchar(255) DEFAULT NULL,
  `sambutan_pembina` text DEFAULT NULL,
  `foto_pembina` varchar(255) DEFAULT NULL,
  `sambutan_ketua` text DEFAULT NULL,
  `foto_ketua` varchar(255) DEFAULT NULL,
  `nama_ketua` varchar(255) DEFAULT NULL,
  `teks_copyright_footer` varchar(255) DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `popup_aktif` tinyint(1) NOT NULL DEFAULT 0,
  `popup_gambar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `site_settings_updated_by_foreign` (`updated_by`),
  CONSTRAINT `site_settings_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_settings`
--

LOCK TABLES `site_settings` WRITE;
/*!40000 ALTER TABLE `site_settings` DISABLE KEYS */;
INSERT INTO `site_settings` VALUES (1,'site/01KX3BKCQ2NGXW82ZM1RT0WHFM.png','site/01KX3VMN327WT7R4SMBDPC1BJY.png','Indonesia Channa Contest',NULL,NULL,NULL,NULL,'Indonesia - IND',NULL,NULL,NULL,NULL,'admin@indonesiachannacontest.org','admin@indonesiachannacontest.org','0851-7100-1596',NULL,'Faizal Ariza','Pembina Indonesia Channa Contest','<p><em>Selamat datang di website resmi <strong>Indonesia Channa Contest (ICC)</strong>.</em></p><p><em>Website ini kami hadirkan sebagai pusat informasi resmi mengenai regulasi, kegiatan, standar penjurian, serta berbagai informasi yang berkaitan dengan Indonesia Channa Contest. Kami berharap kehadiran website ini dapat menjadi sarana komunikasi yang terbuka, informatif, dan bermanfaat bagi seluruh penghobi, breeder, juri, penyelenggara, maupun masyarakat pecinta ikan Channa di Indonesia.</em></p><p><em>Indonesia Channa Contest berkomitmen untuk terus menjunjung tinggi nilai <strong>profesionalisme, sportivitas, integritas, dan kebersamaan</strong> dalam setiap penyelenggaraan kontes. Dengan dukungan seluruh pihak, kami optimis dapat bersama-sama membangun ekosistem Channa Indonesia yang semakin maju, berkualitas, dan berdaya saing.</em></p><p><em>Terima kasih atas kepercayaan dan dukungan yang telah diberikan. Mari bersama-sama menjaga semangat persaudaraan serta memajukan dunia Channa Indonesia.</em></p><p><strong><em>Salam Sportivitas,</em></strong></p><p><strong><em>Pembina</em></strong><em><br><strong>Indonesia Channa Contest (ICC)</strong></em></p>','site/01KX3QRAM4S5Z0MY0XKV2NAQFC.png','<p><em>Selamat datang di website resmi <strong>Indonesia Channa Contest (ICC)</strong>.</em></p><p><em>Website ini merupakan media informasi dan komunikasi resmi yang kami hadirkan untuk memberikan akses yang mudah terhadap regulasi, jadwal kegiatan, berita, serta berbagai informasi mengenai Indonesia Channa Contest.</em></p><p><em>Sebagai organisasi yang menaungi penyelenggaraan kontes Channa, kami berkomitmen untuk terus meningkatkan kualitas penyelenggaraan melalui penerapan regulasi yang jelas, sistem penjurian yang profesional, serta pelayanan yang transparan dan berintegritas. Kami percaya bahwa kemajuan komunitas Channa dapat terwujud melalui kolaborasi, sportivitas, dan semangat kebersamaan dari seluruh pihak.</em></p><p><em>Terima kasih atas dukungan dan partisipasi seluruh keluarga besar Indonesia Channa Contest. Mari bersama-sama membangun ICC sebagai organisasi yang profesional, terpercaya, dan menjadi kebanggaan komunitas Channa Indonesia.</em></p><p><strong><em>Salam Sportivitas,</em></strong></p><p><strong><em>Ketua Umum</em></strong><em><br><strong>Indonesia Channa Contest (ICC)</strong></em></p>','site/01KX3QXNBD7HN6NK4G8GYVY8PR.png','M. Ilham Rahmani Riyadi','Indonesia Channa Contest',NULL,1,'site/popup/01KYSDNM81W23EPNR485GC0N7G.jpeg','2026-07-09 02:42:20','2026-07-30 04:48:32');
/*!40000 ALTER TABLE `site_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sliders`
--

DROP TABLE IF EXISTS `sliders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sliders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) DEFAULT NULL,
  `gambar` varchar(255) NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `urutan` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `status_aktif` tinyint(1) NOT NULL DEFAULT 1,
  `tgl_mulai_tayang` date DEFAULT NULL,
  `tgl_selesai_tayang` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sliders`
--

LOCK TABLES `sliders` WRITE;
/*!40000 ALTER TABLE `sliders` DISABLE KEYS */;
INSERT INTO `sliders` VALUES (4,'Selamat Datang Di Website Resmi ICC ','sliders/01KX3R10T03YH77JGBBQ8XYFMK.png',NULL,1,1,NULL,NULL,'2026-07-09 08:30:32','2026-07-09 08:30:58'),(5,'Wadah Prestasi Penggemar Channa','sliders/01KX3R1GTFDQK53BXXHX1H8S76.png',NULL,2,1,NULL,NULL,'2026-07-09 08:30:48','2026-07-09 08:30:48');
/*!40000 ALTER TABLE `sliders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `testimonials`
--

DROP TABLE IF EXISTS `testimonials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `testimonials` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint(20) unsigned NOT NULL,
  `organizer_id` bigint(20) unsigned DEFAULT NULL,
  `isi_testimoni` text NOT NULL,
  `rating` tinyint(3) unsigned DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `testimonials_event_id_foreign` (`event_id`),
  KEY `testimonials_organizer_id_foreign` (`organizer_id`),
  CONSTRAINT `testimonials_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  CONSTRAINT `testimonials_organizer_id_foreign` FOREIGN KEY (`organizer_id`) REFERENCES `organizers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `testimonials`
--

LOCK TABLES `testimonials` WRITE;
/*!40000 ALTER TABLE `testimonials` DISABLE KEYS */;
INSERT INTO `testimonials` VALUES (1,7,7,'Luar Biasa Pengalaman Kontes Purwokerto',5,'approved','2026-07-10 01:18:35','2026-07-10 01:20:53');
/*!40000 ALTER TABLE `testimonials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'penyelenggara',
  `status` varchar(255) NOT NULL DEFAULT 'inactive',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `activation_token` varchar(255) DEFAULT NULL,
  `activation_token_expires_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Super Admin ICC','admin@icc.com','admin',NULL,'$2y$12$O9e3cik9aVgvkmBdKrIVJOrx8/ErBVgIsHQmguq.iVED7duARhLwS','super_admin','active',NULL,'2026-07-08 04:57:42','2026-07-08 04:57:42',NULL,NULL),(8,'Chaerul Amin Subekti','subektiaminchaerul@gmail.com','subektiaminchaerul@gmail.com',NULL,'$2y$12$cmtx.B1B8lybkUa.fLMb2.Bi3bsDyVcyEAf93W1x3Xo0iuj7fPZhK','penyelenggara','active',NULL,'2026-07-09 10:53:44','2026-07-09 21:31:19',NULL,NULL),(10,'Chaerudin Ahmad','channaontran2@gmail.com','channaontran2@gmail.com',NULL,'$2y$12$2khIJoQrDQtKGiFgEuAnTOoaos27BjX22jbTqijDK1D.qEhcBFD4m','penyelenggara','active',NULL,'2026-07-09 21:55:33','2026-07-09 21:56:01',NULL,NULL),(11,'Kharis','co2@gmail.com','co2@gmail.com',NULL,'$2y$12$SLIzMin9GfG.sXe01.88QeiMqd/T8kOqFBt7swCM10FvDk3nHu5Ya','penyelenggara','active',NULL,'2026-07-09 22:51:17','2026-07-09 22:51:42',NULL,NULL),(12,'Chaerul Amin Subekti','chaerul@gmail.com','chaerul@gmail.com',NULL,'$2y$12$CbD4NvICUVFkkI6scA6Hw.aexZSlV.0/.ka5/D6oo/RVLroYHlFXq','penyelenggara','active',NULL,'2026-07-09 23:06:04','2026-07-09 23:08:33',NULL,NULL),(13,'Chaerul Amin Subekti','chaerulaminsubekti@gmail.com','chaerulaminsubekti',NULL,'$2y$12$yNqoo8afh4kC0TSSxmM6COmpCswPog50TbubIxEh71hhLWNV4Lp/.','peserta','active',NULL,'2026-07-11 01:25:20','2026-07-11 01:25:20',NULL,NULL),(14,'Chaerul Amin Subekti','chaerul@kusumahusada.ac.id','chaerul232',NULL,'$2y$12$qJssyXznu7ya9n52tFT1sO3WGEWxJVCJIVqx5a9qGrS2tp6LCp4Bm','peserta','active',NULL,'2026-07-11 01:30:09','2026-07-11 01:30:09',NULL,NULL),(15,'Chaerul Amin Subekti','chaerul@icc.org','chaerul@icc.org',NULL,'$2y$12$jJGfFugfXRkMAIVG/QPG4u9IOHBQ7/OLEa4trwnOfMW2Dna9v7tam','peserta','active',NULL,'2026-07-11 01:35:40','2026-07-11 01:35:40',NULL,NULL),(16,'Chaerul Amin Subekti','chaerul@indonesiachannacontest.org','chaerul@indonesiachannacontest.org',NULL,'$2y$12$aQGbnTRzyuJCL4JXlczuEuKg.1CfCqOQmoVrhM5sXYh22ZK0qBKdW','penyelenggara','active',NULL,'2026-07-11 01:39:56','2026-07-11 01:40:42',NULL,NULL),(17,'Chaerudin Ahmad','chaplin@indonesiachannacontest.org','chaplin@indonesiachannacontest.org',NULL,'$2y$12$Cx61SJDQAcVq9Mqtt2q8U.WPIc2PRm3UEQLnNWC/UVYLTtTGMCzGW','penyelenggara','active',NULL,'2026-07-30 04:04:08','2026-07-30 04:04:47',NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `winner_predikats`
--

DROP TABLE IF EXISTS `winner_predikats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `winner_predikats` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `event_class_id` bigint(20) unsigned NOT NULL,
  `nama_predikat` varchar(255) NOT NULL,
  `urutan` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `winner_predikats_event_class_id_foreign` (`event_class_id`),
  CONSTRAINT `winner_predikats_event_class_id_foreign` FOREIGN KEY (`event_class_id`) REFERENCES `event_classes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=385 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `winner_predikats`
--

LOCK TABLES `winner_predikats` WRITE;
/*!40000 ALTER TABLE `winner_predikats` DISABLE KEYS */;
INSERT INTO `winner_predikats` VALUES (1,1,'Juara 1',1,'2026-07-09 11:48:36','2026-07-09 11:48:36'),(2,1,'Juara 2',2,'2026-07-09 11:48:36','2026-07-09 11:48:36'),(3,1,'Juara 3',3,'2026-07-09 11:48:36','2026-07-09 11:48:36'),(4,1,'Juara 4',4,'2026-07-09 11:48:36','2026-07-09 11:48:36'),(5,1,'Juara 5',5,'2026-07-09 11:48:36','2026-07-09 11:48:36'),(6,1,'Grand Champion Marulioder',6,'2026-07-09 11:48:36','2026-07-09 11:48:36'),(7,1,'Grand Champion Medium',7,'2026-07-09 11:48:36','2026-07-09 11:48:36'),(8,1,'Grand Champion Mini',8,'2026-07-09 11:48:36','2026-07-09 11:48:36'),(9,1,'Best Single Fighter',9,'2026-07-09 11:48:36','2026-07-09 11:48:36'),(10,1,'Best Team',10,'2026-07-09 11:48:36','2026-07-09 11:48:36'),(11,1,'Best Team Support',11,'2026-07-09 11:48:36','2026-07-09 11:48:36'),(12,1,'Best Single Fighter Support',12,'2026-07-09 11:48:36','2026-07-09 11:48:36'),(25,16,'Juara 1',1,'2026-07-09 20:20:51','2026-07-09 20:20:51'),(26,16,'Juara 2',2,'2026-07-09 20:20:51','2026-07-09 20:20:51'),(27,16,'Juara 3',3,'2026-07-09 20:20:51','2026-07-09 20:20:51'),(28,16,'Juara 4',4,'2026-07-09 20:20:51','2026-07-09 20:20:51'),(29,16,'Juara 5',5,'2026-07-09 20:20:51','2026-07-09 20:20:51'),(30,17,'Juara 1',1,'2026-07-09 20:20:51','2026-07-09 20:20:51'),(31,17,'Juara 2',2,'2026-07-09 20:20:51','2026-07-09 20:20:51'),(32,17,'Juara 3',3,'2026-07-09 20:20:51','2026-07-09 20:20:51'),(33,17,'Juara 4',4,'2026-07-09 20:20:51','2026-07-09 20:20:51'),(34,17,'Juara 5',5,'2026-07-09 20:20:51','2026-07-09 20:20:51'),(35,18,'Juara 1',1,'2026-07-09 20:20:51','2026-07-09 20:20:51'),(36,18,'Juara 2',2,'2026-07-09 20:20:51','2026-07-09 20:20:51'),(37,18,'Juara 3',3,'2026-07-09 20:20:51','2026-07-09 20:20:51'),(38,18,'Juara 4',4,'2026-07-09 20:20:51','2026-07-09 20:20:51'),(39,18,'Juara 5',5,'2026-07-09 20:20:51','2026-07-09 20:20:51'),(40,19,'Juara 1',1,'2026-07-09 20:20:51','2026-07-09 20:20:51'),(41,19,'Juara 2',2,'2026-07-09 20:20:51','2026-07-09 20:20:51'),(42,19,'Juara 3',3,'2026-07-09 20:20:51','2026-07-09 20:20:51'),(43,19,'Juara 4',4,'2026-07-09 20:20:51','2026-07-09 20:20:51'),(44,19,'Juara 5',5,'2026-07-09 20:20:51','2026-07-09 20:20:51'),(45,20,'Juara 1',1,'2026-07-09 20:20:51','2026-07-09 20:20:51'),(46,20,'Juara 2',2,'2026-07-09 20:20:51','2026-07-09 20:20:51'),(47,20,'Juara 3',3,'2026-07-09 20:20:51','2026-07-09 20:20:51'),(48,20,'Juara 4',4,'2026-07-09 20:20:51','2026-07-09 20:20:51'),(49,20,'Juara 5',5,'2026-07-09 20:20:51','2026-07-09 20:20:51'),(50,21,'Juara 1',1,'2026-07-09 20:20:51','2026-07-09 20:20:51'),(51,21,'Juara 2',2,'2026-07-09 20:20:51','2026-07-09 20:20:51'),(52,21,'Juara 3',3,'2026-07-09 20:20:51','2026-07-09 20:20:51'),(53,21,'Juara 4',4,'2026-07-09 20:20:51','2026-07-09 20:20:51'),(54,21,'Juara 5',5,'2026-07-09 20:20:51','2026-07-09 20:20:51'),(55,22,'Juara 1',1,'2026-07-09 20:20:51','2026-07-09 20:20:51'),(56,22,'Juara 2',2,'2026-07-09 20:20:51','2026-07-09 20:20:51'),(57,22,'Juara 3',3,'2026-07-09 20:20:51','2026-07-09 20:20:51'),(58,22,'Juara 4',4,'2026-07-09 20:20:51','2026-07-09 20:20:51'),(59,22,'Juara 5',5,'2026-07-09 20:20:51','2026-07-09 20:20:51'),(60,23,'Juara 1',1,'2026-07-09 20:20:51','2026-07-09 20:20:51'),(61,23,'Juara 2',2,'2026-07-09 20:20:51','2026-07-09 20:20:51'),(62,23,'Juara 3',3,'2026-07-09 20:20:51','2026-07-09 20:20:51'),(63,23,'Juara 4',4,'2026-07-09 20:20:51','2026-07-09 20:20:51'),(64,23,'Juara 5',5,'2026-07-09 20:20:51','2026-07-09 20:20:51'),(65,5,'Juara 1',1,'2026-07-09 20:20:54','2026-07-09 20:20:54'),(66,5,'Juara 2',2,'2026-07-09 20:20:54','2026-07-09 20:20:54'),(67,5,'Juara 3',3,'2026-07-09 20:20:54','2026-07-09 20:20:54'),(68,5,'Juara 4',4,'2026-07-09 20:20:54','2026-07-09 20:20:54'),(69,5,'Juara 5',5,'2026-07-09 20:20:54','2026-07-09 20:20:54'),(70,24,'Juara 1',1,'2026-07-09 21:59:18','2026-07-09 21:59:18'),(71,24,'Juara 2',2,'2026-07-09 21:59:18','2026-07-09 21:59:18'),(72,24,'Juara 3',3,'2026-07-09 21:59:18','2026-07-09 21:59:18'),(73,24,'Juara 4',4,'2026-07-09 21:59:18','2026-07-09 21:59:18'),(74,24,'Juara 5',5,'2026-07-09 21:59:18','2026-07-09 21:59:18'),(75,25,'Juara 1',1,'2026-07-09 21:59:18','2026-07-09 21:59:18'),(76,25,'Juara 2',2,'2026-07-09 21:59:18','2026-07-09 21:59:18'),(77,25,'Juara 3',3,'2026-07-09 21:59:18','2026-07-09 21:59:18'),(78,25,'Juara 4',4,'2026-07-09 21:59:18','2026-07-09 21:59:18'),(79,25,'Juara 5',5,'2026-07-09 21:59:18','2026-07-09 21:59:18'),(80,26,'Juara 1',1,'2026-07-09 21:59:18','2026-07-09 21:59:18'),(81,26,'Juara 2',2,'2026-07-09 21:59:18','2026-07-09 21:59:18'),(82,26,'Juara 3',3,'2026-07-09 21:59:18','2026-07-09 21:59:18'),(83,26,'Juara 4',4,'2026-07-09 21:59:18','2026-07-09 21:59:18'),(84,26,'Juara 5',5,'2026-07-09 21:59:18','2026-07-09 21:59:18'),(85,27,'Juara 1',1,'2026-07-09 21:59:18','2026-07-09 21:59:18'),(86,27,'Juara 2',2,'2026-07-09 21:59:18','2026-07-09 21:59:18'),(87,27,'Juara 3',3,'2026-07-09 21:59:18','2026-07-09 21:59:18'),(88,27,'Juara 4',4,'2026-07-09 21:59:18','2026-07-09 21:59:18'),(89,27,'Juara 5',5,'2026-07-09 21:59:18','2026-07-09 21:59:18'),(90,28,'Juara 1',1,'2026-07-09 21:59:18','2026-07-09 21:59:18'),(91,28,'Juara 2',2,'2026-07-09 21:59:18','2026-07-09 21:59:18'),(92,28,'Juara 3',3,'2026-07-09 21:59:18','2026-07-09 21:59:18'),(93,28,'Juara 4',4,'2026-07-09 21:59:18','2026-07-09 21:59:18'),(94,28,'Juara 5',5,'2026-07-09 21:59:18','2026-07-09 21:59:18'),(95,29,'Juara 1',1,'2026-07-09 21:59:18','2026-07-09 21:59:18'),(96,29,'Juara 2',2,'2026-07-09 21:59:18','2026-07-09 21:59:18'),(97,29,'Juara 3',3,'2026-07-09 21:59:18','2026-07-09 21:59:18'),(98,29,'Juara 4',4,'2026-07-09 21:59:18','2026-07-09 21:59:18'),(99,29,'Juara 5',5,'2026-07-09 21:59:18','2026-07-09 21:59:18'),(100,30,'Juara 1',1,'2026-07-09 21:59:18','2026-07-09 21:59:18'),(101,30,'Juara 2',2,'2026-07-09 21:59:18','2026-07-09 21:59:18'),(102,30,'Juara 3',3,'2026-07-09 21:59:18','2026-07-09 21:59:18'),(103,30,'Juara 4',4,'2026-07-09 21:59:18','2026-07-09 21:59:18'),(104,30,'Juara 5',5,'2026-07-09 21:59:18','2026-07-09 21:59:18'),(105,31,'Juara 1',1,'2026-07-09 21:59:18','2026-07-09 21:59:18'),(106,31,'Juara 2',2,'2026-07-09 21:59:18','2026-07-09 21:59:18'),(107,31,'Juara 3',3,'2026-07-09 21:59:18','2026-07-09 21:59:18'),(108,31,'Juara 4',4,'2026-07-09 21:59:18','2026-07-09 21:59:18'),(109,31,'Juara 5',5,'2026-07-09 21:59:18','2026-07-09 21:59:18'),(110,32,'Juara 1',1,'2026-07-09 22:01:02','2026-07-09 22:01:02'),(111,32,'Juara 2',2,'2026-07-09 22:01:02','2026-07-09 22:01:02'),(112,32,'Juara 3',3,'2026-07-09 22:01:02','2026-07-09 22:01:02'),(113,32,'Juara 4',4,'2026-07-09 22:01:02','2026-07-09 22:01:02'),(114,32,'Juara 5',5,'2026-07-09 22:01:02','2026-07-09 22:01:02'),(115,33,'Juara 1',1,'2026-07-09 22:54:06','2026-07-09 22:54:06'),(116,33,'Juara 2',2,'2026-07-09 22:54:06','2026-07-09 22:54:06'),(117,33,'Juara 3',3,'2026-07-09 22:54:06','2026-07-09 22:54:06'),(118,33,'Juara 4',4,'2026-07-09 22:54:06','2026-07-09 22:54:06'),(119,33,'Juara 5',5,'2026-07-09 22:54:06','2026-07-09 22:54:06'),(120,34,'Juara 1',1,'2026-07-09 22:54:06','2026-07-09 22:54:06'),(121,34,'Juara 2',2,'2026-07-09 22:54:06','2026-07-09 22:54:06'),(122,34,'Juara 3',3,'2026-07-09 22:54:06','2026-07-09 22:54:06'),(123,34,'Juara 4',4,'2026-07-09 22:54:06','2026-07-09 22:54:06'),(124,34,'Juara 5',5,'2026-07-09 22:54:06','2026-07-09 22:54:06'),(125,35,'Juara 1',1,'2026-07-09 22:54:06','2026-07-09 22:54:06'),(126,35,'Juara 2',2,'2026-07-09 22:54:06','2026-07-09 22:54:06'),(127,35,'Juara 3',3,'2026-07-09 22:54:06','2026-07-09 22:54:06'),(128,35,'Juara 4',4,'2026-07-09 22:54:06','2026-07-09 22:54:06'),(129,35,'Juara 5',5,'2026-07-09 22:54:06','2026-07-09 22:54:06'),(130,36,'Juara 1',1,'2026-07-09 22:54:06','2026-07-09 22:54:06'),(131,36,'Juara 2',2,'2026-07-09 22:54:06','2026-07-09 22:54:06'),(132,36,'Juara 3',3,'2026-07-09 22:54:06','2026-07-09 22:54:06'),(133,36,'Juara 4',4,'2026-07-09 22:54:06','2026-07-09 22:54:06'),(134,36,'Juara 5',5,'2026-07-09 22:54:06','2026-07-09 22:54:06'),(135,37,'Juara 1',1,'2026-07-09 22:54:06','2026-07-09 22:54:06'),(136,37,'Juara 2',2,'2026-07-09 22:54:06','2026-07-09 22:54:06'),(137,37,'Juara 3',3,'2026-07-09 22:54:06','2026-07-09 22:54:06'),(138,37,'Juara 4',4,'2026-07-09 22:54:06','2026-07-09 22:54:06'),(139,37,'Juara 5',5,'2026-07-09 22:54:06','2026-07-09 22:54:06'),(140,38,'Juara 1',1,'2026-07-09 22:54:06','2026-07-09 22:54:06'),(141,38,'Juara 2',2,'2026-07-09 22:54:06','2026-07-09 22:54:06'),(142,38,'Juara 3',3,'2026-07-09 22:54:06','2026-07-09 22:54:06'),(143,38,'Juara 4',4,'2026-07-09 22:54:06','2026-07-09 22:54:06'),(144,38,'Juara 5',5,'2026-07-09 22:54:06','2026-07-09 22:54:06'),(145,39,'Juara 1',1,'2026-07-09 22:54:06','2026-07-09 22:54:06'),(146,39,'Juara 2',2,'2026-07-09 22:54:06','2026-07-09 22:54:06'),(147,39,'Juara 3',3,'2026-07-09 22:54:06','2026-07-09 22:54:06'),(148,39,'Juara 4',4,'2026-07-09 22:54:06','2026-07-09 22:54:06'),(149,39,'Juara 5',5,'2026-07-09 22:54:06','2026-07-09 22:54:06'),(150,40,'Juara 1',1,'2026-07-09 22:54:06','2026-07-09 22:54:06'),(151,40,'Juara 2',2,'2026-07-09 22:54:06','2026-07-09 22:54:06'),(152,40,'Juara 3',3,'2026-07-09 22:54:06','2026-07-09 22:54:06'),(153,40,'Juara 4',4,'2026-07-09 22:54:06','2026-07-09 22:54:06'),(154,40,'Juara 5',5,'2026-07-09 22:54:06','2026-07-09 22:54:06'),(195,49,'Juara 1',1,'2026-08-02 11:10:14','2026-08-02 11:10:14'),(196,49,'Juara 2',2,'2026-08-02 11:10:14','2026-08-02 11:10:14'),(197,49,'Juara 3',3,'2026-08-02 11:10:14','2026-08-02 11:10:14'),(198,49,'Juara 4',4,'2026-08-02 11:10:14','2026-08-02 11:10:14'),(199,49,'Juara 5',5,'2026-08-02 11:10:14','2026-08-02 11:10:14'),(200,50,'Juara 1',1,'2026-08-02 11:10:27','2026-08-02 11:10:27'),(201,50,'Juara 2',2,'2026-08-02 11:10:27','2026-08-02 11:10:27'),(202,50,'Juara 3',3,'2026-08-02 11:10:27','2026-08-02 11:10:27'),(203,50,'Juara 4',4,'2026-08-02 11:10:27','2026-08-02 11:10:27'),(204,50,'Juara 5',5,'2026-08-02 11:10:27','2026-08-02 11:10:27'),(205,51,'Juara 1',1,'2026-08-02 11:10:38','2026-08-02 11:10:38'),(206,51,'Juara 2',2,'2026-08-02 11:10:38','2026-08-02 11:10:38'),(207,51,'Juara 3',3,'2026-08-02 11:10:38','2026-08-02 11:10:38'),(208,51,'Juara 4',4,'2026-08-02 11:10:38','2026-08-02 11:10:38'),(209,51,'Juara 5',5,'2026-08-02 11:10:38','2026-08-02 11:10:38'),(210,52,'Juara 1',1,'2026-08-02 11:10:47','2026-08-02 11:10:47'),(211,52,'Juara 2',2,'2026-08-02 11:10:47','2026-08-02 11:10:47'),(212,52,'Juara 3',3,'2026-08-02 11:10:47','2026-08-02 11:10:47'),(213,52,'Juara 4',4,'2026-08-02 11:10:47','2026-08-02 11:10:47'),(214,52,'Juara 5',5,'2026-08-02 11:10:47','2026-08-02 11:10:47'),(215,53,'Juara 1',1,'2026-08-02 11:10:55','2026-08-02 11:10:55'),(216,53,'Juara 2',2,'2026-08-02 11:10:55','2026-08-02 11:10:55'),(217,53,'Juara 3',3,'2026-08-02 11:10:55','2026-08-02 11:10:55'),(218,53,'Juara 4',4,'2026-08-02 11:10:55','2026-08-02 11:10:55'),(219,53,'Juara 5',5,'2026-08-02 11:10:55','2026-08-02 11:10:55'),(220,54,'Juara 1',1,'2026-08-02 11:11:09','2026-08-02 11:11:09'),(221,54,'Juara 2',2,'2026-08-02 11:11:09','2026-08-02 11:11:09'),(222,54,'Juara 3',3,'2026-08-02 11:11:09','2026-08-02 11:11:09'),(223,54,'Juara 4',4,'2026-08-02 11:11:09','2026-08-02 11:11:09'),(224,54,'Juara 5',5,'2026-08-02 11:11:09','2026-08-02 11:11:09'),(225,55,'Juara 1',1,'2026-08-02 11:11:43','2026-08-02 11:11:43'),(226,55,'Juara 2',2,'2026-08-02 11:11:43','2026-08-02 11:11:43'),(227,55,'Juara 3',3,'2026-08-02 11:11:43','2026-08-02 11:11:43'),(228,55,'Juara 4',4,'2026-08-02 11:11:43','2026-08-02 11:11:43'),(229,55,'Juara 5',5,'2026-08-02 11:11:43','2026-08-02 11:11:43'),(230,56,'Juara 1',1,'2026-08-02 11:11:58','2026-08-02 11:11:58'),(231,56,'Juara 2',2,'2026-08-02 11:11:58','2026-08-02 11:11:58'),(232,56,'Juara 3',3,'2026-08-02 11:11:58','2026-08-02 11:11:58'),(233,56,'Juara 4',4,'2026-08-02 11:11:58','2026-08-02 11:11:58'),(234,56,'Juara 5',5,'2026-08-02 11:11:58','2026-08-02 11:11:58'),(235,57,'Juara 1',1,'2026-08-02 11:12:05','2026-08-02 11:12:05'),(236,57,'Juara 2',2,'2026-08-02 11:12:05','2026-08-02 11:12:05'),(237,57,'Juara 3',3,'2026-08-02 11:12:05','2026-08-02 11:12:05'),(238,57,'Juara 4',4,'2026-08-02 11:12:05','2026-08-02 11:12:05'),(239,57,'Juara 5',5,'2026-08-02 11:12:05','2026-08-02 11:12:05'),(240,58,'Juara 1',1,'2026-08-02 11:12:14','2026-08-02 11:12:14'),(241,58,'Juara 2',2,'2026-08-02 11:12:14','2026-08-02 11:12:14'),(242,58,'Juara 3',3,'2026-08-02 11:12:14','2026-08-02 11:12:14'),(243,58,'Juara 4',4,'2026-08-02 11:12:14','2026-08-02 11:12:14'),(244,58,'Juara 5',5,'2026-08-02 11:12:14','2026-08-02 11:12:14'),(245,59,'Juara 1',1,'2026-08-02 11:12:26','2026-08-02 11:12:26'),(246,59,'Juara 2',2,'2026-08-02 11:12:26','2026-08-02 11:12:26'),(247,59,'Juara 3',3,'2026-08-02 11:12:26','2026-08-02 11:12:26'),(248,59,'Juara 4',4,'2026-08-02 11:12:26','2026-08-02 11:12:26'),(249,59,'Juara 5',5,'2026-08-02 11:12:26','2026-08-02 11:12:26'),(250,60,'Juara 1',1,'2026-08-02 11:12:39','2026-08-02 11:12:39'),(251,60,'Juara 2',2,'2026-08-02 11:12:39','2026-08-02 11:12:39'),(252,60,'Juara 3',3,'2026-08-02 11:12:39','2026-08-02 11:12:39'),(253,60,'Juara 4',4,'2026-08-02 11:12:39','2026-08-02 11:12:39'),(254,60,'Juara 5',5,'2026-08-02 11:12:39','2026-08-02 11:12:39'),(255,61,'Juara 1',1,'2026-08-02 11:12:49','2026-08-02 11:12:49'),(256,61,'Juara 2',2,'2026-08-02 11:12:49','2026-08-02 11:12:49'),(257,61,'Juara 3',3,'2026-08-02 11:12:49','2026-08-02 11:12:49'),(258,61,'Juara 4',4,'2026-08-02 11:12:49','2026-08-02 11:12:49'),(259,61,'Juara 5',5,'2026-08-02 11:12:49','2026-08-02 11:12:49'),(260,62,'Juara 1',1,'2026-08-02 11:12:58','2026-08-02 11:12:58'),(261,62,'Juara 2',2,'2026-08-02 11:12:58','2026-08-02 11:12:58'),(262,62,'Juara 3',3,'2026-08-02 11:12:58','2026-08-02 11:12:58'),(263,62,'Juara 4',4,'2026-08-02 11:12:58','2026-08-02 11:12:58'),(264,62,'Juara 5',5,'2026-08-02 11:12:58','2026-08-02 11:12:58'),(265,63,'Juara 1',1,'2026-08-02 11:13:08','2026-08-02 11:13:08'),(266,63,'Juara 2',2,'2026-08-02 11:13:08','2026-08-02 11:13:08'),(267,63,'Juara 3',3,'2026-08-02 11:13:08','2026-08-02 11:13:08'),(268,63,'Juara 4',4,'2026-08-02 11:13:08','2026-08-02 11:13:08'),(269,63,'Juara 5',5,'2026-08-02 11:13:08','2026-08-02 11:13:08'),(310,1,'Juara 1',1,'2026-08-02 20:32:24','2026-08-02 20:32:24'),(311,1,'Juara 2',2,'2026-08-02 20:32:24','2026-08-02 20:32:24'),(312,1,'Juara 3',3,'2026-08-02 20:32:24','2026-08-02 20:32:24'),(313,1,'Juara 4',4,'2026-08-02 20:32:24','2026-08-02 20:32:24'),(314,1,'Juara 5',5,'2026-08-02 20:32:24','2026-08-02 20:32:24'),(315,2,'Juara 1',1,'2026-08-02 23:52:59','2026-08-02 23:52:59'),(316,2,'Juara 2',2,'2026-08-02 23:52:59','2026-08-02 23:52:59'),(317,2,'Juara 3',3,'2026-08-02 23:52:59','2026-08-02 23:52:59'),(318,2,'Juara 4',4,'2026-08-02 23:52:59','2026-08-02 23:52:59'),(319,2,'Juara 5',5,'2026-08-02 23:52:59','2026-08-02 23:52:59'),(320,3,'Juara 1',1,'2026-08-03 01:01:59','2026-08-03 01:01:59'),(321,3,'Juara 2',2,'2026-08-03 01:01:59','2026-08-03 01:01:59'),(322,3,'Juara 3',3,'2026-08-03 01:01:59','2026-08-03 01:01:59'),(323,3,'Juara 4',4,'2026-08-03 01:01:59','2026-08-03 01:01:59'),(324,3,'Juara 5',5,'2026-08-03 01:01:59','2026-08-03 01:01:59'),(325,4,'Juara 1',1,'2026-08-03 01:02:32','2026-08-03 01:02:32'),(326,4,'Juara 2',2,'2026-08-03 01:02:32','2026-08-03 01:02:32'),(327,4,'Juara 3',3,'2026-08-03 01:02:32','2026-08-03 01:02:32'),(328,4,'Juara 4',4,'2026-08-03 01:02:32','2026-08-03 01:02:32'),(329,4,'Juara 5',5,'2026-08-03 01:02:32','2026-08-03 01:02:32'),(330,5,'Juara 1',1,'2026-08-03 01:02:45','2026-08-03 01:02:45'),(331,5,'Juara 2',2,'2026-08-03 01:02:45','2026-08-03 01:02:45'),(332,5,'Juara 3',3,'2026-08-03 01:02:45','2026-08-03 01:02:45'),(333,5,'Juara 4',4,'2026-08-03 01:02:45','2026-08-03 01:02:45'),(334,5,'Juara 5',5,'2026-08-03 01:02:45','2026-08-03 01:02:45'),(335,6,'Juara 1',1,'2026-08-03 01:02:52','2026-08-03 01:02:52'),(336,6,'Juara 2',2,'2026-08-03 01:02:52','2026-08-03 01:02:52'),(337,6,'Juara 3',3,'2026-08-03 01:02:52','2026-08-03 01:02:52'),(338,6,'Juara 4',4,'2026-08-03 01:02:52','2026-08-03 01:02:52'),(339,6,'Juara 5',5,'2026-08-03 01:02:52','2026-08-03 01:02:52'),(340,7,'Juara 1',1,'2026-08-03 01:03:10','2026-08-03 01:03:10'),(341,7,'Juara 2',2,'2026-08-03 01:03:10','2026-08-03 01:03:10'),(342,7,'Juara 3',3,'2026-08-03 01:03:10','2026-08-03 01:03:10'),(343,7,'Juara 4',4,'2026-08-03 01:03:10','2026-08-03 01:03:10'),(344,7,'Juara 5',5,'2026-08-03 01:03:10','2026-08-03 01:03:10'),(345,8,'Juara 1',1,'2026-08-03 01:03:18','2026-08-03 01:03:18'),(346,8,'Juara 2',2,'2026-08-03 01:03:18','2026-08-03 01:03:18'),(347,8,'Juara 3',3,'2026-08-03 01:03:18','2026-08-03 01:03:18'),(348,8,'Juara 4',4,'2026-08-03 01:03:18','2026-08-03 01:03:18'),(349,8,'Juara 5',5,'2026-08-03 01:03:18','2026-08-03 01:03:18'),(350,9,'Juara 1',1,'2026-08-03 01:03:29','2026-08-03 01:03:29'),(351,9,'Juara 2',2,'2026-08-03 01:03:29','2026-08-03 01:03:29'),(352,9,'Juara 3',3,'2026-08-03 01:03:29','2026-08-03 01:03:29'),(353,9,'Juara 4',4,'2026-08-03 01:03:29','2026-08-03 01:03:29'),(354,9,'Juara 5',5,'2026-08-03 01:03:29','2026-08-03 01:03:29'),(355,10,'Juara 1',1,'2026-08-03 01:03:41','2026-08-03 01:03:41'),(356,10,'Juara 2',2,'2026-08-03 01:03:41','2026-08-03 01:03:41'),(357,10,'Juara 3',3,'2026-08-03 01:03:41','2026-08-03 01:03:41'),(358,10,'Juara 4',4,'2026-08-03 01:03:41','2026-08-03 01:03:41'),(359,10,'Juara 5',5,'2026-08-03 01:03:41','2026-08-03 01:03:41'),(360,11,'Juara 1',1,'2026-08-03 01:04:04','2026-08-03 01:04:04'),(361,11,'Juara 2',2,'2026-08-03 01:04:04','2026-08-03 01:04:04'),(362,11,'Juara 3',3,'2026-08-03 01:04:04','2026-08-03 01:04:04'),(363,11,'Juara 4',4,'2026-08-03 01:04:04','2026-08-03 01:04:04'),(364,11,'Juara 5',5,'2026-08-03 01:04:04','2026-08-03 01:04:04'),(365,12,'Juara 1',1,'2026-08-03 01:04:13','2026-08-03 01:04:13'),(366,12,'Juara 2',2,'2026-08-03 01:04:13','2026-08-03 01:04:13'),(367,12,'Juara 3',3,'2026-08-03 01:04:13','2026-08-03 01:04:13'),(368,12,'Juara 4',4,'2026-08-03 01:04:13','2026-08-03 01:04:13'),(369,12,'Juara 5',5,'2026-08-03 01:04:13','2026-08-03 01:04:13'),(370,13,'Juara 1',1,'2026-08-03 01:04:23','2026-08-03 01:04:23'),(371,13,'Juara 2',2,'2026-08-03 01:04:23','2026-08-03 01:04:23'),(372,13,'Juara 3',3,'2026-08-03 01:04:23','2026-08-03 01:04:23'),(373,13,'Juara 4',4,'2026-08-03 01:04:23','2026-08-03 01:04:23'),(374,13,'Juara 5',5,'2026-08-03 01:04:23','2026-08-03 01:04:23'),(375,14,'Juara 1',1,'2026-08-03 01:04:30','2026-08-03 01:04:30'),(376,14,'Juara 2',2,'2026-08-03 01:04:30','2026-08-03 01:04:30'),(377,14,'Juara 3',3,'2026-08-03 01:04:30','2026-08-03 01:04:30'),(378,14,'Juara 4',4,'2026-08-03 01:04:30','2026-08-03 01:04:30'),(379,14,'Juara 5',5,'2026-08-03 01:04:30','2026-08-03 01:04:30'),(380,15,'Juara 1',1,'2026-08-03 01:04:40','2026-08-03 01:04:40'),(381,15,'Juara 2',2,'2026-08-03 01:04:40','2026-08-03 01:04:40'),(382,15,'Juara 3',3,'2026-08-03 01:04:40','2026-08-03 01:04:40'),(383,15,'Juara 4',4,'2026-08-03 01:04:40','2026-08-03 01:04:40'),(384,15,'Juara 5',5,'2026-08-03 01:04:40','2026-08-03 01:04:40');
/*!40000 ALTER TABLE `winner_predikats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `winners`
--

DROP TABLE IF EXISTS `winners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `winners` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint(20) unsigned NOT NULL,
  `event_class_id` bigint(20) unsigned DEFAULT NULL,
  `winner_predikat_id` bigint(20) unsigned DEFAULT NULL,
  `nama_pemenang` varchar(255) NOT NULL,
  `peringkat` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `winners_event_id_foreign` (`event_id`),
  KEY `winners_event_class_id_foreign` (`event_class_id`),
  KEY `winners_winner_predikat_id_foreign` (`winner_predikat_id`),
  CONSTRAINT `winners_event_class_id_foreign` FOREIGN KEY (`event_class_id`) REFERENCES `event_classes` (`id`) ON DELETE SET NULL,
  CONSTRAINT `winners_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  CONSTRAINT `winners_winner_predikat_id_foreign` FOREIGN KEY (`winner_predikat_id`) REFERENCES `winner_predikats` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `winners`
--

LOCK TABLES `winners` WRITE;
/*!40000 ALTER TABLE `winners` DISABLE KEYS */;
INSERT INTO `winners` VALUES (1,7,1,1,'AAA',NULL,'2026-07-09 12:38:15','2026-07-09 12:38:15'),(6,7,16,NULL,'Channa System Syndicate',NULL,'2026-07-09 20:24:53','2026-07-09 20:24:53'),(7,7,19,NULL,'Citra Jaya Snakehead',NULL,'2026-07-09 20:38:32','2026-07-09 20:38:32'),(8,9,24,NULL,'CSS - Jabar Ngahiji',NULL,'2026-07-09 21:59:39','2026-07-09 21:59:39'),(9,10,33,NULL,'Citra Jaya Snakehead',NULL,'2026-07-09 22:54:20','2026-07-09 22:54:20');
/*!40000 ALTER TABLE `winners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'portal-icc'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-08-18 16:32:43
