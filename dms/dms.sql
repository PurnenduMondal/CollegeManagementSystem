-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 13, 2021 at 11:18 AM
-- Server version: 8.0.21
-- PHP Version: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dms`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `password`) VALUES
(1, 'admin1', 'admin1', '1'),
(2, 'Admin2', 'Admin2', '2');

-- --------------------------------------------------------

--
-- Table structure for table `assignment`
--

DROP TABLE IF EXISTS `assignment`;
CREATE TABLE IF NOT EXISTS `assignment` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sessionId` int NOT NULL,
  `teacherId` int NOT NULL,
  `subjectId` int NOT NULL,
  `questions` varchar(100) NOT NULL,
  `totalMarks` int NOT NULL,
  `assignmentDate` date NOT NULL,
  `assignmentDeadline` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `assignment`
--

INSERT INTO `assignment` (`id`, `sessionId`, `teacherId`, `subjectId`, `questions`, `totalMarks`, `assignmentDate`, `assignmentDeadline`) VALUES
(1, 1, 1, 1, 'assignment1.pdf', 30, '2021-11-01', '2021-11-04');

-- --------------------------------------------------------

--
-- Table structure for table `assignmentresult`
--

DROP TABLE IF EXISTS `assignmentresult`;
CREATE TABLE IF NOT EXISTS `assignmentresult` (
  `studentId` int NOT NULL AUTO_INCREMENT,
  `sessionId` int NOT NULL,
  `teacherId` int NOT NULL,
  `subjectId` int NOT NULL,
  `answers` varchar(100) NOT NULL,
  `marks` int NOT NULL,
  PRIMARY KEY (`studentId`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `assignmentresult`
--

INSERT INTO `assignmentresult` (`studentId`, `sessionId`, `teacherId`, `subjectId`, `answers`, `marks`) VALUES
(2, 1, 1, 1, 'answers2.pdf', 24),
(1, 1, 1, 1, 'answers1.pdf', 16);

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

DROP TABLE IF EXISTS `department`;
CREATE TABLE IF NOT EXISTS `department` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `HOD` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`id`, `name`, `HOD`) VALUES
(1, 'MCA', 'PKD'),
(2, 'BTECH-IT', 'CKD');

-- --------------------------------------------------------

--
-- Table structure for table `fees`
--

DROP TABLE IF EXISTS `fees`;
CREATE TABLE IF NOT EXISTS `fees` (
  `feesTableId` int NOT NULL AUTO_INCREMENT,
  `sessionId` int NOT NULL,
  `semesterNo` int NOT NULL,
  `studentId` int NOT NULL,
  `hostelAssigned` tinyint NOT NULL,
  `isPaid` tinyint NOT NULL,
  `payableAmount` int NOT NULL,
  `tuition_discount` int NOT NULL,
  `hostel_discount` int NOT NULL,
  `others_discount` int NOT NULL,
  PRIMARY KEY (`feesTableId`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `fees`
--

INSERT INTO `fees` (`feesTableId`, `sessionId`, `semesterNo`, `studentId`, `hostelAssigned`, `isPaid`, `payableAmount`, `tuition_discount`, `hostel_discount`, `others_discount`) VALUES
(1, 1, 1, 1, 0, 1, 0, 25, 10, 0),
(2, 1, 2, 1, 0, 1, 0, 25, 0, 0),
(3, 1, 3, 1, 1, 1, 0, 25, 0, 0),
(4, 1, 4, 1, 0, 1, 0, 25, 0, 0),
(5, 1, 1, 2, 0, 1, 0, 0, 0, 0),
(6, 1, 2, 2, 0, 1, 0, 0, 0, 0),
(7, 1, 3, 2, 1, 1, 0, 0, 0, 0),
(8, 1, 4, 2, 0, 1, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `result`
--

DROP TABLE IF EXISTS `result`;
CREATE TABLE IF NOT EXISTS `result` (
  `marksTableId` int NOT NULL AUTO_INCREMENT,
  `sessionId` int NOT NULL,
  `studentId` int NOT NULL,
  `subjectId` int NOT NULL,
  `theory` int NOT NULL,
  `practical` int NOT NULL,
  PRIMARY KEY (`marksTableId`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `result`
--

INSERT INTO `result` (`marksTableId`, `sessionId`, `studentId`, `subjectId`, `theory`, `practical`) VALUES
(1, 1, 1, 1, 80, 25),
(2, 1, 1, 2, 60, 15),
(3, 1, 1, 3, 46, 28),
(4, 1, 1, 4, 65, 24),
(5, 1, 1, 5, 0, 0),
(6, 1, 1, 6, 0, 0),
(7, 1, 1, 7, 0, 0),
(8, 1, 1, 8, 0, 0),
(9, 1, 2, 1, 60, 25),
(10, 1, 2, 2, 66, 19),
(11, 1, 2, 3, 49, 23),
(12, 1, 2, 4, 67, 26),
(13, 1, 2, 5, 0, 0),
(14, 1, 2, 6, 0, 0),
(15, 1, 2, 7, 0, 0),
(16, 1, 2, 8, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `semester`
--

DROP TABLE IF EXISTS `semester`;
CREATE TABLE IF NOT EXISTS `semester` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sessionId` int NOT NULL,
  `semesterNo` tinyint(1) NOT NULL,
  `syllabus` varchar(100) NOT NULL,
  `examDate` date NOT NULL,
  `tuitionFees` int NOT NULL,
  `hostelFees` int NOT NULL,
  `otherFees` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `semester`
--

INSERT INTO `semester` (`id`, `sessionId`, `semesterNo`, `syllabus`, `examDate`, `tuitionFees`, `hostelFees`, `otherFees`) VALUES
(1, 1, 1, 'sem1_mca_2020.pdf', '2020-06-01', 58240, 15000, 0),
(2, 1, 2, 'sem2_mca_2020.pdf', '0000-00-00', 34266, 15000, 0),
(3, 1, 3, 'sem3_mca_2020.pdf', '0000-00-00', 34266, 15000, 0),
(4, 1, 4, 'sem4_mca_2020.pdf', '0000-00-00', 34266, 15000, 0),
(5, 2, 1, '', '2020-06-01', 58240, 15000, 0),
(6, 2, 2, '', '0000-00-00', 34266, 15000, 0),
(7, 2, 3, '', '0000-00-00', 34266, 15000, 0),
(8, 2, 4, '', '0000-00-00', 34266, 15000, 0),
(9, 2, 5, '', '0000-00-00', 0, 0, 0),
(10, 2, 6, '', '0000-00-00', 0, 0, 0),
(11, 2, 7, '', '0000-00-00', 0, 0, 0),
(12, 2, 8, '', '0000-00-00', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `session`
--

DROP TABLE IF EXISTS `session`;
CREATE TABLE IF NOT EXISTS `session` (
  `id` int NOT NULL AUTO_INCREMENT,
  `dept_name` varchar(50) NOT NULL,
  `year` int NOT NULL,
  `semesterCount` int NOT NULL,
  `currentSemester` int NOT NULL,
  `startingDate` date NOT NULL,
  `endingDate` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `session`
--

INSERT INTO `session` (`id`, `dept_name`, `year`, `semesterCount`, `currentSemester`, `startingDate`, `endingDate`) VALUES
(1, 'MCA', 2020, 4, 3, '2020-07-03', '2022-07-12'),
(2, 'BTECH(IT)', 2021, 8, 2, '2021-06-07', '2025-06-09'),
(3, 'MCA', 2017, 6, 0, '2017-05-03', '2020-06-12');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

DROP TABLE IF EXISTS `student`;
CREATE TABLE IF NOT EXISTS `student` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `courseName` varchar(50) NOT NULL,
  `batch` int NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`id`, `name`, `courseName`, `batch`, `email`, `password`) VALUES
(1, 'student1', 'MCA', 2020, 'student1', 1),
(2, 'student2', 'MCA', 2020, 'student2', 2),
(3, 'Student3', 'MCA', 2021, 'Student3', 3);

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

DROP TABLE IF EXISTS `subject`;
CREATE TABLE IF NOT EXISTS `subject` (
  `id` int NOT NULL AUTO_INCREMENT,
  `subject` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subject` (`subject`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`id`, `subject`) VALUES
(1, 'Subject1'),
(2, 'Subject2'),
(3, 'Subject3'),
(4, 'Subject4'),
(5, 'Subject5'),
(6, 'Subject6'),
(7, 'Subject7'),
(8, 'Subject8');

-- --------------------------------------------------------

--
-- Table structure for table `tagging`
--

DROP TABLE IF EXISTS `tagging`;
CREATE TABLE IF NOT EXISTS `tagging` (
  `taggingTableId` int NOT NULL AUTO_INCREMENT,
  `teacherId` int NOT NULL,
  `sessionId` int NOT NULL,
  `semesterNo` int NOT NULL,
  `subjectId` int NOT NULL,
  PRIMARY KEY (`taggingTableId`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tagging`
--

INSERT INTO `tagging` (`taggingTableId`, `teacherId`, `sessionId`, `semesterNo`, `subjectId`) VALUES
(1, 1, 1, 1, 1),
(2, 2, 1, 1, 2),
(3, 3, 1, 2, 3),
(4, 4, 1, 2, 4),
(5, 5, 1, 3, 5),
(6, 6, 1, 3, 6),
(7, 7, 1, 4, 7),
(8, 8, 1, 4, 8);

-- --------------------------------------------------------

--
-- Table structure for table `teacher`
--

DROP TABLE IF EXISTS `teacher`;
CREATE TABLE IF NOT EXISTS `teacher` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `password` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `teacher`
--

INSERT INTO `teacher` (`id`, `name`, `email`, `password`) VALUES
(1, 'teacher1', 'teacher1', 1),
(2, 'teacher2', 'teacher2', 2),
(3, 'teacher3', 'teacher3', 3),
(4, 'teacher4', 'teacher4', 4),
(5, 'teacher5', 'teacher5', 5),
(6, 'teacher6', 'teacher6', 6),
(7, 'teacher7', 'teacher7', 7),
(8, 'teacher8', 'teacher8', 8);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
