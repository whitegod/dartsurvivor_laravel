/*
SQLyog Community v12.4.2 (64 bit)
MySQL - 10.4.32-MariaDB : Database - dart
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`dart` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `dart`;

/*Table structure for table `admins` */

DROP TABLE IF EXISTS `admins`;

CREATE TABLE `admins` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT NULL,
  `first_name` varchar(20) DEFAULT NULL,
  `last_name` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address1` varchar(100) DEFAULT NULL,
  `address2` varchar(100) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `zip` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `admins` */

insert  into `admins`(`id`,`user_id`,`status`,`first_name`,`last_name`,`email`,`phone`,`address1`,`address2`,`city`,`state`,`zip`,`created_at`,`updated_at`,`deleted_at`,`fullname`) values 
(1,1,'active','Super','Admin','admin@dart.com','999-999-9999','7 Dark street','house 29','Indian','IN','54435','2025-05-12 08:46:09','2019-10-31 10:20:51',NULL,'Super Admin');

/*Table structure for table `role_user` */

DROP TABLE IF EXISTS `role_user`;

CREATE TABLE `role_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1476 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `role_user` */

insert  into `role_user`(`id`,`role_id`,`user_id`,`created_at`,`updated_at`) values 
(1,1,1,'2018-11-01 18:22:00','2018-11-01 18:23:00');

/*Table structure for table `roles` */

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `roles` */

insert  into `roles`(`id`,`created_at`,`updated_at`,`name`,`description`) values 
(1,'2018-11-01 05:30:00','2018-11-01 05:30:00','Admin','Admin'),
(2,'2018-11-01 07:30:00','2018-11-01 07:30:00','User','User');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `client_id` varchar(255) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `token` varchar(255) DEFAULT NULL,
  `password` varchar(200) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `admin_id` varchar(255) DEFAULT NULL,
  `payroll_id` varchar(255) DEFAULT NULL,
  `worker_id` varchar(255) DEFAULT NULL,
  `contact_id` varchar(255) DEFAULT NULL,
  `account_manager_id` varchar(25) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `email_token` varchar(200) DEFAULT NULL,
  `verified` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`name`,`email`,`client_id`,`status`,`token`,`password`,`remember_token`,`admin_id`,`payroll_id`,`worker_id`,`contact_id`,`account_manager_id`,`created_at`,`updated_at`,`email_token`,`verified`) values 
(1,'Dart Admin','admin@dart.com','000',1,'','$2y$10$6E8oWaxqgaMrETR/IYtHEegfRejR9fVqg0f/OfISMJFtqyybQpQYu','LCVvFvLKNjN941Mf1pdEOiOjMcREUZ54S0y2mT1rDsVfADTpsKUDQUkyNTxo',NULL,NULL,NULL,NULL,NULL,'2017-10-13 18:59:32','2019-03-10 09:14:56',NULL,NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
