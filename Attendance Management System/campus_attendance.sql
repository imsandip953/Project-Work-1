-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 10, 2026 at 06:07 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `campus_attendance`
--

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `course_name` varchar(100) NOT NULL,
  `course_code` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `course_name`, `course_code`) VALUES
(1, 'Advanced Potions', 'POT401'),
(2, 'Defense Against Dark Arts', 'DADA202'),
(3, 'Transfiguration', 'DADA201'),
(4, 'Potions Core I', 'POT101'),
(5, 'Defense Against the Dark Arts', 'DADA301'),
(6, 'Advanced Transfiguration', 'TRNS402'),
(7, 'Charms Mastery', 'CHRM201'),
(8, 'Herbology and Botany', 'HERB105'),
(9, 'Care of Magical Creatures', 'CMC204'),
(10, 'Data Structures & Algorithms', 'CSE211'),
(11, 'Object Oriented Programming', 'CSE213'),
(12, 'Automata Theory & Formal Languages', 'CSE311'),
(13, 'Software Engineering & Information Systems', 'CSE313'),
(14, 'Compiler Design & Construction', 'CSE321'),
(15, 'Computer Networks & Security Systems', 'CSE323');

-- --------------------------------------------------------

--
-- Table structure for table `daily_attendance`
--

CREATE TABLE `daily_attendance` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `status` enum('Present','Absent') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `daily_attendance`
--

INSERT INTO `daily_attendance` (`id`, `student_id`, `course_id`, `date`, `status`) VALUES
(14, 101, 1, '2026-06-24', 'Present'),
(15, 102, 1, '2026-06-24', 'Present'),
(16, 104, 1, '2026-06-24', 'Absent'),
(17, 103, 1, '2026-06-24', 'Present'),
(18, 101, 2, '2026-06-24', 'Absent'),
(19, 102, 2, '2026-06-24', 'Present'),
(20, 104, 2, '2026-06-24', 'Present'),
(21, 103, 2, '2026-06-24', 'Absent'),
(22, 101, 1, '2026-06-25', 'Present'),
(23, 102, 1, '2026-06-25', 'Present'),
(24, 104, 1, '2026-06-25', 'Absent'),
(25, 103, 1, '2026-06-25', 'Present'),
(30, 101, 7, '2026-06-25', 'Absent'),
(31, 102, 7, '2026-06-25', 'Present'),
(32, 104, 7, '2026-06-25', 'Present'),
(33, 103, 7, '2026-06-25', 'Present'),
(34, 101, 7, '2026-06-26', 'Absent'),
(35, 102, 7, '2026-06-26', 'Present'),
(36, 104, 7, '2026-06-26', 'Absent'),
(37, 103, 7, '2026-06-26', 'Present'),
(38, 101, 7, '2026-06-27', 'Present'),
(39, 102, 7, '2026-06-27', 'Absent'),
(40, 104, 7, '2026-06-27', 'Present'),
(41, 103, 7, '2026-06-27', 'Present'),
(42, 108, 12, '2026-06-23', 'Present'),
(43, 108, 12, '2026-06-24', 'Present'),
(44, 108, 12, '2026-06-25', 'Absent'),
(45, 108, 13, '2026-06-25', 'Absent'),
(46, 109, 14, '2026-06-25', 'Present'),
(47, 109, 15, '2026-06-25', 'Present'),
(49, 113, 1, '2026-06-26', 'Present'),
(50, 114, 1, '2026-06-26', 'Present'),
(51, 110, 1, '2026-06-26', 'Absent'),
(52, 112, 1, '2026-06-26', 'Present'),
(53, 101, 1, '2026-06-26', 'Present'),
(54, 102, 1, '2026-06-26', 'Present'),
(55, 111, 1, '2026-06-26', 'Absent'),
(56, 104, 1, '2026-06-26', 'Present'),
(57, 115, 1, '2026-06-26', 'Absent'),
(58, 103, 1, '2026-06-26', 'Present'),
(59, 113, 1, '2026-06-27', 'Present'),
(60, 114, 1, '2026-06-27', 'Present'),
(61, 110, 1, '2026-06-27', 'Present'),
(62, 112, 1, '2026-06-27', 'Present'),
(63, 101, 1, '2026-06-27', 'Present'),
(64, 102, 1, '2026-06-27', 'Present'),
(65, 111, 1, '2026-06-27', 'Present'),
(66, 104, 1, '2026-06-27', 'Absent'),
(67, 115, 1, '2026-06-27', 'Present'),
(68, 103, 1, '2026-06-27', 'Present'),
(69, 113, 1, '2026-06-28', 'Absent'),
(70, 114, 1, '2026-06-28', 'Present'),
(71, 110, 1, '2026-06-28', 'Absent'),
(72, 112, 1, '2026-06-28', 'Present'),
(73, 101, 1, '2026-06-28', 'Absent'),
(74, 102, 1, '2026-06-28', 'Absent'),
(75, 111, 1, '2026-06-28', 'Present'),
(76, 104, 1, '2026-06-28', 'Present'),
(77, 115, 1, '2026-06-28', 'Absent'),
(78, 103, 1, '2026-06-28', 'Present'),
(79, 132, 13, '2026-06-25', 'Present'),
(80, 126, 13, '2026-06-25', 'Absent'),
(81, 133, 13, '2026-06-25', 'Present'),
(82, 134, 13, '2026-06-25', 'Present'),
(83, 127, 13, '2026-06-25', 'Absent'),
(84, 130, 13, '2026-06-25', 'Present'),
(85, 129, 13, '2026-06-25', 'Absent'),
(86, 128, 13, '2026-06-25', 'Present'),
(87, 125, 13, '2026-06-25', 'Present'),
(89, 131, 13, '2026-06-25', 'Present'),
(90, 132, 13, '2026-06-26', 'Present'),
(91, 126, 13, '2026-06-26', 'Present'),
(92, 133, 13, '2026-06-26', 'Absent'),
(93, 134, 13, '2026-06-26', 'Present'),
(94, 127, 13, '2026-06-26', 'Absent'),
(95, 130, 13, '2026-06-26', 'Present'),
(96, 129, 13, '2026-06-26', 'Present'),
(97, 128, 13, '2026-06-26', 'Absent'),
(98, 125, 13, '2026-06-26', 'Absent'),
(99, 108, 13, '2026-06-26', 'Present'),
(100, 131, 13, '2026-06-26', 'Present'),
(101, 132, 13, '2026-06-27', 'Absent'),
(102, 126, 13, '2026-06-27', 'Present'),
(103, 133, 13, '2026-06-27', 'Present'),
(104, 134, 13, '2026-06-27', 'Present'),
(105, 127, 13, '2026-06-27', 'Present'),
(106, 130, 13, '2026-06-27', 'Absent'),
(107, 129, 13, '2026-06-27', 'Absent'),
(108, 128, 13, '2026-06-27', 'Present'),
(109, 125, 13, '2026-06-27', 'Present'),
(110, 108, 13, '2026-06-27', 'Absent'),
(111, 131, 13, '2026-06-27', 'Present'),
(112, 132, 13, '2026-06-28', 'Absent'),
(113, 126, 13, '2026-06-28', 'Present'),
(114, 133, 13, '2026-06-28', 'Absent'),
(115, 134, 13, '2026-06-28', 'Absent'),
(116, 127, 13, '2026-06-28', 'Present'),
(117, 130, 13, '2026-06-28', 'Present'),
(118, 129, 13, '2026-06-28', 'Present'),
(119, 128, 13, '2026-06-28', 'Present'),
(120, 125, 13, '2026-06-28', 'Present'),
(121, 108, 13, '2026-06-28', 'Absent'),
(122, 131, 13, '2026-06-28', 'Present'),
(123, 113, 4, '2026-06-29', 'Present'),
(124, 114, 4, '2026-06-29', 'Present'),
(125, 110, 4, '2026-06-29', 'Absent'),
(126, 112, 4, '2026-06-29', 'Present'),
(127, 101, 4, '2026-06-29', 'Present'),
(128, 102, 4, '2026-06-29', 'Absent'),
(129, 111, 4, '2026-06-29', 'Present'),
(130, 104, 4, '2026-06-29', 'Present'),
(131, 115, 4, '2026-06-29', 'Present'),
(132, 103, 4, '2026-06-29', 'Absent'),
(133, 108, 13, '2026-06-30', 'Present'),
(134, 125, 13, '2026-06-30', 'Present'),
(135, 126, 13, '2026-06-30', 'Present'),
(136, 127, 13, '2026-06-30', 'Present'),
(137, 128, 13, '2026-06-30', 'Present'),
(138, 129, 13, '2026-06-30', 'Present'),
(139, 130, 13, '2026-06-30', 'Present'),
(140, 131, 13, '2026-06-30', 'Present'),
(141, 132, 13, '2026-06-30', 'Present'),
(142, 133, 13, '2026-06-30', 'Absent'),
(143, 134, 13, '2026-06-30', 'Absent'),
(144, 102, 4, '2026-06-30', 'Present'),
(145, 103, 4, '2026-06-30', 'Present'),
(146, 101, 4, '2026-06-30', 'Absent'),
(147, 104, 4, '2026-06-30', 'Present'),
(148, 110, 4, '2026-06-30', 'Present'),
(149, 111, 4, '2026-06-30', 'Absent'),
(150, 112, 4, '2026-06-30', 'Present'),
(151, 113, 4, '2026-06-30', 'Absent'),
(152, 114, 4, '2026-06-30', 'Present'),
(153, 115, 4, '2026-06-30', 'Present'),
(154, 102, 4, '2026-06-28', 'Present'),
(155, 103, 4, '2026-06-28', 'Absent'),
(156, 101, 4, '2026-06-28', 'Present'),
(157, 104, 4, '2026-06-28', 'Absent'),
(158, 110, 4, '2026-06-28', 'Absent'),
(159, 111, 4, '2026-06-28', 'Absent'),
(160, 112, 4, '2026-06-28', 'Present'),
(161, 113, 4, '2026-06-28', 'Present'),
(162, 114, 4, '2026-06-28', 'Present'),
(163, 115, 4, '2026-06-28', 'Present'),
(164, 102, 1, '2026-08-10', 'Present'),
(165, 103, 1, '2026-08-10', 'Present'),
(166, 101, 1, '2026-08-10', 'Absent'),
(167, 104, 1, '2026-08-10', 'Present'),
(168, 110, 1, '2026-08-10', 'Present'),
(169, 111, 1, '2026-08-10', 'Present'),
(170, 112, 1, '2026-08-10', 'Present'),
(171, 113, 1, '2026-08-10', 'Absent'),
(172, 114, 1, '2026-08-10', 'Absent'),
(173, 115, 1, '2026-08-10', 'Absent');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `roll_number` varchar(50) NOT NULL,
  `department` varchar(100) NOT NULL,
  `semester` varchar(50) NOT NULL,
  `session_name` varchar(50) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `user_id`, `roll_number`, `department`, `semester`, `session_name`, `phone_number`) VALUES
(3, 101, '07', 'Department of Defense Against the Dark Arts', '1st', 'Fall 2026', '+447700900077'),
(4, 102, '01', 'Department of Transfiguration', '1st', 'Fall 2026', '+447700900088'),
(5, 103, '02', 'Department of Charms', '1st', 'Fall 2026', '+447700900099'),
(6, 104, '10', 'Department of Herbology', '1st', 'Fall 2026', '+447700900111'),
(7, 105, '241002011', 'Computer Science & Engineering', '3rd', 'Spring 2026', '01911223344'),
(9, 108, '501', 'Department of Arithmancy', '5th', 'Summer 2026', '+447700900222'),
(10, 109, '601', 'Department of Alchemy', '6th', 'Autumn 2026', '+447700900333'),
(11, 110, '11', 'Slytherin House', '1st', 'Fall 2026', '+447700900112'),
(12, 111, '12', 'Ravenclaw House', '1st', 'Fall 2026', '+447700900113'),
(13, 112, '13', 'Gryffindor House', '1st', 'Fall 2026', '+447700900114'),
(14, 113, '14', 'Hufflepuff House', '1st', 'Fall 2026', '+447700900115'),
(15, 114, '15', 'Ravenclaw House', '1st', 'Fall 2026', '+447700900116'),
(16, 115, '16', 'Slytherin House', '1st', 'Fall 2026', '+447700900117'),
(17, 116, '241002012', 'Computer Science & Engineering', '3rd', 'Spring 2026', '01911223345'),
(18, 117, '241002013', 'Computer Science & Engineering', '3rd', 'Spring 2026', '01911223346'),
(19, 118, '241002014', 'Computer Science & Engineering', '3rd', 'Spring 2026', '01911223347'),
(20, 119, '241002015', 'Computer Science & Engineering', '3rd', 'Spring 2026', '01911223348'),
(21, 120, '241002016', 'Computer Science & Engineering', '3rd', 'Spring 2026', '01911223349'),
(22, 121, '241002017', 'Computer Science & Engineering', '3rd', 'Spring 2026', '01911223350'),
(23, 122, '241002018', 'Computer Science & Engineering', '3rd', 'Spring 2026', '01911223351'),
(24, 123, '241002019', 'Computer Science & Engineering', '3rd', 'Spring 2026', '01911223352'),
(25, 124, '241002020', 'Computer Science & Engineering', '3rd', 'Spring 2026', '01911223353'),
(26, 125, '231002001', 'Computer Science & Engineering', '5th', 'Summer 2026', '01711112221'),
(27, 126, '231002002', 'Computer Science & Engineering', '5th', 'Summer 2026', '01711112222'),
(28, 127, '231002003', 'Computer Science & Engineering', '5th', 'Summer 2026', '01711112223'),
(29, 128, '231002004', 'Computer Science & Engineering', '5th', 'Summer 2026', '01711112224'),
(30, 129, '231002005', 'Computer Science & Engineering', '5th', 'Summer 2026', '01711112225'),
(31, 130, '231002006', 'Computer Science & Engineering', '5th', 'Summer 2026', '01711112226'),
(32, 131, '231002007', 'Computer Science & Engineering', '5th', 'Summer 2026', '01711112227'),
(33, 132, '231002008', 'Computer Science & Engineering', '5th', 'Summer 2026', '01711112228'),
(34, 133, '231002009', 'Computer Science & Engineering', '5th', 'Summer 2026', '01711112229'),
(35, 134, '231002010', 'Computer Science & Engineering', '5th', 'Summer 2026', '01711112230'),
(36, 135, '231002081', 'Computer Science & Engineering', '6th', 'Autumn 2026', '01555667701'),
(37, 136, '231002082', 'Computer Science & Engineering', '6th', 'Autumn 2026', '01555667702'),
(38, 137, '231002083', 'Computer Science & Engineering', '6th', 'Autumn 2026', '01555667703'),
(39, 138, '231002084', 'Computer Science & Engineering', '6th', 'Autumn 2026', '01555667704'),
(40, 139, '231002085', 'Computer Science & Engineering', '6th', 'Autumn 2026', '01555667705'),
(41, 140, '231002086', 'Computer Science & Engineering', '6th', 'Autumn 2026', '01555667706'),
(42, 141, '231002087', 'Computer Science & Engineering', '6th', 'Autumn 2026', '01555667707'),
(43, 142, '231002088', 'Computer Science & Engineering', '6th', 'Autumn 2026', '01555667708'),
(44, 143, '231002089', 'Computer Science & Engineering', '6th', 'Autumn 2026', '01555667709'),
(45, 144, '231002090', 'Computer Science & Engineering', '6th', 'Autumn 2026', '01555667710'),
(46, 145, '101', 'ELE', '2nd', 'Spring 2026', '+49 151 5553281');

-- --------------------------------------------------------

--
-- Table structure for table `teacher_assignments`
--

CREATE TABLE `teacher_assignments` (
  `id` int(11) NOT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `semester` varchar(50) NOT NULL,
  `session_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teacher_assignments`
--

INSERT INTO `teacher_assignments` (`id`, `teacher_id`, `course_id`, `semester`, `session_name`) VALUES
(15, 2, 1, '1st', 'Fall 2026'),
(16, 2, 4, '1st', 'Fall 2026'),
(17, 6, 3, '1st', 'Fall 2026'),
(18, 6, 6, '1st', 'Fall 2026'),
(19, 7, 7, '1st', 'Fall 2026'),
(20, 7, 10, '3rd', 'Spring 2026'),
(21, 8, 8, '1st', 'Fall 2026'),
(22, 8, 11, '3rd', 'Spring 2026'),
(23, 9, 2, '1st', 'Fall 2026'),
(24, 9, 5, '1st', 'Fall 2026'),
(25, 10, 9, '1st', 'Fall 2026'),
(26, 10, 12, '5th', 'Summer 2026'),
(27, 2, 13, '5th', 'Summer 2026'),
(28, 6, 14, '6th', 'Autumn 2026'),
(29, 7, 15, '6th', 'Autumn 2026');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('main_admin','teacher','student') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`) VALUES
(1, 'System Director', 'admin@system.com', '1234', 'main_admin'),
(2, 'Professor Snape', 'teacher@system.com', '1234', 'teacher'),
(4, 'Sandip Paul', 'sandip@system.com', '1234', 'main_admin'),
(6, 'Minerva McGonagall', 'mcgonagall@hogwarts.edu', '1234', 'teacher'),
(7, 'Filius Flitwick', 'flitwick@hogwarts.edu', '1234', 'teacher'),
(8, 'Pomona Sprout', 'sprout@hogwarts.edu', '1234', 'teacher'),
(9, 'Remus Lupin', 'lupin@hogwarts.edu', '1234', 'teacher'),
(10, 'Rubeus Hagrid', 'hagrid@hogwarts.edu', '1234', 'teacher'),
(101, 'Harry Potter', 'harry@hogwarts.edu', '1234', 'student'),
(102, 'Hermione Granger', 'hermione@hogwarts.edu', '1234', 'student'),
(103, 'Ron Weasley', 'ron@hogwarts.edu', '1234', 'student'),
(104, 'Neville Longbottom', 'neville@hogwarts.edu', '1234', 'student'),
(105, 'Sourav Das', 'sourav@campus.edu', '$2y$10$O8Kx6UAnZz1F9n7UfM2FpOn6HkWX8p86N2qg4QG9mFzFhV8xZ4OaG', 'student'),
(108, 'Tahmid Rahman', 'tahmid@hogwarts.edu', '1234', 'student'),
(109, 'Anika Tabassum', 'anika@hogwarts.edu', '1234', 'student'),
(110, 'Draco Malfoy', 'draco@hogwarts.edu', '1234', 'student'),
(111, 'Luna Lovegood', 'luna@hogwarts.edu', '1234', 'student'),
(112, 'Ginny Weasley', 'ginny@hogwarts.edu', '1234', 'student'),
(113, 'Cedric Diggory', 'cedric@hogwarts.edu', '1234', 'student'),
(114, 'Cho Chang', 'cho@hogwarts.edu', '1234', 'student'),
(115, 'Pansy Parkinson', 'pansy@hogwarts.edu', '1234', 'student'),
(116, 'Fred Weasley', 'fred@campus.edu', '1234', 'student'),
(117, 'George Weasley', 'george@campus.edu', '1234', 'student'),
(118, 'Lee Jordan', 'lee@campus.edu', '1234', 'student'),
(119, 'Angelina Johnson', 'angelina@campus.edu', '1234', 'student'),
(120, 'Alicia Spinnet', 'alicia@campus.edu', '1234', 'student'),
(121, 'Katie Bell', 'katie@campus.edu', '1234', 'student'),
(122, 'Oliver Wood', 'oliver@campus.edu', '1234', 'student'),
(123, 'Percy Weasley', 'percy@campus.edu', '1234', 'student'),
(124, 'Penelope Clear', 'penelope@campus.edu', '1234', 'student'),
(125, 'Seamus Finnigan', 'seamus@campus.edu', '1234', 'student'),
(126, 'Dean Thomas', 'dean@campus.edu', '1234', 'student'),
(127, 'Lavender Brown', 'lavender@campus.edu', '1234', 'student'),
(128, 'Parvati Patil', 'parvati@campus.edu', '1234', 'student'),
(129, 'Padma Patil', 'padma@campus.edu', '1234', 'student'),
(130, 'Michael Corner', 'michael@campus.edu', '1234', 'student'),
(131, 'Terry Boot', 'terry@campus.edu', '1234', 'student'),
(132, 'Anthony Goldstein', 'anthony@campus.edu', '1234', 'student'),
(133, 'Ernie Macmillan', 'ernie@campus.edu', '1234', 'student'),
(134, 'Hannah Abbott', 'hannah@campus.edu', '1234', 'student'),
(135, 'Justin Finch', 'justin@campus.edu', '1234', 'student'),
(136, 'Susan Bones', 'susan@campus.edu', '1234', 'student'),
(137, 'Zacharias Smith', 'zacharias@campus.edu', '1234', 'student'),
(138, 'Colin Creevey', 'colin@campus.edu', '1234', 'student'),
(139, 'Dennis Creevey', 'dennis@campus.edu', '1234', 'student'),
(140, 'Blaise Zabini', 'blaise@campus.edu', '1234', 'student'),
(141, 'Theodore Nott', 'theodore@campus.edu', '1234', 'student'),
(142, 'Millicent Bulstrode', 'millicent@campus.edu', '1234', 'student'),
(143, 'Marcus Flint', 'marcus@campus.edu', '1234', 'student'),
(144, 'Katie Zhang', 'katie.z@campus.edu', '1234', 'student'),
(145, 'Rio Rio', 'rio@hshl.de', '$2y$10$yTvy3EcVfkv1t.KYUXqB7uQw0BnAqfUemWK5YiDSiIfxYFLHghSIq', 'student');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `course_code` (`course_code`);

--
-- Indexes for table `daily_attendance`
--
ALTER TABLE `daily_attendance`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_entry` (`student_id`,`course_id`,`date`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD UNIQUE KEY `roll_number` (`roll_number`);

--
-- Indexes for table `teacher_assignments`
--
ALTER TABLE `teacher_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `course_id` (`course_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `daily_attendance`
--
ALTER TABLE `daily_attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=174;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `teacher_assignments`
--
ALTER TABLE `teacher_assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=146;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `daily_attendance`
--
ALTER TABLE `daily_attendance`
  ADD CONSTRAINT `daily_attendance_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `daily_attendance_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `teacher_assignments`
--
ALTER TABLE `teacher_assignments`
  ADD CONSTRAINT `teacher_assignments_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `teacher_assignments_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
