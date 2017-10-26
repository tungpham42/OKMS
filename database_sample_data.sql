-- phpMyAdmin SQL Dump
-- version 3.5.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 03, 2013 at 04:20 PM
-- Server version: 5.1.66-cll
-- PHP Version: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `tungpham_main`
--

-- --------------------------------------------------------

--
-- Table structure for table `OKMS_COMMENT`
--

CREATE TABLE IF NOT EXISTS `OKMS_COMMENT` (
  `Comment_ID` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Comment ID',
  `Post_ID` int(10) unsigned DEFAULT NULL COMMENT 'Page ID',
  `User_ID` int(10) unsigned NOT NULL COMMENT 'User ID',
  `Comment_Body` longtext COMMENT 'Comment Body',
  `Comment_Hide_Name` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Hide comments?',
  `Comment_Created` int(11) NOT NULL DEFAULT '0' COMMENT 'Date created timestamp',
  `Comment_Edited` int(11) NOT NULL DEFAULT '0' COMMENT 'Date updated timestamp',
  PRIMARY KEY (`Comment_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

--
-- Dumping data for table `OKMS_COMMENT`
--

INSERT INTO `OKMS_COMMENT` (`Comment_ID`, `Post_ID`, `User_ID`, `Comment_Body`, `Comment_Hide_Name`, `Comment_Created`, `Comment_Edited`) VALUES
(8, 143, 2, 'i don''t know', 1, 1350099590, 0),
(21, 85, 2, '''the key'', is that right?', 0, 1359880949, 0),
(20, 153, 2, 'OKMS stands for Online Knowledge Management System. This project is a college''s assignment for last semesters'' students. It is a Q & A platform for lecturers and students to share knowledge and query. My role in this project is the Development Manager, which focuses on developing the functions and features for the website.', 0, 1359880428, 0);

-- --------------------------------------------------------

--
-- Table structure for table `OKMS_COMMENT_VOTE`
--

CREATE TABLE IF NOT EXISTS `OKMS_COMMENT_VOTE` (
  `User_ID` int(10) unsigned NOT NULL COMMENT 'User ID',
  `Comment_ID` int(10) unsigned NOT NULL COMMENT 'Comment ID',
  `CommentVote_Like` int(1) NOT NULL DEFAULT '0' COMMENT 'Like Token',
  `CommentVote_Dislike` int(1) NOT NULL DEFAULT '0' COMMENT 'Dislike Token',
  KEY `User_ID` (`User_ID`),
  KEY `Comment_ID` (`Comment_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `OKMS_COMMENT_VOTE`
--

INSERT INTO `OKMS_COMMENT_VOTE` (`User_ID`, `Comment_ID`, `CommentVote_Like`, `CommentVote_Dislike`) VALUES
(2, 21, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `OKMS_COURSE`
--

CREATE TABLE IF NOT EXISTS `OKMS_COURSE` (
  `Course_ID` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Course ID',
  `User_ID` int(10) unsigned DEFAULT NULL COMMENT 'Coordinator ID',
  `Course_Name` varchar(255) NOT NULL DEFAULT '' COMMENT 'Course Name',
  `Course_Code` varchar(255) NOT NULL DEFAULT '' COMMENT 'Course Code',
  `Course_Allowed` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Allow students to post questions?',
  `Course_For_Guest` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Allow guests to post questions?',
  PRIMARY KEY (`Course_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=38 ;

--
-- Dumping data for table `OKMS_COURSE`
--

INSERT INTO `OKMS_COURSE` (`Course_ID`, `User_ID`, `Course_Name`, `Course_Code`, `Course_Allowed`) VALUES
(8, 4, 'BIS Strategy and Governance', 'ISYS2424', 1),
(2, 6, 'Project Mgmt & Prof Practice for Info Systems', 'ISYS2131', 1),
(1, 82, 'BIS Capstone Project', 'ISYS2132', 1),
(3, 43, 'Business Computing', 'ISYS2109', 0),
(5, 42, 'Internet for Business', 'ISYS2110', 0),
(12, NULL, 'E-Business Systems', 'INTE2435', 1),
(13, NULL, 'Bussiness Information System Analysis and Design 2', 'ISYS2117', 0),
(14, 3, 'Business Info Systems Development 2', 'ISYS2119', 1),
(15, 3, 'The Business IS Professional', 'ISYS3295', 0),
(22, 43, 'Intro to BIS Development', 'ISYS2115', 1),
(18, NULL, 'Client Management', 'COMM2384', 1),
(19, NULL, 'Asian Cyber Culture', 'COMM2383', 0),
(32, NULL, 'Visual Basic', 'ISYS2116', 0),
(21, NULL, 'Marketing', 'MKTG1205', 1),
(23, NULL, 'Networking in Business', 'INTE2432', 0),
(31, NULL, ' Database Fundamental Demo', 'ISYS2222', 0),
(30, 82, 'BIS Capstone Project Demo', 'ISYS2111', 1),
(33, NULL, 'Business Info System Development 1', 'ISYS2116', 0),
(34, NULL, 'Business Database 2', 'ISYS2423', 0),
(35, 53, 'Internet for Business Saigon', 'ISYS2110S', 0),
(36, 82, 'Test Course', 'ISYS2113', 0),
(37, 82, 'ERP Systems', 'ISYS2426', 1);

-- --------------------------------------------------------

--
-- Table structure for table `OKMS_POST`
--

CREATE TABLE IF NOT EXISTS `OKMS_POST` (
  `Post_ID` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Post ID',
  `User_ID` int(10) unsigned NOT NULL COMMENT 'User ID',
  `Course_ID` int(10) unsigned NOT NULL COMMENT 'Course ID',
  `Repost_ID` int(10) unsigned DEFAULT NULL COMMENT 'Repost ID',
  `Post_Week` int(2) NOT NULL COMMENT 'Week',
  `Post_Title` varchar(255) NOT NULL DEFAULT '' COMMENT 'Page Title',
  `Post_URL` varchar(60) NOT NULL COMMENT 'Page URL Alias',
  `Post_Question` text NOT NULL COMMENT 'Page Body',
  `Post_Answer` text NOT NULL COMMENT 'Answer',
  `Post_Hide_Name` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Hide username',
  `Post_Current` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Current Semester?',
  `Post_Created` int(11) NOT NULL DEFAULT '0' COMMENT 'Date created timestamp',
  `Post_Edited` int(11) NOT NULL DEFAULT '0' COMMENT 'Date updated timestamp',
  PRIMARY KEY (`Post_ID`),
  FULLTEXT KEY `SEARCH` (`Post_Title`,`Post_Question`,`Post_Answer`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=154 ;

--
-- Dumping data for table `OKMS_POST`
--

INSERT INTO `OKMS_POST` (`Post_ID`, `User_ID`, `Course_ID`, `Repost_ID`, `Post_Week`, `Post_Title`, `Post_URL`, `Post_Question`, `Post_Answer`, `Post_Hide_Name`, `Post_Current`, `Post_Created`, `Post_Edited`) VALUES
(9, 3, 5, NULL, 8, 'Competitive Forces', 'competitive-forces', 'I would like to ask about the Competitive Forces in Micro environment. Are we supposed to follow the original Porter''s five forces, or the one that has been grouped into buy-side, sell-side and competitive threats in the textbook?', 'Please follow the one in the textbook.', 0, 1, 1344565551, 0),
(48, 3, 1, 48, 10, 'New question', 'new-question', 'When will the final presentation be?\r\nI would like to see it right now', 'Week 12', 0, 1, 1345704379, 1346223897),
(55, 11, 1, 55, 10, 'Why do you need to do Capstone Project?', 'why-do-you-need-to-do-capstone-project', 'Why do you need to do Capstone Project?', '1', 1, 1, 1345781998, 1345800324),
(53, 10, 2, NULL, 10, 'Unknown unknowns', 'unknown-unknowns', 'What is relational table?', '', 0, 1, 1345778067, 0),
(54, 10, 2, NULL, 10, 'Known unknowns', 'known-unknowns', 'What is Known unknowns?', '', 1, 1, 1345779432, 0),
(10, 3, 5, NULL, 5, 'benchmark in marketplace analysis', 'benchmark-in-marketplace-analysis', 'I would like to ask you about the benchmark in marketplace analysis. Could you provide me some information? What I should include in that? Thank you.', 'That means when you are doing competitor analysis, the benchmarks (used to evaluate your competitors) need to be identified from your industry sector''s best practice. You also require to look beyond its industry sector at what leading companies such as Amazon are doing. For example, a company in the financial services industry could look at google and yahoo to see if there are any lessons to learn on ways to make information pro-vision easier.', 0, 1, 1344565641, 0),
(11, 3, 5, NULL, 5, 'start up company', 'start-up-company', 'Hello, Nelson Leung, my team is doing a business plan on a start up company. Our company ''s business is to provide the site where people (Ex: Vietnamnese and foreigners) can come to search for the suitable transportation tickets (transport by car s, bus or train) with the best price. We will provide the searching as well as the comparing function where the visitors can come and look up the price between different transportation company (such as Phuong Trang, Mai Linh) and they can also buy the ticket online on our site. We will in turn earn the commission on each ticket we sold. \r\nI have 2 questions regarding this business: 1) what are our products or services? ( Is it the comparision services we provide on the site or is it the transportation ticket itself? 2) Who are our competitiors? because we couldnt find any company doing the same things as us? (is it possible that we do not have competitors?', '1) An commercial organization can provide products and services at the same time e.g. in RMIT, the primary business nature is to provide education service but at the same time RMIT also sells products like coffee mugs (with RMIT logo), Raincoat and so on. The same also happens in hair salon. ‎2) You don''t need to to find competitors that offer exactly the same service and product as your organization. Instead you can find competitors that offer product/service that are similar to your organization. Let''s use your business as an example. Your company offers online bus, train and car booking services, then probably website like these are your competitors: http://www.vietnamrailways.com/?clid=CKDi8J7jq7ECFcZV4god4wUA1g http://vinasuntravel.com/modules/car.php?l=1 Your website also offer comparison services, then you may consider https://www.roadhop.com/ as one of your competitors.', 0, 1, 1344565785, 1346229319),
(12, 3, 5, 12, 4, 'SLEPT', 'slept', 'Can anyone explain for me about the SLEPT (external) section in the proposal? Our group plan to sell product online, so in this section, should i write about how these factors (Social, legal, economical,... ) will affect my company''s decision? I''m kinda dunt know how to write in this section', 'I can give you one example: for legal factor, Vietnam government has implemented eCommerce laws and policies but these laws and policies haven''t been strictly enforced by the government (provide reference). As a result, Vietnamese tend not to shop online because their rights are not well protected', 0, 1, 1344565962, 0),
(13, 3, 5, 13, 3, 'Company URL', 'company-url', 'Can you explain the section Company URL in the Proposal? What exactly does it mean?', 'Find a suitable URL for your company. In P.140 of your book, it states that "It is important that companies define a URL strategy which will help customers or partners find relevant parts of the site containing references to specific produc...', 0, 1, 1344566010, 0),
(14, 3, 5, 14, 1, 'HTML and CSS exercise', 'html-and-css-exercise', 'I want to practice HTML and CSS exercise but I cannot open Dreamweaver in lab room on level 1. Is this software only be usable in lab room on level 2?', ' I do believe dreamweaver is installed in every lab. If dreamweaver doesn''t work in one machine, you may try another machine. Please email your tutor if you find the application is not working in a certain lab. Thanks very much.', 0, 1, 1344566329, 0),
(59, 3, 13, NULL, 10, 'BAD 2', 'bad-2', 'This is the first question for BAD 2', 'Hello Student belongs to this course', 0, 1, 1345866370, 0),
(77, 33, 2, NULL, 10, 'Course content', 'course-content', 'Is this course hard to learn?', '', 0, 1, 1345882500, 0),
(78, 10, 2, 78, 10, 'test Rating', 'test-rating', 'This is for rating test\r\n\r\nBo has edited this.', '', 0, 1, 1345993023, 1346223047),
(79, 2, 2, NULL, 11, 'What is Project Quality Management', 'what-is-project-quality-management', 'What is Project Quality Management?', '', 0, 1, 1346153960, 1346234028),
(108, 7, 1, 108, 7, 'Questions', 'questions-0-0', 'Questions on Week 5', '', 0, 0, 1346662314, 0),
(16, 3, 8, 16, 6, 'Technology Roadmap', 'technology-roadmap', 'What is the purpose of a technology roadmap? What the (2) main objectives when creating a technology road map for an organization?  ', '"Develop to support business vision, define direction for the business\n2 main objective:\n- support business vision, strategy and objective\n- A framework to integrate among many solution to reach the needs of enterprise"', 0, 1, 1344664024, 0),
(65, 29, 2, NULL, 10, 'Urgent Exam Coming', 'urgent-exam-coming', 'what are the exam questions ? ', '', 0, 1, 1345871375, 0),
(18, 6, 2, NULL, 5, 'Quality Plan', 'quality-plan', 'What is 5 elements of Quality Plan\r\n1234', '5 elements of quality plan are.........\r\n1234', 0, 1, 1344667115, 1345800368),
(19, 3, 1, 19, 4, 'Phase 3 Deliverable', 'phase-3-deliverable', 'What are elements we have to submit at phase 3?', 'Some elements you need to submit is......', 0, 0, 1344670089, 0),
(27, 3, 8, 16, 6, 'Technology Roadmap', 'technology-roadmap-0', 'What is the purpose of a technology roadmap? What the (2) main objectives when creating a technology road map for an organization?  ', '''Develop to support business vision, define direction for the business\r\n2 main objective:\r\n- support business vision, strategy and objective\r\n- A framework to integrate among many solution to reach the needs of enterprise''', 0, 1, 1344780504, 0),
(41, 0, 2, 41, 1, '5658', '5658', '"There can be no difference anywhere that doesn''t make a difference elsewhere." William James\n\nCould ', '', 0, 1, 1345268086, 0),
(58, 3, 12, NULL, 10, 'First Quetion', 'first-quetion', 'This is the first question for E Business System', 'Hello World', 0, 1, 1345866319, 0),
(36, 2, 2, NULL, 9, 'How can I use RACI', 'how-can-i-use-raci', 'Can you explain more about the RACI in the matrix-based charts', '', 0, 1, 1344944771, 0),
(37, 2, 2, NULL, 9, 'Responsibility Assignment Matrix', 'responsibility-assignment-matrix', 'Can one party be in charge of many roles?', '', 0, 1, 1344944992, 0),
(38, 2, 2, NULL, 9, 'What wrong with Monte Carlo Simulation', 'what-wrong-with-monte-carlo-simulation', 'What is wrong with Monte Carlo Simulation in Risk Knowledge Area?', '', 0, 1, 1344945256, 0),
(39, 2, 2, NULL, 9, 'What are unknown unknowns', 'what-are-unknown-unknowns', 'What is the difference between between known unknowns and unknown unknowns?', '', 0, 1, 1344947660, 0),
(42, 0, 2, 42, 9, 'Random trivial question', 'random-trivial-question', '"There can be no difference anywhere that doesn''t make a difference elsewhere." William James\n\nCould anyone please explain this saying to me? What does it mean?', '', 0, 1, 1345268167, 0),
(60, 8, 2, NULL, 10, 'Assignment 3 question', 'assignment-3-question', 'Hi Melina, \n\nCan you extend the deadline for Assignment 3? Say - Week 12.', '', 0, 1, 1345866756, 0),
(61, 21, 12, NULL, 10, 'E-commerce', 'e-commerce', 'What is e-commerce ?', '', 0, 1, 1345867207, 0),
(62, 24, 1, NULL, 10, 'question', 'question123213', 'Hello, is this me....', '', 0, 1, 1345871146, 1346214716),
(63, 24, 1, NULL, 10, 'Hellohoo', 'hellohoo', 'Hahah', '', 1, 1, 1345871207, 1346214708),
(64, 25, 8, NULL, 10, 'Report Length', 'report-length', 'What is the word limit for the report assignment?', '', 0, 1, 1345871350, 0),
(66, 27, 8, NULL, 10, 'How to play a girl in 1 hour?', 'how-to-play-a-girl-in-1-hour', 'How to play a girl in 1 hour?', '', 1, 1, 1345871582, 0),
(67, 27, 8, NULL, 10, 'How to khai dao?', 'how-to-khai-dao', 'How to khai dao?', '', 1, 1, 1345871668, 0),
(68, 27, 8, NULL, 10, 'Who am i?', 'who-am-i', 'Who am i?', '', 1, 1, 1345871789, 0),
(69, 30, 2, NULL, 10, 'efsdfgsdfsdfsdf', 'efsdfgsdfsdfsdf', 'Tinasdasdasd', 'Answer\r\n11111111', 0, 1, 1345871834, 1345871887),
(70, 27, 8, NULL, 10, 'an o nhu the nao?', 'an-o-nhu-the-nao', 'an o nhu the nao?', '', 0, 1, 1345871859, 0),
(71, 28, 8, NULL, 10, 'testing', 'testing', 'Doi qua cac ban oi', '', 0, 1, 1345871950, 0),
(72, 26, 2, NULL, 9, 'Mr', 'mr', 'test without "hide your user from others"\n', '', 0, 1, 1345871987, 0),
(73, 26, 2, NULL, 10, 'test', 'test', 'test with "hide your username from others"', '', 1, 1, 1345872033, 0),
(74, 30, 2, NULL, 10, 'sdfsdgfsdg', 'sdfsdgfsdg', 'dsfsdgsdf', '', 0, 1, 1345872646, 0),
(75, 31, 14, NULL, 10, 'nhung', 'dksjflffdsfsd', 'jghkijkljjl;', 'fgdf', 1, 1, 1345877258, 1345878029),
(112, 3, 1, NULL, 12, 'Welcome', 'welcome', 'Welcome all to ISYS2132 BIS Capstone Project', '', 0, 1, 1346832827, 0),
(113, 63, 1, 113, 12, 'More documentations for Capstone', 'more-documentations-for-capstone', '[3:06:32 PM] Lâm Trần: During the project, our team and client had agreed on several changed requests. If we do not submit these documents then how do you keep track of what has been changed? Then we don''t have any document to tell what should we do in next step neither. What do you think?', '', 0, 0, 1346833079, 0),
(85, 0, 2, NULL, 11, 'Fill in the blank problem', 'fill-in-the-blank-problem', 'The key, the whole key, nothing but the _____ \nWhat should be in the blank guys?', '', 0, 1, 1346221782, 0),
(88, 3, 18, NULL, 11, 'this is the sample post for Client Management', 'this-is-the-sample-post-for-client-management', 'Sample question', 'Sample answer', 0, 1, 1346223835, 0),
(87, 3, 1, 48, 10, 'New question', 'new-question-0', 'When will the final presentation be?', 'Week 12', 0, 1, 1346223114, 0),
(89, 3, 19, NULL, 11, 'This is a sample for Asian Cyber Culture', 'this-is-a-sample-for-asian-cyber-culture', 'Sample test for ACyber Culture', 'Sample test for A Cyber Culter', 0, 1, 1346223869, 0),
(91, 39, 18, 91, 11, 'ABC', 'abc', 'XYZ', '', 1, 0, 1346224505, 0),
(110, 7, 1, 110, 7, 'Questions', 'questions-0-0-0', 'Questions on Week 5', '', 0, 0, 1346747568, 0),
(111, 7, 1, 111, 7, 'Questions', 'questions-0-0-0-0', 'Questions on Week 5', '', 0, 0, 1346747619, 0),
(95, 43, 22, 95, 1, 'what is vb?', 'what-is-vb', 'what is vb?', '', 0, 0, 1346227039, 1346227094),
(96, 43, 22, 96, 1, 'what is vb?', 'what-is-vb-0', 'what is vb?', '', 0, 0, 1346227213, 0),
(97, 43, 22, 97, 11, 'Who is Huy', 'who-is-huy', 'Who am I?', '', 0, 0, 1346227350, 0),
(98, 43, 22, 98, 11, 'Who is Huy', 'who-is-huy-0', 'Who am I?', '', 0, 0, 1346227512, 0),
(100, 3, 5, 100, 4, 'SLEPT', 'slept-0', 'Can anyone explain for me about the SLEPT (external) section in the proposal? Our group plan to sell product online, so in this section, should i write about how these factors (Social, legal, economical,... ) will affect my company''s decision? I''m kinda dunt know how to write in this section', 'I can give you one example: for legal factor, Vietnam government has implemented eCommerce laws and policies but these laws and policies haven''t been strictly enforced by the government (provide reference). As a result, Vietnamese tend not to shop online because their rights are not well protected', 0, 0, 1346229796, 0),
(101, 3, 5, 101, 4, 'SLEPT', 'slept-0', 'Can anyone explain for me about the SLEPT (external) section in the proposal? Our group plan to sell product online, so in this section, should i write about how these factors (Social, legal, economical,... ) will affect my company''s decision? I''m kinda dunt know how to write in this section', 'I can give you one example: for legal factor, Vietnam government has implemented eCommerce laws and policies but these laws and policies haven''t been strictly enforced by the government (provide reference). As a result, Vietnamese tend not to shop online because their rights are not well protected', 0, 0, 1346230066, 0),
(104, 48, 14, 104, 11, 'HL khung', 'hl-khung', 'HL co bi khung ko?', '', 0, 0, 1346232612, 0),
(105, 7, 1, 105, 7, 'Questions', 'questions', 'Questions on Week 5', '', 0, 0, 1346318299, 1346318544),
(106, 3, 1, 106, 4, 'Phase 3 Deliverable', 'phase-3-deliverable-0', 'What are elements we have to submit at phase 3?', 'Some elements you need to submit is......', 0, 0, 1346318486, 0),
(107, 7, 1, 107, 7, 'Questions', 'questions-0', 'Questions on Week 5', '', 0, 0, 1346318621, 0),
(128, 82, 31, 126, 12, 'Welcome ISYS2222', 'welcome-isys2222-1', 'Welcome students', '', 0, 1, 1346985131, 0),
(124, 82, 30, NULL, 12, 'Welcome to ISYS2111', 'welcome-to-isys2111', 'Welcome student to the demonstration of BIS Capstone Project', '', 0, 1, 1346919420, 0),
(125, 83, 30, NULL, 12, 'Hello ISYS2111', 'hello-isys2111', 'Hello, this is a post of student in ISYS2111', '', 0, 1, 1346919571, 0),
(126, 82, 31, 126, 12, 'Welcome ISYS2222', 'welcome-isys2222', 'Welcome students', '', 0, 1, 1346919784, 0),
(127, 82, 31, 126, 12, 'Welcome ISYS2222', 'welcome-isys2222-0', 'Welcome students', '', 0, 1, 1346920103, 0),
(119, 7, 1, NULL, 12, 'This is a subject', 'this-is-a-subject', 'This is a question', 'This is an answer', 0, 1, 1346853299, 0),
(129, 2, 2, NULL, 12, 'Hello ISYS2131', 'hello-isys2131', 'Hello', '', 1, 1, 1346986689, 0),
(131, 82, 30, 131, 10, 'New question', 'new-question', 'When will the final presentation be?', 'Week 12', 0, 1, 1347273796, 1350034930),
(134, 2, 2, NULL, 12, 'What is a relational table', 'what-is-a-relational-table', 'What is it?', '', 0, 1, 1347523074, 0),
(136, 43, 22, 98, 11, 'Who is Huy', 'who-is-huy-0-0', 'Who am I?', '', 0, 1, 1347605550, 0),
(137, 82, 34, 137, 12, 'Database Concept 2', 'database-concept-2', 'What is a Database?', 'Database contains a collection of logically related data', 0, 1, 1347689560, 0),
(145, 2, 8, NULL, 1, 'Case study', 'case-study', 'What are the case studies of this semester?', '', 0, 1, 1350816165, 0),
(146, 2, 2, NULL, 1, 'Homework', 'homework', 'What is the homework for this week?', '', 0, 1, 1350816298, 0),
(153, 2, 1, NULL, 12, 'What is OKMS?', 'what-is-okms', 'What is OKMS?', '', 0, 1, 1359879829, 0),
(148, 2, 2, NULL, 3, 'hello world', 'hello-world', 'hi', '', 0, 1, 1352006824, 0),
(151, 82, 1, 113, 12, 'More documentations for Capstone', 'more-documentations-for-capstone-0', '[3:06:32 PM] Lâm Trần: During the project, our team and client had agreed on several changed requests. If we do not submit these documents then how do you keep track of what has been changed? Then we don\\''t have any document to tell what should we do in next step neither. What do you think?', '', 0, 1, 1353312511, 0);

-- --------------------------------------------------------

--
-- Table structure for table `OKMS_POST_FOLLOW`
--

CREATE TABLE IF NOT EXISTS `OKMS_POST_FOLLOW` (
  `User_ID` int(10) unsigned NOT NULL COMMENT 'User ID',
  `Post_ID` int(10) unsigned NOT NULL COMMENT 'Post ID',
  KEY `User_ID` (`User_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `OKMS_POST_FOLLOW`
--

INSERT INTO `OKMS_POST_FOLLOW` (`User_ID`, `Post_ID`) VALUES
(10, 14),
(10, 13),
(8, 17),
(10, 38),
(10, 37),
(10, 36),
(10, 27),
(3, 55),
(30, 60),
(25, 16),
(29, 67),
(28, 71),
(30, 73),
(30, 69),
(26, 69),
(32, 75),
(32, 76),
(33, 69),
(2, 77),
(2, 72),
(2, 132),
(4, 59),
(2, 78),
(3, 79),
(41, 92),
(40, 93),
(47, 14),
(47, 11),
(47, 99),
(48, 104),
(2, 107),
(50, 104),
(2, 106),
(63, 111),
(10, 113),
(10, 114),
(79, 114),
(2, 119),
(81, 120),
(81, 122),
(2, 123),
(83, 124),
(83, 126),
(2, 11),
(2, 138),
(2, 112),
(2, 87),
(2, 63),
(2, 62),
(2, 55),
(2, 48),
(2, 134),
(2, 79),
(2, 39),
(2, 38),
(2, 37),
(2, 36),
(2, 146),
(2, 145),
(2, 147),
(2, 148),
(2, 151),
(2, 153),
(2, 85);

-- --------------------------------------------------------

--
-- Table structure for table `OKMS_POST_RATE`
--

CREATE TABLE IF NOT EXISTS `OKMS_POST_RATE` (
  `User_ID` int(10) unsigned NOT NULL COMMENT 'User ID',
  `Post_ID` int(10) unsigned NOT NULL COMMENT 'Post ID',
  `PostRate` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Rate Token',
  KEY `User_ID` (`User_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `OKMS_POST_RATE`
--

INSERT INTO `OKMS_POST_RATE` (`User_ID`, `Post_ID`, `PostRate`) VALUES
(28, 68, 3),
(28, 68, 3),
(28, 68, 3),
(28, 68, 3),
(28, 68, 3),
(28, 70, 4),
(28, 68, 4),
(28, 70, 4),
(28, 70, 4),
(28, 70, 4),
(28, 70, 4),
(30, 65, 5),
(30, 66, 4),
(25, 71, 4),
(30, 67, 4),
(30, 68, 4),
(30, 69, 4),
(30, 70, 4),
(30, 71, 5),
(30, 72, 5),
(29, 70, 5),
(28, 71, 3),
(29, 67, 3),
(27, 67, 5),
(24, 63, 4),
(27, 66, 5),
(25, 16, 3),
(25, 64, 3),
(25, 27, 3),
(22, 55, 1),
(22, 55, 3),
(21, 61, 3),
(22, 60, 4),
(8, 60, 5),
(8, 54, 5),
(10, 53, 5),
(10, 54, 5),
(10, 55, 2),
(3, 13, 3),
(3, 13, 3),
(3, 14, 4),
(6, 42, 4),
(6, 53, 2),
(3, 52, 1),
(6, 54, 1),
(2, 43, 1),
(28, 68, 3),
(28, 70, 4),
(28, 70, 4),
(28, 70, 4),
(28, 67, 4),
(30, 73, 4),
(30, 64, 3),
(26, 69, 3),
(31, 75, 3),
(33, 77, 4),
(33, 65, 4),
(33, 69, 5),
(2, 77, 3),
(2, 55, 3),
(2, 74, 4),
(2, 67, 5),
(2, 66, 3),
(2, 65, 3),
(10, 77, 2),
(10, 74, 5),
(2, 78, 5),
(10, 78, 1),
(2, 72, 3),
(2, 73, 5),
(2, 60, 5),
(2, 69, 1),
(2, 71, 2),
(8, 55, 3),
(2, 70, 5),
(8, 78, 4),
(3, 76, 5),
(3, 75, 5),
(3, 74, 1),
(3, 73, 1),
(3, 72, 1),
(3, 71, 1),
(3, 70, 1),
(3, 69, 1),
(4, 59, 5),
(2, 79, 5),
(3, 77, 5),
(3, 79, 1),
(3, 68, 1),
(3, 83, 5),
(3, 82, 2),
(35, 84, 4),
(35, 82, 3),
(16, 77, 5),
(37, 86, 5),
(37, 87, 3),
(46, 14, 4),
(46, 14, 5),
(48, 104, 1),
(2, 101, 5),
(4, 87, 5),
(3, 104, 5),
(7, 105, 3),
(3, 107, 5),
(3, 106, 5),
(3, 98, 2),
(3, 91, 5),
(4, 111, 4),
(3, 111, 3),
(10, 115, 5),
(2, 119, 5),
(2, 118, 5),
(2, 116, 4),
(2, 112, 4),
(81, 120, 4),
(81, 122, 5),
(83, 125, 4),
(82, 127, 1),
(82, 125, 1),
(82, 124, 1),
(2, 14, 1),
(2, 12, 5),
(2, 11, 5),
(2, 41, 3),
(2, 13, 5),
(2, 129, 5),
(82, 128, 5),
(2, 85, 5),
(2, 9, 5),
(2, 132, 5),
(2, 134, 4),
(82, 137, 5),
(2, 10, 5),
(2, 138, 1),
(2, 62, 5),
(2, 148, 5),
(82, 131, 5),
(82, 119, 2),
(2, 151, 5),
(2, 145, 5),
(2, 146, 3),
(2, 87, 2),
(6, 61, 5),
(6, 58, 1);

-- --------------------------------------------------------

--
-- Table structure for table `OKMS_POST_VOTE`
--

CREATE TABLE IF NOT EXISTS `OKMS_POST_VOTE` (
  `User_ID` int(10) unsigned NOT NULL COMMENT 'User ID',
  `Post_ID` int(10) unsigned NOT NULL COMMENT 'Post ID',
  `PostVote_Like` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Like Token',
  `PostVote_Dislike` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Dislike Token',
  KEY `User_ID` (`User_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `OKMS_POST_VOTE`
--

INSERT INTO `OKMS_POST_VOTE` (`User_ID`, `Post_ID`, `PostVote_Like`, `PostVote_Dislike`) VALUES
(3, 13, 0, 0),
(3, 14, 0, 0),
(3, 12, 1, 0),
(10, 17, 0, 1),
(8, 17, 0, 1),
(8, 18, 0, 0),
(10, 22, 0, 1),
(1, 24, 0, 0),
(3, 28, 1, 0),
(3, 22, 1, 0),
(3, 23, 1, 0),
(3, 27, 1, 0),
(2, 43, 0, 0),
(8, 39, 1, 0),
(21, 61, 1, 0),
(8, 37, 1, 0),
(8, 36, 1, 0),
(8, 38, 1, 0),
(3, 55, 1, 0),
(6, 54, 0, 0),
(11, 39, 1, 0),
(21, 59, 1, 0),
(3, 61, 0, 1),
(22, 55, 1, 0),
(25, 27, 0, 0),
(24, 63, 0, 0),
(25, 16, 0, 0),
(27, 66, 1, 0),
(27, 67, 1, 0),
(30, 60, 1, 0),
(29, 67, 1, 0),
(28, 71, 1, 0),
(26, 69, 1, 0),
(30, 69, 0, 1),
(30, 73, 1, 0),
(31, 75, 0, 0),
(32, 76, 0, 1),
(32, 75, 0, 1),
(33, 77, 0, 1),
(2, 77, 1, 0),
(2, 36, 1, 0),
(2, 37, 1, 0),
(2, 38, 1, 0),
(2, 71, 1, 0),
(2, 78, 1, 0),
(8, 78, 1, 0),
(10, 78, 0, 1),
(2, 72, 1, 0),
(4, 71, 1, 0),
(3, 78, 1, 0),
(2, 79, 1, 0),
(2, 73, 1, 0),
(2, 70, 1, 0),
(8, 79, 0, 1),
(35, 84, 0, 0),
(35, 82, 0, 0),
(37, 86, 0, 1),
(37, 87, 1, 0),
(41, 92, 1, 0),
(40, 93, 1, 0),
(46, 14, 1, 0),
(47, 14, 0, 0),
(47, 99, 1, 0),
(46, 100, 1, 0),
(48, 104, 1, 0),
(3, 104, 0, 0),
(7, 105, 0, 0),
(7, 107, 1, 0),
(2, 107, 1, 0),
(3, 107, 1, 0),
(2, 106, 1, 0),
(3, 106, 1, 0),
(7, 106, 1, 0),
(63, 111, 0, 1),
(3, 112, 0, 0),
(10, 114, 0, 0),
(2, 119, 1, 0),
(2, 118, 1, 0),
(2, 87, 1, 0),
(81, 120, 0, 1),
(81, 122, 0, 1),
(2, 123, 0, 1),
(82, 124, 1, 0),
(83, 124, 1, 0),
(83, 126, 0, 1),
(82, 127, 1, 0),
(2, 14, 1, 0),
(2, 11, 1, 0),
(2, 41, 1, 0),
(2, 10, 1, 0),
(2, 13, 1, 0),
(2, 129, 1, 0),
(82, 125, 1, 0),
(82, 129, 0, 0),
(82, 128, 1, 0),
(2, 85, 1, 0),
(2, 132, 1, 0),
(4, 132, 1, 0),
(2, 134, 1, 0),
(82, 137, 1, 0),
(2, 74, 1, 0),
(2, 69, 1, 0),
(2, 9, 1, 0),
(2, 12, 1, 0),
(2, 138, 1, 0),
(2, 112, 1, 0),
(2, 63, 1, 0),
(2, 62, 1, 0),
(2, 55, 1, 0),
(2, 48, 1, 0),
(2, 27, 1, 0),
(2, 18, 1, 0),
(2, 16, 1, 0),
(2, 64, 1, 0),
(2, 65, 1, 0),
(2, 66, 1, 0),
(2, 67, 1, 0),
(2, 68, 1, 0),
(2, 54, 1, 0),
(2, 60, 1, 0),
(2, 53, 1, 0),
(2, 42, 1, 0),
(2, 39, 1, 0),
(4, 136, 1, 0),
(4, 67, 0, 1),
(2, 128, 1, 0),
(2, 148, 1, 0),
(82, 131, 0, 1),
(2, 151, 1, 0),
(2, 145, 1, 0),
(6, 61, 1, 0),
(6, 58, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `OKMS_ROLE`
--

CREATE TABLE IF NOT EXISTS `OKMS_ROLE` (
  `Role_ID` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Role ID',
  `Role_Name` varchar(60) NOT NULL DEFAULT '' COMMENT 'Role Name',
  PRIMARY KEY (`Role_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `OKMS_ROLE`
--

INSERT INTO `OKMS_ROLE` (`Role_ID`, `Role_Name`) VALUES
(1, 'System Admin'),
(2, 'Student'),
(3, 'Lecturer');

-- --------------------------------------------------------

--
-- Table structure for table `OKMS_SEMESTER`
--

CREATE TABLE IF NOT EXISTS `OKMS_SEMESTER` (
  `Semester_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Semester_Code` varchar(255) NOT NULL,
  `Semester_Start_Date` int(11) NOT NULL DEFAULT '0',
  `Semester_End_Date` int(11) NOT NULL DEFAULT '0',
  `Semester_Current` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Semester_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `OKMS_SEMESTER`
--

INSERT INTO `OKMS_SEMESTER` (`Semester_ID`, `Semester_Code`, `Semester_Start_Date`, `Semester_End_Date`, `Semester_Current`) VALUES
(1, '2012B', 1339952400, 1349629200, 0),
(2, '2012A', 1329670800, 1339347600, 0),
(3, '2012C', 1350234000, 1359910800, 1);

-- --------------------------------------------------------

--
-- Table structure for table `OKMS_USER`
--

CREATE TABLE IF NOT EXISTS `OKMS_USER` (
  `User_ID` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'User ID',
  `Role_ID` int(10) unsigned NOT NULL COMMENT 'Role ID',
  `User_Alias` varchar(32) DEFAULT NULL COMMENT 'User Alias',
  `User_Username` varchar(60) NOT NULL DEFAULT '' COMMENT 'User Name',
  `User_Fullname` varchar(255) NOT NULL DEFAULT '' COMMENT 'Fullname',
  `User_Password` varchar(32) NOT NULL DEFAULT '' COMMENT 'Password',
  `User_Mail` varchar(64) DEFAULT NULL COMMENT 'Email',
  `User_Created` int(11) NOT NULL DEFAULT '0' COMMENT 'Date created timestamp',
  `User_Hash` varchar(32) NOT NULL COMMENT 'Verify email hash',
  `User_Status` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'User status',
  PRIMARY KEY (`User_ID`),
  UNIQUE KEY `User_Username` (`User_Username`),
  UNIQUE KEY `User_Mail` (`User_Mail`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=88 ;

--
-- Dumping data for table `OKMS_USER`
--

INSERT INTO `OKMS_USER` (`User_ID`, `Role_ID`, `User_Alias`, `User_Username`, `User_Fullname`, `User_Password`, `User_Mail`, `User_Created`, `User_Hash`, `User_Status`) VALUES
(1, 1, NULL, 'admin', 'System Administrator', 'df67be76ff44a34799a1616443d56776', 'tnlam.vietnam@gmail.com', 0, '', 1),
(2, 2, '', 's3230273', 'Tung Pham', 'c191665ed8a5db5399dc852f4b5e7006', 's3230273@rmit.edu.vn', 1344503528, 'fe9fc289c3ff0af142b6d3bead98a923', 1),
(83, 2, '', 's3246094', 'Nguyen Tran', 'e10adc3949ba59abbe56e057f20f883e', 's3246094@rmit.edu.vn', 1346919120, 'f4be00279ee2e0a53eafdaa94a151e2c', 1),
(4, 3, 'hiep.pham', 'v80601', 'Hiep Pham', 'e10adc3949ba59abbe56e057f20f883e', 'hiep.pham@rmit.edu.vn', 0, '', 1),
(85, 2, '', 's3246091', 'Ly Thi Gia Ngoc', 'e10adc3949ba59abbe56e057f20f883e', 's3246091@rmit.edu.vn', 1347706039, '67f7fb873eaf29526a11a9b7ac33bfac', 1),
(6, 3, 'melina.silva', 'v10652', 'Melina Silva', 'e10adc3949ba59abbe56e057f20f883e', 'melina.silva@rmit.edu.vn', 0, '', 1),
(7, 3, 'nicole.tsang', 'v90205', 'Nicole Tsang', 'e10adc3949ba59abbe56e057f20f883e', 'nicole.tsang@rmit.edu.vn', 0, '', 1),
(9, 3, 'rupan.das', 'v90206', 'Rupan Kanti Das', 'e10adc3949ba59abbe56e057f20f883e', 'rupan.das@rmit.edu.vn', 0, '', 1),
(87, 2, '', 's3298783', 'Vi Thao Le', '042fdac0dbbf91dcb122f7aca394658d', 's3298783@rmit.edu.vn', 1352172114, '67f7fb873eaf29526a11a9b7ac33bfac', 1),
(11, 2, '', 's3230356', 'Do The Quoc', 'e10adc3949ba59abbe56e057f20f883e', 's3230356@rmit.edu.vn', 1344666340, 'c8fbbc86abe8bd6a5eb6a3b4d0411301', 1),
(19, 2, '', 's3210018', 'Vu Hong Bao Chau', 'dc4c8e6e87d9b36b9ece85596b2e3496', 's3210018@rmit.edu.vn', 1345864399, 'faa9afea49ef2ff029a833cccc778fd0', 1),
(82, 3, 'nelson.leung', 'v80212', 'Nelson Leung', 'e10adc3949ba59abbe56e057f20f883e', 'nelson.leung@rmit.edu.vn', 1346919075, '5fd0b37cd7dbbb00f97ba6ce92bf5add', 1),
(20, 2, '', 's3221642', 'Nguyen Dang Thanh Hung', '0665e102caf221e2fa7944b8195a9c03', 's3221642@rmit.edu.vn', 1345865258, '6855456e2fe46a9d49d3d3af4f57443d', 1),
(21, 2, '', 's3255121', 'Nguyen Thi Anh Ngoc', '406d047725fab4fd9270a3edb640a645', 's3255121@student.rmit.edu.au', 1345866106, 'd79aac075930c83c2f1e369a511148fe', 1),
(22, 2, '', 's3246044', 'Doan Quoc Anh', '7236bab9a8b051e207841f9e91253e8a', 's3246044@rmit.edu.vn', 1345866425, '37a749d808e46495a8da1e5352d03cae', 1),
(30, 2, '', 's3221686', 'Tran Trung Tin', '21ef05aed5af92469a50b35623d52101', 's3221686@rmit.edu.vn', 1345871536, '26337353b7962f533d78c762373b3318', 1),
(24, 2, '', 's3255189', 'luan', '25f9e794323b453885f5181f1b624d0b', 's3255189@rmit.edu.vn', 1345870391, '692f93be8c7a41525c0baf2076aecfb4', 1),
(25, 2, '', 's3230472', 'Thao Nguyen', '1d1b82ed77f1722d7a855aa21292b95b', 's3230472@rmit.edu.vn', 1345870403, 'a02ffd91ece5e7efeb46db8f10a74059', 1),
(26, 2, '', 's3259249', 'Thinh', 'e10adc3949ba59abbe56e057f20f883e', 's3259249@rmit.edu.vn', 1345870410, '54229abfcfa5649e7003b83dd4755294', 1),
(27, 2, '', 's3246051', 'diep', '96e79218965eb72c92a549dd5a330112', 's3246051@rmit.edu.vn', 1345870765, '7cbbc409ec990f19c78c75bd1e06f215', 1),
(28, 2, '', 's3220677', 'ha truong', '827ccb0eea8a706c4c34a16891f84e7b', 's3220677@rmit.edu.vn', 1345870876, '74bba22728b6185eec06286af6bec36d', 1),
(29, 2, '', 's3220626', 'Nathan Nguyen', '63316dd4b2bb450d4548f129d6790af8', 's3220626@rmit.edu.vn', 1345870903, 'e5841df2166dd424a57127423d276bbe', 1),
(31, 2, '', 's3311304', 'Pham Thi Kieu Nhung', 'ab3b3feffb11bebbb91877a4f139d646', 's3311304@rmit.edu.vn', 1345876599, 'c4015b7f368e6b4871809f49debe0579', 1),
(32, 2, '', 's3342015', 'Vuu Ngoc My Linh', '93cb166133eb147550fc9ac00d036651', 's3342015@rmit.edu.vn', 1345876610, '06997f04a7db92466a2baa6ebc8b872d', 1),
(33, 2, '', 's3298821', 'Kate Huynh', '56cc74febb8fc82359bb9b76e8f104c3', 's3298821@rmit.edu.vn', 1345881835, '7c590f01490190db0ed02a5070e20f01', 1),
(35, 2, '', 's3258217', 'Tang Thuy Hong Minh', 'f3dbcaf5c7c822776069d588cb940e2b', 's3258217@rmit.edu.vn', 1346219958, 'd9d4f495e875a2e075a1a4a6e1b9770f', 1),
(37, 3, 'trung.phan', 'v10836', 'Phan Tan Nguyen Trung', 'df67be76ff44a34799a1616443d56776', 'v10836@rmit.edu.vn', 1346222606, '1c383cd30b7c298ab50293adfecb7b18', 1),
(39, 2, '', 's3246705', 'Nguyen Ngoc Hoang Vy', '3433e0a57814b9cc656e9555abe5081f', 's3246705@rmit.edu.vn', 1346223656, '437d7d1d97917cd627a34a6a0fb41136', 1),
(40, 2, '', 's3357896', 'Amy', 'df67be76ff44a34799a1616443d56776', 's3357896@rmit.edu.vn', 1346223967, '5487315b1286f907165907aa8fc96619', 1),
(41, 2, '', 's3373038', 'Phan Miunh Tri', '428a8ec34f53fdc863c9cb50a14384cf', 's3373038@rmit.edu.vn', 1346224046, '55743cc0393b1cb4b8b37d09ae48d097', 1),
(44, 3, 'irfan.ulhaq', 'v10651', 'irfan ulhaq', '79cfeb94595de33b3326c06ab1c7dbda', 'v10651@rmit.edu.vn', 1346227658, 'a8c88a0055f636e4a163a5e3d16adab7', 1),
(43, 3, 'huy.huynh', 'v80624', 'Huy Huynh', '827ccb0eea8a706c4c34a16891f84e7b', 'v80624@rmit.edu.vn', 1346225665, 'cfbce4c1d7c425baf21d6b6f2babe6be', 1),
(86, 2, '', 's3246063', 'Tran Ngoc Lam', '4297f44b13955235245b2497399d7a93', 's3246063@rmit.edu.vn', 1347959533, 'eda80a3d5b344bc40f3bc04f65b7a357', 1),
(46, 3, 'ashish.das', 'v90220', 'Cherry', 'e10adc3949ba59abbe56e057f20f883e', 'v90220@rmit.edu.vn', 1346227967, '24681928425f5a9133504de568f5f6df', 1),
(47, 3, 'narumon', 'v81011', 'Cherry Sriratanaviriyakul', 'df67be76ff44a34799a1616443d56776', 'v81011@rmit.edu.vn', 1346228353, 'e4bb4c5173c2ce17fd8fcd40041c068f', 1),
(48, 2, '', 's3325036', 'Tran Thi Thuy An', '1ef2d53c057013d9eda3f8d68456a8f3', 's3325036@rmit.edu.vn', 1346232086, '233509073ed3432027d48b1a83f5fbd2', 1),
(50, 3, '', 'highland.tran', 'Nguyen Highland', 'e10adc3949ba59abbe56e057f20f883e', 'highland.tran@gmail.com', 1346326971, 'e46de7e1bcaaced9a54f1e9d0d2f800d', 1),
(53, 3, '', 'tungpham42', 'Tung', '38717d78f2dada06b518b8438851024f', 'tung.42@gmail.com', 1346384382, '0ff39bbbf981ac0151d340c9aa40e63e', 1);

-- --------------------------------------------------------

--
-- Table structure for table `OKMS_USER_COURSE`
--

CREATE TABLE IF NOT EXISTS `OKMS_USER_COURSE` (
  `Course_ID` int(10) unsigned NOT NULL COMMENT 'Course ID',
  `User_ID` int(10) unsigned NOT NULL COMMENT 'User ID',
  KEY `Course_ID` (`Course_ID`),
  KEY `User_ID` (`User_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `OKMS_USER_COURSE`
--

INSERT INTO `OKMS_USER_COURSE` (`Course_ID`, `User_ID`) VALUES
(2, 6),
(8, 2),
(3, 4),
(1, 29),
(2, 2),
(5, 2),
(8, 4),
(35, 53),
(12, 4),
(5, 11),
(2, 11),
(31, 26),
(31, 48),
(13, 4),
(12, 21),
(13, 21),
(12, 6),
(8, 22),
(2, 22),
(8, 24),
(2, 26),
(2, 29),
(8, 25),
(8, 26),
(8, 27),
(8, 28),
(8, 29),
(8, 50),
(2, 30),
(8, 30),
(14, 9),
(15, 7),
(14, 31),
(14, 32),
(15, 31),
(15, 32),
(30, 82),
(2, 33),
(8, 33),
(3, 33),
(22, 4),
(22, 43),
(3, 43),
(31, 29),
(31, 17),
(8, 37),
(5, 37),
(1, 17),
(1, 2),
(31, 83),
(31, 82),
(18, 39),
(19, 39),
(30, 7),
(21, 40),
(21, 41),
(3, 44),
(3, 46),
(5, 44),
(5, 46),
(3, 47),
(5, 47),
(5, 43),
(5, 17),
(5, 25),
(1, 82),
(23, 48),
(14, 48),
(30, 83),
(1, 48),
(14, 50),
(15, 50),
(0, 19),
(1, 26),
(34, 82),
(36, 82),
(31, 2),
(1, 4),
(3, 6),
(37, 82),
(37, 87);

--
-- Table structure for table `OKMS_USER_FOLLOW`
--

CREATE TABLE IF NOT EXISTS `OKMS_USER_FOLLOW` (
  `User_ID` int(10) unsigned NOT NULL COMMENT 'User ID',
  `Followee_ID` int(10) unsigned NOT NULL COMMENT 'Followee ID',
  KEY `User_ID` (`User_ID`),
  KEY `Followee_ID` (`Followee_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `OKMS_USER_FOLLOW`
--
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
