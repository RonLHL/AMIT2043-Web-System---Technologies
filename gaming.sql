-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 12, 2024 at 08:32 PM
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
-- Database: `gaming`
--

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `event_id` varchar(10) NOT NULL,
  `event_name` varchar(50) NOT NULL,
  `event_desc` varchar(2000) NOT NULL,
  `event_img` varchar(50) NOT NULL,
  `event_venue` varchar(50) NOT NULL,
  `status` char(1) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `price` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`event_id`, `event_name`, `event_desc`, `event_img`, `event_venue`, `status`, `date`, `time`, `price`) VALUES
('E0001', 'LudoNarraCon 2024 (Online)', 'LudoNarraCon 2024 (Online) is a digital convention celebrating narrative video games, hosted on Steam. An initiative of indie label Fellow Traveller, it aims to create a platform to showcase and celebrate interesting and innovative narrative games, replicating as many of the aspects and benefits of the physical convention experience as possible within a digital format.', '6641043775d6e', 'Online', 'A', '2024-05-09', '20:00:00', 20),
('E0002', 'WasabiCon 2024', 'From WasabiCon: Starting in Jacksonville in 2012, WasabiCon is Northeast Florida’s largest and Jacksonville’s longest running pop culture convention. WasabiCon is a three-day event for fans of Asian pop culture, cosplay, and gaming.\r\n\r\nWasabiCon 2024 will take place at the Prime F. Osborn III Convention Center and will feature celebrity guests, video games, tabletop gaming, a car show, food trucks, and over 200 exhibitors.', '6640b796aeb68', 'Online', 'U', '2024-05-11', '17:00:00', 30),
('E0003', 'TactiCon 2024 (Online)', 'TactiCon 2024 is a digital convention hosted on Steam celebrating strategy video games. TactiCon highlights the brilliant and creative minds who develop strategy games and gathers the players who love them. Get Industry Updates and visit the Event Calendar for more events like...', '6640b9305fe7d', 'Online', 'U', '2024-05-15', '14:00:00', 25),
('E0004', 'IMPACT! Indie Games 2024', 'ALL DAY MICRO-CONFERENCE SET IN THE HEART OF BRISTOL\r\n\r\nTOPICS\r\n– Marketing Your Game\r\n– Securing a Publishing Deal\r\n– Growing Your Games Community\r\n– How to Succeed as an Independent Games Business', '6640c35f851e8', '10-12 Fairfax St Bristol, BsS1 3DB UK', 'A', '2024-05-23', '10:30:00', 60),
('E0005', 'Cerebral Puzzle Showcase 2024', 'Do you love puzzle games? Do you dabble every once in a while? Are you a puzzle novice curious to know if the genre is for you? Cerebral Puzzle Showcase was created to help you find your next favorite puzzle game! Visit the News Section for event news and the Event Calendar for Cerebral Puzzle Showcase was created to help you find your next favorite puzzle game!', '6640bfc874262', 'Online', 'A', '2024-05-30', '12:00:00', 20),
('E0006', 'Game Access 2024', 'Game Access 2024 brings together thousands of people every year from all walks of life, connected by their boundless creativity and passion for making games. Together we celebrate our work, learn from the industry’s heroes, and look towards new adventures ahead.\r\n\r\nFamed for its relaxed atmosphere, inspiring speakers, outstanding design and rich accompanying program, Game Access is fast becoming one of the most sought-after and beloved game dev events in the region. In combination with the distinctive charm of the city of Brno, Game Access is the event that will make you want to come back every year.', '6640c38210d74', 'Brno Exhibition Centre', 'A', '2024-05-31', '10:30:00', 30),
('E0007', 'BostonFIG 2024 (Online)', 'BostonFIG 2024 is a celebration of independent game development worldwide. The Fest showcases digital and tabletop games, with our showcase games selected from hundreds of submissions from independent game developers. Fest attendees have the chance to play digital games, tabletop games, and immersive experience games in a casual environment, for a very affordable ticket price.', '6640c114a664e', 'Online', 'A', '2024-06-01', '12:00:00', 20),
('E0008', 'Israel Mobile Summit 2024', 'The Israel Mobile Summit is the biggest apps/games/adtech in Israel and one of the most influential events in EMEA. Each year it attracts 1,200-1,500 attendees. It’s a catch-all event that is attended by dozens of delegates from each game publisher in Israel, and some from rest of the world.', '6640c1ed1e98c', '101 Sderot Rokach Tel Aviv, Israel', 'A', '2024-06-06', '09:00:00', 300),
('E0009', 'Identity V', 'Identity V is NetEase’s first survival horror game. With a gothic art style, mysterious storylines and an exciting 1vs4 gameplay, the game will bring you a breathtaking experience. You will first enter the game as a detective, who has received a mysterious invitation letter. The letter asks you to go to an abandoned manor and search for a missing girl. During the investigation process, you will have to gain clues by playing either the Hunter or the Survivor. And as you get closer and closer to the truth, you find something horrifying...', '6640c2b7dbdbf', 'Online', 'A', '2024-06-09', '18:09:00', 16.95),
('E0010', 'Genshin Impact', 'In the game, set forth on a journey across a fantasy world called Teyvat. In this vast world, you can explore seven nations, meet a diverse cast of characters with unique personalities and abilities, and fight powerful enemies together with them, all on the way during your quest to find your lost sibling.', '6640c34185cfc', 'Online', 'A', '2024-10-26', '05:20:00', 520);

-- --------------------------------------------------------

--
-- Table structure for table `event_schedule`
--

CREATE TABLE `event_schedule` (
  `eventSch_id` varchar(10) NOT NULL,
  `username` varchar(30) NOT NULL,
  `event_id` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` varchar(10) NOT NULL,
  `feedback_desc` varchar(2000) NOT NULL,
  `username` varchar(30) NOT NULL,
  `event_id` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `username` varchar(30) NOT NULL,
  `fullname` varchar(30) NOT NULL,
  `gender` char(1) NOT NULL,
  `birthdate` date NOT NULL,
  `phone_number` varchar(12) NOT NULL,
  `email_address` varchar(50) NOT NULL,
  `password` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`username`, `fullname`, `gender`, `birthdate`, `phone_number`, `email_address`, `password`) VALUES
('admin', 'Ning Guang', 'F', '2002-08-26', '012-5201314', 'NingGuang@gmail.com', 'ningguang520'),
('DahDah123', 'Dah Sa Chun', 'M', '2004-01-14', '018-2509438', 'SaChun@gmail.com', 'dah250945');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `event_schedule`
--
ALTER TABLE `event_schedule`
  ADD PRIMARY KEY (`eventSch_id`),
  ADD KEY `user_id` (`event_id`),
  ADD KEY `username` (`username`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `f_username` (`username`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`username`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `event_schedule`
--
ALTER TABLE `event_schedule`
  ADD CONSTRAINT `e_username` FOREIGN KEY (`username`) REFERENCES `user` (`username`),
  ADD CONSTRAINT `event_id` FOREIGN KEY (`event_id`) REFERENCES `event` (`event_id`);

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `f_event_id` FOREIGN KEY (`event_id`) REFERENCES `event` (`event_id`),
  ADD CONSTRAINT `f_username` FOREIGN KEY (`username`) REFERENCES `user` (`username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
