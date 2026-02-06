-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 14, 2026 at 11:48 PM
-- Server version: 8.4.7-7
-- PHP Version: 8.1.34

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
(1, 7, 1, 1, '2025-12-25', '2025-12-27', 2, 1, 488.00, 244.00, 2, 'pending', 'Special request', NULL, 'BK-6944886946E21', '2025-12-18 23:04:09', '2025-12-18 23:04:09'),
(2, 5, 1, 4, '2026-06-15', '2026-07-17', 2, 1, 63950.00, 2000.00, 32, 'pending', 'Special request', NULL, 'BK-69564046D4296', '2026-01-01 09:37:10', '2026-01-01 09:37:10'),
(3, 5, 15, 70, '2026-01-25', '2026-01-28', 2, 1, 600.00, 200.00, 3, 'pending', 'Late check-in', NULL, 'BK-6968158C9CD7C', '2026-01-14 22:15:40', '2026-01-14 22:15:40'),
(4, 5, 15, 70, '2026-01-20', '2026-01-22', 2, 3, 1200.00, 200.00, 2, 'pending', 'Late check-in', NULL, 'BK-696815C01B74A', '2026-01-14 22:16:32', '2026-01-14 22:16:32'),
(5, 5, 15, NULL, '2026-01-25', '2026-01-28', 6, 3, 9000.00, 3000.00, 3, 'pending', 'Late check-in requested', NULL, 'BK-696817CE972BD', '2026-01-14 22:25:18', '2026-01-14 22:25:18'),
(6, 5, 15, NULL, '2026-01-25', '2026-01-28', 6, 1, 3000.00, 1000.00, 3, 'pending', 'Late check-in requested', NULL, 'BK-696818139EE57', '2026-01-14 22:26:27', '2026-01-14 22:26:27'),
(7, 5, 15, NULL, '2026-01-25', '2026-01-28', 6, 3, 9000.00, 3000.00, 3, 'pending', 'Late check-in requested', NULL, 'BK-6968183C6EEE3', '2026-01-14 22:27:08', '2026-01-14 22:27:08');

-- --------------------------------------------------------

--
-- Table structure for table `booking_rooms`
--

CREATE TABLE `booking_rooms` (
  `id` bigint UNSIGNED NOT NULL,
  `booking_id` bigint UNSIGNED NOT NULL,
  `room_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `booking_rooms`
--

INSERT INTO `booking_rooms` (`id`, `booking_id`, `room_id`, `created_at`, `updated_at`) VALUES
(1, 5, 80, '2026-01-14 22:25:18', '2026-01-14 22:25:18'),
(2, 5, 81, '2026-01-14 22:25:18', '2026-01-14 22:25:18'),
(3, 5, 82, '2026-01-14 22:25:18', '2026-01-14 22:25:18'),
(4, 6, 83, '2026-01-14 22:26:27', '2026-01-14 22:26:27'),
(5, 7, 84, '2026-01-14 22:27:08', '2026-01-14 22:27:08'),
(6, 7, 85, '2026-01-14 22:27:08', '2026-01-14 22:27:08'),
(7, 7, 86, '2026-01-14 22:27:08', '2026-01-14 22:27:08');

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
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `buses`
--

INSERT INTO `buses` (`id`, `name_ar`, `name_en`, `total_seats`, `type`, `is_active`, `created_at`, `updated_at`, `user_id`) VALUES
(1, 'Glenna Fuller', 'Lee Ellison', 40, 'big', 1, '2025-12-01 01:11:36', '2026-01-04 18:51:59', NULL),
(2, 'Go bus', 'Go bus', 40, 'Big', 1, '2025-12-01 01:11:36', '2025-12-25 22:16:50', NULL),
(3, 'Go bus', 'Go bus', 20, 'Small\r\n', 1, '2025-12-01 01:11:36', '2025-12-25 22:16:50', NULL),
(4, 'Leila Sellers', 'Sharon Simpson', 60, 'small', 1, '2025-12-28 17:32:56', '2025-12-28 17:39:10', NULL),
(6, 'أتوبيس سياحي', 'Tourist Bus', 20, 'small', 1, '2026-01-13 17:48:48', '2026-01-13 17:49:36', 5),
(7, 'أتوبيس سياحي', 'Tourist Bus', 40, 'big', 1, '2026-01-13 22:02:45', '2026-01-13 22:02:45', 7),
(8, 'اتوبيس العروبة', 'Ouroba Bus', 13, 'small', 1, '2026-01-13 22:18:14', '2026-01-13 22:18:14', 7);

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
(3, 1, 1, 6, '2025-12-24 19:44:11', '2025-12-24 19:44:11'),
(4, 3, 1, 1, '2025-12-31 16:02:12', '2025-12-31 16:02:12'),
(5, 3, 1, 2, '2025-12-31 16:02:12', '2025-12-31 16:02:12'),
(6, 3, 1, 3, '2025-12-31 16:02:12', '2025-12-31 16:02:12'),
(7, 4, 1, 7, '2025-12-31 16:05:21', '2025-12-31 16:05:21'),
(8, 4, 1, 8, '2025-12-31 16:05:21', '2025-12-31 16:05:21'),
(9, 5, 1, 10, '2025-12-31 16:06:09', '2025-12-31 16:06:09'),
(10, 8, 1, 12, '2026-01-14 21:49:30', '2026-01-14 21:49:30'),
(11, 8, 1, 12, '2026-01-14 21:49:30', '2026-01-14 21:49:30'),
(12, 8, 1, 13, '2026-01-14 21:49:30', '2026-01-14 21:49:30');

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
-- Table structure for table `closed_seats`
--

CREATE TABLE `closed_seats` (
  `id` bigint UNSIGNED NOT NULL,
  `trip_id` bigint UNSIGNED NOT NULL,
  `seat_number` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `closed_seats`
--

INSERT INTO `closed_seats` (`id`, `trip_id`, `seat_number`, `created_at`, `updated_at`) VALUES
(1, 1, 11, '2026-01-14 21:59:10', '2026-01-14 21:59:10'),
(2, 1, 15, '2026-01-14 21:59:10', '2026-01-14 21:59:10');

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
  `available_tickets` int NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `activity_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price_per_person` decimal(10,2) DEFAULT NULL,
  `duration` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_secondary` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `max_participants` int DEFAULT NULL,
  `instant_booking` tinyint(1) DEFAULT '0',
  `allow_cancellation` tinyint(1) DEFAULT '0',
  `cancellation_hours` int DEFAULT NULL,
  `activity_images` json DEFAULT NULL,
  `id_images` json DEFAULT NULL,
  `user_id` bigint DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `name_ar`, `name_en`, `location_ar`, `location_en`, `location_url`, `lat`, `lng`, `category`, `image_url`, `event_date`, `description_ar`, `description_en`, `price`, `available_tickets`, `is_active`, `created_at`, `updated_at`, `activity_type`, `price_per_person`, `duration`, `phone`, `phone_secondary`, `max_participants`, `instant_booking`, `allow_cancellation`, `cancellation_hours`, `activity_images`, `id_images`, `user_id`) VALUES
(1, 'Test', 'تسيت', 'Test', 'تسيت', 'https://www.google.com/maps/place/Gardenia+City,+Nasr+City,+Cairo+Governorate/@30.0646733,31.385397,15z/data=!3m1!4b1!4m6!3m5!1s0x14583', 30.06467300, 31.38539700, 'general', 'none', '2025-12-26 21:58:22', 'for all users ', 'for all users ', 310.00, 50, 1, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 0, 0, NULL, NULL, NULL, 1),
(2, 'خيل', 'Hourse', 'Dxfhxhfcjtdut', 'Iyfiyfiyfiy', NULL, NULL, NULL, 'general', NULL, '2025-12-26 00:18:00', 'Uvhvycy', NULL, 1500.00, 6, 1, '2025-12-25 22:19:01', '2025-12-25 22:19:01', NULL, NULL, NULL, '', NULL, NULL, 0, 0, NULL, NULL, NULL, 1),
(3, 'نشاط محدث', 'نشاط محدث', 'الإسكندرية', 'الإسكندرية', NULL, NULL, NULL, 'general', NULL, '2025-12-29 21:07:00', 'نشاط محدث', 'نشاط محدث', 100.00, 1, 1, '2025-12-29 21:07:46', '2025-12-29 21:18:59', 'سفر', 150.00, '3 hours', '01000000000', '01100000000', 20, 1, 1, 24, '[\"activities/images/Yy9nu2fxBG8JFj8tocWETptocsyg3E46384GIwSQ.jpg\", \"activities/images/2mA8zSIQCQ7TTYVPPSN3ueSUHFUcgOUNdzab12Nu.jpg\"]', '{\"back\": \"activities/documents/xIAoQphjDKHO0J4f9VwEl6HAoNLe6GsDaaoys21f.jpg\", \"front\": \"activities/documents/whc7QYNkUjl8a3dwve7V3cJPgeQxQMhxZkHEZvbL.jpg\"}', 5),
(4, 'نشاط ترفيهي رائع', 'نشاط ترفيهي رائع', 'القاهرة', 'القاهرة', NULL, NULL, NULL, 'general', NULL, '2026-12-01 12:54:00', 'نشاط ترفيهي رائع', 'نشاط ترفيهي رائع', 100.00, 1, 1, '2025-12-30 12:54:04', '2025-12-31 11:57:27', 'ترفيه', 100.00, '2 hours', '01000000000', '01100000000', NULL, 0, 0, NULL, '[\"activities/images/IH8Xm79dFxVLySLzjeujDxLmi7vk1eUnau2YMAtS.jpg\", \"activities/images/iK4CzzTO8xhl5OCLqx5s5iyBfAiUE9ZcixbbzAFf.jpg\"]', '{\"back\": \"activities/documents/epBnhsnyqMlBkmuKSlwMbVp9f845ODWVJo4g9NJm.jpg\", \"front\": \"activities/documents/k96q4INLuD4Q0ysjh5GtasKC6UpvjepLyb9c2yVz.jpg\"}', 5),
(5, 'oky', 'oky', 'cairo', 'cairo', NULL, NULL, NULL, 'general', NULL, '2026-05-01 12:59:00', 'oky', 'oky', 500.00, 1, 1, '2025-12-30 12:59:27', '2025-12-31 12:00:40', 'تعليم', 1200.00, '9', '01003705602', '01146656627', 24, 1, 1, 33, '[\"activities/images/F1hkQNzoTdrF86zrGhiEi4thQy5iXy4hQC0igvQH.jpg\"]', '{\"back\": \"activities/documents/bGGD4RJsgVm9P8SGY5UOjB6vIWacOP1RVKwdcXDF.jpg\", \"front\": \"activities/documents/sG4yna62AKgDxKKZPt27SXmJZVJu1yFImLbcD9Zs.jpg\"}', 7);

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
(1, 5, 1, 6, 1860.00, 'pending', 'Need a child seat', 'ET-694C47DCEA3EC', '2025-12-24 20:06:52', '2025-12-24 20:06:52'),
(2, 7, 1, 2, 620.00, 'pending', NULL, 'ET-6955150C66D87', '2025-12-31 12:20:28', '2025-12-31 12:20:28'),
(3, 7, 1, 1, 310.00, 'pending', NULL, 'ET-69551F115F418', '2025-12-31 13:03:13', '2025-12-31 13:03:13'),
(4, 7, 1, 1, 310.00, 'pending', NULL, 'ET-69551F2FA77F5', '2025-12-31 13:03:43', '2025-12-31 13:03:43'),
(5, 7, 1, 1, 310.00, 'pending', NULL, 'ET-69551F761A762', '2025-12-31 13:04:54', '2025-12-31 13:04:54'),
(6, 7, 1, 1, 310.00, 'pending', NULL, 'ET-69551FA52A353', '2025-12-31 13:05:41', '2025-12-31 13:05:41'),
(7, 7, 1, 1, 310.00, 'pending', NULL, 'ET-69551FCC54987', '2025-12-31 13:06:20', '2025-12-31 13:06:20'),
(8, 7, 1, 1, 310.00, 'pending', 'معي اطفال', 'ET-69552046DFF0B', '2025-12-31 13:08:22', '2025-12-31 13:08:22'),
(9, 7, 5, 1, 500.00, 'pending', 'معي اطفال', 'ET-695520798F4C3', '2025-12-31 13:09:13', '2025-12-31 13:09:13'),
(10, 7, 4, 1, 100.00, 'pending', NULL, 'ET-695520B4AA645', '2025-12-31 13:10:12', '2025-12-31 13:10:12');

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
(60, 7, 5, '2025-12-30 05:40:36', '2025-12-30 05:40:36'),
(62, 7, 9, '2025-12-30 05:40:41', '2025-12-30 05:40:41');

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
  `lang` decimal(10,2) DEFAULT '31.02',
  `identity_images` json DEFAULT NULL,
  `lease_agreement` json DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description_ar` text COLLATE utf8mb4_unicode_ci,
  `description_en` text COLLATE utf8mb4_unicode_ci,
  `schedule_type` enum('hourly','daily') COLLATE utf8mb4_unicode_ci NOT NULL,
  `hourly_price` decimal(10,2) DEFAULT '0.00',
  `booking_settings` json DEFAULT NULL,
  `week_schedule` json DEFAULT NULL,
  `blocked_dates` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hotels`
--

INSERT INTO `hotels` (`id`, `name_ar`, `name_en`, `address_ar`, `address_en`, `province_id`, `type`, `website_url`, `about_info_ar`, `about_info_en`, `is_active`, `created_at`, `updated_at`, `country`, `rate`, `services`, `lat`, `lang`, `identity_images`, `lease_agreement`, `user_id`, `price`, `phone`, `phone_2`, `description_ar`, `description_en`, `schedule_type`, `hourly_price`, `booking_settings`, `week_schedule`, `blocked_dates`) VALUES
(1, 'Bruno Berry', 'Jorden Herman', 'Vero ut ipsa et vel', 'Est perferendis iust', 22, 'hotel', 'https://www.zyrimynihes.co.uk', 'Est consectetur te', 'Sunt quis aliquam ne', 1, '2025-11-15 21:29:27', '2025-12-19 16:50:16', 'مصر', 4, '[\"wifi\", \"parking\", \"pool\"]', 26.82, 30.80, NULL, NULL, NULL, NULL, '01060495817', NULL, NULL, NULL, 'hourly', 0.00, NULL, NULL, NULL),
(2, 'هلنان دريم', 'Helnan dream', '6 october', 'October', 3, 'hotel_apartment', NULL, NULL, NULL, 1, '2025-12-12 11:52:30', '2025-12-12 11:52:30', 'تركيا', 2, '[\"wifi\", \"parking\", \"pool\"]', 31.00, 30.80, NULL, NULL, NULL, NULL, '01060495817', NULL, NULL, NULL, 'hourly', 0.00, NULL, NULL, NULL),
(3, 'Fdffc', 'Gfcfcc', 'Vgccc', 'Cccc', 15, 'spa', 'https://saferplus.net/hotel/hotels/create', 'Vgccc', 'Cccc', 1, '2025-12-12 19:49:43', '2025-12-12 19:49:43', 'السعودية', 3, '[\"wifi\", \"parking\", \"pool\", \"food\", \"sports_center\", \"elevator\", \"social_rooms\", \"opening\"]', 31.00, 30.80, NULL, NULL, NULL, NULL, '01060495817', NULL, NULL, NULL, 'hourly', 0.00, NULL, NULL, NULL),
(4, 'ريكسوس', 'Rexos', 'مدينه ملوي', 'Aeeefv', 8, 'hostel', 'https://saferplus.net/hotel/hotels/create', 'احنا زي الفل', 'We are good', 1, '2025-12-14 23:33:57', '2025-12-18 11:14:10', 'مصر', 2, '[\"wifi\", \"parking\", \"pool\"]', 30.04, 31.24, NULL, NULL, NULL, NULL, '01060495817', NULL, NULL, NULL, 'hourly', 0.00, NULL, NULL, NULL),
(5, 'Vladimir Rojas', 'Malachi Love', 'Laboris sequi aut ma', 'Qui quas consequat', 5, 'hotel', 'https://www.himoj.us', 'Architecto animi do', 'Labore proident rep', 1, '2025-12-17 21:09:15', '2025-12-18 10:36:58', 'فرنسا', 4, '[\"wifi\", \"parking\", \"pool\", \"food\", \"sports_center\", \"elevator\", \"social_rooms\", \"opening\"]', 30.04, 31.04, NULL, NULL, NULL, NULL, '01060495817', NULL, NULL, NULL, 'hourly', 0.00, NULL, NULL, NULL),
(6, 'هيلتون', 'Hilton', 'القاهرة', 'Cairo', 1, 'hostel', NULL, NULL, NULL, 1, '2025-12-19 16:53:20', '2025-12-19 17:14:59', 'Egypt', 2, '[\"wifi\", \"parking\", \"pool\", \"food\", \"sports_center\", \"elevator\", \"social_rooms\", \"opening\"]', NULL, NULL, NULL, NULL, NULL, NULL, '01060495817', '01060495817', NULL, NULL, 'hourly', 0.00, NULL, NULL, NULL),
(7, 'Audrey White', 'Unity Mcknight', 'Deserunt aut maiores', 'Esse est sit dolor', 27, 'spa', 'https://www.linilajyducysop.com', 'Velit at sit aut ut', 'Dolorum in non bland', 1, '2025-12-23 19:51:22', '2025-12-23 19:51:22', 'مصر', 2, '[\"wifi\", \"sports_center\", \"kitchen\", \"dishes_silverware\", \"hot_water_kettle\", \"crib\", \"smoke_alarm\", \"hangers\", \"iron\"]', 30.00, 31.21, NULL, NULL, NULL, NULL, '01060495817', '01060495817', NULL, NULL, 'hourly', 0.00, NULL, NULL, NULL),
(8, 'Garrison Kemp', 'Aline Shepherd', 'Ducimus facere non', 'Vel assumenda dolor', 2, 'hotel_apartment', 'https://www.lefe.ca', 'Sed deserunt harum a', 'Hic deleniti volupta', 0, '2025-12-26 13:12:57', '2025-12-28 20:16:39', 'مصر', 5, '[\"wifi\", \"social_rooms\", \"hot_water_kettle\"]', 30.03, 31.11, NULL, NULL, NULL, NULL, '01060495817', '01060495817', NULL, NULL, 'hourly', 0.00, NULL, NULL, NULL),
(9, 'علي النيل', 'on nile', 'علي النيل', 'on nile', 1, 'hotel', NULL, NULL, NULL, 1, '2025-12-28 21:12:24', '2025-12-28 21:29:11', 'مصر', 2, NULL, 30.02, 31.02, '{\"back\": \"hotels/documents/ZrwzntCM3IZiYei07q4Lf6VYY1ZmxYl7tuYjfAFO.jpg\", \"front\": \"hotels/documents/wzyDWvSdKsXHNr8iWsy1lA4KHvtH7Iu8pyoFbkhu.jpg\"}', NULL, 5, 1200.00, '01060495817', '01060495817', 'on nile', 'on nile', 'hourly', 0.00, NULL, NULL, NULL),
(10, 'علي النيل', 'on nile', 'علي النيل', 'on nile', 1, 'hotel', NULL, NULL, NULL, 0, '2025-12-29 16:20:02', '2025-12-29 16:20:02', 'مصر', 2, NULL, 30.02, 31.02, '{\"back\": \"hotels/documents/nsBvVUUWWN7tAcNDDbEm4mcb08S7eVWKNIkWcOpy.jpg\", \"front\": \"hotels/documents/1Bv62rnU6BDiVvOTqNE683TElkf9dcnP40EfZPn1.jpg\"}', NULL, 5, 1200.00, '01060495817', '01060495817', 'on nile', 'on nile', 'hourly', 0.00, NULL, NULL, NULL),
(11, 'فندق', 'فندق', 'فندق', 'فندق', 1, 'hotel', NULL, NULL, NULL, 0, '2025-12-29 16:42:34', '2025-12-30 16:26:01', 'مصر', 2, NULL, 30.02, 31.02, '{\"back\": \"hotels/documents/LwqwYe2bUtFveW7Yv1OS2EXAAMszmy9KDuaftrrW.jpg\", \"front\": \"hotels/documents/usSRMSCVgrZ95WjtU7jCMSZxI9gicOloic2XPHXm.jpg\"}', NULL, 7, 88.00, '01060495817', '8854', 'فندق', 'فندق', 'hourly', 150.00, '{\"max_hours\": \"24\", \"min_hours\": \"4\", \"advance_booking_days\": \"30\"}', '[{\"day\": \"saturday\", \"is_available\": \"0\"}, {\"day\": \"sunday\", \"is_available\": \"0\"}, {\"day\": \"monday\", \"time_slots\": [{\"to\": \"23:59\", \"from\": \"00:00\"}], \"is_available\": \"1\"}, {\"day\": \"tuesday\", \"time_slots\": [{\"to\": \"23:59\", \"from\": \"00:00\"}], \"is_available\": \"1\"}, {\"day\": \"wednesday\", \"time_slots\": [{\"to\": \"23:59\", \"from\": \"00:00\"}], \"is_available\": \"1\"}, {\"day\": \"thursday\", \"time_slots\": [{\"to\": \"23:59\", \"from\": \"00:00\"}], \"is_available\": \"1\"}, {\"day\": \"friday\", \"is_available\": \"0\"}]', '[{\"date\": \"2025-12-30\", \"reason\": \"booked\"}]'),
(12, 'فندق محدث', 'Updated Hotel', 'الجيزة', 'Giza', 1, 'hotel', NULL, NULL, NULL, 1, '2025-12-29 20:08:15', '2025-12-29 20:12:50', 'مصر', 2, NULL, 30.02, 31.02, '{\"back\": \"hotels/documents/PpgryfhiVejfpgdmmMv4sTDDgQo5lWmV9uJcVriB.jpg\", \"front\": \"hotels/documents/RR6LH8OGReMonW3ODGTztDIDuSzL7VPO4pDG9I7v.jpg\"}', '[\"hotels/documents/GjjfPAtqH3A9omPPvCa1rkYdu8gqc3Qo4yJBHNio.jpg\", \"hotels/documents/vdsjmOL4UnMgFRK6b4uKQiudh88hGTjvTRUaoF2f.jpg\"]', 5, 200.00, '01060495817', '01100000000', 'وصف محدث', 'Updated description', 'hourly', 60.00, '{\"max_hours\": 8, \"min_hours\": 2, \"advance_booking_days\": 2}', '[{\"day\": \"monday\", \"time_slots\": [{\"to\": \"12:00\", \"from\": \"09:00\"}, {\"to\": \"18:00\", \"from\": \"14:00\"}], \"is_available\": true}]', '[{\"date\": \"2025-01-10\", \"reason\": \"Maintenance\"}]'),
(13, 'منتجع', 'منتجع', 'الفيوم', 'fayoum', 1, 'hostel', NULL, NULL, NULL, 0, '2026-01-02 12:21:34', '2026-01-02 12:21:34', 'مصر', 2, NULL, 30.02, 31.02, '{\"back\": \"hotels/documents/WgHSYabK4UhL4E2ofvinoyEH5dWKnBSlXnvX4xrG.jpg\", \"front\": \"hotels/documents/ECmU4YeUrKThLuG1Lkx2FoCsbFstjtkvawSqnWfA.jpg\"}', '[\"hotels/documents/PaF1xLx4D5m0HmH7vs46JootCnpbRxPaxmMkynQS.jpg\"]', 7, 1500.00, '0100', '011', 'okay', 'okay', 'hourly', 0.00, NULL, NULL, NULL),
(14, 'فندقي الجديد', 'My New Hotel', 'شارع 123', '123 Street', 1, 'hotel', 'https://saferplus.net/hotel/hotels/create', NULL, NULL, 1, '2026-01-12 20:51:48', '2026-01-12 21:01:33', 'مصر', 2, '[{\"id\": 1, \"image\": \"services/NlBocOTkUSayp0chWDmePfsI2Z8Dt52vSW52VRLz.jpg\", \"name_ar\": \"صورة\", \"name_en\": \"Carter Church\"}]', 30.02, 31.02, '{\"back\": \"hotels/documents/AaUvH3pPFylGvd7rOmOHtr2zqEoTG5rtCcNSw9Pn.png\", \"front\": \"hotels/documents/2TTiKea1AmGC05rfLc8YZsqFvmoovcPEpcdB9JXZ.png\"}', '[\"hotels/documents/TIs0m7W48VkSNZilxAkfKOpJgAlqYFvmYSkW2em1.png\"]', 5, NULL, '123456789', NULL, NULL, NULL, 'hourly', 0.00, NULL, NULL, NULL),
(15, 'فندقي', 'Hotel', 'شارع 123', '123 Street', 1, 'hotel', NULL, NULL, NULL, 1, '2026-01-12 21:00:02', '2026-01-12 21:01:02', 'مصر', 2, '[{\"id\": 1, \"image\": \"services/NlBocOTkUSayp0chWDmePfsI2Z8Dt52vSW52VRLz.jpg\", \"name_ar\": \"صورة\", \"name_en\": \"Carter Church\"}]', 30.00, 31.10, '{\"back\": \"hotels/documents/lgq2BBgxK2Wf4zQuo6OBUHvhiTwxRpcdfRVj11Ul.png\", \"front\": \"hotels/documents/7GN2SzlIySHMavp8v7arDlZ3idrmjYK91NLVXFIv.png\"}', '[\"hotels/documents/Ezjfk3BqZjGqsxJmtrWQG75Swm9Y4KKXCj35Wb29.png\"]', 5, NULL, '123456789', NULL, NULL, NULL, 'hourly', 0.00, NULL, NULL, NULL),
(16, 'فندقي (نسخة)', 'Hotel (Copy)', 'شارع 123', '123 Street', 1, 'hotel', NULL, NULL, NULL, 0, '2026-01-12 21:03:21', '2026-01-12 21:03:21', 'مصر', 2, '[{\"id\": 1, \"image\": \"services/NlBocOTkUSayp0chWDmePfsI2Z8Dt52vSW52VRLz.jpg\", \"name_ar\": \"صورة\", \"name_en\": \"Carter Church\"}]', 30.00, 31.10, '{\"back\": \"hotels/documents/lgq2BBgxK2Wf4zQuo6OBUHvhiTwxRpcdfRVj11Ul.png\", \"front\": \"hotels/documents/7GN2SzlIySHMavp8v7arDlZ3idrmjYK91NLVXFIv.png\"}', '[\"hotels/documents/Ezjfk3BqZjGqsxJmtrWQG75Swm9Y4KKXCj35Wb29.png\"]', 5, NULL, '123456789', NULL, NULL, NULL, 'hourly', 0.00, NULL, NULL, NULL),
(17, 'فندق الرحاب', 'Rehab Hotel', 'الفيوم', 'Fayoum', 1, 'hotel', NULL, NULL, NULL, 0, '2026-01-14 14:14:16', '2026-01-14 14:14:16', 'مصر', 2, '[{\"id\": 1, \"image\": \"services/NlBocOTkUSayp0chWDmePfsI2Z8Dt52vSW52VRLz.jpg\", \"name_ar\": \"صورة\", \"name_en\": \"Carter Church\"}]', 30.05, 31.22, '{\"back\": \"hotels/documents/wddUZJUcj66PA2sc6w0Pf8rY2m2sCqWGu81csGNI.jpg\", \"front\": \"hotels/documents/6aipmiDM3IxJl3ZlJLS7nlYudgizjUqBX7zSmfdn.jpg\"}', '[\"hotels/documents/vbbzHUoagH0IQ3BluX6gQRw1pd8Pr57dFcpb7L78.jpg\"]', 7, NULL, '01003705602', NULL, NULL, NULL, 'hourly', 0.00, NULL, NULL, NULL);

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
(24, 7, 16, 'image', 'hotels/7/rooms/16/images/694fd65013394.png', 1, '2025-12-27 12:51:28', '2025-12-27 12:51:28'),
(25, 9, NULL, 'image', 'hotels/9/images/ZKzOqj92kHjFBDpXYMGMMbGzb4CbpP7NTUlDrbd2.jpg', 0, '2025-12-28 21:12:24', '2025-12-28 21:12:24'),
(26, 9, NULL, 'image', 'hotels/9/images/MunvNB8Y4kuv1EOkzvuEDfCFy6GtbgqMy97rmySD.jpg', 1, '2025-12-28 21:12:24', '2025-12-28 21:12:24'),
(27, 9, NULL, 'image', 'hotels/9/images/LNwjhiU70A10tsgbvBUca46MDQ8EA8vn6R112bUB.jpg', 2, '2025-12-28 21:17:08', '2025-12-28 21:17:08'),
(28, 9, NULL, 'image', 'hotels/9/images/B9jSkIpIUxa4SdRXCBbQK1DK7BoxNXStTBgwcBln.jpg', 3, '2025-12-28 21:17:08', '2025-12-28 21:17:08'),
(29, 10, NULL, 'image', 'hotels/10/images/n53hKVacskebj0UJJzlFGdifUSypuEbRs3LOzPWc.jpg', 0, '2025-12-29 16:20:02', '2025-12-29 16:20:02'),
(30, 10, NULL, 'image', 'hotels/10/images/qk0vBNFVxFCHKQW0jpsrPnWfIKIGPiBjDTnl1uHs.jpg', 1, '2025-12-29 16:20:02', '2025-12-29 16:20:02'),
(31, 11, NULL, 'image', 'hotels/11/images/ng4AZYPKVgkiZk5ETAtwrUMJABDqbyHnNMsGQ2FB.jpg', 0, '2025-12-29 16:42:34', '2025-12-29 16:42:34'),
(32, 11, NULL, 'image', 'hotels/11/images/YoDAaZoe6djwPTYDPQ0IfR5D3dthdHy3H9FpG92s.jpg', 1, '2025-12-29 16:42:34', '2025-12-29 16:42:34'),
(33, 11, NULL, 'image', 'hotels/11/images/e2JavqWM10tZNoSukhjYKLZZc1L0oibWRQsApaNh.jpg', 2, '2025-12-29 16:42:34', '2025-12-29 16:42:34'),
(34, 11, NULL, 'image', 'hotels/11/images/dbsHCVw8QQGZkb52HsP4DgDQrelFRwkMCyqzrG2C.jpg', 3, '2025-12-29 16:42:34', '2025-12-29 16:42:34'),
(35, 11, NULL, 'image', 'hotels/11/images/nboSNktGpSxBollJ2rOrM9EfUwx17xaT2F4pFFOR.jpg', 4, '2025-12-29 16:42:34', '2025-12-29 16:42:34'),
(36, 12, NULL, 'image', 'hotels/12/images/tM7K4MGuo5sDOKKSF2k1xQDjxqcJhLP9XatBaG1u.jpg', 0, '2025-12-29 20:08:15', '2025-12-29 20:08:15'),
(37, 12, NULL, 'image', 'hotels/12/images/e1uSYMyA8ZelAHKqWeWgRLAB0FjwnoYAvS58JQxA.jpg', 1, '2025-12-29 20:08:15', '2025-12-29 20:08:15'),
(38, 13, NULL, 'image', 'hotels/13/images/vwvP3kD94EWzdOGpa4BEQ8gpsNbAvkzQm15atZS8.jpg', 0, '2026-01-02 12:21:34', '2026-01-02 12:21:34'),
(39, 13, NULL, 'image', 'hotels/13/images/xMX8VXzovn6gP3XiSSgA135PBqnXyBCQG2brX1Ze.jpg', 1, '2026-01-02 12:21:34', '2026-01-02 12:21:34'),
(40, 13, NULL, 'image', 'hotels/13/images/5QfQn7yW5mOWuV7UVFVL2KDdNzcTIDxTanL6teG3.jpg', 2, '2026-01-02 12:21:34', '2026-01-02 12:21:34'),
(41, 13, NULL, 'image', 'hotels/13/images/2gVo5fAXn3WIv2nHuZhftXnX7f5lquROvWnBwfRE.jpg', 3, '2026-01-02 12:21:34', '2026-01-02 12:21:34'),
(42, 13, NULL, 'image', 'hotels/13/images/6SoR4rA4j3tszZ94X4wdPXfBzYiz339z2TyQHBjV.jpg', 4, '2026-01-02 12:21:34', '2026-01-02 12:21:34'),
(43, 15, 71, 'image', 'hotels/15/rooms/71/JsxTYwXHe2ApS7EAaQNbg1ZJlz36jIAlZI6YS3s7.png', 0, '2026-01-12 21:06:42', '2026-01-12 21:06:42'),
(44, 15, 71, 'image', 'hotels/15/rooms/71/QPEYl0FpUUDLPYMNiBKoPX0GmYf5rxen4PbZ3dCi.png', 1, '2026-01-12 21:06:42', '2026-01-12 21:06:42'),
(45, 15, 72, 'image', 'hotels/15/rooms/71/JsxTYwXHe2ApS7EAaQNbg1ZJlz36jIAlZI6YS3s7.png', 0, '2026-01-12 21:18:14', '2026-01-12 21:18:14'),
(46, 15, 72, 'image', 'hotels/15/rooms/71/QPEYl0FpUUDLPYMNiBKoPX0GmYf5rxen4PbZ3dCi.png', 1, '2026-01-12 21:18:14', '2026-01-12 21:18:14'),
(47, 15, 73, 'image', 'hotels/15/rooms/71/JsxTYwXHe2ApS7EAaQNbg1ZJlz36jIAlZI6YS3s7.png', 0, '2026-01-12 21:18:14', '2026-01-12 21:18:14'),
(48, 15, 73, 'image', 'hotels/15/rooms/71/QPEYl0FpUUDLPYMNiBKoPX0GmYf5rxen4PbZ3dCi.png', 1, '2026-01-12 21:18:14', '2026-01-12 21:18:14'),
(49, 15, 74, 'image', 'hotels/15/rooms/71/JsxTYwXHe2ApS7EAaQNbg1ZJlz36jIAlZI6YS3s7.png', 0, '2026-01-12 21:18:14', '2026-01-12 21:18:14'),
(50, 15, 74, 'image', 'hotels/15/rooms/71/QPEYl0FpUUDLPYMNiBKoPX0GmYf5rxen4PbZ3dCi.png', 1, '2026-01-12 21:18:14', '2026-01-12 21:18:14'),
(51, 15, 75, 'image', 'hotels/15/rooms/71/JsxTYwXHe2ApS7EAaQNbg1ZJlz36jIAlZI6YS3s7.png', 0, '2026-01-12 21:18:14', '2026-01-12 21:18:14'),
(52, 15, 75, 'image', 'hotels/15/rooms/71/QPEYl0FpUUDLPYMNiBKoPX0GmYf5rxen4PbZ3dCi.png', 1, '2026-01-12 21:18:14', '2026-01-12 21:18:14'),
(53, 15, 76, 'image', 'hotels/15/rooms/71/JsxTYwXHe2ApS7EAaQNbg1ZJlz36jIAlZI6YS3s7.png', 0, '2026-01-12 21:18:14', '2026-01-12 21:18:14'),
(54, 15, 76, 'image', 'hotels/15/rooms/71/QPEYl0FpUUDLPYMNiBKoPX0GmYf5rxen4PbZ3dCi.png', 1, '2026-01-12 21:18:14', '2026-01-12 21:18:14'),
(55, 15, 77, 'image', 'hotels/15/rooms/71/JsxTYwXHe2ApS7EAaQNbg1ZJlz36jIAlZI6YS3s7.png', 0, '2026-01-12 21:18:14', '2026-01-12 21:18:14'),
(56, 15, 77, 'image', 'hotels/15/rooms/71/QPEYl0FpUUDLPYMNiBKoPX0GmYf5rxen4PbZ3dCi.png', 1, '2026-01-12 21:18:14', '2026-01-12 21:18:14'),
(57, 15, 78, 'image', 'hotels/15/rooms/71/JsxTYwXHe2ApS7EAaQNbg1ZJlz36jIAlZI6YS3s7.png', 0, '2026-01-12 21:18:14', '2026-01-12 21:18:14'),
(58, 15, 78, 'image', 'hotels/15/rooms/71/QPEYl0FpUUDLPYMNiBKoPX0GmYf5rxen4PbZ3dCi.png', 1, '2026-01-12 21:18:14', '2026-01-12 21:18:14'),
(59, 15, 79, 'image', 'hotels/15/rooms/71/JsxTYwXHe2ApS7EAaQNbg1ZJlz36jIAlZI6YS3s7.png', 0, '2026-01-12 21:18:14', '2026-01-12 21:18:14'),
(60, 15, 79, 'image', 'hotels/15/rooms/71/QPEYl0FpUUDLPYMNiBKoPX0GmYf5rxen4PbZ3dCi.png', 1, '2026-01-12 21:18:14', '2026-01-12 21:18:14'),
(61, 15, 80, 'image', 'hotels/15/rooms/71/JsxTYwXHe2ApS7EAaQNbg1ZJlz36jIAlZI6YS3s7.png', 0, '2026-01-12 21:18:14', '2026-01-12 21:18:14'),
(62, 15, 80, 'image', 'hotels/15/rooms/71/QPEYl0FpUUDLPYMNiBKoPX0GmYf5rxen4PbZ3dCi.png', 1, '2026-01-12 21:18:14', '2026-01-12 21:18:14'),
(63, 15, 81, 'image', 'hotels/15/rooms/71/JsxTYwXHe2ApS7EAaQNbg1ZJlz36jIAlZI6YS3s7.png', 0, '2026-01-12 21:18:14', '2026-01-12 21:18:14'),
(64, 15, 81, 'image', 'hotels/15/rooms/71/QPEYl0FpUUDLPYMNiBKoPX0GmYf5rxen4PbZ3dCi.png', 1, '2026-01-12 21:18:14', '2026-01-12 21:18:14'),
(65, 15, 82, 'image', 'hotels/15/rooms/71/JsxTYwXHe2ApS7EAaQNbg1ZJlz36jIAlZI6YS3s7.png', 0, '2026-01-12 21:18:14', '2026-01-12 21:18:14'),
(66, 15, 82, 'image', 'hotels/15/rooms/71/QPEYl0FpUUDLPYMNiBKoPX0GmYf5rxen4PbZ3dCi.png', 1, '2026-01-12 21:18:14', '2026-01-12 21:18:14'),
(67, 15, 83, 'image', 'hotels/15/rooms/71/JsxTYwXHe2ApS7EAaQNbg1ZJlz36jIAlZI6YS3s7.png', 0, '2026-01-12 21:18:14', '2026-01-12 21:18:14'),
(68, 15, 83, 'image', 'hotels/15/rooms/71/QPEYl0FpUUDLPYMNiBKoPX0GmYf5rxen4PbZ3dCi.png', 1, '2026-01-12 21:18:14', '2026-01-12 21:18:14'),
(69, 15, 84, 'image', 'hotels/15/rooms/71/JsxTYwXHe2ApS7EAaQNbg1ZJlz36jIAlZI6YS3s7.png', 0, '2026-01-12 21:18:14', '2026-01-12 21:18:14'),
(70, 15, 84, 'image', 'hotels/15/rooms/71/QPEYl0FpUUDLPYMNiBKoPX0GmYf5rxen4PbZ3dCi.png', 1, '2026-01-12 21:18:14', '2026-01-12 21:18:14'),
(71, 15, 85, 'image', 'hotels/15/rooms/71/JsxTYwXHe2ApS7EAaQNbg1ZJlz36jIAlZI6YS3s7.png', 0, '2026-01-12 21:18:14', '2026-01-12 21:18:14'),
(72, 15, 85, 'image', 'hotels/15/rooms/71/QPEYl0FpUUDLPYMNiBKoPX0GmYf5rxen4PbZ3dCi.png', 1, '2026-01-12 21:18:14', '2026-01-12 21:18:14'),
(73, 15, 86, 'image', 'hotels/15/rooms/71/JsxTYwXHe2ApS7EAaQNbg1ZJlz36jIAlZI6YS3s7.png', 0, '2026-01-12 21:18:14', '2026-01-12 21:18:14'),
(74, 15, 86, 'image', 'hotels/15/rooms/71/QPEYl0FpUUDLPYMNiBKoPX0GmYf5rxen4PbZ3dCi.png', 1, '2026-01-12 21:18:14', '2026-01-12 21:18:14'),
(75, 15, 87, 'image', 'hotels/15/rooms/71/JsxTYwXHe2ApS7EAaQNbg1ZJlz36jIAlZI6YS3s7.png', 0, '2026-01-12 21:18:14', '2026-01-12 21:18:14'),
(76, 15, 87, 'image', 'hotels/15/rooms/71/QPEYl0FpUUDLPYMNiBKoPX0GmYf5rxen4PbZ3dCi.png', 1, '2026-01-12 21:18:14', '2026-01-12 21:18:14'),
(77, 15, 88, 'image', 'hotels/15/rooms/71/JsxTYwXHe2ApS7EAaQNbg1ZJlz36jIAlZI6YS3s7.png', 0, '2026-01-12 21:18:14', '2026-01-12 21:18:14'),
(78, 15, 88, 'image', 'hotels/15/rooms/71/QPEYl0FpUUDLPYMNiBKoPX0GmYf5rxen4PbZ3dCi.png', 1, '2026-01-12 21:18:14', '2026-01-12 21:18:14'),
(79, 15, 89, 'image', 'hotels/15/rooms/71/JsxTYwXHe2ApS7EAaQNbg1ZJlz36jIAlZI6YS3s7.png', 0, '2026-01-12 21:18:14', '2026-01-12 21:18:14'),
(80, 15, 89, 'image', 'hotels/15/rooms/71/QPEYl0FpUUDLPYMNiBKoPX0GmYf5rxen4PbZ3dCi.png', 1, '2026-01-12 21:18:14', '2026-01-12 21:18:14'),
(81, 15, 90, 'image', 'hotels/15/rooms/71/JsxTYwXHe2ApS7EAaQNbg1ZJlz36jIAlZI6YS3s7.png', 0, '2026-01-12 21:18:14', '2026-01-12 21:18:14'),
(82, 15, 90, 'image', 'hotels/15/rooms/71/QPEYl0FpUUDLPYMNiBKoPX0GmYf5rxen4PbZ3dCi.png', 1, '2026-01-12 21:18:14', '2026-01-12 21:18:14'),
(83, 15, 91, 'image', 'hotels/15/rooms/71/JsxTYwXHe2ApS7EAaQNbg1ZJlz36jIAlZI6YS3s7.png', 0, '2026-01-12 21:18:14', '2026-01-12 21:18:14'),
(84, 15, 91, 'image', 'hotels/15/rooms/71/QPEYl0FpUUDLPYMNiBKoPX0GmYf5rxen4PbZ3dCi.png', 1, '2026-01-12 21:18:14', '2026-01-12 21:18:14'),
(85, 15, 92, 'image', 'hotels/15/rooms/92/LaCG4MZv7GGT0B49Ldo5r7BSNPHLJx1vAP5pEhbA.png', 0, '2026-01-12 21:24:57', '2026-01-12 21:24:57'),
(86, 15, 92, 'image', 'hotels/15/rooms/92/PYEcRIASSLxKi530Lo0bhyz3Yq6xsjDl9qx7QqmQ.png', 1, '2026-01-12 21:24:57', '2026-01-12 21:24:57'),
(87, 15, 93, 'image', 'hotels/15/rooms/93/sLGrPJzrG94dUEjeWIzSgPHIKwRVaYgIFyApYzj3.png', 0, '2026-01-12 21:31:05', '2026-01-12 21:31:05'),
(88, 15, 93, 'image', 'hotels/15/rooms/93/Sgvvc53JWerh57a9T0Qn22r7U0b6mwcV6Kq2Ww16.png', 1, '2026-01-12 21:31:05', '2026-01-12 21:31:05'),
(89, 15, 70, 'image', 'hotels/15/rooms/70/LKknZCJnmQEgVq8YfTuNxuZ9vj6ZUxUVsBuHePRN.png', 0, '2026-01-12 21:32:25', '2026-01-12 21:32:25'),
(90, 15, 70, 'image', 'hotels/15/rooms/70/bJGx2M96OHDLg2l5YXtMfwnnrnnhWAeoaWqcqrAA.png', 1, '2026-01-12 21:32:25', '2026-01-12 21:32:25'),
(91, 11, 94, 'image', 'hotels/11/rooms/94/X8QjV63gMJLaRfvut5eewSgHdGXsQ8sD6R1S7FHw.jpg', 0, '2026-01-14 07:49:51', '2026-01-14 07:49:51');

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
(16, 7, NULL, 'standard', 954.00, 0.00, 0.00, 2, 3, 3, 1, '08:16:00', '10:20:00', '[{\"to_date\": \"2025-12-31\", \"to_time\": \"06:11\", \"from_date\": \"2025-12-23\", \"from_time\": \"01:19\"}]', '2025-12-27 12:51:28', '2025-12-27 12:51:28', NULL),
(17, 4, NULL, 'standard', 122.00, 0.00, 0.00, 1, 1, 1, 1, '14:00:00', '12:00:00', '[{\"to_date\": \"2025-12-25\", \"to_time\": \"12:49\", \"from_date\": \"2025-12-21\", \"from_time\": \"21:49\"}]', '2025-12-30 20:20:49', '2025-12-30 20:20:49', NULL),
(18, 4, NULL, 'standard', 122.00, 0.00, 0.00, 1, 1, 1, 1, '14:00:00', '12:00:00', '[{\"to_date\": \"2025-12-25\", \"to_time\": \"12:49\", \"from_date\": \"2025-12-21\", \"from_time\": \"21:49\"}]', '2025-12-30 20:20:49', '2025-12-30 20:20:49', NULL),
(19, 4, NULL, 'standard', 122.00, 0.00, 0.00, 1, 1, 1, 1, '14:00:00', '12:00:00', '[{\"to_date\": \"2025-12-25\", \"to_time\": \"12:49\", \"from_date\": \"2025-12-21\", \"from_time\": \"21:49\"}]', '2025-12-30 20:20:49', '2025-12-30 20:20:49', NULL),
(20, 4, NULL, 'standard', 122.00, 0.00, 0.00, 1, 1, 1, 1, '14:00:00', '12:00:00', '[{\"to_date\": \"2025-12-25\", \"to_time\": \"12:49\", \"from_date\": \"2025-12-21\", \"from_time\": \"21:49\"}]', '2025-12-30 20:20:49', '2025-12-30 20:20:49', NULL),
(21, 4, NULL, 'standard', 122.00, 0.00, 0.00, 1, 1, 1, 1, '14:00:00', '12:00:00', '[{\"to_date\": \"2025-12-25\", \"to_time\": \"12:49\", \"from_date\": \"2025-12-21\", \"from_time\": \"21:49\"}]', '2025-12-30 20:20:49', '2025-12-30 20:20:49', NULL),
(22, 4, NULL, 'standard', 122.00, 0.00, 0.00, 1, 1, 1, 1, '14:00:00', '12:00:00', '[{\"to_date\": \"2025-12-25\", \"to_time\": \"12:49\", \"from_date\": \"2025-12-21\", \"from_time\": \"21:49\"}]', '2025-12-30 20:20:49', '2025-12-30 20:20:49', NULL),
(23, 4, NULL, 'standard', 122.00, 0.00, 0.00, 1, 1, 1, 1, '14:00:00', '12:00:00', '[{\"to_date\": \"2025-12-25\", \"to_time\": \"12:49\", \"from_date\": \"2025-12-21\", \"from_time\": \"21:49\"}]', '2025-12-30 20:20:49', '2025-12-30 20:20:49', NULL),
(24, 4, NULL, 'standard', 122.00, 0.00, 0.00, 1, 1, 1, 1, '14:00:00', '12:00:00', '[{\"to_date\": \"2025-12-25\", \"to_time\": \"12:49\", \"from_date\": \"2025-12-21\", \"from_time\": \"21:49\"}]', '2025-12-30 20:20:49', '2025-12-30 20:20:49', NULL),
(25, 4, NULL, 'standard', 122.00, 0.00, 0.00, 1, 1, 1, 1, '14:00:00', '12:00:00', '[{\"to_date\": \"2025-12-25\", \"to_time\": \"12:49\", \"from_date\": \"2025-12-21\", \"from_time\": \"21:49\"}]', '2025-12-30 20:20:49', '2025-12-30 20:20:49', NULL),
(26, 4, NULL, 'standard', 122.00, 0.00, 0.00, 1, 1, 1, 1, '14:00:00', '12:00:00', '[{\"to_date\": \"2025-12-25\", \"to_time\": \"12:49\", \"from_date\": \"2025-12-21\", \"from_time\": \"21:49\"}]', '2025-12-30 20:20:49', '2025-12-30 20:20:49', NULL),
(27, 4, NULL, 'standard', 122.00, 0.00, 0.00, 1, 1, 1, 1, '14:00:00', '12:00:00', '[{\"to_date\": \"2025-12-25\", \"to_time\": \"12:49\", \"from_date\": \"2025-12-21\", \"from_time\": \"21:49\"}]', '2025-12-30 20:20:49', '2025-12-30 20:20:49', NULL),
(28, 4, NULL, 'standard', 122.00, 0.00, 0.00, 1, 1, 1, 1, '14:00:00', '12:00:00', '[{\"to_date\": \"2025-12-25\", \"to_time\": \"12:49\", \"from_date\": \"2025-12-21\", \"from_time\": \"21:49\"}]', '2025-12-30 20:20:49', '2025-12-30 20:20:49', NULL),
(29, 4, NULL, 'standard', 122.00, 0.00, 0.00, 1, 1, 1, 1, '14:00:00', '12:00:00', '[{\"to_date\": \"2025-12-25\", \"to_time\": \"12:49\", \"from_date\": \"2025-12-21\", \"from_time\": \"21:49\"}]', '2025-12-30 20:20:49', '2025-12-30 20:20:49', NULL),
(30, 4, NULL, 'standard', 122.00, 0.00, 0.00, 1, 1, 1, 1, '14:00:00', '12:00:00', '[{\"to_date\": \"2025-12-25\", \"to_time\": \"12:49\", \"from_date\": \"2025-12-21\", \"from_time\": \"21:49\"}]', '2025-12-30 20:20:49', '2025-12-30 20:20:49', NULL),
(31, 4, NULL, 'standard', 122.00, 0.00, 0.00, 1, 1, 1, 1, '14:00:00', '12:00:00', '[{\"to_date\": \"2025-12-25\", \"to_time\": \"12:49\", \"from_date\": \"2025-12-21\", \"from_time\": \"21:49\"}]', '2025-12-30 20:20:49', '2025-12-30 20:20:49', NULL),
(32, 4, NULL, 'standard', 122.00, 0.00, 0.00, 1, 1, 1, 1, '14:00:00', '12:00:00', '[{\"to_date\": \"2025-12-25\", \"to_time\": \"12:49\", \"from_date\": \"2025-12-21\", \"from_time\": \"21:49\"}]', '2025-12-30 20:20:49', '2025-12-30 20:20:49', NULL),
(33, 4, NULL, 'standard', 122.00, 0.00, 0.00, 1, 1, 1, 1, '14:00:00', '12:00:00', '[{\"to_date\": \"2025-12-25\", \"to_time\": \"12:49\", \"from_date\": \"2025-12-21\", \"from_time\": \"21:49\"}]', '2025-12-30 20:20:49', '2025-12-30 20:20:49', NULL),
(34, 4, NULL, 'standard', 122.00, 0.00, 0.00, 1, 1, 1, 1, '14:00:00', '12:00:00', '[{\"to_date\": \"2025-12-25\", \"to_time\": \"12:49\", \"from_date\": \"2025-12-21\", \"from_time\": \"21:49\"}]', '2025-12-30 20:20:49', '2025-12-30 20:20:49', NULL),
(35, 4, NULL, 'standard', 122.00, 0.00, 0.00, 1, 1, 1, 1, '14:00:00', '12:00:00', '[{\"to_date\": \"2025-12-25\", \"to_time\": \"12:49\", \"from_date\": \"2025-12-21\", \"from_time\": \"21:49\"}]', '2025-12-30 20:20:49', '2025-12-30 20:20:49', NULL),
(36, 4, NULL, 'standard', 122.00, 0.00, 0.00, 1, 1, 1, 1, '14:00:00', '12:00:00', '[{\"to_date\": \"2025-12-25\", \"to_time\": \"12:49\", \"from_date\": \"2025-12-21\", \"from_time\": \"21:49\"}]', '2025-12-30 20:20:49', '2025-12-30 20:20:49', NULL),
(37, 4, NULL, 'standard', 122.00, 0.00, 0.00, 1, 1, 1, 1, '14:00:00', '12:00:00', '[{\"to_date\": \"2025-12-25\", \"to_time\": \"12:49\", \"from_date\": \"2025-12-21\", \"from_time\": \"21:49\"}]', '2025-12-30 20:20:49', '2025-12-30 20:20:49', NULL),
(38, 4, NULL, 'standard', 122.00, 0.00, 0.00, 1, 1, 1, 1, '14:00:00', '12:00:00', '[{\"to_date\": \"2025-12-25\", \"to_time\": \"12:49\", \"from_date\": \"2025-12-21\", \"from_time\": \"21:49\"}]', '2025-12-30 20:20:49', '2025-12-30 20:20:49', NULL),
(39, 4, NULL, 'standard', 122.00, 0.00, 0.00, 1, 1, 1, 1, '14:00:00', '12:00:00', '[{\"to_date\": \"2025-12-25\", \"to_time\": \"12:49\", \"from_date\": \"2025-12-21\", \"from_time\": \"21:49\"}]', '2025-12-30 20:20:49', '2025-12-30 20:20:49', NULL),
(40, 4, NULL, 'standard', 122.00, 0.00, 0.00, 1, 1, 1, 1, '14:00:00', '12:00:00', '[{\"to_date\": \"2025-12-25\", \"to_time\": \"12:49\", \"from_date\": \"2025-12-21\", \"from_time\": \"21:49\"}]', '2025-12-30 20:20:49', '2025-12-30 20:20:49', NULL),
(42, 4, NULL, 'standard', 122.00, 0.00, 0.00, 1, 1, 1, 1, '14:00:00', '12:00:00', '[{\"to_date\": \"2025-12-25\", \"to_time\": \"12:49\", \"from_date\": \"2025-12-21\", \"from_time\": \"21:49\"}]', '2025-12-30 20:20:49', '2025-12-30 20:20:49', NULL),
(43, 4, NULL, 'standard', 122.00, 0.00, 0.00, 1, 1, 1, 1, '14:00:00', '12:00:00', '[{\"to_date\": \"2025-12-25\", \"to_time\": \"12:49\", \"from_date\": \"2025-12-21\", \"from_time\": \"21:49\"}]', '2025-12-30 20:20:49', '2025-12-30 20:20:49', NULL),
(49, 4, NULL, 'standard', 122.00, 0.00, 0.00, 1, 1, 1, 1, '14:00:00', '12:00:00', '[{\"to_date\": \"2025-12-25\", \"to_time\": \"12:49\", \"from_date\": \"2025-12-21\", \"from_time\": \"21:49\"}]', '2025-12-30 20:20:49', '2025-12-30 20:20:49', NULL),
(50, 4, NULL, 'standard', 122.00, 0.00, 0.00, 1, 1, 1, 1, '14:00:00', '12:00:00', '[{\"to_date\": \"2025-12-25\", \"to_time\": \"12:49\", \"from_date\": \"2025-12-21\", \"from_time\": \"21:49\"}]', '2025-12-30 20:20:49', '2025-12-30 20:20:49', NULL),
(51, 4, NULL, 'standard', 122.00, 0.00, 0.00, 1, 1, 1, 1, '14:00:00', '12:00:00', '[{\"to_date\": \"2025-12-25\", \"to_time\": \"12:49\", \"from_date\": \"2025-12-21\", \"from_time\": \"21:49\"}]', '2025-12-30 20:20:49', '2025-12-30 20:20:49', NULL),
(52, 4, NULL, 'standard', 122.00, 0.00, 0.00, 1, 1, 1, 1, '14:00:00', '12:00:00', '[{\"to_date\": \"2025-12-25\", \"to_time\": \"12:49\", \"from_date\": \"2025-12-21\", \"from_time\": \"21:49\"}]', '2025-12-30 20:20:49', '2025-12-30 20:20:49', NULL),
(53, 4, NULL, 'standard', 122.00, 0.00, 0.00, 1, 1, 1, 1, '14:00:00', '12:00:00', '[{\"to_date\": \"2025-12-25\", \"to_time\": \"12:49\", \"from_date\": \"2025-12-21\", \"from_time\": \"21:49\"}]', '2025-12-30 20:20:49', '2025-12-30 20:20:49', NULL),
(54, 4, NULL, 'standard', 122.00, 0.00, 0.00, 1, 1, 1, 1, '14:00:00', '12:00:00', '[{\"to_date\": \"2025-12-25\", \"to_time\": \"12:49\", \"from_date\": \"2025-12-21\", \"from_time\": \"21:49\"}]', '2025-12-30 20:20:49', '2025-12-30 20:20:49', NULL),
(55, 4, NULL, 'standard', 122.00, 0.00, 0.00, 1, 1, 1, 1, '14:00:00', '12:00:00', '[{\"to_date\": \"2025-12-25\", \"to_time\": \"12:49\", \"from_date\": \"2025-12-21\", \"from_time\": \"21:49\"}]', '2025-12-30 20:20:49', '2025-12-30 20:20:49', NULL),
(56, 4, NULL, 'standard', 122.00, 0.00, 0.00, 1, 1, 1, 1, '14:00:00', '12:00:00', '[{\"to_date\": \"2025-12-25\", \"to_time\": \"12:49\", \"from_date\": \"2025-12-21\", \"from_time\": \"21:49\"}]', '2025-12-30 20:20:49', '2025-12-30 20:20:49', NULL),
(57, 4, NULL, 'standard', 122.00, 0.00, 0.00, 1, 1, 1, 1, '14:00:00', '12:00:00', '[{\"to_date\": \"2025-12-25\", \"to_time\": \"12:49\", \"from_date\": \"2025-12-21\", \"from_time\": \"21:49\"}]', '2025-12-30 20:20:49', '2025-12-30 20:20:49', NULL),
(58, 4, NULL, 'standard', 122.00, 0.00, 0.00, 1, 1, 1, 1, '14:00:00', '12:00:00', '[{\"to_date\": \"2025-12-25\", \"to_time\": \"12:49\", \"from_date\": \"2025-12-21\", \"from_time\": \"21:49\"}]', '2025-12-30 20:20:49', '2025-12-30 20:20:49', NULL),
(59, 4, NULL, 'standard', 122.00, 0.00, 0.00, 1, 1, 1, 1, '14:00:00', '12:00:00', '[{\"to_date\": \"2025-12-25\", \"to_time\": \"12:49\", \"from_date\": \"2025-12-21\", \"from_time\": \"21:49\"}]', '2025-12-30 20:20:49', '2025-12-30 20:20:49', NULL),
(60, 4, NULL, 'standard', 122.00, 0.00, 0.00, 1, 1, 1, 1, '14:00:00', '12:00:00', '[{\"to_date\": \"2025-12-25\", \"to_time\": \"12:49\", \"from_date\": \"2025-12-21\", \"from_time\": \"21:49\"}]', '2025-12-30 20:20:49', '2025-12-30 20:20:49', NULL),
(61, 4, NULL, 'standard', 122.00, 0.00, 0.00, 1, 1, 1, 1, '14:00:00', '12:00:00', '[{\"to_date\": \"2025-12-25\", \"to_time\": \"12:49\", \"from_date\": \"2025-12-21\", \"from_time\": \"21:49\"}]', '2025-12-30 20:20:49', '2025-12-30 20:20:49', NULL),
(62, 4, NULL, 'standard', 122.00, 0.00, 0.00, 1, 1, 1, 1, '14:00:00', '12:00:00', '[{\"to_date\": \"2025-12-25\", \"to_time\": \"12:49\", \"from_date\": \"2025-12-21\", \"from_time\": \"21:49\"}]', '2025-12-30 20:20:49', '2025-12-30 20:20:49', NULL),
(63, 4, NULL, 'standard', 122.00, 0.00, 0.00, 1, 1, 1, 1, '14:00:00', '12:00:00', '[{\"to_date\": \"2025-12-25\", \"to_time\": \"12:49\", \"from_date\": \"2025-12-21\", \"from_time\": \"21:49\"}]', '2025-12-30 20:20:49', '2025-12-30 20:20:49', NULL),
(64, 4, NULL, 'standard', 122.00, 0.00, 0.00, 1, 1, 1, 1, '14:00:00', '12:00:00', '[{\"to_date\": \"2025-12-25\", \"to_time\": \"12:49\", \"from_date\": \"2025-12-21\", \"from_time\": \"21:49\"}]', '2025-12-30 20:20:49', '2025-12-30 20:20:49', NULL),
(65, 4, NULL, 'standard', 122.00, 0.00, 0.00, 1, 1, 1, 1, '14:00:00', '12:00:00', '[{\"to_date\": \"2025-12-25\", \"to_time\": \"12:49\", \"from_date\": \"2025-12-21\", \"from_time\": \"21:49\"}]', '2025-12-30 20:20:49', '2025-12-30 20:20:49', NULL),
(66, 4, NULL, 'standard', 122.00, 0.00, 0.00, 1, 1, 1, 1, '14:00:00', '12:00:00', '[{\"to_date\": \"2025-12-25\", \"to_time\": \"12:49\", \"from_date\": \"2025-12-21\", \"from_time\": \"21:49\"}]', '2025-12-30 20:20:49', '2025-12-30 20:20:49', NULL),
(70, 15, 'test', 'standard', 200.00, 123.00, 12.00, 2, 2, 3, 1, '14:00:00', '12:00:00', NULL, '2026-01-12 21:04:05', '2026-01-12 21:32:25', '[{\"id\": 1, \"image\": \"services/NlBocOTkUSayp0chWDmePfsI2Z8Dt52vSW52VRLz.jpg\", \"name_ar\": \"صورة\", \"name_en\": \"Carter Church\"}]'),
(71, 15, NULL, 'standard', 200.00, 0.00, 0.00, 2, 1, 3, 1, '14:00:00', '12:00:00', NULL, '2026-01-12 21:06:42', '2026-01-12 21:06:42', '[{\"id\": 1, \"image\": \"services/NlBocOTkUSayp0chWDmePfsI2Z8Dt52vSW52VRLz.jpg\", \"name_ar\": \"صورة\", \"name_en\": \"Carter Church\"}]'),
(72, 15, '1)', 'standard', 200.00, 0.00, 0.00, 2, 1, 3, 1, '14:00:00', '12:00:00', NULL, '2026-01-12 21:18:14', '2026-01-12 21:18:14', '[{\"id\": 1, \"image\": \"services/NlBocOTkUSayp0chWDmePfsI2Z8Dt52vSW52VRLz.jpg\", \"name_ar\": \"صورة\", \"name_en\": \"Carter Church\"}]'),
(73, 15, '2)', 'standard', 200.00, 0.00, 0.00, 2, 1, 3, 1, '14:00:00', '12:00:00', NULL, '2026-01-12 21:18:14', '2026-01-12 21:18:14', '[{\"id\": 1, \"image\": \"services/NlBocOTkUSayp0chWDmePfsI2Z8Dt52vSW52VRLz.jpg\", \"name_ar\": \"صورة\", \"name_en\": \"Carter Church\"}]'),
(74, 15, '3)', 'standard', 200.00, 0.00, 0.00, 2, 1, 3, 1, '14:00:00', '12:00:00', NULL, '2026-01-12 21:18:14', '2026-01-12 21:18:14', '[{\"id\": 1, \"image\": \"services/NlBocOTkUSayp0chWDmePfsI2Z8Dt52vSW52VRLz.jpg\", \"name_ar\": \"صورة\", \"name_en\": \"Carter Church\"}]'),
(75, 15, '4)', 'standard', 200.00, 0.00, 0.00, 2, 1, 3, 1, '14:00:00', '12:00:00', NULL, '2026-01-12 21:18:14', '2026-01-12 21:18:14', '[{\"id\": 1, \"image\": \"services/NlBocOTkUSayp0chWDmePfsI2Z8Dt52vSW52VRLz.jpg\", \"name_ar\": \"صورة\", \"name_en\": \"Carter Church\"}]'),
(76, 15, '5)', 'standard', 200.00, 0.00, 0.00, 2, 1, 3, 1, '14:00:00', '12:00:00', NULL, '2026-01-12 21:18:14', '2026-01-12 21:18:14', '[{\"id\": 1, \"image\": \"services/NlBocOTkUSayp0chWDmePfsI2Z8Dt52vSW52VRLz.jpg\", \"name_ar\": \"صورة\", \"name_en\": \"Carter Church\"}]'),
(77, 15, '6)', 'standard', 200.00, 0.00, 0.00, 2, 1, 3, 1, '14:00:00', '12:00:00', NULL, '2026-01-12 21:18:14', '2026-01-12 21:18:14', '[{\"id\": 1, \"image\": \"services/NlBocOTkUSayp0chWDmePfsI2Z8Dt52vSW52VRLz.jpg\", \"name_ar\": \"صورة\", \"name_en\": \"Carter Church\"}]'),
(78, 15, '7)', 'standard', 200.00, 0.00, 0.00, 2, 1, 3, 1, '14:00:00', '12:00:00', NULL, '2026-01-12 21:18:14', '2026-01-12 21:18:14', '[{\"id\": 1, \"image\": \"services/NlBocOTkUSayp0chWDmePfsI2Z8Dt52vSW52VRLz.jpg\", \"name_ar\": \"صورة\", \"name_en\": \"Carter Church\"}]'),
(79, 15, '8)', 'standard', 200.00, 0.00, 0.00, 2, 1, 3, 1, '14:00:00', '12:00:00', NULL, '2026-01-12 21:18:14', '2026-01-12 21:18:14', '[{\"id\": 1, \"image\": \"services/NlBocOTkUSayp0chWDmePfsI2Z8Dt52vSW52VRLz.jpg\", \"name_ar\": \"صورة\", \"name_en\": \"Carter Church\"}]'),
(80, 15, '9)', 'standard', 1000.00, 0.00, 0.00, 2, 1, 3, 1, '14:00:00', '12:00:00', NULL, '2026-01-12 21:18:14', '2026-01-12 21:41:14', '[{\"id\": 1, \"image\": \"services/NlBocOTkUSayp0chWDmePfsI2Z8Dt52vSW52VRLz.jpg\", \"name_ar\": \"صورة\", \"name_en\": \"Carter Church\"}]'),
(81, 15, '10)', 'standard', 1000.00, 0.00, 0.00, 2, 1, 3, 1, '14:00:00', '12:00:00', NULL, '2026-01-12 21:18:14', '2026-01-12 21:41:14', '[{\"id\": 1, \"image\": \"services/NlBocOTkUSayp0chWDmePfsI2Z8Dt52vSW52VRLz.jpg\", \"name_ar\": \"صورة\", \"name_en\": \"Carter Church\"}]'),
(82, 15, '11)', 'standard', 1000.00, 0.00, 0.00, 2, 1, 3, 1, '14:00:00', '12:00:00', NULL, '2026-01-12 21:18:14', '2026-01-12 21:41:14', '[{\"id\": 1, \"image\": \"services/NlBocOTkUSayp0chWDmePfsI2Z8Dt52vSW52VRLz.jpg\", \"name_ar\": \"صورة\", \"name_en\": \"Carter Church\"}]'),
(83, 15, '12)', 'standard', 1000.00, 0.00, 0.00, 2, 1, 3, 1, '14:00:00', '12:00:00', NULL, '2026-01-12 21:18:14', '2026-01-12 21:41:14', '[{\"id\": 1, \"image\": \"services/NlBocOTkUSayp0chWDmePfsI2Z8Dt52vSW52VRLz.jpg\", \"name_ar\": \"صورة\", \"name_en\": \"Carter Church\"}]'),
(84, 15, '13)', 'standard', 1000.00, 0.00, 0.00, 2, 1, 3, 1, '14:00:00', '12:00:00', NULL, '2026-01-12 21:18:14', '2026-01-12 21:41:14', '[{\"id\": 1, \"image\": \"services/NlBocOTkUSayp0chWDmePfsI2Z8Dt52vSW52VRLz.jpg\", \"name_ar\": \"صورة\", \"name_en\": \"Carter Church\"}]'),
(85, 15, '14)', 'standard', 1000.00, 0.00, 0.00, 2, 1, 3, 1, '14:00:00', '12:00:00', NULL, '2026-01-12 21:18:14', '2026-01-12 21:41:14', '[{\"id\": 1, \"image\": \"services/NlBocOTkUSayp0chWDmePfsI2Z8Dt52vSW52VRLz.jpg\", \"name_ar\": \"صورة\", \"name_en\": \"Carter Church\"}]'),
(86, 15, '15)', 'standard', 1000.00, 0.00, 0.00, 2, 1, 3, 1, '14:00:00', '12:00:00', NULL, '2026-01-12 21:18:14', '2026-01-12 21:41:14', '[{\"id\": 1, \"image\": \"services/NlBocOTkUSayp0chWDmePfsI2Z8Dt52vSW52VRLz.jpg\", \"name_ar\": \"صورة\", \"name_en\": \"Carter Church\"}]'),
(87, 15, '16)', 'standard', 1000.00, 0.00, 0.00, 2, 1, 3, 1, '14:00:00', '12:00:00', NULL, '2026-01-12 21:18:14', '2026-01-12 21:41:14', '[{\"id\": 1, \"image\": \"services/NlBocOTkUSayp0chWDmePfsI2Z8Dt52vSW52VRLz.jpg\", \"name_ar\": \"صورة\", \"name_en\": \"Carter Church\"}]'),
(88, 15, '17)', 'standard', 1000.00, 0.00, 0.00, 2, 1, 3, 1, '14:00:00', '12:00:00', NULL, '2026-01-12 21:18:14', '2026-01-12 21:41:14', '[{\"id\": 1, \"image\": \"services/NlBocOTkUSayp0chWDmePfsI2Z8Dt52vSW52VRLz.jpg\", \"name_ar\": \"صورة\", \"name_en\": \"Carter Church\"}]'),
(89, 15, '18)', 'standard', 1000.00, 0.00, 0.00, 2, 1, 3, 1, '14:00:00', '12:00:00', NULL, '2026-01-12 21:18:14', '2026-01-12 21:41:14', '[{\"id\": 1, \"image\": \"services/NlBocOTkUSayp0chWDmePfsI2Z8Dt52vSW52VRLz.jpg\", \"name_ar\": \"صورة\", \"name_en\": \"Carter Church\"}]'),
(90, 15, '19)', 'standard', 1000.00, 0.00, 0.00, 2, 1, 3, 1, '14:00:00', '12:00:00', NULL, '2026-01-12 21:18:14', '2026-01-12 21:41:14', '[{\"id\": 1, \"image\": \"services/NlBocOTkUSayp0chWDmePfsI2Z8Dt52vSW52VRLz.jpg\", \"name_ar\": \"صورة\", \"name_en\": \"Carter Church\"}]'),
(91, 15, '20)', 'standard', 200.00, 0.00, 0.00, 2, 1, 3, 1, '14:00:00', '12:00:00', NULL, '2026-01-12 21:18:14', '2026-01-12 21:18:14', '[{\"id\": 1, \"image\": \"services/NlBocOTkUSayp0chWDmePfsI2Z8Dt52vSW52VRLz.jpg\", \"name_ar\": \"صورة\", \"name_en\": \"Carter Church\"}]'),
(92, 15, NULL, 'standard', 300.00, 0.00, 0.00, 2, 1, 3, 1, '14:00:00', '12:00:00', NULL, '2026-01-12 21:24:57', '2026-01-12 21:24:57', '[{\"id\": 1, \"image\": \"services/NlBocOTkUSayp0chWDmePfsI2Z8Dt52vSW52VRLz.jpg\", \"name_ar\": \"صورة\", \"name_en\": \"Carter Church\"}]'),
(93, 15, 'room1', 'standard', 300.00, 100.00, 50.00, 2, 1, 3, 1, '14:00:00', '12:00:00', NULL, '2026-01-12 21:31:05', '2026-01-12 21:31:05', '[{\"id\": 1, \"image\": \"services/NlBocOTkUSayp0chWDmePfsI2Z8Dt52vSW52VRLz.jpg\", \"name_ar\": \"صورة\", \"name_en\": \"Carter Church\"}]'),
(94, 11, 'غرفة كبيره', 'standard', 250.00, 70.00, 10.00, 3, 2, 4, 1, '14:00:00', '12:00:00', NULL, '2026-01-14 07:49:51', '2026-01-14 07:49:51', '[{\"id\": 1, \"image\": \"services/NlBocOTkUSayp0chWDmePfsI2Z8Dt52vSW52VRLz.jpg\", \"name_ar\": \"صورة\", \"name_en\": \"Carter Church\"}]');

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
(39, '2025_12_26_132148_add_services_to_hotel_rooms_table', 19),
(40, '2025_12_28_203753_add_documents_to_hotels_table', 20),
(41, '2025_12_29_114447_create_private_car_media_table', 21),
(42, '2026_01_12_000000_create_services_table', 22),
(43, '2026_01_12_000001_add_user_id_to_various_tables', 23),
(44, '2026_01_14_211652_add_booking_fields_to_service_requests_table', 24),
(45, '2026_01_14_214105_create_closed_seats_table', 25),
(46, '2026_01_14_222005_create_booking_rooms_table', 26);

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
(11, 'App\\Models\\User', 5, 'auth_token', '81dbabd36e8df2ccf12a76e27e34df1702e9fb7d80a6dcba57572e290e49560d', '[\"*\"]', '2026-01-14 22:36:11', NULL, '2025-12-15 19:31:43', '2026-01-14 22:36:11'),
(19, 'App\\Models\\User', 7, 'auth_token', '65186573cf9891b6fbc02a346ea7b2eb7afea36c542da43ef53186723b318250', '[\"*\"]', '2026-01-14 14:46:28', NULL, '2025-12-30 12:51:51', '2026-01-14 14:46:28');

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
  `price_per_day` decimal(10,2) NOT NULL,
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
  `updated_at` timestamp NULL DEFAULT NULL,
  `price_per_hour` decimal(5,2) DEFAULT NULL,
  `car_model` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `private_cars`
--

INSERT INTO `private_cars` (`id`, `name_ar`, `name_en`, `price_per_day`, `seats_count`, `image`, `max_speed`, `acceleration`, `power`, `fuel_type`, `transmission`, `notes_ar`, `notes_en`, `is_active`, `created_at`, `updated_at`, `price_per_hour`, `car_model`, `user_id`) VALUES
(1, 'Chanda Woodward', 'Arthur Kim', 896.00, 10, 'private-cars/EEG2tkAkpPmXB1lh91bQV6JaoEQEkNqBSv1lH3L9.jpg', 89, 42.00, 93, 'gasoline', 'automatic', 'Saepe qui ut a quo d', 'Velit ipsum corrup', 1, '2025-12-01 01:11:23', '2025-12-28 17:42:18', 10.00, 'Toyota2000', NULL),
(3, 'تويوتا كورولا', 'Toyota Corolla', 1000.00, 5, NULL, 180, 9.50, 132, 'gasoline', 'automatic', 'سيارة اقتصادية ومناسبة للاستخدام اليومي', 'Economical car suitable for daily use', 1, '2026-01-13 17:49:57', '2026-01-13 17:51:07', 80.00, '2024', 5),
(4, 'تويوتا كورولا', 'Toyota Corolla', 1500.00, 4, NULL, 220, 3.80, 150, 'gasoline', 'automatic', 'سيارة راقية و جميله', 'very nice car', 1, '2026-01-14 07:06:04', '2026-01-14 07:06:04', 250.00, '2025', 7);

-- --------------------------------------------------------

--
-- Table structure for table `private_car_media`
--

CREATE TABLE `private_car_media` (
  `id` bigint UNSIGNED NOT NULL,
  `private_car_id` bigint UNSIGNED NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_column` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `private_car_media`
--

INSERT INTO `private_car_media` (`id`, `private_car_id`, `file_path`, `order_column`, `created_at`, `updated_at`) VALUES
(1, 1, 'private-cars/qDin0s0EZiOnLmylzcGCRVaTr3FkDakvjuN4B6K7.png', 1, '2025-12-29 11:58:18', '2025-12-29 11:58:18'),
(2, 1, 'private-cars/sbwJqQzELDtVNhUtMzqJb8C92VgRkp1wxe5No5Ro.jpg', 2, '2025-12-29 11:58:18', '2025-12-29 11:58:18'),
(3, 1, 'private-cars/jRSgbXgJZfrRTcLl5vb9RVxG002piHzCeYJKUjoq.jpg', 3, '2025-12-29 11:58:18', '2025-12-29 11:58:18'),
(4, 3, 'private-cars/rItuE6f4BEMEQi2F8HAbpKOo5TKz2iBHyx1M3VUz.png', 0, '2026-01-13 18:03:17', '2026-01-13 18:03:17'),
(5, 3, 'private-cars/chseECrWRXBn8oYhJf5CmrvHep3pu2meYu8vfC0d.png', 1, '2026-01-13 18:03:17', '2026-01-13 18:03:17'),
(8, 4, 'private-cars/1betTR7dVk3uDQJbpyoyCGZOcCxWOWy9NOhEVukc.jpg', 0, '2026-01-14 07:06:04', '2026-01-14 07:06:04'),
(9, 4, 'private-cars/bUVbpMk5B29oyF2y8ZH43tyTHgEMDZKgBzwm8Ntt.jpg', 1, '2026-01-14 07:06:04', '2026-01-14 07:06:04');

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
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint UNSIGNED NOT NULL,
  `name_ar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name_ar`, `name_en`, `image`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'صورة', 'Carter Church', 'services/NlBocOTkUSayp0chWDmePfsI2Z8Dt52vSW52VRLz.jpg', 1, '2026-01-12 19:58:41', '2026-01-12 19:58:58');

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
  `booking_type` enum('hours','days') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
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

INSERT INTO `service_requests` (`id`, `user_id`, `service_type`, `trip_id`, `bus_id`, `departure_location_ar`, `departure_location_en`, `arrival_location_ar`, `arrival_location_en`, `passengers_count`, `trip_date`, `private_car_id`, `duration_hours`, `booking_type`, `start_date`, `start_time`, `total_price`, `status`, `notes`, `request_reference`, `created_at`, `updated_at`) VALUES
(1, 5, 'bus', 1, 1, 'Id totam officia ali', 'Laborum Provident', 'Quisquam temporibus', 'Omnis nulla labore i', 3, '2025-12-29', NULL, NULL, NULL, NULL, NULL, 2019.00, 'pending', 'Please reserve window seats if possible', 'SR-694C428B08BE5', '2025-12-24 19:44:11', '2025-12-24 19:44:11'),
(2, 5, 'private_car', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 6, NULL, '2025-12-26', NULL, 5376.00, 'pending', 'Need a child seat', 'SR-694C43478E8E0', '2025-12-24 19:47:19', '2025-12-24 19:47:19'),
(3, 7, 'bus', 1, 1, 'Id totam officia ali', 'Laborum Provident', 'Quisquam temporibus', 'Omnis nulla labore i', 3, '2026-12-01', NULL, NULL, NULL, NULL, NULL, 2019.00, 'pending', NULL, 'SR-695549042525F', '2025-12-31 16:02:12', '2025-12-31 16:02:12'),
(4, 7, 'bus', 1, 1, 'Id totam officia ali', 'Laborum Provident', 'Quisquam temporibus', 'Omnis nulla labore i', 2, '2026-12-01', NULL, NULL, NULL, NULL, NULL, 1346.00, 'pending', NULL, 'SR-695549C16E077', '2025-12-31 16:05:21', '2025-12-31 16:05:21'),
(5, 7, 'bus', 1, 1, 'Id totam officia ali', 'Laborum Provident', 'Quisquam temporibus', 'Omnis nulla labore i', 1, '2026-12-01', NULL, NULL, NULL, NULL, NULL, 673.00, 'pending', NULL, 'SR-695549F15F027', '2025-12-31 16:06:09', '2025-12-31 16:06:09'),
(6, 5, 'private_car', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 5, 'hours', '2026-01-15', '14:30:00', 400.00, 'pending', 'Pickup from airport, please be on time', 'SR-69680ADE83306', '2026-01-14 21:30:06', '2026-01-14 21:30:06'),
(7, 5, 'private_car', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 48, 'days', '2026-01-20', NULL, 2000.00, 'pending', 'Family trip, need large trunk', 'SR-69680B2755972', '2026-01-14 21:31:19', '2026-01-14 21:31:19'),
(8, 5, 'bus', 1, 1, 'Id totam officia ali', 'Laborum Provident', 'Quisquam temporibus', 'Omnis nulla labore i', 3, '2026-12-01', NULL, NULL, NULL, NULL, NULL, 2019.00, 'pending', 'Please reserve seats near the window', 'SR-69680F6A837ED', '2026-01-14 21:49:30', '2026-01-14 21:49:30');

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
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `trips`
--

INSERT INTO `trips` (`id`, `departure_location_ar`, `departure_location_en`, `arrival_location_ar`, `arrival_location_en`, `bus_id`, `price`, `trip_date`, `trip_time`, `duration_minutes`, `is_active`, `created_at`, `updated_at`, `user_id`) VALUES
(1, 'Id totam officia ali', 'Laborum Provident', 'Quisquam temporibus', 'Omnis nulla labore i', 1, 673.00, '2026-12-01', '19:41:00', 75, 1, '2025-12-01 01:17:04', '2025-12-31 11:18:10', NULL),
(3, 'القاهرة', 'Cairo', 'الإسكندرية', 'Alexandria', 6, 250.00, '2026-01-20', '14:30:00', 180, 1, '2026-01-13 18:08:39', '2026-01-13 18:08:39', 5),
(4, 'الدمام', 'Dammam', 'الرياض', 'Riyadh', 7, 150.00, '2026-01-28', '00:04:00', 100, 1, '2026-01-13 22:04:23', '2026-01-14 14:36:04', 7);

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
(1, 'مسؤول النظام', 'admin@hotel.com', NULL, NULL, NULL, '$2y$12$hNDcR9TQUGFtzQJ3lxSOpuPKHIWHjKgxW8Kgi/u6wWZcVp1eDfJnG', 1, 1, 'IjFkeFxiP0VHhhVjjLLmE98QNVp7sy17dLcZ7U1iGralJY5Tyw5nEvKFM8bS', '2025-11-13 21:51:20', '2025-11-15 21:50:12'),
(2, 'أحمد محمد', 'ahmed@example.com', '01234567890', NULL, NULL, '$2y$12$.aD6IZSyYnHt0e0C99Dbeup3I2AcierfKb5Aj/d.u.//.BQ988efy', 0, 1, 'BwxXvl70QgSlABdumoi9VVOH5qbgZoa39w4TWvgObYT4dx6jyaIyA1VR06JT', '2025-11-15 21:49:40', '2025-11-15 21:50:13'),
(3, 'فاطمة علي', 'fatima@example.com', '01234567891', NULL, NULL, '$2y$12$t4iFgrhVXl6Fbb4CUbO8Vufjj.o5RHK3xUkDw6o2vC9rfp0euaXDC', 0, 1, NULL, '2025-11-15 21:49:40', '2025-11-15 21:50:13'),
(4, 'خالد أحمد', 'khaled@example.com', '01234567892', NULL, NULL, '$2y$12$Vy0xE4hOp5o2LuY5cffR4.FpxyoY0Q7HQ/PH6smOoD6y09oTHbSb.', 0, 1, NULL, '2025-11-15 21:49:40', '2025-11-15 21:50:13'),
(5, 'ali', 'mostafa@gmail.com', '01092702209', '1767176979_Screenshot_1767008003.png', NULL, '$2y$12$qs2FrlGbckmu/jJLctbVueesao9ou0owA/gyPtnHiUp3PTS2OaybW', 0, 1, NULL, '2025-12-12 15:49:38', '2025-12-31 10:29:39'),
(6, 'Ahmed Mohamed', 'ahmed2@example.com', '03234567890', NULL, NULL, '$2y$12$eRtEPb36hpZ6eDdc32UArOQ0fwKykmdnOlXq8BHMGgVpfkDQQ5d/.', 0, 1, NULL, '2025-12-15 09:02:13', '2025-12-15 09:02:13'),
(7, 'Ahmed Ibrahim', 'ahmed@gmail.com', '01003705602', '1767177453_1767177449786.jpg', NULL, '$2y$12$3dpa3ZAs3RPQO4QY8CnLSuyqgR4sqTvbsVdE/5RoS6g9p2sNIkfyS', 0, 1, NULL, '2025-12-15 09:17:00', '2025-12-31 10:37:33');

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

--
-- Dumping data for table `user_vouchers`
--

INSERT INTO `user_vouchers` (`id`, `user_id`, `voucher_id`, `booking_id`, `discount_amount`, `used_at`, `created_at`, `updated_at`) VALUES
(1, 5, 2, 2, 50.00, '2026-01-01 09:37:10', '2026-01-01 09:37:10', '2026-01-01 09:37:10');

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
(2, 'SAVE50', 'وفر 50 ريال', 'Save 50 SAR', 'خصم 50 ريال على الحجوزات أكثر من 300 ريال', '50 SAR discount on bookings over 300 SAR', 'fixed', 50.00, 300.00, NULL, 50, 1, '2025-12-24 19:32:20', '2026-01-24 19:32:20', 1, '2025-12-24 19:32:20', '2026-01-01 09:37:10'),
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
(1, 5, 100.00, 'SAR', '2025-12-24 19:27:55', '2025-12-24 19:27:55'),
(2, 7, 0.00, 'SAR', '2025-12-31 11:41:44', '2025-12-31 11:41:44');

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
-- Indexes for table `booking_rooms`
--
ALTER TABLE `booking_rooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `booking_rooms_booking_id_room_id_unique` (`booking_id`,`room_id`),
  ADD KEY `booking_rooms_room_id_foreign` (`room_id`);

--
-- Indexes for table `buses`
--
ALTER TABLE `buses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `buses_user_id_foreign` (`user_id`);

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
-- Indexes for table `closed_seats`
--
ALTER TABLE `closed_seats`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `closed_seats_trip_id_seat_number_unique` (`trip_id`,`seat_number`);

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `private_cars_user_id_foreign` (`user_id`);

--
-- Indexes for table `private_car_media`
--
ALTER TABLE `private_car_media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `private_car_media_private_car_id_foreign` (`private_car_id`);

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
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

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
  ADD KEY `trips_bus_id_index` (`bus_id`),
  ADD KEY `trips_user_id_foreign` (`user_id`);

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `booking_rooms`
--
ALTER TABLE `booking_rooms`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `buses`
--
ALTER TABLE `buses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `bus_seats`
--
ALTER TABLE `bus_seats`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `closed_seats`
--
ALTER TABLE `closed_seats`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `event_tickets`
--
ALTER TABLE `event_tickets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `hotels`
--
ALTER TABLE `hotels`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `hotel_managers`
--
ALTER TABLE `hotel_managers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `hotel_media`
--
ALTER TABLE `hotel_media`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT for table `hotel_rooms`
--
ALTER TABLE `hotel_rooms`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

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
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `policies`
--
ALTER TABLE `policies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `private_cars`
--
ALTER TABLE `private_cars`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `private_car_media`
--
ALTER TABLE `private_car_media`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `service_requests`
--
ALTER TABLE `service_requests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `trips`
--
ALTER TABLE `trips`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user_vouchers`
--
ALTER TABLE `user_vouchers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `wallets`
--
ALTER TABLE `wallets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
-- Constraints for table `booking_rooms`
--
ALTER TABLE `booking_rooms`
  ADD CONSTRAINT `booking_rooms_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_rooms_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `hotel_rooms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `buses`
--
ALTER TABLE `buses`
  ADD CONSTRAINT `buses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `bus_seats`
--
ALTER TABLE `bus_seats`
  ADD CONSTRAINT `bus_seats_service_request_id_foreign` FOREIGN KEY (`service_request_id`) REFERENCES `service_requests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bus_seats_trip_id_foreign` FOREIGN KEY (`trip_id`) REFERENCES `trips` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `closed_seats`
--
ALTER TABLE `closed_seats`
  ADD CONSTRAINT `closed_seats_trip_id_foreign` FOREIGN KEY (`trip_id`) REFERENCES `trips` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `private_cars`
--
ALTER TABLE `private_cars`
  ADD CONSTRAINT `private_cars_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `private_car_media`
--
ALTER TABLE `private_car_media`
  ADD CONSTRAINT `private_car_media_private_car_id_foreign` FOREIGN KEY (`private_car_id`) REFERENCES `private_cars` (`id`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `trips_bus_id_foreign` FOREIGN KEY (`bus_id`) REFERENCES `buses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `trips_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

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
