-- MySQL dump 10.13  Distrib 8.0.43, for Linux (x86_64)
--
-- Host: localhost    Database: jrbbin_flight_booking
-- ------------------------------------------------------
-- Server version	8.0.43-0ubuntu0.22.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admins` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bookings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` enum('MALE','FEMALE') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `passport_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `passport_expiry` date DEFAULT NULL,
  `nationality` varchar(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amadeus_order_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `flight_offer` json DEFAULT NULL,
  `stripe_payment_intent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_payment_intent_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stripe_refund_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `refunded_at` timestamp NULL DEFAULT NULL,
  `stripe_metadata` text COLLATE utf8mb4_unicode_ci,
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `booking_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bookings_booking_token_unique` (`booking_token`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bookings`
--

LOCK TABLES `bookings` WRITE;
/*!40000 ALTER TABLE `bookings` DISABLE KEYS */;
INSERT INTO `bookings` VALUES (1,'Muhammad Astafa','Butt','astafabutt3@gmail.com','03044562709','2025-09-30','MALE','325325235325','2025-09-30','21',NULL,'\"{\\\"type\\\":\\\"flight-offer\\\",\\\"id\\\":\\\"65\\\",\\\"source\\\":\\\"GDS\\\",\\\"instantTicketingRequired\\\":false,\\\"nonHomogeneous\\\":false,\\\"oneWay\\\":false,\\\"isUpsellOffer\\\":false,\\\"lastTicketingDate\\\":\\\"2025-09-29\\\",\\\"lastTicketingDateTime\\\":\\\"2025-09-29\\\",\\\"numberOfBookableSeats\\\":9,\\\"itineraries\\\":[{\\\"duration\\\":\\\"PT7H15M\\\",\\\"segments\\\":[{\\\"departure\\\":{\\\"iataCode\\\":\\\"DEL\\\",\\\"terminal\\\":\\\"1D\\\",\\\"at\\\":\\\"2025-09-30T07:00:00\\\"},\\\"arrival\\\":{\\\"iataCode\\\":\\\"IXB\\\",\\\"at\\\":\\\"2025-09-30T09:25:00\\\"},\\\"carrierCode\\\":\\\"SG\\\",\\\"number\\\":\\\"903\\\",\\\"aircraft\\\":{\\\"code\\\":\\\"737\\\"},\\\"operating\\\":{\\\"carrierCode\\\":\\\"SG\\\"},\\\"duration\\\":\\\"PT2H25M\\\",\\\"id\\\":\\\"32\\\",\\\"numberOfStops\\\":0,\\\"blacklistedInEU\\\":false},{\\\"departure\\\":{\\\"iataCode\\\":\\\"IXB\\\",\\\"at\\\":\\\"2025-09-30T11:15:00\\\"},\\\"arrival\\\":{\\\"iataCode\\\":\\\"BOM\\\",\\\"terminal\\\":\\\"1\\\",\\\"at\\\":\\\"2025-09-30T14:15:00\\\"},\\\"carrierCode\\\":\\\"SG\\\",\\\"number\\\":\\\"506\\\",\\\"aircraft\\\":{\\\"code\\\":\\\"737\\\"},\\\"operating\\\":{\\\"carrierCode\\\":\\\"SG\\\"},\\\"duration\\\":\\\"PT3H\\\",\\\"id\\\":\\\"33\\\",\\\"numberOfStops\\\":0,\\\"blacklistedInEU\\\":false}]}],\\\"price\\\":{\\\"currency\\\":\\\"INR\\\",\\\"total\\\":\\\"24474.00\\\",\\\"base\\\":\\\"19891.00\\\",\\\"fees\\\":[{\\\"amount\\\":\\\"0.00\\\",\\\"type\\\":\\\"SUPPLIER\\\"},{\\\"amount\\\":\\\"0.00\\\",\\\"type\\\":\\\"TICKETING\\\"}],\\\"grandTotal\\\":\\\"24474.00\\\"},\\\"pricingOptions\\\":{\\\"fareType\\\":[\\\"PUBLISHED\\\"],\\\"includedCheckedBagsOnly\\\":true},\\\"validatingAirlineCodes\\\":[\\\"GP\\\"],\\\"travelerPricings\\\":[{\\\"travelerId\\\":\\\"1\\\",\\\"fareOption\\\":\\\"STANDARD\\\",\\\"travelerType\\\":\\\"ADULT\\\",\\\"price\\\":{\\\"currency\\\":\\\"INR\\\",\\\"total\\\":\\\"24474.00\\\",\\\"base\\\":\\\"19891.00\\\"},\\\"fareDetailsBySegment\\\":[{\\\"segmentId\\\":\\\"32\\\",\\\"cabin\\\":\\\"ECONOMY\\\",\\\"fareBasis\\\":\\\"URTSAVER\\\",\\\"class\\\":\\\"U\\\",\\\"includedCheckedBags\\\":{\\\"weight\\\":15,\\\"weightUnit\\\":\\\"KG\\\"},\\\"includedCabinBags\\\":{\\\"weight\\\":7,\\\"weightUnit\\\":\\\"KG\\\"}},{\\\"segmentId\\\":\\\"33\\\",\\\"cabin\\\":\\\"ECONOMY\\\",\\\"fareBasis\\\":\\\"URTSAVER\\\",\\\"class\\\":\\\"U\\\",\\\"includedCheckedBags\\\":{\\\"weight\\\":15,\\\"weightUnit\\\":\\\"KG\\\"},\\\"includedCabinBags\\\":{\\\"weight\\\":7,\\\"weightUnit\\\":\\\"KG\\\"}}]}]}\"',NULL,'pending',NULL,NULL,NULL,NULL,NULL,24474.00,NULL,'2025-09-29 10:54:26','2025-09-29 10:54:26'),(2,'Muhammad Astafa','Butt','astafabutt3@gmail.com','03044562709','2025-09-30','MALE','325325235325','2025-09-30','32',NULL,'\"{\\\"type\\\":\\\"flight-offer\\\",\\\"id\\\":\\\"1\\\",\\\"source\\\":\\\"GDS\\\",\\\"instantTicketingRequired\\\":false,\\\"nonHomogeneous\\\":false,\\\"oneWay\\\":false,\\\"isUpsellOffer\\\":false,\\\"lastTicketingDate\\\":\\\"2025-09-29\\\",\\\"lastTicketingDateTime\\\":\\\"2025-09-29\\\",\\\"numberOfBookableSeats\\\":8,\\\"itineraries\\\":[{\\\"duration\\\":\\\"PT2H25M\\\",\\\"segments\\\":[{\\\"departure\\\":{\\\"iataCode\\\":\\\"DEL\\\",\\\"terminal\\\":\\\"3\\\",\\\"at\\\":\\\"2025-09-30T14:10:00\\\"},\\\"arrival\\\":{\\\"iataCode\\\":\\\"BOM\\\",\\\"terminal\\\":\\\"2\\\",\\\"at\\\":\\\"2025-09-30T16:35:00\\\"},\\\"carrierCode\\\":\\\"AI\\\",\\\"number\\\":\\\"2112\\\",\\\"aircraft\\\":{\\\"code\\\":\\\"32N\\\"},\\\"operating\\\":{\\\"carrierCode\\\":\\\"AI\\\"},\\\"duration\\\":\\\"PT2H25M\\\",\\\"id\\\":\\\"56\\\",\\\"numberOfStops\\\":0,\\\"blacklistedInEU\\\":false}]}],\\\"price\\\":{\\\"currency\\\":\\\"INR\\\",\\\"total\\\":\\\"6224.00\\\",\\\"base\\\":\\\"5303.00\\\",\\\"fees\\\":[{\\\"amount\\\":\\\"0.00\\\",\\\"type\\\":\\\"SUPPLIER\\\"},{\\\"amount\\\":\\\"0.00\\\",\\\"type\\\":\\\"TICKETING\\\"}],\\\"grandTotal\\\":\\\"6224.00\\\"},\\\"pricingOptions\\\":{\\\"fareType\\\":[\\\"PUBLISHED\\\"],\\\"includedCheckedBagsOnly\\\":true},\\\"validatingAirlineCodes\\\":[\\\"AI\\\"],\\\"travelerPricings\\\":[{\\\"travelerId\\\":\\\"1\\\",\\\"fareOption\\\":\\\"STANDARD\\\",\\\"travelerType\\\":\\\"ADULT\\\",\\\"price\\\":{\\\"currency\\\":\\\"INR\\\",\\\"total\\\":\\\"6224.00\\\",\\\"base\\\":\\\"5303.00\\\"},\\\"fareDetailsBySegment\\\":[{\\\"segmentId\\\":\\\"56\\\",\\\"cabin\\\":\\\"ECONOMY\\\",\\\"fareBasis\\\":\\\"UL1YXSII\\\",\\\"brandedFare\\\":\\\"ECOVALU\\\",\\\"brandedFareLabel\\\":\\\"ECO VALUE\\\",\\\"class\\\":\\\"U\\\",\\\"includedCheckedBags\\\":{\\\"weight\\\":15,\\\"weightUnit\\\":\\\"KG\\\"},\\\"includedCabinBags\\\":{\\\"weight\\\":7,\\\"weightUnit\\\":\\\"KG\\\"},\\\"amenities\\\":[{\\\"description\\\":\\\"PRE RESERVED SEAT ASSIGNMENT\\\",\\\"isChargeable\\\":false,\\\"amenityType\\\":\\\"PRE_RESERVED_SEAT\\\",\\\"amenityProvider\\\":{\\\"name\\\":\\\"BrandedFare\\\"}},{\\\"description\\\":\\\"MEAL SERVICES\\\",\\\"isChargeable\\\":false,\\\"amenityType\\\":\\\"MEAL\\\",\\\"amenityProvider\\\":{\\\"name\\\":\\\"BrandedFare\\\"}},{\\\"description\\\":\\\"REFUNDABLE TICKET\\\",\\\"isChargeable\\\":true,\\\"amenityType\\\":\\\"BRANDED_FARES\\\",\\\"amenityProvider\\\":{\\\"name\\\":\\\"BrandedFare\\\"}},{\\\"description\\\":\\\"CHANGEABLE TICKET\\\",\\\"isChargeable\\\":true,\\\"amenityType\\\":\\\"BRANDED_FARES\\\",\\\"amenityProvider\\\":{\\\"name\\\":\\\"BrandedFare\\\"}},{\\\"description\\\":\\\"UPGRADE\\\",\\\"isChargeable\\\":true,\\\"amenityType\\\":\\\"UPGRADES\\\",\\\"amenityProvider\\\":{\\\"name\\\":\\\"BrandedFare\\\"}},{\\\"description\\\":\\\"FREE CHECKED BAGGAGE ALLOWANCE\\\",\\\"isChargeable\\\":false,\\\"amenityType\\\":\\\"BRANDED_FARES\\\",\\\"amenityProvider\\\":{\\\"name\\\":\\\"BrandedFare\\\"}}]}]}]}\"',NULL,'pending',NULL,NULL,NULL,NULL,NULL,6224.00,NULL,'2025-09-29 11:24:51','2025-09-29 11:24:51');
/*!40000 ALTER TABLE `bookings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('laravel-cache-amadeus_token','s:28:\"ZDiabzJxkXGG4qEQYdX5tni6calk\";',1759164742);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
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
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2025_09_26_194107_create_bookings_table',1),(5,'2025_09_27_162658_add_stripe_columns_to_bookings_table',1),(6,'2025_09_27_200024_add_refund_columns_to_bookings_table',1),(7,'2025_09_27_200338_update_status_column_in_bookings_table',1),(8,'2025_09_27_202756_create_admins_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
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
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-10-01 13:02:16
