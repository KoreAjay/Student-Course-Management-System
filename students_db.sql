-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 16, 2025 at 11:43 AM
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
-- Database: `students_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `course_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `course_name`, `description`, `created_at`) VALUES
(1, 'Introduction to Computer Science', 'Basics of computer science, logic, and programming.', '2025-06-16 08:47:03'),
(2, 'Data Structures and Algorithms', 'In-depth study of data organization and algorithms.', '2025-06-16 08:47:03'),
(3, 'Web Development', 'Frontend and backend web development with HTML, CSS, JavaScript, PHP.', '2025-06-16 08:47:03'),
(4, 'Database Management Systems', 'Introduction to relational databases and SQL.', '2025-06-16 08:47:03'),
(5, 'Operating Systems', 'Concepts and design of operating systems.', '2025-06-16 08:47:03'),
(6, 'Computer Networks', 'Fundamentals of data communication and networking.', '2025-06-16 08:47:03'),
(7, 'Object-Oriented Programming', 'OOP concepts with languages like Java or C++.', '2025-06-16 08:47:03'),
(8, 'Python Programming', 'Introductory and intermediate Python skills.', '2025-06-16 08:47:03'),
(9, 'Machine Learning', 'ML models, data preprocessing, training and evaluation.', '2025-06-16 08:47:03'),
(10, 'Mobile App Development', 'Building apps with Flutter, Android, or iOS.', '2025-06-16 08:47:03'),
(11, 'Cloud Computing', 'AWS, Azure, and principles of cloud architecture.', '2025-06-16 08:47:03'),
(12, 'Cybersecurity Basics', 'Introduction to ethical hacking, security protocols.', '2025-06-16 08:47:03'),
(13, 'Artificial Intelligence', 'AI techniques and problem-solving.', '2025-06-16 08:47:03'),
(14, 'Software Engineering', 'Software development lifecycle and methodologies.', '2025-06-16 08:47:03'),
(16, 'UI/UX Design', 'User interface and user experience principles and tools.', '2025-06-16 08:47:03'),
(17, 'Artificial Intelligence', 'Artificial Intelligence', '2025-06-16 08:49:57');

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `course_name` varchar(100) NOT NULL,
  `enroll_date` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollments`
--

INSERT INTO `enrollments` (`id`, `email`, `course_name`, `enroll_date`) VALUES
(2, 'ajay@gmail.com', 'Data Structures and Algorithms', '2025-06-16'),
(3, 'ajay@gmail.com', 'Web Development', '2025-06-16'),
(4, 'rahul@gmail.com', 'Web Development', '2025-06-16'),
(6, 'rahul0@gmail.com', 'Object-Oriented Programming', '2025-06-16');

-- --------------------------------------------------------

--
-- Table structure for table `registration`
--

CREATE TABLE `registration` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `roll_no` varchar(50) DEFAULT NULL,
  `course` varchar(100) DEFAULT NULL,
  `yearSemister` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `registered_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registration`
--

INSERT INTO `registration` (`id`, `fullname`, `email`, `roll_no`, `course`, `yearSemister`, `password`, `registered_at`) VALUES
(1, 'Ajay', 'ajay@gmail.com', '101', 'MCA', '2nd Year', 'Ajay@123', '2025-06-16 08:44:26'),
(2, 'Rahul', 'rahul@gmail.com', '102', 'MCA', '2nd Year', 'Rahul@123', '2025-06-16 09:03:58');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'Admin', 'admin@example.com', 'admin123', '2025-06-16 08:48:41');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `registration`
--
ALTER TABLE `registration`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `registration`
--
ALTER TABLE `registration`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
