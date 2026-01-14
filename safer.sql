-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 27, 2025 at 08:23 PM
-- Server version: 8.4.6-6
-- PHP Version: 8.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `safer`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `hotel_id` bigint UNSIGNED NOT NULL,
  `room_id` bigint UNSIGNED DEFAULT NULL,
  `check_in_date` date NOT NULL,
  `check_out_date` date NOT NULL,
  `guests_count` int NOT NULL DEFAULT '1',
  `rooms_count` int NOT NULL DEFAULT '1',
  `total_price` decimal(10,2) NOT NULL,
  `price_per_night` decimal(10,2) NOT NULL,
  `nights_count` int NOT NULL DEFAULT '1',
  `status` enum('pending','confirmed','checked_in','checked_out','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `admin_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `booking_reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `hotel_id`, `room_id`, `check_in_date`, `check_out_date`, `guests_count`, `rooms_count`, `total_price`, `price_per_night`, `nights_count`, `status`, `notes`, `admin_notes`, `booking_reference`, `created_at`, `updated_at`) VALUES
(1, 7, 1, 1, '2025-12-25', '2025-12-27', 2, 1, 488.00, 244.00, 2, 'pending', 'Special request', NULL, 'BK-6944886946E21', '2025-12-18 23:04:09', '2025-12-18 23:04:09');

-- --------------------------------------------------------

--
-- Table structure for table `buses`
--

CREATE TABLE `buses` (
  `id` bigint UNSIGNED NOT NULL,
  `name_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_seats` int NOT NULL,
  `type` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'standard',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `buses`
--

INSERT INTO `buses` (`id`, `name_ar`, `name_en`, `total_seats`, `type`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Glenna Fuller', 'Lee Ellison', 20, 'standard', 1, '2025-12-01 01:11:36', '2025-12-25 22:16:50');

-- --------------------------------------------------------

--
-- Table structure for table `bus_seats`
--

CREATE TABLE `bus_seats` (
  `id` bigint UNSIGNED NOT NULL,
  `service_request_id` bigint UNSIGNED NOT NULL,
  `trip_id` bigint UNSIGNED NOT NULL,
  `seat_number` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bus_seats`
--

INSERT INTO `bus_seats` (`id`, `service_request_id`, `trip_id`, `seat_number`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 4, '2025-12-24 19:44:11', '2025-12-24 19:44:11'),
(2, 1, 1, 5, '2025-12-24 19:44:11', '2025-12-24 19:44:11'),
(3, 1, 1, 6, '2025-12-24 19:44:11', '2025-12-24 19:44:11');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_links`
--

CREATE TABLE `contact_links` (
  `id` bigint UNSIGNED NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact_links`
--

INSERT INTO `contact_links` (`id`, `type`, `title_ar`, `title_en`, `url`, `icon`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Phone', '+966123456789', '+966123456789', '+966123456789', NULL, 1, NULL, NULL),
(2, 'Email', ' mailto:info@saferplus.net', ' mailto:info@saferplus.net', ' mailto:info@saferplus.net', NULL, 1, NULL, NULL),
(3, 'Facebook', 'Facebook', 'Facebook', 'www.facebook.com', NULL, 1, NULL, NULL),
(4, 'Website', 'Website', 'Website', 'www.Website.com', NULL, 1, NULL, NULL),
(5, 'customerService', 'customerService', 'customerService', '01223239488023', NULL, 1, NULL, NULL),
(6, 'whatsapp', 'whatsapp', 'whatsapp', '012032324043', NULL, 1, NULL, NULL),
(7, 'instagram', 'instagram', 'instagram', 'www.instagram.com', NULL, 1, NULL, NULL),
(8, 'x', 'x', 'x', 'www.x.com', NULL, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE `conversations` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `admin_id` bigint UNSIGNED DEFAULT NULL,
  `hotel_manager_id` bigint UNSIGNED DEFAULT NULL,
  `hotel_id` bigint UNSIGNED DEFAULT NULL,
  `status` enum('open','closed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `last_message_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `conversations`
--

INSERT INTO `conversations` (`id`, `user_id`, `admin_id`, `hotel_manager_id`, `hotel_id`, `status`, `last_message_at`, `created_at`, `updated_at`) VALUES
(1, 2, 1, NULL, NULL, 'open', '2025-11-15 21:52:03', '2025-11-15 21:51:56', '2025-11-15 21:52:47'),
(2, 5, 1, NULL, NULL, 'open', NULL, '2025-12-14 12:43:16', '2025-12-14 12:43:16');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` bigint UNSIGNED NOT NULL,
  `name_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `location_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `location_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `location_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lat` decimal(10,8) DEFAULT NULL,
  `lng` decimal(11,8) DEFAULT NULL,
  `category` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'general',
  `image_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event_date` datetime NOT NULL,
  `description_ar` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `description_en` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `available_tickets` int NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `name_ar`, `name_en`, `location_ar`, `location_en`, `location_url`, `lat`, `lng`, `category`, `image_url`, `event_date`, `description_ar`, `description_en`, `price`, `available_tickets`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Test', 'تسيت', 'Test', 'تسيت', 'https://www.google.com/maps/place/Gardenia+City,+Nasr+City,+Cairo+Governorate/@30.0646733,31.385397,15z/data=!3m1!4b1!4m6!3m5!1s0x14583', 30.06467300, 31.38539700, 'general', 'none', '2025-12-26 21:58:22', 'for all users ', 'for all users ', 310.00, 50, 1, NULL, NULL),
(2, 'خيل', 'Hourse', 'Dxfhxhfcjtdut', 'Iyfiyfiyfiy', NULL, NULL, NULL, 'general', NULL, '2025-12-26 00:18:00', 'Uvhvycy', NULL, 1500.00, 6, 1, '2025-12-25 22:19:01', '2025-12-25 22:19:01');

-- --------------------------------------------------------

--
-- Table structure for table `event_tickets`
--

CREATE TABLE `event_tickets` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `event_id` bigint UNSIGNED NOT NULL,
  `tickets_count` int NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `ticket_reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `event_tickets`
--

INSERT INTO `event_tickets` (`id`, `user_id`, `event_id`, `tickets_count`, `total_price`, `status`, `notes`, `ticket_reference`, `created_at`, `updated_at`) VALUES
(1, 5, 1, 6, 1860.00, 'pending', 'Need a child seat', 'ET-694C47DCEA3EC', '2025-12-24 20:06:52', '2025-12-24 20:06:52');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` bigint UNSIGNED NOT NULL,
  `question_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `question_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer_ar` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer_en` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `order_column` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `question_ar`, `question_en`, `answer_ar`, `answer_en`, `is_active`, `order_column`, `created_at`, `updated_at`) VALUES
(1, 'سؤال أول', 'First question', 'إجابة أولى', 'First answer', 1, 1, '2025-12-24 10:16:48', '2025-12-24 10:16:48'),
(2, 'سؤال ثاني', 'Second question', 'إجابة ثانية', 'Second answer', 1, 2, '2025-12-24 10:16:48', '2025-12-24 10:16:48');

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `favoritable_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`id`, `user_id`, `favoritable_id`, `created_at`, `updated_at`) VALUES
(1, 5, 1, '2025-12-15 19:58:21', '2025-12-15 19:58:21'),
(44, 5, 2, '2025-12-24 18:36:01', '2025-12-24 18:36:01'),
(58, 7, 1, '2025-12-25 16:38:58', '2025-12-25 16:38:58');

-- --------------------------------------------------------

--
-- Table structure for table `hotels`
--

CREATE TABLE `hotels` (
  `id` bigint UNSIGNED NOT NULL,
  `name_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_ar` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_en` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `province_id` bigint UNSIGNED NOT NULL,
  `type` enum('hotel','hostel','spa','hotel_apartment') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'hotel',
  `website_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `about_info_ar` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `about_info_en` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'مصر',
  `rate` tinyint NOT NULL DEFAULT '2',
  `services` json DEFAULT NULL,
  `lat` decimal(10,2) DEFAULT '30.02',
  `lang` decimal(10,2) DEFAULT '31.02'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hotels`
--

INSERT INTO `hotels` (`id`, `name_ar`, `name_en`, `address_ar`, `address_en`, `province_id`, `type`, `website_url`, `about_info_ar`, `about_info_en`, `is_active`, `created_at`, `updated_at`, `country`, `rate`, `services`, `lat`, `lang`) VALUES
(1, 'Bruno Berry', 'Jorden Herman', 'Vero ut ipsa et vel', 'Est perferendis iust', 22, 'hotel', 'https://www.zyrimynihes.co.uk', 'Est consectetur te', 'Sunt quis aliquam ne', 1, '2025-11-15 21:29:27', '2025-12-19 16:50:16', 'مصر', 4, '[\"wifi\", \"parking\", \"pool\"]', 26.82, 30.80),
(2, 'هلنان دريم', 'Helnan dream', '6 october', 'October', 3, 'hotel_apartment', NULL, NULL, NULL, 1, '2025-12-12 11:52:30', '2025-12-12 11:52:30', 'تركيا', 2, '[\"wifi\", \"parking\", \"pool\"]', 31.00, 30.80),
(3, 'Fdffc', 'Gfcfcc', 'Vgccc', 'Cccc', 15, 'spa', 'https://saferplus.net/hotel/hotels/create', 'Vgccc', 'Cccc', 1, '2025-12-12 19:49:43', '2025-12-12 19:49:43', 'السعودية', 3, '[\"wifi\", \"parking\", \"pool\", \"food\", \"sports_center\", \"elevator\", \"social_rooms\", \"opening\"]', 31.00, 30.80),
(4, 'ريكسوس', 'Rexos', 'مدينه ملوي', 'Aeeefv', 8, 'hostel', 'https://saferplus.net/hotel/hotels/create', 'احنا زي الفل', 'We are good', 1, '2025-12-14 23:33:57', '2025-12-18 11:14:10', 'مصر', 2, '[\"wifi\", \"parking\", \"pool\"]', 30.04, 31.24),
(5, 'Vladimir Rojas', 'Malachi Love', 'Laboris sequi aut ma', 'Qui quas consequat', 5, 'hotel', 'https://www.himoj.us', 'Architecto animi do', 'Labore proident rep', 1, '2025-12-17 21:09:15', '2025-12-18 10:36:58', 'فرنسا', 4, '[\"wifi\", \"parking\", \"pool\", \"food\", \"sports_center\", \"elevator\", \"social_rooms\", \"opening\"]', 30.04, 31.04),
(6, 'هيلتون', 'Hilton', 'القاهرة', 'Cairo', 1, 'hostel', NULL, NULL, NULL, 1, '2025-12-19 16:53:20', '2025-12-19 17:14:59', 'Egypt', 2, '[\"wifi\", \"parking\", \"pool\", \"food\", \"sports_center\", \"elevator\", \"social_rooms\", \"opening\"]', NULL, NULL),
(7, 'Audrey White', 'Unity Mcknight', 'Deserunt aut maiores', 'Esse est sit dolor', 27, 'spa', 'https://www.linilajyducysop.com', 'Velit at sit aut ut', 'Dolorum in non bland', 1, '2025-12-23 19:51:22', '2025-12-23 19:51:22', 'مصر', 2, '[\"wifi\", \"sports_center\", \"kitchen\", \"dishes_silverware\", \"hot_water_kettle\", \"crib\", \"smoke_alarm\", \"hangers\", \"iron\"]', 30.00, 31.21),
(8, 'Garrison Kemp', 'Aline Shepherd', 'Ducimus facere non', 'Vel assumenda dolor', 2, 'hotel_apartment', 'https://www.lefe.ca', 'Sed deserunt harum a', 'Hic deleniti volupta', 1, '2025-12-26 13:12:57', '2025-12-26 13:12:57', 'مصر', 5, '[\"wifi\", \"social_rooms\", \"hot_water_kettle\"]', 30.03, 31.11);

-- --------------------------------------------------------

--
-- Table structure for table `hotel_managers`
--

CREATE TABLE `hotel_managers` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `hotel_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hotel_managers`
--

INSERT INTO `hotel_managers` (`id`, `user_id`, `hotel_id`, `created_at`, `updated_at`) VALUES
(1, 2, 1, '2025-11-15 22:15:05', '2025-11-15 22:15:05'),
(2, 2, 3, '2025-12-12 19:49:43', '2025-12-12 19:49:43'),
(3, 2, 4, '2025-12-14 23:33:57', '2025-12-14 23:33:57'),
(4, 2, 5, '2025-12-17 21:09:15', '2025-12-17 21:09:15'),
(5, 2, 6, '2025-12-19 16:53:20', '2025-12-19 16:53:20'),
(6, 2, 7, '2025-12-23 19:51:22', '2025-12-23 19:51:22');

-- --------------------------------------------------------

--
-- Table structure for table `hotel_media`
--

CREATE TABLE `hotel_media` (
  `id` bigint UNSIGNED NOT NULL,
  `hotel_id` bigint UNSIGNED NOT NULL,
  `room_id` bigint UNSIGNED DEFAULT NULL,
  `type` enum('image','video') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_column` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hotel_media`
--

INSERT INTO `hotel_media` (`id`, `hotel_id`, `room_id`, `type`, `file_path`, `order_column`, `created_at`, `updated_at`) VALUES
(6, 4, NULL, 'image', 'hotels/4/images/lCjczfvNgEoTPCS1Au7vCiWEF8PyLP5YoA3xZALt.jpg', 0, '2025-12-17 20:49:17', '2025-12-17 20:49:17'),
(7, 3, NULL, 'image', 'hotels/3/images/yhpeNGeASEBRzjDBDYOiF4kpW3kSzAwF9Pza2ByZ.jpg', 0, '2025-12-17 20:55:28', '2025-12-17 20:55:28'),
(8, 3, NULL, 'image', 'hotels/3/images/LZQ47JxjC5BuNzgpeAjHjX0AtJ6uFb4R2Q1woBdx.jpg', 1, '2025-12-17 20:56:48', '2025-12-17 20:56:48'),
(9, 1, NULL, 'image', 'hotels/1/images/OASBkN98nx3dsfT96x02FMn4IPX5sF4jArvcuuyV.jpg', 0, '2025-12-17 21:02:41', '2025-12-17 21:02:41'),
(10, 1, NULL, 'image', '0', 1, '2025-12-17 21:06:04', '2025-12-17 21:06:04'),
(11, 1, NULL, 'image', '0', 2, '2025-12-17 21:06:41', '2025-12-17 21:06:41'),
(12, 5, NULL, 'image', 'hotels/5/images/vGyA26wJrchhrZvqo5Dc8tRmkzK6jVPmsGSX5gbi.png', 0, '2025-12-17 21:09:15', '2025-12-17 21:09:15'),
(13, 5, NULL, 'image', 'hotels/5/images/tFAKXwiONk0s5g7NKWGBQZgwUjU3xuoVNos1eAOT.png', 1, '2025-12-17 21:15:15', '2025-12-17 21:15:15'),
(14, 1, NULL, 'image', 'hotels/1/images/1KGCQ9aX38tT6ZjpvQ6867C4neOXhFctpnovtKnZ.jpg', 3, '2025-12-19 16:50:16', '2025-12-19 16:50:16'),
(15, 4, 8, 'image', 'hotels/4/rooms/8/images/QyQe1WNGWb9BRH7YPJuSOuNzZyJ7GzlzMtlRtLAZ.png', 0, '2025-12-21 19:18:42', '2025-12-21 19:18:42'),
(16, 7, NULL, 'image', 'hotels/7/images/7e29yb8VPuyohLJTElnV9QOySy9z4PR7jB0YljKd.jpg', 0, '2025-12-23 19:51:22', '2025-12-23 19:51:22'),
(17, 7, NULL, 'image', 'hotels/7/images/tnDsOwTqfGfG95yAxS4rdPPIYH9tRMdVCtuOTG8r.jpg', 1, '2025-12-23 19:51:22', '2025-12-23 19:51:22'),
(19, 7, 10, 'image', 'hotels/7/rooms/10/images/NBmmJmwLv6hTYfP24MEjpHxyLFjVf5oWYlTjD3ef.png', 1, '2025-12-23 20:00:12', '2025-12-23 20:00:12'),
(20, 8, NULL, 'image', 'hotels/8/images/sxEojI27bTACnKxon3F1yvYLe53vocWsZQPirAiD.jpg', 0, '2025-12-26 13:12:57', '2025-12-26 13:12:57'),
(21, 8, NULL, 'image', 'hotels/8/images/g2MbQ8R1bFkS6SRnzPIum9uZ05r4kmPVLCy2o02D.jpg', 1, '2025-12-26 13:12:57', '2025-12-26 13:12:57'),
(22, 8, NULL, 'image', 'hotels/8/images/1aGI6CCLIMFWYCIvlL72XbU8FnIyl1zM1lFt1HFX.jpg', 2, '2025-12-26 13:12:57', '2025-12-26 13:12:57'),
(23, 7, 15, 'image', 'hotels/7/rooms/15/images/694fd6500b92f.png', 1, '2025-12-27 12:51:28', '2025-12-27 12:51:28'),
(24, 7, 16, 'image', 'hotels/7/rooms/16/images/694fd65013394.png', 1, '2025-12-27 12:51:28', '2025-12-27 12:51:28');

-- --------------------------------------------------------

--
-- Table structure for table `hotel_rooms`
--

CREATE TABLE `hotel_rooms` (
  `id` bigint UNSIGNED NOT NULL,
  `hotel_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'standard',
  `price_per_night` decimal(10,2) NOT NULL,
  `cleaning_fee` decimal(10,2) DEFAULT '0.00',
  `service_fee` decimal(10,2) DEFAULT '0.00',
  `beds_count` int NOT NULL DEFAULT '1',
  `bathrooms_count` int NOT NULL DEFAULT '1',
  `rooms_count` int NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `checkin_time` time NOT NULL DEFAULT '14:00:00',
  `checkout_time` time NOT NULL DEFAULT '12:00:00',
  `blocked_slots` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `services` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hotel_rooms`
--

INSERT INTO `hotel_rooms` (`id`, `hotel_id`, `name`, `type`, `price_per_night`, `cleaning_fee`, `service_fee`, `beds_count`, `bathrooms_count`, `rooms_count`, `is_active`, `checkin_time`, `checkout_time`, `blocked_slots`, `created_at`, `updated_at`, `services`) VALUES
(1, 1, NULL, 'standard', 244.00, 0.00, 0.00, 2, 3, 2, 1, '14:00:00', '12:00:00', NULL, '2025-11-15 21:31:25', '2025-11-15 21:31:25', NULL),
(2, 2, NULL, 'standard', 1500.00, 0.00, 0.00, 1, 1, 1, 1, '14:00:00', '12:00:00', NULL, '2025-12-12 11:53:30', '2025-12-12 11:53:30', NULL),
(3, 2, NULL, 'standard', 1400.00, 0.00, 0.00, 1, 1, 1, 1, '14:00:00', '12:00:00', NULL, '2025-12-12 11:53:56', '2025-12-12 11:53:56', NULL),
(4, 1, NULL, 'standard', 2000.00, 0.00, 0.00, 3, 1, 1, 1, '14:00:00', '12:00:00', NULL, '2025-12-12 19:50:34', '2025-12-12 19:50:34', NULL),
(5, 1, NULL, 'standard', 5000.00, 0.00, 0.00, 3, 2, 1, 1, '14:00:00', '12:00:00', NULL, '2025-12-12 19:50:56', '2025-12-12 19:50:56', NULL),
(7, 4, NULL, 'standard', 3000.00, 0.00, 0.00, 1, 1, 1, 1, '14:00:00', '12:00:00', NULL, '2025-12-19 16:59:51', '2025-12-19 16:59:51', NULL),
(8, 4, NULL, 'standard', 200.00, 0.00, 0.00, 1, 1, 1, 1, '14:00:00', '12:00:00', NULL, '2025-12-21 19:18:42', '2025-12-21 19:18:42', NULL),
(9, 4, NULL, 'standard', 122.00, 0.00, 0.00, 1, 1, 1, 1, '14:00:00', '12:00:00', '[{\"to_date\": \"2025-12-25\", \"to_time\": \"12:49\", \"from_date\": \"2025-12-21\", \"from_time\": \"21:49\"}]', '2025-12-21 19:49:51', '2025-12-21 19:49:51', NULL),
(10, 7, NULL, 'standard', 954.00, 0.00, 0.00, 2, 2, 3, 1, '08:16:00', '10:20:00', '[{\"to_date\": \"2025-12-31\", \"to_time\": \"06:11\", \"from_date\": \"2025-12-23\", \"from_time\": \"01:19\"}]', '2025-12-23 20:00:12', '2025-12-23 20:00:12', NULL),
(12, 4, NULL, 'standard', 122.00, 0.00, 0.00, 1, 1, 1, 1, '14:00:00', '12:00:00', '[{\"to_date\": \"2025-12-25\", \"to_time\": \"12:49\", \"from_date\": \"2025-12-21\", \"from_time\": \"21:49\"}]', '2025-12-27 12:10:07', '2025-12-27 12:10:07', NULL),
(13, 1, NULL, 'standard', 244.00, 0.00, 0.00, 2, 3, 3, 1, '14:00:00', '12:00:00', NULL, '2025-12-27 12:30:11', '2025-12-27 12:30:11', NULL),
(14, 1, NULL, 'standard', 244.00, 0.00, 0.00, 2, 3, 3, 1, '14:00:00', '12:00:00', NULL, '2025-12-27 12:30:11', '2025-12-27 12:30:11', NULL),
(15, 7, NULL, 'standard', 954.00, 0.00, 0.00, 2, 3, 3, 1, '08:16:00', '10:20:00', '[{\"to_date\": \"2025-12-31\", \"to_time\": \"06:11\", \"from_date\": \"2025-12-23\", \"from_time\": \"01:19\"}]', '2025-12-27 12:51:28', '2025-12-27 12:51:28', NULL),
(16, 7, NULL, 'standard', 954.00, 0.00, 0.00, 2, 3, 3, 1, '08:16:00', '10:20:00', '[{\"to_date\": \"2025-12-31\", \"to_time\": \"06:11\", \"from_date\": \"2025-12-23\", \"from_time\": \"01:19\"}]', '2025-12-27 12:51:28', '2025-12-27 12:51:28', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` bigint UNSIGNED NOT NULL,
  `conversation_id` bigint UNSIGNED NOT NULL,
  `sender_id` bigint UNSIGNED NOT NULL,
  `sender_type` enum('user','admin') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `file_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('text','file') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `conversation_id`, `sender_id`, `sender_type`, `message`, `file_path`, `file_name`, `type`, `is_read`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'admin', 'نمنمنم', NULL, NULL, 'text', 0, '2025-11-15 21:52:03', '2025-11-15 21:52:03');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_11_13_000000_add_is_admin_to_users_table', 1),
(5, '2025_11_14_000001_create_contact_links_table', 2),
(6, '2025_11_14_000002_create_policies_table', 2),
(7, '2025_11_14_000003_create_faqs_table', 2),
(11, '2025_11_15_230828_create_provinces_table', 3),
(12, '2025_11_15_230829_create_hotels_table', 3),
(13, '2025_11_15_230830_create_hotel_rooms_table', 4),
(14, '2025_11_15_230831_create_hotel_media_table', 4),
(15, '2025_11_15_232343_add_bilingual_fields_to_hotels_table', 5),
(16, '2025_11_15_232826_create_personal_access_tokens_table', 6),
(17, '2025_11_15_232826_add_phone_to_users_table', 7),
(18, '2025_11_15_233759_add_is_active_to_users_table', 8),
(19, '2025_11_15_234543_create_conversations_table', 9),
(20, '2025_11_15_234543_create_messages_table', 9),
(21, '2025_11_16_000112_create_hotel_managers_table', 10),
(22, '2025_11_16_000129_add_hotel_manager_id_to_conversations_table', 10),
(23, '2025_11_30_233435_create_bookings_table', 11),
(24, '2025_11_30_233436_create_payments_table', 11),
(25, '2025_12_01_003841_create_buses_table', 12),
(27, '2025_12_01_003842_create_trips_table', 13),
(28, '2025_12_01_003844_create_private_cars_table', 13),
(29, '2025_12_01_003846_create_service_requests_table', 13),
(30, '2025_12_01_003847_create_bus_seats_table', 14),
(31, '2025_12_01_004652_create_events_table', 14),
(32, '2025_12_01_004653_create_event_tickets_table', 14),
(33, '2025_12_12_160508_add_image_to_users_table', 15),
(36, '2025_12_15_102111_add_type_to_hotels_table', 16),
(37, '2025_12_16_145634_add_coordinates_to_hotels_table', 17),
(38, '2025_12_21_193840_add_time_slots_to_hotel_rooms_table', 18),
(39, '2025_12_26_132148_add_services_to_hotel_rooms_table', 19);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `message` text COLLATE utf8mb4_general_ci NOT NULL,
  `type` varchar(100) COLLATE utf8mb4_general_ci DEFAULT 'general',
  `data` json DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `type`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
(1, 5, 'your bookning completed', 'your bookning completed', 'general', '[\"your bookning completed\"]', NULL, NULL, NULL),
(2, 1, 'Repudiandae occaecat', 'Error aliquam velit', 'booking', NULL, NULL, '2025-12-24 21:11:01', '2025-12-24 21:11:01'),
(3, 2, 'Repudiandae occaecat', 'Error aliquam velit', 'booking', NULL, NULL, '2025-12-24 21:11:01', '2025-12-24 21:11:01'),
(4, 3, 'Repudiandae occaecat', 'Error aliquam velit', 'booking', NULL, NULL, '2025-12-24 21:11:01', '2025-12-24 21:11:01'),
(5, 4, 'Repudiandae occaecat', 'Error aliquam velit', 'booking', NULL, NULL, '2025-12-24 21:11:01', '2025-12-24 21:11:01'),
(6, 5, 'Repudiandae occaecat', 'Error aliquam velit', 'booking', NULL, NULL, '2025-12-24 21:11:01', '2025-12-24 21:11:01'),
(7, 6, 'Repudiandae occaecat', 'Error aliquam velit', 'booking', NULL, NULL, '2025-12-24 21:11:01', '2025-12-24 21:11:01'),
(8, 7, 'Repudiandae occaecat', 'Error aliquam velit', 'booking', NULL, NULL, '2025-12-24 21:11:01', '2025-12-24 21:11:01');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint UNSIGNED NOT NULL,
  `booking_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` enum('cash','card','bank_transfer','online','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash',
  `status` enum('pending','completed','failed','refunded') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `transaction_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(6, 'App\\Models\\User', 6, 'auth_token', '9ee20bafecfa94bf046bb73e18129a8cda75d5a73d28b348ea4f597cf5f2279b', '[\"*\"]', NULL, NULL, '2025-12-15 09:02:13', '2025-12-15 09:02:13'),
(10, 'App\\Models\\User', 2, 'auth_token', 'ee91832eadf6df686e8d9f0d796e8dee6b450dcbb805e68afee527c116f2d586', '[\"*\"]', '2025-12-18 17:50:54', NULL, '2025-12-15 09:46:17', '2025-12-18 17:50:54'),
(11, 'App\\Models\\User', 5, 'auth_token', '81dbabd36e8df2ccf12a76e27e34df1702e9fb7d80a6dcba57572e290e49560d', '[\"*\"]', '2025-12-25 16:05:16', NULL, '2025-12-15 19:31:43', '2025-12-25 16:05:16'),
(18, 'App\\Models\\User', 7, 'auth_token', '32f0082efc6ca226bea5d986a67b5128b0e457f13e647bfebc351e4a1a7e6d0b', '[\"*\"]', '2025-12-27 18:08:00', NULL, '2025-12-23 10:06:53', '2025-12-27 18:08:00');

-- --------------------------------------------------------

--
-- Table structure for table `policies`
--

CREATE TABLE `policies` (
  `id` bigint UNSIGNED NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `body_ar` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `body_en` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `private_cars`
--

CREATE TABLE `private_cars` (
  `id` bigint UNSIGNED NOT NULL,
  `name_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `seats_count` int NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `max_speed` int DEFAULT NULL,
  `acceleration` decimal(5,2) DEFAULT NULL,
  `power` int DEFAULT NULL,
  `fuel_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'gasoline',
  `transmission` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'automatic',
  `notes_ar` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `notes_en` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `private_cars`
--

INSERT INTO `private_cars` (`id`, `name_ar`, `name_en`, `price`, `seats_count`, `image`, `max_speed`, `acceleration`, `power`, `fuel_type`, `transmission`, `notes_ar`, `notes_en`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Chanda Woodward', 'Arthur Kim', 896.00, 10, NULL, 89, 42.00, 93, 'gasoline', 'automatic', 'Saepe qui ut a quo d', 'Velit ipsum corrup', 1, '2025-12-01 01:11:23', '2025-12-01 01:11:23');

-- --------------------------------------------------------

--
-- Table structure for table `provinces`
--

CREATE TABLE `provinces` (
  `id` bigint UNSIGNED NOT NULL,
  `name_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `provinces`
--

INSERT INTO `provinces` (`id`, `name_ar`, `name_en`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'القاهرة', 'Cairo', 1, '2025-11-15 21:25:46', '2025-11-15 21:25:46'),
(2, 'الإسكندرية', 'Alexandria', 1, '2025-11-15 21:25:46', '2025-11-15 21:25:46'),
(3, 'الجيزة', 'Giza', 1, '2025-11-15 21:25:46', '2025-11-15 21:25:46'),
(4, 'الشرقية', 'Sharqia', 1, '2025-11-15 21:25:46', '2025-11-15 21:25:46'),
(5, 'الدقهلية', 'Dakahlia', 1, '2025-11-15 21:25:46', '2025-11-15 21:25:46'),
(6, 'البحيرة', 'Beheira', 1, '2025-11-15 21:25:46', '2025-11-15 21:25:46'),
(7, 'القليوبية', 'Qalyubia', 1, '2025-11-15 21:25:46', '2025-11-15 21:25:46'),
(8, 'المنيا', 'Minya', 1, '2025-11-15 21:25:46', '2025-11-15 21:25:46'),
(9, 'المنوفية', 'Monufia', 1, '2025-11-15 21:25:46', '2025-11-15 21:25:46'),
(10, 'أسيوط', 'Asyut', 1, '2025-11-15 21:25:46', '2025-11-15 21:25:46'),
(11, 'سوهاج', 'Sohag', 1, '2025-11-15 21:25:46', '2025-11-15 21:25:46'),
(12, 'قنا', 'Qena', 1, '2025-11-15 21:25:46', '2025-11-15 21:25:46'),
(13, 'أسوان', 'Aswan', 1, '2025-11-15 21:25:46', '2025-11-15 21:25:46'),
(14, 'الأقصر', 'Luxor', 1, '2025-11-15 21:25:46', '2025-11-15 21:25:46'),
(15, 'البحر الأحمر', 'Red Sea', 1, '2025-11-15 21:25:46', '2025-11-15 21:25:46'),
(16, 'الوادي الجديد', 'New Valley', 1, '2025-11-15 21:25:46', '2025-11-15 21:25:46'),
(17, 'مطروح', 'Matruh', 1, '2025-11-15 21:25:46', '2025-11-15 21:25:46'),
(18, 'شمال سيناء', 'North Sinai', 1, '2025-11-15 21:25:46', '2025-11-15 21:25:46'),
(19, 'جنوب سيناء', 'South Sinai', 1, '2025-11-15 21:25:46', '2025-11-15 21:25:46'),
(20, 'بورسعيد', 'Port Said', 1, '2025-11-15 21:25:46', '2025-11-15 21:25:46'),
(21, 'الإسماعيلية', 'Ismailia', 1, '2025-11-15 21:25:46', '2025-11-15 21:25:46'),
(22, 'السويس', 'Suez', 1, '2025-11-15 21:25:46', '2025-11-15 21:25:46'),
(23, 'دمياط', 'Damietta', 1, '2025-11-15 21:25:46', '2025-11-15 21:25:46'),
(24, 'كفر الشيخ', 'Kafr El Sheikh', 1, '2025-11-15 21:25:46', '2025-11-15 21:25:46'),
(25, 'الغربية', 'Gharbia', 1, '2025-11-15 21:25:46', '2025-11-15 21:25:46'),
(26, 'بني سويف', 'Beni Suef', 1, '2025-11-15 21:25:46', '2025-11-15 21:25:46'),
(27, 'الفيوم', 'Faiyum', 1, '2025-11-15 21:25:46', '2025-11-15 21:25:46');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `hotel_id` bigint UNSIGNED NOT NULL,
  `rating` int NOT NULL,
  `comment` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `hotel_id`, `rating`, `comment`, `created_at`, `updated_at`) VALUES
(1, 5, 2, 3, 'Good hotel', '2025-12-24 18:40:20', '2025-12-24 18:56:25');

-- --------------------------------------------------------

--
-- Table structure for table `service_requests`
--

CREATE TABLE `service_requests` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `service_type` enum('bus','private_car') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `trip_id` bigint UNSIGNED DEFAULT NULL,
  `bus_id` bigint UNSIGNED DEFAULT NULL,
  `departure_location_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `departure_location_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `arrival_location_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `arrival_location_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `passengers_count` int DEFAULT NULL,
  `trip_date` date DEFAULT NULL,
  `private_car_id` bigint UNSIGNED DEFAULT NULL,
  `duration_hours` int DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','in_progress','completed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `request_reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_requests`
--

INSERT INTO `service_requests` (`id`, `user_id`, `service_type`, `trip_id`, `bus_id`, `departure_location_ar`, `departure_location_en`, `arrival_location_ar`, `arrival_location_en`, `passengers_count`, `trip_date`, `private_car_id`, `duration_hours`, `start_date`, `total_price`, `status`, `notes`, `request_reference`, `created_at`, `updated_at`) VALUES
(1, 5, 'bus', 1, 1, 'Id totam officia ali', 'Laborum Provident', 'Quisquam temporibus', 'Omnis nulla labore i', 3, '2025-12-29', NULL, NULL, NULL, 2019.00, 'pending', 'Please reserve window seats if possible', 'SR-694C428B08BE5', '2025-12-24 19:44:11', '2025-12-24 19:44:11'),
(2, 5, 'private_car', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 6, '2025-12-26', 5376.00, 'pending', 'Need a child seat', 'SR-694C43478E8E0', '2025-12-24 19:47:19', '2025-12-24 19:47:19');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('13cfeSsSSMUFeHpMdi1OlFRpoknc9kELgjqAP3M2', NULL, '197.45.55.186', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUVZ6d3Y5MklGcGxDSFlaMkhzRG9rNXF0WGNuNUlIR2UwQnc0V1NlayI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHBzOi8vc2FmZXJwbHVzLm5ldC9ob3RlbC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1766751617),
('6rh2hBbBTVQ9XS2Y9cnvSDuVOnapVoHejtcthk1p', NULL, '182.40.104.255', 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiM2xtUmYzTnhJVXVSVzlsSE9Wc09rVk9MTElBWnR2d2Y4alhUMldFMiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHBzOi8vc2FmZXJwbHVzLm5ldCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1766744771),
('9FsD7zJuEcTi5pOURRM7CdT2xyrgCYERkKuFCBya', NULL, '197.45.55.186', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSHVoaTZmbk92UVRBWXFDSXdtSzVZZEo3SnZYMVVkTVQ5WkNBVXJXNCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHBzOi8vc2FmZXJwbHVzLm5ldC9ob3RlbC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1766750967),
('Aen0TlWqnflQouQfqyQ6ulO7ouLSiXFpTDVx5Zay', NULL, '197.45.55.186', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiMXhwY3VhNUQxRkpHdE90RTY5Qk90a1poMjJUWkRMamx5QXc0OVRUYyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1766751188),
('BSDWRHnEibEr4fy8JBzXqyHAKKaVhENAiGAg8y14', NULL, '197.45.55.186', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoialNsbVV3Zk1KT0sxczVxTDBGcTlyT3l1d0VhNHlWT3Z2cUpvVU5ZVSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHBzOi8vc2FmZXJwbHVzLm5ldC9hZG1pbi9sb2dpbiI7fX0=', 1766751053),
('QLPatDS6LOBOnssibij4BQN35tX4keg2wUr90lFW', NULL, '54.90.119.54', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/13.0.782.20 Safari/535.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVktJNWlXSGVsUWdXNTBrS2UwQnJPZW1kVVJKRWlMVjBWcm9USm1qQSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHBzOi8vc2FmZXJwbHVzLm5ldCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1766749271),
('zaYQFdj0JTOrwUxOqkAdMcrbE6RM1PegGiq7P11p', NULL, '197.45.55.186', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSWhUbkd3NGpPRnhzOVE3bDZWSmtLRTFhZmZvZnV5RFJrWGJJcE02dSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHBzOi8vc2FmZXJwbHVzLm5ldCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1766750959);

-- --------------------------------------------------------

--
-- Table structure for table `trips`
--

CREATE TABLE `trips` (
  `id` bigint UNSIGNED NOT NULL,
  `departure_location_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `departure_location_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `arrival_location_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `arrival_location_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bus_id` bigint UNSIGNED NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `trip_date` date NOT NULL,
  `trip_time` time NOT NULL,
  `duration_minutes` int NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `trips`
--

INSERT INTO `trips` (`id`, `departure_location_ar`, `departure_location_en`, `arrival_location_ar`, `arrival_location_en`, `bus_id`, `price`, `trip_date`, `trip_time`, `duration_minutes`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Id totam officia ali', 'Laborum Provident', 'Quisquam temporibus', 'Omnis nulla labore i', 1, 673.00, '2025-12-29', '18:40:00', 75, 1, '2025-12-01 01:17:04', '2025-12-01 01:17:04');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `image`, `email_verified_at`, `password`, `is_admin`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'مسؤول النظام', 'admin@hotel.com', NULL, NULL, NULL, '$2y$12$hNDcR9TQUGFtzQJ3lxSOpuPKHIWHjKgxW8Kgi/u6wWZcVp1eDfJnG', 1, 1, 'iNPf30NArdv1fQ6dr1H6KFjLa156mTE3CgIXoKA2Non7IwhOQiFbGmVqo5MW', '2025-11-13 21:51:20', '2025-11-15 21:50:12'),
(2, 'أحمد محمد', 'ahmed@example.com', '01234567890', NULL, NULL, '$2y$12$.aD6IZSyYnHt0e0C99Dbeup3I2AcierfKb5Aj/d.u.//.BQ988efy', 0, 1, 'r4FL7UjQVNEZyWXlquLvF162iJd6gvbA70gwd6oWkBbHRuDoImaMDrvja7zb', '2025-11-15 21:49:40', '2025-11-15 21:50:13'),
(3, 'فاطمة علي', 'fatima@example.com', '01234567891', NULL, NULL, '$2y$12$t4iFgrhVXl6Fbb4CUbO8Vufjj.o5RHK3xUkDw6o2vC9rfp0euaXDC', 0, 1, NULL, '2025-11-15 21:49:40', '2025-11-15 21:50:13'),
(4, 'خالد أحمد', 'khaled@example.com', '01234567892', NULL, NULL, '$2y$12$Vy0xE4hOp5o2LuY5cffR4.FpxyoY0Q7HQ/PH6smOoD6y09oTHbSb.', 0, 1, NULL, '2025-11-15 21:49:40', '2025-11-15 21:50:13'),
(5, 'Mostafa', 'mostafa@gmail.com', '01092702209', '1765556341_login.jpeg', NULL, '$2y$12$qs2FrlGbckmu/jJLctbVueesao9ou0owA/gyPtnHiUp3PTS2OaybW', 0, 1, NULL, '2025-12-12 15:49:38', '2025-12-12 16:19:01'),
(6, 'Ahmed Mohamed', 'ahmed2@example.com', '03234567890', NULL, NULL, '$2y$12$eRtEPb36hpZ6eDdc32UArOQ0fwKykmdnOlXq8BHMGgVpfkDQQ5d/.', 0, 1, NULL, '2025-12-15 09:02:13', '2025-12-15 09:02:13'),
(7, 'Ahmed Ibrahim', 'ahmed@gmail.com', '01003705602', NULL, NULL, '$2y$12$3dpa3ZAs3RPQO4QY8CnLSuyqgR4sqTvbsVdE/5RoS6g9p2sNIkfyS', 0, 1, NULL, '2025-12-15 09:17:00', '2025-12-15 13:31:19');

-- --------------------------------------------------------

--
-- Table structure for table `user_vouchers`
--

CREATE TABLE `user_vouchers` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `voucher_id` bigint UNSIGNED NOT NULL,
  `booking_id` bigint UNSIGNED DEFAULT NULL,
  `discount_amount` decimal(10,2) NOT NULL,
  `used_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vouchers`
--

CREATE TABLE `vouchers` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `title_ar` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `title_en` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description_ar` text COLLATE utf8mb4_general_ci,
  `description_en` text COLLATE utf8mb4_general_ci,
  `type` enum('percentage','fixed') COLLATE utf8mb4_general_ci NOT NULL,
  `value` decimal(10,2) NOT NULL,
  `min_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `max_discount` decimal(10,2) DEFAULT NULL,
  `usage_limit` int DEFAULT NULL,
  `used_count` int NOT NULL DEFAULT '0',
  `valid_from` timestamp NOT NULL,
  `valid_until` timestamp NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vouchers`
--

INSERT INTO `vouchers` (`id`, `code`, `title_ar`, `title_en`, `description_ar`, `description_en`, `type`, `value`, `min_amount`, `max_discount`, `usage_limit`, `used_count`, `valid_from`, `valid_until`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'WELCOME20', 'خصم الترحيب', 'Welcome Discount', 'احصل على خصم 20% على حجزك الأول', 'Get 20% discount on your first booking', 'percentage', 20.00, 100.00, 200.00, 100, 0, '2025-12-24 19:32:20', '2026-03-24 19:32:20', 1, '2025-12-24 19:32:20', '2025-12-24 19:32:20'),
(2, 'SAVE50', 'وفر 50 ريال', 'Save 50 SAR', 'خصم 50 ريال على الحجوزات أكثر من 300 ريال', '50 SAR discount on bookings over 300 SAR', 'fixed', 50.00, 300.00, NULL, 50, 0, '2025-12-24 19:32:20', '2026-01-24 19:32:20', 1, '2025-12-24 19:32:20', '2025-12-24 19:32:20'),
(3, 'WEEKEND15', 'خصم نهاية الأسبوع', 'Weekend Discount', 'خصم 15% على حجوزات نهاية الأسبوع', '15% discount on weekend bookings', 'percentage', 15.00, 200.00, 150.00, NULL, 0, '2025-12-24 19:32:20', '2026-02-24 19:32:20', 1, '2025-12-24 19:32:20', '2025-12-24 19:32:20');

-- --------------------------------------------------------

--
-- Table structure for table `wallets`
--

CREATE TABLE `wallets` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `currency` varchar(3) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'SAR',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wallets`
--

INSERT INTO `wallets` (`id`, `user_id`, `balance`, `currency`, `created_at`, `updated_at`) VALUES
(1, 5, 100.00, 'SAR', '2025-12-24 19:27:55', '2025-12-24 19:27:55');

-- --------------------------------------------------------

--
-- Table structure for table `wallet_transactions`
--

CREATE TABLE `wallet_transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `wallet_id` bigint UNSIGNED NOT NULL,
  `type` enum('credit','debit') COLLATE utf8mb4_general_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `reference_id` bigint UNSIGNED DEFAULT NULL,
  `reference_type` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bookings_booking_reference_unique` (`booking_reference`),
  ADD KEY `bookings_room_id_foreign` (`room_id`),
  ADD KEY `bookings_user_id_status_index` (`user_id`,`status`),
  ADD KEY `bookings_hotel_id_status_index` (`hotel_id`,`status`),
  ADD KEY `bookings_check_in_date_check_out_date_index` (`check_in_date`,`check_out_date`),
  ADD KEY `bookings_booking_reference_index` (`booking_reference`);

--
-- Indexes for table `buses`
--
ALTER TABLE `buses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bus_seats`
--
ALTER TABLE `bus_seats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bus_seats_trip_id_seat_number_index` (`trip_id`,`seat_number`),
  ADD KEY `bus_seats_service_request_id_index` (`service_request_id`);

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
-- Indexes for table `contact_links`
--
ALTER TABLE `contact_links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `conversations_admin_id_foreign` (`admin_id`),
  ADD KEY `conversations_user_id_status_index` (`user_id`,`status`),
  ADD KEY `conversations_last_message_at_index` (`last_message_at`),
  ADD KEY `conversations_hotel_manager_id_foreign` (`hotel_manager_id`),
  ADD KEY `conversations_hotel_id_foreign` (`hotel_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `events_event_date_is_active_index` (`event_date`,`is_active`);

--
-- Indexes for table `event_tickets`
--
ALTER TABLE `event_tickets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `event_tickets_ticket_reference_unique` (`ticket_reference`),
  ADD KEY `event_tickets_user_id_status_index` (`user_id`,`status`),
  ADD KEY `event_tickets_event_id_status_index` (`event_id`,`status`),
  ADD KEY `event_tickets_ticket_reference_index` (`ticket_reference`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `favorites_user_id_foreign` (`user_id`);

--
-- Indexes for table `hotels`
--
ALTER TABLE `hotels`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hotels_province_id_foreign` (`province_id`);

--
-- Indexes for table `hotel_managers`
--
ALTER TABLE `hotel_managers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hotel_managers_user_id_hotel_id_unique` (`user_id`,`hotel_id`),
  ADD KEY `hotel_managers_user_id_index` (`user_id`),
  ADD KEY `hotel_managers_hotel_id_index` (`hotel_id`);

--
-- Indexes for table `hotel_media`
--
ALTER TABLE `hotel_media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hotel_media_hotel_id_foreign` (`hotel_id`),
  ADD KEY `hotel_media_room_id_foreign` (`room_id`);

--
-- Indexes for table `hotel_rooms`
--
ALTER TABLE `hotel_rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hotel_rooms_hotel_id_foreign` (`hotel_id`);

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
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messages_sender_id_foreign` (`sender_id`),
  ADD KEY `messages_conversation_id_created_at_index` (`conversation_id`,`created_at`),
  ADD KEY `messages_is_read_sender_type_index` (`is_read`,`sender_type`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_booking_id_status_index` (`booking_id`,`status`),
  ADD KEY `payments_transaction_id_index` (`transaction_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `policies`
--
ALTER TABLE `policies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `policies_slug_unique` (`slug`);

--
-- Indexes for table `private_cars`
--
ALTER TABLE `private_cars`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `provinces`
--
ALTER TABLE `provinces`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_hotel_review` (`user_id`,`hotel_id`),
  ADD KEY `hotel_id` (`hotel_id`);

--
-- Indexes for table `service_requests`
--
ALTER TABLE `service_requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `service_requests_request_reference_unique` (`request_reference`),
  ADD KEY `service_requests_trip_id_foreign` (`trip_id`),
  ADD KEY `service_requests_bus_id_foreign` (`bus_id`),
  ADD KEY `service_requests_private_car_id_foreign` (`private_car_id`),
  ADD KEY `service_requests_user_id_status_index` (`user_id`,`status`),
  ADD KEY `service_requests_service_type_status_index` (`service_type`,`status`),
  ADD KEY `service_requests_request_reference_index` (`request_reference`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `trips`
--
ALTER TABLE `trips`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trips_trip_date_is_active_index` (`trip_date`,`is_active`),
  ADD KEY `trips_bus_id_index` (`bus_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_vouchers`
--
ALTER TABLE `user_vouchers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_vouchers_user_id_foreign` (`user_id`),
  ADD KEY `user_vouchers_voucher_id_foreign` (`voucher_id`),
  ADD KEY `user_vouchers_booking_id_foreign` (`booking_id`);

--
-- Indexes for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD UNIQUE KEY `vouchers_code_unique` (`code`);

--
-- Indexes for table `wallets`
--
ALTER TABLE `wallets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wallets_user_id_foreign` (`user_id`);

--
-- Indexes for table `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wallet_transactions_wallet_id_foreign` (`wallet_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `buses`
--
ALTER TABLE `buses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bus_seats`
--
ALTER TABLE `bus_seats`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `contact_links`
--
ALTER TABLE `contact_links`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `event_tickets`
--
ALTER TABLE `event_tickets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `hotels`
--
ALTER TABLE `hotels`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `hotel_managers`
--
ALTER TABLE `hotel_managers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `hotel_media`
--
ALTER TABLE `hotel_media`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `hotel_rooms`
--
ALTER TABLE `hotel_rooms`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `policies`
--
ALTER TABLE `policies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `private_cars`
--
ALTER TABLE `private_cars`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `provinces`
--
ALTER TABLE `provinces`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_requests`
--
ALTER TABLE `service_requests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `trips`
--
ALTER TABLE `trips`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user_vouchers`
--
ALTER TABLE `user_vouchers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `wallets`
--
ALTER TABLE `wallets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_hotel_id_foreign` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `hotel_rooms` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bus_seats`
--
ALTER TABLE `bus_seats`
  ADD CONSTRAINT `bus_seats_service_request_id_foreign` FOREIGN KEY (`service_request_id`) REFERENCES `service_requests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bus_seats_trip_id_foreign` FOREIGN KEY (`trip_id`) REFERENCES `trips` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `conversations`
--
ALTER TABLE `conversations`
  ADD CONSTRAINT `conversations_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `conversations_hotel_id_foreign` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `conversations_hotel_manager_id_foreign` FOREIGN KEY (`hotel_manager_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `conversations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `event_tickets`
--
ALTER TABLE `event_tickets`
  ADD CONSTRAINT `event_tickets_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `event_tickets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hotels`
--
ALTER TABLE `hotels`
  ADD CONSTRAINT `hotels_province_id_foreign` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `hotel_managers`
--
ALTER TABLE `hotel_managers`
  ADD CONSTRAINT `hotel_managers_hotel_id_foreign` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hotel_managers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hotel_media`
--
ALTER TABLE `hotel_media`
  ADD CONSTRAINT `hotel_media_hotel_id_foreign` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hotel_media_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `hotel_rooms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hotel_rooms`
--
ALTER TABLE `hotel_rooms`
  ADD CONSTRAINT `hotel_rooms_hotel_id_foreign` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `service_requests`
--
ALTER TABLE `service_requests`
  ADD CONSTRAINT `service_requests_bus_id_foreign` FOREIGN KEY (`bus_id`) REFERENCES `buses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `service_requests_private_car_id_foreign` FOREIGN KEY (`private_car_id`) REFERENCES `private_cars` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `service_requests_trip_id_foreign` FOREIGN KEY (`trip_id`) REFERENCES `trips` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `service_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `trips`
--
ALTER TABLE `trips`
  ADD CONSTRAINT `trips_bus_id_foreign` FOREIGN KEY (`bus_id`) REFERENCES `buses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_vouchers`
--
ALTER TABLE `user_vouchers`
  ADD CONSTRAINT `user_vouchers_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `user_vouchers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_vouchers_voucher_id_foreign` FOREIGN KEY (`voucher_id`) REFERENCES `vouchers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wallets`
--
ALTER TABLE `wallets`
  ADD CONSTRAINT `wallets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  ADD CONSTRAINT `wallet_transactions_wallet_id_foreign` FOREIGN KEY (`wallet_id`) REFERENCES `wallets` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
