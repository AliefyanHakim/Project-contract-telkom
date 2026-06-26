-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 26, 2026 at 01:19 AM
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
  `id` bigint UNSIGNED NOT NULL,
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
(1, 8, 'AUTH', 'Login', '2026-06-25 18:18:40', '2026-06-25 18:18:40');

-- --------------------------------------------------------

--
-- Table structure for table `baso_files`
--

CREATE TABLE `baso_files` (
  `id` bigint UNSIGNED NOT NULL,
  `contract_id` bigint UNSIGNED NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `baso_date` date DEFAULT NULL,
  `uploaded_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `billings`
--

CREATE TABLE `billings` (
  `id` bigint UNSIGNED NOT NULL,
  `contract_id` bigint NOT NULL,
  `billing_period` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `payment_status` enum('pending','paid','overdue') COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `payment_date` date DEFAULT NULL,
  `proof_file` text COLLATE utf8mb4_general_ci,
  `updated_by` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `billings`
--

INSERT INTO `billings` (`id`, `contract_id`, `billing_period`, `due_date`, `amount`, `payment_status`, `payment_date`, `proof_file`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-06', '2026-06-20', 2200000.00, 'pending', NULL, NULL, NULL, '2026-06-25 06:58:48', '2026-06-25 06:58:48'),
(2, 1, '2026-07', '2026-07-20', 2200000.00, 'pending', NULL, NULL, NULL, '2026-06-25 06:58:48', '2026-06-25 06:58:48'),
(3, 2, '2026-06', '2026-06-20', 1800000.00, 'pending', NULL, NULL, NULL, '2026-06-25 06:59:52', '2026-06-25 06:59:52'),
(4, 2, '2026-07', '2026-07-20', 1800000.00, 'pending', NULL, NULL, NULL, '2026-06-25 06:59:52', '2026-06-25 06:59:52'),
(5, 3, '2026-06', '2026-06-20', 2200000.00, 'pending', NULL, NULL, NULL, '2026-06-25 07:01:40', '2026-06-25 07:01:40'),
(6, 3, '2026-07', '2026-07-20', 2200000.00, 'pending', NULL, NULL, NULL, '2026-06-25 07:01:40', '2026-06-25 07:01:40'),
(7, 4, '2026-06', '2026-06-20', 3500000.00, 'pending', NULL, NULL, NULL, '2026-06-25 07:07:19', '2026-06-25 07:07:19'),
(8, 4, '2026-07', '2026-07-20', 3500000.00, 'pending', NULL, NULL, NULL, '2026-06-25 07:07:19', '2026-06-25 07:07:19'),
(9, 5, '2026-06', '2026-06-20', 2500000.00, 'pending', NULL, NULL, NULL, '2026-06-25 07:09:50', '2026-06-25 07:09:50'),
(10, 5, '2026-07', '2026-07-20', 2500000.00, 'pending', NULL, NULL, NULL, '2026-06-25 07:09:50', '2026-06-25 07:09:50'),
(11, 6, '2026-06', '2026-06-20', 5000000.00, 'pending', NULL, NULL, NULL, '2026-06-25 07:10:55', '2026-06-25 07:10:55'),
(12, 6, '2026-07', '2026-07-20', 5000000.00, 'pending', NULL, NULL, NULL, '2026-06-25 07:10:55', '2026-06-25 07:10:55'),
(13, 6, '2026-08', '2026-08-20', 5000000.00, 'pending', NULL, NULL, NULL, '2026-06-25 07:10:55', '2026-06-25 07:10:55'),
(14, 7, '2026-06', '2026-06-20', 1200000.00, 'pending', NULL, NULL, NULL, '2026-06-25 07:11:41', '2026-06-25 07:11:41'),
(15, 7, '2026-07', '2026-07-20', 1200000.00, 'pending', NULL, NULL, NULL, '2026-06-25 07:11:41', '2026-06-25 07:11:41'),
(16, 8, '2026-06', '2026-06-20', 1500000.00, 'pending', NULL, NULL, NULL, '2026-06-25 07:13:12', '2026-06-25 07:13:12'),
(17, 8, '2026-07', '2026-07-20', 1500000.00, 'pending', NULL, NULL, NULL, '2026-06-25 07:13:12', '2026-06-25 07:13:12'),
(18, 9, '2026-06', '2026-06-20', 5000000.00, 'pending', NULL, NULL, NULL, '2026-06-25 07:14:35', '2026-06-25 07:14:35'),
(19, 9, '2026-07', '2026-07-20', 5000000.00, 'pending', NULL, NULL, NULL, '2026-06-25 07:14:35', '2026-06-25 07:14:35'),
(20, 9, '2026-08', '2026-08-20', 5000000.00, 'pending', NULL, NULL, NULL, '2026-06-25 07:14:35', '2026-06-25 07:14:35'),
(21, 10, '2026-06', '2026-06-20', 5000000.00, 'pending', NULL, NULL, NULL, '2026-06-25 07:15:36', '2026-06-25 07:15:36'),
(22, 10, '2026-07', '2026-07-20', 5000000.00, 'pending', NULL, NULL, NULL, '2026-06-25 07:15:36', '2026-06-25 07:15:36'),
(23, 11, '2026-06', '2026-06-20', 1800000.00, 'paid', '2026-06-25', NULL, 13, '2026-06-25 07:17:11', '2026-06-25 07:24:09'),
(24, 11, '2026-07', '2026-07-20', 1800000.00, 'pending', NULL, NULL, NULL, '2026-06-25 07:17:11', '2026-06-25 07:17:11'),
(25, 12, '2026-06', '2026-06-20', 1800000.00, 'pending', NULL, NULL, NULL, '2026-06-25 10:31:27', '2026-06-25 10:31:27'),
(26, 12, '2026-07', '2026-07-20', 1800000.00, 'pending', NULL, NULL, NULL, '2026-06-25 10:31:27', '2026-06-25 10:31:27'),
(27, 13, '2026-06', '2026-06-20', 1800000.00, 'pending', NULL, NULL, NULL, '2026-06-25 10:46:30', '2026-06-25 10:46:30'),
(28, 13, '2026-07', '2026-07-20', 1800000.00, 'pending', NULL, NULL, NULL, '2026-06-25 10:46:30', '2026-06-25 10:46:30'),
(29, 14, '2026-05', '2026-05-20', 2200000.00, 'pending', NULL, NULL, NULL, '2026-06-25 10:48:05', '2026-06-25 10:48:05'),
(30, 14, '2026-06', '2026-06-20', 2200000.00, 'pending', NULL, NULL, NULL, '2026-06-25 10:48:05', '2026-06-25 10:48:05');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-5c785c036466adea360111aa28563bfd556b5fba:timer', 'i:1782436777;', 1782436777),
('laravel-cache-5c785c036466adea360111aa28563bfd556b5fba', 'i:1;', 1782436777);

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
  `id` bigint UNSIGNED NOT NULL,
  `contract_number` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `contract_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `account_number` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sid` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `owner_am_id` bigint NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'active',
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

INSERT INTO `contracts` (`id`, `contract_number`, `contract_name`, `account_number`, `sid`, `owner_am_id`, `start_date`, `end_date`, `status`, `created_by`, `created_at`, `updated_at`, `customer_id_number`, `telkom_name`, `telkom_position`, `telkom_unit`, `customer_address`, `customer_npwp`, `customer_pic_name`, `customer_pic_position`, `customer_phone`, `customer_email`, `generated_file`, `signing_date`, `signing_location`) VALUES
(1, '17251634', 'PT Pelindo', '8136173', '72364275', 1, '2026-06-26', '2026-07-28', 'active', 1, '2026-06-25 06:58:48', '2026-06-25 06:58:48', NULL, 'Silvia', 'AM', 'BS', 'Plaju', '84584', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, '756763', 'Bank Indonesia', '26347254', '3657336', 1, '2026-06-15', '2026-07-15', 'expiring', 1, '2026-06-25 06:59:52', '2026-06-25 07:00:19', NULL, 'Vindira', 'AM', 'BS', 'Sudirman', '8475846', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, '08349374', 'Gramedia', '7247254', '7237252', 1, '2026-06-10', '2026-07-10', 'expiring', 1, '2026-06-25 07:01:40', '2026-06-25 07:01:40', NULL, 'Alifyan', 'AM', 'BS', 'KM 7', '83748364', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, '8457853', 'PT Pertamina', '47548753', '612416246', 1, '2026-06-01', '2026-07-01', 'followup', 1, '2026-06-25 07:07:19', '2026-06-25 07:07:19', NULL, 'Piyayo', 'AM', 'BS', 'Plaju', '9374583', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, '8236234', 'Bank Sumsel', '92738264', '28425472', 3, '2026-06-25', '2026-07-25', 'expiring', 2, '2026-06-25 07:09:50', '2026-06-25 07:19:04', NULL, 'Vindi', 'AM', 'BS', 'Jakabaring', '92748346', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, '9357364', 'Palembang Icon Mall', '97433648', '7121635', 2, '2026-06-16', '2026-08-16', 'active', 2, '2026-06-25 07:10:55', '2026-06-25 07:10:55', NULL, 'Nabila', 'AM', 'BS', 'Simpang 5', '8346386', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, '824627345', 'PT PLN', '834637484', '72327532', 2, '2026-06-02', '2026-07-02', 'followup', 2, '2026-06-25 07:11:41', '2026-06-25 07:11:41', NULL, 'Alifyan', 'AM', 'BS', 'Kertapati', '824286', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(8, '7235273', 'PT Angkasa Pura', '927328632', '83463745', 3, '2026-06-17', '2026-07-17', 'expiring', 3, '2026-06-25 07:13:12', '2026-06-25 07:13:12', NULL, 'Silvia', 'AM', 'BS', 'Bandara', '827463', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(9, '354342', 'Richeese Factory', '03859375', '8262376', 3, '2026-06-27', '2026-08-27', 'active', 3, '2026-06-25 07:14:35', '2026-06-25 07:14:35', NULL, 'Septi', 'Inputter', 'BS', 'Sudirman', '93538638', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, '83436', 'PT Sinarmas', '834637453', '63526362', 3, '2026-06-01', '2026-07-01', 'followup', 3, '2026-06-25 07:15:36', '2026-06-25 07:15:36', NULL, 'Avryns', 'AM', 'BS', 'Bukit Besar', '83538645', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(11, '746374', 'Lion Air', '83473743', '7352632', 2, '2026-06-18', '2026-07-18', 'expiring', 2, '2026-06-25 07:17:11', '2026-06-25 07:17:11', NULL, 'Alifyan', 'AM', 'BS', 'Bandara', '834636', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(12, '862727', 'Transmart', '72TW6256', '723625', 2, '2026-06-29', '2026-07-29', 'active', 2, '2026-06-25 10:31:27', '2026-06-25 10:36:25', NULL, 'Silvia', 'AM', 'BS', 'Pakjo', '827637253', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(13, '8347386', 'Hypermart', '95949347', '346376', 2, '2026-06-17', '2026-07-17', 'expiring', 2, '2026-06-25 10:46:30', '2026-06-25 10:46:30', NULL, 'Vindi', 'AM', 'BS', 'PS', '8347837', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(14, '47587', 'Citilink', '8748465', '23472346', 2, '2026-05-05', '2026-06-05', 'expired', 2, '2026-06-25 10:48:05', '2026-06-25 10:48:05', NULL, 'Alifyan', 'AM', 'BS', 'Bandara', '8347387', NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `contract_extensions`
--

CREATE TABLE `contract_extensions` (
  `id` bigint UNSIGNED NOT NULL,
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
  `id` bigint UNSIGNED NOT NULL,
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
  `id` bigint UNSIGNED NOT NULL,
  `contract_id` bigint NOT NULL,
  `service_id` bigint NOT NULL,
  `installation_fee` decimal(15,2) NOT NULL DEFAULT '0.00',
  `monthly_fee` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contract_services`
--

INSERT INTO `contract_services` (`id`, `contract_id`, `service_id`, `installation_fee`, `monthly_fee`, `created_at`, `updated_at`) VALUES
(1, 1, 6, 4500000.00, 2200000.00, '2026-06-25 06:58:48', '2026-06-25 06:58:48'),
(3, 2, 8, 3500000.00, 1800000.00, '2026-06-25 07:00:19', '2026-06-25 07:00:19'),
(7, 6, 7, 8000000.00, 5000000.00, '2026-06-25 07:10:55', '2026-06-25 07:10:55'),
(8, 7, 3, 2500000.00, 1200000.00, '2026-06-25 07:11:41', '2026-06-25 07:11:41'),
(9, 8, 2, 3000000.00, 1500000.00, '2026-06-25 07:13:12', '2026-06-25 07:13:12'),
(10, 9, 7, 8000000.00, 5000000.00, '2026-06-25 07:14:35', '2026-06-25 07:14:35'),
(11, 10, 7, 8000000.00, 5000000.00, '2026-06-25 07:15:36', '2026-06-25 07:15:36'),
(12, 11, 8, 3500000.00, 1800000.00, '2026-06-25 07:17:11', '2026-06-25 07:17:11'),
(13, 5, 1, 5000000.00, 2500000.00, '2026-06-25 07:17:25', '2026-06-25 07:17:25'),
(19, 3, 1, 5000000.00, 2500000.00, '2026-06-25 09:32:20', '2026-06-25 09:32:20'),
(21, 4, 4, 4000000.00, 2000000.00, '2026-06-25 10:20:22', '2026-06-25 10:20:22'),
(23, 12, 2, 3000000.00, 1500000.00, '2026-06-25 10:36:25', '2026-06-25 10:36:25'),
(24, 13, 8, 3500000.00, 1800000.00, '2026-06-25 10:46:30', '2026-06-25 10:46:30'),
(25, 14, 6, 4500000.00, 2200000.00, '2026-06-25 10:48:05', '2026-06-25 10:48:05');

-- --------------------------------------------------------

--
-- Table structure for table `contract_transfer_history`
--

CREATE TABLE `contract_transfer_history` (
  `id` bigint UNSIGNED NOT NULL,
  `contract_id` bigint NOT NULL,
  `from_am_id` bigint DEFAULT NULL,
  `to_am_id` bigint DEFAULT NULL,
  `transferred_by` bigint NOT NULL,
  `transfer_type` enum('direct','approved_request') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'approved_request',
  `notes` text COLLATE utf8mb4_general_ci,
  `transfer_date` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contract_transfer_history`
--

INSERT INTO `contract_transfer_history` (`id`, `contract_id`, `from_am_id`, `to_am_id`, `transferred_by`, `transfer_type`, `notes`, `transfer_date`, `created_at`, `updated_at`) VALUES
(1, 5, 2, 3, 8, 'direct', 'Ulang', '2026-06-25 14:19:04', '2026-06-25 07:19:04', '2026-06-25 07:19:04');

-- --------------------------------------------------------

--
-- Table structure for table `contract_transfer_requests`
--

CREATE TABLE `contract_transfer_requests` (
  `id` bigint UNSIGNED NOT NULL,
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

--
-- Dumping data for table `contract_transfer_requests`
--

INSERT INTO `contract_transfer_requests` (`id`, `contract_id`, `requested_by`, `current_am_id`, `target_am_id`, `reason`, `status`, `approved_by`, `approved_at`, `created_at`, `updated_at`) VALUES
(1, 5, 2, 2, 3, 'Ga sempat', 'rejected', 8, '2026-06-25 14:18:29', '2026-06-25 07:17:54', '2026-06-25 07:18:29');

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
  `id` bigint UNSIGNED NOT NULL,
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
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `manager_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `daily_schedule` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '08:00',
  `weekly_schedule` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'monday_morning',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notification_settings`
--

INSERT INTO `notification_settings` (`id`, `user_id`, `manager_email`, `daily_schedule`, `weekly_schedule`, `created_at`, `updated_at`) VALUES
(1, NULL, '', '08:00', 'monday_morning', '2026-06-24 08:51:58', '2026-06-24 08:51:58'),
(2, NULL, 'manager@example.com', '08:00', 'monday_morning', '2026-06-24 15:54:05', '2026-06-24 15:54:05'),
(3, 9, 'AM1@telkom.com', '08:00', 'monday_morning', '2026-06-24 10:40:05', '2026-06-24 10:40:05'),
(4, 12, 'inputter@telkom.com', '08:00', 'monday_morning', '2026-06-24 12:10:31', '2026-06-24 12:10:31'),
(5, 1, 'AM1@telkom.com', '08:00', 'monday_morning', '2026-06-25 07:07:52', '2026-06-25 07:07:52'),
(6, 2, 'silvianabila2301@gmail.com', '08:00', 'monday_morning', '2026-06-25 07:12:02', '2026-06-25 10:32:08');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
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
  `id` bigint UNSIGNED NOT NULL,
  `service_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `installation_fee` decimal(18,2) NOT NULL DEFAULT '0.00',
  `monthly_fee` decimal(18,2) NOT NULL DEFAULT '0.00',
  `status` enum('active','inactive') COLLATE utf8mb4_general_ci DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `service_name`, `installation_fee`, `monthly_fee`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Metro Ethernet', 5000000.00, 2500000.00, 'active', '2026-06-20 00:44:26', '2026-06-20 00:44:26'),
(2, 'IP Transit', 3000000.00, 1500000.00, 'active', '2026-06-20 00:44:26', '2026-06-20 00:44:26'),
(3, 'Astinet', 2500000.00, 1200000.00, 'active', '2026-06-20 00:44:26', '2026-06-20 00:44:26'),
(4, 'Managed Service', 4000000.00, 2000000.00, 'active', '2026-06-20 00:44:26', '2026-06-20 00:44:26'),
(5, 'SD-WAN', 6000000.00, 3500000.00, 'active', '2026-06-20 00:44:26', '2026-06-20 00:44:26'),
(6, 'Cloud Connect', 4500000.00, 2200000.00, 'active', '2026-06-20 00:44:26', '2026-06-20 00:44:26'),
(7, 'Data Center Service', 8000000.00, 5000000.00, 'active', '2026-06-20 00:44:26', '2026-06-20 00:44:26'),
(8, 'Internet Dedicated', 3500000.00, 1800000.00, 'active', '2026-06-20 00:44:26', '2026-06-20 00:44:26');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('Fb2BXpB5XnHwZC7PDEJ6LVT46bfU69FLwaW4gE7X', 8, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJhZXM2cW9ydHgzb1RuN044U29GN0JSTEhwZjQwcG1tNXJlemdTbVFRIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9wcm9maWxlIiwicm91dGUiOiJwcm9maWxlIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjh9', 1782436740);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
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
(1, 2, 'Account Manager 1', 'AM1@telkom.com', '$2y$12$SdYtglKdH0H5ADuf16lEyeAsLgY2Sl8hOnR45qEcwWuSYJnc.fuH2', 'active', '2026-06-17 02:06:37', '2026-06-25 02:10:20'),
(2, 2, 'Account Manager 2', 'AM2@telkom.com', '$2y$12$fwFUKh.sSsn6NmyyDsB/VujtpY7OtiH5koMosRT.qVfEePZM91ZrS', 'active', '2026-06-17 02:06:37', '2026-06-17 02:06:37'),
(3, 2, 'Account Manager 3', 'AM3@telkom.com', '$2y$12$Mc2XZmFr0wOm4em/ZpaRdu7x9MJuMeGFC7Wn0pCZrg00JNWy2VlIC', 'active', '2026-06-17 02:06:37', '2026-06-17 02:06:37'),
(8, 1, 'Manager', 'manager@telkom.com', '$2y$12$x/jKf20Lo8Lck9FpPjqnJO9KV5TGrui//UDH/.vV.tf.o2PjibgiK', 'active', '2026-06-17 02:06:36', '2026-06-17 02:06:36'),
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
-- Indexes for table `baso_files`
--
ALTER TABLE `baso_files`
  ADD PRIMARY KEY (`id`);

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `baso_files`
--
ALTER TABLE `baso_files`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `billings`
--
ALTER TABLE `billings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `contracts`
--
ALTER TABLE `contracts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `contract_extensions`
--
ALTER TABLE `contract_extensions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contract_files`
--
ALTER TABLE `contract_files`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contract_services`
--
ALTER TABLE `contract_services`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `contract_transfer_history`
--
ALTER TABLE `contract_transfer_history`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contract_transfer_requests`
--
ALTER TABLE `contract_transfer_requests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification_settings`
--
ALTER TABLE `notification_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
