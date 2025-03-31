-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 31, 2025 at 06:33 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eRent`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `booking_date` datetime NOT NULL,
  `payment_status` varchar(20) NOT NULL,
  `return_status` int(11) NOT NULL DEFAULT 0,
  `return_date` datetime DEFAULT NULL,
  `no_of_days` int(11) NOT NULL,
  `total_amount_paid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `vehicle_id`, `booking_date`, `payment_status`, `return_status`, `return_date`, `no_of_days`, `total_amount_paid`) VALUES
(4, 3, 3, '2025-03-30 17:41:00', 'Success', 1, '2025-03-30 21:14:42', 6, 3258),
(5, 3, 4, '2025-03-30 17:46:40', 'Success', 1, '2025-03-30 21:17:10', 10, 7800),
(6, 3, 4, '2025-03-30 17:53:19', 'Success', 1, '2025-03-30 21:29:43', 6, 4680),
(7, 3, 3, '2025-03-30 18:01:11', 'Success', 1, '2025-03-30 21:32:29', 43, 23349),
(8, 3, 4, '2025-03-31 04:17:33', 'Success', 1, '2025-03-31 07:48:02', 4, 3120);

-- --------------------------------------------------------

--
-- Table structure for table `chargingStations`
--

CREATE TABLE `chargingStations` (
  `id` int(11) NOT NULL,
  `chargingStationName` varchar(111) NOT NULL,
  `Location` varchar(111) NOT NULL,
  `PinCode` varchar(111) NOT NULL,
  `Latitude` varchar(111) NOT NULL,
  `Longitude` varchar(111) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chargingStations`
--

INSERT INTO `chargingStations` (`id`, `chargingStationName`, `Location`, `PinCode`, `Latitude`, `Longitude`) VALUES
(1, 'Go EC ', 'Pandalam', '689501', '9.225', '76.6785'),
(2, 'Go EC ', 'Adoor', '691523', '9.153', '76.7356');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `aadhar` varchar(20) NOT NULL,
  `pan` varchar(10) NOT NULL,
  `dob` date NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `middle_name`, `last_name`, `address`, `aadhar`, `pan`, `dob`, `email`, `password`) VALUES
(2, 'Anish', 'S', 'Nair', 'NEDUNGADAPPALLY KULANGARACKAL', '1234567890987654', 'ATNPN0584G', '2000-01-11', 'anish.vilayil.s@gmail.com', '$2y$10$2gJYjQi1X037dYeNGsss8Oa/mnSGe1THe6O9fO32YT4th9i0QDMDG'),
(3, 'Anish', 'S', 'Nair', 'NEDUNGADAPPALLY KULANGARACKAL', '1234567890987654', 'ATNPN0584G', '1980-01-17', 'anish.vilayil.s1@gmail.com', '$2y$10$JwHoeMO.mHAEoyxSbR2..e.zl8BnRtUuRbwjul5kYR8h8BFz4Hhdi');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` int(11) NOT NULL,
  `vehicle_number` varchar(20) NOT NULL,
  `model_name` varchar(100) NOT NULL,
  `manufacturer_name` varchar(100) NOT NULL,
  `model_year` int(11) NOT NULL,
  `image_link` text DEFAULT NULL,
  `seat_capacity` int(11) NOT NULL,
  `battery_range` varchar(50) NOT NULL,
  `availability_status` enum('Available','Rented','Maintenance') NOT NULL DEFAULT 'Available',
  `rent_rate` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`id`, `vehicle_number`, `model_name`, `manufacturer_name`, `model_year`, `image_link`, `seat_capacity`, `battery_range`, `availability_status`, `rent_rate`) VALUES
(1, 'KL26K6612', 'RAPID', 'SKODA', 2021, 'https://imgd.aeplcdn.com/664x374/n/cw/ec/171777/kylaq-exterior-right-front-three-quarter-6.jpeg?isig=0&q=80', 5, '650', 'Rented', '600.00'),
(3, 'KL26K6614', 'LAURA', 'SKODA', 2021, 'https://imgd.aeplcdn.com/664x374/n/cw/ec/171777/kylaq-exterior-right-front-three-quarter-6.jpeg?isig=0&q=80', 4, '543', 'Available', '543.00'),
(4, 'KL 45 H 6612', 'CURVV', 'TATA', 2024, 'https://stimg.cardekho.com/images/carexteriorimages/930x620/Tata/Curvv/9578/1723033064164/front-left-side-47.jpg', 5, '600', 'Available', '780.00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `vehicle_id` (`vehicle_id`);

--
-- Indexes for table `chargingStations`
--
ALTER TABLE `chargingStations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vehicle_number` (`vehicle_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `chargingStations`
--
ALTER TABLE `chargingStations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
