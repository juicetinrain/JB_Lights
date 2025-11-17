-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 17, 2025 at 03:43 AM
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
-- Database: `jb_lights`
--

-- --------------------------------------------------------

--
-- Table structure for table `cancellation_requests`
--

CREATE TABLE `cancellation_requests` (
  `id` int(11) NOT NULL,
  `reservation_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reason` text NOT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `admin_notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cancellation_requests`
--

INSERT INTO `cancellation_requests` (`id`, `reservation_id`, `user_id`, `reason`, `status`, `admin_notes`, `created_at`, `updated_at`) VALUES
(1, 6, 1, 'i hate thie neeed to update', 'approved', 'next time pay attention!', '2025-11-17 02:02:45', '2025-11-17 02:04:14');

-- --------------------------------------------------------

--
-- Table structure for table `contact_submissions`
--

CREATE TABLE `contact_submissions` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `ip_address` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_submissions`
--

INSERT INTO `contact_submissions` (`id`, `first_name`, `last_name`, `phone`, `email`, `subject`, `message`, `submitted_at`, `ip_address`) VALUES
(1, 'hII ', 'MY NAME IS', '0988323123', 'NONO@GMAIL.COM', 'General Inquiry', 'NOO WAY', '2025-11-10 04:37:04', '127.0.0.1'),
(2, 'hII', 'MY NAME IS', '0988323123', 'NONO@GMAIL.COM', 'General Inquiry', 'NOO WAY', '2025-11-10 04:39:20', '127.0.0.1'),
(3, 'hII', 'MY NAME IS', '0988323123', 'NONO@GMAIL.COM', 'General Inquiry', 'NOO WAY', '2025-11-10 04:39:33', '127.0.0.1'),
(4, 'Naven', 'Marl', '09231123321123', 'mymail@email.com', 'General Inquiry', 'nooooOo!!!!!!!!', '2025-11-17 02:10:19', '127.0.0.1'),
(5, 'naven', 'marl', '09324141431', 'test@jblights.com', 'General Inquiry', 'asdwserfregewrwggwrgrwwrggrwgrwgwr', '2025-11-17 02:24:21', '127.0.0.1'),
(6, 'naven', 'marl', '09324141431', 'test@jblights.com', 'General Inquiry', 'asdwserfregewrwggwrgrwwrggrwgrwgwr', '2025-11-17 02:33:08', '127.0.0.1'),
(7, 'naven', 'marl', '09324141431', 'test@jblights.com', 'General Inquiry', 'asdwserfregewrwggwrgrwwrggrwgrwgwr', '2025-11-17 02:33:17', '127.0.0.1'),
(8, 'naven', 'marl', '09324141431', 'test@jblights.com', 'General Inquiry', 'asdwserfregewrwggwrgrwwrggrwgrwgwr', '2025-11-17 02:33:23', '127.0.0.1');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `brand` varchar(50) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1,
  `available_quantity` int(11) DEFAULT 1,
  `condition` varchar(20) DEFAULT 'good',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `item_name`, `category`, `brand`, `quantity`, `available_quantity`, `condition`, `created_at`) VALUES
(1, 'Powered Speaker 15\"', 'Sound', 'JBL', 8, 6, 'excellent', '2025-11-09 20:07:52'),
(2, 'Digital Mixer', 'Sound', 'Behringer', 2, 2, 'good', '2025-11-09 20:07:52'),
(3, 'Wireless Microphone', 'Sound', 'Shure', 6, 4, 'excellent', '2025-11-09 20:07:52'),
(4, 'LED Par Light', 'Lighting', 'ADJ', 12, 10, 'good', '2025-11-09 20:07:52'),
(5, 'Moving Head Light', 'Lighting', 'Chauvet', 4, 3, 'excellent', '2025-11-09 20:07:52');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `contact_name` varchar(100) NOT NULL,
  `contact_email` varchar(100) NOT NULL,
  `contact_phone` varchar(20) NOT NULL,
  `event_type` varchar(50) NOT NULL,
  `event_date` date NOT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `event_address` text NOT NULL,
  `event_location` text DEFAULT NULL,
  `landmark_notes` text DEFAULT NULL,
  `preferred_contact` varchar(20) DEFAULT 'phone',
  `social_media_handle` varchar(100) DEFAULT NULL,
  `package` varchar(100) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(20) NOT NULL,
  `downpayment_amount` decimal(10,2) DEFAULT 0.00,
  `status` varchar(20) DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id`, `contact_name`, `contact_email`, `contact_phone`, `event_type`, `event_date`, `start_time`, `end_time`, `event_address`, `event_location`, `landmark_notes`, `preferred_contact`, `social_media_handle`, `package`, `total_amount`, `payment_method`, `downpayment_amount`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Justin Basco', 'justin@email.com', '12345678901', 'Wedding', '2026-08-20', NULL, NULL, 'Dau, Mabalacat City', '15.1963,120.6093', NULL, 'phone', NULL, 'Basic Package', 5000.00, 'cod', 0.00, 'Confirmed', '2025-11-09 20:07:52', '2025-11-11 04:32:37'),
(2, 'Naven Cuenca', 'naven@email.com', '12345678902', 'Birthday', '2026-08-25', NULL, NULL, 'Angeles City', '15.1963,120.6093', NULL, 'phone', NULL, 'Tables', 2000.00, 'cod', 0.00, 'Confirmed', '2025-11-09 20:07:52', '2025-11-11 04:32:37'),
(3, 'Tyron Gonzales', 'tyron@email.com', '12345678903', 'Corporate', '2026-08-04', NULL, NULL, 'San Fernando', '15.1963,120.6093', NULL, 'phone', NULL, 'Basic Package', 5000.00, 'cod', 0.00, 'Confirmed', '2025-11-09 20:07:52', '2025-11-11 04:32:37'),
(4, 'Admin User', 'admin@jblights.com', '09000000000', 'Birthday', '2025-11-13', '08:00:00', '17:00:00', 'Bamban-Centenial Bridge, MacArthur Highway, Xevera, Mabalacat, Pampanga, Central Luzon, 2317, Philippines', '15.244329136494445,120.56646000621666', NULL, 'phone', NULL, 'Premium Setup', 7000.00, 'gcash', 2100.00, 'Confirmed', '2025-11-11 04:37:15', '2025-11-11 04:39:08'),
(5, 'Nanana mmamw', 'errfjj@gga.com', '09334232112', 'Other', '2025-11-21', '08:00:00', '17:00:00', 'Xevera, Mabalacat, Pampanga, Central Luzon, 2317, Philippines', '15.2456825,120.5609601', NULL, 'phone', NULL, 'Basic Setup', 5000.00, 'cod', 0.00, 'Pending', '2025-11-11 06:04:31', '2025-11-11 06:04:31'),
(6, 'Admin', 'admin@jblights.com', '09000000000', 'Corporate', '2025-11-27', '08:00:00', '17:00:00', 'Location at 15.023188, 120.721207', '15.02318760846413,120.72120666503908', 'Xevera', 'facebook', 'haha guy', 'Basic Setup', 5000.00, 'cod', 0.00, 'Cancelled', '2025-11-17 00:02:56', '2025-11-17 02:04:14'),
(7, 'Navenia', 'kirito@gmail.com', '09081242132', 'Corporate', '2025-11-28', '08:00:00', '17:00:00', 'Lara, San Fernando, Pampanga, Central Luzon, 2000, Philippines', '15.0794,120.62', 'OLFU', 'facebook', 'Acneuc Nevan', 'Basic Setup', 5000.00, 'cod', 0.00, 'Pending', '2025-11-17 00:29:25', '2025-11-17 00:29:25');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` varchar(20) DEFAULT 'user',
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `user_type`, `phone`, `address`, `created_at`) VALUES
(1, 'Admin', 'admin@jblights.com', 'admin', 'admin', '09000000000', '', '2025-11-09 20:07:52'),
(2, 'Justin Basco', 'justin@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', '12345678901', NULL, '2025-11-09 20:07:52'),
(3, 'Naven Cuenca', 'naven@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', '12345678902', NULL, '2025-11-09 20:07:52'),
(4, 'Tyron Gonzales', 'tyron@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', '12345678903', NULL, '2025-11-09 20:07:52'),
(5, 'Nanana mmamw', 'errfjj@gga.com', 'nigga', 'user', NULL, NULL, '2025-11-11 06:03:34'),
(6, 'Nyayhahaha', 'kirito@gmail.com', 'asuna', 'user', '', '', '2025-11-17 00:27:39');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cancellation_requests`
--
ALTER TABLE `cancellation_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reservation_id` (`reservation_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `contact_submissions`
--
ALTER TABLE `contact_submissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cancellation_requests`
--
ALTER TABLE `cancellation_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contact_submissions`
--
ALTER TABLE `contact_submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cancellation_requests`
--
ALTER TABLE `cancellation_requests`
  ADD CONSTRAINT `cancellation_requests_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cancellation_requests_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
