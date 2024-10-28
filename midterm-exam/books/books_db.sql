-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 14, 2024 at 03:26 PM
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
-- Database: `books_db`
--
CREATE DATABASE IF NOT EXISTS `books_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `books_db`;

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `number_of_copies` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `number_of_copies`) VALUES
(1, 'The Great Gatsby', 'F. Scott Fitzgerald', 3),
(2, 'To Kill a Mockingbird', 'Harper Lee', 2),
(3, '1984', 'George Orwell', 3),
(4, 'Moby Dick', 'Herman Melville', 1),
(5, 'The Catcher in the Rye', 'J.D. Salinger', 2);

-- --------------------------------------------------------

--
-- Table structure for table `book_borrowing_transactions`
--

CREATE TABLE `book_borrowing_transactions` (
  `id` int(11) NOT NULL,
  `borrower_name` varchar(100) NOT NULL,
  `book_id` int(11) NOT NULL,
  `borrow_date` datetime NOT NULL,
  `return_date` datetime DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `status` enum('Borrowed','Returned') DEFAULT 'Borrowed',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `book_borrowing_transactions`
--

INSERT INTO `book_borrowing_transactions` (`id`, `borrower_name`, `book_id`, `borrow_date`, `return_date`, `remarks`, `status`, `created_at`) VALUES
(1, 'Sample', 3, '2024-10-14 00:00:00', NULL, '', 'Borrowed', '2024-10-14 13:02:23'),
(2, 'Jaydee', 4, '2024-10-15 00:00:00', '2024-10-16 00:00:00', '', 'Returned', '2024-10-14 13:02:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `book_borrowing_transactions`
--
ALTER TABLE `book_borrowing_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `book_id` (`book_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `book_borrowing_transactions`
--
ALTER TABLE `book_borrowing_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `book_borrowing_transactions`
--
ALTER TABLE `book_borrowing_transactions`
  ADD CONSTRAINT `book_borrowing_transactions_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
