-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 02, 2024 at 07:49 PM
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
-- Database: `example`
--

-- --------------------------------------------------------

--
-- Table structure for table `adoption_request`
--

CREATE TABLE `adoption_request` (
  `User_ID` varchar(5) DEFAULT NULL,
  `Request_ID` varchar(5) NOT NULL,
  `Date_Of_Request` varchar(20) DEFAULT NULL,
  `Request_Status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `adoption_request`
--

INSERT INTO `adoption_request` (`User_ID`, `Request_ID`, `Date_Of_Request`, `Request_Status`) VALUES
('10001', '10101', '8/14/1990', 'Completed'),
('20002', '20202', '11/14/2024', 'In-Progress'),
('30003', '30303', '03/03/2024', 'In-Progress');

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE `client` (
  `User_ID` varchar(5) NOT NULL,
  `Name` varchar(50) DEFAULT NULL,
  `Email` varchar(50) DEFAULT NULL,
  `State_Of_Residency` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `client`
--

INSERT INTO `client` (`User_ID`, `Name`, `Email`, `State_Of_Residency`) VALUES
('10001', 'Meldin Bektic', 'mbektic@kent.edu', 'Ohio'),
('20002', 'Shaquille O. Neal', 'shaqattaq@hotmail.com', 'Texas'),
('30003', 'Calvin Cordozar Broadus Jr.', '420BlazeIt@gmail.com', 'California');

-- --------------------------------------------------------

--
-- Table structure for table `dinosaur`
--

CREATE TABLE `dinosaur` (
  `User_ID` varchar(5) DEFAULT NULL,
  `Request_ID` varchar(5) DEFAULT NULL,
  `Shelter_ID` varchar(5) DEFAULT NULL,
  `Dinosaur_ID` varchar(5) NOT NULL,
  `Name` varchar(50) DEFAULT NULL,
  `Species` varchar(50) DEFAULT NULL,
  `Age` varchar(25) DEFAULT NULL,
  `Gender` varchar(20) DEFAULT NULL,
  `Size` varchar(20) DEFAULT NULL,
  `Price` decimal(8,2) DEFAULT NULL,
  `Adoption_Status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dinosaur`
--

INSERT INTO `dinosaur` (`User_ID`, `Request_ID`, `Shelter_ID`, `Dinosaur_ID`, `Name`, `Species`, `Age`, `Gender`, `Size`, `Price`, `Adoption_Status`) VALUES
(NULL, NULL, '11111', '12345', 'Chomper', 'T-Rex', '7 Months', 'Male', 'Medium', 10000.00, 'Available'),
(NULL, NULL, '33333', '44313', 'Karen', 'Velociraptor', '8 Years', 'Female', 'Medium', 15000.00, 'Available'),
('10001', '10101', NULL, '46290', 'Brittany', 'Pterodactyl', '15 Years', 'Female', 'Small', 8000.00, 'Adopted'),
('20002', '20202', '22222', '67890', 'Tony', 'Triceratops', '4 Years', 'Male', 'Large', 20000.00, 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `shelter`
--

CREATE TABLE `shelter` (
  `Shelter_ID` varchar(5) NOT NULL,
  `Name` varchar(50) DEFAULT NULL,
  `Location` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shelter`
--

INSERT INTO `shelter` (`Shelter_ID`, `Name`, `Location`) VALUES
('11111', 'Prehistoric Palace', 'Cleveland, OH'),
('22222', 'Igneous Inn', 'Forks, WA'),
('33333', 'Dino Dugout', 'San Francisco, CA');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adoption_request`
--
ALTER TABLE `adoption_request`
  ADD PRIMARY KEY (`Request_ID`),
  ADD KEY `FK_User_ID` (`User_ID`);

--
-- Indexes for table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`User_ID`);

--
-- Indexes for table `dinosaur`
--
ALTER TABLE `dinosaur`
  ADD PRIMARY KEY (`Dinosaur_ID`),
  ADD KEY `FK_User_ID_Dinosaur` (`User_ID`),
  ADD KEY `FK_Request_ID_Dinosaur` (`Request_ID`),
  ADD KEY `FK_Shelter_ID_Dinosaur` (`Shelter_ID`);

--
-- Indexes for table `shelter`
--
ALTER TABLE `shelter`
  ADD PRIMARY KEY (`Shelter_ID`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `adoption_request`
--
ALTER TABLE `adoption_request`
  ADD CONSTRAINT `FK_User_ID` FOREIGN KEY (`User_ID`) REFERENCES `client` (`User_ID`);

--
-- Constraints for table `dinosaur`
--
ALTER TABLE `dinosaur`
  ADD CONSTRAINT `FK_Request_ID_Dinosaur` FOREIGN KEY (`Request_ID`) REFERENCES `adoption_request` (`Request_ID`),
  ADD CONSTRAINT `FK_Shelter_ID_Dinosaur` FOREIGN KEY (`Shelter_ID`) REFERENCES `shelter` (`Shelter_ID`),
  ADD CONSTRAINT `FK_User_ID_Dinosaur` FOREIGN KEY (`User_ID`) REFERENCES `client` (`User_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
