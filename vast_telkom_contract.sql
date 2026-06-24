-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 24, 2026 at 09:25 AM
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

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `module`, `activity`, `created_at`, `updated_at`) VALUES
(1, 9, 'CONTRACT', 'Created contract 432', '2026-06-23 21:06:45', '2026-06-23 21:06:45'),
(2, 9, 'CONTRACT', 'Created contract 4', '2026-06-23 21:24:27', '2026-06-23 21:24:27'),
(3, 8, 'AUTH', 'Login', '2026-06-23 23:49:59', '2026-06-23 23:49:59');

-- --------------------------------------------------------

--
-- Table structure for table `baso_files`
--

CREATE TABLE `baso_files` (
  `id` bigint NOT NULL,
  `contract_id` bigint NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `baso_date` date DEFAULT NULL,
  `uploaded_by` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `baso_files`
--

INSERT INTO `baso_files` (`id`, `contract_id`, `file_name`, `file_path`, `baso_date`, `uploaded_by`, `created_at`, `updated_at`) VALUES
(1, 20, 'CONTRACT-15.docx', 'baso/glcx9eKIqHscOt5Le7gHERJSSrq9hbAZEtglxJOV.docx', '2026-06-24', 9, '2026-06-23 21:24:27', '2026-06-23 21:24:27');

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
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `status` enum('active','expiring','followup','expired','terminated') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_by` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `account_number` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sid` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telkom_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telkom_position` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telkom_unit` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `customer_address` text COLLATE utf8mb4_general_ci,
  `customer_npwp` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `customer_pic_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `customer_pic_position` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `customer_phone` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `customer_email` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `signing_date` date DEFAULT NULL,
  `signing_location` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contracts`
--

INSERT INTO `contracts` (`id`, `contract_number`, `contract_name`, `owner_am_id`, `start_date`, `end_date`, `status`, `created_by`, `created_at`, `updated_at`, `account_number`, `sid`, `telkom_name`, `telkom_position`, `telkom_unit`, `customer_address`, `customer_npwp`, `customer_pic_name`, `customer_pic_position`, `customer_phone`, `customer_email`, `signing_date`, `signing_location`) VALUES
(5, '1', 'a', 9, '2026-06-20', '2026-06-27', 'expired', 8, '2026-06-20 01:21:56', '2026-06-20 03:09:41', 'a', NULL, 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a@gmail.com', '2026-06-20', 'a'),
(6, '12', 'a', 9, '2026-06-20', '2026-06-27', 'followup', 8, '2026-06-20 01:23:29', '2026-06-22 19:07:53', 'a', NULL, 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a@gmail.com', '2026-06-20', 'a'),
(7, '2', 'b', 9, '2026-06-22', '2026-07-22', 'expiring', 8, '2026-06-21 19:06:39', '2026-06-22 19:46:37', '1', NULL, 'b', 'b', 'b', 'b', 'b', 'b', 'b', '1', 'b@gmail.com', '2026-06-23', 'b'),
(8, '67', 'p', 10, '2026-06-19', '2026-06-21', 'expired', 8, '2026-06-21 21:08:07', '2026-06-21 21:08:07', '17', NULL, 'p', 'p', 'p', 'p', 'p', 'p', 'p', '8', 'p@gmail.com', '2026-06-20', 'p'),
(16, '567', 'cccc', 11, '2026-06-22', '2026-07-22', 'expiring', 8, '2026-06-22 02:08:08', '2026-06-22 02:08:08', '1234', NULL, 'k', 'kk', 'kkk', 'p0', '7777', 'n', 'nnn', '000', 'aaaa@gmail.com', '2026-06-22', 'sana'),
(17, '1234', 'r', 11, '2026-06-22', '2026-07-22', 'expiring', 8, '2026-06-22 02:25:54', '2026-06-22 02:25:54', '123', NULL, 'q', 'w', 'e', 't', 'y', 'u', 'i', '0', 'o@gmail.com', '2026-06-22', 'd'),
(18, '123', 'def', 9, '2026-06-23', '2026-06-27', 'followup', 8, '2026-06-22 18:44:54', '2026-06-22 18:45:37', 'a', NULL, 'PT Telkom', 'b', 'A', 'a', '123', 's', 'b', '0987', 'silvia@gmail.com', '2026-06-23', 'a'),
(20, '4', 'q', 9, '2026-06-24', '2026-12-24', 'active', 9, '2026-06-23 21:24:26', '2026-06-23 21:24:26', NULL, NULL, 'q', 'q', 'q', 'q', 'q', 'q', 'q', 'q', 'q@gmail.com', '2026-06-24', 'q');

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

--
-- Dumping data for table `contract_files`
--

INSERT INTO `contract_files` (`id`, `contract_id`, `file_name`, `file_path`, `uploaded_by`, `created_at`, `updated_at`) VALUES
(4, 16, 'CONTRACT-16.docx', 'contracts/CONTRACT-16.docx', 8, '2026-06-22 02:08:08', '2026-06-22 02:08:08'),
(5, 17, 'CONTRACT-17.docx', 'contracts/CONTRACT-17.docx', 8, '2026-06-22 02:25:54', '2026-06-22 02:25:54'),
(6, 18, 'CONTRACT-18.docx', 'contracts/CONTRACT-18.docx', 8, '2026-06-22 18:44:55', '2026-06-22 18:44:55'),
(7, 20, 'KFS_PT_IFORTE_SOLUSI_INFOTEK.docx', 'contracts/J8GY4L9QXAbwU52NA1ArcU9RCXb2xNUBaObGxkno.docx', 9, '2026-06-23 21:24:27', '2026-06-23 21:24:27');

-- --------------------------------------------------------

--
-- Table structure for table `contract_services`
--

CREATE TABLE `contract_services` (
  `id` bigint NOT NULL,
  `contract_id` bigint NOT NULL,
  `service_id` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `installation_fee` decimal(15,2) DEFAULT '0.00',
  `monthly_fee` decimal(15,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `contract_services`
--

INSERT INTO `contract_services` (`id`, `contract_id`, `service_id`, `created_at`, `updated_at`, `installation_fee`, `monthly_fee`) VALUES
(1, 1, 3, '2026-06-20 01:12:36', '2026-06-20 01:12:36', 0.00, 0.00),
(2, 2, 3, '2026-06-20 01:17:56', '2026-06-20 01:17:56', 0.00, 0.00),
(3, 3, 3, '2026-06-20 01:20:13', '2026-06-20 01:20:13', 0.00, 0.00),
(4, 4, 3, '2026-06-20 01:21:45', '2026-06-20 01:21:45', 0.00, 0.00),
(15, 5, 3, '2026-06-20 03:09:41', '2026-06-20 03:09:41', 0.00, 0.00),
(16, 5, 1, '2026-06-20 03:09:41', '2026-06-20 03:09:41', 0.00, 0.00),
(21, 8, 2, '2026-06-21 21:08:07', '2026-06-21 21:08:07', 0.00, 0.00),
(22, 14, 8, '2026-06-22 01:44:09', '2026-06-22 01:44:09', 0.00, 0.00),
(23, 14, 1, '2026-06-22 01:44:09', '2026-06-22 01:44:09', 0.00, 0.00),
(24, 15, 7, '2026-06-22 01:48:43', '2026-06-22 01:48:43', 0.00, 0.00),
(25, 15, 2, '2026-06-22 01:48:43', '2026-06-22 01:48:43', 0.00, 0.00),
(26, 16, 7, '2026-06-22 02:08:08', '2026-06-22 02:08:08', 0.00, 0.00),
(27, 17, 2, '2026-06-22 02:25:54', '2026-06-22 02:25:54', 0.00, 0.00),
(28, 17, 6, '2026-06-22 02:25:54', '2026-06-22 02:25:54', 0.00, 0.00),
(31, 18, 6, '2026-06-22 18:45:37', '2026-06-22 18:45:37', 0.00, 0.00),
(32, 18, 5, '2026-06-22 18:45:37', '2026-06-22 18:45:37', 0.00, 0.00),
(33, 6, 3, '2026-06-22 19:07:53', '2026-06-22 19:07:53', 0.00, 0.00),
(34, 7, 3, '2026-06-22 19:46:37', '2026-06-22 19:46:37', 0.00, 0.00),
(35, 19, 2, '2026-06-23 21:06:45', '2026-06-23 21:06:45', 0.00, 0.00),
(36, 19, 2, '2026-06-23 21:06:45', '2026-06-23 21:06:45', 0.00, 0.00),
(37, 20, 4, '2026-06-23 21:24:26', '2026-06-23 21:24:26', 0.00, 0.00),
(38, 20, 8, '2026-06-23 21:24:26', '2026-06-23 21:24:26', 0.00, 0.00);

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
  `manager_email` varchar(255) DEFAULT NULL,
  `daily_schedule` varchar(20) DEFAULT '08:00',
  `weekly_schedule` varchar(50) DEFAULT 'monday_morning',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `notification_settings`
--

INSERT INTO `notification_settings` (`id`, `manager_email`, `daily_schedule`, `weekly_schedule`, `created_at`, `updated_at`) VALUES
(1, 'manager@perusahaan.com', '10:00', 'friday_morning', '2026-06-24 00:14:22', '2026-06-24 01:04:48');

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
('5rywhZRgIUBb4qxiOyQpqU8GpyLdmwH3Mmrb8K9g', 8, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJqZDcxNFhCdjB3TTB3ZGY0TkM1SUpmVEI2MzlZZm9UekdnUlNHVGJxIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2Rhc2hib2FyZCIsInJvdXRlIjoiZGFzaGJvYXJkIn0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjo4fQ==', 1782274714),
('Hvjg4P3Na1gJDUPFafUPI07gLAYNP0CfD2kDNrRX', 8, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJqZHBFRmF2SFpXMVpFY09HMzh0SlNvR29UNENFRVBvZWpKS2VtS1MyIiwidXJsIjpbXSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9lbWFpbC1ub3RpZmljYXRpb25zIiwicm91dGUiOiJlbWFpbC5ub3RpZmljYXRpb25zIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjh9', 1782288529),
('kFunsmrJdxxKhbezEoa5p3Toj9SKotkCBkS5YGfi', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJFT1hGNUJXcXdGb0VPTWs2bTh2SjRNejVXU3lnSlYwUjI5b2NiRFhyIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2VtYWlsLW5vdGlmaWNhdGlvbnMifSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1782283785),
('z98HMCQ7S1ubLEtXUauFae1V4spqlj2f3acDnJtD', 9, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJ4bzdYWmdzc3pTUHZkUlNuaWM2eEJJS1BWWHkxQUI4SHNtQWo0ckxmIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9iYXNvXC8xXC9kb3dubG9hZCIsInJvdXRlIjoiYmFzby5kb3dubG9hZCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjo5fQ==', 1782275593);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint NOT NULL,
  `role_id` bigint NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `profile_photo` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_general_ci DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `name`, `email`, `profile_photo`, `password`, `status`, `created_at`, `updated_at`) VALUES
(8, 1, 'Manager', 'manager@telkom.com', 'profile-photos/mnJytVRAeCb5YaXJ9JYQzo2FpCLxcb7f82D7YT4S.png', '$2y$12$x/jKf20Lo8Lck9FpPjqnJO9KV5TGrui//UDH/.vV.tf.o2PjibgiK', 'active', '2026-06-17 02:06:36', '2026-06-23 00:28:09'),
(9, 2, 'Account Manager 1', 'AM1@telkom.com', NULL, '$2y$12$SdYtglKdH0H5ADuf16lEyeAsLgY2Sl8hOnR45qEcwWuSYJnc.fuH2', 'active', '2026-06-17 02:06:37', '2026-06-17 02:06:37'),
(10, 2, 'Account Manager 2', 'AM2@telkom.com', NULL, '$2y$12$fwFUKh.sSsn6NmyyDsB/VujtpY7OtiH5koMosRT.qVfEePZM91ZrS', 'active', '2026-06-17 02:06:37', '2026-06-17 02:06:37'),
(11, 2, 'Account Manager 3', 'AM3@telkom.com', NULL, '$2y$12$Mc2XZmFr0wOm4em/ZpaRdu7x9MJuMeGFC7Wn0pCZrg00JNWy2VlIC', 'active', '2026-06-17 02:06:37', '2026-06-17 02:06:37'),
(12, 3, 'Support Inputter', 'inputter@telkom.com', NULL, '$2y$12$TZF/JYTfXlKEvJgbEbBIseOykjzXGu218.YTj5Q49nhhOctlvZcSy', 'active', '2026-06-17 02:06:37', '2026-06-17 02:06:37'),
(13, 4, 'Support Paycall', 'paycall@telkom.com', NULL, '$2y$12$QbRQEiTmdgaDOQUK7pB/kOq4c2bvA20TCe/KqEEjQq1INMuGq8LPW', 'active', '2026-06-17 02:06:38', '2026-06-17 02:06:38');

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
-- Indexes for table `baso_files`
--
ALTER TABLE `baso_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `baso_files_contract_id_fk` (`contract_id`),
  ADD KEY `baso_files_uploaded_by_fk` (`uploaded_by`);

--
-- Indexes for table `billings`
--
ALTER TABLE `billings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contract_id` (`contract_id`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

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
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `baso_files`
--
ALTER TABLE `baso_files`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `billings`
--
ALTER TABLE `billings`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contracts`
--
ALTER TABLE `contracts`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `contract_extensions`
--
ALTER TABLE `contract_extensions`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contract_files`
--
ALTER TABLE `contract_files`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `contract_services`
--
ALTER TABLE `contract_services`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

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
-- Constraints for table `baso_files`
--
ALTER TABLE `baso_files`
  ADD CONSTRAINT `baso_files_contract_id_fk` FOREIGN KEY (`contract_id`) REFERENCES `contracts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `baso_files_uploaded_by_fk` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

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
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
