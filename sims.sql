-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 26, 2025 at 11:12 AM
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
-- Database: `sims`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_logins`
--

CREATE TABLE `admin_logins` (
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `email` char(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_logins`
--

INSERT INTO `admin_logins` (`username`, `password`, `last_login`, `email`) VALUES
('admin', 'password123', '2025-03-18 15:28:57', 'samkelokay2@gmail.com'),
('test_user', 'Samu1225%%', NULL, 'godthenorth@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `product` varchar(255) NOT NULL,
  `barcode` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `email` char(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`product`, `barcode`, `quantity`, `price`, `email`) VALUES
('Product B', '1234567550123', 590, 45.70, 'samkelokay2@gmail.com'),
('Product E', '1234567809876', 100, 36.99, 'godthenorth@gmail.com'),
('Product A', '1234567890123', 9, 19.99, 'godthenorth@gmail.com'),
('Product D', '1765342537399', 5, 79.99, 'samkelokay2@gmail.com'),
('Test 2', '8906545321', 15, 66.14, 'godthenorth@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `product` varchar(255) NOT NULL,
  `barcode` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `total_sales` int(11) NOT NULL,
  `total_revenue` decimal(10,2) NOT NULL,
  `day_last_bought` datetime DEFAULT NULL,
  `email` char(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`product`, `barcode`, `price`, `total_sales`, `total_revenue`, `day_last_bought`, `email`) VALUES
('Product B', '1234567550123', 70.99, 5, 9876.99, '2025-03-19 11:40:41', ''),
('Product D', '1765342537399', 129.99, 66, 8579.00, '2025-03-26 09:43:48', 'samkelokay2@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `product` varchar(255) NOT NULL,
  `barcode` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `supplier_name` varchar(255) NOT NULL,
  `supplier_number` varchar(10) NOT NULL,
  `email` char(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`product`, `barcode`, `quantity`, `price`, `supplier_name`, `supplier_number`, `email`) VALUES
('Product A', '1234567890123', 100, 15.00, 'Supplier X', '0123456789', 'samkelokay2@gmail.com'),
('Test Product', '890627635255d', 20, 67.99, 'PSuppliers', '0614450276', 'samkelokay2@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_logins`
--
ALTER TABLE `admin_logins`
  ADD PRIMARY KEY (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`barcode`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD KEY `fk_sales_inventory` (`barcode`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`barcode`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `fk_sales_inventory` FOREIGN KEY (`barcode`) REFERENCES `inventory` (`barcode`),
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`barcode`) REFERENCES `inventory` (`barcode`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
