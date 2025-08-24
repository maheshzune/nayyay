-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql300.infinityfree.com
-- Generation Time: Aug 24, 2025 at 01:51 PM
-- Server version: 11.4.7-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_38566052_lawyermanagement`
--

-- --------------------------------------------------------

--
-- Table structure for table `administrator`
--

CREATE TABLE `administrator` (
  `administrator_id` varchar(20) NOT NULL,
  `city` varchar(40) NOT NULL,
  `address` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `administrator`
--

INSERT INTO `administrator` (`administrator_id`, `city`, `address`) VALUES
('Admin010101', 'Ahmedabad', 'kalash business hub ,b/h Narendra Modi S');

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `booking_id` int(11) NOT NULL,
  `date` varchar(20) NOT NULL,
  `subject` varchar(150) NOT NULL,
  `description` varchar(300) NOT NULL,
  `client_id` varchar(20) NOT NULL,
  `lawyer_id` varchar(20) NOT NULL,
  `status` varchar(20) NOT NULL,
  `document` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`booking_id`, `date`, `subject`, `description`, `client_id`, `lawyer_id`, `status`, `document`) VALUES
(2, '2019-04-24', '', 'ok test', 'Client5cbab2a1987da', 'Lawyer5cba0637a2a93', 'Accepted', NULL),
(3, '2019-04-22', '', 'test ok', 'Client5cbab2a1987da', 'Lawyer5cba06796f40b', 'Accepted', NULL),
(4, '2019-04-17', '', 'eygdshjdfcd', 'Client5cbab2a1987da', 'Lawyer5cbb38fddeafa', 'Pending', NULL),
(5, '2019-04-24', '', 'ok ', 'Client5cbb36c2cfd64', 'Lawyer5cbb38fddeafa', 'Pending', NULL),
(6, '2019-04-17', '', 'jdncdjkfc', 'Client5cbb36c2cfd64', 'Lawyer5cba0637a2a93', 'Accepted', NULL),
(8, '2025-02-19', '', '', '<br />\r\n<b>Warning</', 'Lawyer5cba06796f40b', '', NULL),
(9, '2025-02-19', '', '', '<br />\r\n<b>Warning</', 'Lawyer5cba06796f40b', '', NULL),
(10, '2025-02-19', '', '', '<br />\r\n<b>Warning</', 'Lawyer5cba06796f40b', '', NULL),
(11, '2025-02-21', '', '', '<br />\r\n<b>Warning</', 'Lawyer5cba06796f40b', '', NULL),
(14, '2025-02-05', '', '', '<br />\r\n<b>Warning</', 'Lawyer5cba06796f40b', 'Pending', NULL),
(26, '2025-02-21 17:21:00', 'hello', 'hrrrrrrr', 'Client5cb6315a228dd', 'Lawyer5cba06796f40b', 'Accepted', 'uploads/1740154860_schlorship2025.pdf'),
(27, '2025-02-21 17:25:32', 'hello', 'hrrrrrrr', 'Client5cb6315a228dd', 'Lawyer5cba06796f40b', 'Pending', 'uploads/1740155132_schlorship2025.pdf'),
(28, '2025-02-21 17:25:57', 'hello', 'hrrrrrrr', 'Client5cb6315a228dd', 'Lawyer5cba06796f40b', 'Pending', 'uploads/1740155157_schlorship2025.pdf'),
(30, '2025-02-21 17:27:50', 'hello', 'hrrrrrrr', 'Client5cb6315a228dd', 'Lawyer5cba06796f40b', 'Pending', 'uploads/1740155270_schlorship2025.pdf'),
(31, '2025-02-21 17:27:54', 'hello', 'hrrrrrrr', 'Client5cb6315a228dd', 'Lawyer5cba06796f40b', 'Pending', 'uploads/1740155274_schlorship2025.pdf'),
(32, '2025-02-21 17:56:18', 'hi', 'hooooopppee', 'Client5cb6315a228dd', 'Lawyer5cba06796f40b', 'Rejected', 'uploads/1740156978_ration card_compressed_compressed.pdf'),
(33, '2025-03-03 20:19:32', 'theft case', 'theft happened in my house', 'Client5cb6315a228dd', 'Lawyer5cbab501ee0df', 'Accepted', 'uploads/33_1741031464.pdf'),
(34, '2025-03-11 17:29:56', 'frad', 'froad zalai', 'Client5cb6315a228dd', 'Lawyer5cba0637a2a93', 'Accepted', 'uploads/1741710596_c++ assignment.docx'),
(35, '2025-03-15 11:04:20', 'Divorce', 'i hate my husband ', 'Client5cb6315a228dd', 'Lawyer5cba06796f40b', 'Accepted', 'uploads/1742033060_c++ assignment.docx'),
(36, '2025-03-15 14:54:27', 'fire recovery', 'we had fire in our home', 'Client5cb6315a228dd', 'Lawyer5cbb38fddeafa', 'Pending', 'uploads/1742046867_schlorship2025.pdf'),
(37, '2025-03-17 06:22:21', 'theft case', 'this is theft case', 'Client5cb6315a228dd', 'Lawyer5cbab501ee0df', 'Accepted', 'uploads/1742188941_References_and_Bibliography.docx'),
(38, '2025-03-17 06:47:05', 'fire recovery', 'gggg', 'Client5cb6315a228dd', 'Lawyer5cba06796f40b', 'Accepted', 'uploads/1742190425_dfd.png'),
(39, '2025-03-17 07:10:38', 'theft', 'theft case', 'Client5cb6315a228dd', 'Lawyer5cba0637a2a93', 'Accepted', 'uploads/1742191838_dfd1level.png'),
(40, '2025-03-26 07:50:32', 'frad', 'dbsakdfjs', 'Client5cb6315a228dd', 'Lawyer5cba0637a2a93', 'Pending', 'uploads/1742971832_about.php'),
(41, '2025-04-14 13:15:30', 'jasdjiohioads', 'sdlasdihk', 'Client5cb6315a228dd', 'Lawyer67d6c08b61cfb', 'Pending', 'uploads/1744629330_Index1 (2).pdf'),
(42, '2025-04-14 13:15:55', 'jasdjiohioads', 'sdlasdihk', 'Client5cb6315a228dd', 'Lawyer67d6c08b61cfb', 'Pending', 'uploads/1744629355_Index1 (2).pdf'),
(43, '2025-04-14 13:16:17', 'jasdjiohioads', 'sdlasdihk', 'Client5cb6315a228dd', 'Lawyer67d6c08b61cfb', 'Pending', 'uploads/1744629377_Index1 (2).pdf'),
(44, '2025-04-14 13:16:27', 'jasdjiohioads', 'sdlasdihk', 'Client5cb6315a228dd', 'Lawyer67d6c08b61cfb', 'Pending', 'uploads/1744629387_Index1 (2).pdf'),
(45, '2025-04-14 13:17:33', 'jasdjiohioads', 'sdlasdihk', 'Client5cb6315a228dd', 'Lawyer67d6c08b61cfb', 'Pending', 'uploads/1744629453_Index1 (2).pdf'),
(46, '2025-04-14 13:47:12', 'fraud', 'misleading and defamation ', 'Client5cb6315a228dd', 'Lawyer67d6c08b61cfb', 'Pending', 'uploads/1744631232_Placement-Cell new.pdf'),
(47, '2025-04-15 05:44:55', 'theft case', 'home robbery ', 'Client5cb6315a228dd', 'Lawyer5cba0637a2a93', 'Pending', 'uploads/1744688695_WhatsApp Image 2025-04-15 at 01.10.07_9aa2065c.jpg'),
(48, '2025-04-15 06:12:32', 'fire recovery', 'dfghjkl;', 'Client5cb6315a228dd', 'Lawyer67d6c08b61cfb', 'Pending', 'uploads/1744690352_Index1 (1).pdf'),
(49, '2025-04-15 08:05:02', 'theft case', 'thhdksjnkj', 'Client5cb6315a228dd', 'Lawyer5cba0637a2a93', 'Pending', 'uploads/1744697102_Index1 (1).docx'),
(50, '2025-04-15 08:07:55', 'fire recovery', 'shkhjd', 'Client5cb6315a228dd', 'Lawyer5cba06796f40b', 'Pending', 'uploads/1744697275_Index1 (1).docx'),
(51, '2025-04-15 08:08:45', 'fire recovery', '12335855', 'Client5cb6315a228dd', 'Lawyer5cbab501ee0df', 'Accepted', 'uploads/1744697325_Index1 (1).pdf'),
(52, '2025-04-15 08:11:44', 'Divorce', 'divorce from husband ', 'Client5cb6315a228dd', 'Lawyer67d6c08b61cfb', 'Pending', 'uploads/1744697504_Index1 (1).pdf');

-- --------------------------------------------------------

--
-- Table structure for table `case`
--

CREATE TABLE `case` (
  `case_id` int(11) NOT NULL,
  `client_id` varchar(20) NOT NULL,
  `lawyer_id` varchar(20) NOT NULL,
  `case_type` varchar(50) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Pending',
  `location` varchar(100) NOT NULL,
  `fees` decimal(10,2) NOT NULL,
  `document_path` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deadline` date DEFAULT NULL,
  `hearing_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `case`
--

INSERT INTO `case` (`case_id`, `client_id`, `lawyer_id`, `case_type`, `status`, `location`, `fees`, `document_path`, `notes`, `created_at`, `updated_at`, `deadline`, `hearing_date`) VALUES
(1, 'Client5cb6315a228dd', 'Lawyer5cba06796f40b', 'robbery case', 'Won', 'akola', '3000.00', NULL, 'case date 13 dec', '2025-02-24 17:02:00', '2025-04-15 05:26:58', '2025-02-20', '2025-03-24'),
(2, 'Client5cb6315a228dd', 'Lawyer5cbab501ee0df', 'robbery case', 'Under Hearing', 'akot', '200.00', 'uploads/33_1741031759.pdf', 'fir filed', '2025-03-03 19:50:28', '2025-03-15 17:43:25', '2025-03-30', '2025-03-29'),
(3, 'Client5cbab2a1987da', 'Lawyer5cba0637a2a93', 'rape', 'Pending', 'pune', '2001.00', NULL, 'its ok', '2025-03-11 16:36:00', '2025-03-17 06:15:18', '2025-04-23', '2020-04-29'),
(4, 'Client5cb6315a228dd', 'Lawyer5cba06796f40b', 'divorce case', 'Under hearing', 'akola', '2000.00', NULL, 'case is being proceed in court', '2025-03-15 10:12:37', '2025-04-14 19:44:49', '2025-03-13', '2025-03-31'),
(5, 'Client5cb6315a228dd', 'Lawyer5cba06796f40b', 'hello', 'Accepted', 'goa', '4000.00', 'uploads/Index1.pdf', '', '2025-04-15 03:58:14', '2025-04-15 03:58:14', '2026-01-07', '2025-04-25');

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE `client` (
  `client_id` varchar(20) NOT NULL,
  `contact_number` varchar(15) NOT NULL,
  `full_address` varchar(200) NOT NULL,
  `city` varchar(100) NOT NULL,
  `zip_code` varchar(50) NOT NULL,
  `image` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `client`
--

INSERT INTO `client` (`client_id`, `contact_number`, `full_address`, `city`, `zip_code`, `image`) VALUES
('Client5cb6315a228dd', '08698636924', 'wadia boys hostel', 'akola', '411001', 'images/upload/1747832979_Sad, lonely, and broken hearted Teddy Bear Background _ Premium AI-generated image.jpeg'),
('Client5cbab2a1987da', '08698636924', 'wadia boys hostel', 'akola', '411001', 'images/upload/1747832979_Sad, lonely, and broken hearted Teddy Bear Background _ Premium AI-generated image.jpeg'),
('Client5cbb36c2cfd64', '08698636924', 'wadia boys hostel', 'akola', '411001', 'images/upload/1747832979_Sad, lonely, and broken hearted Teddy Bear Background _ Premium AI-generated image.jpeg'),
('Client67fca728b032a', '08698636924', 'wadia boys hostel', 'akola', '411001', 'images/upload/1747832979_Sad, lonely, and broken hearted Teddy Bear Background _ Premium AI-generated image.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `lawyer`
--

CREATE TABLE `lawyer` (
  `lawyer_id` varchar(20) NOT NULL,
  `bar_id` varchar(50) DEFAULT NULL,
  `bar_license` varchar(100) DEFAULT NULL,
  `contact_Number` varchar(15) NOT NULL,
  `university_College` varchar(100) NOT NULL,
  `degree` varchar(100) NOT NULL,
  `passing_year` varchar(100) NOT NULL,
  `full_address` varchar(200) NOT NULL,
  `city` varchar(50) NOT NULL,
  `zip_code` varchar(50) NOT NULL,
  `practise_Length` varchar(100) NOT NULL,
  `case_handle` varchar(500) NOT NULL,
  `speciality` varchar(100) NOT NULL,
  `image` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `lawyer`
--

INSERT INTO `lawyer` (`lawyer_id`, `bar_id`, `bar_license`, `contact_Number`, `university_College`, `degree`, `passing_year`, `full_address`, `city`, `zip_code`, `practise_Length`, `case_handle`, `speciality`, `image`) VALUES
('Lawyer5cba0637a2a93', 'BCMG/2024/12345', NULL, '01725655111', 'Silver Oak University', 'LLB', '2011', '24, Marine Drive, Nariman Point, Mumbai - 400021, Maharashtra', 'Chittagong', '400021', '1-5 years', 'Criminal matter,Civil matter,Writ Jurisdiction,', 'Commercial Law', '20250521091011_WhatsApp Image 2025-04-15 at 00.39.09_13c20282.jpg'),
('Lawyer5cba06796f40b', 'BCMG/2024/12345', NULL, '01725655222', 'M S University ', 'LLM', '2008', '8, Calangute Beach Road, Near Titoï¿½s Lane, Goa - 403516', 'Sylhet', '403516', '6-10 years', 'Criminal matter,Civil matter,Writ Jurisdiction,', 'Commercial Law', '20250522061857_1111111.jpg'),
('Lawyer5cba0723cc8f9', 'BCMG/2024/12345', NULL, '01725000111', 'L D University', 'LLB', '2012', '21, Ring Road, Adajan, Surat - 395009, Gujarat', 'Surat', '395009', '16-20 years', 'Civil matter,Writ Jurisdiction,Company law,Labour Law,Property Law,', 'Investment Law', 'appa.jpg'),
('Lawyer5cbab501ee0df', 'BCMG/2024/12345', NULL, '01725000022', 'BMCC', 'LLM', '2016', '17, Wardha Road, Ramdaspeth, Nagpur - 440012, Maharashtra', 'Rangpur', '440012', '11-15 years', 'Commercial matter,Construction law,Information Technology,Family Law,Religious Matter,Investment Matter,', 'Construction Law', '20250521091042_WhatsApp Image 2025-04-15 at 01.04.44_985a3994.jpg'),
('Lawyer5cbb38fddeafa', NULL, NULL, '01782343423', 'Symbyosis University', 'LLB', '2013', '14, Arera Colony, Near DB Mall, Bhopal - 462016, Madhya Pradesh', 'Mymensingh', '462016', '1-5 years', 'Contract law,Commercial matter,Construction law,Information Technology,', 'IT Law', '20250521091210_WhatsApp Image 2025-04-15 at 00.43.58_18b6d4c5.jpg'),
('Lawyer67a6360657255', 'BCMG/2024/12345', NULL, '08698636924', 'MIT University', 'LLB', '2011', '3, Sector 17, Near Elante Mall, Chandigarh - 160017, Punjab', 'Chandigarh', '160017', '16-20 years', 'Construction law,', 'Construction Law', '20250207173414_DALL·E 2025-01-10 11.38.23 - A modern and welcoming registration form banner with a vibrant and professional theme. The image should in'),
('Lawyer67b7605a8a071', NULL, NULL, '', '', '', '', '', '', '', '', '', '', ''),
('Lawyer67d6c08b61cfb', 'BCMG/2024/12345', NULL, '32125256565', 'ness', 'LLB', '2005', 'amhedabad', 'Chittagong', '380005', '6-10 years', 'Commercial,', 'Family Law', '20250521091134_WhatsApp Image 2025-04-15 at 01.10.07_9aa2065c.jpg'),
('Lawyer682de5a237c7e', NULL, NULL, '1234567889', 'Maharaja sayaji university ', 'LLB ', '2004', '301 A Siddhivinayak Flates  B/h Daliyawadi N/r Vimal Bakery, Wadi, Vadodara', 'Vadodara', '390001', '20', 'Criminal Cases,Civil Cases,Commercial Cases,Family Cases,', 'Criminal law', '20250521103930_IMG20250119173825.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `u_id` varchar(20) NOT NULL,
  `first_Name` varchar(100) NOT NULL,
  `last_Name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  `role` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`u_id`, `first_Name`, `last_Name`, `email`, `password`, `status`, `role`) VALUES
('Admin010101', 'admin', 'admin', 'admin@gmail.com', 'a', 'Active', 'Admin'),
('Client5cb6315a228dd', 'Mahesh ', 'Zene Zune', 'maheshzene@gmail.com ', 'm', 'Active', 'User'),
('Client5cbab2a1987da', 'Mahesh ', 'Zene Zune', 'arnbswami@gmail.com ', '123456', 'Active', 'User'),
('Client5cbb36c2cfd64', 'Mahesh ', 'Zene Zune', 'armanm@gmail.com ', '098765', 'Active', 'User'),
('Client67fca728b032a', 'Mahesh ', 'Zene Zune', 'm@gmail.com', '65985', 'Active', 'User'),
('Lawyer5cba0637a2a93', 'Diya ', 'Kalplish ', 'dkaplish@gmail.com ', 'd', 'Active', 'Lawyer'),
('Lawyer5cba06796f40b', 'Vanshika', 'Gidde', 'vgidde@gmail.com', 'v', 'Active', 'Lawyer'),
('Lawyer5cba0723cc8f9', 'Shivratana ', 'Jalkote', 'shivj@gmail.com ', '91351 ', 'Active', 'Lawyer'),
('Lawyer5cbab501ee0df', 'Saloni', 'Agarwal', 'salonia@gmail.com', 's', 'Active', 'Lawyer'),
('Lawyer5cbb38fddeafa', 'Bhoomika', 'Patil', 'bpatil@gmail.com ', 'b', 'Active', 'Lawyer'),
('Lawyer6780b2ed2676c', 'Radhika ', 'Patil', ' rpatil@gmail.com', ' 123456', 'Pending', 'Lawyer'),
('Lawyer67b7605a8a071', '', '', ' ', '97507 ', 'Inactive', 'Lawyer'),
('Lawyer67d6c08b61cfb', 'Dhruvika', 'Chitte', 'dhruvika@gmail.com ', 'd', 'Active', 'Lawyer'),
('Lawyer682de5a237c7e', 'Siara ', 'Awasthi', 'siara13@gmail.com ', '123456', 'Active', 'Lawyer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administrator`
--
ALTER TABLE `administrator`
  ADD PRIMARY KEY (`administrator_id`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`booking_id`);

--
-- Indexes for table `case`
--
ALTER TABLE `case`
  ADD PRIMARY KEY (`case_id`),
  ADD KEY `fk_case_client` (`client_id`),
  ADD KEY `fk_case_lawyer` (`lawyer_id`);

--
-- Indexes for table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`client_id`);

--
-- Indexes for table `lawyer`
--
ALTER TABLE `lawyer`
  ADD PRIMARY KEY (`lawyer_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`u_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `case`
--
ALTER TABLE `case`
  MODIFY `case_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `case`
--
ALTER TABLE `case`
  ADD CONSTRAINT `fk_case_client` FOREIGN KEY (`client_id`) REFERENCES `client` (`client_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_case_lawyer` FOREIGN KEY (`lawyer_id`) REFERENCES `lawyer` (`lawyer_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
