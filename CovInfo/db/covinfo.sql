-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3307
-- Generation Time: Jan 05, 2022 at 03:54 PM
-- Server version: 5.7.24
-- PHP Version: 7.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `covinfo`
--

-- --------------------------------------------------------

--
-- Table structure for table `account_type`
--

CREATE TABLE `account_type` (
  `account_type_id` int(11) NOT NULL,
  `account_type_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `account_type`
--

INSERT INTO `account_type` (`account_type_id`, `account_type_name`) VALUES
(1, 'PreUser'),
(2, 'ActiveUser'),
(3, 'InactiveUser');

-- --------------------------------------------------------

--
-- Table structure for table `administrator`
--

CREATE TABLE `administrator` (
  `administrator_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `administrator`
--

INSERT INTO `administrator` (`administrator_id`, `user_id`) VALUES
(1, 2),
(2, 3);

-- --------------------------------------------------------

--
-- Table structure for table `blood_type`
--

CREATE TABLE `blood_type` (
  `blood_type_id` int(11) NOT NULL,
  `blood_type_name` varchar(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `blood_type`
--

INSERT INTO `blood_type` (`blood_type_id`, `blood_type_name`) VALUES
(1, 'A+'),
(2, 'A-'),
(3, 'B+'),
(4, 'B-'),
(5, 'AB+'),
(6, 'AB-'),
(7, 'O+'),
(8, 'O-'),
(9, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `contact_record`
--

CREATE TABLE `contact_record` (
  `contact_id` int(11) NOT NULL,
  `contact_user_id` int(11) NOT NULL,
  `trace_date` date NOT NULL,
  `user_id` int(11) NOT NULL,
  `infection_record_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `contact_record`
--

INSERT INTO `contact_record` (`contact_id`, `contact_user_id`, `trace_date`, `user_id`, `infection_record_id`) VALUES
(6, 2, '2022-01-03', 1, 17);

-- --------------------------------------------------------

--
-- Table structure for table `daily_report`
--

CREATE TABLE `daily_report` (
  `daily_report_id` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `new_cases` int(11) DEFAULT '0',
  `deaths` int(11) DEFAULT '0',
  `recovered` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `daily_report`
--

INSERT INTO `daily_report` (`daily_report_id`, `date`, `new_cases`, `deaths`, `recovered`) VALUES
(1, '2021-11-05', 628, 15, 339),
(2, '2021-11-06', 617, 20, 339),
(3, '2021-11-07', 626, 20, 271),
(4, '2021-11-08', 679, 19, 512),
(5, '2021-11-09', 718, 17, 400),
(6, '2021-11-10', 693, 16, 333),
(7, '2021-11-11', 715, 19, 272),
(8, '2021-11-12', 723, 23, 333),
(9, '2021-11-13', 716, 22, 381),
(10, '2021-11-14', 697, 23, 426),
(11, '2021-11-15', 732, 21, 382),
(12, '2021-11-16', 720, 18, 429),
(13, '2021-11-17', 728, 23, 448),
(14, '2021-11-18', 737, 15, 372),
(15, '2021-11-19', 745, 14, 351),
(16, '2021-11-20', 725, 22, 442),
(17, '2021-11-21', 650, 19, 475),
(18, '2021-11-22', 726, 13, 567),
(19, '2021-11-23', 112, 2, 0),
(20, '2021-12-29', 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `death_record`
--

CREATE TABLE `death_record` (
  `record_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `medical_officer_id` int(11) NOT NULL,
  `death_date` date NOT NULL,
  `death_location` varchar(1024) NOT NULL,
  `death_cause` varchar(6024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `district`
--

CREATE TABLE `district` (
  `district_id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `province_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `district`
--

INSERT INTO `district` (`district_id`, `name`, `province_id`) VALUES
(1, 'Gampaha', 1);

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE `doctor` (
  `doctor_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`doctor_id`, `user_id`) VALUES
(1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `gender`
--

CREATE TABLE `gender` (
  `gender_id` int(11) NOT NULL,
  `gender` varchar(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `gender`
--

INSERT INTO `gender` (`gender_id`, `gender`) VALUES
(1, 'Male'),
(2, 'Female');

-- --------------------------------------------------------

--
-- Table structure for table `infection_record`
--

CREATE TABLE `infection_record` (
  `infection_record_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `admitted_date` date DEFAULT NULL,
  `release_date` date DEFAULT NULL,
  `medical_centre_id` int(11) DEFAULT NULL,
  `remarks` varchar(2048) DEFAULT NULL,
  `autority_id` int(11) DEFAULT NULL,
  `admission_administrator_id` int(11) NOT NULL,
  `release_medical_officer_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `infection_record`
--

INSERT INTO `infection_record` (`infection_record_id`, `user_id`, `admitted_date`, `release_date`, `medical_centre_id`, `remarks`, `autority_id`, `admission_administrator_id`, `release_medical_officer_id`) VALUES
(15, 1, '2022-01-01', '2022-01-01', 2, 'efsdfsdf', NULL, 1, 2),
(16, 1, '2022-01-01', '2022-01-01', 2, 'saasa', 2, 1, 2),
(17, 1, '2022-01-03', '2022-01-03', 2, '', 2, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `medical_centre`
--

CREATE TABLE `medical_centre` (
  `medical_centre_id` int(11) NOT NULL,
  `name` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `medical_centre`
--

INSERT INTO `medical_centre` (`medical_centre_id`, `name`) VALUES
(1, 'IDH'),
(2, 'General Hospital - Negombo'),
(3, 'General Hospital - Gampaha');

-- --------------------------------------------------------

--
-- Table structure for table `moh_division`
--

CREATE TABLE `moh_division` (
  `moh_division_id` int(11) NOT NULL,
  `moh_name` varchar(32) NOT NULL,
  `district_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `moh_division`
--

INSERT INTO `moh_division` (`moh_division_id`, `moh_name`, `district_id`) VALUES
(1, 'Ja-Ela', 1),
(2, 'Negombo', 1),
(3, 'Imbulgoda', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `notification_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `sent_date_time` datetime NOT NULL,
  `read_status_id` int(11) NOT NULL,
  `notification_message` varchar(512) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`notification_id`, `receiver_id`, `sent_date_time`, `read_status_id`, `notification_message`) VALUES
(3, 1, '2021-12-26 18:17:06', 1, 'Quarantine Period has Ended'),
(4, 1, '2021-12-26 18:34:09', 1, 'You have being added to Infected'),
(5, 1, '2021-12-26 18:34:40', 1, 'You have being added to Infected'),
(6, 1, '2021-12-26 18:37:18', 1, 'You have being added to Quarantined'),
(7, 1, '2021-12-26 18:37:29', 1, 'You have being added to Infected'),
(8, 1, '2021-12-26 18:37:33', 1, 'You have being added to Quarantined'),
(9, 1, '2021-12-26 18:44:34', 1, 'Your Quarantined Period has Extended'),
(10, 1, '2021-12-26 18:48:23', 1, 'You have being added to Infected'),
(11, 1, '2021-12-26 18:48:29', 1, 'You have being added to Quarantined'),
(12, 1, '2021-12-26 18:48:29', 1, 'Your have being released'),
(13, 1, '2021-12-26 18:55:55', 1, 'Your vaccination Registration completed'),
(14, 1, '2021-12-28 17:56:36', 1, 'You have being added to Infected'),
(15, 1, '2021-12-28 19:41:58', 1, 'You have being added to Quarantined'),
(16, 1, '2021-12-28 19:41:58', 1, 'Your have being released'),
(17, 1, '2021-12-28 19:42:51', 1, 'You have being added to Infected'),
(18, 1, '2021-12-28 19:46:38', 1, 'You have being added to Quarantined'),
(19, 1, '2021-12-28 19:46:38', 1, 'Your have being released'),
(20, 1, '2021-12-28 19:47:10', 1, 'You have being added to Infected'),
(21, 1, '2021-12-28 19:49:09', 1, 'You have being added to Quarantined'),
(22, 1, '2021-12-28 19:49:09', 1, 'Your have being released'),
(23, 1, '2021-12-28 19:49:17', 1, 'You have being added to Infected'),
(24, 1, '2021-12-28 19:56:14', 1, 'You have being added to Quarantined'),
(25, 1, '2021-12-28 20:26:06', 1, 'Your Quarantined Period has Extended'),
(26, 1, '2021-12-28 20:48:25', 1, 'Your vaccination Registration completed'),
(27, 1, '2021-12-29 00:00:01', 1, 'Quarantine Period has Ended'),
(28, 1, '2021-12-29 12:30:38', 1, 'You have being added to Quarantined'),
(29, 1, '2021-12-29 12:31:07', 1, 'Your Quarantined Period has Extended'),
(30, 1, '2021-12-29 12:33:43', 1, 'You have being added to Infected'),
(31, 1, '2021-12-29 12:46:25', 1, 'You have being added to Quarantined'),
(32, 1, '2021-12-29 12:46:25', 1, 'Your have being released'),
(33, 1, '2021-12-29 12:47:45', 1, 'Your vaccination Registration completed'),
(34, 1, '2021-12-30 13:33:28', 1, 'You have being added to Infected'),
(35, 1, '2021-12-30 13:33:56', 1, 'You have being added to Quarantined'),
(36, 1, '2021-12-30 13:33:56', 1, 'Your have being released'),
(37, 1, '2021-12-30 13:37:50', 1, 'Your vaccination Registration completed'),
(38, 2, '2021-12-30 14:36:02', 1, 'You have being added to Quarantined'),
(39, 1, '2022-01-01 11:08:11', 1, 'You have being added to Infected'),
(40, 1, '2022-01-01 11:27:21', 1, 'You have being added to Quarantined'),
(41, 1, '2022-01-01 11:27:21', 1, 'Your have being released'),
(42, 1, '2022-01-01 11:42:54', 1, 'You have being added to Infected'),
(43, 1, '2022-01-01 12:55:55', 1, 'You have being added to Quarantined'),
(44, 1, '2022-01-01 12:55:55', 1, 'Your have being released'),
(45, 2, '2022-01-01 12:59:49', 1, 'Your vaccination Registration completed'),
(46, 1, '2022-01-03 20:56:15', 1, 'You have being added to Infected'),
(47, 1, '2022-01-03 22:42:05', 1, 'You have being added to Quarantined'),
(48, 1, '2022-01-03 22:42:05', 1, 'Your have being released');

-- --------------------------------------------------------

--
-- Table structure for table `province`
--

CREATE TABLE `province` (
  `province_id` int(11) NOT NULL,
  `prov_name` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `province`
--

INSERT INTO `province` (`province_id`, `prov_name`) VALUES
(1, 'Western province');

-- --------------------------------------------------------

--
-- Table structure for table `quarantine_place`
--

CREATE TABLE `quarantine_place` (
  `quarantine_place_id` int(11) NOT NULL,
  `quarantine_place_name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `quarantine_place`
--

INSERT INTO `quarantine_place` (`quarantine_place_id`, `quarantine_place_name`) VALUES
(1, 'Home'),
(2, 'Negombo Quarantine Centre\r\n'),
(3, 'Gampha Quarantine Centre\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `quarantine_record`
--

CREATE TABLE `quarantine_record` (
  `quarantine_record_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `administrator_id` int(11) NOT NULL,
  `place_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `quarantine_record`
--

INSERT INTO `quarantine_record` (`quarantine_record_id`, `user_id`, `start_date`, `end_date`, `administrator_id`, `place_id`) VALUES
(1, 1, '2021-09-07', '2021-09-21', 1, 2),
(2, 1, '2021-11-01', '2021-11-15', 1, 1),
(3, 1, '2021-11-23', '2021-12-24', 1, 2),
(7, 1, '2021-12-25', '2021-12-31', 1, 2),
(8, 1, '2021-12-25', '2021-12-31', 1, 1),
(9, 1, '2021-12-25', '2022-01-07', 1, 1),
(10, 1, '2021-12-25', '2022-01-09', 1, 1),
(11, 1, '2021-12-25', '2022-01-08', 1, 1),
(12, 1, '2021-12-25', '2021-12-15', 1, 1),
(13, 1, '2021-12-28', '2022-01-30', 1, 2),
(14, 1, '2021-12-29', '2022-02-06', 1, 2),
(15, 1, '2021-12-29', '2022-01-26', 1, 1),
(16, 1, '2021-12-30', '2022-01-27', 1, 1),
(17, 1, '2022-01-01', '2022-01-27', 1, 1),
(18, 1, '2022-01-01', '2022-01-28', 1, 1),
(19, 1, '2022-01-03', '2022-01-28', 1, 1);

--
-- Triggers `quarantine_record`
--
DELIMITER $$
CREATE TRIGGER `Quarantine_Extended_trigger` AFTER UPDATE ON `quarantine_record` FOR EACH ROW BEGIN
    IF !(NEW.end_date<=>OLD.end_date)
    THEN
        INSERT INTO notification(receiver_id, sent_date_time, read_status_id,notification_message) VALUES (NEW.user_id,NOW(),1,'Your Quarantined Period has Extended');
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `read_status`
--

CREATE TABLE `read_status` (
  `read_status_id` int(11) NOT NULL,
  `read_status_name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `read_status`
--

INSERT INTO `read_status` (`read_status_id`, `read_status_name`) VALUES
(1, 'UnRead'),
(2, 'Read');

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `report_id` int(11) NOT NULL,
  `total_cases` int(11) DEFAULT '0',
  `total_deaths` int(11) DEFAULT '0',
  `total_recovered` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `report`
--

INSERT INTO `report` (`report_id`, `total_cases`, `total_deaths`, `total_recovered`) VALUES
(1, 553748, 14057, 8);

-- --------------------------------------------------------

--
-- Table structure for table `report_type`
--

CREATE TABLE `report_type` (
  `report_type_id` tinyint(4) NOT NULL,
  `name` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `report_type`
--

INSERT INTO `report_type` (`report_type_id`, `name`) VALUES
(1, 'PCR');

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `status_id` int(11) NOT NULL,
  `status_name` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`status_id`, `status_name`) VALUES
(1, 'Healthy'),
(2, 'Quarantined'),
(3, 'Infected'),
(4, 'Deceased');

-- --------------------------------------------------------

--
-- Table structure for table `test_report`
--

CREATE TABLE `test_report` (
  `test_report_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `medical_officer_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `type_id` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `test_report`
--

INSERT INTO `test_report` (`test_report_id`, `user_id`, `medical_officer_id`, `date`, `status`, `type_id`) VALUES
(1, 1, 1, '2021-10-19', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `account_id` char(12) DEFAULT NULL,
  `password` varchar(64) DEFAULT NULL,
  `email_address` varchar(64) DEFAULT NULL,
  `first_name` varchar(32) DEFAULT NULL,
  `middle_name` varchar(32) DEFAULT NULL,
  `last_name` varchar(32) DEFAULT NULL,
  `nic_number` varchar(16) DEFAULT NULL,
  `birth_day` date DEFAULT NULL,
  `gender_id` int(11) DEFAULT NULL,
  `district_id` int(11) DEFAULT NULL,
  `province_id` int(11) DEFAULT NULL,
  `moh_division_id` int(11) DEFAULT NULL,
  `address` varchar(128) DEFAULT NULL,
  `phone_number` varchar(16) DEFAULT NULL,
  `user_type_id` int(11) DEFAULT NULL,
  `status_id` int(11) DEFAULT NULL,
  `vaccine_status_id` int(11) DEFAULT NULL,
  `blood_type_id` int(11) DEFAULT NULL,
  `account_type` int(11) DEFAULT NULL,
  `medical_remarks` varchar(512) NOT NULL,
  `o_birth_day` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `account_id`, `password`, `email_address`, `first_name`, `middle_name`, `last_name`, `nic_number`, `birth_day`, `gender_id`, `district_id`, `province_id`, `moh_division_id`, `address`, `phone_number`, `user_type_id`, `status_id`, `vaccine_status_id`, `blood_type_id`, `account_type`, `medical_remarks`, `o_birth_day`) VALUES
(1, '199910260000', 'c63debef5bf79982cdf319a6473df394', 'ravin.19@cse.mrt.ac.lk', 'Ravin', 'Dulanjana', 'Fernando', '991234567V', '1999-10-26', 1, 1, 1, 1, 'No 123, Charch road, Ja ela', '076713227789', 1, 2, 3, 6, 2, '', '1999-10-26'),
(2, '199910310000', '01ba7c310dff3c66420ef443863cb3fa', 'krishan.19@cse.mrt.ac.lk', 'Krishan', 'Chavinda', 'Appuhamy', '994786541V', '1999-10-31', 1, 1, 1, 1, 'No 4,Temple road, Kattuwa, Negombo', '0716885650', 2, 2, 2, 2, 2, '', '1999-10-31'),
(3, '199911240000', 'ea4adefd90680522dbee12f917360591', 'gamunu.19@cse.mrt.ac.lk', 'Gamunu', 'Shakya', 'Bandara', '994753621V', '1999-11-24', 1, 1, 1, 3, 'No 123,church road, imbulgoda.', '0714865472', 2, 1, 1, 8, 2, '', '1999-11-24');

--
-- Triggers `user`
--
DELIMITER $$
CREATE TRIGGER `Infected_trigger` AFTER UPDATE ON `user` FOR EACH ROW BEGIN
    IF NEW.status_id<=>3 && OLD.status_id<=>2 || NEW.status_id<=>3 && OLD.status_id<=>1
    THEN
        INSERT INTO notification(receiver_id, sent_date_time, read_status_id,notification_message) VALUES (NEW.user_id,NOW(),1,'You have being added to Infected');
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `Patient_Release_trigger` AFTER UPDATE ON `user` FOR EACH ROW BEGIN
    IF NEW.status_id<=>2 && OLD.status_id<=>3
    THEN
        INSERT INTO notification(receiver_id, sent_date_time, read_status_id,notification_message) VALUES (NEW.user_id,NOW(),1,'Your have being released');
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `Quarantine_Period_Ended_trigger` AFTER UPDATE ON `user` FOR EACH ROW BEGIN
    IF NEW.status_id<=>1 && OLD.status_id<=>2
    THEN
      INSERT INTO notification(receiver_id, sent_date_time, read_status_id,notification_message) VALUES (NEW.user_id,NOW(),1,'Quarantine Period has Ended');
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `Quarantined_trigger` AFTER UPDATE ON `user` FOR EACH ROW BEGIN
    IF NEW.status_id<=>2 && OLD.status_id<=>1 || NEW.status_id<=>2 && OLD.status_id<=>3
    THEN
        INSERT INTO notification(receiver_id, sent_date_time, read_status_id,notification_message) VALUES (NEW.user_id,NOW(),1,'You have being added to Quarantined');
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `Vaccination_Completed_trigger` AFTER UPDATE ON `user` FOR EACH ROW BEGIN
    IF NEW.vaccine_status_id > OLD.vaccine_status_id
    THEN
        INSERT INTO notification(receiver_id, sent_date_time, read_status_id,notification_message) VALUES (NEW.user_id,NOW(),1,'Your vaccination Registration completed');
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `user_type`
--

CREATE TABLE `user_type` (
  `user_type_id` int(11) NOT NULL,
  `user_type_name` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_type`
--

INSERT INTO `user_type` (`user_type_id`, `user_type_name`) VALUES
(1, 'Public'),
(2, 'Medical'),
(3, 'Authority');

-- --------------------------------------------------------

--
-- Table structure for table `vaccination_record`
--

CREATE TABLE `vaccination_record` (
  `vaccination_record_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `vaccine_id` int(11) DEFAULT NULL,
  `administrator_id` int(11) DEFAULT NULL,
  `place` varchar(32) DEFAULT NULL,
  `dose` int(11) DEFAULT NULL,
  `batch_number` varchar(16) DEFAULT NULL,
  `next_appointment` date DEFAULT NULL,
  `remarks` varchar(512) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `vaccination_record`
--

INSERT INTO `vaccination_record` (`vaccination_record_id`, `user_id`, `date`, `vaccine_id`, `administrator_id`, `place`, `dose`, `batch_number`, `next_appointment`, `remarks`) VALUES
(1, 1, '2021-10-01', 2, 1, 'Ja-ela church', 1, '20211001AAA', '2021-11-01', 'headache after the vaccination. '),
(5, 1, '2021-12-29', 3, 1, 'Gampha hospital', 2, '123123123123', '2022-01-09', 'qwqwq'),
(6, 2, '2022-01-01', 2, 1, 'Gampha hospital', 1, '123123123123', '2022-01-28', 'kkkk');

-- --------------------------------------------------------

--
-- Table structure for table `vaccine`
--

CREATE TABLE `vaccine` (
  `vaccine_id` int(11) NOT NULL,
  `vaccine_name` varchar(32) DEFAULT NULL,
  `manufacture` varchar(32) DEFAULT NULL,
  `effectiveness` int(11) DEFAULT NULL,
  `number_of_doses` int(11) DEFAULT NULL,
  `days_between_two_doses` int(11) DEFAULT NULL,
  `details` varchar(1024) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `vaccine`
--

INSERT INTO `vaccine` (`vaccine_id`, `vaccine_name`, `manufacture`, `effectiveness`, `number_of_doses`, `days_between_two_doses`, `details`) VALUES
(1, 'Covishield', 'Serum Institute of India Pvt Ltd', 81, 2, 28, 'The Oxford–AstraZeneca COVID-19 vaccine, codenamed AZD1222, and sold under the brand names Covishield and Vaxzevria among others, is a viral vector vaccine for prevention of COVID-19. Developed in the United Kingdom by the Oxford University and British-Swedish company AstraZeneca, using as a vector the modified chimpanzee adenovirus ChAdOx1. The vaccine is given by intramuscular injection. Studies carried out in 2020 showed that the efficacy of the vaccine is 76.0% at preventing symptomatic COVID-19 beginning at 22 days following the first dose, and 81.3% after the second dose. A study in Scotland found that, for symptomatic COVID-19 infection after the second dose, the vaccine is 81% effective against the Alpha variant , and 61% against the Delta variant'),
(2, 'Sinopharm ', 'Beijing Bio-Institute ', 79, 2, 28, 'The Sinopharm BIBP COVID-19 vaccine, also known as BBIBP-CorV, the Sinopharm COVID-19 vaccine,or BIBP vaccine, is one of two inactivated virus COVID-19 vaccines developed by Sinopharm\'s Beijing Institute of Biological Products (sometimes written as Beijing Bio-Institute of Biological Products, resulting in the two different acronyms BBIBP and BIBP for the same vaccine). It completed Phase III trials in Argentina, Bahrain, Egypt, Morocco, Pakistan, Peru, and the United Arab Emirates (UAE) with over 60,000 participants. BBIBP-CorV shares similar technology with CoronaVac and Covaxin, other inactivated virus vaccines for COVID-19. Its product name is SARS-CoV-2 Vaccine  not to be confused with the similar product name of CoronaVac.'),
(3, 'Sputnik - V', 'Gamaleya Research Institute', 92, 2, 28, 'Sputnik V (Russian: Спутник V) or Gam-COVID-Vac (Russian: Гам-КОВИД-Вак) is an adenovirus viral vector vaccine for COVID-19 developed by the Gamaleya Research Institute of Epidemiology and Microbiology in Russia. It is the world\'s first registered combination vector vaccine for the prevention of COVID-19, having been registered on 11 August 2020 by the Russian Ministry of Health.\r\n\r\n'),
(4, 'Pfizer', 'Pfizer and BioNTech', 95, 2, 28, 'The Pfizer–BioNTech COVID-19 vaccine (INN: tozinameran), sold under the brand name Comirnaty, is an mRNA-based COVID-19 vaccine developed by the German biotechnology company BioNTech and for its development collaborated with American company Pfizer, for support with clinical trials, logistics, and manufacturing.It is authorized for use in people aged five years and older in some judrisdictions, twelve years and older in some jurisdictions, and for people sixteen years and older in other jurisdictions, to provide protection against COVID-19, caused by infection with the SARS-CoV-2 virus. The vaccine is given by intramuscular injection. It is composed of nucleoside-modified mRNA (modRNA) encoding a mutated form of the full-length spike protein of SARS-CoV-2, which is encapsulated in lipid nanoparticles. Initial advice indicated that vaccination required two doses given 21 days apart, but the interval was later extended to up to 42 days in the US, and up to four months in Canada.'),
(5, 'Moderna', 'Moderna', 94, 2, 28, 'The Moderna COVID‑19 vaccine , codenamed mRNA-1273 and sold under the brand name Spikevax, is a COVID-19 vaccine developed by American company Moderna, the United States National Institute of Allergy and Infectious Diseases (NIAID) and the Biomedical Advanced Research and Development Authority (BARDA). It is authorized for use in people aged twelve years and older in some jurisdictions and for people eighteen years and older in other jurisdictions to provide protection against COVID-19 which is caused by infection by the SARS-CoV-2 virus. It is designed to be administered as two or three 0.5 mL doses given by intramuscular injection at an interval of at least 28 days apart');

-- --------------------------------------------------------

--
-- Table structure for table `vaccine_status`
--

CREATE TABLE `vaccine_status` (
  `vaccine_status_id` int(11) NOT NULL,
  `vaccine_status_name` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `vaccine_status`
--

INSERT INTO `vaccine_status` (`vaccine_status_id`, `vaccine_status_name`) VALUES
(1, 'None'),
(2, 'Partial'),
(3, 'Completed');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account_type`
--
ALTER TABLE `account_type`
  ADD PRIMARY KEY (`account_type_id`);

--
-- Indexes for table `administrator`
--
ALTER TABLE `administrator`
  ADD PRIMARY KEY (`administrator_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `blood_type`
--
ALTER TABLE `blood_type`
  ADD PRIMARY KEY (`blood_type_id`);

--
-- Indexes for table `contact_record`
--
ALTER TABLE `contact_record`
  ADD PRIMARY KEY (`contact_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `infection_record_id` (`infection_record_id`);

--
-- Indexes for table `daily_report`
--
ALTER TABLE `daily_report`
  ADD PRIMARY KEY (`daily_report_id`);

--
-- Indexes for table `death_record`
--
ALTER TABLE `death_record`
  ADD PRIMARY KEY (`record_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `medical_officer_id` (`medical_officer_id`);

--
-- Indexes for table `district`
--
ALTER TABLE `district`
  ADD PRIMARY KEY (`district_id`),
  ADD KEY `province_id` (`province_id`);

--
-- Indexes for table `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`doctor_id`),
  ADD KEY `user_id` (`user_id`) USING BTREE;

--
-- Indexes for table `gender`
--
ALTER TABLE `gender`
  ADD PRIMARY KEY (`gender_id`);

--
-- Indexes for table `infection_record`
--
ALTER TABLE `infection_record`
  ADD PRIMARY KEY (`infection_record_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `medical_centre_id` (`medical_centre_id`),
  ADD KEY `autority_id` (`autority_id`),
  ADD KEY `admission_administrator_id` (`admission_administrator_id`),
  ADD KEY `release_medical_officer_id` (`release_medical_officer_id`);

--
-- Indexes for table `medical_centre`
--
ALTER TABLE `medical_centre`
  ADD PRIMARY KEY (`medical_centre_id`);

--
-- Indexes for table `moh_division`
--
ALTER TABLE `moh_division`
  ADD PRIMARY KEY (`moh_division_id`),
  ADD KEY `district_id` (`district_id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `receiver_id` (`receiver_id`),
  ADD KEY `read_status_id` (`read_status_id`);

--
-- Indexes for table `province`
--
ALTER TABLE `province`
  ADD PRIMARY KEY (`province_id`);

--
-- Indexes for table `quarantine_place`
--
ALTER TABLE `quarantine_place`
  ADD PRIMARY KEY (`quarantine_place_id`);

--
-- Indexes for table `quarantine_record`
--
ALTER TABLE `quarantine_record`
  ADD PRIMARY KEY (`quarantine_record_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `administrator_id` (`administrator_id`),
  ADD KEY `place_id` (`place_id`);

--
-- Indexes for table `read_status`
--
ALTER TABLE `read_status`
  ADD PRIMARY KEY (`read_status_id`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`report_id`);

--
-- Indexes for table `report_type`
--
ALTER TABLE `report_type`
  ADD PRIMARY KEY (`report_type_id`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`status_id`);

--
-- Indexes for table `test_report`
--
ALTER TABLE `test_report`
  ADD PRIMARY KEY (`test_report_id`),
  ADD KEY `type_id` (`type_id`),
  ADD KEY `medical_officer_id` (`medical_officer_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `account_id` (`account_id`) USING BTREE,
  ADD KEY `gender_id` (`gender_id`),
  ADD KEY `district_id` (`district_id`),
  ADD KEY `province_id` (`province_id`),
  ADD KEY `moh_division_id` (`moh_division_id`),
  ADD KEY `user_type_id` (`user_type_id`),
  ADD KEY `status_id` (`status_id`),
  ADD KEY `vaccine_status_id` (`vaccine_status_id`),
  ADD KEY `blood_type_id` (`blood_type_id`),
  ADD KEY `account_type` (`account_type`);

--
-- Indexes for table `user_type`
--
ALTER TABLE `user_type`
  ADD PRIMARY KEY (`user_type_id`);

--
-- Indexes for table `vaccination_record`
--
ALTER TABLE `vaccination_record`
  ADD PRIMARY KEY (`vaccination_record_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `administrator_id` (`administrator_id`),
  ADD KEY `vaccine_id` (`vaccine_id`);

--
-- Indexes for table `vaccine`
--
ALTER TABLE `vaccine`
  ADD PRIMARY KEY (`vaccine_id`);

--
-- Indexes for table `vaccine_status`
--
ALTER TABLE `vaccine_status`
  ADD PRIMARY KEY (`vaccine_status_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account_type`
--
ALTER TABLE `account_type`
  MODIFY `account_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `administrator`
--
ALTER TABLE `administrator`
  MODIFY `administrator_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `blood_type`
--
ALTER TABLE `blood_type`
  MODIFY `blood_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `contact_record`
--
ALTER TABLE `contact_record`
  MODIFY `contact_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `daily_report`
--
ALTER TABLE `daily_report`
  MODIFY `daily_report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `death_record`
--
ALTER TABLE `death_record`
  MODIFY `record_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `district`
--
ALTER TABLE `district`
  MODIFY `district_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `doctor`
--
ALTER TABLE `doctor`
  MODIFY `doctor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `gender`
--
ALTER TABLE `gender`
  MODIFY `gender_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `infection_record`
--
ALTER TABLE `infection_record`
  MODIFY `infection_record_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `medical_centre`
--
ALTER TABLE `medical_centre`
  MODIFY `medical_centre_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `moh_division`
--
ALTER TABLE `moh_division`
  MODIFY `moh_division_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `province`
--
ALTER TABLE `province`
  MODIFY `province_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `quarantine_place`
--
ALTER TABLE `quarantine_place`
  MODIFY `quarantine_place_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `quarantine_record`
--
ALTER TABLE `quarantine_record`
  MODIFY `quarantine_record_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `report`
--
ALTER TABLE `report`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `report_type`
--
ALTER TABLE `report_type`
  MODIFY `report_type_id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `test_report`
--
ALTER TABLE `test_report`
  MODIFY `test_report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_type`
--
ALTER TABLE `user_type`
  MODIFY `user_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `vaccination_record`
--
ALTER TABLE `vaccination_record`
  MODIFY `vaccination_record_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `vaccine`
--
ALTER TABLE `vaccine`
  MODIFY `vaccine_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `vaccine_status`
--
ALTER TABLE `vaccine_status`
  MODIFY `vaccine_status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `administrator`
--
ALTER TABLE `administrator`
  ADD CONSTRAINT `administrator_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `contact_record`
--
ALTER TABLE `contact_record`
  ADD CONSTRAINT `contact_record_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `contact_record_ibfk_2` FOREIGN KEY (`infection_record_id`) REFERENCES `infection_record` (`infection_record_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `death_record`
--
ALTER TABLE `death_record`
  ADD CONSTRAINT `death_record_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `death_record_ibfk_2` FOREIGN KEY (`medical_officer_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `district`
--
ALTER TABLE `district`
  ADD CONSTRAINT `district_ibfk_1` FOREIGN KEY (`province_id`) REFERENCES `province` (`province_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `doctor`
--
ALTER TABLE `doctor`
  ADD CONSTRAINT `doctor_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `infection_record`
--
ALTER TABLE `infection_record`
  ADD CONSTRAINT `infection_record_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `infection_record_ibfk_3` FOREIGN KEY (`medical_centre_id`) REFERENCES `medical_centre` (`medical_centre_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `infection_record_ibfk_6` FOREIGN KEY (`autority_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `infection_record_ibfk_7` FOREIGN KEY (`admission_administrator_id`) REFERENCES `administrator` (`administrator_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `infection_record_ibfk_8` FOREIGN KEY (`release_medical_officer_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `moh_division`
--
ALTER TABLE `moh_division`
  ADD CONSTRAINT `moh_division_ibfk_1` FOREIGN KEY (`district_id`) REFERENCES `district` (`district_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `notification_ibfk_1` FOREIGN KEY (`receiver_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `notification_ibfk_2` FOREIGN KEY (`read_status_id`) REFERENCES `read_status` (`read_status_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `quarantine_record`
--
ALTER TABLE `quarantine_record`
  ADD CONSTRAINT `quarantine_record_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `quarantine_record_ibfk_2` FOREIGN KEY (`administrator_id`) REFERENCES `administrator` (`administrator_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `quarantine_record_ibfk_3` FOREIGN KEY (`place_id`) REFERENCES `quarantine_place` (`quarantine_place_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `test_report`
--
ALTER TABLE `test_report`
  ADD CONSTRAINT `test_report_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `report_type` (`report_type_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `test_report_ibfk_2` FOREIGN KEY (`medical_officer_id`) REFERENCES `administrator` (`administrator_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`gender_id`) REFERENCES `gender` (`gender_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_ibfk_2` FOREIGN KEY (`district_id`) REFERENCES `district` (`district_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_ibfk_3` FOREIGN KEY (`province_id`) REFERENCES `province` (`province_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_ibfk_4` FOREIGN KEY (`moh_division_id`) REFERENCES `moh_division` (`moh_division_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_ibfk_5` FOREIGN KEY (`user_type_id`) REFERENCES `user_type` (`user_type_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_ibfk_6` FOREIGN KEY (`status_id`) REFERENCES `status` (`status_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_ibfk_7` FOREIGN KEY (`vaccine_status_id`) REFERENCES `vaccine_status` (`vaccine_status_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_ibfk_8` FOREIGN KEY (`blood_type_id`) REFERENCES `blood_type` (`blood_type_id`),
  ADD CONSTRAINT `user_ibfk_9` FOREIGN KEY (`account_type`) REFERENCES `account_type` (`account_type_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `vaccination_record`
--
ALTER TABLE `vaccination_record`
  ADD CONSTRAINT `vaccination_record_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `vaccination_record_ibfk_2` FOREIGN KEY (`administrator_id`) REFERENCES `administrator` (`administrator_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `vaccination_record_ibfk_3` FOREIGN KEY (`vaccine_id`) REFERENCES `vaccine` (`vaccine_id`) ON DELETE CASCADE ON UPDATE CASCADE;

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `DailyReport` ON SCHEDULE EVERY 1 DAY STARTS '2021-12-27 00:00:01' ON COMPLETION NOT PRESERVE ENABLE DO INSERT INTO Daily_report (date, new_cases, deaths, recovered) VALUES (SYSDATE(),0,0,0)$$

CREATE DEFINER=`root`@`localhost` EVENT `QuarantinePeriodEnd` ON SCHEDULE EVERY 1 DAY STARTS '2021-12-24 00:00:01' ON COMPLETION NOT PRESERVE ENABLE DO UPDATE user  INNER JOIN quarantine_record
    ON quarantine_record.user_id =user.user_id
set user.status_id=1
WHERE NOW()>=quarantine_record.end_date$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
