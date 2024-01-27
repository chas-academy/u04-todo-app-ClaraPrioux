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
(21,	'Work',	5),
(22,	'Personal',	5),
(23,	'Home',	5);

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
(48,	'Complete project report',	'Prepare and finalize the quarterly project report for client presentation.\r\n',	0,	5,	21),
(49,	'Schedule team meeting',	'Set up a team meeting to discuss upcoming project deadlines.',	0,	5,	21),
(50,	'Buy groceries',	'Make a list of essential groceries and head to the supermarket.',	0,	5,	22),
(51,	'Exercise for 30 minutes',	'Engage in a 30-minute workout routine at home.',	0,	5,	22),
(52,	'Read a chapter of a book ',	'Spend some time reading a chapter from your current book.',	0,	5,	22),
(53,	'Clean the house',	'Tidy up each room, dust surfaces, and vacuum the floors.',	0,	5,	23),
(54,	'Fix the leaking faucet',	'Repair the leaking faucet in the kitchen.',	0,	5,	23),
(55,	'Water the plants',	'Ensure all indoor and outdoor plants receive proper watering.\r\n',	0,	5,	23),
(56,	'Rearrange living room furniture',	'Consider rearranging the furniture for a fresh look.',	0,	5,	23);

DROP TABLE IF EXISTS `Users`;
CREATE TABLE `Users` (
  `userID` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `Users` (`userID`, `Username`, `Password`) VALUES
(5,	'Clara',	'$2y$10$0mUJYXs2ZJJeD3RQ1Ud6NuetfOpAcS0bUUSQegyiL/qqzr5pQtnxO');

-- 2024-01-27 21:15:35