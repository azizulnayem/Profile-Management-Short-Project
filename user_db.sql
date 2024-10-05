-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 19, 2024 at 03:14 PM
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
-- Database: `user_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `tourist_packages`
--

CREATE TABLE `tourist_packages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tourist_packages`
--

INSERT INTO `tourist_packages` (`id`, `name`, `description`, `image`, `price`) VALUES
(1, 'Mountain Adventure', 'A thrilling adventure in the mountains.', 'uploads/mountain.jpg', 499.00),
(2, 'Beach Getaway', 'Relax on the sandy beaches with this getaway.', 'uploads/beach.jpg', 299.00),
(3, 'City Tour', 'Explore the vibrant city life with guided tours.', 'uploads/city.jpg', 199.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `name`, `image`) VALUES
(7, 'Lulu', '$2y$10$CF9w73W4MbFfGcSaZFU9s.nTXm6FCQfJyflbW9OFpl2dGEq3ucRyu', 'lulu@gmail.com', 'Lulu Khan', 'uploads/5ae598141b0fabd2f83288210afdcc19.jpg'),
(8, 'Mohsina', '$2y$10$6Ovs9y6/FLcBFe2QJ8mCFusx34QDU8Nc73x2Pvg4kN97OJSLVwN0i', 'mohsina@gmail.com', 'Mehnaj', 'uploads/9e888a48d50f89173dfba48223667d43.jpg'),
(9, 'Mehedi', '$2y$10$gkDYvocH9eKxRUv6lC14nOOtr6jE0p4nTG1ZhynQaR.sEX1ZkTeQe', 'mehedi@gmail.com', 'Mehedi Hasan', 'uploads/3fd8f6897ca3a3d07eeceff548757dc7.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tourist_packages`
--
ALTER TABLE `tourist_packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tourist_packages`
--
ALTER TABLE `tourist_packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
