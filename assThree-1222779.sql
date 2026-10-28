-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 23, 2026 at 06:29 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `web1222779_souvenirstore`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `order_date`, `total`) VALUES
(1849762314, 1179394870, '2026-06-23 02:39:14', 135.00),
(2264302063, 2055836401, '2026-06-06 04:21:24', 35.00),
(2809096931, 2055836401, '2026-06-06 04:19:59', 72.00),
(4182994010, 2055836401, '2026-06-05 07:09:39', 35.00),
(9226400814, 2055836401, '2026-06-05 07:06:12', 35.00);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` bigint(20) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `unit_price`) VALUES
(1, 9226400814, 1, 1, 35.00),
(2, 4182994010, 1, 1, 35.00),
(3, 2809096931, 2, 1, 50.00),
(4, 2809096931, 16, 1, 22.00),
(5, 2264302063, 1, 1, 35.00),
(6, 1849762314, 1, 1, 35.00),
(7, 1849762314, 2, 2, 50.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `photo1` varchar(255) NOT NULL,
  `photo2` varchar(255) NOT NULL,
  `photo3` varchar(255) NOT NULL,
  `default_photo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `category`, `description`, `price`, `quantity`, `rating`, `photo1`, `photo2`, `photo3`, `default_photo`) VALUES
(1, 'Palestinian Embroidered Cushion', 'Embroidery', 'Traditional handmade Palestinian cushion.', 35.98, 10, 5, 'IMG_5079.jpg', 'IMG-4672.webp', 'il_fullxfull.5872806665_bzmy.webp', 'IMG_5079.jpg'),
(2, 'Olive Wood Spoon', 'Olive Wood', 'Handmade olive wood kitchen.', 50.00, 17, 4, '2_1.jpeg', '2_2.jpeg', '2_3.jpeg', '2_1.jpeg'),
(15, 'spoons', 'Kitchen', 'hand kitchen tool', 14.00, 5, 4, '15_1.jpeg', '15_2.jpeg', '15_3.jpeg', '15_1.jpeg'),
(16, 'ceramic', 'Ceramics', 'palstiniens hand ceramic', 22.00, 3, 5, '16_1.jpeg', '16_2.jpeg', '16_3.jpeg', '16_1.jpeg'),
(17, 'Palestinian Keffiyeh', 'Embroidery', 'Traditional Palestinian keffiyeh scarf', 25.00, 15, 5, '17_1.jpeg', '17_2.jpeg', '17_3.jpeg', '17_3.jpeg'),
(18, 'Olive Wood Bowl', 'Olive Wood', 'Handmade olive wood serving bowl', 31.02, 8, 4, '18_1.jpeg', '18_2.jpeg', '18_3.jpeg', '18_2.jpeg'),
(19, 'Ceramic Coffee Cup', 'Ceramics', 'Hand-painted Palestinian ceramic cup', 18.00, 12, 5, '19_1.jpeg', '19_2.jpeg', '19_3.jpeg', '19_2.jpeg'),
(20, 'Tatreez Wall Art', 'Embroidery', 'Handmade embroidered wall decoration', 45.00, 6, 5, '20_1.jpeg', '20_2.jpeg', '20_3.jpeg', '20_1.jpeg'),
(21, 'Wooden Jewelry Box', 'Olive Wood', 'Handmade olive wood jewelry box', 28.00, 10, 4, '21_1.jpeg', '21_2.jpeg', '21_3.jpeg', '21_3.jpeg'),
(22, 'Palestinian Mug', 'Ceramics', 'Ceramic mug with Palestinian design', 12.00, 20, 3, '22_1.jpeg', '22_2.jpeg', '22_3.jpeg', '22_1.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` bigint(20) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `dob` date NOT NULL,
  `flat_no` varchar(20) DEFAULT NULL,
  `street` varchar(100) NOT NULL,
  `city` varchar(50) NOT NULL,
  `country` varchar(50) NOT NULL,
  `postal_code` varchar(6) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Customer','Employee') DEFAULT 'Customer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `email`, `mobile`, `dob`, `flat_no`, `street`, `city`, `country`, `postal_code`, `password`, `role`) VALUES
(1179394870, 'ali', 'moon', 'ali12@gmail.com', '0546537826', '2000-02-06', '778', 'farhod', 'palestine', 'haifa', '990865', '$2y$10$kx73MCybMg9S1QBEErGR3uVetFl7UieQr1R.CYMWz2dsc0D123hEW', 'Customer'),
(2055836401, 'asma', 'azaj', 'shaimaazmi18@gmail.com', '0525357663', '2005-02-01', '3454', 'axdzsa', 'eand', 'sdswlle', '876652', '$2y$10$T/4p4r6T2OnslXs53pCgn.b4tTyofXPJi4xoFbC4A27TcD33UqQK.', 'Customer'),
(7179235859, 'ola', 'ali', 'asdb@gmail.com', '05253578', '2000-02-02', '345', 'الهاشميه', 'eand', 'Palestine', '876652', '$2y$10$8jVWvWBT.W.TG6aKwdup/eBEVRmRlVPQeITt1nnw.Hi3ZhvO1k1Fa', 'Customer'),
(8612419774, 'noor', 'ali', 'noor45@gmail.com', '5253576899', '1998-03-04', '3454', 'الهاشميه', 'eand', 'Palestine', '876652', '$2y$10$nDLXgbkiwWviEOZsVhGuMuovBfFsZfU5TWuTDE2L8UInannrYGMPW', 'Employee');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
