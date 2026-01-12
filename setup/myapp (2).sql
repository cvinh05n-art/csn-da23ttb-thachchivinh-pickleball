-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 12, 2026 at 02:55 PM
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
-- Database: `myapp`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `DT` varchar(12) NOT NULL,
  `ngaydat` date NOT NULL,
  `giodat` time NOT NULL,
  `note` text DEFAULT NULL,
  `STATUS` varchar(20) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`id`, `name`, `DT`, `ngaydat`, `giodat`, `note`, `STATUS`) VALUES
(1, 'gegeg', '123456789', '2025-12-30', '11:15:00', '', 'pending'),
(2, 'user1', '123456789', '2026-01-07', '07:00:00', '', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('admin','chusan','user') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`, `role`) VALUES
(3, 'Admin', 'cvinh05n@gmail.com', '$2y$10$Z7N9rFpY/cHCFQUIvxiRmOZsEYO2uDLOFgbFrM8Lezv9NJLC2VESq', '2025-11-22 19:23:25', 'admin'),
(4, 'a1', 'cv123@gmail.com', '$2y$10$w4EO3V1pRzr7CpR/NRhGNOSg/g4PVPYLmrRAIlrBkhsz.mMn1ZDKy', '2025-11-23 00:57:45', 'user'),
(5, 'GEGEG', 'gegeg@gmail.com', '$2y$10$f8zwT8VK3kXLGkDr.qrnuuSm9qf1dF5HVVmCkQtphbJRDKG3fmuh6', '2025-12-24 17:11:31', 'user'),
(6, 'user1', 'user1@gmail.com', '$2y$10$xVaSmrJI.bw1S6Yar129yu.joWcBIUq2pUc6n4p49qF/QBL7hJjDS', '2026-01-04 02:06:20', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
