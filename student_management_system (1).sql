-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 11, 2026 at 06:03 PM
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
-- Database: `student_management_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `academic_details`
--

CREATE TABLE `academic_details` (
  `academic_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `qualification_id` int(11) NOT NULL,
  `school_name` varchar(150) NOT NULL,
  `percentage` decimal(5,2) NOT NULL,
  `board_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `academic_details`
--

INSERT INTO `academic_details` (`academic_id`, `student_id`, `qualification_id`, `school_name`, `percentage`, `board_id`) VALUES
(3, 2, 1, 'F\'MAFKEWP', 12.00, 5),
(4, 2, 2, 'schoool', 12.00, 5),
(9, 5, 1, 'SCHOOL', 90.00, 5),
(10, 5, 2, 'schoool', 90.00, 5);

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `attendance_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `attendance_date` date NOT NULL,
  `status` enum('Present','Absent') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`attendance_id`, `student_id`, `staff_id`, `class_id`, `subject_id`, `attendance_date`, `status`, `created_at`) VALUES
(1, 5, 27, 4, 5, '2026-01-11', 'Present', '2026-07-11 10:30:09');

-- --------------------------------------------------------

--
-- Table structure for table `boards`
--

CREATE TABLE `boards` (
  `board_id` int(11) NOT NULL,
  `board_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `boards`
--

INSERT INTO `boards` (`board_id`, `board_name`) VALUES
(2, 'CBSE'),
(4, 'IB'),
(3, 'ICSE'),
(5, 'IGCSE'),
(6, 'NIOS'),
(1, 'State Board');

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `class_id` int(11) NOT NULL,
  `class_name` varchar(50) NOT NULL,
  `department_id` int(11) NOT NULL,
  `year_id` int(11) NOT NULL,
  `section` varchar(5) NOT NULL,
  `class_advisor` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`class_id`, `class_name`, `department_id`, `year_id`, `section`, `class_advisor`) VALUES
(1, 'aids I-A', 14, 1, 'A', 25),
(2, 'B.Sc Computer Science I-B', 4, 1, 'B', 26),
(3, 'B.Com I-C', 5, 1, 'C', 18),
(4, 'B.Sc Computer Science II-B', 4, 2, 'B', 27);

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `department_id` int(11) NOT NULL,
  `department_name` varchar(100) NOT NULL,
  `program_level` enum('UG','PG','Diploma','PhD') NOT NULL DEFAULT 'UG'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`department_id`, `department_name`, `program_level`) VALUES
(1, 'B.Tech CSE', 'UG'),
(2, 'B.Tech IT', 'UG'),
(3, 'BCA', 'UG'),
(4, 'B.Sc Computer Science', 'UG'),
(5, 'B.Com', 'UG'),
(6, 'BBA', 'UG'),
(7, 'MCA', 'PG'),
(8, 'MBA', 'PG'),
(9, 'Artificial Intelligence', 'UG'),
(10, 'management', 'PG'),
(14, 'aids', 'UG');

-- --------------------------------------------------------

--
-- Table structure for table `designations`
--

CREATE TABLE `designations` (
  `designation_id` int(11) NOT NULL,
  `designation_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `designations`
--

INSERT INTO `designations` (`designation_id`, `designation_name`) VALUES
(8, 'Administrator'),
(5, 'Assistant Professor'),
(3, 'HOD'),
(6, 'Lab Assistant'),
(7, 'Office Staff'),
(1, 'Principal'),
(4, 'Professor'),
(2, 'Vice Principal');

-- --------------------------------------------------------

--
-- Table structure for table `document_types`
--

CREATE TABLE `document_types` (
  `document_type_id` int(11) NOT NULL,
  `document_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `document_types`
--

INSERT INTO `document_types` (`document_type_id`, `document_name`) VALUES
(2, '10th Marksheet'),
(3, '12th Marksheet'),
(4, 'Aadhaar'),
(10, 'Address Proof'),
(6, 'Community Certificate'),
(11, 'Experience Certificate'),
(8, 'Highest Qualification Certificate'),
(9, 'ID Proof'),
(12, 'Joining Letter'),
(1, 'Photo'),
(7, 'Resume'),
(5, 'Transfer Certificate');

-- --------------------------------------------------------

--
-- Table structure for table `emergency_contacts`
--

CREATE TABLE `emergency_contacts` (
  `emergency_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `emergency_name` varchar(100) NOT NULL,
  `relationship_type_id` int(11) NOT NULL,
  `emergency_phone` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `emergency_contacts`
--

INSERT INTO `emergency_contacts` (`emergency_id`, `student_id`, `emergency_name`, `relationship_type_id`, `emergency_phone`) VALUES
(2, 2, 'FJPIoewjF', 2, '6789028902'),
(5, 5, 'sssssss', 1, '6789028902');

-- --------------------------------------------------------

--
-- Table structure for table `family_details`
--

CREATE TABLE `family_details` (
  `family_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `relationship_type_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `occupation` varchar(100) DEFAULT NULL,
  `phone` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `family_details`
--

INSERT INTO `family_details` (`family_id`, `student_id`, `relationship_type_id`, `name`, `occupation`, `phone`) VALUES
(3, 2, 1, 'F\'WKF\"kf', 'denwonfklw', '1234567890'),
(4, 2, 2, 'SJNPCNAEIOW', 'housewife', '6732890000'),
(9, 5, 1, 'F\'WKF\"kf', 'business', '1111111111'),
(10, 5, 2, 'zsnov[', 'housewife', '6732890000');

-- --------------------------------------------------------

--
-- Table structure for table `genders`
--

CREATE TABLE `genders` (
  `gender_id` int(11) NOT NULL,
  `gender_name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `genders`
--

INSERT INTO `genders` (`gender_id`, `gender_name`) VALUES
(2, 'Female'),
(1, 'Male'),
(3, 'Others');

-- --------------------------------------------------------

--
-- Table structure for table `program_levels`
--

CREATE TABLE `program_levels` (
  `program_level_id` int(11) NOT NULL,
  `program_level_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `program_levels`
--

INSERT INTO `program_levels` (`program_level_id`, `program_level_name`) VALUES
(3, 'Diploma'),
(2, 'Postgraduate'),
(1, 'Undergraduate');

-- --------------------------------------------------------

--
-- Table structure for table `qualifications`
--

CREATE TABLE `qualifications` (
  `qualification_id` int(11) NOT NULL,
  `qualification_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `qualifications`
--

INSERT INTO `qualifications` (`qualification_id`, `qualification_name`) VALUES
(1, '10th'),
(2, '12th'),
(3, 'B.E'),
(7, 'B.Sc'),
(4, 'B.Tech'),
(5, 'M.E'),
(8, 'M.Sc'),
(6, 'M.Tech'),
(10, 'MBA'),
(9, 'MCA'),
(11, 'Ph.D');

-- --------------------------------------------------------

--
-- Table structure for table `relationship_types`
--

CREATE TABLE `relationship_types` (
  `relationship_type_id` int(11) NOT NULL,
  `relationship_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `relationship_types`
--

INSERT INTO `relationship_types` (`relationship_type_id`, `relationship_name`) VALUES
(9, 'Aunt'),
(4, 'Brother'),
(1, 'Father'),
(11, 'Friend'),
(6, 'Grandfather'),
(7, 'Grandmother'),
(3, 'Guardian'),
(2, 'Mother'),
(12, 'Other'),
(10, 'Relative'),
(5, 'Sister'),
(8, 'Uncle');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`) VALUES
(3, 'Admin'),
(2, 'Staff'),
(1, 'Student');

-- --------------------------------------------------------

--
-- Table structure for table `siblings`
--

CREATE TABLE `siblings` (
  `sibling_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `sibling_name` varchar(100) NOT NULL,
  `relationship_type_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staff_id` int(11) NOT NULL,
  `staff_name` varchar(100) NOT NULL,
  `register_number` varchar(30) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `gender_id` int(11) NOT NULL,
  `date_of_birth` date NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `designation_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staff_id`, `staff_name`, `register_number`, `email`, `phone`, `gender_id`, `date_of_birth`, `photo`, `department_id`, `designation_id`) VALUES
(18, 'Hyderkma', '249000', 'c@gmail.com', '0000000000', 2, '1111-11-11', 'uploads/staff_photos/1783426187_Screenshot 2026-04-09 212538.png', 4, 8),
(25, 'munjid', '123455', 'mun@gmail.com', '7598581406', 2, '1111-11-11', 'uploads/staff/1783502035_Screenshot 2026-04-08 195745.png', 1, 5),
(26, 'Buhari', '999999', 'ss@gmail.com', '7598581406', 1, '1111-11-11', 'uploads/staff/1783676838_Screenshot 2026-04-09 212125.png', 5, 5),
(27, 'mufeed', '123456', 'M@gmail.com', '9090909090', 1, '1111-11-11', 'uploads/staffs/staff_photos/1783764541_Screenshot 2026-04-09 212538.png', 4, 5);

-- --------------------------------------------------------

--
-- Table structure for table `staff_addresses`
--

CREATE TABLE `staff_addresses` (
  `address_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `permanent_address` text NOT NULL,
  `temporary_address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `staff_addresses`
--

INSERT INTO `staff_addresses` (`address_id`, `staff_id`, `permanent_address`, `temporary_address`) VALUES
(4, 18, 'dscokS', 'dscokS'),
(11, 25, 'tirunelveli', 'tirunelveli'),
(12, 26, 'ggggggggg', 'fffffff'),
(13, 27, 'ggggggggggggg', 'ggggggggggggg');

-- --------------------------------------------------------

--
-- Table structure for table `staff_documents`
--

CREATE TABLE `staff_documents` (
  `staff_document_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `document_type_id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `staff_documents`
--

INSERT INTO `staff_documents` (`staff_document_id`, `staff_id`, `document_type_id`, `file_path`, `uploaded_at`) VALUES
(11, 25, 1, 'uploads/staff/1783502035_Screenshot 2026-04-08 195745.png', '2026-07-08 07:17:40'),
(12, 25, 7, 'uploads/staff/1783502035_Screenshot 2026-04-09 212125.png', '2026-07-08 07:17:40'),
(13, 25, 8, 'uploads/staff_documents/1783495060_Screenshot 2026-04-09 214749.png', '2026-07-08 07:17:40'),
(14, 25, 9, 'uploads/staff_documents/1783495060_Screenshot 2026-04-15 204551.png', '2026-07-08 07:17:40'),
(15, 25, 10, 'uploads/staff_documents/1783495060_Screenshot 2026-04-09 212538.png', '2026-07-08 07:17:40'),
(16, 26, 1, 'uploads/staff/1783676838_Screenshot 2026-04-09 212125.png', '2026-07-09 10:59:26'),
(17, 26, 7, 'uploads/staff_documents/1783594766_Screenshot 2026-04-09 212125.png', '2026-07-09 10:59:26'),
(18, 26, 8, 'uploads/staff_documents/1783594766_Screenshot 2026-04-09 214557.png', '2026-07-09 10:59:26'),
(19, 26, 9, 'uploads/staff_documents/1783594766_Screenshot 2026-04-09 214749.png', '2026-07-09 10:59:26'),
(20, 26, 10, 'uploads/staff_documents/1783594766_Screenshot 2026-04-09 221724.png', '2026-07-09 10:59:26'),
(21, 27, 1, 'uploads/staff_documents/1783764541_Screenshot 2026-04-09 212538.png', '2026-07-11 10:09:01'),
(22, 27, 7, 'uploads/staff_documents/1783764541_Screenshot 2026-04-09 214557.png', '2026-07-11 10:09:01'),
(23, 27, 8, 'uploads/staff_documents/1783764541_Screenshot 2026-04-09 212125.png', '2026-07-11 10:09:01'),
(24, 27, 9, 'uploads/staff_documents/1783764541_Screenshot 2026-04-08 195745.png', '2026-07-11 10:09:01'),
(25, 27, 10, 'uploads/staff_documents/1783764541_Screenshot 2026-04-09 214749.png', '2026-07-11 10:09:01');

-- --------------------------------------------------------

--
-- Table structure for table `staff_emergency_contacts`
--

CREATE TABLE `staff_emergency_contacts` (
  `emergency_contact_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `emergency_name` varchar(100) NOT NULL,
  `relationship_type_id` int(11) NOT NULL,
  `emergency_phone` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `staff_emergency_contacts`
--

INSERT INTO `staff_emergency_contacts` (`emergency_contact_id`, `staff_id`, `emergency_name`, `relationship_type_id`, `emergency_phone`) VALUES
(9, 25, 'suf', 2, '1234321236'),
(10, 26, 'ggggggggg', 1, '1111111111'),
(11, 27, 'ddddd', 1, '2323232323');

-- --------------------------------------------------------

--
-- Table structure for table `staff_qualifications`
--

CREATE TABLE `staff_qualifications` (
  `staff_qualification_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `qualification_id` int(11) NOT NULL,
  `university_name` varchar(150) DEFAULT NULL,
  `specialization` varchar(100) DEFAULT NULL,
  `institution` varchar(150) DEFAULT NULL,
  `year_of_passing` year(4) DEFAULT NULL,
  `percentage` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `staff_qualifications`
--

INSERT INTO `staff_qualifications` (`staff_qualification_id`, `staff_id`, `qualification_id`, `university_name`, `specialization`, `institution`, `year_of_passing`, `percentage`) VALUES
(12, 27, 3, 'EWKJFNIJ', 'dsk;nmfaspifnaew', 'efp9EHFAWNPE', '2004', 90.00);

-- --------------------------------------------------------

--
-- Table structure for table `staff_qualifications_master`
--

CREATE TABLE `staff_qualifications_master` (
  `qualification_id` int(11) NOT NULL,
  `qualification_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `staff_qualifications_master`
--

INSERT INTO `staff_qualifications_master` (`qualification_id`, `qualification_name`) VALUES
(1, 'B.E'),
(3, 'B.Sc'),
(2, 'B.Tech'),
(4, 'M.E'),
(6, 'M.Sc'),
(5, 'M.Tech'),
(8, 'MBA'),
(7, 'MCA'),
(10, 'Other'),
(9, 'Ph.D');

-- --------------------------------------------------------

--
-- Table structure for table `staff_subjects`
--

CREATE TABLE `staff_subjects` (
  `staff_subject_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `staff_subjects`
--

INSERT INTO `staff_subjects` (`staff_subject_id`, `staff_id`, `subject_id`, `class_id`, `assigned_at`) VALUES
(1, 26, 4, 3, '2026-07-11 08:44:02'),
(2, 27, 5, 4, '2026-07-11 10:11:59');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `student_name` varchar(100) NOT NULL,
  `register_number` varchar(30) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `gender_id` int(11) NOT NULL,
  `date_of_birth` date NOT NULL,
  `department_id` int(11) NOT NULL,
  `year_id` int(11) NOT NULL,
  `class_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `program_level_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `student_name`, `register_number`, `email`, `phone`, `gender_id`, `date_of_birth`, `department_id`, `year_id`, `class_id`, `created_at`, `program_level_id`) VALUES
(2, 'hyderali', '1112', 'g@gmail.com', '1234567890', 2, '1111-11-11', 4, 1, 2, '2026-07-06 11:05:04', 3),
(5, 'ahmed', '990000', 'a@gmail.com', '9894928029', 1, '1111-11-11', 4, 2, 4, '2026-07-11 10:16:29', 1);

-- --------------------------------------------------------

--
-- Table structure for table `student_addresses`
--

CREATE TABLE `student_addresses` (
  `address_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `permanent_address` text NOT NULL,
  `temporary_address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `student_addresses`
--

INSERT INTO `student_addresses` (`address_id`, `student_id`, `permanent_address`, `temporary_address`) VALUES
(2, 2, 'am\'fMFE', 'am\'fMFE'),
(5, 5, 'dddddddddd', 'dddddddddd');

-- --------------------------------------------------------

--
-- Table structure for table `student_documents`
--

CREATE TABLE `student_documents` (
  `document_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `document_type_id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `student_documents`
--

INSERT INTO `student_documents` (`document_id`, `student_id`, `document_type_id`, `file_path`, `uploaded_at`) VALUES
(6, 2, 1, 'Screenshot 2026-04-09 214749.png', '2026-07-06 11:05:05'),
(7, 2, 2, 'Screenshot 2026-04-09 212538.png', '2026-07-06 11:05:05'),
(8, 2, 3, 'Screenshot 2026-04-09 214557.png', '2026-07-06 11:05:05'),
(9, 2, 4, 'Screenshot 2026-04-09 214749.png', '2026-07-06 11:05:05'),
(10, 2, 5, 'Screenshot 2026-04-09 212538.png', '2026-07-06 11:05:05'),
(22, 5, 1, 'Screenshot 2026-04-09 214557.png', '2026-07-11 10:16:29'),
(23, 5, 2, 'Screenshot 2026-04-09 212125.png', '2026-07-11 10:16:29'),
(24, 5, 3, 'Screenshot 2026-04-09 212538.png', '2026-07-11 10:16:29'),
(25, 5, 4, 'Screenshot 2026-04-09 214557.png', '2026-07-11 10:16:29'),
(26, 5, 5, 'Screenshot 2026-04-09 214749.png', '2026-07-11 10:16:29');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `subject_id` int(11) NOT NULL,
  `subject_code` varchar(20) NOT NULL,
  `subject_name` varchar(100) NOT NULL,
  `department_id` int(11) NOT NULL,
  `year_id` int(11) NOT NULL,
  `semester` int(11) NOT NULL,
  `credits` int(11) DEFAULT 4,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`subject_id`, `subject_code`, `subject_name`, `department_id`, `year_id`, `semester`, `credits`, `created_at`) VALUES
(1, 'csd2201', 'CCN', 1, 2, 4, 3, '2026-07-11 06:30:47'),
(2, 'csd2202', 'OS', 1, 1, 2, 5, '2026-07-11 06:31:21'),
(4, 'bc2201', 'abd', 5, 1, 2, 3, '2026-07-11 08:43:34'),
(5, 'cse2201', 'oss', 4, 2, 3, 4, '2026-07-11 10:01:38');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `register_number` varchar(30) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role_id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `student_id`, `register_number`, `email`, `password`, `created_at`, `role_id`, `staff_id`) VALUES
(2, 2, '1112', 'g@gmail.com', '$2y$10$lWBHXBfZXQXEQkucYO86seJFQEIjEaH3hfbA.Om1GktMOAwP679Wy', '2026-07-06 11:05:04', 1, NULL),
(8, NULL, 'ADMIN001', 'admin@gmail.com', '$2y$10$bAgkiVcCe9iL.aPpqoHleefy1xcG94Dlo42omxIMJdmdXPHI.bp86', '2026-07-09 10:41:30', 3, 25),
(9, NULL, '999999', 'ss@gmail.com', '$2y$10$RrkhTD3nHSRTq79BYsyVf.ROlCyauizpojUN9ZvtY2X3PVhad/lQq', '2026-07-09 10:59:26', 2, 26),
(11, NULL, '123456', 'M@gmail.com', '$2y$10$QtCXgeP1sOO3rZxt4E5CmeqnXyzADo2o7FvkI6qDYY4lDV3NK1cTy', '2026-07-11 10:09:01', 2, 27),
(12, 5, '990000', 'a@gmail.com', '$2y$10$oDMZBe4jsVkwzac/u25au.h.nUgvtNes4aZuRfz0HT/V79mozDfxa', '2026-07-11 10:16:29', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `years`
--

CREATE TABLE `years` (
  `year_id` int(11) NOT NULL,
  `year_name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `years`
--

INSERT INTO `years` (`year_id`, `year_name`) VALUES
(1, '1st Year'),
(2, '2nd Year'),
(3, '3rd Year'),
(4, '4th Year');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academic_details`
--
ALTER TABLE `academic_details`
  ADD PRIMARY KEY (`academic_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `qualification_id` (`qualification_id`),
  ADD KEY `board_id` (`board_id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`attendance_id`),
  ADD UNIQUE KEY `unique_attendance` (`student_id`,`subject_id`,`attendance_date`);

--
-- Indexes for table `boards`
--
ALTER TABLE `boards`
  ADD PRIMARY KEY (`board_id`),
  ADD UNIQUE KEY `board_name` (`board_name`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`class_id`),
  ADD UNIQUE KEY `unique_class` (`department_id`,`year_id`,`section`),
  ADD KEY `year_id` (`year_id`),
  ADD KEY `class_advisor` (`class_advisor`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`department_id`),
  ADD UNIQUE KEY `department_name` (`department_name`);

--
-- Indexes for table `designations`
--
ALTER TABLE `designations`
  ADD PRIMARY KEY (`designation_id`),
  ADD UNIQUE KEY `designation_name` (`designation_name`);

--
-- Indexes for table `document_types`
--
ALTER TABLE `document_types`
  ADD PRIMARY KEY (`document_type_id`),
  ADD UNIQUE KEY `document_name` (`document_name`);

--
-- Indexes for table `emergency_contacts`
--
ALTER TABLE `emergency_contacts`
  ADD PRIMARY KEY (`emergency_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `relationship_type_id` (`relationship_type_id`);

--
-- Indexes for table `family_details`
--
ALTER TABLE `family_details`
  ADD PRIMARY KEY (`family_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `relationship_type_id` (`relationship_type_id`);

--
-- Indexes for table `genders`
--
ALTER TABLE `genders`
  ADD PRIMARY KEY (`gender_id`),
  ADD UNIQUE KEY `gender_name` (`gender_name`);

--
-- Indexes for table `program_levels`
--
ALTER TABLE `program_levels`
  ADD PRIMARY KEY (`program_level_id`),
  ADD UNIQUE KEY `program_level_name` (`program_level_name`);

--
-- Indexes for table `qualifications`
--
ALTER TABLE `qualifications`
  ADD PRIMARY KEY (`qualification_id`),
  ADD UNIQUE KEY `qualification_name` (`qualification_name`);

--
-- Indexes for table `relationship_types`
--
ALTER TABLE `relationship_types`
  ADD PRIMARY KEY (`relationship_type_id`),
  ADD UNIQUE KEY `relationship_name` (`relationship_name`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Indexes for table `siblings`
--
ALTER TABLE `siblings`
  ADD PRIMARY KEY (`sibling_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `relationship_type_id` (`relationship_type_id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staff_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `register_number` (`register_number`) USING BTREE,
  ADD KEY `department_id` (`department_id`),
  ADD KEY `fk_staff_designation` (`designation_id`),
  ADD KEY `fk_staff_gender` (`gender_id`);

--
-- Indexes for table `staff_addresses`
--
ALTER TABLE `staff_addresses`
  ADD PRIMARY KEY (`address_id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `staff_documents`
--
ALTER TABLE `staff_documents`
  ADD PRIMARY KEY (`staff_document_id`),
  ADD KEY `staff_id` (`staff_id`),
  ADD KEY `document_type_id` (`document_type_id`);

--
-- Indexes for table `staff_emergency_contacts`
--
ALTER TABLE `staff_emergency_contacts`
  ADD PRIMARY KEY (`emergency_contact_id`),
  ADD KEY `staff_id` (`staff_id`),
  ADD KEY `relationship_type_id` (`relationship_type_id`);

--
-- Indexes for table `staff_qualifications`
--
ALTER TABLE `staff_qualifications`
  ADD PRIMARY KEY (`staff_qualification_id`),
  ADD KEY `staff_id` (`staff_id`),
  ADD KEY `fk_staff_qualification_master` (`qualification_id`);

--
-- Indexes for table `staff_qualifications_master`
--
ALTER TABLE `staff_qualifications_master`
  ADD PRIMARY KEY (`qualification_id`),
  ADD UNIQUE KEY `qualification_name` (`qualification_name`);

--
-- Indexes for table `staff_subjects`
--
ALTER TABLE `staff_subjects`
  ADD PRIMARY KEY (`staff_subject_id`),
  ADD UNIQUE KEY `staff_id` (`staff_id`,`subject_id`,`class_id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `register_number` (`register_number`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `gender_id` (`gender_id`),
  ADD KEY `department_id` (`department_id`),
  ADD KEY `year_id` (`year_id`),
  ADD KEY `fk_program_level` (`program_level_id`),
  ADD KEY `fk_students_class` (`class_id`);

--
-- Indexes for table `student_addresses`
--
ALTER TABLE `student_addresses`
  ADD PRIMARY KEY (`address_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `student_documents`
--
ALTER TABLE `student_documents`
  ADD PRIMARY KEY (`document_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `document_type_id` (`document_type_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`subject_id`),
  ADD UNIQUE KEY `subject_code` (`subject_code`),
  ADD KEY `department_id` (`department_id`),
  ADD KEY `year_id` (`year_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `register_number` (`register_number`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `uk_users_register_number` (`register_number`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `fk_users_role` (`role_id`),
  ADD KEY `fk_users_staff` (`staff_id`);

--
-- Indexes for table `years`
--
ALTER TABLE `years`
  ADD PRIMARY KEY (`year_id`),
  ADD UNIQUE KEY `year_name` (`year_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `academic_details`
--
ALTER TABLE `academic_details`
  MODIFY `academic_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `boards`
--
ALTER TABLE `boards`
  MODIFY `board_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `class_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `designations`
--
ALTER TABLE `designations`
  MODIFY `designation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `document_types`
--
ALTER TABLE `document_types`
  MODIFY `document_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `emergency_contacts`
--
ALTER TABLE `emergency_contacts`
  MODIFY `emergency_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `family_details`
--
ALTER TABLE `family_details`
  MODIFY `family_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `genders`
--
ALTER TABLE `genders`
  MODIFY `gender_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `program_levels`
--
ALTER TABLE `program_levels`
  MODIFY `program_level_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `qualifications`
--
ALTER TABLE `qualifications`
  MODIFY `qualification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `relationship_types`
--
ALTER TABLE `relationship_types`
  MODIFY `relationship_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `siblings`
--
ALTER TABLE `siblings`
  MODIFY `sibling_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `staff_addresses`
--
ALTER TABLE `staff_addresses`
  MODIFY `address_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `staff_documents`
--
ALTER TABLE `staff_documents`
  MODIFY `staff_document_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `staff_emergency_contacts`
--
ALTER TABLE `staff_emergency_contacts`
  MODIFY `emergency_contact_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `staff_qualifications`
--
ALTER TABLE `staff_qualifications`
  MODIFY `staff_qualification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `staff_qualifications_master`
--
ALTER TABLE `staff_qualifications_master`
  MODIFY `qualification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `staff_subjects`
--
ALTER TABLE `staff_subjects`
  MODIFY `staff_subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `student_addresses`
--
ALTER TABLE `student_addresses`
  MODIFY `address_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `student_documents`
--
ALTER TABLE `student_documents`
  MODIFY `document_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `years`
--
ALTER TABLE `years`
  MODIFY `year_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `academic_details`
--
ALTER TABLE `academic_details`
  ADD CONSTRAINT `academic_details_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `academic_details_ibfk_2` FOREIGN KEY (`qualification_id`) REFERENCES `qualifications` (`qualification_id`),
  ADD CONSTRAINT `academic_details_ibfk_3` FOREIGN KEY (`board_id`) REFERENCES `boards` (`board_id`);

--
-- Constraints for table `classes`
--
ALTER TABLE `classes`
  ADD CONSTRAINT `classes_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`),
  ADD CONSTRAINT `classes_ibfk_2` FOREIGN KEY (`year_id`) REFERENCES `years` (`year_id`),
  ADD CONSTRAINT `classes_ibfk_3` FOREIGN KEY (`class_advisor`) REFERENCES `staff` (`staff_id`);

--
-- Constraints for table `emergency_contacts`
--
ALTER TABLE `emergency_contacts`
  ADD CONSTRAINT `emergency_contacts_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `emergency_contacts_ibfk_2` FOREIGN KEY (`relationship_type_id`) REFERENCES `relationship_types` (`relationship_type_id`);

--
-- Constraints for table `family_details`
--
ALTER TABLE `family_details`
  ADD CONSTRAINT `family_details_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `family_details_ibfk_2` FOREIGN KEY (`relationship_type_id`) REFERENCES `relationship_types` (`relationship_type_id`);

--
-- Constraints for table `siblings`
--
ALTER TABLE `siblings`
  ADD CONSTRAINT `siblings_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `siblings_ibfk_2` FOREIGN KEY (`relationship_type_id`) REFERENCES `relationship_types` (`relationship_type_id`);

--
-- Constraints for table `staff`
--
ALTER TABLE `staff`
  ADD CONSTRAINT `fk_staff_designation` FOREIGN KEY (`designation_id`) REFERENCES `designations` (`designation_id`),
  ADD CONSTRAINT `fk_staff_gender` FOREIGN KEY (`gender_id`) REFERENCES `genders` (`gender_id`),
  ADD CONSTRAINT `staff_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`);

--
-- Constraints for table `staff_addresses`
--
ALTER TABLE `staff_addresses`
  ADD CONSTRAINT `staff_addresses_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`staff_id`) ON DELETE CASCADE;

--
-- Constraints for table `staff_documents`
--
ALTER TABLE `staff_documents`
  ADD CONSTRAINT `staff_documents_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`staff_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `staff_documents_ibfk_2` FOREIGN KEY (`document_type_id`) REFERENCES `document_types` (`document_type_id`);

--
-- Constraints for table `staff_emergency_contacts`
--
ALTER TABLE `staff_emergency_contacts`
  ADD CONSTRAINT `staff_emergency_contacts_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`staff_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `staff_emergency_contacts_ibfk_2` FOREIGN KEY (`relationship_type_id`) REFERENCES `relationship_types` (`relationship_type_id`);

--
-- Constraints for table `staff_qualifications`
--
ALTER TABLE `staff_qualifications`
  ADD CONSTRAINT `fk_staff_qualification_master` FOREIGN KEY (`qualification_id`) REFERENCES `staff_qualifications_master` (`qualification_id`),
  ADD CONSTRAINT `staff_qualifications_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`staff_id`) ON DELETE CASCADE;

--
-- Constraints for table `staff_subjects`
--
ALTER TABLE `staff_subjects`
  ADD CONSTRAINT `staff_subjects_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`staff_id`),
  ADD CONSTRAINT `staff_subjects_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`),
  ADD CONSTRAINT `staff_subjects_ibfk_3` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`);

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `fk_program_level` FOREIGN KEY (`program_level_id`) REFERENCES `program_levels` (`program_level_id`),
  ADD CONSTRAINT `fk_students_class` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`),
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`gender_id`) REFERENCES `genders` (`gender_id`),
  ADD CONSTRAINT `students_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`),
  ADD CONSTRAINT `students_ibfk_3` FOREIGN KEY (`year_id`) REFERENCES `years` (`year_id`);

--
-- Constraints for table `student_addresses`
--
ALTER TABLE `student_addresses`
  ADD CONSTRAINT `student_addresses_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE;

--
-- Constraints for table `student_documents`
--
ALTER TABLE `student_documents`
  ADD CONSTRAINT `student_documents_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_documents_ibfk_2` FOREIGN KEY (`document_type_id`) REFERENCES `document_types` (`document_type_id`);

--
-- Constraints for table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `subjects_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`),
  ADD CONSTRAINT `subjects_ibfk_2` FOREIGN KEY (`year_id`) REFERENCES `years` (`year_id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`),
  ADD CONSTRAINT `fk_users_staff` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`staff_id`),
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
