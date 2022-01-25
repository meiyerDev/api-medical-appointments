-- Adminer 4.8.1 MySQL 8.0.26 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `branches`;
CREATE TABLE `branches` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `branches` (`id`, `name`) VALUES
(1,	'Pediatría'),
(2,	'Ginecología'),
(3,	'Cardiología'),
(4,	'Endocrinología');

DROP TABLE IF EXISTS `dates`;
CREATE TABLE `dates` (
  `id` int NOT NULL AUTO_INCREMENT,
  `patient_id` int NOT NULL,
  `branch_id` int NOT NULL,
  `date_at` datetime NOT NULL,
  `doctor_id` int DEFAULT NULL,
  `confirmed_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `patient_id` (`patient_id`),
  KEY `branch_id` (`branch_id`),
  KEY `doctor_id` (`doctor_id`),
  CONSTRAINT `dates_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `dates_ibfk_2` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `dates_ibfk_3` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `dates` (`id`, `patient_id`, `branch_id`, `date_at`, `doctor_id`, `confirmed_at`, `created_at`) VALUES
(1,	2,	1,	'2022-01-26 00:00:00',	2,	'2022-01-25 22:22:16',	'2022-01-25 22:22:16');

DROP TABLE IF EXISTS `doctors`;
CREATE TABLE `doctors` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `user_id` int NOT NULL,
  `branch_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `branch_id` (`branch_id`),
  CONSTRAINT `doctors_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `doctors_ibfk_2` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `doctors` (`id`, `name`, `user_id`, `branch_id`) VALUES
(2,	'Meiyer Jaimes',	5,	1);

DROP TABLE IF EXISTS `patients`;
CREATE TABLE `patients` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `user_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `patients_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `patients` (`id`, `name`, `email`, `user_id`) VALUES
(1,	'Meiyer Jaimes',	'meiyer.patient.1@gmail.com',	1),
(2,	'Meiyer Jaimes',	'meiyer.patient.2@gmail.com',	2);

DROP TABLE IF EXISTS `tokens`;
CREATE TABLE `tokens` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `status` tinyint NOT NULL,
  `token` longtext NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `tokens` (`id`, `user_id`, `status`, `token`, `date`) VALUES
(1,	2,	1,	'261d958a60fdc3c55a629ad1ce165fc0',	'2022-01-25 22:21:00'),
(2,	5,	1,	'0ec98968c59f24cc137c785283ea153f',	'2022-01-25 22:21:00');

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `role_id` enum('1','2') NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role_id`) VALUES
(1,	'Meiyer Jaimes',	'meiyer.patient.1@gmail.com',	'25d55ad283aa400af464c76d713c07ad',	'2'),
(2,	'Meiyer Jaimes',	'meiyer.patient.2@gmail.com',	'25d55ad283aa400af464c76d713c07ad',	'2'),
(5,	'Meiyer Jaimes',	'meiyer.doctor@gmail.com',	'25d55ad283aa400af464c76d713c07ad',	'1');

-- 2022-01-25 22:23:21