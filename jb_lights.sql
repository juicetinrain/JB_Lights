-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 11, 2025 at 08:02 AM
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
(3, 'hII', 'MY NAME IS', '0988323123', 'NONO@GMAIL.COM', 'General Inquiry', 'NOO WAY', '2025-11-10 04:39:33', '127.0.0.1');

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
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `event_address` text NOT NULL,
  `event_location` text DEFAULT NULL,
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

INSERT INTO `reservations` (`id`, `contact_name`, `contact_email`, `contact_phone`, `event_type`, `event_date`, `event_address`, `event_location`, `package`, `total_amount`, `payment_method`, `downpayment_amount`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Justin Basco', 'justin@email.com', '12345678901', 'Wedding', '2026-08-20', 'Dau, Mabalacat City', '15.1963,120.6093', 'Basic Package', 5000.00, 'cod', 0.00, 'Confirmed', '2025-11-09 20:07:52', '2025-11-11 04:32:37'),
(2, 'Naven Cuenca', 'naven@email.com', '12345678902', 'Birthday', '2026-08-25', 'Angeles City', '15.1963,120.6093', 'Tables', 2000.00, 'cod', 0.00, 'Confirmed', '2025-11-09 20:07:52', '2025-11-11 04:32:37'),
(3, 'Tyron Gonzales', 'tyron@email.com', '12345678903', 'Corporate', '2026-08-04', 'San Fernando', '15.1963,120.6093', 'Basic Package', 5000.00, 'cod', 0.00, 'Confirmed', '2025-11-09 20:07:52', '2025-11-11 04:32:37'),
(4, 'Admin User', 'admin@jblights.com', '09000000000', 'Birthday', '2025-11-13', 'Bamban-Centenial Bridge, MacArthur Highway, Xevera, Mabalacat, Pampanga, Central Luzon, 2317, Philippines', '15.244329136494445,120.56646000621666', 'Premium Setup', 7000.00, 'gcash', 2100.00, 'Confirmed', '2025-11-11 04:37:15', '2025-11-11 04:39:08'),
(5, 'Nanana mmamw', 'errfjj@gga.com', '09334232112', 'Other', '2025-11-21', 'Xevera, Mabalacat, Pampanga, Central Luzon, 2317, Philippines', '15.2456825,120.5609601', 'Basic Setup', 5000.00, 'cod', 0.00, 'Pending', '2025-11-11 06:04:31', '2025-11-11 06:04:31');

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
(5, 'Nanana mmamw', 'errfjj@gga.com', 'nigga', 'user', NULL, NULL, '2025-11-11 06:03:34');

--
-- Indexes for dumped tables
--

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
-- Indexes for table `packages`
--
ALTER TABLE `packages`
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
-- AUTO_INCREMENT for table `contact_submissions`
--
ALTER TABLE `contact_submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
