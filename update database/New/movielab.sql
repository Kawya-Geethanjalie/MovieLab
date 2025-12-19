-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 19, 2025 at 05:10 AM
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
-- Database: `movielab`
--

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `movie_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `release_year` year(4) DEFAULT NULL,
  `genre` varchar(100) DEFAULT NULL,
  `rating` decimal(3,1) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `poster_image` varchar(255) DEFAULT NULL,
  `trailer_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `view_count` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`movie_id`, `title`, `description`, `release_year`, `genre`, `rating`, `duration`, `poster_image`, `trailer_url`, `created_at`, `updated_at`, `view_count`) VALUES
(1, 'Avatar: The Way of Water', 'Jake Sully lives with his newfound family formed on the extrasolar moon Pandora.', '2022', 'Sci-Fi', 7.8, 192, '/images/Avatar.jpg', NULL, '2025-11-28 10:16:15', '2025-11-28 10:16:15', 0),
(2, 'Oppenheimer', 'The story of American scientist J. Robert Oppenheimer and his role in the development of the atomic bomb.', '2023', 'Biography', 8.6, 180, '/images/Oppenheimer.jpg', NULL, '2025-11-28 10:16:15', '2025-11-28 10:16:15', 0),
(3, 'Spider-Man: Across the Spider-Verse', 'Miles Morales catapults across the Multiverse.', '2023', 'Animation', 8.9, 140, '/images/SpiderMan.jpg', NULL, '2025-11-28 10:16:15', '2025-11-28 10:16:15', 0),
(4, 'Dune: Part Two', 'Paul Atreides unites with Chani and the Fremen.', '2024', 'Sci-Fi', 8.8, 166, '/images/Dune.jpg', NULL, '2025-11-28 10:16:15', '2025-11-28 10:16:15', 0),
(5, 'John Wick: Chapter 4', 'John Wick uncovers a path to defeating The High Table.', '2023', 'Action', 7.9, 169, '/images/JohnWick.jpg', NULL, '2025-11-28 10:16:15', '2025-11-28 10:16:15', 0),
(6, 'sinhala', 'saswasa', '2024', 'Drama', 10.0, 77, NULL, NULL, '2025-11-30 17:40:40', '2025-11-30 17:40:40', 0),
(7, 'ss', 'ssss', '2021', 'Pop', 8.0, 455, NULL, NULL, '2025-11-30 18:32:22', '2025-11-30 18:32:22', 0),
(8, 'KIck', 'Kick is a 2014 Indian Hindi-language action comedy film produced and directed by Sajid Nadiadwala in his directorial debut under the Nadiadwala Grandson Entertainment banner and starring Salman Khan, Jacqueline Fernandez, Randeep Hooda and Nawazuddin Siddiqui in the lead roles.[4] An official remake of the eponymous 2009 Telugu original, it was made in collaboration with UTV Motion Pictures on a reported budget of ₹55 crore (US$6.5 million).', '2011', 'Romance', 6.8, 120, '/uploads/movies/6944ce3698b4d5.16738709_1766116918.jpeg', 'https://www.youtube.com/watch?v=u-j1nx_HY5o', '2025-12-19 04:01:58', '2025-12-19 04:01:58', 0);

-- --------------------------------------------------------

--
-- Table structure for table `songs`
--

CREATE TABLE `songs` (
  `song_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `artist` varchar(255) NOT NULL,
  `album` varchar(255) DEFAULT NULL,
  `genre` varchar(100) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `language` varchar(50) DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `audio_file` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `songs`
--

INSERT INTO `songs` (`song_id`, `title`, `artist`, `album`, `genre`, `duration`, `language`, `cover_image`, `audio_file`, `created_at`, `updated_at`) VALUES
(1, 'Blinding Lights', 'The Weeknd', 'After Hours', 'Pop', 200, 'English', '/images/BlindingLights.jpg', NULL, '2025-11-28 10:16:15', '2025-11-28 10:16:15'),
(2, 'Shape of You', 'Ed Sheeran', '÷ (Divide)', 'Pop', 233, 'English', '/images/EdSheeran.jpg', NULL, '2025-11-28 10:16:15', '2025-11-28 10:16:15'),
(3, 'Despacito', 'Luis Fonsi ft. Daddy Yankee', 'Vida', 'Reggaeton', 229, 'Spanish', '/images/Despacito.jpg', NULL, '2025-11-28 10:16:15', '2025-11-28 10:16:15'),
(4, 'Bohemian Rhapsody', 'Queen', 'A Night at the Opera', 'Rock', 355, 'English', '/images/BohemianRhapsody.jpg', NULL, '2025-11-28 10:16:15', '2025-11-28 10:16:15'),
(5, 'Imagine', 'John Lennon', 'Imagine', 'Rock', 183, 'English', '/images/Imagine.jpg', NULL, '2025-11-28 10:16:15', '2025-11-28 10:16:15'),
(6, 'ukg', 'sdds', 'dsdsds', 'Thriller', 2, 'Sinhala', NULL, NULL, '2025-11-30 17:48:46', '2025-11-30 17:48:46'),
(7, 'ukg', 'sdds', 'dsdsds', 'Thriller', 2, 'Sinhala', NULL, NULL, '2025-11-30 17:50:04', '2025-11-30 17:50:04'),
(8, 'Hale Dil', 'Harshit saxsena', 'Merder 2', 'Classical', 4, 'Hindi', '/uploads/songs/6944d03d5ce8a5.02715999_1766117437.jpg', '/uploads/songs/audio/6944d03d5d4825.76835349_1766117437.m4a', '2025-12-19 04:10:37', '2025-12-19 04:10:37');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `birthday` date NOT NULL,
  `country` varchar(100) NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `user_type` enum('normal','premium','admin') DEFAULT 'normal',
  `is_active` tinyint(1) DEFAULT 1,
  `email_verified` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password_hash`, `first_name`, `last_name`, `birthday`, `country`, `profile_image`, `user_type`, `is_active`, `email_verified`, `created_at`, `updated_at`, `last_login`) VALUES
(1, 'admin', 'admin@movielab.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', 'User', '1990-01-01', 'United States', NULL, 'admin', 1, 1, '2025-11-28 10:16:15', '2025-11-28 10:16:15', NULL),
(2, 'Sasindu', 'sasindusanjana88@gmail.com', '$2y$10$F5rx9Lr2MQG0dmtayvWnJ.4f1kesCtuoubVQq29VxtigrtMj/DS6u', 'Sasindu', 'Sanjana', '2001-02-01', 'Sri Lanka', 'profile_692977df75784.jpg', 'normal', 1, 0, '2025-11-28 10:22:23', '2025-11-28 10:22:37', '2025-11-28 10:22:37'),
(3, 'Lochana', 'nimnalochana@gmail.com', '$2y$10$NbKKkpIG5Na08uxoWN1GcuGPHaBJMB.wn0ya4GVxfQxh0KK61WfEi', 'Nimna', 'lochana', '2006-05-25', 'Sri Lanka', 'profile_69298f68e4787.jpg', 'admin', 1, 0, '2025-11-28 12:02:49', '2025-12-04 07:39:50', '2025-12-04 07:39:50'),
(4, 'samudi', 'contact.leewya@gmail.com', '$2y$10$E7KxxAkcWUw8pWhH2/Ve8O95R3U7AWarq/TqoOym/j2vokqeRrXS6', 'samudi', 'kawya', '2003-03-10', 'Sri Lanka', 'profile_6944ceaaa96a8.jpeg', 'admin', 1, 0, '2025-12-19 04:03:54', '2025-12-19 04:04:28', '2025-12-19 04:04:28');

-- --------------------------------------------------------

--
-- Table structure for table `user_activity`
--

CREATE TABLE `user_activity` (
  `activity_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `activity_type` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_activity`
--

INSERT INTO `user_activity` (`activity_id`, `user_id`, `activity_type`, `description`, `ip_address`, `created_at`) VALUES
(1, 1, 'logout', 'User logged out successfully', '::1', '2025-11-28 10:21:06'),
(2, 4, 'add_song', 'Added song: Hale Dil by Harshit saxsena (ID: 8)', '::1', '2025-12-19 04:10:37');

-- --------------------------------------------------------

--
-- Table structure for table `user_favorites`
--

CREATE TABLE `user_favorites` (
  `favorite_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content_type` enum('movie','song') NOT NULL,
  `content_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_sessions`
--

CREATE TABLE `user_sessions` (
  `session_id` varchar(128) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`movie_id`),
  ADD KEY `idx_title` (`title`),
  ADD KEY `idx_genre` (`genre`),
  ADD KEY `idx_rating` (`rating`),
  ADD KEY `idx_release_year` (`release_year`);

--
-- Indexes for table `songs`
--
ALTER TABLE `songs`
  ADD PRIMARY KEY (`song_id`),
  ADD KEY `idx_title` (`title`),
  ADD KEY `idx_artist` (`artist`),
  ADD KEY `idx_genre` (`genre`),
  ADD KEY `idx_language` (`language`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_username` (`username`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_user_type` (`user_type`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `user_activity`
--
ALTER TABLE `user_activity`
  ADD PRIMARY KEY (`activity_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_activity_type` (`activity_type`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `user_favorites`
--
ALTER TABLE `user_favorites`
  ADD PRIMARY KEY (`favorite_id`),
  ADD UNIQUE KEY `unique_favorite` (`user_id`,`content_type`,`content_id`),
  ADD KEY `idx_user_content` (`user_id`,`content_type`);

--
-- Indexes for table `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_expires_at` (`expires_at`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `movie_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `songs`
--
ALTER TABLE `songs`
  MODIFY `song_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_activity`
--
ALTER TABLE `user_activity`
  MODIFY `activity_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_favorites`
--
ALTER TABLE `user_favorites`
  MODIFY `favorite_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `user_activity`
--
ALTER TABLE `user_activity`
  ADD CONSTRAINT `user_activity_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `user_favorites`
--
ALTER TABLE `user_favorites`
  ADD CONSTRAINT `user_favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD CONSTRAINT `user_sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
