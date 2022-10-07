-- Adminer 4.7.0 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `master_bank`;
CREATE TABLE `master_bank` (
  `id` int NOT NULL AUTO_INCREMENT,
  `label` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `value` tinyint NOT NULL,
  `sort_order` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '0',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '1-active,0- inactive',
  `date_created` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `master_bank` (`id`, `label`, `value`, `sort_order`, `status`, `date_created`) VALUES
(1,	'Bank',	1,	'0',	1,	'2022-07-09 14:35:53'),
(2,	'PhonePe',	2,	'0',	1,	'2022-07-09 14:31:03'),
(3,	'Google Pay',	3,	'0',	1,	'2022-07-09 14:36:01');

-- 2022-07-09 15:00:10
