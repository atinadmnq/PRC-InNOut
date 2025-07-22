-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 22, 2025 at 02:35 AM
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
-- Database: `prc_inout`
--

-- --------------------------------------------------------

--
-- Table structure for table `contents`
--

CREATE TABLE `contents` (
  `id` int(11) NOT NULL,
  `regional_office` varchar(100) DEFAULT NULL,
  `contact_person` varchar(100) DEFAULT NULL,
  `designation` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contents`
--

INSERT INTO `contents` (`id`, `regional_office`, `contact_person`, `designation`) VALUES
(1, 'PRC-NCR (Manila)', 'Ms. Henrietta P. Narvaez', 'Chief ARD'),
(2, 'PRC Region I - Rosales', 'Atty. Arl Ruth B. Sacay-Sabelo', 'Regional Director'),
(3, 'Tuguegarao City', 'Mr. Juan G. Alilam, Jr.', 'Officer-in-Charge'),
(4, 'San Fernando, Pampanga', 'Mr. Paul H. Aban', 'Officer-in-Charge'),
(5, 'Lucena City', 'Dir. Reynaldo V. Cristobal', 'Officer-in-Charge'),
(6, 'Legaspi City', 'Dir. Sharo B. Lo', 'Regional Director'),
(7, 'Iloilo City', 'Dir. Romel B. Balisang', 'Officer-in-Charge'),
(8, 'Cebu City', 'Dir. Narcival Randie Z. Taquiqui', 'Regional Director'),
(9, 'Tacloban City', 'Dir. Armond M. Englis', 'Officer-in-Charge'),
(10, 'Pagadian City', 'Dir. Sharo B. Lo', 'Officer-in-Charge'),
(11, 'Cagayan de Oro City', 'Dir. Julie L. Sabalza', 'Officer-in-Charge'),
(12, 'Davao City', 'Dir. Raquel R. Abrantes', 'Regional Director'),
(13, 'General Santos', 'Dir. Raquel R. Abrantes', 'Regional Director'),
(14, 'Butuan City', 'Dir. Cheryl P. Elicano', 'Regional Director'),
(15, 'Koronadal', 'Rotelo B. Cabugsa', 'Regional Director'),
(16, 'Regional Office IVB - MIMAROPA', 'Reynaldo D. Agcaoili', 'Regional Director');

-- --------------------------------------------------------

--
-- Table structure for table `incoming`
--

CREATE TABLE `incoming` (
  `id` int(11) NOT NULL,
  `ctrlNum` varchar(100) NOT NULL,
  `source` varchar(255) NOT NULL,
  `dateRecd` date NOT NULL,
  `timeRe` time NOT NULL,
  `subj` text NOT NULL,
  `attachment` text NOT NULL,
  `stat` varchar(50) NOT NULL,
  `actionUnit` varchar(255) NOT NULL,
  `dateRel` date NOT NULL,
  `recipient` varchar(255) NOT NULL,
  `intial` varchar(50) NOT NULL,
  `trackingNum` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `incoming`
--

INSERT INTO `incoming` (`id`, `ctrlNum`, `source`, `dateRecd`, `timeRe`, `subj`, `attachment`, `stat`, `actionUnit`, `dateRel`, `recipient`, `intial`, `trackingNum`, `created_at`) VALUES
(5, 'adada', 'daedada', '2025-07-16', '01:07:00', 'dada', 'dada', 'Pending', 'dad', '2025-07-15', 'adad', 'ada', 'adasd', '2025-07-15 17:07:51');

-- --------------------------------------------------------

--
-- Table structure for table `outgoing`
--

CREATE TABLE `outgoing` (
  `id` int(11) NOT NULL,
  `date_received` date NOT NULL,
  `control_number` varchar(100) NOT NULL,
  `division_section` varchar(255) NOT NULL,
  `contents` text NOT NULL,
  `package_type` varchar(100) NOT NULL,
  `pieces` int(11) NOT NULL,
  `contact_person` varchar(100) DEFAULT NULL,
  `designation` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `outgoing`
--

INSERT INTO `outgoing` (`id`, `date_received`, `control_number`, `division_section`, `contents`, `package_type`, `pieces`, `contact_person`, `designation`) VALUES
(6, '2025-07-07', '123-965', 'n/a', 'PRC-NCR (Manila)', 'test', 1, 'Ms. Henrietta P. Narvaez', 'Chief ARD'),
(7, '2025-07-09', 'adada', 'test', 'Koronadal', 'ada', 1, 'Rotelo B. Cabugsa', 'Regional Director'),
(8, '2025-07-21', 'adada', 'n/a', 'San Fernando, Pampanga', 'saa', 1, 'Mr. Paul H. Aban', 'Officer-in-Charge'),
(9, '2025-07-01', '121', 'fghj', 'Cagayan de Oro City', 'saa', 122, 'Dir. Julie L. Sabalza', 'Officer-in-Charge');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`) VALUES
(8, 'admin', '$2y$10$ZX6AvUcFL07M5vASbVcY2upf2YGAOt0qsWeIItvH09V4yujZByhIq', 'test@prc.mail');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contents`
--
ALTER TABLE `contents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `incoming`
--
ALTER TABLE `incoming`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `outgoing`
--
ALTER TABLE `outgoing`
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
-- AUTO_INCREMENT for table `contents`
--
ALTER TABLE `contents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `incoming`
--
ALTER TABLE `incoming`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `outgoing`
--
ALTER TABLE `outgoing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
