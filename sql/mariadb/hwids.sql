-- Active: 1704156387972@@127.0.0.1@3306@site
-- --------------------------------------------------------
-- Хост:                         127.0.0.1
-- Версия сервера:               11.2.2-MariaDB-1:11.2.2+maria~ubu2204 - mariadb.org binary distribution
-- Операционная система:         debian-linux-gnu
-- HeidiSQL Версия:              12.5.0.6749
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Дамп структуры для таблица site.hwids
CREATE TABLE IF NOT EXISTS `hwids` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `publickey` blob DEFAULT NULL,
  `hwDiskId` varchar(255) DEFAULT NULL,
  `baseboardSerialNumber` varchar(255) DEFAULT NULL,
  `graphicCard` varchar(255) DEFAULT NULL,
  `displayId` blob DEFAULT NULL,
  `bitness` int(11) DEFAULT NULL,
  `totalMemory` bigint(20) DEFAULT NULL,
  `logicalProcessors` int(11) DEFAULT NULL,
  `physicalProcessors` int(11) DEFAULT NULL,
  `processorMaxFreq` bigint(11) DEFAULT NULL,
  `battery` tinyint(1) NOT NULL DEFAULT 0,
  `banned` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `publickey` (`publickey`(255))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- Экспортируемые данные не выделены.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
