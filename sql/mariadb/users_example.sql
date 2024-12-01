/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) NOT NULL DEFAULT '',
  `uuid` char(36) NOT NULL DEFAULT uuid(),
  `password` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL,
  `accessToken` char(32) DEFAULT NULL,
  `serverID` varchar(41) DEFAULT NULL,
  `hwidId` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`user_id`) USING BTREE,
  UNIQUE KEY `name` (`username`) USING BTREE,
  UNIQUE KEY `uuid` (`uuid`) USING BTREE,
  UNIQUE KEY `email` (`email`),
  KEY `uuid_idx` (`uuid`) USING BTREE,
  KEY `username_idx` (`username`) USING BTREE,
  KEY `users_hwidfk` (`hwidId`),
  CONSTRAINT `users_hwidfk` FOREIGN KEY (`hwidId`) REFERENCES `hwids` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5784 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
CREATE TYPE "texture_provider_assets_meta" AS ENUM (
  'SLIM');

CREATE TYPE "texture_provider_assets_type" AS ENUM (
  'SKIN',
  'CAPE');

CREATE TABLE texture_provider_users (
  id serial4 NOT NULL,
  username varchar NULL,
  "uuid" varchar NULL,
  CONSTRAINT pk PRIMARY KEY (id),
  CONSTRAINT username_un UNIQUE (username),
  CONSTRAINT uuid_un UNIQUE (uuid)
);

CREATE TABLE texture_provider_user_assets (
  user_id int4 NOT NULL,
  "type" "texture_provider_assets_type" NOT NULL,
  hash varchar NULL,
  "meta" "texture_provider_assets_meta" NULL,
  CONSTRAINT assets_pk PRIMARY KEY (user_id, type),
  CONSTRAINT assets_users_fk FOREIGN KEY (user_id) REFERENCES texture_provider_users(id)
);