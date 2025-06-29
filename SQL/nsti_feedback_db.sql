-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 26, 2025 at 01:41 PM
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
-- Database: `nsti_feedback_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `dp_file_path` varchar(100) NOT NULL,
  `designation` enum('teacher','admin') NOT NULL DEFAULT 'teacher',
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `password`, `mobile`, `email`, `dp_file_path`, `designation`, `created_at`, `created_by`, `updated_at`, `updated_by`, `status`) VALUES
(1, 'karan', '$2y$10$tm35IpuHcA/ImMz8NlUaF.gaBNJG/Ovngp0iIozJ3vMblWmktrAsm', '9131478476', 'karansahu.nsti@gmail.com', 'dp_uploads/profile_68415c6792a037.42560735.jpg', 'admin', '2025-05-29 11:18:57', 0, '2025-06-19 16:48:10', 0, 1),
(7, 'admin', '$2y$10$CPvcUClpnM/li.LCX4PQK.GB1znHwDhZr32zWkAu4aP7l1iqBXqwS', '1234567890', 'admin@gmail.com', 'dp_uploads/profile_68415ec437ed41.50398267.jpg', 'teacher', '2025-06-02 13:56:31', NULL, '2025-06-05 14:39:24', NULL, 1),
(10, 'a', '$2y$10$9bYQyOiWyWea/GbJ5TwlCea.cH7IQrrcNvBfowPSQNBcNTnYoL.ce', '123456', 'a@gmail.com', 'dp_uploads/profile_68415e6d3ba7e9.18296620.jpg', 'teacher', '2025-06-05 14:37:57', NULL, '2025-06-05 14:37:57', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `trade_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `program` enum('CTS','CITS') NOT NULL,
  `attendance_id` varchar(50) NOT NULL,
  `rating` int(1) NOT NULL CHECK (`rating` between 1 and 5),
  `remarks` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `attendance_id` int(8) NOT NULL,
  `name` varchar(100) NOT NULL,
  `trade` text NOT NULL,
  `program` enum('CTS','CITS') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `attendance_id`, `name`, `trade`, `program`) VALUES
(1, 52173882, 'Karan Sahu', 'Computer Software Applications', 'CITS'),
(3, 7316484, 'Deepika Pal', 'Artificial Intelligence and Programming Assistant', 'CTS'),
(4, 57316128, 'Gourab Das', 'Artificial Intelligence and Programming Assistant', 'CTS'),
(5, 21342110, 'Kamalendar Kumar Singh', 'Artificial Intelligence and Programming Assistant', 'CTS'),
(6, 44263929, 'Bristi Mandi', 'Artificial Intelligence and Programming Assistant', 'CTS'),
(7, 63513687, 'Suchorita Mitra', 'Artificial Intelligence and Programming Assistant', 'CTS'),
(8, 1371178, 'Baishaki Paul', 'Artificial Intelligence and Programming Assistant', 'CTS'),
(9, 49422886, 'Shreya Deyashi', 'Artificial Intelligence and Programming Assistant', 'CTS'),
(10, 74451769, 'Milli Sharma', 'Artificial Intelligence and Programming Assistant', 'CTS'),
(11, 29469956, 'Shashank Dwivedi', 'Artificial Intelligence and Programming Assistant', 'CTS'),
(12, 41059678, 'Rijubrata Das', 'Artificial Intelligence and Programming Assistant', 'CTS'),
(13, 21972602, 'Bhavya Kumari', 'Artificial Intelligence and Programming Assistant', 'CTS'),
(14, 35666143, 'Gautam Kumar Pandit', 'Artificial Intelligence and Programming Assistant', 'CTS'),
(15, 73104103, 'Suresh Nayek', 'Artificial Intelligence and Programming Assistant', 'CTS'),
(16, 24499116, 'Anjali Mishra', 'Artificial Intelligence and Programming Assistant', 'CTS'),
(17, 14737045, 'Swapnadip Ghosh', 'Artificial Intelligence and Programming Assistant', 'CTS'),
(18, 521542, 'Dulal Chandra Nayek', 'Artificial Intelligence and Programming Assistant', 'CTS'),
(19, 19284259, 'Md Amir Ansari', 'Artificial Intelligence and Programming Assistant', 'CTS'),
(20, 8013406, 'Bapi Sarkar', 'Artificial Intelligence and Programming Assistant', 'CTS'),
(21, 88994968, 'Nabilah Asif', 'Artificial Intelligence and Programming Assistant', 'CTS'),
(22, 23008850, 'Arijit Rakshit', 'Artificial Intelligence and Programming Assistant', 'CTS'),
(23, 68891736, 'Abir Das', 'Artificial Intelligence and Programming Assistant', 'CTS'),
(24, 17215131, 'Mosmi Kumari', 'Artificial Intelligence and Programming Assistant', 'CTS'),
(25, 65657874, 'Protyusa Chatterjee', 'Artificial Intelligence and Programming Assistant', 'CTS'),
(26, 63190280, 'Punam Khalkho', 'Artificial Intelligence and Programming Assistant', 'CTS'),
(27, 91114768, 'Saurav Sharma', 'Carpenter', 'CITS'),
(28, 21515402, 'Gaurav Dipak Kevat', 'Carpenter', 'CITS'),
(29, 38766310, 'Rahul Raj Bind', 'Carpenter', 'CITS'),
(30, 24024487, 'Gajender', 'Carpenter', 'CITS'),
(31, 4557419, 'Kamlapati Vishvakarma', 'Carpenter', 'CITS'),
(32, 58033118, 'Atul Kumar Yadav', 'Carpenter', 'CITS'),
(33, 31467605, 'Lalit Kumar', 'Carpenter', 'CITS'),
(34, 92096062, 'Charan Das', 'Carpenter', 'CITS'),
(35, 31517162, 'Krishnakant Yadav', 'Carpenter', 'CITS'),
(36, 2910013, 'Akshay', 'Carpenter', 'CITS'),
(37, 26452162, 'Vaibhav Kumar Rao', 'Carpenter', 'CITS'),
(38, 49265004, 'Ajay', 'Carpenter', 'CITS'),
(39, 5338447, 'Pushpendra Prajapati', 'Carpenter', 'CITS'),
(40, 32128627, 'Mohit Singh', 'Carpenter', 'CITS'),
(41, 44805388, 'Rahul', 'Carpenter', 'CITS'),
(42, 36578545, 'Vikas Thakur', 'Carpenter', 'CITS'),
(43, 32945594, 'Ranjeet Kumar', 'Computer Software Applications', 'CITS'),
(44, 18168600, 'Siwangi Gupta', 'Computer Software Applications', 'CITS'),
(45, 76404016, 'Pushpendra Tiwari', 'Computer Software Applications', 'CITS'),
(46, 75382045, 'Shivlal Prasad Azad', 'Computer Software Applications', 'CITS'),
(47, 7847318, 'Deepak Kumar', 'Computer Software Applications', 'CITS'),
(48, 36758779, 'Manjima Bhattacharyya', 'Computer Software Applications', 'CITS'),
(49, 65359097, 'Soma Pradhan', 'Computer Software Applications', 'CITS'),
(50, 59939922, 'Gagan Kumar Sahu', 'Computer Software Applications', 'CITS'),
(51, 8019477, 'Rashi', 'Computer Software Applications', 'CITS'),
(52, 72334853, 'Anil Kumar Paswan', 'Computer Software Applications', 'CITS'),
(53, 36647607, 'Rohit Kumar', 'Computer Software Applications', 'CITS'),
(54, 11213112, 'Pinaki Das', 'Computer Software Applications', 'CITS'),
(55, 76473334, 'Rupendra Kumar Shreewas', 'Computer Software Applications', 'CITS'),
(56, 89652249, 'Manoranjan Kar', 'Computer Software Applications', 'CITS'),
(57, 1001932, 'Arvind', 'Computer Software Applications', 'CITS'),
(58, 4706594, 'Vishal Dahariya', 'Computer Software Applications', 'CITS'),
(59, 73720611, 'Aditya Kumar Sharma', 'Computer Software Applications', 'CITS'),
(60, 61376840, 'Frima Mahipal', 'Computer Software Applications', 'CITS'),
(61, 49677765, 'Shilpi Maurya', 'Computer Software Applications', 'CITS'),
(62, 44706876, 'Shubham Kumar Ojha', 'Computer Software Applications', 'CITS'),
(63, 65815180, 'Sushama Thakur', 'Computer Software Applications', 'CITS'),
(64, 81505285, 'Ribhu Ghosh', 'Computer Software Applications', 'CITS'),
(65, 95484688, 'Reema Sahu', 'Computer Software Applications', 'CITS'),
(66, 7828362, 'Muskan', 'Computer Software Applications', 'CITS'),
(67, 94727072, 'Gautam Kumar', 'Draughtsman (Civil)', 'CITS'),
(68, 48844188, 'Sandeep Raj', 'Draughtsman (Civil)', 'CITS'),
(69, 83987943, 'Sandeep Kumar', 'Draughtsman (Civil)', 'CITS'),
(70, 76040833, 'Ravi Shankar Kumar', 'Draughtsman (Civil)', 'CITS'),
(71, 52455577, 'Papai Ghosh', 'Draughtsman (Civil)', 'CITS'),
(72, 24722855, 'Jitendra Oraon', 'Draughtsman (Civil)', 'CITS'),
(73, 25899984, 'Uma Shankar', 'Draughtsman (Civil)', 'CITS'),
(74, 51909586, 'Abhinav Kumar', 'Draughtsman (Civil)', 'CITS'),
(75, 30405263, 'Sanjeev Kumar Suman', 'Draughtsman (Civil)', 'CITS'),
(76, 78175898, 'Bhawani Prakash Oraon', 'Draughtsman (Civil)', 'CITS'),
(77, 84908842, 'Alkesh Prajapati', 'Draughtsman (Civil)', 'CITS'),
(78, 26784792, 'Kundan Kumar', 'Draughtsman (Civil)', 'CITS'),
(79, 27052547, 'Omkar Patel', 'Draughtsman (Civil)', 'CITS'),
(80, 27118326, 'Praveen Kumar', 'Draughtsman (Civil)', 'CITS'),
(81, 98629420, 'Sumit Kumar Yadav', 'Draughtsman (Civil)', 'CITS'),
(82, 39551243, 'Sunil Kumar Yadav', 'Draughtsman (Civil)', 'CITS'),
(83, 4281473, 'Vishnu Avtar Sharma', 'Draughtsman (Civil)', 'CITS'),
(84, 35778215, 'Virendra Kumar', 'Draughtsman (Civil)', 'CITS'),
(85, 238310, 'Anjali Kumari', 'Draughtsman (Civil)', 'CITS'),
(86, 37928143, 'Tarikul Islam', 'Draughtsman (Civil)', 'CITS'),
(87, 57293982, 'Vaishali', 'Draughtsman (Civil)', 'CITS'),
(88, 40160886, 'Mahendra Singh', 'Draughtsman (Civil)', 'CITS'),
(89, 70764082, 'Anuj Kumar', 'Draughtsman (Civil)', 'CITS'),
(90, 48702735, 'Rahul', 'Draughtsman (Civil)', 'CITS'),
(91, 61593066, 'Rahul Kumar', 'Draughtsman (Civil)', 'CITS'),
(92, 83144748, 'Shivanee', 'Draughtsman (Civil)', 'CITS'),
(93, 98598864, 'Vikash Kumar', 'Draughtsman (Civil)', 'CITS'),
(94, 62366957, 'Kanchan Patel', 'Draughtsman (Civil)', 'CITS'),
(95, 4885730, 'Savita', 'Draughtsman (Civil)', 'CITS'),
(96, 18019244, 'Jamshed Ali', 'Draughtsman (Civil)', 'CITS'),
(97, 66343367, 'Suneel Sahni', 'Draughtsman (Civil)', 'CITS'),
(98, 39536613, 'Balram Kumar', 'Draughtsman (Civil)', 'CITS'),
(99, 82779154, 'Nitesh Kumar Yadav', 'Draughtsman (Civil)', 'CITS'),
(100, 1742409, 'Nitish Kumar', 'Draughtsman (Civil)', 'CITS'),
(101, 99055402, 'Archana Kushwaha', 'Draughtsman (Civil)', 'CITS'),
(102, 94710215, 'Kusum', 'Draughtsman (Civil)', 'CITS'),
(103, 30886913, 'Surbhi Chourey', 'Draughtsman (Civil)', 'CITS'),
(104, 62512608, 'Sandhya Yadav', 'Draughtsman (Civil)', 'CITS'),
(105, 86293350, 'Chandan Kumar', 'Draughtsman (Civil)', 'CITS'),
(106, 74293642, 'Manish Kumar', 'Draughtsman (Civil)', 'CITS'),
(107, 60268649, 'Pappu Kumar Singh', 'Draughtsman (Civil)', 'CITS'),
(108, 41478625, 'Rahul Pandey', 'Draughtsman (Civil)', 'CITS'),
(109, 23439852, 'Chiranjiv Kumar Mishra', 'Draughtsman (Civil)', 'CITS'),
(110, 65389300, 'Sunil Kumar Singh', 'Draughtsman (Civil)', 'CITS'),
(111, 79837536, 'Satyam Vishwakarma', 'Draughtsman (Civil)', 'CITS'),
(112, 1960065, 'Mohd Arsalan Akhtar', 'Draughtsman (Civil)', 'CITS'),
(113, 78431283, 'Kumari Sanjana Balmikee', 'Draughtsman (Civil)', 'CITS'),
(114, 16748181, 'Ankita', 'Draughtsman (Civil)', 'CITS'),
(115, 60064094, 'Sachin Kumar', 'Draughtsman (Mech)', 'CITS'),
(116, 94093720, 'Shalini Gupta', 'Draughtsman (Mech)', 'CITS'),
(117, 72086729, 'Souvik Dey', 'Draughtsman (Mech)', 'CITS'),
(118, 89392452, 'Vikas Kumar', 'Draughtsman (Mech)', 'CITS'),
(119, 19440054, 'Aditya Kumar Kasyap', 'Draughtsman (Mech)', 'CITS'),
(120, 80098837, 'Arnav', 'Draughtsman (Mech)', 'CITS'),
(121, 31756449, 'Sonu Kumar', 'Draughtsman (Mech)', 'CITS'),
(122, 17956566, 'Hem Prasad Parte', 'Draughtsman (Mech)', 'CITS'),
(123, 99540974, 'Aravind Kumar Bharati', 'Draughtsman (Mech)', 'CITS'),
(124, 5536079, 'Atul Yadav', 'Draughtsman (Mech)', 'CITS'),
(125, 88300757, 'Rupkishor Sharma', 'Draughtsman (Mech)', 'CITS'),
(126, 52642855, 'Krishna Verma', 'Draughtsman (Mech)', 'CITS'),
(127, 82819135, 'Kumari Durga Rai', 'Draughtsman (Mech)', 'CITS'),
(128, 13085755, 'Soni Prasad', 'Draughtsman (Mech)', 'CITS'),
(129, 67873705, 'Arvind Kumar', 'Draughtsman (Mech)', 'CITS'),
(130, 29652280, 'Mukesh Kumar', 'Draughtsman (Mech)', 'CITS'),
(131, 66972889, 'Ajay Kumar', 'Draughtsman (Mech)', 'CITS'),
(132, 51965544, 'Ajay Augustine Kujur', 'Electrician', 'CITS'),
(133, 4028806, 'Bipin Kumar', 'Electrician', 'CITS'),
(134, 88343096, 'Manish Kumar', 'Electrician', 'CITS'),
(135, 26809963, 'Bablu Kumar', 'Electrician', 'CITS'),
(136, 61226769, 'Khudiram Hansda', 'Electrician', 'CITS'),
(137, 54368834, 'Palash Kumar Sarkar', 'Electrician', 'CITS'),
(138, 49955462, 'Moumita Pandey', 'Electrician', 'CITS'),
(139, 65061766, 'Tulsi Kumar Mahto', 'Electrician', 'CITS'),
(140, 68041444, 'Nikki Raj', 'Electrician', 'CITS'),
(141, 87006098, 'Ramjee', 'Electrician', 'CITS'),
(142, 6846754, 'Amit Kumar Saw', 'Electrician', 'CITS'),
(143, 80448259, 'Shreya Sudha', 'Electrician', 'CITS'),
(144, 62398430, 'Aakanksha Kiran', 'Electrician', 'CITS'),
(145, 90353014, 'Baby Neha Praween', 'Electrician', 'CITS'),
(146, 28688978, 'Shibram Tudu', 'Electrician', 'CITS'),
(147, 33442863, 'Durgapada Hansda', 'Electrician', 'CITS'),
(148, 47822245, 'Manish Kumar', 'Electrician', 'CITS'),
(149, 4175635, 'Amit Ranjan', 'Electrician', 'CITS'),
(150, 60571882, 'Monika Kumari', 'Electrician', 'CITS'),
(151, 96882166, 'Sukhanandan Kumar Das', 'Electrician', 'CITS'),
(152, 79995748, 'Alok Ranjan', 'Electrician', 'CITS'),
(153, 13929176, 'Priyanshu Kumar Sharma', 'Electrician', 'CITS'),
(154, 15679158, 'Abhishek Kumar', 'Electrician', 'CITS'),
(155, 83513074, 'Saurabh Kumar Shukla', 'Electrician', 'CITS'),
(156, 4500503, 'Rakesh Kumar', 'Electrician', 'CITS'),
(157, 92487679, 'Sarifa Khatun', 'Electrician', 'CITS'),
(158, 64221739, 'Vivek Kumar Tiwari', 'Electrician', 'CITS'),
(159, 40700863, 'Ashwani', 'Electrician', 'CITS'),
(160, 37964459, 'Raushan Kumar', 'Electrician', 'CITS'),
(161, 49241299, 'Rohit Xalxo', 'Electrician', 'CITS'),
(162, 52214405, 'Santosh Kumar Rai', 'Electrician', 'CITS'),
(163, 93484961, 'Amisha Kiran', 'Electrician', 'CITS'),
(164, 50916189, 'Satya Prakash', 'Electrician', 'CITS'),
(165, 36825890, 'Shyam Krishna', 'Electrician', 'CITS'),
(166, 6366700, 'Sonar Manik Mondal', 'Electrician', 'CITS'),
(167, 65967720, 'Rajesh Prajapati', 'Electrician', 'CITS'),
(168, 752892, 'Shashi Bhushan', 'Electrician', 'CITS'),
(169, 67541533, 'Sunil Kumar', 'Electrician', 'CITS'),
(170, 14586222, 'Kapindra Kumar', 'Electrician', 'CITS'),
(171, 12171961, 'Ravi Raj Kumar', 'Electrician', 'CITS'),
(172, 93596545, 'Shailesh Dubey', 'Electrician', 'CITS'),
(173, 33797646, 'Sweta Oraon', 'Electrician', 'CITS'),
(174, 88353158, 'Bikesh Oraon', 'Electrician', 'CITS'),
(175, 81752649, 'Soni Gautam', 'Electrician', 'CITS'),
(176, 86237751, 'Vandna Kumari', 'Electrician', 'CITS'),
(177, 66768649, 'Manisha Mahato', 'Electrician', 'CITS'),
(178, 18686904, 'Sweta Kumari', 'Electrician', 'CITS'),
(179, 9714116, 'Suraj Kumar', 'Electrician', 'CITS'),
(180, 46502604, 'Poonam Kumari', 'Electrician', 'CITS'),
(181, 2359799, 'Pradip Biswas', 'Fitter', 'CITS'),
(182, 9861693, 'Bajrangee Kumar Yadav', 'Fitter', 'CITS'),
(183, 97705737, 'Md Abdul Jabbar', 'Fitter', 'CITS'),
(184, 76767374, 'Sachin Kumar', 'Fitter', 'CITS'),
(185, 70788130, 'Sanjukta Biswas', 'Fitter', 'CITS'),
(186, 53118805, 'Suparna Pal', 'Fitter', 'CITS'),
(187, 61247911, 'Aditya Gupta', 'Fitter', 'CITS'),
(188, 52307418, 'Raj Kumar', 'Fitter', 'CITS'),
(189, 46888896, 'Sonu Kumar', 'Fitter', 'CITS'),
(190, 31315753, 'Kundan Kumar', 'Fitter', 'CITS'),
(191, 63912465, 'Saurabh Kumar', 'Fitter', 'CITS'),
(192, 27427379, 'Pramod Kumar Paswan', 'Fitter', 'CITS'),
(193, 4545163, 'Santosh Shaw', 'Fitter', 'CITS'),
(194, 97804153, 'Pritam Manna', 'Fitter', 'CITS'),
(195, 51651068, 'Prem Chandra', 'Fitter', 'CITS'),
(196, 58659629, 'Aditya Raj', 'Fitter', 'CITS'),
(197, 92367239, 'Munna Murmu', 'Fitter', 'CITS'),
(198, 49386972, 'Lokesh Kumar Chaubey', 'Fitter', 'CITS'),
(199, 48951906, 'Murari Kumar Yadav', 'Fitter', 'CITS'),
(200, 65426224, 'Hritick Kumar Rabidas', 'Fitter', 'CITS'),
(201, 55986783, 'Rohan Kumar', 'Fitter', 'CITS'),
(202, 42886092, 'Chhotu Kumar', 'Fitter', 'CITS'),
(203, 56859212, 'Raviranjan Choudhary', 'Fitter', 'CITS'),
(204, 45343500, 'Prince Kumar', 'Fitter', 'CITS'),
(205, 30159756, 'Randhir Kumar', 'Fitter', 'CITS'),
(206, 36876846, 'Shiv Pujan Bind', 'Fitter', 'CITS'),
(207, 32543754, 'Sumit Kumar', 'Fitter', 'CITS'),
(208, 91844361, 'Anjali Choudhary', 'Fitter', 'CITS'),
(209, 30310150, 'Anshu Pandey', 'Fitter', 'CITS'),
(210, 7916499, 'Deepak Kumar', 'Fitter', 'CITS'),
(211, 68578292, 'Chandan Kumar', 'Fitter', 'CITS'),
(212, 20330861, 'Manish Kumar', 'Fitter', 'CITS'),
(213, 22148791, 'Vikash Kumar Pal', 'Fitter', 'CITS'),
(214, 70188802, 'Bipin Kumar', 'Fitter', 'CITS'),
(215, 15224370, 'Kumari Barkha Rani', 'Fitter', 'CITS'),
(216, 710374, 'Prakhar Raj', 'Fitter', 'CITS'),
(217, 80736915, 'Prity Kumari', 'Fitter', 'CITS'),
(218, 44632193, 'Yuvraj Singh', 'Fitter', 'CITS'),
(219, 86660137, 'Kanchan Kumari', 'Fitter', 'CITS'),
(220, 87156932, 'Sandeep Sharma', 'Fitter', 'CITS'),
(221, 97533508, 'Aradhana Mahato', 'Fitter', 'CITS'),
(222, 90749115, 'Chandan Kumar Ghadai', 'Fitter', 'CITS'),
(223, 34487294, 'Gautam Kapri', 'Fitter', 'CITS'),
(224, 89091574, 'Dhananjoy Hansda', 'Fitter', 'CITS'),
(225, 11470701, 'Rahul Kumar Paswan', 'Fitter', 'CITS'),
(226, 48670049, 'Sangram Keshari Nayak', 'Fitter', 'CITS'),
(227, 9302726, 'Bikki Prasad', 'Fitter', 'CITS'),
(228, 29880581, 'Dazy Kumari', 'Fitter', 'CITS'),
(229, 34703438, 'Niraj Kumar', 'Fitter', 'CITS'),
(230, 81206833, 'Soni Kumari', 'Fitter', 'CITS'),
(231, 12081813, 'Ajay Kumar', 'Fitter', 'CITS'),
(232, 56786554, 'Krishnendu Shaw', 'Fitter', 'CITS'),
(233, 89571188, 'Rupesh Kumar', 'Fitter', 'CITS'),
(234, 39006497, 'Sayanti Tudu', 'Fitter', 'CITS'),
(235, 54582359, 'Somnath Paramanik', 'Fitter', 'CITS'),
(236, 80000354, 'Krishna Sah', 'Fitter', 'CITS'),
(237, 15629585, 'Surya Prakash Verma', 'Fitter', 'CITS'),
(238, 20614266, 'Araman', 'Fitter', 'CITS'),
(239, 91747472, 'Ankush Kumar', 'Fitter', 'CITS'),
(240, 56412095, 'Devendra Kumar', 'Fitter', 'CITS'),
(241, 57822519, 'Payal Yadav', 'Fitter', 'CITS'),
(242, 27140218, 'Shubham Kumar', 'Fitter', 'CITS'),
(243, 47085662, 'Vipin Kumar', 'Fitter', 'CITS'),
(244, 35066001, 'Mahanand Kumar Mandal', 'Fitter', 'CITS'),
(245, 16377901, 'Manish Kumar', 'Fitter', 'CITS'),
(246, 10690942, 'Pawan Kr Gupta', 'Fitter', 'CITS'),
(247, 21047704, 'Amit Kumar', 'Fitter', 'CITS'),
(248, 80061743, 'Jugnu Jigyasu', 'Fitter', 'CITS'),
(249, 81178031, 'Madhab Karmakar', 'Fitter', 'CITS'),
(250, 87436579, 'Md Akbar Ali', 'Fitter', 'CITS'),
(251, 18043483, 'Rajesh Hembrom', 'Fitter', 'CITS'),
(252, 92979221, 'Santu Barman', 'Fitter', 'CITS'),
(253, 97521361, 'Sneha Rang', 'Fitter', 'CITS'),
(254, 5837999, 'Abhishek Kumar', 'Fitter', 'CITS'),
(255, 85679284, 'Bablu Kumar Mahto', 'Fitter', 'CITS'),
(256, 53770962, 'Debasis Biswal', 'Fitter', 'CITS'),
(257, 74842634, 'Shishupal Singh', 'Fitter', 'CITS'),
(258, 20839109, 'Anarjeet Kumar', 'Fitter', 'CITS'),
(259, 54508823, 'Manish Kumar Gupta', 'Fitter', 'CITS'),
(260, 23163581, 'Nageshwar Bediya', 'Fitter', 'CITS'),
(261, 62941754, 'Nandkishor Kumar', 'Fitter', 'CITS'),
(262, 53330708, 'Tiyasa Ghosh', 'Fitter', 'CITS'),
(263, 92074082, 'Veena Gupta', 'Fitter', 'CITS'),
(264, 64595370, 'Vivek Kumar', 'Fitter', 'CITS'),
(265, 44430722, 'Bhim Kumar', 'Fitter', 'CITS'),
(266, 50853311, 'Pintu Kumar Yadav', 'Fitter', 'CITS'),
(267, 96483935, 'Yogendra Kumar', 'Fitter', 'CITS'),
(268, 8229395, 'Saroj Choudhary', 'Fitter', 'CITS'),
(269, 61264557, 'Dochand Bharti', 'Fitter', 'CITS'),
(270, 79860874, 'Pintu Bhakat', 'Fitter', 'CITS'),
(271, 61464507, 'Vishal Kumar Singh', 'Fitter', 'CITS'),
(272, 50901737, 'Arjun Singh', 'Fitter', 'CITS'),
(273, 10811785, 'Goutam Giri', 'Fitter', 'CITS'),
(274, 17536482, 'Subham Maity', 'Fitter', 'CITS'),
(275, 21249966, 'Raj Hans', 'Fitter', 'CITS'),
(276, 48109746, 'Amit Kumar', 'Foundryman', 'CITS'),
(277, 1035813, 'Harsh Dev Dwivedi', 'Foundryman', 'CITS'),
(278, 29318570, 'Km Chhaya  Verma', 'Foundryman', 'CITS'),
(279, 75509039, 'Khagesh Kumar', 'Foundryman', 'CITS'),
(280, 13498466, 'Sagnik Manna', 'Foundryman', 'CTS'),
(281, 66927474, 'Sk Mannan Ali', 'Foundryman', 'CTS'),
(282, 88908587, 'Deep Das', 'Foundryman', 'CTS'),
(283, 21994860, 'Ankan Panja', 'Foundryman', 'CTS'),
(284, 50866182, 'Vishal Singh', 'Foundryman', 'CTS'),
(285, 66691088, 'Suman Shil', 'Foundryman', 'CTS'),
(286, 30107360, 'Chanchal Chakraborty', 'Foundryman', 'CTS'),
(287, 71620205, 'Pradip Kumar', 'Foundryman', 'CTS'),
(288, 49978512, 'Puja Singh', 'Foundryman', 'CTS'),
(289, 84380489, 'Khushboo Kumari', 'Instrument Mechanic', 'CITS'),
(290, 91751586, 'Km Aradhana', 'Instrument Mechanic', 'CITS'),
(291, 69665977, 'Rachana Pal', 'Instrument Mechanic', 'CITS'),
(292, 35118213, 'Tukaram', 'Instrument Mechanic', 'CITS'),
(293, 27273875, 'Rajonna Santra', 'IoT Technician (Smart Agriculture)', 'CTS'),
(294, 78555025, 'Arnab Nandi', 'IoT Technician (Smart Agriculture)', 'CTS'),
(295, 86021850, 'Baidya Nath Hembram', 'IoT Technician (Smart Agriculture)', 'CTS'),
(296, 77667643, 'Mahuya Banerjee', 'IoT Technician (Smart Agriculture)', 'CTS'),
(297, 43299306, 'Apurba Mondal', 'IoT Technician (Smart Agriculture)', 'CTS'),
(298, 85419853, 'Souvik Karmakar', 'IoT Technician (Smart Agriculture)', 'CTS'),
(299, 50982624, 'Nityananda Khelo', 'IoT Technician (Smart Agriculture)', 'CTS'),
(300, 74291111, 'Rimi Bodak', 'IoT Technician (Smart Agriculture)', 'CTS'),
(301, 93631372, 'Susmita Bar', 'IoT Technician (Smart Agriculture)', 'CTS'),
(302, 99287318, 'Dhruve Gupta', 'IoT Technician (Smart Agriculture)', 'CTS'),
(303, 84458001, 'Rohit Kumar', 'IoT Technician (Smart Agriculture)', 'CTS'),
(304, 82473702, 'Piyasa  Bag', 'IoT Technician (Smart Agriculture)', 'CTS'),
(305, 6887681, 'Tiyasa Bag', 'IoT Technician (Smart Agriculture)', 'CTS'),
(306, 10227095, 'Samrat Mondal', 'IoT Technician (Smart Agriculture)', 'CTS'),
(307, 17630921, 'Saprativo Khelo', 'IoT Technician (Smart Agriculture)', 'CTS'),
(308, 46374477, 'Soumyadip Roy', 'IoT Technician (Smart Agriculture)', 'CTS'),
(309, 84983143, 'Ankit Kumar', 'Machinist', 'CITS'),
(310, 87300704, 'Girja Shankar Singh Pariya', 'Machinist', 'CITS'),
(311, 61490106, 'Ram Majhi', 'Machinist', 'CITS'),
(312, 68504487, 'Prince Kumar', 'Machinist', 'CITS'),
(313, 2108849, 'Abhishek Anand', 'Machinist', 'CITS'),
(314, 14855271, 'Sneha Choudhary', 'Machinist', 'CITS'),
(315, 99513008, 'Avinash Kumar', 'Machinist', 'CITS'),
(316, 56255387, 'Prashant Kumar Rajwar', 'Machinist', 'CITS'),
(317, 53736130, 'Shreyesk Gupta', 'Machinist', 'CITS'),
(318, 93635459, 'Sudhanshu Kumar', 'Machinist', 'CITS'),
(319, 59598944, 'Nelson Beck', 'Machinist', 'CITS'),
(320, 92194977, 'Raj Nandani Kumari', 'Machinist', 'CITS'),
(321, 45446597, 'Ritik Agnihotry', 'Machinist', 'CITS'),
(322, 71578346, 'Aditya Kumar', 'Machinist', 'CTS'),
(323, 33248257, 'Anupam James Khalkho', 'Machinist', 'CTS'),
(324, 74591848, 'Manish Kumar', 'Machinist', 'CTS'),
(325, 18942231, 'Sumit Kumar', 'Machinist', 'CTS'),
(326, 69454930, 'Sohom Kanrar', 'Machinist', 'CTS'),
(327, 1076892, 'Ajoy Kumar Sharma', 'Machinist', 'CTS'),
(328, 61764921, 'Sayan Bera', 'Machinist', 'CTS'),
(329, 33629028, 'Sudhakar Kumar', 'Machinist', 'CTS'),
(330, 67359583, 'Rahul Kumar', 'Machinist', 'CTS'),
(331, 10012263, 'Prem Kerketta', 'Machinist', 'CTS'),
(332, 50229561, 'Priyotosh Bera', 'Machinist', 'CTS'),
(333, 24682998, 'Sudip Santra', 'Machinist', 'CTS'),
(334, 73254928, 'Suman Das', 'Machinist', 'CTS'),
(335, 11883509, 'Asia Khatun', 'Machinist', 'CTS'),
(336, 2477665, 'Priyanka Das', 'Machinist', 'CTS'),
(337, 82901664, 'Indranil Bhattacharjee', 'Machinist Grinder', 'CTS'),
(338, 82544486, 'Sujoy Roy', 'Machinist Grinder', 'CTS'),
(339, 97412146, 'Biplab Dolui', 'Machinist Grinder', 'CTS'),
(340, 84143973, 'Hritesh Gupta', 'Machinist Grinder', 'CTS'),
(341, 3310537, 'Bablu Kumar Rauth', 'Machinist Grinder', 'CTS'),
(342, 37552615, 'Bolbam Chowdhury', 'Machinist Grinder', 'CTS'),
(343, 95056920, 'Ritik Raaz', 'Machinist Grinder', 'CTS'),
(344, 60323908, 'Raj Mallick', 'Machinist Grinder', 'CTS'),
(345, 2846177, 'Chayan Dey', 'Machinist Grinder', 'CTS'),
(346, 30439586, 'Arun Prasad', 'Machinist Grinder', 'CTS'),
(347, 66241677, 'Ayan Das', 'Machinist Grinder', 'CTS'),
(348, 86108777, 'Shivam Kumar', 'Machinist Grinder', 'CTS'),
(349, 31350375, 'Pintus Kumar Shaw', 'Machinist Grinder', 'CTS'),
(350, 50529956, 'Diptesh Khan', 'Machinist Grinder', 'CTS'),
(351, 10454406, 'Pujan Ghosh', 'Machinist Grinder', 'CTS'),
(352, 75993108, 'Aditya Majhi', 'Machinist Grinder', 'CTS'),
(353, 39167043, 'Sumanta Barui', 'Machinist Grinder', 'CTS'),
(354, 32660308, 'Biswanath Prasad', 'Machinist Grinder', 'CTS'),
(355, 65744234, 'Amarnath Gupta', 'Machinist Grinder', 'CITS'),
(356, 86922804, 'Debjoti Maji', 'Machinist Grinder', 'CITS'),
(357, 59097050, 'Rajkumar Singh Choudhary', 'Machinist Grinder', 'CITS'),
(358, 38930257, 'Ranjeet Singh Kushwaha', 'Mechanic Machine Tool Maintenance (MMTM)', 'CITS'),
(359, 12292552, 'Hriday Chandra Ghosh', 'Mechanic Motor Vehicle', 'CITS'),
(360, 8920755, 'Nisha Kumari', 'Mechanic Motor Vehicle', 'CITS'),
(361, 91901456, 'Navneet Tiwari', 'Mechanic Motor Vehicle', 'CITS'),
(362, 12498204, 'Shubham Chowbey', 'Mechanic Motor Vehicle', 'CITS'),
(363, 81280233, 'Camelia Naskar', 'Mechanic Motor Vehicle', 'CITS'),
(364, 23840664, 'Subhankar Dey', 'Mechanic Motor Vehicle', 'CITS'),
(365, 76887969, 'Kundan Kumar', 'Mechanic Motor Vehicle', 'CITS'),
(366, 24355943, 'Anup Kumar', 'Mechanic Motor Vehicle', 'CITS'),
(367, 784761, 'Anish Kumar', 'Mechanic Motor Vehicle', 'CITS'),
(368, 73662971, 'Joel Hembrom', 'Mechanic Motor Vehicle', 'CITS'),
(369, 10843946, 'Shyam Bihari', 'Mechanic Motor Vehicle', 'CITS'),
(370, 76296965, 'Sonu Kumar', 'Mechanic Motor Vehicle', 'CITS'),
(371, 22416700, 'Ravi Kumar', 'Mechanic Motor Vehicle', 'CITS'),
(372, 55381070, 'Rakesh Mandal', 'Mechanic Motor Vehicle', 'CITS'),
(373, 18339477, 'Pintu Kumar', 'Mechanic Motor Vehicle', 'CITS'),
(374, 7508127, 'Aman Kumar', 'Mechanic Motor Vehicle', 'CITS'),
(375, 21027457, 'Amit Kumar Thakur', 'Mechanic Motor Vehicle', 'CITS'),
(376, 57893499, 'Praphulla Topno', 'Mechanic Motor Vehicle', 'CITS'),
(377, 25018105, 'Sunil Kumar Bharteya', 'Mechanic Motor Vehicle', 'CITS'),
(378, 14075271, 'Amit Sawaiyan', 'Mechanic Motor Vehicle', 'CITS'),
(379, 97112804, 'Amit Kudada', 'Mechanic Motor Vehicle', 'CITS'),
(380, 55505327, 'Babua Kumar', 'Mechanic Refrigeration & Air-Conditioning', 'CITS'),
(381, 63318212, 'Sasanka Halder', 'Mechanic Refrigeration & Air-Conditioning', 'CITS'),
(382, 2505509, 'Mukesh Kumar Pandit', 'Mechanic Refrigeration & Air-Conditioning', 'CITS'),
(383, 46987189, 'Gour Ranjan Mandal', 'Mechanic Refrigeration & Air-Conditioning', 'CITS'),
(384, 76957225, 'Pankaj Kumar Pal', 'Mechanic Refrigeration & Air-Conditioning', 'CITS'),
(385, 73294438, 'Vikas Pal', 'Mechanic Refrigeration & Air-Conditioning', 'CITS'),
(386, 49899198, 'Jugunu Roshanlal Gupta', 'Mechanic Refrigeration & Air-Conditioning', 'CITS'),
(387, 82223716, 'Vikas Yadav', 'Mechanic Refrigeration & Air-Conditioning', 'CITS'),
(388, 81006909, 'Sumit Kumar', 'Mechanic Refrigeration & Air-Conditioning', 'CITS'),
(389, 96960737, 'Dompaka Vara Prasad Rao', 'Mechanic Refrigeration & Air-Conditioning', 'CITS'),
(390, 30142637, 'Jinty Nath', 'Mechanic Refrigeration & Air-Conditioning', 'CITS'),
(391, 13480580, 'Rohit Kumar', 'Mechanic Refrigeration & Air-Conditioning', 'CITS'),
(392, 49700360, 'Bittu Kumar', 'Mechanic Refrigeration & Air-Conditioning', 'CITS'),
(393, 39904181, 'Roshani Devi', 'Mechanic Refrigeration & Air-Conditioning', 'CITS'),
(394, 38698863, 'Ranjan Kumar', 'Mechanic Refrigeration & Air-Conditioning', 'CITS'),
(395, 73161415, 'Neetu Devi', 'Mechanic Refrigeration & Air-Conditioning', 'CITS'),
(396, 59075546, 'Shivansh Tiwari', 'Mechanic Refrigeration & Air-Conditioning', 'CITS'),
(397, 16077062, 'Km Sarita', 'Mechanic Refrigeration & Air-Conditioning', 'CITS'),
(398, 79857838, 'Kashish Soni', 'Mechanic Refrigeration & Air-Conditioning', 'CITS'),
(399, 14076481, 'Bhupesh Yadav', 'Mechanic Refrigeration & Air-Conditioning', 'CITS'),
(400, 61412231, 'Vishal', 'Mechanic Refrigeration & Air-Conditioning', 'CITS'),
(401, 21732378, 'Bastab Paul', 'Mechanic Refrigeration & Air-Conditioning', 'CITS'),
(402, 21895678, 'Ashish Kumar Chaurasia', 'Mechanic Refrigeration & Air-Conditioning', 'CITS'),
(403, 90939142, 'Aman Kumar', 'Reading of Drawing and Arithmetic', 'CITS'),
(404, 55791584, 'Raja Kumar', 'Reading of Drawing and Arithmetic', 'CITS'),
(405, 16617935, 'Vivek Ranjan', 'Reading of Drawing and Arithmetic', 'CITS'),
(406, 53793236, 'Devendra Kumar', 'Reading of Drawing and Arithmetic', 'CITS'),
(407, 77952725, 'Mukesh Kumar', 'Reading of Drawing and Arithmetic', 'CITS'),
(408, 19787277, 'Alekhraj Kumar', 'Reading of Drawing and Arithmetic', 'CITS'),
(409, 35522676, 'Manoj Kumar', 'Reading of Drawing and Arithmetic', 'CITS'),
(410, 45441963, 'Sita Ram Shriwas', 'Reading of Drawing and Arithmetic', 'CITS'),
(411, 42911330, 'Dreponjay Roy', 'Reading of Drawing and Arithmetic', 'CITS'),
(412, 60968980, 'Prabhanshu Shekhar', 'Reading of Drawing and Arithmetic', 'CITS'),
(413, 54738077, 'Md Asif', 'Reading of Drawing and Arithmetic', 'CITS'),
(414, 93028380, 'Raj Kumar Singh', 'Reading of Drawing and Arithmetic', 'CITS'),
(415, 3536235, 'Sujit Kumar', 'Reading of Drawing and Arithmetic', 'CITS'),
(416, 4627679, 'Ananya Kumari', 'Reading of Drawing and Arithmetic', 'CITS'),
(417, 47779376, 'Sonu Kumar', 'Reading of Drawing and Arithmetic', 'CITS'),
(418, 70649878, 'Brajesh', 'Reading of Drawing and Arithmetic', 'CITS'),
(419, 92854596, 'Rahul Kumar Singh', 'Reading of Drawing and Arithmetic', 'CITS'),
(420, 9747035, 'Vivekanand Saraswati', 'Reading of Drawing and Arithmetic', 'CITS'),
(421, 82238741, 'Chalasani S Radha Krishna', 'Reading of Drawing and Arithmetic', 'CITS'),
(422, 95159251, 'Anshu Kumar', 'Reading of Drawing and Arithmetic', 'CITS'),
(423, 21105351, 'Anshuman', 'Reading of Drawing and Arithmetic', 'CITS'),
(424, 51522999, 'Vinay Chandrawanshi', 'Reading of Drawing and Arithmetic', 'CITS'),
(425, 80010079, 'Nibha Bharti', 'Reading of Drawing and Arithmetic', 'CITS'),
(426, 3812220, 'Aditya Narayan', 'Reading of Drawing and Arithmetic', 'CITS'),
(427, 32270280, 'Rishikesh Kumar Kushawaha', 'Reading of Drawing and Arithmetic', 'CITS'),
(428, 20551073, 'Abhishek Kumar', 'Reading of Drawing and Arithmetic', 'CITS'),
(429, 5772573, 'Ashish Kumar', 'Reading of Drawing and Arithmetic', 'CITS'),
(430, 1387960, 'Sonam Kumari', 'Reading of Drawing and Arithmetic', 'CITS'),
(431, 73431178, 'Dinamani Kannaujiya', 'Reading of Drawing and Arithmetic', 'CITS'),
(432, 833669, 'Shailendra Kumar Kushwaha', 'Sheet Metal Worker', 'CITS'),
(433, 40666374, 'Pawan Kumar Sharma', 'Sheet Metal Worker', 'CITS'),
(434, 41612114, 'Rajeev Ranjan', 'Sheet Metal Worker', 'CITS'),
(435, 65683169, 'Harsh Kumar', 'Sheet Metal Worker', 'CITS'),
(436, 16310573, 'Dharmpal', 'Sheet Metal Worker', 'CITS'),
(437, 21493118, 'Ankit Koundal', 'Sheet Metal Worker', 'CITS'),
(438, 25552022, 'Shubham Kumar', 'Surveyor', 'CITS'),
(439, 11617635, 'Debabrata Nandi', 'Surveyor', 'CITS'),
(440, 17059085, 'Kaushal Kumar Rawani', 'Surveyor', 'CITS'),
(441, 21265066, 'Aditya Kumar Bhagat', 'Surveyor', 'CITS'),
(442, 99702289, 'Vikash Kumar', 'Surveyor', 'CITS'),
(443, 72669027, 'Raju Lohra', 'Surveyor', 'CITS'),
(444, 72123799, 'Sandeep Kumar Sharma', 'Surveyor', 'CITS'),
(445, 27874186, 'Prem Gupta', 'Surveyor', 'CITS'),
(446, 24294632, 'Deepankar Dutta', 'Surveyor', 'CITS'),
(447, 30478174, 'Anuj Kumar', 'Surveyor', 'CITS'),
(448, 29009808, 'Sanjit Kumar Mahato', 'Surveyor', 'CITS'),
(449, 18319543, 'Birendra Gorain', 'Surveyor', 'CITS'),
(450, 54884422, 'Namita Mandi', 'Surveyor', 'CITS'),
(451, 89202228, 'Bhim Kumar', 'Surveyor', 'CITS'),
(452, 53845737, 'Pankaj Kumar', 'Surveyor', 'CITS'),
(453, 67907485, 'Rajesh Kumar', 'Surveyor', 'CITS'),
(454, 93656387, 'Devraj Rajwar', 'Surveyor', 'CITS'),
(455, 98612777, 'Asish Pasupalak', 'Surveyor', 'CITS'),
(456, 18834202, 'Aman Kumar', 'Surveyor', 'CITS'),
(457, 37361091, 'Uday Kumar Mahato', 'Surveyor', 'CITS'),
(458, 88308554, 'Aman Kumar', 'Surveyor', 'CITS'),
(459, 88323138, 'Vibha Mishra', 'Surveyor', 'CITS'),
(460, 98693913, 'Shubham Kumar', 'Surveyor', 'CITS'),
(461, 67598950, 'Rahul Kumar', 'Surveyor', 'CITS'),
(462, 14039647, 'Nabin Chandra Halder', 'Technician Mechatronics', 'CTS'),
(463, 1496492, 'Shubham Kumar', 'Technician Mechatronics', 'CTS'),
(464, 34546595, 'Sidharth Das', 'Technician Mechatronics', 'CTS'),
(465, 10118338, 'Kundan Keshri', 'Technician Mechatronics', 'CTS'),
(466, 97611983, 'Kusum Das Adhikari', 'Technician Mechatronics', 'CTS'),
(467, 26221462, 'Raushan Kumar', 'Technician Mechatronics', 'CTS'),
(468, 92905684, 'Akash Kumar', 'Technician Mechatronics', 'CTS'),
(469, 38113241, 'Sachin Kumar', 'Technician Mechatronics', 'CTS'),
(470, 20825608, 'Arpita Maity', 'Technician Mechatronics', 'CTS'),
(471, 32164953, 'Anjali Kumari Routh', 'Technician Mechatronics', 'CTS'),
(472, 44298949, 'Satish Kumar', 'Technician Mechatronics', 'CTS'),
(473, 23469021, 'Avinash Kumar', 'Technician Mechatronics', 'CTS'),
(474, 51294769, 'Supriya Bairagi', 'Technician Mechatronics', 'CTS'),
(475, 42869950, 'Subham Mondal', 'Technician Mechatronics', 'CTS'),
(476, 90763441, 'Satish Kumar', 'Technician Mechatronics', 'CTS'),
(477, 48435184, 'Kaushal Kumar', 'Technician Mechatronics', 'CTS'),
(478, 16147421, 'Souvik Senapati', 'Technician Mechatronics', 'CTS'),
(479, 73778387, 'Himangshu Mondal', 'Technician Mechatronics', 'CTS'),
(480, 18463159, 'Kartik Nayak', 'Technician Mechatronics', 'CTS'),
(481, 88575038, 'Souvik Das', 'Technician Mechatronics', 'CTS'),
(482, 89392042, 'Swagatam Patra', 'Technician Mechatronics', 'CTS'),
(483, 84694046, 'Rohit Kumar', 'Turner', 'CITS'),
(484, 60803375, 'Shastr Kumar', 'Turner', 'CITS'),
(485, 78135052, 'Sirshak Manna', 'Turner', 'CITS'),
(486, 2048817, 'Sneha Bharti', 'Turner', 'CITS'),
(487, 94349742, 'Suraj Kumar', 'Turner', 'CITS'),
(488, 10431791, 'Vishwajeet Kumar', 'Turner', 'CITS'),
(489, 70054204, 'Yashwant Mahato', 'Turner', 'CITS'),
(490, 14898755, 'Deepak Kumar Sharma', 'Turner', 'CITS'),
(491, 26195107, 'Deepak Bhardwaj', 'Turner', 'CITS'),
(492, 5781632, 'Prakash Kumar', 'Turner', 'CITS'),
(493, 87386252, 'Rakesh Kumar Das', 'Turner', 'CITS'),
(494, 28877261, 'Aalok Kumar', 'Turner', 'CITS'),
(495, 28029400, 'Sourav Kabiraj', 'Turner', 'CITS'),
(496, 50952748, 'Rahul Yadav', 'Turner', 'CITS'),
(497, 21771135, 'Sahabuddin', 'Turner', 'CITS'),
(498, 93999263, 'Vishal Kumar Thakur', 'Turner', 'CITS'),
(499, 95369287, 'Pappu Kumar Rana', 'Turner', 'CITS'),
(500, 23399710, 'Prince Sharma', 'Turner', 'CITS'),
(501, 84119335, 'Ryajudin', 'Turner', 'CITS'),
(502, 10191024, 'Promod Purty', 'Turner', 'CITS'),
(503, 7685385, 'Pradeep Kumar', 'Turner', 'CITS'),
(504, 49813065, 'Biswajoy Sing', 'Turner', 'CITS'),
(505, 81987411, 'Souvik Sutradhar', 'Welder', 'CITS'),
(506, 51092420, 'Alokesh Chandra Sinha', 'Welder', 'CITS'),
(507, 7115210, 'Mohd. Faiz Khan', 'Welder', 'CITS'),
(508, 1422117, 'Rajeev Kumar', 'Welder', 'CITS'),
(509, 60667773, 'Sanjeep Murmu', 'Welder', 'CITS'),
(510, 40796720, 'Rohit Kumar', 'Welder', 'CITS'),
(511, 30954528, 'Soni Kumari', 'Welder', 'CITS'),
(512, 38553557, 'Ankur Vishwakarma', 'Welder', 'CITS'),
(513, 91355495, 'Mampi Roy', 'Welder', 'CITS'),
(514, 46780451, 'Rupam Das', 'Welder', 'CITS'),
(515, 27058305, 'Dipak Pandit', 'Welder', 'CITS'),
(516, 47136261, 'Mukesh Kumar', 'Welder', 'CITS'),
(517, 14796292, 'Ritesh Kumar', 'Welder', 'CITS'),
(518, 36136986, 'Chandan Kumar Shukla', 'Welder', 'CITS'),
(519, 37342782, 'Anand Kumar Sharma', 'Welder', 'CITS'),
(520, 98419026, 'Sonali Kumari', 'Welder', 'CITS'),
(521, 14474980, 'Rupak Mandal', 'Welder', 'CITS'),
(522, 82130968, 'Dipa Thakur', 'Welder', 'CITS'),
(523, 62509389, 'Prakash Kumar', 'Welder', 'CITS'),
(524, 78647959, 'Jagendra Verma', 'Welder', 'CITS'),
(525, 50359865, 'Rajendra Kumar', 'Welder', 'CITS'),
(526, 60154576, 'Kavita Kumari', 'Welder', 'CITS'),
(527, 19736103, 'Jayprakash Kumar', 'Welder', 'CITS'),
(528, 20804486, 'Sujeet Kumar', 'Welder', 'CITS'),
(529, 21690114, 'Vishu Kumar', 'Welder', 'CITS'),
(530, 94714353, 'Abhishek Yadav', 'Welder', 'CITS'),
(531, 78161902, 'Niraj Kumar', 'Welder', 'CITS'),
(532, 66120276, 'Santosh Kumar', 'Welder', 'CITS'),
(533, 57500652, 'Ujjwal Kumar', 'Welder', 'CITS'),
(534, 29033070, 'Mukesh Ram', 'Welder', 'CITS'),
(535, 3594038, 'Pursottam Mandal', 'Welder', 'CITS'),
(536, 34129731, 'Sumanti Das', 'Welder', 'CITS'),
(537, 99203891, 'Deepak Raj', 'Welder', 'CITS'),
(538, 80267088, 'Pawan Tirkey', 'Welder', 'CITS'),
(539, 52850676, 'Subhasis Das', 'Welder', 'CITS'),
(540, 7426659, 'Saroj Mahato', 'Welder', 'CITS'),
(541, 39311193, 'Ashish Roy', 'Welder', 'CITS'),
(542, 80190885, 'Ranabir Kayal', 'Welder', 'CITS');

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `subject_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `common_for_cts` tinyint(4) NOT NULL DEFAULT 0,
  `common_for_cits` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`subject_id`, `name`, `common_for_cts`, `common_for_cits`, `created_at`, `created_by`, `updated_at`, `updated_by`, `status`) VALUES
(1, 'TRADE THEORY', 1, 1, '2025-06-10 14:25:26', NULL, '2025-06-10 14:25:26', NULL, 1),
(2, 'TRADE PRACTICAL', 1, 1, '2025-06-10 14:26:48', NULL, '2025-06-10 14:26:48', NULL, 1),
(3, 'TRAINING METHODOLOGY THEORY', 0, 1, '2025-06-19 14:56:24', NULL, '2025-06-19 14:56:24', NULL, 1),
(4, 'TRAINING METHODOLOGY PRACTICAL', 0, 1, '2025-06-19 14:56:42', NULL, '2025-06-19 14:56:42', NULL, 1),
(5, 'WORKSHOP CALCULATION AND SCIENCE', 1, 1, '2025-06-19 14:57:40', NULL, '2025-06-25 15:51:48', NULL, 1),
(6, 'ENGINEERING DRAWING', 1, 1, '2025-06-19 14:58:03', NULL, '2025-06-25 15:52:00', NULL, 1),
(7, 'SOFT SKILLS', 0, 0, '2025-06-19 14:58:17', NULL, '2025-06-19 14:58:17', NULL, 1),
(8, 'EMPLOYABILITY SKILLS', 1, 0, '2025-06-25 15:53:13', NULL, '2025-06-25 15:53:13', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `teacher_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `mobile_no` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `password` text DEFAULT NULL,
  `designation` enum('admin','other') NOT NULL DEFAULT 'other',
  `dp_file_path` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`teacher_id`, `name`, `mobile_no`, `email`, `created_at`, `created_by`, `updated_at`, `updated_by`, `status`, `password`, `designation`, `dp_file_path`) VALUES
(8, 'SARBESHWAR MAHATA', '', '', '2025-06-25 11:37:12', NULL, '2025-06-25 11:37:12', NULL, 1, NULL, 'other', NULL),
(15, 'SUMIT BHATTACHARYA', '', '', '2025-06-25 12:30:33', NULL, '2025-06-25 12:30:33', NULL, 1, NULL, 'other', NULL),
(16, 'ATANU GHOSH', '', '', '2025-06-25 14:04:26', NULL, '2025-06-25 14:04:26', NULL, 1, NULL, 'other', NULL),
(17, 'BASHISTA HAZRA', '', '', '2025-06-25 14:06:19', NULL, '2025-06-25 14:06:19', NULL, 1, NULL, 'other', NULL),
(18, 'AMIT KUMAR', '1111111111', 'hello@gmail.com', '2025-06-25 14:09:19', NULL, '2025-06-25 16:05:29', NULL, 1, NULL, 'other', NULL),
(19, ' UTPAL KUMAR SARKAR', '', '', '2025-06-25 14:10:31', NULL, '2025-06-25 14:10:31', NULL, 1, NULL, 'other', NULL),
(20, 'G.K. SAHU', '', '', '2025-06-25 14:12:32', NULL, '2025-06-25 14:12:32', NULL, 1, NULL, 'other', NULL),
(21, 'PALLAB DATTA', '', '', '2025-06-25 14:14:16', NULL, '2025-06-25 14:14:16', NULL, 1, NULL, 'other', NULL),
(22, ' PRASANT CHATTERJEE', '', '', '2025-06-25 14:15:45', NULL, '2025-06-25 14:15:45', NULL, 1, NULL, 'other', NULL),
(23, ' NIRMALYA HAZRA', '', '', '2025-06-25 14:18:36', NULL, '2025-06-25 14:18:36', NULL, 1, NULL, 'other', NULL),
(24, ' TAPAN KUMAR HALDER', '', '', '2025-06-25 14:20:28', NULL, '2025-06-25 14:20:28', NULL, 1, NULL, 'other', NULL),
(25, ' PUJA MUKHARJEE', '', '', '2025-06-25 14:22:25', NULL, '2025-06-25 14:22:25', NULL, 1, NULL, 'other', NULL),
(26, 'RUPEN KUMAR SAHA', '', '', '2025-06-25 14:24:33', NULL, '2025-06-25 14:24:33', NULL, 1, NULL, 'other', NULL),
(27, 'BIBEK KUMAR SINGH', '', '', '2025-06-25 14:27:14', NULL, '2025-06-25 14:27:14', NULL, 1, NULL, 'other', NULL),
(28, ' KARAN KUMAR PANDEY', '', 'kpandey@gmail.com', '2025-06-25 14:28:25', NULL, '2025-06-26 10:00:02', NULL, 1, '$2y$10$CPvcUClpnM/li.LCX4PQK.GB1znHwDhZr32zWkAu4aP7l1iqBXqwS', 'admin', 'dp_uploads/profile_685cccca000417.98665932.jpg'),
(29, 'SUBHANKAR RANA', '', '', '2025-06-25 14:29:45', NULL, '2025-06-25 14:29:45', NULL, 1, NULL, 'other', NULL),
(30, ' SUPRIYA RANA', '', '', '2025-06-25 14:31:48', NULL, '2025-06-25 14:31:48', NULL, 1, NULL, 'other', NULL),
(31, 'DHIRAJ KUMAR GHOSH', '1111111111', 'hello@gmail.com', '2025-06-25 14:34:30', NULL, '2025-06-25 16:03:56', NULL, 1, NULL, 'other', NULL),
(32, 'DEBABRATA MONDAL', '', '', '2025-06-25 14:35:44', NULL, '2025-06-25 14:35:44', NULL, 1, NULL, 'other', NULL),
(33, 'MADHUSUDAN KARMAKAR', '1111111111', 'hello@gmail.com', '2025-06-25 14:37:16', NULL, '2025-06-25 16:01:57', NULL, 1, NULL, 'other', NULL),
(34, 'MD TALIB', '', '', '2025-06-25 15:30:20', NULL, '2025-06-25 15:30:20', NULL, 1, NULL, 'other', NULL),
(35, 'SAYANTI MANNA', '', '', '2025-06-25 15:31:23', NULL, '2025-06-25 15:31:23', NULL, 1, NULL, 'other', NULL),
(36, 'TULSI KUMAR MAHATO', '', '', '2025-06-25 15:32:53', NULL, '2025-06-25 15:32:53', NULL, 1, NULL, 'other', NULL),
(37, 'ISHITA BISWAS', '', '', '2025-06-25 15:54:25', NULL, '2025-06-25 15:54:25', NULL, 1, '$2y$10$c/rrPydlmP93n6wy9Ozdv.Zn9aN77.BewJq64q2.lgWmUdepejE3i', 'other', NULL),
(38, 'GOUTAM SARKAR', '', '', '2025-06-25 15:58:13', NULL, '2025-06-25 15:58:13', NULL, 1, '$2y$10$4NCOIiJQex2tHrB7hGKOGOV161BzPdDzJ80uWyUgZIh/WZ9jLpM0.', 'other', NULL),
(39, 'BANANI POREL', '', '', '2025-06-25 16:11:24', NULL, '2025-06-25 16:11:24', NULL, 1, '$2y$10$754K9Vq0onM/u9dPJs6Vy.kru3HRbjVgp8ytEXM0lZfwNs0A0Hv1u', 'other', NULL),
(40, 'SATYAM', '1111111111', 'hello@gmail.com', '2025-06-25 16:18:46', NULL, '2025-06-26 15:41:42', NULL, 1, '$2y$10$zr6IGLILxQxJ3ICl10qYtuCHY2ysG7sIVez0uOZFC7S3mwFQDAc1u', 'other', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `teacher_subject_trade`
--

CREATE TABLE `teacher_subject_trade` (
  `id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `trade_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `program` enum('CTS','CITS') NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teacher_subject_trade`
--

INSERT INTO `teacher_subject_trade` (`id`, `teacher_id`, `trade_id`, `subject_id`, `program`, `created_at`, `created_by`, `updated_at`, `updated_by`, `status`) VALUES
(10, 8, 8, 1, 'CITS', '2025-06-25 11:37:12', NULL, '2025-06-25 12:15:18', NULL, 1),
(11, 8, 8, 2, 'CITS', '2025-06-25 11:37:12', NULL, '2025-06-25 12:16:10', NULL, 1),
(12, 8, 8, 4, 'CITS', '2025-06-25 11:37:12', NULL, '2025-06-25 12:16:48', NULL, 1),
(13, 8, 8, 5, 'CITS', '2025-06-25 11:37:12', NULL, '2025-06-25 12:16:57', NULL, 1),
(14, 8, 8, 6, 'CITS', '2025-06-25 11:37:12', NULL, '2025-06-25 12:17:22', NULL, 1),
(15, 8, 9, 1, 'CITS', '2025-06-25 11:37:12', NULL, '2025-06-25 12:19:34', NULL, 1),
(16, 8, 9, 2, 'CITS', '2025-06-25 11:37:12', NULL, '2025-06-25 12:19:53', NULL, 1),
(44, 15, 8, 1, 'CITS', '2025-06-25 12:30:33', NULL, '2025-06-25 12:30:33', NULL, 1),
(45, 15, 8, 2, 'CITS', '2025-06-25 12:30:33', NULL, '2025-06-25 12:30:33', NULL, 1),
(46, 15, 9, 4, 'CITS', '2025-06-25 12:30:33', NULL, '2025-06-25 12:30:33', NULL, 1),
(47, 15, 9, 5, 'CITS', '2025-06-25 12:30:33', NULL, '2025-06-25 12:30:33', NULL, 1),
(48, 15, 9, 6, 'CITS', '2025-06-25 12:30:33', NULL, '2025-06-25 12:30:33', NULL, 1),
(49, 16, 4, 1, 'CITS', '2025-06-25 14:04:26', NULL, '2025-06-25 14:04:26', NULL, 1),
(50, 16, 4, 5, 'CITS', '2025-06-25 14:04:26', NULL, '2025-06-25 14:04:26', NULL, 1),
(51, 16, 4, 6, 'CITS', '2025-06-25 14:04:26', NULL, '2025-06-25 14:04:26', NULL, 1),
(52, 17, 4, 2, 'CITS', '2025-06-25 14:06:19', NULL, '2025-06-25 14:06:19', NULL, 1),
(53, 17, 4, 4, 'CITS', '2025-06-25 14:06:19', NULL, '2025-06-25 14:06:19', NULL, 1),
(54, 18, 19, 2, 'CTS', '2025-06-25 14:09:19', NULL, '2025-06-25 16:05:29', NULL, 1),
(55, 18, 31, 2, 'CITS', '2025-06-25 14:09:19', NULL, '2025-06-25 14:09:19', NULL, 1),
(57, 18, 31, 4, 'CITS', '2025-06-25 14:09:19', NULL, '2025-06-25 14:09:19', NULL, 1),
(58, 18, 31, 5, 'CITS', '2025-06-25 14:09:19', NULL, '2025-06-25 14:09:19', NULL, 1),
(59, 18, 5, 2, 'CITS', '2025-06-25 14:09:19', NULL, '2025-06-25 14:09:19', NULL, 1),
(60, 18, 5, 4, 'CITS', '2025-06-25 14:09:19', NULL, '2025-06-25 14:09:19', NULL, 1),
(61, 19, 5, 1, 'CITS', '2025-06-25 14:10:31', NULL, '2025-06-25 14:10:31', NULL, 1),
(62, 19, 5, 5, 'CITS', '2025-06-25 14:10:31', NULL, '2025-06-25 14:10:31', NULL, 1),
(63, 19, 5, 6, 'CITS', '2025-06-25 14:10:31', NULL, '2025-06-25 14:10:31', NULL, 1),
(64, 20, 6, 1, 'CITS', '2025-06-25 14:12:32', NULL, '2025-06-25 14:12:32', NULL, 1),
(65, 20, 6, 2, 'CITS', '2025-06-25 14:12:32', NULL, '2025-06-25 14:12:32', NULL, 1),
(66, 20, 0, 0, '', '2025-06-25 14:12:32', NULL, '2025-06-25 14:12:32', NULL, 1),
(67, 20, 6, 4, 'CITS', '2025-06-25 14:12:32', NULL, '2025-06-25 14:12:32', NULL, 1),
(68, 20, 6, 5, 'CITS', '2025-06-25 14:12:32', NULL, '2025-06-25 14:12:32', NULL, 1),
(69, 20, 6, 6, 'CITS', '2025-06-25 14:12:32', NULL, '2025-06-25 14:12:32', NULL, 1),
(70, 21, 7, 1, 'CITS', '2025-06-25 14:14:17', NULL, '2025-06-25 14:14:17', NULL, 1),
(71, 21, 7, 2, 'CITS', '2025-06-25 14:14:17', NULL, '2025-06-25 14:14:17', NULL, 1),
(72, 21, 7, 4, 'CITS', '2025-06-25 14:14:17', NULL, '2025-06-25 14:14:17', NULL, 1),
(73, 21, 7, 5, 'CITS', '2025-06-25 14:14:17', NULL, '2025-06-25 14:14:17', NULL, 1),
(74, 21, 7, 6, 'CITS', '2025-06-25 14:14:17', NULL, '2025-06-25 14:14:17', NULL, 1),
(75, 22, 9, 1, 'CITS', '2025-06-25 14:15:45', NULL, '2025-06-25 14:15:45', NULL, 1),
(76, 22, 9, 2, 'CITS', '2025-06-25 14:15:45', NULL, '2025-06-25 14:15:45', NULL, 1),
(77, 23, 10, 1, 'CITS', '2025-06-25 14:18:36', NULL, '2025-06-25 14:18:36', NULL, 1),
(78, 23, 10, 2, 'CITS', '2025-06-25 14:18:36', NULL, '2025-06-25 14:18:36', NULL, 1),
(79, 23, 10, 4, 'CITS', '2025-06-25 14:18:36', NULL, '2025-06-25 14:18:36', NULL, 1),
(80, 23, 10, 5, 'CITS', '2025-06-25 14:18:36', NULL, '2025-06-25 14:18:36', NULL, 1),
(81, 23, 10, 6, 'CITS', '2025-06-25 14:18:36', NULL, '2025-06-25 14:18:36', NULL, 1),
(82, 24, 11, 1, 'CITS', '2025-06-25 14:20:28', NULL, '2025-06-25 14:20:28', NULL, 1),
(83, 24, 11, 2, 'CITS', '2025-06-25 14:20:28', NULL, '2025-06-25 14:20:28', NULL, 1),
(84, 24, 11, 4, 'CITS', '2025-06-25 14:20:28', NULL, '2025-06-25 14:20:28', NULL, 1),
(85, 24, 11, 5, 'CITS', '2025-06-25 14:20:28', NULL, '2025-06-25 14:20:28', NULL, 1),
(86, 24, 11, 6, 'CITS', '2025-06-25 14:20:28', NULL, '2025-06-25 14:20:28', NULL, 1),
(87, 25, 12, 1, 'CITS', '2025-06-25 14:22:25', NULL, '2025-06-25 14:22:25', NULL, 1),
(88, 25, 12, 2, 'CITS', '2025-06-25 14:22:25', NULL, '2025-06-25 14:22:25', NULL, 1),
(89, 25, 12, 4, 'CITS', '2025-06-25 14:22:25', NULL, '2025-06-25 14:22:25', NULL, 1),
(90, 25, 12, 5, 'CITS', '2025-06-25 14:22:25', NULL, '2025-06-25 14:22:25', NULL, 1),
(91, 26, 13, 1, 'CITS', '2025-06-25 14:24:33', NULL, '2025-06-25 14:24:33', NULL, 1),
(92, 26, 13, 2, 'CITS', '2025-06-25 14:24:33', NULL, '2025-06-25 14:24:33', NULL, 1),
(93, 26, 13, 4, 'CITS', '2025-06-25 14:24:33', NULL, '2025-06-25 14:24:33', NULL, 1),
(94, 26, 13, 5, 'CITS', '2025-06-25 14:24:33', NULL, '2025-06-25 14:24:33', NULL, 1),
(95, 27, 14, 1, 'CITS', '2025-06-25 14:27:14', NULL, '2025-06-25 14:27:14', NULL, 1),
(96, 27, 14, 2, 'CITS', '2025-06-25 14:27:14', NULL, '2025-06-25 14:27:14', NULL, 1),
(97, 27, 14, 4, 'CITS', '2025-06-25 14:27:14', NULL, '2025-06-25 14:27:14', NULL, 1),
(98, 27, 14, 5, 'CITS', '2025-06-25 14:27:14', NULL, '2025-06-25 14:27:14', NULL, 1),
(99, 28, 2, 1, 'CITS', '2025-06-25 14:28:25', NULL, '2025-06-25 14:28:25', NULL, 1),
(100, 28, 2, 2, 'CITS', '2025-06-25 14:28:25', NULL, '2025-06-25 14:28:25', NULL, 1),
(101, 28, 2, 4, 'CITS', '2025-06-25 14:28:25', NULL, '2025-06-25 14:28:25', NULL, 1),
(102, 29, 15, 1, 'CITS', '2025-06-25 14:29:45', NULL, '2025-06-25 14:29:45', NULL, 1),
(103, 29, 15, 2, 'CITS', '2025-06-25 14:29:45', NULL, '2025-06-25 14:29:45', NULL, 1),
(104, 29, 15, 4, 'CITS', '2025-06-25 14:29:45', NULL, '2025-06-25 14:29:45', NULL, 1),
(105, 29, 15, 5, 'CITS', '2025-06-25 14:29:45', NULL, '2025-06-25 14:29:45', NULL, 1),
(106, 29, 15, 6, 'CITS', '2025-06-25 14:29:45', NULL, '2025-06-25 14:29:45', NULL, 1),
(107, 30, 16, 1, 'CITS', '2025-06-25 14:31:48', NULL, '2025-06-25 14:31:48', NULL, 1),
(108, 30, 16, 2, 'CITS', '2025-06-25 14:31:48', NULL, '2025-06-25 14:31:48', NULL, 1),
(109, 30, 16, 4, 'CITS', '2025-06-25 14:31:48', NULL, '2025-06-25 14:31:48', NULL, 1),
(110, 30, 16, 5, 'CITS', '2025-06-25 14:31:48', NULL, '2025-06-25 14:31:48', NULL, 1),
(111, 30, 16, 6, 'CITS', '2025-06-25 14:31:48', NULL, '2025-06-25 14:53:26', NULL, 1),
(112, 31, 24, 6, 'CTS', '2025-06-25 14:34:30', NULL, '2025-06-25 16:03:56', NULL, 1),
(113, 31, 18, 1, 'CITS', '2025-06-25 14:34:30', NULL, '2025-06-25 14:34:30', NULL, 1),
(114, 31, 17, 2, 'CITS', '2025-06-25 14:34:30', NULL, '2025-06-25 14:34:30', NULL, 1),
(115, 31, 18, 2, 'CITS', '2025-06-25 14:34:30', NULL, '2025-06-25 14:34:30', NULL, 1),
(116, 31, 17, 4, 'CITS', '2025-06-25 14:34:30', NULL, '2025-06-25 14:34:30', NULL, 1),
(117, 31, 18, 4, 'CITS', '2025-06-25 14:34:30', NULL, '2025-06-25 14:34:30', NULL, 1),
(118, 31, 17, 5, 'CITS', '2025-06-25 14:34:30', NULL, '2025-06-25 14:34:30', NULL, 1),
(119, 31, 18, 5, 'CITS', '2025-06-25 14:34:30', NULL, '2025-06-25 14:34:30', NULL, 1),
(120, 31, 17, 6, 'CITS', '2025-06-25 14:34:30', NULL, '2025-06-25 14:34:30', NULL, 1),
(121, 31, 18, 6, 'CITS', '2025-06-25 14:34:30', NULL, '2025-06-25 14:34:30', NULL, 1),
(122, 32, 3, 1, 'CITS', '2025-06-25 14:35:45', NULL, '2025-06-25 14:35:45', NULL, 1),
(123, 32, 3, 2, 'CITS', '2025-06-25 14:35:45', NULL, '2025-06-25 14:35:45', NULL, 1),
(124, 32, 3, 4, 'CITS', '2025-06-25 14:35:45', NULL, '2025-06-25 14:35:45', NULL, 1),
(125, 32, 3, 5, 'CITS', '2025-06-25 14:35:45', NULL, '2025-06-25 14:35:45', NULL, 1),
(126, 32, 3, 6, 'CITS', '2025-06-25 14:35:45', NULL, '2025-06-25 14:35:45', NULL, 1),
(127, 33, 23, 6, 'CTS', '2025-06-25 14:37:17', NULL, '2025-06-25 16:01:57', NULL, 1),
(128, 33, 21, 2, 'CITS', '2025-06-25 14:37:17', NULL, '2025-06-25 14:37:17', NULL, 1),
(129, 33, 21, 4, 'CITS', '2025-06-25 14:37:17', NULL, '2025-06-25 14:37:17', NULL, 1),
(130, 33, 21, 5, 'CITS', '2025-06-25 14:37:17', NULL, '2025-06-25 14:37:17', NULL, 1),
(131, 33, 21, 6, 'CITS', '2025-06-25 14:37:17', NULL, '2025-06-25 14:37:17', NULL, 1),
(132, 34, 22, 1, 'CTS', '2025-06-25 15:30:20', NULL, '2025-06-25 15:30:20', NULL, 1),
(133, 34, 22, 2, 'CTS', '2025-06-25 15:30:20', NULL, '2025-06-25 15:30:20', NULL, 1),
(134, 34, 22, 5, 'CTS', '2025-06-25 15:30:20', NULL, '2025-06-25 15:30:20', NULL, 1),
(135, 34, 22, 6, 'CTS', '2025-06-25 15:30:20', NULL, '2025-06-25 15:30:20', NULL, 1),
(136, 35, 1, 1, 'CTS', '2025-06-25 15:31:23', NULL, '2025-06-25 15:31:23', NULL, 1),
(137, 35, 1, 2, 'CTS', '2025-06-25 15:31:23', NULL, '2025-06-25 15:31:23', NULL, 1),
(138, 36, 23, 2, 'CTS', '2025-06-25 15:32:53', NULL, '2025-06-25 15:32:53', NULL, 1),
(139, 37, 1, 8, 'CTS', '2025-06-25 15:54:25', NULL, '2025-06-25 15:54:25', NULL, 1),
(140, 38, 19, 1, 'CTS', '2025-06-25 15:58:13', NULL, '2025-06-25 15:58:13', NULL, 1),
(141, 38, 20, 1, 'CTS', '2025-06-25 15:58:13', NULL, '2025-06-25 15:58:13', NULL, 1),
(142, 38, 20, 2, 'CTS', '2025-06-25 15:58:13', NULL, '2025-06-25 15:58:13', NULL, 1),
(143, 38, 20, 5, 'CTS', '2025-06-25 15:58:13', NULL, '2025-06-25 15:58:13', NULL, 1),
(144, 39, 8, 3, 'CITS', '2025-06-25 16:11:24', NULL, '2025-06-25 16:11:24', NULL, 1),
(145, 39, 4, 3, 'CITS', '2025-06-25 16:11:24', NULL, '2025-06-25 16:11:24', NULL, 1),
(146, 39, 5, 3, 'CITS', '2025-06-25 16:11:24', NULL, '2025-06-25 16:11:24', NULL, 1),
(147, 39, 6, 3, 'CITS', '2025-06-25 16:11:24', NULL, '2025-06-25 16:11:24', NULL, 1),
(148, 39, 7, 3, 'CITS', '2025-06-25 16:11:24', NULL, '2025-06-25 16:11:24', NULL, 1),
(149, 39, 9, 3, 'CITS', '2025-06-25 16:11:24', NULL, '2025-06-25 16:11:24', NULL, 1),
(150, 39, 10, 3, 'CITS', '2025-06-25 16:11:24', NULL, '2025-06-25 16:11:24', NULL, 1),
(151, 39, 11, 3, 'CITS', '2025-06-25 16:11:24', NULL, '2025-06-25 16:11:24', NULL, 1),
(152, 39, 12, 3, 'CITS', '2025-06-25 16:11:24', NULL, '2025-06-25 16:11:24', NULL, 1),
(153, 39, 13, 3, 'CITS', '2025-06-25 16:11:24', NULL, '2025-06-25 16:11:24', NULL, 1),
(154, 39, 14, 3, 'CITS', '2025-06-25 16:11:24', NULL, '2025-06-25 16:11:24', NULL, 1),
(155, 39, 2, 3, 'CITS', '2025-06-25 16:11:24', NULL, '2025-06-25 16:11:24', NULL, 1),
(156, 39, 15, 3, 'CITS', '2025-06-25 16:11:24', NULL, '2025-06-25 16:11:24', NULL, 1),
(157, 39, 16, 3, 'CITS', '2025-06-25 16:11:24', NULL, '2025-06-25 16:11:24', NULL, 1),
(158, 39, 17, 3, 'CITS', '2025-06-25 16:11:24', NULL, '2025-06-25 16:11:24', NULL, 1),
(159, 39, 18, 3, 'CITS', '2025-06-25 16:11:24', NULL, '2025-06-25 16:11:24', NULL, 1),
(160, 39, 3, 3, 'CITS', '2025-06-25 16:11:24', NULL, '2025-06-25 16:11:24', NULL, 1),
(161, 39, 31, 3, 'CITS', '2025-06-25 16:11:24', NULL, '2025-06-25 16:11:24', NULL, 1),
(162, 39, 21, 3, 'CITS', '2025-06-25 16:11:24', NULL, '2025-06-25 16:11:24', NULL, 1),
(163, 40, 2, 7, 'CITS', '2025-06-25 16:18:46', NULL, '2025-06-26 15:41:42', NULL, 1),
(164, 40, 4, 3, 'CITS', '2025-06-25 16:18:46', NULL, '2025-06-25 16:18:46', NULL, 1),
(165, 40, 5, 3, 'CITS', '2025-06-25 16:18:46', NULL, '2025-06-25 16:18:46', NULL, 1),
(166, 40, 6, 3, 'CITS', '2025-06-25 16:18:46', NULL, '2025-06-25 16:18:46', NULL, 1),
(167, 40, 7, 3, 'CITS', '2025-06-25 16:18:46', NULL, '2025-06-25 16:18:46', NULL, 1),
(168, 40, 9, 3, 'CITS', '2025-06-25 16:18:46', NULL, '2025-06-25 16:18:46', NULL, 1),
(169, 40, 10, 3, 'CITS', '2025-06-25 16:18:46', NULL, '2025-06-25 16:18:46', NULL, 1),
(170, 40, 11, 3, 'CITS', '2025-06-25 16:18:46', NULL, '2025-06-25 16:18:46', NULL, 1),
(171, 40, 12, 3, 'CITS', '2025-06-25 16:18:46', NULL, '2025-06-25 16:18:46', NULL, 1),
(172, 40, 13, 3, 'CITS', '2025-06-25 16:18:46', NULL, '2025-06-25 16:18:46', NULL, 1),
(173, 40, 14, 3, 'CITS', '2025-06-25 16:18:46', NULL, '2025-06-25 16:18:46', NULL, 1),
(174, 40, 2, 3, 'CITS', '2025-06-25 16:18:46', NULL, '2025-06-25 16:18:46', NULL, 1),
(175, 40, 15, 3, 'CITS', '2025-06-25 16:18:46', NULL, '2025-06-25 16:18:46', NULL, 1),
(176, 40, 16, 3, 'CITS', '2025-06-25 16:18:46', NULL, '2025-06-25 16:18:46', NULL, 1),
(177, 40, 17, 3, 'CITS', '2025-06-25 16:18:46', NULL, '2025-06-25 16:18:46', NULL, 1),
(178, 40, 18, 3, 'CITS', '2025-06-25 16:18:46', NULL, '2025-06-25 16:18:46', NULL, 1),
(179, 40, 3, 3, 'CITS', '2025-06-25 16:18:46', NULL, '2025-06-25 16:18:46', NULL, 1),
(180, 40, 31, 3, 'CITS', '2025-06-25 16:18:46', NULL, '2025-06-25 16:18:46', NULL, 1),
(181, 40, 21, 3, 'CITS', '2025-06-25 16:18:46', NULL, '2025-06-25 16:18:46', NULL, 1),
(182, 40, 19, 8, 'CTS', '2025-06-25 16:18:46', NULL, '2025-06-25 16:18:46', NULL, 1),
(183, 40, 24, 8, 'CTS', '2025-06-25 16:18:47', NULL, '2025-06-25 16:18:47', NULL, 1),
(184, 40, 22, 8, 'CTS', '2025-06-25 16:18:47', NULL, '2025-06-25 16:18:47', NULL, 1),
(185, 40, 23, 8, 'CTS', '2025-06-25 16:18:47', NULL, '2025-06-25 16:18:47', NULL, 1),
(186, 40, 20, 8, 'CTS', '2025-06-25 16:18:47', NULL, '2025-06-25 16:18:47', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `trade`
--

CREATE TABLE `trade` (
  `trade_id` int(11) NOT NULL,
  `trade_name` varchar(100) NOT NULL,
  `program` enum('CTS','CITS') NOT NULL DEFAULT 'CITS',
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trade`
--

INSERT INTO `trade` (`trade_id`, `trade_name`, `program`, `created_at`, `created_by`, `updated_at`, `updated_by`, `status`) VALUES
(1, 'Artificial Intelligence and Programming Assistant', 'CTS', '2025-06-06 11:02:15', NULL, '2025-06-12 17:07:44', NULL, 1),
(2, 'Computer Software Applications', 'CITS', '2025-06-04 14:11:09', NULL, '2025-06-12 17:08:27', NULL, 1),
(3, 'Fitter 1 & 2', 'CITS', '2025-06-04 14:16:51', NULL, '2025-06-25 11:48:58', NULL, 1),
(4, 'Machinist', 'CITS', '2025-06-06 11:08:05', NULL, '2025-06-12 17:12:24', NULL, 1),
(5, 'Mechanic Machine Tool Maintenance (MMTM)', 'CITS', '2025-06-06 11:08:48', NULL, '2025-06-12 17:14:08', NULL, 1),
(6, 'Turner', 'CITS', '2025-06-06 11:08:57', NULL, '2025-06-12 17:16:25', NULL, 1),
(7, 'Mechanic Motor Vehicle', 'CITS', '2025-06-06 11:09:03', NULL, '2025-06-12 17:14:27', NULL, 1),
(8, 'Electrician', 'CITS', '2025-06-06 11:09:10', NULL, '2025-06-12 17:09:39', NULL, 1),
(9, 'Mechanic Refrigeration & Air-Conditioning', 'CITS', '2025-06-06 11:09:17', NULL, '2025-06-12 17:14:49', NULL, 1),
(10, 'Instrument Mechanic', 'CITS', '2025-06-06 11:09:20', NULL, '2025-06-12 17:11:23', NULL, 1),
(11, 'Draughtsman (Civil)', 'CITS', '2025-06-06 11:09:24', NULL, '2025-06-12 17:08:59', NULL, 1),
(12, 'Surveyor', 'CITS', '2025-06-06 11:09:36', NULL, '2025-06-12 17:15:48', NULL, 1),
(13, 'Draughtsman (Mech)', 'CITS', '2025-06-06 11:09:44', NULL, '2025-06-12 17:09:18', NULL, 1),
(14, 'Reading of Drawing and Arithmetic', 'CITS', '2025-06-06 11:09:49', NULL, '2025-06-12 17:15:09', NULL, 1),
(15, 'Welder', 'CITS', '2025-06-06 11:09:54', NULL, '2025-06-12 17:16:43', NULL, 1),
(16, 'Carpenter', 'CITS', '2025-06-06 11:10:05', NULL, '2025-06-12 17:08:09', NULL, 1),
(17, 'Foundryman', 'CITS', '2025-06-06 11:10:14', NULL, '2025-06-12 17:10:54', NULL, 1),
(18, 'Sheet Metal Worker', 'CITS', '2025-06-06 11:10:21', NULL, '2025-06-12 17:15:26', NULL, 1),
(19, 'IoT Technician (Smart Agriculture)', 'CTS', '2025-06-06 11:11:25', NULL, '2025-06-12 17:11:46', NULL, 1),
(20, 'Technician Mechatronics', 'CTS', '2025-06-06 11:11:46', NULL, '2025-06-12 17:16:04', NULL, 1),
(21, 'Machinist Grinder', 'CITS', '2025-06-10 12:06:48', NULL, '2025-06-12 17:13:29', NULL, 1),
(22, 'Machinist CTS', 'CTS', '2025-06-10 12:14:59', NULL, '2025-06-25 11:50:34', NULL, 1),
(23, 'Machinist Grinder CTS', 'CTS', '2025-06-10 12:15:18', NULL, '2025-06-25 11:50:47', NULL, 1),
(24, 'Foundryman CTS', 'CTS', '2025-06-10 12:22:14', NULL, '2025-06-25 11:50:56', NULL, 1),
(31, 'Fitter 3 & 4', 'CITS', '2025-06-25 11:49:17', NULL, '2025-06-25 11:49:17', NULL, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`subject_id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`teacher_id`);

--
-- Indexes for table `teacher_subject_trade`
--
ALTER TABLE `teacher_subject_trade`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trade`
--
ALTER TABLE `trade`
  ADD PRIMARY KEY (`trade_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=548;

--
-- AUTO_INCREMENT for table `subject`
--
ALTER TABLE `subject`
  MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `teacher_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `teacher_subject_trade`
--
ALTER TABLE `teacher_subject_trade`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=187;

--
-- AUTO_INCREMENT for table `trade`
--
ALTER TABLE `trade`
  MODIFY `trade_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
