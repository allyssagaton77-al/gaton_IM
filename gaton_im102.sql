-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 24, 2026 at 04:33 AM
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
-- Database: `gaton_im102`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Electronics'),
(2, 'Food'),
(3, 'Office Supplies'),
(4, 'Clothing'),
(5, 'Home Appliances');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `category_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `stock`, `category_id`, `supplier_id`, `created_at`) VALUES
(2, 'Mouse', 'Wireless optical mouse', 500.00, 50, 1, 1, '2026-07-23 00:56:59'),
(3, 'Keyboard', 'Mechanical keyboard', 1500.00, 30, 1, 1, '2026-07-13 00:56:12'),
(4, 'Bread', 'Fresh loaf bread', 50.00, 100, 2, 2, '2026-08-14 00:56:26'),
(5, 'Cake', 'Chocolate cake', 350.00, 20, 2, 2, '0000-00-00 00:00:00'),
(6, 'Cookies', 'Box of cookies', 120.00, 40, 2, 2, '2026-06-15 00:56:18'),
(7, 'Notebook', '200-page notebook', 75.00, 80, 3, 3, '2026-03-15 00:56:03'),
(8, 'Ballpen', 'Blue ink pen', 15.00, 200, 3, 3, '2026-02-15 00:52:16'),
(9, 'Stapler', 'Standard office stapler', 180.00, 25, 3, 3, '2026-02-15 00:56:23'),
(10, 'T-Shirt', 'Cotton round-neck shirt', 250.00, 60, 4, 3, '2026-06-12 00:56:16'),
(11, 'Electric Fan', '16-inch stand fan', 1800.00, 18, 5, 1, '2026-06-02 00:56:01'),
(12, 'Rice Cooker', '1.8L rice cooker', 2200.00, 12, 5, 1, '2026-06-08 21:56:17'),
(13, 'Cellphone', 'oppo a12823', 9.00, 56, 1, 1, '2026-06-15 01:20:33');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `contact_person` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `contact_person`, `phone`) VALUES
(1, 'TechCorp', 'John michael', '0912 345 6789'),
(2, 'Bakeshop Supply', 'Maria espinoza\r\n', '0918 765 4321'),
(3, 'Office World', 'Carlos yulo', '0917 111 321');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','staff') DEFAULT 'staff',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `role`, `created_at`) VALUES
(1, 'ally', 'ally@gmail.com', '$2y$10$kf/POVdXI1CdwIKf2RH7rOqlUfBO5A7jpE/DOs5w3OnRd7u8UJQ3G', 'staff', '2026-06-24 01:13:29'),
(3, 'allyssa', 'allyssa@gmail.com', '$2y$10$pxWhlH9TsrMnxJnZz3KyP.I.HFwqMV3g0YoFxFC849HNj/hGomrPq', 'admin', '2026-06-24 02:26:10'),
(4, 'kaka', 'kak@gmai.com', '$2y$10$y8lsc8XtW/xVrgFCkYRJOe12m2kj0NDyaeY5fqWVvs7onxn/NCAI6', 'staff', '2026-06-24 02:30:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
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
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
