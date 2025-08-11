-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 11, 2025 at 05:17 PM
-- Server version: 8.0.31
-- PHP Version: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fyp`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `passphrase_hash` varchar(255) NOT NULL,
  `image_password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=138 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `passphrase_hash`, `image_password`, `created_at`) VALUES
(122, 'shasmitha', 'shxsmithadurai2123@gmail.com', '$2y$10$uypRulAYwYKlcr4HirFjLufV97hMZ71AQ53haFoUjRPIRAGLfLyg.', 'http://localhost/FYP2/images/pic()4.jpg,http://localhost/FYP2/images/pic5.jpeg,images/user_6819b9eb5dc0d6.76802202.jpg', '2025-05-06 07:27:39'),
(137, 'test', 'test2@gmail.com', '$2y$10$HppvcBWlx1t83kwUlA4IFOKyV.OmrOw4RV7aKkLicJkOxb3Wu0Igy', 'http://localhost/FYP2/images/pic1.webp,http://localhost/FYP2/images/pic2.jpg,http://localhost/FYP2/images/pic3.webp', '2025-08-11 17:08:54'),
(131, 'Logensss', 'logen3195ss@gmail.com', '$2y$10$l1QcSonUNWyK9N06.kDcH.v0umuTRAXFYcUyCoZcjHpXgqWXnKvVy', 'http://localhost/FYP2/images/pic1.jpg,http://localhost/FYP2/images/()pic2.png,http://localhost/FYP2/images/pic()3.jpg', '2025-05-07 07:05:45'),
(127, 'rishee', 'keesanrishee@gmail.com', '$2y$10$bqfxPVPlQ4L1oaGEuMaMJOVzAye9lHOYnMix51.BhSjSK11hWNOkK', 'http://localhost/FYP2/images/pic()3.jpg,http://localhost/FYP2/images/pic()4.jpg,http://localhost/FYP2/images/()pic2.png', '2025-05-07 06:22:21'),
(135, 'Logen', 'logen3195@gmail.com', '$2y$10$YyeOWuUR6rk3zgh5v6kGE.lKcDEs9TuB9mNYqQ78WEmKJscinlMwa', 'images/user_6822eaee136bd3.82615128.jpeg,images/user_6822eaee1405f7.76979442.jpeg,images/user_6822eaee148a10.84520795.jpeg', '2025-05-13 06:47:10'),
(136, 'steve', 'steve2@gmail.com', '$2y$10$eN6kTZAc2MmbbthDi9MSVuAXadxcvB3.mIrZs7dcyilYFWw6dbznu', 'http://localhost/FYP2/images/pic1.webp,http://localhost/FYP2/images/pic2.jpg,http://localhost/FYP2/images/pic3.webp', '2025-08-11 17:05:20');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
