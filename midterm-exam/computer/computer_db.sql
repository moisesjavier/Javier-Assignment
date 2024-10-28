-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 14, 2024 at 01:52 PM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 7.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `computer_db`
--
CREATE DATABASE IF NOT EXISTS `computer_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `computer_db`;

-- --------------------------------------------------------

--
-- Table structure for table `computer_rentals`
--

CREATE TABLE `computer_rentals` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime DEFAULT NULL,
  `computer_unit_id` int(11) NOT NULL,
  `remarks` text DEFAULT NULL,
  `status` enum('In Use','Completed') DEFAULT 'In Use',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `computer_rentals`
--

INSERT INTO `computer_rentals` (`id`, `customer_name`, `start_time`, `end_time`, `computer_unit_id`, `remarks`, `status`, `created_at`) VALUES
(1, 'Jaydee', '2024-10-14 02:30:00', '2024-10-14 03:02:00', 1, 'Okay', 'Completed', '2024-10-13 18:32:08'),
(2, 'Jaydee', '2024-10-14 02:53:00', '2024-10-14 02:56:00', 3, '', 'Completed', '2024-10-13 18:53:20'),
(4, 'Jaydee', '2024-10-14 03:03:00', '2024-10-14 03:04:00', 1, '', 'Completed', '2024-10-13 19:03:03');

-- --------------------------------------------------------

--
-- Table structure for table `computer_units`
--

CREATE TABLE `computer_units` (
  `id` int(11) NOT NULL,
  `unit_name` varchar(20) NOT NULL,
  `is_available` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `computer_units`
--

INSERT INTO `computer_units` (`id`, `unit_name`, `is_available`) VALUES
(1, 'Unit1', 1),
(2, 'Unit2', 1),
(3, 'Unit3', 1),
(4, 'Unit4', 1),
(5, 'Unit5', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `computer_rentals`
--
ALTER TABLE `computer_rentals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `computer_unit_id` (`computer_unit_id`);

--
-- Indexes for table `computer_units`
--
ALTER TABLE `computer_units`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `computer_rentals`
--
ALTER TABLE `computer_rentals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `computer_units`
--
ALTER TABLE `computer_units`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `computer_rentals`
--
ALTER TABLE `computer_rentals`
  ADD CONSTRAINT `computer_rentals_ibfk_1` FOREIGN KEY (`computer_unit_id`) REFERENCES `computer_units` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
