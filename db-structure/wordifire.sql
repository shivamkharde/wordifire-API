-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 21, 2020 at 10:56 AM
-- Server version: 10.1.32-MariaDB
-- PHP Version: 7.2.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wordifire`
--

-- --------------------------------------------------------

--
-- Table structure for table `notification_configurations`
--

CREATE TABLE `notification_configurations` (
  `id` int(255) NOT NULL,
  `player_id` varchar(5000) NOT NULL,
  `joined_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `word_data`
--

CREATE TABLE `word_data` (
  `id` int(255) NOT NULL,
  `word` varchar(500) NOT NULL,
  `defination` varchar(5000) NOT NULL,
  `pronounciation_audio` varchar(5000) NOT NULL,
  `pronounciation_text` varchar(6000) NOT NULL,
  `origin` varchar(6000) NOT NULL,
  `example` varchar(6000) NOT NULL,
  `example_source` varchar(5000) NOT NULL,
  `word_podcast` varchar(5000) NOT NULL,
  `timestamp` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `notification_configurations`
--
ALTER TABLE `notification_configurations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `word_data`
--
ALTER TABLE `word_data`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_word` (`word`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `notification_configurations`
--
ALTER TABLE `notification_configurations`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `word_data`
--
ALTER TABLE `word_data`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
