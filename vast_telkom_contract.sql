-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 20, 2026 at 10:43 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vast_telkom_contract`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `module` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `activity` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `billings`
--

CREATE TABLE `billings` (
  `id` bigint NOT NULL,
  `contract_id` bigint NOT NULL,
  `billing_period` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `payment_status` enum('pending','paid','overdue') COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `payment_date` date DEFAULT NULL,
  `proof_file` text COLLATE utf8mb4_general_ci,
  `updated_by` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contracts`
--

CREATE TABLE `contracts` (
  `id` bigint NOT NULL,
  `contract_number` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `contract_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `owner_am_id` bigint NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('active','expired','terminated') COLLATE utf8mb4_general_ci DEFAULT 'active',
  `created_by` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `customer_id_number` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telkom_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telkom_position` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telkom_unit` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `customer_address` text COLLATE utf8mb4_general_ci,
  `customer_npwp` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `customer_pic_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `customer_pic_position` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `customer_phone` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `customer_email` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `generated_file` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `signing_date` date DEFAULT NULL,
  `signing_location` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contracts`
--

INSERT INTO `contracts` (`id`, `contract_number`, `contract_name`, `owner_am_id`, `start_date`, `end_date`, `status`, `created_by`, `created_at`, `updated_at`, `customer_id_number`, `telkom_name`, `telkom_position`, `telkom_unit`, `customer_address`, `customer_npwp`, `customer_pic_name`, `customer_pic_position`, `customer_phone`, `customer_email`, `generated_file`, `signing_date`, `signing_location`) VALUES
(5, '1', 'a', 9, '2026-06-20', '2026-06-27', 'expired', 8, '2026-06-20 01:21:56', '2026-06-20 03:09:41', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a@gmail.com', NULL, '2026-06-20', 'a'),
(6, '12', 'a', 9, '2026-06-20', '2026-06-27', 'active', 8, '2026-06-20 01:23:29', '2026-06-20 01:23:29', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a@gmail.com', NULL, '2026-06-20', 'a');

-- --------------------------------------------------------

--
-- Table structure for table `contract_extensions`
--

CREATE TABLE `contract_extensions` (
  `id` bigint NOT NULL,
  `contract_id` bigint NOT NULL,
  `old_end_date` date NOT NULL,
  `new_end_date` date NOT NULL,
  `reason` text COLLATE utf8mb4_general_ci,
  `updated_by` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contract_files`
--

CREATE TABLE `contract_files` (
  `id` bigint NOT NULL,
  `contract_id` bigint NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `file_path` text COLLATE utf8mb4_general_ci NOT NULL,
  `uploaded_by` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contract_services`
--

CREATE TABLE `contract_services` (
  `id` bigint NOT NULL,
  `contract_id` bigint NOT NULL,
  `service_id` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `contract_services`
--

INSERT INTO `contract_services` (`id`, `contract_id`, `service_id`, `created_at`, `updated_at`) VALUES
(1, 1, 3, '2026-06-20 01:12:36', '2026-06-20 01:12:36'),
(2, 2, 3, '2026-06-20 01:17:56', '2026-06-20 01:17:56'),
(3, 3, 3, '2026-06-20 01:20:13', '2026-06-20 01:20:13'),
(4, 4, 3, '2026-06-20 01:21:45', '2026-06-20 01:21:45'),
(6, 6, 3, '2026-06-20 01:23:29', '2026-06-20 01:23:29'),
(15, 5, 3, '2026-06-20 03:09:41', '2026-06-20 03:09:41'),
(16, 5, 1, '2026-06-20 03:09:41', '2026-06-20 03:09:41');

-- --------------------------------------------------------

--
-- Table structure for table `contract_transfer_history`
--

CREATE TABLE `contract_transfer_history` (
  `id` bigint NOT NULL,
  `contract_id` bigint NOT NULL,
  `from_am_id` bigint DEFAULT NULL,
  `to_am_id` bigint DEFAULT NULL,
  `transferred_by` bigint NOT NULL,
  `notes` text COLLATE utf8mb4_general_ci,
  `transfer_date` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contract_transfer_requests`
--

CREATE TABLE `contract_transfer_requests` (
  `id` bigint NOT NULL,
  `contract_id` bigint NOT NULL,
  `requested_by` bigint NOT NULL,
  `current_am_id` bigint NOT NULL,
  `target_am_id` bigint NOT NULL,
  `reason` text COLLATE utf8mb4_general_ci,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `approved_by` bigint DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `id` bigint NOT NULL,
  `contract_id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `note` text COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_general_ci,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification_settings`
--

CREATE TABLE `notification_settings` (
  `id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `notification_email` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `notification_settings`
--

INSERT INTO `notification_settings` (`id`, `user_id`, `notification_email`, `created_at`, `updated_at`) VALUES
(1, 8, 'manager@perusahaan.com', '2026-06-18 00:09:09', '2026-06-18 00:09:09');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Manager', NULL, NULL),
(2, 'Account Manager', NULL, NULL),
(3, 'Support Inputter', NULL, NULL),
(4, 'Support Paycall', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `installation_fee` decimal(18,2) NOT NULL DEFAULT '0.00',
  `monthly_fee` decimal(18,2) NOT NULL DEFAULT '0.00',
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `service_name`, `installation_fee`, `monthly_fee`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Metro Ethernet', 5000000.00, 2500000.00, 'active', '2026-06-20 07:44:26', '2026-06-20 07:44:26'),
(2, 'IP Transit', 3000000.00, 1500000.00, 'active', '2026-06-20 07:44:26', '2026-06-20 07:44:26'),
(3, 'Astinet', 2500000.00, 1200000.00, 'active', '2026-06-20 07:44:26', '2026-06-20 07:44:26'),
(4, 'Managed Service', 4000000.00, 2000000.00, 'active', '2026-06-20 07:44:26', '2026-06-20 07:44:26'),
(5, 'SD-WAN', 6000000.00, 3500000.00, 'active', '2026-06-20 07:44:26', '2026-06-20 07:44:26'),
(6, 'Cloud Connect', 4500000.00, 2200000.00, 'active', '2026-06-20 07:44:26', '2026-06-20 07:44:26'),
(7, 'Data Center Service', 8000000.00, 5000000.00, 'active', '2026-06-20 07:44:26', '2026-06-20 07:44:26'),
(8, 'Internet Dedicated', 3500000.00, 1800000.00, 'active', '2026-06-20 07:44:26', '2026-06-20 07:44:26');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `payload` longtext NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('ha1HkM468IDUV6KsVO36StNPqG4X5ex7bae8WLtp', 8, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJDaHN0TTlQTnpOMUg4bVNqYUZZMlVmUWZHeThZdlNLRExrWmRYS0hPIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9jb250cmFjdC1saXN0Iiwicm91dGUiOiJjb250cmFjdC5saXN0In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjh9', 1781951177),
('XeIG7K65vmxgKYYMlhHeM2uyFJX3MLCrZGt4XIpl', 13, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJYNUVoWm9hSHlVZks2OEJscTZxYWlYU0lQZW1WOTlDbHFOMERFVUoxIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9jb250cmFjdHNcLzYiLCJyb3V0ZSI6ImNvbnRyYWN0cy5zaG93In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjEzfQ==', 1781950209);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint NOT NULL,
  `role_id` bigint NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_general_ci DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `name`, `email`, `password`, `status`, `created_at`, `updated_at`) VALUES
(8, 1, 'Manager', 'manager@telkom.com', '$2y$12$x/jKf20Lo8Lck9FpPjqnJO9KV5TGrui//UDH/.vV.tf.o2PjibgiK', 'active', '2026-06-17 02:06:36', '2026-06-17 02:06:36'),
(9, 2, 'Account Manager 1', 'AM1@telkom.com', '$2y$12$SdYtglKdH0H5ADuf16lEyeAsLgY2Sl8hOnR45qEcwWuSYJnc.fuH2', 'active', '2026-06-17 02:06:37', '2026-06-17 02:06:37'),
(10, 2, 'Account Manager 2', 'AM2@telkom.com', '$2y$12$fwFUKh.sSsn6NmyyDsB/VujtpY7OtiH5koMosRT.qVfEePZM91ZrS', 'active', '2026-06-17 02:06:37', '2026-06-17 02:06:37'),
(11, 2, 'Account Manager 3', 'AM3@telkom.com', '$2y$12$Mc2XZmFr0wOm4em/ZpaRdu7x9MJuMeGFC7Wn0pCZrg00JNWy2VlIC', 'active', '2026-06-17 02:06:37', '2026-06-17 02:06:37'),
(12, 3, 'Support Inputter', 'inputter@telkom.com', '$2y$12$TZF/JYTfXlKEvJgbEbBIseOykjzXGu218.YTj5Q49nhhOctlvZcSy', 'active', '2026-06-17 02:06:37', '2026-06-17 02:06:37'),
(13, 4, 'Support Paycall', 'paycall@telkom.com', '$2y$12$QbRQEiTmdgaDOQUK7pB/kOq4c2bvA20TCe/KqEEjQq1INMuGq8LPW', 'active', '2026-06-17 02:06:38', '2026-06-17 02:06:38');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `billings`
--
ALTER TABLE `billings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contract_id` (`contract_id`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `contracts`
--
ALTER TABLE `contracts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `contract_number` (`contract_number`),
  ADD KEY `owner_am_id` (`owner_am_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `contract_extensions`
--
ALTER TABLE `contract_extensions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contract_id` (`contract_id`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `contract_files`
--
ALTER TABLE `contract_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contract_id` (`contract_id`),
  ADD KEY `uploaded_by` (`uploaded_by`);

--
-- Indexes for table `contract_services`
--
ALTER TABLE `contract_services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contract_transfer_history`
--
ALTER TABLE `contract_transfer_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contract_id` (`contract_id`),
  ADD KEY `from_am_id` (`from_am_id`),
  ADD KEY `to_am_id` (`to_am_id`),
  ADD KEY `transferred_by` (`transferred_by`);

--
-- Indexes for table `contract_transfer_requests`
--
ALTER TABLE `contract_transfer_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contract_id` (`contract_id`),
  ADD KEY `requested_by` (`requested_by`),
  ADD KEY `current_am_id` (`current_am_id`),
  ADD KEY `target_am_id` (`target_am_id`),
  ADD KEY `approved_by` (`approved_by`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contract_id` (`contract_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `notification_settings`
--
ALTER TABLE `notification_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `billings`
--
ALTER TABLE `billings`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contracts`
--
ALTER TABLE `contracts`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `contract_extensions`
--
ALTER TABLE `contract_extensions`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contract_files`
--
ALTER TABLE `contract_files`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contract_services`
--
ALTER TABLE `contract_services`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `contract_transfer_history`
--
ALTER TABLE `contract_transfer_history`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contract_transfer_requests`
--
ALTER TABLE `contract_transfer_requests`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification_settings`
--
ALTER TABLE `notification_settings`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `billings`
--
ALTER TABLE `billings`
  ADD CONSTRAINT `billings_ibfk_1` FOREIGN KEY (`contract_id`) REFERENCES `contracts` (`id`),
  ADD CONSTRAINT `billings_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `contracts`
--
ALTER TABLE `contracts`
  ADD CONSTRAINT `contracts_ibfk_1` FOREIGN KEY (`owner_am_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `contracts_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `contract_extensions`
--
ALTER TABLE `contract_extensions`
  ADD CONSTRAINT `contract_extensions_ibfk_1` FOREIGN KEY (`contract_id`) REFERENCES `contracts` (`id`),
  ADD CONSTRAINT `contract_extensions_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `contract_files`
--
ALTER TABLE `contract_files`
  ADD CONSTRAINT `contract_files_ibfk_1` FOREIGN KEY (`contract_id`) REFERENCES `contracts` (`id`),
  ADD CONSTRAINT `contract_files_ibfk_2` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `contract_transfer_history`
--
ALTER TABLE `contract_transfer_history`
  ADD CONSTRAINT `contract_transfer_history_ibfk_1` FOREIGN KEY (`contract_id`) REFERENCES `contracts` (`id`),
  ADD CONSTRAINT `contract_transfer_history_ibfk_2` FOREIGN KEY (`from_am_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `contract_transfer_history_ibfk_3` FOREIGN KEY (`to_am_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `contract_transfer_history_ibfk_4` FOREIGN KEY (`transferred_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `contract_transfer_requests`
--
ALTER TABLE `contract_transfer_requests`
  ADD CONSTRAINT `contract_transfer_requests_ibfk_1` FOREIGN KEY (`contract_id`) REFERENCES `contracts` (`id`),
  ADD CONSTRAINT `contract_transfer_requests_ibfk_2` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `contract_transfer_requests_ibfk_3` FOREIGN KEY (`current_am_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `contract_transfer_requests_ibfk_4` FOREIGN KEY (`target_am_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `contract_transfer_requests_ibfk_5` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `notes_ibfk_1` FOREIGN KEY (`contract_id`) REFERENCES `contracts` (`id`),
  ADD CONSTRAINT `notes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `notification_settings`
--
ALTER TABLE `notification_settings`
  ADD CONSTRAINT `fk_notification_settings_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
