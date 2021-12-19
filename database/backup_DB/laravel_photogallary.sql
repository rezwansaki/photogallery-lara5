-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 06, 2020 at 11:20 PM
-- Server version: 5.7.26
-- PHP Version: 7.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laravel_photogallary`
--

-- --------------------------------------------------------

--
-- Table structure for table `albums`
--

DROP TABLE IF EXISTS `albums`;
CREATE TABLE IF NOT EXISTS `albums` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cover_image` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=120 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `albums`
--

INSERT INTO `albums` (`id`, `name`, `cover_image`, `created_at`, `updated_at`) VALUES
(99, 'Girls', 'Girls.jpg', '2018-05-07 12:42:31', '2018-05-07 12:42:31'),
(98, 'Foods', 'Foods.jpg', '2018-05-07 12:42:24', '2018-05-07 12:42:24'),
(97, 'Flowers', 'Flowers.jpg', '2018-05-07 12:42:16', '2018-05-07 12:42:16'),
(96, 'Boys', 'Boys.jpg', '2018-05-07 12:42:07', '2018-05-07 12:42:07'),
(95, 'Animals', 'Animals.jpg', '2018-05-07 12:41:56', '2018-05-07 12:41:56'),
(100, 'Scene', 'Scene.jpg', '2018-05-07 12:42:38', '2018-05-07 12:42:38');

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

DROP TABLE IF EXISTS `images`;
CREATE TABLE IF NOT EXISTS `images` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` int(11) NOT NULL,
  `image_views` int(11) NOT NULL DEFAULT '1',
  `album_id` int(11) NOT NULL,
  `uploaded_date` datetime NOT NULL,
  `uploaded_by` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=384 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`id`, `name`, `display_name`, `description`, `size`, `image_views`, `album_id`, `uploaded_date`, `uploaded_by`, `created_at`, `updated_at`) VALUES
(382, '20200706110723pm_20180507060552pm_201.jpg', '20180507060552pm_201', '20180507060552pm_201.jpg', 400517, 1, 98, '2020-07-06 11:07:23', '14', '2020-07-06 17:04:23', '2020-07-06 17:04:23'),
(379, '20200706110702pm_20180507060547pm_201.jpg', '20180507060547pm_201', '20180507060547pm_201.jpg', 63942, 1, 99, '2020-07-06 11:07:02', '14', '2020-07-06 17:04:02', '2020-07-06 17:04:02'),
(380, '20200706110708pm_20180507060549pm_201.jpg', '20180507060549pm_201', '20180507060549pm_201.jpg', 41516, 1, 99, '2020-07-06 11:07:08', '14', '2020-07-06 17:04:08', '2020-07-06 17:04:08'),
(381, '20200706110714pm_20180507060550pm_201.jpg', '20180507060550pm_201', '20180507060550pm_201.jpg', 73447, 1, 99, '2020-07-06 11:07:14', '14', '2020-07-06 17:04:14', '2020-07-06 17:04:14'),
(377, '20200706110745pm_20180507060542pm_201.jpg', '20180507060542pm_201', '20180507060542pm_201.jpg', 199066, 1, 99, '2020-07-06 11:07:45', '14', '2020-07-06 17:03:45', '2020-07-06 17:03:45'),
(378, '20200706110753pm_20180507060545pm_201.jpg', '20180507060545pm_201', '20180507060545pm_201.jpg', 55792, 1, 100, '2020-07-06 11:07:53', '14', '2020-07-06 17:03:53', '2020-07-06 17:03:53'),
(376, '20200706110737pm_20180507060535pm_201.jpg', '20180507060535pm_201', '20180507060535pm_201.jpg', 132074, 1, 96, '2020-07-06 11:07:37', '14', '2020-07-06 17:03:37', '2020-07-06 17:03:37'),
(375, '20200706110727pm_20180507060531pm_201.jpg', '20180507060531pm_201', '20180507060531pm_201.jpg', 56505, 1, 96, '2020-07-06 11:07:27', '14', '2020-07-06 17:03:27', '2020-07-06 17:03:27'),
(373, '20200706110712pm_20180507060524pm_201.jpg', '20180507060524pm_201', '20180507060524pm_201.jpg', 228486, 1, 96, '2020-07-06 11:07:12', '14', '2020-07-06 17:03:12', '2020-07-06 17:03:12'),
(374, '20200706110719pm_20180507060526pm_201.jpg', '20180507060526pm_201', '20180507060526pm_201.jpg', 61488, 1, 99, '2020-07-06 11:07:19', '14', '2020-07-06 17:03:19', '2020-07-06 17:03:19'),
(372, '20200706110705pm_20180507060521pm_201.jpg', '20180507060521pm_201', '20180507060521pm_201.jpg', 15943, 1, 99, '2020-07-06 11:07:05', '14', '2020-07-06 17:03:05', '2020-07-06 17:03:05'),
(371, '20200706110759pm_20180507060520pm_201.jpg', '20180507060520pm_201', '20180507060520pm_201.jpg', 49106, 1, 99, '2020-07-06 11:07:59', '14', '2020-07-06 17:02:59', '2020-07-06 17:02:59'),
(370, '20200706110753pm_20180507060519pm_201.jpg', '20180507060519pm_201', '20180507060519pm_201.jpg', 56504, 1, 96, '2020-07-06 11:07:53', '14', '2020-07-06 17:02:53', '2020-07-06 17:02:53'),
(369, '20200706110743pm_20180507060517pm_201.jpg', '20180507060517pm_201', '20180507060517pm_201.jpg', 138154, 1, 100, '2020-07-06 11:07:43', '14', '2020-07-06 17:02:43', '2020-07-06 17:02:43'),
(368, '20200706110735pm_20180507060516pm_201.jpg', '20180507060516pm_201', '20180507060516pm_201.jpg', 107951, 1, 95, '2020-07-06 11:07:35', '14', '2020-07-06 17:02:35', '2020-07-06 17:02:35'),
(367, '20200706110726pm_20180507060515pm_201.jpg', '20180507060515pm_201', '20180507060515pm_201.jpg', 16470, 1, 96, '2020-07-06 11:07:26', '14', '2020-07-06 17:02:26', '2020-07-06 17:02:26'),
(366, '20200706110719pm_20180507060510pm_201.jpg', '20180507060510pm_201', '20180507060510pm_201.jpg', 53741, 1, 100, '2020-07-06 11:07:19', '14', '2020-07-06 17:02:19', '2020-07-06 17:02:19'),
(365, '20200706110711pm_20180507060507pm_201.jpg', '20180507060507pm_201', '20180507060507pm_201.jpg', 70885, 1, 100, '2020-07-06 11:07:11', '14', '2020-07-06 17:02:11', '2020-07-06 17:02:11'),
(362, '20200706110734pm_20180507060500pm_201.jpg', '20180507060500pm_201', '20180507060500pm_201.jpg', 204980, 1, 98, '2020-07-06 11:07:46', '14', '2020-07-06 17:01:34', '2020-07-06 17:01:46'),
(363, '20200706110755pm_20180507060500pm_201.jpg', '20180507060500pm_201', '20180507060500pm_201.jpg', 201537, 1, 100, '2020-07-06 11:07:55', '14', '2020-07-06 17:01:55', '2020-07-06 17:01:55'),
(364, '20200706110703pm_20180507060507pm_201.Jpg', '20180507060507pm_201', '20180507060507pm_201.Jpg', 40899, 1, 95, '2020-07-06 11:07:03', '14', '2020-07-06 17:02:03', '2020-07-06 17:02:03'),
(383, '20200706110731pm_20180507060558pm_201.jpg', '20180507060558pm_201', '20180507060558pm_201.jpg', 110066, 1, 96, '2020-07-06 11:07:31', '14', '2020-07-06 17:04:31', '2020-07-06 17:04:31');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(13, '2014_10_12_000000_create_users_table', 5),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(5, '2017_12_26_200441_create_images_table', 3),
(6, '2017_12_26_201658_create_albums_table', 4),
(14, '2018_04_21_225201_create_settings_table', 6);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_to_reset_password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_of_email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `max_uploaded_file_size` int(11) NOT NULL,
  `total_images_to_display` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `project_name`, `email_to_reset_password`, `password_of_email`, `max_uploaded_file_size`, `total_images_to_display`, `created_at`, `updated_at`) VALUES
(4, 'PhotoGallary', 'alinsworld****@gmail.com', '*****', 900, 20, '2018-05-07 12:01:14', '2018-05-07 13:44:48');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(14, 'rezwansaki', 'rezwansaki@gmail.com', '$2y$10$fKu69pKL9gPeuaFXcgkuRu6bSihIfiJR9lR1zPKq/W0kUivM1qfjO', 'RhmGOjZSDJaITAeXgYFgspEUNZTSaLRbstrMyEZhu7HbvWVsyqyvyptLL5Ty', '2018-05-07 12:03:59', '2018-05-07 12:03:59');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
