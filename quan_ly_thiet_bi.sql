-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 22, 2025 at 02:02 PM
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
-- Database: `quan_ly_thiet_bi`
--

-- --------------------------------------------------------

--
-- Table structure for table `add_device`
--

CREATE TABLE `add_device` (
  `id` int(5) NOT NULL,
  `device_name` varchar(50) NOT NULL,
  `device_image` varchar(5000) NOT NULL,
  `device_status` varchar(50) NOT NULL,
  `device_qty` varchar(50) NOT NULL,
  `available_qty` varchar(50) NOT NULL,
  `admin_username` varchar(50) NOT NULL,
  `location` varchar(100) NOT NULL DEFAULT 'Kho tổng'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `add_device`
--

INSERT INTO `add_device` (`id`, `device_name`, `device_image`, `device_status`, `device_qty`, `available_qty`, `admin_username`, `location`) VALUES
(5, 'Chuột', '../public_image/c101e30d2e9c1f163b288bbdb24e58f1_chuot.png', 'Còn mới', '89', '89', 'Administrator', 'Kho tổng'),
(6, 'Máy in', '../public_image/6edf17377c3b1bdca34558c4d81dcd81mayin.jpg', 'Còn mới', '5', '5', 'Administrator', 'Kho 1'),
(7, 'Máy chiếu', '../public_image/5bb642fc2991b93818a88e2d4582a8a9maychieu.jpg', 'Còn mới', '2', '2', 'Administrator', 'Kho tổng'),
(8, 'Màn hình', '../public_image/287980735d4cf34e41a5240ebaf1c5fbmanhinh.jpg', 'Còn mới ', '70', '70', 'Administrator', 'Kho 2'),
(9, 'Bàn phím', '../public_image/3ecc2374719a720f4ac9ad41f56b446abanphim.jpg', 'Còn mới', '30', '30', 'Administrator', 'Kho tổng'),
(10, 'Màn hình dell', '../public_image/ad3a65c9ac11e7ef1035b6fca1a60c2b.jpg', 'Còn mới', '45', '45', 'Administrator', 'Kho 2');

-- --------------------------------------------------------

--
-- Table structure for table `admin_registration`
--

CREATE TABLE `admin_registration` (
  `id` int(5) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(500) NOT NULL,
  `email` varchar(50) NOT NULL,
  `contact` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_registration`
--

INSERT INTO `admin_registration` (`id`, `firstname`, `lastname`, `username`, `password`, `email`, `contact`, `status`) VALUES
(1, 'Super', 'Admin', 'Administrator', '$2y$10$G/gFXV9wK5ieC6IybsxU3u.YF9pRYWjwPITuO86Sd705JC4veKFyO', 'administrator@gmail.com', '00000000', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `issue_device`
--

CREATE TABLE `issue_device` (
  `id` int(5) NOT NULL,
  `user_enrollment` varchar(50) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `user_email` varchar(50) NOT NULL,
  `user_contact` varchar(50) NOT NULL,
  `device_name` varchar(50) NOT NULL,
  `device_issue_date` varchar(50) NOT NULL,
  `quantity` int(50) NOT NULL,
  `location` varchar(100) NOT NULL,
  `device_return_date` varchar(50) NOT NULL,
  `user_username` varchar(50) NOT NULL,
  `status` varchar(50) DEFAULT 'Chờ duyệt'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `issue_device`
--

INSERT INTO `issue_device` (`id`, `user_enrollment`, `user_name`, `user_email`, `user_contact`, `device_name`, `device_issue_date`, `quantity`, `location`, `device_return_date`, `user_username`, `status`) VALUES
(25, 'USR20251017001', 'Tester Cyber', 'tester@gmail.com', '0123456789', 'Bàn phím', '2025-10-18', 20, '', '2025-10-18', 'tester', 'Đã duyệt trả'),
(26, 'USR20251017001', 'Tester Cyber', 'tester@gmail.com', '0123456789', 'Màn hình dell', '2025-10-18', 15, '', '2025-10-18', 'tester', 'Đã duyệt trả'),
(27, 'USR20251017001', 'Tester Cyber', 'tester@gmail.com', '0123456789', 'Bàn phím', '2025-10-18', 10, '', '2025-10-18', 'tester', 'Đã duyệt trả');

-- --------------------------------------------------------

--
-- Table structure for table `manager_registration`
--

CREATE TABLE `manager_registration` (
  `id` int(5) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(500) NOT NULL,
  `email` varchar(50) NOT NULL,
  `contact` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `manager_registration`
--

INSERT INTO `manager_registration` (`id`, `firstname`, `lastname`, `username`, `password`, `email`, `contact`, `status`) VALUES
(1, 'Super', 'Manager', 'Manager', '$2y$10$sKrh1Q0N.r6tyfRc0bRijOLuBB9Cn8XPzwEWrOlwBdGQ/DwNdtCHG', 'manager@gmail.com', '1111111', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `user_registration`
--

CREATE TABLE `user_registration` (
  `id` int(5) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(500) NOT NULL,
  `email` varchar(50) NOT NULL,
  `contact` varchar(50) NOT NULL,
  `enrollment` varchar(50) NOT NULL DEFAULT 'no',
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_registration`
--

INSERT INTO `user_registration` (`id`, `firstname`, `lastname`, `username`, `password`, `email`, `contact`, `enrollment`, `status`) VALUES
(1, 'Nguyen', 'An', 'annguyen', 'annguyen', 'annguyen@gmail.com', '0123456789', 'ND001', 'no'),
(2, 'Dinh', 'Binh', 'binhdinh', 'binhdinh', 'binhdinh@gmail.com', '023456789', 'ND002', 'no'),
(3, 'Hoang', 'Cong', 'conghoang', 'conghoang', 'conghoang@gmail.com', '03456789', 'ND003', 'no'),
(4, 'Tran', 'Dong', 'dongtran', 'dongtran', 'dongtran@gmail.com', '0456789', 'ND004', 'no'),
(7, 'Tester', 'Cyber', 'tester', '$2y$10$JCLniDV9CxYvGo8vCDJAzuvW9jzQ2uhwLXASHs0GzjB/xcPbvcD8K', 'dinhdong19082003@gmail.com', '0123456789', 'USR20251017001', 'no');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `add_device`
--
ALTER TABLE `add_device`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_registration`
--
ALTER TABLE `admin_registration`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `issue_device`
--
ALTER TABLE `issue_device`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `manager_registration`
--
ALTER TABLE `manager_registration`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_registration`
--
ALTER TABLE `user_registration`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `add_device`
--
ALTER TABLE `add_device`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `admin_registration`
--
ALTER TABLE `admin_registration`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `issue_device`
--
ALTER TABLE `issue_device`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `manager_registration`
--
ALTER TABLE `manager_registration`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_registration`
--
ALTER TABLE `user_registration`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
