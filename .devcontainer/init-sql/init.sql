-- Adminer 4.8.1 MySQL 5.5.5-10.4.32-MariaDB-1:10.4.32+maria~ubu2004 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `lists`;
CREATE TABLE `lists` (
  `listId` int(11) NOT NULL AUTO_INCREMENT,
  `listName` varchar(50) NOT NULL,
  `userID` int(11) NOT NULL,
  PRIMARY KEY (`listId`),
  KEY `userID` (`userID`),
  CONSTRAINT `lists_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `Users` (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `lists` (`listId`, `listName`, `userID`) VALUES
(1,	'How to add the list to the database?',	1),
(2,	'Chores ',	2);

DROP TABLE IF EXISTS `tasks`;
CREATE TABLE `tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `completion` tinyint(4) DEFAULT 0,
  `userID` int(11) DEFAULT NULL,
  `listId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userID` (`userID`),
  KEY `listId` (`listId`),
  CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `Users` (`userID`),
  CONSTRAINT `tasks_ibfk_2` FOREIGN KEY (`listId`) REFERENCES `lists` (`listId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `tasks` (`id`, `title`, `description`, `completion`, `userID`, `listId`) VALUES
(1,	'Study more',	'I have to learn more if I want to be the best Fullstack developer',	1,	NULL,	NULL),
(3,	'Complete Project Proposal',	'Draft and finalize the project proposal document, outlining project goals, deliverables, and timelines for team review.',	0,	NULL,	NULL),
(4,	'Grocery Shopping',	'Purchase essential groceries for the week, including fresh produce, dairy, and pantry staples to ensure a well-stocked kitchen.',	0,	NULL,	NULL),
(5,	'Schedule Dentist Appointment',	'Call the dentist\'s office to schedule a routine check-up and cleaning. Choose a convenient time for the appointment.',	0,	NULL,	NULL),
(6,	'Exam Preparation',	'Review and summarize key concepts from Chapter 6 for upcoming exams. Create a study plan to cover all relevant material effectively.',	0,	NULL,	NULL),
(7,	'Attend Team Meeting',	'Participate in the team meeting at 2:00 PM to discuss project updates, address challenges, and collaborate on upcoming tasks and goals.',	0,	NULL,	NULL),
(10,	'Tjena',	'hur Ã¤r lÃ¤get',	1,	NULL,	NULL),
(11,	'Last test design ',	'Last test design before beginning the VG goals',	0,	NULL,	NULL),
(13,	'First test with foreign key',	'I\'m just gonna try to add an todo list, but i will have to create a session so I don\'t need to add a user. ',	0,	1,	1),
(14,	'Wash my clothes',	'I have to do it before the weekend ',	0,	2,	NULL);

DROP TABLE IF EXISTS `Users`;
CREATE TABLE `Users` (
  `userID` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(25) NOT NULL,
  `Password` varchar(25) NOT NULL,
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `Users` (`userID`, `Username`, `Password`) VALUES
(1,	'Clara',	'blablabla'),
(2,	'Quentin',	'jaimelestetardenini');

-- 2024-01-23 10:00:22