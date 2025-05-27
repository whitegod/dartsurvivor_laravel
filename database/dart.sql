-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 27, 2025 at 03:12 AM
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
-- Table structure for table `caseworker`
--

CREATE TABLE `caseworker` (
  `id` int(11) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lanme` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hotel`
--

CREATE TABLE `hotel` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` char(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hotel`
--

INSERT INTO `hotel` (`id`, `name`, `address`, `phone`) VALUES
(1, 'kings hotel', 'address dummy', '123123141'),
(2, 'queen\'s hotel', 'dsafdsafdsafds fdf', '432143456');

-- --------------------------------------------------------

--
-- Table structure for table `lodge_unit`
--

CREATE TABLE `lodge_unit` (
  `id` int(11) NOT NULL,
  `unit_name` varchar(20) NOT NULL,
  `survivor_id` int(11) DEFAULT NULL,
  `statepark_id` int(11) NOT NULL,
  `li_date` date DEFAULT NULL,
  `lo_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lodge_unit`
--

INSERT INTO `lodge_unit` (`id`, `unit_name`, `survivor_id`, `statepark_id`, `li_date`, `lo_date`) VALUES
(1, 'lot 12', 3, 1, '2025-05-27', '2025-05-30'),
(2, 'lot ABC', NULL, 2, NULL, NULL);

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
(2, '2018-11-01 11:30:00', '2018-11-01 11:30:00', 'User', 'User');

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
(1, 1, 1, '2018-11-01 22:22:00', '2018-11-01 22:23:00');

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `id` int(11) NOT NULL,
  `room_num` int(11) NOT NULL,
  `survivor_id` int(11) DEFAULT NULL,
  `hotel_id` int(11) NOT NULL,
  `li_date` date DEFAULT NULL,
  `lo_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`id`, `room_num`, `survivor_id`, `hotel_id`, `li_date`, `lo_date`) VALUES
(1, 101, 2, 1, '2025-05-27', '2025-05-31'),
(2, 202, NULL, 1, NULL, NULL),
(3, 1102, NULL, 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `statepark`
--

CREATE TABLE `statepark` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `statepark`
--

INSERT INTO `statepark` (`id`, `name`, `address`, `phone`) VALUES
(1, 'statepark1', 'adrerehrerh', ''),
(2, 'test park 3', 'dsfaeererwerew', '214321414');

-- --------------------------------------------------------

--
-- Table structure for table `survivor`
--

CREATE TABLE `survivor` (
  `id` int(10) UNSIGNED NOT NULL,
  `fema_id` varchar(64) DEFAULT NULL,
  `fname` varchar(100) DEFAULT NULL,
  `lname` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` char(255) DEFAULT NULL,
  `state` char(5) DEFAULT NULL,
  `zip` char(10) DEFAULT NULL,
  `county` char(255) DEFAULT NULL,
  `primary_phone` varchar(32) DEFAULT NULL,
  `secondary_phone` varchar(32) DEFAULT NULL,
  `hh_size` int(11) DEFAULT NULL,
  `pets` int(11) DEFAULT NULL,
  `email` char(50) DEFAULT NULL,
  `li_date` date DEFAULT NULL,
  `own_rent` tinyint(1) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `location_type` varchar(32) DEFAULT NULL,
  `opt_out` tinyint(4) DEFAULT NULL,
  `opt_out_reason` char(30) DEFAULT NULL,
  `caseworker_id` int(11) DEFAULT NULL,
  `notes` varchar(3000) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `survivor`
--

INSERT INTO `survivor` (`id`, `fema_id`, `fname`, `lname`, `address`, `city`, `state`, `zip`, `county`, `primary_phone`, `secondary_phone`, `hh_size`, `pets`, `email`, `li_date`, `own_rent`, `author`, `location_type`, `opt_out`, `opt_out_reason`, `caseworker_id`, `notes`, `created_at`, `updated_at`) VALUES
(1, '364783292634', 'test', 'dfsd', NULL, NULL, NULL, NULL, NULL, '(890) 903-5768', '(890) 903-2344', 3, 1, NULL, NULL, 0, 'Dart Admin', 'TTU', 0, 'N/A', NULL, NULL, '2025-05-26 15:29:38', '2025-05-26 15:32:27'),
(2, '343256765', 'dd', 'fdge', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'Dart Admin', 'Hotel', 0, 'N/A', NULL, NULL, '2025-05-26 15:43:18', '2025-05-26 16:09:15'),
(3, '343256243', 'adsa', 'fewfw', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 1, NULL, NULL, 0, 'Dart Admin', 'State Park', 0, 'Personal', NULL, NULL, '2025-05-26 15:43:36', '2025-05-26 17:45:50');

-- --------------------------------------------------------

--
-- Table structure for table `ttu`
--

CREATE TABLE `ttu` (
  `id` int(10) UNSIGNED NOT NULL,
  `survivor_id` int(11) DEFAULT NULL,
  `vin` varchar(64) DEFAULT NULL,
  `manufacturer` varchar(100) DEFAULT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `model` varchar(100) DEFAULT NULL,
  `year` varchar(10) DEFAULT NULL,
  `status` varchar(32) DEFAULT NULL,
  `title_manufacturer` varchar(100) DEFAULT NULL,
  `title_brand` varchar(100) DEFAULT NULL,
  `title_model` varchar(100) DEFAULT NULL,
  `has_title` varchar(10) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `unit` varchar(32) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `county` varchar(100) DEFAULT NULL,
  `imei` varchar(64) DEFAULT NULL,
  `purchase_price` decimal(10,2) DEFAULT NULL,
  `total_beds` int(11) DEFAULT NULL,
  `transpo_agency` varchar(100) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `comments` text DEFAULT NULL,
  `fdec` varchar(32) DEFAULT NULL,
  `lo` varchar(10) DEFAULT NULL,
  `lo_date` date DEFAULT NULL,
  `est_lo_date` date DEFAULT NULL,
  `has_200sqft` tinyint(1) DEFAULT 0,
  `has_propanefire` tinyint(1) DEFAULT 0,
  `has_tv` tinyint(1) DEFAULT 0,
  `has_hydraul` tinyint(1) DEFAULT 0,
  `has_steps` tinyint(1) DEFAULT 0,
  `has_teardrop` tinyint(1) DEFAULT 0,
  `has_foldwalls` tinyint(1) DEFAULT 0,
  `has_extkitchen` tinyint(1) DEFAULT 0,
  `is_onsite` tinyint(1) DEFAULT 0,
  `is_occupied` tinyint(1) DEFAULT 0,
  `is_winterized` tinyint(1) DEFAULT 0,
  `is_deblocked` tinyint(1) DEFAULT 0,
  `is_cleaned` tinyint(1) DEFAULT 0,
  `is_gps_removed` tinyint(1) DEFAULT 0,
  `is_being_donated` tinyint(1) DEFAULT 0,
  `is_sold_at_auction` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ttu`
--

INSERT INTO `ttu` (`id`, `survivor_id`, `vin`, `manufacturer`, `brand`, `model`, `year`, `status`, `title_manufacturer`, `title_brand`, `title_model`, `has_title`, `location`, `unit`, `address`, `county`, `imei`, `purchase_price`, `total_beds`, `transpo_agency`, `remarks`, `comments`, `fdec`, `lo`, `lo_date`, `est_lo_date`, `has_200sqft`, `has_propanefire`, `has_tv`, `has_hydraul`, `has_steps`, `has_teardrop`, `has_foldwalls`, `has_extkitchen`, `is_onsite`, `is_occupied`, `is_winterized`, `is_deblocked`, `is_cleaned`, `is_gps_removed`, `is_being_donated`, `is_sold_at_auction`, `created_at`, `updated_at`) VALUES
(1, NULL, '5ZT2CXSBXSM090391', 'Forest River', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2, 1, '3SD2CXSBXSM046291', 'Forest River', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '2025-05-27', '2025-05-29', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(3, 3, '3SD2CXBNMSM043391', 'Forest River', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ttulocation`
--

CREATE TABLE `ttulocation` (
  `id` int(11) NOT NULL,
  `ttu_id` int(11) NOT NULL,
  `loc_name` varchar(50) NOT NULL,
  `loc_address` varchar(255) NOT NULL,
  `loc_phone` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `verified` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `client_id`, `status`, `token`, `password`, `remember_token`, `admin_id`, `payroll_id`, `worker_id`, `contact_id`, `account_manager_id`, `created_at`, `updated_at`, `email_token`, `verified`) VALUES
(1, 'Dart Admin', 'admin@dart.com', '000', 1, '', '$2y$10$6E8oWaxqgaMrETR/IYtHEegfRejR9fVqg0f/OfISMJFtqyybQpQYu', 'LCVvFvLKNjN941Mf1pdEOiOjMcREUZ54S0y2mT1rDsVfADTpsKUDQUkyNTxo', NULL, NULL, NULL, NULL, NULL, '2017-10-13 22:59:32', '2019-03-10 13:14:56', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `caseworker`
--
ALTER TABLE `caseworker`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hotel`
--
ALTER TABLE `hotel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lodge_unit`
--
ALTER TABLE `lodge_unit`
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
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `statepark`
--
ALTER TABLE `statepark`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `survivor`
--
ALTER TABLE `survivor`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ttu`
--
ALTER TABLE `ttu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ttulocation`
--
ALTER TABLE `ttulocation`
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
-- AUTO_INCREMENT for table `caseworker`
--
ALTER TABLE `caseworker`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hotel`
--
ALTER TABLE `hotel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `lodge_unit`
--
ALTER TABLE `lodge_unit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `role_user`
--
ALTER TABLE `role_user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1476;

--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `statepark`
--
ALTER TABLE `statepark`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `survivor`
--
ALTER TABLE `survivor`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ttu`
--
ALTER TABLE `ttu`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ttulocation`
--
ALTER TABLE `ttulocation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
