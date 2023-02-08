-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 08, 2023 at 12:14 PM
-- Server version: 5.7.36
-- PHP Version: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
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
) ENGINE=MyISAM AUTO_INCREMENT=407 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`id`, `name`, `display_name`, `description`, `size`, `image_views`, `album_id`, `uploaded_date`, `uploaded_by`, `created_at`, `updated_at`) VALUES
(405, '20221105071114pm_ass8v.jpg', 'Sweet Food', 'sweet food', 117156, 2, 98, '2022-11-05 07:11:14', '14', '2022-11-05 13:58:14', '2023-01-18 11:57:48'),
(402, '20221105071106pm_ass8v.jpg', 'Sweet Food', 'sweet food', 117156, 4, 98, '2022-11-05 07:11:06', '14', '2022-11-05 13:57:06', '2023-02-08 06:11:56'),
(403, '20221105071123pm_ass9d.jpg', 'Sweet Food', 'sweet food', 196664, 2, 98, '2022-11-05 07:11:23', '14', '2022-11-05 13:57:23', '2023-01-18 11:57:58'),
(404, '20221105071139pm_ass22s.jpg', 'Sweet Food', 'sweet food', 122860, 4, 98, '2022-11-05 07:11:39', '14', '2022-11-05 13:57:39', '2023-01-18 11:57:54'),
(401, '20221105071129pm_Harley_Davidson2.jpg', 'Bike', 'bike', 178897, 1, 100, '2022-11-05 07:11:29', '14', '2022-11-05 13:56:29', '2022-11-05 13:56:29'),
(400, '20221105071148pm_Harley_Davidson.jpg', 'Bike', 'bike', 218423, 1, 100, '2022-11-05 07:11:48', '14', '2022-11-05 13:55:48', '2022-11-05 13:55:48'),
(399, '20221105071115pm_blue-windows-wallpaper-17.jpg', 'Windows', 'windows background', 232449, 3, 100, '2022-11-05 07:11:15', '14', '2022-11-05 13:54:15', '2022-11-05 14:32:30'),
(398, '20221105071123pm_windows_8_default-wallpaper-1920x1080.jpg', 'Windows', 'windows background', 122619, 1, 100, '2022-11-05 07:11:23', '14', '2022-11-05 13:53:23', '2022-11-05 13:53:23'),
(397, '20221105071115pm_download-hd-wallpapers-for-windows-6.jpg', 'Windows', 'windows background', 282582, 2, 100, '2022-11-05 07:11:15', '14', '2022-11-05 13:52:15', '2022-11-05 13:52:18'),
(396, '20221105071117pm_202112230412539.jpg', 'Windows', 'windows', 70885, 1, 100, '2022-11-05 07:11:17', '14', '2022-11-05 13:50:17', '2022-11-05 13:50:17'),
(395, '20221105071151pm_202112230412538.jpg', 'Actor', 'actor', 110066, 1, 96, '2022-11-05 07:11:51', '14', '2022-11-05 13:49:51', '2022-11-05 13:49:51'),
(394, '20221105071131pm_202112230412537.jpg', 'Illustration', 'illustration', 199066, 1, 100, '2022-11-05 07:11:31', '14', '2022-11-05 13:49:31', '2022-11-05 13:49:31'),
(393, '20221105071104pm_202112230412536.jpg', 'baby', 'baby', 63942, 1, 99, '2022-11-05 07:11:04', '14', '2022-11-05 13:49:04', '2022-11-05 13:49:04'),
(392, '20221105071146pm_202112230412535.jpg', 'Sweet', 'sweet', 204980, 1, 98, '2022-11-05 07:11:46', '14', '2022-11-05 13:48:46', '2022-11-05 13:48:46'),
(391, '20221105071130pm_202112230412534.jpg', 'Girl', 'girl', 41516, 1, 99, '2022-11-05 07:11:30', '14', '2022-11-05 13:48:30', '2022-11-05 13:48:30'),
(390, '20221105071113pm_202112230412533.jpg', 'cute baby', 'baby', 16470, 1, 96, '2022-11-05 07:11:13', '14', '2022-11-05 13:48:13', '2022-11-05 13:48:13'),
(389, '20221105071154pm_202112230412531.Jpg', 'Tiger', 'tiger', 40899, 1, 95, '2022-11-05 07:11:54', '14', '2022-11-05 13:47:54', '2022-11-05 13:47:54'),
(388, '20221105071140pm_20211223041253.jpg', 'Girl', 'girl', 49106, 1, 99, '2022-11-05 07:11:40', '14', '2022-11-05 13:47:40', '2022-11-05 13:47:40'),
(387, '20221105071121pm_20211223041212.jpg', 'Girl', 'cute girl', 15943, 1, 99, '2022-11-05 07:11:21', '14', '2022-11-05 13:47:21', '2022-11-05 13:47:21'),
(386, '20221105071123pm_20211223041208.jpg', 'Linux', 'linux', 53741, 2, 100, '2022-11-05 07:11:23', '14', '2022-11-05 13:46:23', '2022-11-05 13:46:24'),
(385, '20221105071102pm_20211223041204.jpg', 'Baby', 'cute baby', 56504, 2, 96, '2022-11-05 07:11:02', '14', '2022-11-05 13:46:02', '2022-11-05 13:46:40'),
(384, '20221105071141pm_20200706110723.jpg', 'Jelabi', 'Sweet', 400517, 2, 98, '2022-11-05 07:11:41', '14', '2022-11-05 13:45:41', '2023-02-08 03:34:58');

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

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`email`, `token`, `created_at`) VALUES
('info@alinsworld.com', '$2y$10$SExFFvy1pe4Tcjjm1RWxjewlV8CzNZWIP19w2ypH9.MxLKiE1e7sa', '2023-02-08 04:03:41');

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
(4, 'PhotoGallary', '', '', 900, 20, '2018-05-07 12:01:14', '2023-02-08 04:36:01');

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
(14, 'rezwansaki', 'info@alinsworld.com', '$2y$10$y7rTrUlkBFgafI2QCQclAeJrmrmplaIy/zbHDhMaYRfNYD7ow7z96', 'lgr4BKdoWGrXa3xXwyZ8qCbxAs117jSovzj84gtZtUEOxNgllfaZxYivfT9T', '2018-05-07 12:03:59', '2023-02-08 03:38:43');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
