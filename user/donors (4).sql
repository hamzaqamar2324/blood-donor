-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 05, 2025 at 02:26 PM
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
-- Database: `donors`
--

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `sender` varchar(255) NOT NULL,
  `receiver` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `registration`
--

CREATE TABLE `registration` (
  `user_id` int(50) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `contact` int(50) NOT NULL,
  `blood_type` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `popup_seen` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registration`
--

INSERT INTO `registration` (`user_id`, `user_name`, `user_email`, `contact`, `blood_type`, `city`, `password`, `popup_seen`) VALUES
(73, 'usama', 'usama12@gmail.com', 2147483647, 'A-', 'Karachi', '123', 0),
(74, 'herry', 'herry@gmail.com', 123123, 'A-', 'Sialkot', '123', 0),
(75, 'lifeflow', 'life@gmail.com', 123, 'O+', 'Sialkot', '123', 0),
(76, 'sadiq', 'sadiq@gmaiil.com', 92384, 'A-', 'Karachi', '123', 0),
(77, 'saad', 'saad@gmail.com', 1231235234, 'O+', 'Karachi', '123', 0),
(78, 'sadiq', 'sadiq1@gmaiil.com', 92384, 'B+', 'Karachi', '123', 0),
(79, 'hamza2', 'hamza2@gmail.com', 2341234, 'AB+', 'Rawalpindi', '123', 0),
(80, 'hamza2', 'hamza2@gmail.com', 2147483647, 'O+', 'Islamabad', '123', 0),
(81, 'saad45', 'saad123@gmail.com', 1231235234, 'O+', 'Karachi', '123', 0),
(82, 'hamza', 'hamza@gmail.com', 123, 'A-', 'Karachi', '123', 0),
(83, 'ahad', 'ahad@gmail.com', 2147483647, 'O+', 'Sialkot', '123', 0),
(84, 'usama', 'usama1@gmail.com', 2147483647, 'A-', 'Karachi', '123', 0),
(85, 'ahad1', 'ahad1@gmail.com', 45345, 'B-', 'Karachi', '123', 0),
(86, 'hamza', 'alam@gmail.com', 2147483647, 'AB-', 'Lahore-', '123', 0),
(87, 'minhaj', 'minhaj@gmail.com', 1231231, 'A-', 'Karachi', '123', 0),
(88, 'habiba', 'habiba123@gmail.com', 1234235, 'O+', 'Islamabad', '123', 0),
(89, 'hammad', 'hammad12@gmail.com', 2147483647, 'AB-', 'Lahore-', '123', 0),
(90, 'danish', 'danish@gmail.com', 2147483647, 'AB-', 'Islamabad', '123', 0),
(91, 'umm e habiba', 'habibaiqbal@123', 90909090, 'O+', 'Karachi', 'habibaiqbal', 0),
(92, 'Habiba Iqbal', 'habibaiqbal@123', 214748, 'O+', 'Karachi', 'bibbo', 0),
(93, 'huma', 'huma@gmail.com', 987, 'A+', 'Karachi', 'zille huma', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `registration`
--
ALTER TABLE `registration`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `registration`
--
ALTER TABLE `registration`
  MODIFY `user_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
