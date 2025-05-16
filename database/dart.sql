-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 16, 2025 at 07:46 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dart`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(10) UNSIGNED NOT NULL,
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
  `fullname` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `user_id`, `status`, `first_name`, `last_name`, `email`, `phone`, `address1`, `address2`, `city`, `state`, `zip`, `created_at`, `updated_at`, `deleted_at`, `fullname`) VALUES
(1, 1, 'active', 'Super', 'Admin', 'admin@dart.com', '999-999-9999', '7 Dark street', 'house 29', 'Indian', 'IN', '54435', '2025-05-12 12:46:09', '2019-10-31 14:20:51', NULL, 'Super Admin');

-- --------------------------------------------------------

--
-- Table structure for table `author`
--

CREATE TABLE `author` (
  `id` int(11) NOT NULL,
  `author_name` char(255) NOT NULL,
  `address` varchar(500) NOT NULL,
  `description` varchar(500) NOT NULL,
  `scope` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `author`
--

INSERT INTO `author` (`id`, `author_name`, `address`, `description`, `scope`) VALUES
(1, 'Carol Hughes', '5309 Aerosmith Rd.', '', ''),
(2, 'Damien Weyas	', '9998 State Park St.', '', ''),
(3, 'Sweet Caroline', '7 Ulysses S. Grant Rd.', '', ''),
(4, 'Damien Weyas	', '7 Ulysses S. Grant Rd.', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2025_05_15_094256_update_ttus_table', 1),
(2, '2025_05_15_102431_create_survivors_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `created_at`, `updated_at`, `name`, `description`) VALUES
(1, '2018-11-01 09:30:00', '2018-11-01 09:30:00', 'Admin', 'Admin'),
(2, '2018-11-01 11:30:00', '2018-11-01 11:30:00', 'Owner', 'Owner'),
(3, NULL, NULL, 'Read/Write', 'Read/Write'),
(4, NULL, NULL, 'Read', 'Read'),
(5, NULL, NULL, 'Write', 'Write');

-- --------------------------------------------------------

--
-- Table structure for table `role_user`
--

CREATE TABLE `role_user` (
  `id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_user`
--

INSERT INTO `role_user` (`id`, `role_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2018-11-01 22:22:00', '2018-11-01 22:23:00'),
(2, 3, 2, '2018-11-01 22:22:00', '2018-11-01 22:23:00'),
(1479, 4, 27, '2025-05-15 19:15:15', '2025-05-15 19:15:15');

-- --------------------------------------------------------

--
-- Table structure for table `survivors`
--

CREATE TABLE `survivors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fema_id` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `hh_size` int(11) DEFAULT NULL,
  `li_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `survivors`
--

INSERT INTO `survivors` (`id`, `fema_id`, `name`, `address`, `phone`, `hh_size`, `li_date`, `created_at`, `updated_at`) VALUES
(2, '123455', 'Dave Kaminsky	', '4831 Wetsone Dr.	', '(123) 456-7890	', 5, '2025-05-13', '2025-05-15 14:30:00', '2025-05-15 14:30:00'),
(3, '190762', 'Charles Xavier', '9998 State Park St.', '(123) 458-2896', 22, '2025-08-05', '2025-05-15 14:59:36', '2025-05-15 14:59:36');

-- --------------------------------------------------------

--
-- Table structure for table `ttus`
--

CREATE TABLE `ttus` (
  `id` int(10) UNSIGNED NOT NULL,
  `vin` varchar(64) DEFAULT NULL,
  `manufacturer` varchar(64) DEFAULT NULL,
  `brand` varchar(64) DEFAULT NULL,
  `model` varchar(64) DEFAULT NULL,
  `year` varchar(16) DEFAULT NULL,
  `status` varchar(64) DEFAULT NULL,
  `title_manufacturer` varchar(64) DEFAULT NULL,
  `title_brand` varchar(64) DEFAULT NULL,
  `title_model` varchar(64) DEFAULT NULL,
  `has_title` varchar(8) DEFAULT NULL,
  `location` varchar(128) DEFAULT NULL,
  `lot` varchar(32) DEFAULT NULL,
  `county` varchar(64) DEFAULT NULL,
  `imei` varchar(64) DEFAULT NULL,
  `price` decimal(12,2) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `total_beds` int(11) DEFAULT NULL,
  `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`features`)),
  `statuses` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`statuses`)),
  `disposition` varchar(64) DEFAULT NULL,
  `transport` varchar(64) DEFAULT NULL,
  `recipient_type` varchar(16) DEFAULT NULL,
  `agency` varchar(128) DEFAULT NULL,
  `category` varchar(128) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `comments` text DEFAULT NULL,
  `fema` varchar(64) DEFAULT NULL,
  `survivor_name` varchar(128) DEFAULT NULL,
  `lo` varchar(8) DEFAULT NULL,
  `lo_date` varchar(32) DEFAULT NULL,
  `est_lo_date` varchar(32) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ttus`
--

INSERT INTO `ttus` (`id`, `vin`, `manufacturer`, `brand`, `model`, `year`, `status`, `title_manufacturer`, `title_brand`, `title_model`, `has_title`, `location`, `lot`, `county`, `imei`, `price`, `address`, `total_beds`, `features`, `statuses`, `disposition`, `transport`, `recipient_type`, `agency`, `category`, `remarks`, `comments`, `fema`, `survivor_name`, `lo`, `lo_date`, `est_lo_date`, `created_at`, `updated_at`) VALUES
(1, '5ZT2CXSBXSM090391', 'Forest River', 'Apex Ultra Lite', NULL, NULL, 'Ready', NULL, NULL, NULL, 'Yes', 'Goldenrod State Park', '12', 'Brexit County', NULL, NULL, '7 Ulysses S. Grant Rd.', 2, '\"{\\\"TV\\\":\\\"on\\\",\\\"Folding Walls\\\":\\\"on\\\"}\"', '\"{\\\"Cleaned\\\":\\\"on\\\"}\"', 'Officially Transferred', 'Select...', 'YES', NULL, NULL, NULL, NULL, NULL, NULL, 'NO', 'N/A', 'N/A', '2025-05-14 15:10:07', '2025-05-16 15:10:07'),
(2, 'G4N2MDSBXM8675309', 'Forest River', 'Apex Ultra Lite', NULL, NULL, 'Occupied', NULL, NULL, NULL, 'Yes', 'Wilson’s RV Park', '243', 'Brexit County', NULL, NULL, '435 California Blvd.', 3, '\"{\\\"TV\\\":\\\"on\\\",\\\"Outdoor Kitchen\\\":\\\"on\\\"}\"', '\"{\\\"Onsite\\\":\\\"on\\\",\\\"Occupied\\\":\\\"on\\\"}\"', 'Officially Transferred', 'Select...', 'YES', NULL, NULL, NULL, NULL, NULL, NULL, 'NO', 'N/A', 'N/A', '2025-05-16 15:09:07', '2025-05-16 15:10:07');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `client_id` varchar(255) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `records_authored` int(11) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `password` varchar(200) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `email_token` varchar(200) DEFAULT NULL,
  `verified` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `client_id`, `status`, `records_authored`, `token`, `password`, `remember_token`, `created_at`, `updated_at`, `email_token`, `verified`) VALUES
(1, 'Dart Admin', 'admin@dart.com', '000', 1, 220, '', '$2y$10$6E8oWaxqgaMrETR/IYtHEegfRejR9fVqg0f/OfISMJFtqyybQpQYu', 'LCVvFvLKNjN941Mf1pdEOiOjMcREUZ54S0y2mT1rDsVfADTpsKUDQUkyNTxo', '2017-10-13 22:59:32', '2019-03-10 13:14:56', NULL, NULL),
(2, 'Dart Inactive', 'inactive@dart.com', '001', 0, 40, '', '$2y$10$6E8oWaxqgaMrETR/IYtHEegfRejR9fVqg0f/OfISMJFtqyybQpQYu', 'LCVvFvLKNjN941Mf1pdEOiOjMcREUZ54S0y2mT1rDsVfADTpsKUDQUkyNTxo', '2017-10-13 22:59:32', '2025-05-15 19:12:10', NULL, NULL),
(27, 'Test Dart', 'test@dart.com', NULL, 1, 0, NULL, '$2y$12$z0Z7HH.NiJVGoZj1m8848u4.Z.XzQCUHvW9I40vf0izFEkuxi890e', NULL, '2025-05-15 19:15:15', '2025-05-15 19:21:03', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `author`
--
ALTER TABLE `author`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `survivors`
--
ALTER TABLE `survivors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ttus`
--
ALTER TABLE `ttus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `author`
--
ALTER TABLE `author`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `role_user`
--
ALTER TABLE `role_user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1480;

--
-- AUTO_INCREMENT for table `survivors`
--
ALTER TABLE `survivors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `ttus`
--
ALTER TABLE `ttus`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
