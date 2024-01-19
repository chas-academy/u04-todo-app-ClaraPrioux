-- Adminer 4.8.1 MySQL 5.5.5-10.4.32-MariaDB-1:10.4.32+maria~ubu2004 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `tasks`;
CREATE TABLE `tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `completion` tinyint(4) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `tasks` (`id`, `title`, `description`, `completion`) VALUES
(1,	'Study more',	'I have to learn more if I want to be the best Fullstack developer',	1),
(3,	'Complete Project Proposal',	'Draft and finalize the project proposal document, outlining project goals, deliverables, and timelines for team review.',	0),
(4,	'Grocery Shopping',	'Purchase essential groceries for the week, including fresh produce, dairy, and pantry staples to ensure a well-stocked kitchen.',	0),
(5,	'Schedule Dentist Appointment',	'Call the dentist\'s office to schedule a routine check-up and cleaning. Choose a convenient time for the appointment.',	0),
(6,	'Exam Preparation',	'Review and summarize key concepts from Chapter 6 for upcoming exams. Create a study plan to cover all relevant material effectively.',	0),
(7,	'Attend Team Meeting',	'Participate in the team meeting at 2:00 PM to discuss project updates, address challenges, and collaborate on upcoming tasks and goals.',	0),
(8,	'Test avec design1',	'pour voir si Ã§a marche',	0),
(10,	'Tjena',	'hur Ã¤r lÃ¤get',	1);

-- 2024-01-19 17:16:05