-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 05, 2025 at 06:07 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nasigoreng`
--

-- --------------------------------------------------------

--
-- Table structure for table `add_ons`
--

CREATE TABLE `add_ons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(455) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `add_ons`
--

INSERT INTO `add_ons` (`id`, `branch_id`, `name`, `description`, `price`, `created_at`, `updated_at`) VALUES
(1, 1, 'Dadar Endog', NULL, 3000, '2025-07-17 15:21:01', '2025-07-17 15:21:01'),
(2, 1, 'Kresek', NULL, 1000, '2025-07-17 15:57:08', '2025-07-17 15:57:08'),
(3, 2, 'Pentol', NULL, 2500, '2025-07-27 15:49:26', '2025-07-27 15:49:26'),
(4, 2, 'Telur Dadar', NULL, 4000, '2025-08-05 02:52:26', '2025-08-05 02:52:26'),
(5, 2, 'Telur Dadar', NULL, 4000, '2025-08-05 02:54:02', '2025-08-05 02:54:02'),
(6, 2, 'Telur Dadar', NULL, 4000, '2025-08-05 03:02:18', '2025-08-05 03:02:18'),
(7, 2, 'Telur Ceplok', NULL, 4000, '2025-08-05 03:02:36', '2025-08-05 03:02:36'),
(8, 2, 'Chicken Saos Blackpapper', NULL, 10000, '2025-08-05 03:03:00', '2025-08-05 03:03:00');

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(455) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `name`, `icon`, `address`, `latitude`, `longitude`, `created_at`, `updated_at`) VALUES
(1, 'Pusat', '8426_Frame 5 (3).png', 'Jl. Pusat No. 1', -7.4093444, 112.7761994, '2025-07-12 11:21:27', '2025-07-19 13:54:49'),
(2, 'Daerah', NULL, 'Jl. Daerah No. 12', NULL, NULL, '2025-07-12 11:21:27', '2025-07-12 11:21:27'),
(5, 'Tenggilis', NULL, 'di rumah', NULL, NULL, '2025-07-16 12:39:17', '2025-07-16 12:39:17');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `pos_visibility` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `image`, `is_active`, `created_at`, `updated_at`, `pos_visibility`) VALUES
(1, 'Bumbu', NULL, 1, '2025-07-13 10:13:42', '2025-07-20 18:01:26', 0),
(2, 'Peralatan', NULL, 1, '2025-07-13 10:13:51', '2025-07-13 10:13:51', 0),
(3, 'Menu', NULL, 1, '2025-07-13 10:21:37', '2025-07-20 18:01:24', 1),
(4, 'Kwetiau', NULL, 1, '2025-08-05 02:47:02', '2025-08-05 02:47:02', 1);

-- --------------------------------------------------------

--
-- Table structure for table `check_ins`
--

CREATE TABLE `check_ins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `coordinates` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `distance_from_branch` double NOT NULL,
  `in_at` datetime NOT NULL,
  `out_at` datetime DEFAULT NULL,
  `in_photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `out_photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `check_ins`
--

INSERT INTO `check_ins` (`id`, `user_id`, `branch_id`, `coordinates`, `distance_from_branch`, `in_at`, `out_at`, `in_photo`, `out_photo`, `duration`, `created_at`, `updated_at`) VALUES
(4, 2, 1, '{\"latitude\":\"-7.393344301405378\",\"longitude\":\"112.76428590186426\"}', 2211.5755140015, '2025-07-14 01:15:26', '2025-07-14 01:56:00', '8CC2F978-4FC2-47D3-9894-5307D3819CAA.jpg', '5933B6B0-4D99-4F96-AA41-0FE61067D55A.jpg', 1, '2025-07-13 18:55:26', '2025-07-13 18:56:00'),
(5, 1, 1, '{\"latitude\":\"-7.393259025239257\",\"longitude\":\"112.76437565019805\"}', 2213.3669668625, '2025-07-14 02:00:05', '2025-07-14 02:03:37', '3216ADAE-6DBC-46B3-A5FB-C8F3DF8AFF25.jpg', '6A8DD5D7-607E-4732-8D16-FBA4CD748A12.jpg', 4, '2025-07-13 19:00:05', '2025-07-13 19:03:37');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_ability` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `branch_id`, `name`, `email`, `phone`, `address`, `transaction_ability`, `created_at`, `updated_at`) VALUES
(1, 1, 'Suroso', 'suroso@gmail.com', '08110361222', NULL, 0, '2025-07-13 06:00:23', '2025-07-13 06:00:23'),
(2, 1, 'Jessica', 'jessica@gmail.com', NULL, NULL, 0, '2025-07-13 06:42:32', '2025-07-13 06:42:32'),
(3, 1, 'Riyan Satria Adi Tama', 'riyan.satria.619@gmail.com', '0881036183076', NULL, 0, '2025-07-13 06:42:41', '2025-07-13 06:42:41'),
(6, 1, 'Rini Wulandari', 'rini.wulandari@gmail.com', '081231231231', NULL, 0, '2025-07-18 10:33:03', '2025-07-18 10:33:03'),
(7, 1, 'Wanda', 'wanda.w25@gmail.com', '088226019408', NULL, 0, '2025-07-18 10:33:30', '2025-07-18 10:33:30'),
(8, 1, 'Adin Menolak Sadar', 'adin.k@gmail.com', '085159772902', NULL, 0, '2025-07-18 10:34:35', '2025-07-18 10:34:35'),
(9, 1, 'Oktavia', 'okta.v@icloud.com', NULL, NULL, 0, '2025-07-18 10:35:03', '2025-07-18 10:35:03'),
(10, 1, 'Herman Lupa Diri', 'herman.ld@icloud.com', '081222578130', NULL, 0, '2025-07-18 10:35:47', '2025-07-18 10:35:47'),
(11, 1, 'Heri Tapiheru', NULL, '087587809461', NULL, 0, '2025-07-18 10:36:14', '2025-07-18 10:36:14'),
(12, 1, 'Joko Mulyono', 'joko.pengutang@gmail.com', '081827373332', NULL, 0, '2025-07-18 10:37:09', '2025-07-18 10:37:09'),
(13, 1, 'Reantika Widya', 'reantika_wid77@gmail.com', '081212383832', NULL, 0, '2025-07-18 10:37:47', '2025-07-18 10:37:47'),
(14, 1, 'Pangeran Nipunegoro', 'pangeran_penipu@icloud.com', '08128726764', NULL, 0, '2025-07-18 10:38:18', '2025-07-18 10:38:18'),
(15, 1, 'Santri Situbohong', 'santri.situbohong@yahoo.com', '08886483787', NULL, 0, '2025-07-18 10:38:41', '2025-07-18 10:38:41'),
(16, 1, 'Nyi Roro Ngibul', NULL, '083782387773', NULL, 0, '2025-07-18 10:40:38', '2025-07-18 10:40:38'),
(17, 1, 'Prabu Silihuange', NULL, '08114783732', NULL, 0, '2025-07-18 10:41:20', '2025-07-18 10:41:20'),
(18, 1, 'Ngutankhamun', 'ngutankhamun.iiiperiode@gmail.com', NULL, NULL, 0, '2025-07-18 10:42:24', '2025-07-18 10:42:24'),
(19, 1, 'Benjamin Ngutanghayu', 'benjamin_pengutang@gmail.com', NULL, NULL, 0, '2025-07-18 10:44:53', '2025-07-18 10:44:53'),
(20, 1, 'Oey Hong Liong', NULL, NULL, NULL, 0, '2025-07-18 10:45:30', '2025-07-18 10:45:30'),
(21, 1, 'Giocovi Dodo', NULL, '081226225189', NULL, 0, '2025-07-18 10:46:00', '2025-07-18 10:46:00'),
(22, 1, 'Jaenudin Ngaciro', 'udin.ngaciro@gmail.com', '085578978461', NULL, 0, '2025-07-18 10:46:29', '2025-07-18 10:46:29'),
(23, 2, 'Roni', NULL, '081818228374', NULL, 0, '2025-07-18 11:45:19', '2025-07-18 11:45:19'),
(24, 2, 'Budi Santoso', 'budi.san@gmail.com', '0889284743', NULL, 0, '2025-07-18 11:45:33', '2025-07-18 11:45:33'),
(25, 2, 'Sabrina', NULL, '08838848372', NULL, 0, '2025-07-18 11:46:26', '2025-07-18 11:46:26'),
(26, 2, 'Deni Situmorang', NULL, '08483748374', NULL, 0, '2025-07-18 11:46:42', '2025-07-18 11:46:42'),
(27, 2, 'Citra Vernanda', 'citra.vernanda@gmail.com', '088274837438', NULL, 0, '2025-07-18 11:47:25', '2025-07-18 11:47:25'),
(28, 2, 'Tatang Sutarma', NULL, '088139834983', NULL, 0, '2025-07-18 11:47:55', '2025-07-18 11:47:55'),
(29, 2, 'Om Heri', NULL, '081284873487', NULL, 0, '2025-07-18 11:48:05', '2025-07-18 11:48:05'),
(30, 1, 'John Doe', NULL, '85159772902', NULL, 0, '2025-08-05 03:16:59', '2025-08-05 03:16:59'),
(31, 1, 'Mas Gundul', NULL, '85159772908', NULL, 0, '2025-08-05 03:18:45', '2025-08-05 03:18:45');

-- --------------------------------------------------------

--
-- Table structure for table `customer_customer_types`
--

CREATE TABLE `customer_customer_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `customer_type_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_customer_types`
--

INSERT INTO `customer_customer_types` (`id`, `customer_id`, `customer_type_id`, `created_at`, `updated_at`) VALUES
(1, 1, 2, '2025-07-13 06:00:23', '2025-07-13 06:00:23'),
(3, 2, 3, '2025-07-13 06:42:32', '2025-07-13 06:42:32'),
(4, 3, 2, '2025-07-13 06:42:41', '2025-07-13 06:42:41'),
(8, 3, 3, '2025-07-13 06:51:31', '2025-07-13 06:51:31'),
(10, 6, 2, '2025-07-18 10:33:03', '2025-07-18 10:33:03'),
(11, 7, 3, '2025-07-18 10:33:30', '2025-07-18 10:33:30'),
(12, 8, 6, '2025-07-18 10:34:35', '2025-07-18 10:34:35'),
(13, 9, 3, '2025-07-18 10:35:03', '2025-07-18 10:35:03'),
(14, 10, 6, '2025-07-18 10:35:47', '2025-07-18 10:35:47'),
(15, 11, 2, '2025-07-18 10:36:14', '2025-07-18 10:36:14'),
(16, 12, 6, '2025-07-18 10:37:09', '2025-07-18 10:37:09'),
(17, 13, 3, '2025-07-18 10:37:47', '2025-07-18 10:37:47'),
(18, 14, 6, '2025-07-18 10:38:18', '2025-07-18 10:38:18'),
(19, 15, 6, '2025-07-18 10:38:41', '2025-07-18 10:38:41'),
(20, 16, 6, '2025-07-18 10:40:38', '2025-07-18 10:40:38'),
(21, 17, 6, '2025-07-18 10:41:20', '2025-07-18 10:41:20'),
(22, 18, 6, '2025-07-18 10:42:24', '2025-07-18 10:42:24'),
(23, 19, 6, '2025-07-18 10:44:53', '2025-07-18 10:44:53'),
(24, 20, 6, '2025-07-18 10:45:30', '2025-07-18 10:45:30'),
(25, 21, 6, '2025-07-18 10:46:00', '2025-07-18 10:46:00'),
(26, 22, 6, '2025-07-18 10:46:29', '2025-07-18 10:46:29'),
(27, 23, 7, '2025-07-18 11:45:19', '2025-07-18 11:45:19'),
(28, 24, 7, '2025-07-18 11:45:33', '2025-07-18 11:45:33'),
(29, 25, 8, '2025-07-18 11:46:26', '2025-07-18 11:46:26'),
(30, 26, 7, '2025-07-18 11:46:42', '2025-07-18 11:46:42'),
(31, 27, 8, '2025-07-18 11:47:25', '2025-07-18 11:47:25'),
(32, 28, 7, '2025-07-18 11:47:55', '2025-07-18 11:47:55'),
(33, 29, 7, '2025-07-18 11:48:05', '2025-07-18 11:48:05');

-- --------------------------------------------------------

--
-- Table structure for table `customer_types`
--

CREATE TABLE `customer_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_types`
--

INSERT INTO `customer_types` (`id`, `branch_id`, `name`, `color`, `created_at`, `updated_at`) VALUES
(2, 1, 'VIP', '#ce4646', '2025-07-13 05:40:02', '2025-07-13 05:40:02'),
(3, 1, 'Setia', '#40a225', '2025-07-13 05:43:45', '2025-07-13 05:43:45'),
(6, 1, 'Pengutang', '#e23232', '2025-07-18 10:33:54', '2025-07-18 10:33:54'),
(7, 2, 'Pria', '#6872fd', '2025-07-18 11:44:28', '2025-07-18 11:44:28'),
(8, 2, 'Wanita', '#ff38e4', '2025-07-18 11:44:38', '2025-07-18 11:44:38');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_00000001_create_branches_table', 1),
(2, '0001_01_01_000000_create_roles_table', 1),
(3, '0001_01_01_000001_create_users_table', 1),
(4, '0001_01_01_000002_create_cache_table', 1),
(5, '0001_01_01_000003_create_jobs_table', 1),
(6, '2025_07_11_081937_create_personal_access_tokens_table', 1),
(7, '2025_07_11_121041_create_check_ins_table', 1),
(8, '2025_07_11_131215_replace_coordinates_with_lat_lng_in_branches_table', 1),
(9, '2025_07_11_163527_create_permissions_table', 1),
(10, '2025_07_11_163545_create_permission_roles_table', 1),
(11, '2025_07_11_163655_create_user_accesses_table', 1),
(12, '2025_07_12_131034_create_products_table', 1),
(13, '2025_07_12_131037_create_product_images_table', 1),
(14, '2025_07_12_131619_create_categories_table', 1),
(15, '2025_07_12_131745_create_product_categories_table', 1),
(16, '2025_07_12_162013_add_current_access_to_users_table', 1),
(17, '2025_07_12_175952_create_suppliers_table', 1),
(18, '2025_07_13_105123_create_purchasings_table', 2),
(19, '2025_07_13_105433_create_purchasing_products_table', 2),
(23, '2025_07_13_111252_create_customers_table', 3),
(24, '2025_07_13_111339_create_customer_types_table', 3),
(25, '2025_07_13_111430_create_customer_customer_types_table', 3),
(34, '2025_07_15_170851_create_stock_movements_table', 4),
(35, '2025_07_15_171135_create_stock_movement_products_table', 4),
(36, '2025_07_15_173609_add_inventory_id_on_purchasings_table', 4),
(39, '2025_07_15_202114_add_purchasing_id_to_stock_movements_table', 5),
(40, '2025_07_16_175304_add_branch_id_destination_to_stock_movements_table', 6),
(41, '2025_07_17_170417_create_add_ons_table', 7),
(42, '2025_07_17_170607_create_product_prices_table', 7),
(43, '2025_07_17_170908_create_product_add_ons_table', 7),
(44, '2025_07_17_195431_create_product_ingredients_table', 8),
(45, '2025_07_18_002703_create_sales_table', 9),
(46, '2025_07_18_004738_create_sales_items_table', 10),
(49, '2025_07_18_023637_create_sales_item_addons_table', 11),
(50, '2025_07_18_144906_add_sales_id_to_stock_movements_table', 12),
(51, '2025_07_18_165137_create_reviews_table', 13),
(52, '2025_07_21_005654_add_pos_visibility_to_categories_table', 14);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `key`, `group`, `description`, `created_at`, `updated_at`) VALUES
(1, 'sanctum', 'sanctum', 'Access to Sanctum module', '2025-07-12 11:21:27', '2025-07-12 11:21:27'),
(2, 'branch', 'branch', 'Access to Branch module', '2025-07-12 11:21:27', '2025-07-12 11:21:27'),
(3, 'product', 'product', 'Access to Product module', '2025-07-12 11:21:27', '2025-07-12 11:21:27'),
(4, 'customer', 'customer', 'Access to Customer module', '2025-07-12 11:21:27', '2025-07-12 11:21:27'),
(5, 'supplier', 'supplier', 'Access to Supplier module', '2025-07-12 11:21:27', '2025-07-12 11:21:27'),
(6, 'users', 'users', 'Access to Users module', '2025-07-12 11:21:27', '2025-07-12 11:21:27'),
(7, 'accessRole', 'accessRole', 'Access to AccessRole module', '2025-07-12 11:21:27', '2025-07-12 11:21:27'),
(8, 'storage', 'storage', 'Access to Storage module', '2025-07-12 11:21:27', '2025-07-12 11:21:27'),
(9, 'purchasing', 'purchasing', 'Access to Purchasing module', '2025-07-13 07:07:35', '2025-07-13 07:07:35'),
(10, 'checkin', 'checkin', 'Access to Checkin module', '2025-07-13 18:40:54', '2025-07-13 18:40:54'),
(11, 'profile', 'profile', 'Access to Profile module', '2025-07-14 13:12:47', '2025-07-14 13:12:47'),
(12, 'inventory', 'inventory', 'Access to Inventory module', '2025-07-15 10:22:54', '2025-07-15 10:22:54'),
(13, 'branches', 'branches', 'Access to Branches module', '2025-07-16 11:46:04', '2025-07-16 11:46:04'),
(14, 'sales', 'sales', 'Access to Sales module', '2025-07-17 09:40:04', '2025-07-17 09:40:04'),
(15, 'invoice', 'invoice', 'Access to Invoice module', '2025-07-19 16:00:14', '2025-07-19 16:00:14'),
(18, 'expense_report', 'expense_report', 'Access to Expense_report module', '2025-07-19 16:02:16', '2025-07-19 16:02:16'),
(19, 'purchasing_report', 'purchasing_report', 'Access to Purchasing_report module', '2025-07-19 16:03:00', '2025-07-19 16:03:00'),
(20, 'sales_report', 'sales_report', 'Access to Sales_report module', '2025-07-19 16:03:39', '2025-07-19 16:03:39'),
(21, 'movement_report', 'movement_report', 'Access to Movement_report module', '2025-07-20 12:40:23', '2025-07-20 12:40:23');

-- --------------------------------------------------------

--
-- Table structure for table `permission_roles`
--

CREATE TABLE `permission_roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permission_roles`
--

INSERT INTO `permission_roles` (`id`, `permission_id`, `role_id`, `created_at`, `updated_at`) VALUES
(1, 7, 1, NULL, NULL),
(2, 2, 1, NULL, NULL),
(3, 4, 1, NULL, NULL),
(5, 1, 1, NULL, NULL),
(6, 8, 1, NULL, NULL),
(7, 5, 1, NULL, NULL),
(8, 6, 1, NULL, NULL),
(10, 1, 2, '2025-07-13 07:08:55', '2025-07-13 07:08:55'),
(12, 3, 2, '2025-07-13 07:08:55', '2025-07-13 07:08:55'),
(15, 6, 2, '2025-07-13 07:08:55', '2025-07-13 07:08:55'),
(17, 8, 2, '2025-07-13 07:08:55', '2025-07-13 07:08:55'),
(18, 9, 2, '2025-07-13 07:08:55', '2025-07-13 07:08:55'),
(19, 2, 2, '2025-07-13 07:09:06', '2025-07-13 07:09:06'),
(20, 5, 2, '2025-07-13 08:43:05', '2025-07-13 08:43:05'),
(21, 9, 1, '2025-07-13 08:43:40', '2025-07-13 08:43:40'),
(22, 10, 1, '2025-07-13 18:40:58', '2025-07-13 18:40:58'),
(23, 11, 1, '2025-07-14 13:13:00', '2025-07-14 13:13:00'),
(24, 12, 1, '2025-07-15 10:23:12', '2025-07-15 10:23:12'),
(25, 13, 1, '2025-07-16 11:46:12', '2025-07-16 11:46:12'),
(26, 14, 1, '2025-07-17 09:40:36', '2025-07-17 09:40:36'),
(28, 18, 1, '2025-07-19 16:03:15', '2025-07-19 16:03:15'),
(29, 20, 1, '2025-07-19 16:04:02', '2025-07-19 16:04:02'),
(30, 19, 1, '2025-07-19 16:04:04', '2025-07-19 16:04:04'),
(31, 21, 1, '2025-07-20 12:40:38', '2025-07-20 12:40:38'),
(32, 3, 1, '2025-07-26 03:27:05', '2025-07-26 03:27:05');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 2, 'app', '2980c5e5bdf1438ed249901bcdedd6573f7765b47e1ce100e1a796e143b4650e', '[\"*\"]', '2025-07-13 18:56:00', NULL, '2025-07-13 11:25:37', '2025-07-13 18:56:00'),
(2, 'App\\Models\\User', 1, 'app', 'cd20c32fa52e719be0f34f1c3bfd1558206098d4b7fa34724b9dc8bf1b030722', '[\"*\"]', '2025-08-02 20:19:48', NULL, '2025-07-13 18:59:52', '2025-08-02 20:19:48'),
(3, 'App\\Models\\User', 2, 'app', 'a275e48e28c1b4084284b8c778e57e0e16aa8a0ad3daa8860104dad17619ce65', '[\"*\"]', '2025-08-03 20:27:21', NULL, '2025-08-03 13:06:56', '2025-08-03 20:27:21'),
(4, 'App\\Models\\User', 2, 'app', 'f70b794586e04ed99818d908ce5d9e53da71aa6d664aa771bd470273c1bf839c', '[\"*\"]', '2025-08-04 07:25:10', NULL, '2025-08-04 06:35:19', '2025-08-04 07:25:10'),
(5, 'App\\Models\\User', 2, 'app', '1a63dec6ccdf3bc6fa72ba627b3a9d8eb1d0ba9c7cac2d65351c06f431bf9bc0', '[\"*\"]', '2025-08-04 09:13:54', NULL, '2025-08-04 09:12:43', '2025-08-04 09:13:54'),
(6, 'App\\Models\\User', 2, 'app', '37a2ee190bbeed37637ea403addbc72eaff934cb16c42544b894370337c59463', '[\"*\"]', '2025-08-04 09:19:03', NULL, '2025-08-04 09:15:20', '2025-08-04 09:19:03'),
(7, 'App\\Models\\User', 2, 'app', 'e1eff4a9c4b77346f4ee7bffcef86e08b0bfeae040ece3cc24ac224cca88236e', '[\"*\"]', '2025-08-05 02:33:10', NULL, '2025-08-05 02:29:01', '2025-08-05 02:33:10'),
(8, 'App\\Models\\User', 2, 'app', '753d25fb2b6deb5f3077c0df958485fa35bf423f428809c7c3ca61d11a729511', '[\"*\"]', '2025-08-05 03:19:05', NULL, '2025-08-05 02:55:27', '2025-08-05 03:19:05');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` bigint(20) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `branch_id`, `name`, `slug`, `description`, `price`, `quantity`, `created_at`, `updated_at`) VALUES
(2, 1, 'Garam', 'garam', NULL, 1000, 353, '2025-07-13 10:21:03', '2025-07-20 14:26:25'),
(3, 1, 'Gula', 'gula', 'Gugugagag', 4000, 256, '2025-07-13 10:21:19', '2025-07-20 14:27:09'),
(4, 1, 'Minyak', 'minyak', NULL, 5000, 265, '2025-07-13 10:21:33', '2025-07-19 16:54:19'),
(5, 1, 'Nasi Goreng', 'nasi-goreng', NULL, 12000, 227, '2025-07-13 10:23:02', '2025-07-20 14:24:39'),
(6, 1, 'Mi Goreng', 'mi-goreng', NULL, 11000, 66, '2025-07-13 10:23:13', '2025-07-20 14:26:25'),
(7, 1, 'Kwetiau', 'kwetiau', NULL, 14000, 60, '2025-07-13 10:23:24', '2025-07-18 11:59:47'),
(8, 5, 'Garam', 'garam', NULL, 1000, 60, '2025-07-16 14:14:25', '2025-07-18 11:57:18'),
(12, 2, 'Garam', 'garam', NULL, 1000, 2, '2025-07-18 11:57:57', '2025-08-01 17:05:14'),
(13, 2, 'Gula', 'gula', 'Gugugagag', 4000, 248, '2025-07-18 11:57:57', '2025-08-01 17:05:14'),
(14, 2, 'Minyak', 'minyak', NULL, 5000, 7, '2025-07-18 11:57:57', '2025-08-01 17:05:14'),
(15, 2, 'Nasi Goreng', 'nasi-goreng', NULL, 12000, 13, '2025-07-18 11:59:47', '2025-07-19 11:06:42'),
(16, 2, 'Mi Goreng', 'mi-goreng', NULL, 11000, 18, '2025-07-18 11:59:47', '2025-08-01 17:05:14'),
(19, 2, 'Bumbu kering', 'bumbu-kering', NULL, 0, 500, '2025-08-05 02:43:51', '2025-08-05 03:08:27'),
(20, 2, 'Saos Goreng', 'saos-goreng', NULL, 0, 500, '2025-08-05 02:44:28', '2025-08-05 03:08:27'),
(21, 2, 'Kwetiau Goreng Master', 'kwetiau-goreng-master', NULL, 18000, 0, '2025-08-05 02:45:51', '2025-08-05 02:45:51');

-- --------------------------------------------------------

--
-- Table structure for table `product_add_ons`
--

CREATE TABLE `product_add_ons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `addon_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_add_ons`
--

INSERT INTO `product_add_ons` (`id`, `product_id`, `addon_id`, `created_at`, `updated_at`) VALUES
(1, 6, 1, '2025-07-17 15:21:01', '2025-07-17 15:21:01'),
(2, 5, 1, '2025-07-17 15:21:01', '2025-07-17 15:21:01'),
(6, 6, 2, '2025-07-17 16:23:33', '2025-07-17 16:23:33'),
(7, 15, 1, '2025-07-26 03:36:11', '2025-07-26 03:36:11'),
(8, 15, 3, '2025-07-27 15:49:26', '2025-07-27 15:49:26'),
(10, 21, 6, '2025-08-05 03:02:18', '2025-08-05 03:02:18'),
(11, 21, 7, '2025-08-05 03:02:36', '2025-08-05 03:02:36'),
(12, 21, 8, '2025-08-05 03:03:00', '2025-08-05 03:03:00');

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE `product_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`id`, `product_id`, `category_id`, `created_at`, `updated_at`) VALUES
(2, 2, 1, '2025-07-13 10:21:03', '2025-07-13 10:21:03'),
(3, 3, 1, '2025-07-13 10:21:19', '2025-07-13 10:21:19'),
(4, 4, 1, '2025-07-13 10:21:33', '2025-07-13 10:21:33'),
(5, 5, 3, '2025-07-13 10:23:02', '2025-07-13 10:23:02'),
(6, 6, 3, '2025-07-13 10:23:13', '2025-07-13 10:23:13'),
(7, 7, 3, '2025-07-13 10:23:24', '2025-07-13 10:23:24'),
(10, 6, 2, '2025-07-17 16:21:43', '2025-07-17 16:21:43'),
(14, 12, 1, '2025-07-18 11:57:57', '2025-07-18 11:57:57'),
(15, 13, 1, '2025-07-18 11:57:57', '2025-07-18 11:57:57'),
(16, 14, 1, '2025-07-18 11:57:57', '2025-07-18 11:57:57'),
(17, 15, 3, '2025-07-18 11:59:47', '2025-07-18 11:59:47'),
(18, 16, 2, '2025-07-18 11:59:47', '2025-07-18 11:59:47'),
(19, 16, 3, '2025-07-18 11:59:47', '2025-07-18 11:59:47'),
(21, 19, 1, '2025-08-05 02:43:51', '2025-08-05 02:43:51'),
(22, 20, 1, '2025-08-05 02:44:28', '2025-08-05 02:44:28'),
(25, 21, 4, '2025-08-05 02:47:23', '2025-08-05 02:47:23');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` bigint(20) NOT NULL,
  `caption` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `filename`, `size`, `caption`, `created_at`, `updated_at`) VALUES
(2, 2, '678039_garam.jpeg', 194664, NULL, '2025-07-13 10:21:03', '2025-07-13 10:21:03'),
(3, 3, '796561_gula.jpg', 81341, NULL, '2025-07-13 10:21:19', '2025-07-13 10:21:19'),
(4, 4, '332557_minyak.png', 307394, NULL, '2025-07-13 10:21:33', '2025-07-13 10:21:33'),
(5, 5, '297083_nasigoreng.jpg', 599348, NULL, '2025-07-13 10:23:02', '2025-07-13 10:23:02'),
(6, 6, '263143_migoreng.jpg', 193483, NULL, '2025-07-13 10:23:13', '2025-07-13 10:23:13'),
(7, 7, '507600_kwetiau.jpg', 45746, NULL, '2025-07-13 10:23:24', '2025-07-13 10:23:24'),
(10, 3, '835520_ChatGPT Image Jun 23, 2025, 12_29_11 AM.png', 2559923, NULL, '2025-07-17 11:40:24', '2025-07-17 11:40:24'),
(11, 3, '825814_Frame 8 (14).png', 6868, NULL, '2025-07-17 11:40:31', '2025-07-17 11:40:31'),
(12, 3, '257480_Frame 8 (4).png', 10350, NULL, '2025-07-17 11:40:43', '2025-07-17 11:40:43'),
(19, 12, '5218678039_garam.jpeg', 194664, NULL, '2025-07-18 11:57:57', '2025-07-18 11:57:57'),
(20, 13, '1516796561_gula.jpg', 81341, NULL, '2025-07-18 11:57:57', '2025-07-18 11:57:57'),
(21, 13, '4679835520_ChatGPT Image Jun 23, 2025, 12_29_11 AM.png', 2559923, NULL, '2025-07-18 11:57:57', '2025-07-18 11:57:57'),
(22, 13, '8533825814_Frame 8 (14).png', 6868, NULL, '2025-07-18 11:57:57', '2025-07-18 11:57:57'),
(23, 13, '7713257480_Frame 8 (4).png', 10350, NULL, '2025-07-18 11:57:57', '2025-07-18 11:57:57'),
(24, 14, '4434332557_minyak.png', 307394, NULL, '2025-07-18 11:57:57', '2025-07-18 11:57:57'),
(25, 15, '7165297083_nasigoreng.jpg', 599348, NULL, '2025-07-18 11:59:47', '2025-07-18 11:59:47'),
(26, 16, '3686263143_migoreng.jpg', 193483, NULL, '2025-07-18 11:59:47', '2025-07-18 11:59:47'),
(28, 19, '797938_logo baru.png', 1344252, NULL, '2025-08-05 02:43:51', '2025-08-05 02:43:51'),
(29, 20, '988172_logo baru.png', 1344252, NULL, '2025-08-05 02:44:28', '2025-08-05 02:44:28'),
(30, 21, '516901_DSCF1043.jpg', 4340750, NULL, '2025-08-05 02:45:51', '2025-08-05 02:45:51');

-- --------------------------------------------------------

--
-- Table structure for table `product_ingredients`
--

CREATE TABLE `product_ingredients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `ingredient_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_ingredients`
--

INSERT INTO `product_ingredients` (`id`, `product_id`, `ingredient_id`, `quantity`, `created_at`, `updated_at`) VALUES
(1, 6, 3, 2, '2025-07-17 13:22:04', '2025-07-17 13:22:04'),
(2, 6, 2, 3, '2025-07-17 15:07:32', '2025-07-17 15:07:32'),
(4, 15, 12, 2, '2025-07-18 12:23:35', '2025-07-18 12:23:35'),
(5, 15, 13, 1, '2025-07-18 12:23:42', '2025-07-18 12:23:42'),
(6, 15, 14, 1, '2025-07-18 12:23:47', '2025-07-18 12:23:47'),
(8, 16, 14, 3, '2025-07-18 12:24:22', '2025-07-18 12:24:22'),
(9, 16, 12, 2, '2025-07-18 12:24:28', '2025-07-18 12:24:28'),
(10, 16, 13, 1, '2025-07-18 12:24:34', '2025-07-18 12:24:34'),
(11, 21, 19, 1, '2025-08-05 02:50:05', '2025-08-05 02:50:05'),
(12, 21, 20, 1, '2025-08-05 02:50:24', '2025-08-05 02:50:24');

-- --------------------------------------------------------

--
-- Table structure for table `product_prices`
--

CREATE TABLE `product_prices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_prices`
--

INSERT INTO `product_prices` (`id`, `product_id`, `label`, `value`, `created_at`, `updated_at`) VALUES
(1, 6, 'GoFood', 13000, '2025-07-17 12:24:23', '2025-07-17 12:24:23'),
(3, 6, 'Grab', 13350, '2025-07-17 12:35:01', '2025-07-17 12:35:01'),
(4, 6, 'Shopee', 13800, '2025-07-17 12:35:07', '2025-07-17 12:35:07'),
(5, 5, 'Grab', 14000, '2025-07-17 20:03:57', '2025-07-17 20:03:57'),
(6, 15, 'App', 14000, '2025-07-18 12:23:25', '2025-07-18 12:23:25'),
(7, 16, 'App', 13000, '2025-07-18 12:24:40', '2025-07-18 12:24:40'),
(8, 21, 'Offline', 18000, '2025-08-05 02:47:51', '2025-08-05 02:47:51'),
(9, 21, 'Gofood', 29000, '2025-08-05 02:49:13', '2025-08-05 02:49:13');

-- --------------------------------------------------------

--
-- Table structure for table `purchasings`
--

CREATE TABLE `purchasings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `supplier_id` bigint(20) UNSIGNED NOT NULL,
  `inventory_id` bigint(20) UNSIGNED DEFAULT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_quantity` int(11) NOT NULL,
  `total_price` bigint(20) NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `recipient` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `received_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchasings`
--

INSERT INTO `purchasings` (`id`, `branch_id`, `supplier_id`, `inventory_id`, `label`, `notes`, `total_quantity`, `total_price`, `status`, `recipient`, `created_by`, `received_at`, `created_at`, `updated_at`) VALUES
(2, 1, 2, 1, 'PO20250713160310', 'mamamia', 8, 17000, 'RECEIVED', 1, 1, '2025-07-15 18:10:05', '2025-07-13 09:03:43', '2025-07-15 11:10:05'),
(3, 1, 2, 5, 'PO20250715191857', 'Beli minyak', 10, 50000, 'RECEIVED', 1, 1, '2025-07-15 19:31:43', '2025-07-15 12:19:01', '2025-07-16 10:30:20'),
(4, 1, 2, NULL, 'PO20250716212930', NULL, 250, 1000000, 'RECEIVED', 1, 1, '2025-07-16 21:29:59', '2025-07-16 14:29:39', '2025-07-16 14:29:59'),
(5, 1, 2, NULL, 'PO20250716213104', NULL, 25, 100000, 'RECEIVED', 1, 1, '2025-07-16 21:31:27', '2025-07-16 14:31:10', '2025-07-16 14:31:27'),
(6, 1, 3, NULL, 'PO20250716213255', NULL, 25, 100000, 'RECEIVED', 1, 1, '2025-07-16 21:33:14', '2025-07-16 14:33:01', '2025-07-16 14:33:14'),
(7, 1, 3, NULL, 'PO20250716213414', NULL, 25, 100000, 'RECEIVED', 1, 1, '2025-07-16 21:34:47', '2025-07-16 14:34:20', '2025-07-16 14:34:47'),
(8, 2, 2, NULL, 'PO20250718191316', NULL, 4, 32000, 'DRAFT', NULL, 1, NULL, '2025-07-18 12:13:20', '2025-07-28 18:59:35'),
(9, 1, 2, NULL, 'PO20250719235254', NULL, 600, 1600000, 'RECEIVED', 1, 1, '2025-07-19 23:54:16', '2025-07-19 16:53:01', '2025-07-19 16:54:16'),
(10, 1, 2, NULL, 'PO20250720212644', NULL, 5, 20000, 'RECEIVED', 1, 1, '2025-07-20 21:26:58', '2025-07-20 14:26:48', '2025-07-20 14:26:58'),
(11, 1, 3, NULL, 'PO20250723221450', NULL, 0, 0, 'DRAFT', NULL, 1, NULL, '2025-07-23 15:15:48', '2025-07-23 15:15:48'),
(12, 1, 3, NULL, 'PO20250723221606', NULL, 0, 0, 'DRAFT', NULL, 1, NULL, '2025-07-23 15:16:24', '2025-07-23 15:16:24'),
(13, 1, 2, NULL, 'PO20250723221655', 'He rek', 0, 0, 'DRAFT', NULL, 1, NULL, '2025-07-23 15:17:01', '2025-07-24 05:22:37'),
(14, 2, 2, NULL, 'PO20250726105748', 'kulakan', 40, 100000, 'DRAFT', NULL, 1, NULL, '2025-07-26 03:58:02', '2025-07-26 03:58:25'),
(15, 2, 2, NULL, 'PO20250804022510', NULL, 150, 750000, 'PUBLISHED', NULL, 2, NULL, '2025-08-03 19:25:35', '2025-08-03 19:54:52'),
(16, 2, 3, NULL, 'PO20250804032507', NULL, 0, 0, 'DRAFT', NULL, 2, NULL, '2025-08-03 20:25:16', '2025-08-03 20:27:11'),
(17, 2, 4, NULL, 'PO20250805101347', 'jgcjcj', 60, 0, 'DRAFT', NULL, 1, NULL, '2025-08-05 03:13:59', '2025-08-05 03:53:05');

-- --------------------------------------------------------

--
-- Table structure for table `purchasing_products`
--

CREATE TABLE `purchasing_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchasing_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `price` bigint(20) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchasing_products`
--

INSERT INTO `purchasing_products` (`id`, `purchasing_id`, `product_id`, `price`, `quantity`, `total_price`, `created_at`, `updated_at`) VALUES
(2, 2, 2, 1000, 5, 5000, '2025-07-13 10:33:41', '2025-07-13 11:17:55'),
(3, 2, 3, 4000, 3, 12000, '2025-07-13 10:54:22', '2025-07-13 10:54:22'),
(4, 3, 4, 5000, 10, 50000, '2025-07-15 12:19:59', '2025-07-15 12:19:59'),
(5, 4, 3, 4000, 250, 1000000, '2025-07-16 14:29:49', '2025-07-16 14:29:49'),
(6, 5, 3, 4000, 25, 100000, '2025-07-16 14:31:20', '2025-07-16 14:31:20'),
(7, 6, 3, 4000, 25, 100000, '2025-07-16 14:33:10', '2025-07-16 14:33:10'),
(8, 7, 3, 4000, 25, 100000, '2025-07-16 14:34:34', '2025-07-16 14:34:34'),
(9, 9, 3, 4000, 200, 800000, '2025-07-19 16:53:13', '2025-07-19 16:53:22'),
(10, 9, 2, 1000, 300, 300000, '2025-07-19 16:53:13', '2025-07-19 16:54:04'),
(11, 9, 4, 5000, 100, 500000, '2025-07-19 16:53:51', '2025-07-19 16:53:55'),
(12, 10, 3, 4000, 5, 20000, '2025-07-20 14:26:55', '2025-07-20 14:26:55'),
(13, 14, 13, 4000, 20, 80000, '2025-07-26 03:58:25', '2025-07-26 03:58:25'),
(14, 14, 12, 1000, 20, 20000, '2025-07-26 03:58:25', '2025-07-26 03:58:25'),
(15, 8, 13, 8000, 4, 32000, '2025-07-28 18:53:18', '2025-07-28 18:59:35'),
(16, 15, 14, 5000, 150, 750000, '2025-08-03 19:48:43', '2025-08-03 19:48:43'),
(18, 17, 20, 0, 60, 0, '2025-08-05 03:53:05', '2025-08-05 03:53:05');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sales_id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `rating` int(11) NOT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `sales_id`, `branch_id`, `customer_id`, `rating`, `body`, `created_at`, `updated_at`) VALUES
(1, 3, 1, 3, 5, 'bagus banget aku suka', '2025-07-18 10:11:09', '2025-07-18 10:11:09'),
(2, 7, 2, 25, 4, 'ih keren banget nasi gorengnya, tapi jumlah nasi gorengnya ganjil aku kurang suka', '2025-07-19 13:42:34', '2025-07-19 13:42:34'),
(3, 4, 2, 1, 4, 'enak bro, tapi masih mentah', '2025-07-19 14:41:19', '2025-07-19 14:41:19'),
(4, 9, 1, 8, 1, 'Ga enak, kudu muntah', '2025-07-19 15:53:03', '2025-07-19 15:53:03');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `multibranch` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`, `multibranch`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'Full access role', 1, '2025-07-12 11:21:28', '2025-07-12 11:21:28'),
(2, 'Staff', NULL, 0, '2025-07-13 07:08:55', '2025-07-13 07:08:55');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `movement_id` bigint(20) UNSIGNED DEFAULT NULL,
  `invoice_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_quantity` int(11) NOT NULL,
  `total_price` bigint(20) NOT NULL,
  `payment_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `branch_id`, `user_id`, `customer_id`, `movement_id`, `invoice_number`, `total_quantity`, `total_price`, `payment_status`, `notes`, `status`, `created_at`, `updated_at`) VALUES
(3, 1, 1, 3, NULL, 'INV_20250718004651', 2, 42000, 'PAID', 'ga ada edit', 'PUBLISHED', '2025-07-17 17:46:51', '2025-07-19 15:17:45'),
(4, 1, 1, 1, NULL, 'INV_20250718144050', 2, 26000, 'PAID', NULL, 'PUBLISHED', '2025-07-18 07:40:50', '2025-07-18 12:35:10'),
(5, 1, 1, 3, NULL, 'INV_20250718151145', 1, 19000, 'UNPAID', NULL, 'DRAFT', '2025-07-18 08:11:45', '2025-07-18 08:12:40'),
(6, 2, 1, 28, NULL, 'INV_20250718191045', 0, 0, 'UNPAID', NULL, 'DRAFT', '2025-07-18 12:10:45', '2025-07-18 12:10:45'),
(7, 2, 1, 25, NULL, 'INV_20250718192457', 1, 14000, 'PAID', NULL, 'PUBLISHED', '2025-07-18 12:24:57', '2025-07-18 12:35:28'),
(8, 2, 1, 26, NULL, 'INV_20250719180602', 6, 84000, 'PAID', NULL, 'PUBLISHED', '2025-07-19 11:06:02', '2025-07-19 11:07:02'),
(9, 1, 1, 8, NULL, 'INV_20250719221821', 1, 20350, 'PAID', NULL, 'PUBLISHED', '2025-07-19 15:18:21', '2025-07-19 15:18:48'),
(10, 1, 1, 7, NULL, 'INV_20250720212426', 1, 14000, 'PAID', NULL, 'PUBLISHED', '2025-07-20 14:24:26', '2025-07-20 14:24:40'),
(11, 1, 1, 10, NULL, 'INV_20250720212516', 4, 57400, 'PAID', NULL, 'PUBLISHED', '2025-07-20 14:25:16', '2025-07-20 14:26:27'),
(12, 1, 1, 3, NULL, 'INV_20250720222306', 0, 0, 'UNPAID', NULL, 'DRAFT', '2025-07-20 15:23:06', '2025-07-20 15:23:06'),
(13, 2, 1, 24, NULL, 'INV_20250726102526', 3, 42000, 'UNPAID', NULL, 'DRAFT', '2025-07-26 03:25:26', '2025-07-26 03:26:36'),
(14, 2, 1, 26, NULL, 'INV_20250726103516', 2, 26000, 'PAID', NULL, 'PUBLISHED', '2025-07-26 03:35:16', '2025-08-01 17:05:27'),
(15, 2, 1, 1, NULL, 'INV_20250803024846', 6, 99400, 'PAID', NULL, 'PUBLISHED', '2025-08-02 19:48:46', '2025-08-02 19:48:46'),
(16, 2, 1, 24, NULL, 'INV_20250803025631', 1, 25350, 'PAID', NULL, 'PUBLISHED', '2025-08-02 19:56:31', '2025-08-02 19:56:31'),
(17, 2, 2, 26, NULL, 'INV_20250804004223', 1, 22350, 'PAID', NULL, 'PUBLISHED', '2025-08-03 17:42:23', '2025-08-03 17:42:23'),
(18, 2, 2, 28, NULL, 'INV_20250804004817', 1, 19350, 'PAID', NULL, 'PUBLISHED', '2025-08-03 17:48:17', '2025-08-03 17:48:17'),
(19, 2, 2, 27, NULL, 'INV_20250804004942', 1, 20000, 'PAID', NULL, 'PUBLISHED', '2025-08-03 17:49:42', '2025-08-03 17:49:42'),
(20, 2, 2, 25, NULL, 'INV_20250804005055', 2, 31000, 'PAID', NULL, 'PUBLISHED', '2025-08-03 17:50:55', '2025-08-03 17:50:55'),
(21, 2, 2, 26, NULL, 'INV_20250804010113', 1, 16350, 'PAID', NULL, 'PUBLISHED', '2025-08-03 18:01:13', '2025-08-03 18:01:13'),
(22, 2, 2, 25, NULL, 'INV_20250804010217', 1, 16000, 'PAID', NULL, 'PUBLISHED', '2025-08-03 18:02:17', '2025-08-03 18:02:17'),
(23, 2, 2, 23, NULL, 'INV_20250804011935', 4, 55200, 'PAID', NULL, 'PUBLISHED', '2025-08-03 18:19:35', '2025-08-03 18:19:35'),
(24, 2, 2, 26, NULL, 'INV_20250804161835', 1, 17000, 'PAID', NULL, 'PUBLISHED', '2025-08-04 09:18:35', '2025-08-04 09:18:35'),
(25, 1, 2, 1, NULL, 'INV_20250805092957', 1, 16800, 'PAID', NULL, 'PUBLISHED', '2025-08-05 02:29:57', '2025-08-05 02:29:57'),
(26, 1, 2, 31, NULL, 'INV_20250805101905', 1, 17000, 'PAID', NULL, 'PUBLISHED', '2025-08-05 03:19:05', '2025-08-05 03:19:05');

-- --------------------------------------------------------

--
-- Table structure for table `sales_items`
--

CREATE TABLE `sales_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sales_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `price_id` bigint(20) UNSIGNED DEFAULT NULL,
  `price` bigint(20) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` bigint(20) NOT NULL,
  `additional_price` bigint(20) NOT NULL,
  `grand_total` bigint(20) NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales_items`
--

INSERT INTO `sales_items` (`id`, `sales_id`, `product_id`, `price_id`, `price`, `quantity`, `total_price`, `additional_price`, `grand_total`, `notes`, `created_at`, `updated_at`) VALUES
(5, 3, 6, 1, 13000, 1, 13000, 6000, 19000, NULL, '2025-07-17 19:51:38', '2025-07-17 19:51:38'),
(9, 3, 5, 5, 14000, 1, 14000, 9000, 23000, NULL, '2025-07-17 20:20:04', '2025-07-17 20:20:04'),
(12, 4, 6, 1, 13000, 2, 26000, 0, 26000, NULL, '2025-07-18 07:42:01', '2025-07-18 07:42:01'),
(13, 5, 6, 1, 13000, 1, 13000, 6000, 19000, NULL, '2025-07-18 08:12:40', '2025-07-18 08:12:40'),
(14, 7, 15, 6, 14000, 1, 14000, 0, 14000, NULL, '2025-07-18 12:25:07', '2025-07-18 12:25:07'),
(15, 8, 15, 6, 14000, 6, 84000, 0, 84000, NULL, '2025-07-19 11:06:38', '2025-07-19 11:06:38'),
(16, 9, 6, 3, 13350, 1, 13350, 7000, 20350, NULL, '2025-07-19 15:18:38', '2025-07-19 15:18:38'),
(17, 10, 5, 5, 14000, 1, 14000, 0, 14000, NULL, '2025-07-20 14:24:35', '2025-07-20 14:24:35'),
(19, 11, 6, 3, 13350, 2, 26700, 0, 26700, NULL, '2025-07-20 14:25:42', '2025-07-20 14:25:42'),
(20, 11, 6, 3, 13350, 2, 26700, 4000, 30700, NULL, '2025-07-20 14:26:16', '2025-07-20 14:26:16'),
(21, 13, 13, 6, 14000, 3, 42000, 0, 42000, NULL, '2025-07-26 03:26:36', '2025-07-26 03:26:36'),
(24, 14, 16, 7, 13000, 2, 26000, 0, 26000, NULL, '2025-08-01 17:04:57', '2025-08-01 17:04:57'),
(25, 15, 5, 5, 14000, 1, 14000, 3000, 17000, NULL, '2025-08-02 19:48:46', '2025-08-02 19:48:46'),
(26, 15, 5, 5, 14000, 1, 14000, 6000, 20000, NULL, '2025-08-02 19:48:46', '2025-08-02 19:48:46'),
(27, 15, 6, 3, 13350, 1, 13350, 3000, 16350, NULL, '2025-08-02 19:48:46', '2025-08-02 19:48:46'),
(28, 15, 6, 3, 13350, 1, 13350, 6000, 19350, NULL, '2025-08-02 19:48:46', '2025-08-02 19:48:46'),
(29, 15, 6, 3, 13350, 2, 26700, 0, 26700, NULL, '2025-08-02 19:48:46', '2025-08-02 19:48:46'),
(30, 16, 6, 3, 13350, 1, 13350, 6000, 25350, NULL, '2025-08-02 19:56:31', '2025-08-02 19:56:31'),
(31, 17, 6, 3, 13350, 1, 13350, 3000, 22350, NULL, '2025-08-03 17:42:23', '2025-08-03 17:42:23'),
(32, 18, 6, 3, 13350, 1, 13350, 3000, 19350, NULL, '2025-08-03 17:48:17', '2025-08-03 17:48:17'),
(33, 19, 5, 5, 14000, 1, 14000, 3000, 20000, NULL, '2025-08-03 17:49:42', '2025-08-03 17:49:42'),
(34, 20, 5, 5, 14000, 2, 28000, 3000, 31000, NULL, '2025-08-03 17:50:55', '2025-08-03 17:50:55'),
(35, 21, 6, 3, 13350, 1, 13350, 3000, 16350, NULL, '2025-08-03 18:01:13', '2025-08-03 18:01:13'),
(36, 22, 6, 1, 13000, 1, 13000, 3000, 16000, NULL, '2025-08-03 18:02:17', '2025-08-03 18:02:17'),
(37, 23, 6, 4, 13800, 4, 55200, 0, 55200, NULL, '2025-08-03 18:19:35', '2025-08-03 18:19:35'),
(38, 24, 5, 5, 14000, 1, 14000, 3000, 17000, NULL, '2025-08-04 09:18:35', '2025-08-04 09:18:35'),
(39, 25, 6, 4, 13800, 1, 13800, 3000, 16800, NULL, '2025-08-05 02:29:57', '2025-08-05 02:29:57'),
(40, 26, 5, 5, 14000, 1, 14000, 3000, 17000, NULL, '2025-08-05 03:19:05', '2025-08-05 03:19:05');

-- --------------------------------------------------------

--
-- Table structure for table `sales_item_addons`
--

CREATE TABLE `sales_item_addons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `addon_id` bigint(20) UNSIGNED NOT NULL,
  `price` bigint(20) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales_item_addons`
--

INSERT INTO `sales_item_addons` (`id`, `item_id`, `addon_id`, `price`, `quantity`, `total_price`, `created_at`, `updated_at`) VALUES
(4, 5, 1, 3000, 2, 6000, '2025-07-17 19:51:38', '2025-07-17 19:51:38'),
(21, 9, 1, 3000, 3, 9000, '2025-07-17 20:20:04', '2025-07-17 20:20:04'),
(23, 13, 1, 3000, 2, 6000, '2025-07-18 08:12:40', '2025-07-18 08:12:40'),
(24, 16, 1, 3000, 2, 6000, '2025-07-19 15:18:38', '2025-07-19 15:18:38'),
(25, 16, 2, 1000, 1, 1000, '2025-07-19 15:18:38', '2025-07-19 15:18:38'),
(26, 20, 1, 3000, 1, 3000, '2025-07-20 14:26:16', '2025-07-20 14:26:16'),
(27, 20, 2, 1000, 1, 1000, '2025-07-20 14:26:16', '2025-07-20 14:26:16'),
(29, 25, 1, 3000, 1, 3000, '2025-08-02 19:48:46', '2025-08-02 19:48:46'),
(30, 26, 1, 3000, 2, 6000, '2025-08-02 19:48:46', '2025-08-02 19:48:46'),
(31, 27, 1, 3000, 1, 3000, '2025-08-02 19:48:46', '2025-08-02 19:48:46'),
(32, 28, 1, 3000, 2, 6000, '2025-08-02 19:48:46', '2025-08-02 19:48:46'),
(33, 30, 1, 3000, 4, 12000, '2025-08-02 19:56:31', '2025-08-02 19:56:31'),
(34, 31, 1, 3000, 3, 9000, '2025-08-03 17:42:23', '2025-08-03 17:42:23'),
(35, 32, 1, 3000, 2, 6000, '2025-08-03 17:48:17', '2025-08-03 17:48:17'),
(36, 33, 1, 3000, 2, 6000, '2025-08-03 17:49:42', '2025-08-03 17:49:42'),
(37, 34, 1, 3000, 1, 3000, '2025-08-03 17:50:55', '2025-08-03 17:50:55'),
(38, 35, 1, 3000, 1, 3000, '2025-08-03 18:01:13', '2025-08-03 18:01:13'),
(39, 36, 1, 3000, 1, 3000, '2025-08-03 18:02:17', '2025-08-03 18:02:17'),
(40, 38, 1, 3000, 1, 3000, '2025-08-04 09:18:35', '2025-08-04 09:18:35'),
(41, 39, 1, 3000, 1, 3000, '2025-08-05 02:29:57', '2025-08-05 02:29:57'),
(42, 40, 1, 3000, 1, 3000, '2025-08-05 03:19:05', '2025-08-05 03:19:05');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('5BQwqD5nMwBDpkHYkZunLcyMd5rlzmxT4cAL7Tnf', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoic1NSS2hHQWZaNEpFZE9PU0M3ZHlDRGNRckhBQXJUT0FUWEVCTG43SCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly90YWtvdG9rby5jb20vcHVyY2hhc2luZy8xNy9kZXRhaWwiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1754365986),
('AumUU2EzCyryOKgKbzk2GUdIDCanLMcJRnNKZNSF', 1, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiN0pWOE5vb3ZrMFlieU9JT3pKbGg4M1E4UEhJcUxybnZPMzhTOVEzZSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoyOToiaHR0cDovL3Rha290b2tvLmNvbS9kYXNoYm9hcmQiO319', 1754365239),
('fNlyaGiYkD6S3NolKeUF5cND7LGK7mWj6catrSvs', NULL, '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTVgwcjJPbzAxOVBJMzFwYkRmM3pTSGYwZVBEaVU3MWRUaVZFdnJWViI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTk6Imh0dHA6Ly90YWtvdG9rby5jb20iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1754363167);

-- --------------------------------------------------------

--
-- Table structure for table `stock_movements`
--

CREATE TABLE `stock_movements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `movement_id_ref` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `branch_id_destination` bigint(20) UNSIGNED DEFAULT NULL,
  `supplier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purchasing_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sales_id` bigint(20) UNSIGNED DEFAULT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_quantity` int(11) NOT NULL,
  `total_price` bigint(20) NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_movements`
--

INSERT INTO `stock_movements` (`id`, `movement_id_ref`, `branch_id`, `branch_id_destination`, `supplier_id`, `user_id`, `purchasing_id`, `sales_id`, `label`, `type`, `total_quantity`, `total_price`, `notes`, `status`, `created_at`, `updated_at`) VALUES
(1, NULL, 1, NULL, 2, 1, NULL, NULL, 'IN20250715181005', 'inbound', 0, 0, 'Stok Masuk dari Pembelian PO20250713160310', 'PUBLISHED', '2025-07-15 11:10:05', '2025-07-15 11:29:31'),
(5, NULL, 1, NULL, 2, 1, 3, NULL, 'IN20250716173015', 'inbound', 10, 0, NULL, 'DRAFT', '2025-07-16 10:30:20', '2025-07-16 10:30:20'),
(6, NULL, 1, NULL, 2, 1, NULL, NULL, 'IN20250716173250', 'inbound', 80, 80000, 'dari manual', 'PUBLISHED', '2025-07-16 10:33:03', '2025-07-16 10:39:20'),
(8, NULL, 1, 2, NULL, 1, NULL, NULL, 'OUT20250716181418', 'outbound', 0, 0, 'ke daerah', 'PUBLISHED', '2025-07-16 11:14:43', '2025-07-16 11:41:28'),
(9, NULL, 1, 5, NULL, 1, NULL, NULL, 'OUT20250716193925', 'outbound', 6, 6000, 'ke daerah lagi', 'PUBLISHED', '2025-07-16 12:39:53', '2025-07-16 14:04:42'),
(10, 9, 5, NULL, NULL, 1, NULL, NULL, 'IN20250716210442', 'inbound', 0, 0, 'Penerimaan dari cabang Pusat', 'DRAFT', '2025-07-16 14:04:42', '2025-07-16 14:04:42'),
(11, NULL, 1, 5, NULL, 1, NULL, NULL, 'OUT20250716211321', 'outbound', 20, 20000, 'ngasih ke tenggilis', 'PUBLISHED', '2025-07-16 14:13:37', '2025-07-16 14:14:25'),
(12, 11, 5, NULL, NULL, 1, NULL, NULL, 'IN20250716211425', 'inbound', 0, 0, 'Penerimaan dari cabang Pusat', 'PUBLISHED', '2025-07-16 14:14:25', '2025-07-16 14:24:41'),
(13, NULL, 1, NULL, 2, 1, 4, NULL, 'IN20250716212959', 'inbound', 0, 0, 'Stok Masuk dari Pembelian PO20250716212930', 'PUBLISHED', '2025-07-16 14:29:59', '2025-07-16 14:30:16'),
(14, NULL, 1, NULL, 2, 1, 5, NULL, 'IN20250716213127', 'inbound', 0, 0, 'Stok Masuk dari Pembelian PO20250716213104', 'PUBLISHED', '2025-07-16 14:31:27', '2025-07-16 14:31:39'),
(15, NULL, 1, NULL, 3, 1, 6, NULL, 'IN20250716213314', 'inbound', 0, 0, 'Stok Masuk dari Pembelian PO20250716213255', 'DRAFT', '2025-07-16 14:33:14', '2025-07-16 14:33:14'),
(16, NULL, 1, NULL, 3, 1, 7, NULL, 'IN20250716213447', 'inbound', 0, 0, 'Stok Masuk dari Pembelian PO20250716213414', 'PUBLISHED', '2025-07-16 14:34:47', '2025-07-16 14:34:52'),
(17, NULL, 1, NULL, NULL, 1, NULL, NULL, 'OUT20250717154338', 'outbound', 8, 32000, 'hilang', 'PUBLISHED', '2025-07-17 08:43:57', '2025-07-17 08:44:18'),
(18, NULL, 1, NULL, NULL, 1, NULL, NULL, 'OP20250718002113', 'opname', 100, 400000, NULL, 'PUBLISHED', '2025-07-17 17:21:17', '2025-07-17 17:23:37'),
(19, NULL, 1, NULL, NULL, 1, NULL, NULL, 'OUT20250718143922', 'outbound', 2, 42000, 'Penjualan INV_20250718004651', 'PUBLISHED', '2025-07-18 07:39:22', '2025-07-18 07:39:22'),
(20, NULL, 1, NULL, NULL, 1, NULL, NULL, 'OUT20250718144209', 'outbound', 2, 26000, 'Penjualan INV_20250718144050', 'PUBLISHED', '2025-07-18 07:42:09', '2025-07-18 07:42:09'),
(21, NULL, 1, NULL, 2, 1, NULL, NULL, 'IN20250718144235', 'inbound', 3, 12000, NULL, 'PUBLISHED', '2025-07-18 07:42:42', '2025-07-18 07:43:01'),
(22, NULL, 1, NULL, NULL, 1, NULL, NULL, 'OUT20250718144700', 'outbound', 2, 26000, 'Penjualan INV_20250718144050', 'PUBLISHED', '2025-07-18 07:47:00', '2025-07-18 07:47:00'),
(23, NULL, 1, 2, NULL, 1, NULL, NULL, 'OUT20250718184920', 'outbound', 40, 280000, NULL, 'DRAFT', '2025-07-18 11:49:27', '2025-07-18 11:50:05'),
(24, NULL, 1, NULL, 3, 1, NULL, NULL, 'IN20250718185021', 'inbound', 230, 1870000, NULL, 'PUBLISHED', '2025-07-18 11:50:28', '2025-07-18 11:50:57'),
(25, NULL, 1, 2, NULL, 1, NULL, NULL, 'OUT20250718185124', 'outbound', 60, 200000, NULL, 'PUBLISHED', '2025-07-18 11:51:29', '2025-07-18 11:52:00'),
(26, 25, 2, NULL, NULL, 1, NULL, NULL, 'IN20250718185200', 'inbound', 0, 0, 'Penerimaan dari cabang Pusat', 'DRAFT', '2025-07-18 11:52:00', '2025-07-18 11:52:00'),
(27, NULL, 1, NULL, 2, 1, NULL, NULL, 'IN20250718185607', 'inbound', 110, 810000, NULL, 'PUBLISHED', '2025-07-18 11:56:49', '2025-07-18 11:57:18'),
(28, NULL, 1, 2, NULL, 1, NULL, NULL, 'OUT20250718185733', 'outbound', 60, 200000, NULL, 'PUBLISHED', '2025-07-18 11:57:39', '2025-07-18 11:57:57'),
(29, 28, 2, NULL, NULL, 1, NULL, NULL, 'IN20250718185757', 'inbound', 0, 0, 'Penerimaan dari cabang Pusat', 'PUBLISHED', '2025-07-18 11:57:57', '2025-07-18 11:58:09'),
(30, NULL, 1, 2, NULL, 1, NULL, NULL, 'OUT20250718185921', 'outbound', 60, 740000, NULL, 'PUBLISHED', '2025-07-18 11:59:26', '2025-07-18 11:59:47'),
(31, 30, 2, NULL, NULL, 1, NULL, NULL, 'IN20250718185947', 'inbound', 0, 0, 'Penerimaan dari cabang Pusat', 'PUBLISHED', '2025-07-18 11:59:47', '2025-07-18 12:00:00'),
(32, NULL, 1, NULL, 3, 1, NULL, NULL, 'IN20250718190027', 'inbound', 165, 550000, NULL, 'PUBLISHED', '2025-07-18 12:00:35', '2025-07-18 12:00:58'),
(33, NULL, 2, NULL, NULL, 1, NULL, 7, 'OUT20250718192514', 'outbound', 1, 14000, 'Penjualan INV_20250718192457', 'PUBLISHED', '2025-07-18 12:25:14', '2025-07-18 12:25:14'),
(34, NULL, 2, NULL, NULL, 1, NULL, 8, 'OUT20250719180642', 'outbound', 6, 84000, 'Penjualan INV_20250719180602', 'PUBLISHED', '2025-07-19 11:06:42', '2025-07-19 11:06:42'),
(35, NULL, 1, NULL, NULL, 1, NULL, 3, 'OUT20250719221744', 'outbound', 2, 42000, 'Penjualan INV_20250718004651', 'PUBLISHED', '2025-07-19 15:17:44', '2025-07-19 15:17:44'),
(36, NULL, 1, NULL, NULL, 1, NULL, 9, 'OUT20250719221844', 'outbound', 1, 20350, 'Penjualan INV_20250719221821', 'PUBLISHED', '2025-07-19 15:18:44', '2025-07-19 15:18:44'),
(37, NULL, 1, NULL, 2, 1, 9, NULL, 'IN20250719235416', 'inbound', 0, 0, 'Stok Masuk dari Pembelian PO20250719235254', 'PUBLISHED', '2025-07-19 16:54:16', '2025-07-19 16:54:19'),
(38, NULL, 1, NULL, NULL, 1, NULL, NULL, 'OP20250720211806', 'opname', 255, 1020000, NULL, 'PUBLISHED', '2025-07-20 14:18:17', '2025-07-20 14:18:30'),
(39, NULL, 1, NULL, NULL, 1, NULL, 10, 'OUT20250720212439', 'outbound', 1, 14000, 'Penjualan INV_20250720212426', 'PUBLISHED', '2025-07-20 14:24:39', '2025-07-20 14:24:39'),
(40, NULL, 1, NULL, NULL, 1, NULL, 11, 'OUT20250720212625', 'outbound', 4, 57400, 'Penjualan INV_20250720212516', 'PUBLISHED', '2025-07-20 14:26:25', '2025-07-20 14:26:25'),
(41, NULL, 1, NULL, 2, 1, 10, NULL, 'IN20250720212658', 'inbound', 0, 0, 'Stok Masuk dari Pembelian PO20250720212644', 'PUBLISHED', '2025-07-20 14:26:58', '2025-07-20 14:27:09'),
(42, NULL, 2, NULL, NULL, 1, NULL, NULL, 'OP20250726104057', 'opname', 250, 1000000, 'update gula', 'PUBLISHED', '2025-07-26 03:41:18', '2025-07-26 03:42:44'),
(43, NULL, 2, NULL, NULL, 1, NULL, 14, 'OUT20250802000514', 'outbound', 2, 26000, 'Penjualan INV_20250726103516', 'PUBLISHED', '2025-08-01 17:05:14', '2025-08-01 17:05:14'),
(44, NULL, 2, NULL, NULL, 1, NULL, NULL, 'OP20250804015147', 'opname', 80, 320000, 'Yhh', 'PUBLISHED', '2025-08-03 18:51:58', '2025-08-03 20:24:38'),
(45, NULL, 2, NULL, NULL, 1, NULL, NULL, 'OP20250804020745', 'opname', 0, 0, NULL, 'DRAFT', '2025-08-03 19:07:47', '2025-08-03 19:07:47'),
(46, NULL, 2, NULL, 4, 1, NULL, NULL, 'IN20250805100716', 'inbound', 1000, 0, 'Produksi Pusat', 'PUBLISHED', '2025-08-05 03:07:35', '2025-08-05 03:08:27');

-- --------------------------------------------------------

--
-- Table structure for table `stock_movement_products`
--

CREATE TABLE `stock_movement_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `movement_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `price` bigint(20) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_movement_products`
--

INSERT INTO `stock_movement_products` (`id`, `movement_id`, `product_id`, `price`, `quantity`, `total_price`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 1000, 5, 5000, '2025-07-15 11:10:05', '2025-07-15 11:10:05'),
(2, 1, 3, 4000, 3, 12000, '2025-07-15 11:10:05', '2025-07-15 11:10:05'),
(6, 5, 4, 0, 10, 50000, '2025-07-16 10:30:20', '2025-07-16 10:30:20'),
(7, 6, 2, 1000, 80, 80000, '2025-07-16 10:38:29', '2025-07-16 10:38:29'),
(9, 9, 2, 1000, 6, 6000, '2025-07-16 13:58:40', '2025-07-16 13:58:40'),
(10, 11, 2, 1000, 20, 20000, '2025-07-16 14:13:51', '2025-07-16 14:13:51'),
(11, 12, 8, 1000, 20, 20000, '2025-07-16 14:14:25', '2025-07-16 14:14:25'),
(12, 13, 5, 4000, 250, 1000000, '2025-07-16 14:29:59', '2025-07-16 14:29:59'),
(13, 14, 6, 4000, 25, 100000, '2025-07-16 14:31:27', '2025-07-16 14:31:27'),
(14, 15, 7, 4000, 25, 100000, '2025-07-16 14:33:14', '2025-07-16 14:33:14'),
(15, 16, 3, 4000, 25, 100000, '2025-07-16 14:34:47', '2025-07-16 14:34:47'),
(16, 17, 3, 4000, 8, 32000, '2025-07-17 08:44:15', '2025-07-17 08:44:15'),
(17, 18, 3, 4000, 100, 400000, '2025-07-17 17:23:22', '2025-07-17 17:23:22'),
(18, 19, 6, 11000, 1, 11000, '2025-07-18 07:39:22', '2025-07-18 07:39:22'),
(19, 19, 3, 4000, 1, 4000, '2025-07-18 07:39:22', '2025-07-18 07:39:22'),
(20, 19, 2, 1000, 1, 1000, '2025-07-18 07:39:22', '2025-07-18 07:39:22'),
(21, 19, 5, 12000, 1, 12000, '2025-07-18 07:39:22', '2025-07-18 07:39:22'),
(22, 20, 6, 11000, 2, 22000, '2025-07-18 07:42:09', '2025-07-18 07:42:09'),
(23, 20, 3, 4000, 2, 8000, '2025-07-18 07:42:09', '2025-07-18 07:42:09'),
(24, 20, 2, 1000, 2, 2000, '2025-07-18 07:42:09', '2025-07-18 07:42:09'),
(25, 21, 3, 4000, 3, 12000, '2025-07-18 07:42:58', '2025-07-18 07:42:58'),
(26, 22, 6, 11000, 2, 22000, '2025-07-18 07:47:00', '2025-07-18 07:47:00'),
(27, 22, 3, 4000, 4, 16000, '2025-07-18 07:47:00', '2025-07-18 07:47:00'),
(28, 22, 2, 1000, 6, 6000, '2025-07-18 07:47:00', '2025-07-18 07:47:00'),
(29, 23, 3, 4000, 10, 40000, '2025-07-18 11:49:55', '2025-07-18 11:49:55'),
(30, 23, 2, 1000, 10, 10000, '2025-07-18 11:49:55', '2025-07-18 11:49:55'),
(31, 23, 6, 11000, 10, 110000, '2025-07-18 11:49:55', '2025-07-18 11:49:55'),
(33, 23, 5, 12000, 10, 120000, '2025-07-18 11:49:55', '2025-07-18 11:49:55'),
(34, 24, 4, 5000, 150, 750000, '2025-07-18 11:50:47', '2025-07-18 11:50:47'),
(35, 24, 7, 14000, 80, 1120000, '2025-07-18 11:50:56', '2025-07-18 11:50:56'),
(36, 25, 4, 5000, 20, 100000, '2025-07-18 11:51:52', '2025-07-18 11:51:52'),
(37, 25, 3, 4000, 20, 80000, '2025-07-18 11:51:52', '2025-07-18 11:51:52'),
(38, 25, 2, 1000, 20, 20000, '2025-07-18 11:51:52', '2025-07-18 11:51:52'),
(42, 27, 8, 1000, 40, 40000, '2025-07-18 11:56:59', '2025-07-18 11:56:59'),
(43, 27, 6, 11000, 70, 770000, '2025-07-18 11:57:15', '2025-07-18 11:57:15'),
(44, 28, 2, 1000, 20, 20000, '2025-07-18 11:57:54', '2025-07-18 11:57:54'),
(45, 28, 3, 4000, 20, 80000, '2025-07-18 11:57:54', '2025-07-18 11:57:54'),
(46, 28, 4, 5000, 20, 100000, '2025-07-18 11:57:54', '2025-07-18 11:57:54'),
(47, 29, 12, 1000, 20, 20000, '2025-07-18 11:57:57', '2025-07-18 11:57:57'),
(48, 29, 13, 4000, 20, 80000, '2025-07-18 11:57:57', '2025-07-18 11:57:57'),
(49, 29, 14, 5000, 20, 100000, '2025-07-18 11:57:57', '2025-07-18 11:57:57'),
(50, 30, 5, 12000, 20, 240000, '2025-07-18 11:59:43', '2025-07-18 11:59:43'),
(51, 30, 6, 11000, 20, 220000, '2025-07-18 11:59:43', '2025-07-18 11:59:43'),
(52, 30, 7, 14000, 20, 280000, '2025-07-18 11:59:43', '2025-07-18 11:59:43'),
(53, 31, 15, 12000, 20, 240000, '2025-07-18 11:59:47', '2025-07-18 11:59:47'),
(54, 31, 16, 11000, 20, 220000, '2025-07-18 11:59:47', '2025-07-18 11:59:47'),
(56, 32, 2, 1000, 55, 55000, '2025-07-18 12:00:55', '2025-07-18 12:00:55'),
(57, 32, 13, 4000, 55, 220000, '2025-07-18 12:00:55', '2025-07-18 12:00:55'),
(58, 32, 4, 5000, 55, 275000, '2025-07-18 12:00:55', '2025-07-18 12:00:55'),
(59, 33, 15, 12000, 1, 12000, '2025-07-18 12:25:14', '2025-07-18 12:25:14'),
(60, 33, 12, 1000, 2, 2000, '2025-07-18 12:25:14', '2025-07-18 12:25:14'),
(61, 33, 13, 4000, 1, 4000, '2025-07-18 12:25:14', '2025-07-18 12:25:14'),
(62, 33, 14, 5000, 1, 5000, '2025-07-18 12:25:14', '2025-07-18 12:25:14'),
(63, 34, 15, 12000, 6, 72000, '2025-07-19 11:06:42', '2025-07-19 11:06:42'),
(64, 34, 12, 1000, 12, 12000, '2025-07-19 11:06:42', '2025-07-19 11:06:42'),
(65, 34, 13, 4000, 6, 24000, '2025-07-19 11:06:42', '2025-07-19 11:06:42'),
(66, 34, 14, 5000, 6, 30000, '2025-07-19 11:06:42', '2025-07-19 11:06:42'),
(67, 35, 6, 11000, 1, 11000, '2025-07-19 15:17:44', '2025-07-19 15:17:44'),
(68, 35, 3, 4000, 2, 8000, '2025-07-19 15:17:44', '2025-07-19 15:17:44'),
(69, 35, 2, 1000, 3, 3000, '2025-07-19 15:17:44', '2025-07-19 15:17:44'),
(70, 35, 5, 12000, 1, 12000, '2025-07-19 15:17:44', '2025-07-19 15:17:44'),
(71, 36, 6, 11000, 1, 11000, '2025-07-19 15:18:44', '2025-07-19 15:18:44'),
(72, 36, 3, 4000, 2, 8000, '2025-07-19 15:18:44', '2025-07-19 15:18:44'),
(73, 36, 2, 1000, 3, 3000, '2025-07-19 15:18:44', '2025-07-19 15:18:44'),
(74, 37, 3, 4000, 200, 800000, '2025-07-19 16:54:16', '2025-07-19 16:54:16'),
(75, 37, 2, 1000, 300, 300000, '2025-07-19 16:54:16', '2025-07-19 16:54:16'),
(76, 37, 4, 5000, 100, 500000, '2025-07-19 16:54:16', '2025-07-19 16:54:16'),
(77, 38, 3, 4000, 255, 1020000, '2025-07-20 14:18:24', '2025-07-20 14:18:24'),
(78, 39, 5, 12000, 1, 12000, '2025-07-20 14:24:39', '2025-07-20 14:24:39'),
(79, 40, 6, 11000, 2, 22000, '2025-07-20 14:26:25', '2025-07-20 14:26:25'),
(80, 40, 3, 4000, 4, 16000, '2025-07-20 14:26:25', '2025-07-20 14:26:25'),
(81, 40, 2, 1000, 6, 6000, '2025-07-20 14:26:25', '2025-07-20 14:26:25'),
(82, 40, 6, 11000, 2, 22000, '2025-07-20 14:26:25', '2025-07-20 14:26:25'),
(83, 40, 3, 4000, 4, 16000, '2025-07-20 14:26:25', '2025-07-20 14:26:25'),
(84, 40, 2, 1000, 6, 6000, '2025-07-20 14:26:25', '2025-07-20 14:26:25'),
(85, 41, 3, 4000, 5, 20000, '2025-07-20 14:26:58', '2025-07-20 14:26:58'),
(86, 42, 13, 4000, 250, 1000000, '2025-07-26 03:42:09', '2025-07-26 03:42:09'),
(87, 43, 16, 11000, 2, 22000, '2025-08-01 17:05:14', '2025-08-01 17:05:14'),
(88, 43, 14, 5000, 6, 30000, '2025-08-01 17:05:14', '2025-08-01 17:05:14'),
(89, 43, 12, 1000, 4, 4000, '2025-08-01 17:05:14', '2025-08-01 17:05:14'),
(90, 43, 13, 4000, 2, 8000, '2025-08-01 17:05:14', '2025-08-01 17:05:14'),
(93, 44, 13, 4000, 80, 320000, '2025-08-03 20:22:31', '2025-08-03 20:22:31'),
(94, 46, 19, 0, 500, 0, '2025-08-05 03:08:03', '2025-08-05 03:08:03'),
(95, 46, 20, 0, 500, 0, '2025-08-05 03:08:03', '2025-08-05 03:08:03');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pic_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `pic_name`, `email`, `phone`, `notes`, `address`, `photo`, `created_at`, `updated_at`) VALUES
(2, 'Agendakota', 'John Connor', 'riyan.satria.619@gmail.com', '0881036183076', NULL, 'Jalan Bumiarjo 5 No. 11', '445555_Frame 8 (15).png', '2025-07-13 03:38:50', '2025-07-13 09:33:56'),
(3, 'Medical Tourism', 'Sam', 'sam@mti.id', '0812121212', NULL, 'di rumah', '698904_Frame 5 (3).png', '2025-07-13 04:10:24', '2025-07-13 09:26:22'),
(4, 'Produksi Pusat', 'Pusat', 'fauzan.widyanto1@gmail.com', '083849575737', NULL, 'kapas madya 3i/4', NULL, '2025-08-05 03:07:10', '2025-08-05 03:07:10');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `current_access` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `current_access`, `name`, `email`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 2, 'Ngademin', 'admin@admin.com', '$2y$12$ADQJS0uU7SbZ7xnQNBRyBuS7zQ5HfitZG5hBo4kXI4J7kGTVOg.WO', NULL, '2025-07-12 11:21:28', '2025-08-05 03:11:10'),
(2, 6, 'Riyan', 'riyan.satria.619@gmail.com', '$2y$12$QzSPqg/CUzdTXZQlhkaO2uBrjTfHMWwQUsQ/5lpvZYmtZeWdGZF1a', NULL, '2025-07-13 07:09:37', '2025-08-04 09:19:01'),
(3, 7, 'Fauzan', 'fauzan@gmail.com', '$2y$12$nRh2ZWN56hTz5SxG/TR/V.WEzSJcWEbmXEZgAL.9B.2ao185caPfK', NULL, '2025-07-26 03:05:57', '2025-07-26 03:05:57');

-- --------------------------------------------------------

--
-- Table structure for table `user_accesses`
--

CREATE TABLE `user_accesses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_accesses`
--

INSERT INTO `user_accesses` (`id`, `user_id`, `role_id`, `branch_id`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, '2025-07-12 11:21:28', '2025-07-12 11:21:28'),
(2, 1, 1, 2, 1, '2025-07-12 11:21:28', '2025-07-12 11:21:28'),
(3, 2, 2, 2, 1, '2025-07-13 07:09:37', '2025-07-13 07:09:37'),
(5, 1, 1, 5, 1, '2025-07-16 14:05:53', '2025-07-16 14:05:53'),
(6, 2, 2, 1, 1, '2025-07-19 12:49:48', '2025-07-19 12:49:48'),
(7, 3, 2, 5, 1, '2025-07-26 03:05:57', '2025-07-26 03:05:57'),
(8, 3, 1, 1, 1, '2025-07-26 03:06:49', '2025-07-26 03:06:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `add_ons`
--
ALTER TABLE `add_ons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `add_ons_branch_id_index` (`branch_id`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `check_ins`
--
ALTER TABLE `check_ins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `check_ins_user_id_index` (`user_id`),
  ADD KEY `check_ins_branch_id_index` (`branch_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customers_branch_id_index` (`branch_id`);

--
-- Indexes for table `customer_customer_types`
--
ALTER TABLE `customer_customer_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_customer_types_customer_id_index` (`customer_id`),
  ADD KEY `customer_customer_types_customer_type_id_index` (`customer_type_id`);

--
-- Indexes for table `customer_types`
--
ALTER TABLE `customer_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_types_branch_id_index` (`branch_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_key_unique` (`key`);

--
-- Indexes for table `permission_roles`
--
ALTER TABLE `permission_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permission_roles_permission_id_role_id_unique` (`permission_id`,`role_id`),
  ADD KEY `permission_roles_role_id_foreign` (`role_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_branch_id_index` (`branch_id`),
  ADD KEY `products_slug_index` (`slug`);

--
-- Indexes for table `product_add_ons`
--
ALTER TABLE `product_add_ons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_add_ons_product_id_index` (`product_id`),
  ADD KEY `product_add_ons_addon_id_index` (`addon_id`);

--
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_categories_product_id_foreign` (`product_id`),
  ADD KEY `product_categories_category_id_foreign` (`category_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_images_product_id_index` (`product_id`);

--
-- Indexes for table `product_ingredients`
--
ALTER TABLE `product_ingredients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_ingredients_product_id_index` (`product_id`),
  ADD KEY `product_ingredients_ingredient_id_index` (`ingredient_id`);

--
-- Indexes for table `product_prices`
--
ALTER TABLE `product_prices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_prices_product_id_index` (`product_id`);

--
-- Indexes for table `purchasings`
--
ALTER TABLE `purchasings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchasings_branch_id_index` (`branch_id`),
  ADD KEY `purchasings_supplier_id_index` (`supplier_id`),
  ADD KEY `purchasings_recipient_index` (`recipient`),
  ADD KEY `purchasings_created_by_index` (`created_by`),
  ADD KEY `purchasings_inventory_id_index` (`inventory_id`);

--
-- Indexes for table `purchasing_products`
--
ALTER TABLE `purchasing_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchasing_products_purchasing_id_index` (`purchasing_id`),
  ADD KEY `purchasing_products_product_id_index` (`product_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviews_sales_id_index` (`sales_id`),
  ADD KEY `reviews_branch_id_index` (`branch_id`),
  ADD KEY `reviews_customer_id_index` (`customer_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_branch_id_index` (`branch_id`),
  ADD KEY `sales_user_id_index` (`user_id`),
  ADD KEY `sales_customer_id_index` (`customer_id`),
  ADD KEY `sales_movement_id_index` (`movement_id`);

--
-- Indexes for table `sales_items`
--
ALTER TABLE `sales_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_items_sales_id_index` (`sales_id`),
  ADD KEY `sales_items_product_id_index` (`product_id`),
  ADD KEY `sales_items_price_id_index` (`price_id`);

--
-- Indexes for table `sales_item_addons`
--
ALTER TABLE `sales_item_addons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_item_addons_item_id_index` (`item_id`),
  ADD KEY `sales_item_addons_addon_id_index` (`addon_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_movements_branch_id_index` (`branch_id`),
  ADD KEY `stock_movements_supplier_id_index` (`supplier_id`),
  ADD KEY `stock_movements_user_id_index` (`user_id`),
  ADD KEY `stock_movements_purchasing_id_index` (`purchasing_id`),
  ADD KEY `stock_movements_branch_id_destination_index` (`branch_id_destination`),
  ADD KEY `stock_movements_movement_id_ref_index` (`movement_id_ref`),
  ADD KEY `stock_movements_sales_id_index` (`sales_id`);

--
-- Indexes for table `stock_movement_products`
--
ALTER TABLE `stock_movement_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_movement_products_movement_id_index` (`movement_id`),
  ADD KEY `stock_movement_products_product_id_index` (`product_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_current_access_index` (`current_access`);

--
-- Indexes for table `user_accesses`
--
ALTER TABLE `user_accesses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_accesses_user_id_role_id_branch_id_unique` (`user_id`,`role_id`,`branch_id`),
  ADD KEY `user_accesses_role_id_foreign` (`role_id`),
  ADD KEY `user_accesses_branch_id_foreign` (`branch_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `add_ons`
--
ALTER TABLE `add_ons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `check_ins`
--
ALTER TABLE `check_ins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `customer_customer_types`
--
ALTER TABLE `customer_customer_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `customer_types`
--
ALTER TABLE `customer_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `permission_roles`
--
ALTER TABLE `permission_roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `product_add_ons`
--
ALTER TABLE `product_add_ons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `product_ingredients`
--
ALTER TABLE `product_ingredients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `product_prices`
--
ALTER TABLE `product_prices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `purchasings`
--
ALTER TABLE `purchasings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `purchasing_products`
--
ALTER TABLE `purchasing_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `sales_items`
--
ALTER TABLE `sales_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `sales_item_addons`
--
ALTER TABLE `sales_item_addons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `stock_movements`
--
ALTER TABLE `stock_movements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `stock_movement_products`
--
ALTER TABLE `stock_movement_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_accesses`
--
ALTER TABLE `user_accesses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `add_ons`
--
ALTER TABLE `add_ons`
  ADD CONSTRAINT `add_ons_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `check_ins`
--
ALTER TABLE `check_ins`
  ADD CONSTRAINT `check_ins_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `check_ins_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `customers_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `customer_customer_types`
--
ALTER TABLE `customer_customer_types`
  ADD CONSTRAINT `customer_customer_types_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_customer_types_customer_type_id_foreign` FOREIGN KEY (`customer_type_id`) REFERENCES `customer_types` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `customer_types`
--
ALTER TABLE `customer_types`
  ADD CONSTRAINT `customer_types_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `permission_roles`
--
ALTER TABLE `permission_roles`
  ADD CONSTRAINT `permission_roles_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `permission_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_add_ons`
--
ALTER TABLE `product_add_ons`
  ADD CONSTRAINT `product_add_ons_addon_id_foreign` FOREIGN KEY (`addon_id`) REFERENCES `add_ons` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_add_ons_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD CONSTRAINT `product_categories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_categories_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_ingredients`
--
ALTER TABLE `product_ingredients`
  ADD CONSTRAINT `product_ingredients_ingredient_id_foreign` FOREIGN KEY (`ingredient_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_ingredients_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_prices`
--
ALTER TABLE `product_prices`
  ADD CONSTRAINT `product_prices_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchasings`
--
ALTER TABLE `purchasings`
  ADD CONSTRAINT `purchasings_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchasings_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchasings_inventory_id_foreign` FOREIGN KEY (`inventory_id`) REFERENCES `stock_movements` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `purchasings_recipient_foreign` FOREIGN KEY (`recipient`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `purchasings_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchasing_products`
--
ALTER TABLE `purchasing_products`
  ADD CONSTRAINT `purchasing_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchasing_products_purchasing_id_foreign` FOREIGN KEY (`purchasing_id`) REFERENCES `purchasings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_sales_id_foreign` FOREIGN KEY (`sales_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sales_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sales_movement_id_foreign` FOREIGN KEY (`movement_id`) REFERENCES `stock_movements` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sales_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `sales_items`
--
ALTER TABLE `sales_items`
  ADD CONSTRAINT `sales_items_price_id_foreign` FOREIGN KEY (`price_id`) REFERENCES `product_prices` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sales_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sales_items_sales_id_foreign` FOREIGN KEY (`sales_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sales_item_addons`
--
ALTER TABLE `sales_item_addons`
  ADD CONSTRAINT `sales_item_addons_addon_id_foreign` FOREIGN KEY (`addon_id`) REFERENCES `add_ons` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sales_item_addons_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `sales_items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD CONSTRAINT `stock_movements_branch_id_destination_foreign` FOREIGN KEY (`branch_id_destination`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `stock_movements_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_movements_movement_id_ref_foreign` FOREIGN KEY (`movement_id_ref`) REFERENCES `stock_movements` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `stock_movements_purchasing_id_foreign` FOREIGN KEY (`purchasing_id`) REFERENCES `purchasings` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `stock_movements_sales_id_foreign` FOREIGN KEY (`sales_id`) REFERENCES `sales` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `stock_movements_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `stock_movements_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `stock_movement_products`
--
ALTER TABLE `stock_movement_products`
  ADD CONSTRAINT `stock_movement_products_movement_id_foreign` FOREIGN KEY (`movement_id`) REFERENCES `stock_movements` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_movement_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_current_access_foreign` FOREIGN KEY (`current_access`) REFERENCES `user_accesses` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_accesses`
--
ALTER TABLE `user_accesses`
  ADD CONSTRAINT `user_accesses_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_accesses_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_accesses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
