-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 21, 2025 at 09:20 PM
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
(8, 'KIck', 'Kick is a 2014 Indian Hindi-language action comedy film produced and directed by Sajid Nadiadwala in his directorial debut under the Nadiadwala Grandson Entertainment banner and starring Salman Khan, Jacqueline Fernandez, Randeep Hooda and Nawazuddin Siddiqui in the lead roles.[4] An official remake of the eponymous 2009 Telugu original, it was made in collaboration with UTV Motion Pictures on a reported budget of ₹55 crore (US$6.5 million).', '2011', 'Romance', 6.8, 120, '/uploads/movies/6944ce3698b4d5.16738709_1766116918.jpeg', 'https://www.youtube.com/watch?v=u-j1nx_HY5o', '2025-12-19 04:01:58', '2025-12-19 04:01:58', 0),
(13, 'Anvar', 'Anwar is a 2007 Indian romantic thriller film written and directed by Manish Jha, who is famous for his work in Matrubhoomi. The film stars the siblings Siddharth Koirala and Manisha Koirala along with Rajpal Yadav and Nauheed Cyrusi[2]. The songs \"Maula Mere Maula\" and \"Tose Naina Lage\" are two of the most popular songs of 2007[3]. The movie is most notable for featuring Siddharth Koirala.\r\n\r\n', '2007', 'Fantasy', 8.0, 160, '/uploads/movies/69484e8681ec49.67228144.jpg', '', '2025-12-21 19:46:14', '2025-12-21 19:46:14', 0),
(14, 'Zid', 'Zid (transl. Obstinance) is a 2014 Indian Hindi-language thriller film directed by Vivek Agnihotri and produced by Anubhav Sinha. The film stars Karanvir Sharma, Mannara Chopra and Shraddha Das in the principal roles.\r\n\r\n', '2014', 'Musical', 5.0, 160, '/uploads/movies/69484f4844ce98.04949081.jpg', '', '2025-12-21 19:49:28', '2025-12-21 19:49:28', 0),
(15, 'Manikarnika', 'Manikarnika: The Queen of Jhansi is a 2019 Indian Hindi-language epic historical drama film[3] based on the life of Rani Lakshmi Bai of Jhansi.[7] It is directed by Krish Jagarlamudi and Kangana Ranaut from a screenplay written by V. Vijayendra Prasad. Produced by Zee Studios, the film stars Ranaut in the title role.[8]\r\n\r\n', '2019', 'History', 9.2, 150, '/uploads/movies/69484fddecd328.00202133.jpg', '', '2025-12-21 19:51:57', '2025-12-21 19:51:57', 0),
(16, 'Taiger Zinda hai', 'Tiger Zinda Hai (transl. Tiger is Alive) is a 2017 Indian Hindi-language action thriller film written and directed by Ali Abbas Zafar and produced by Aditya Chopra under Yash Raj Films.[1][6][7] It is a sequel to Ek Tha Tiger (2012) and the second instalment in the YRF Spy Universe. The film stars Salman Khan and Katrina Kaif who reprise their roles from the predecessor.[8][9][10] Five years after the events of Ek Tha Tiger, Tiger and Zoya find themselves pulled out of hiding to save nurses held hostage by the ISC, a terrorist organisation based in Iraq.\r\n\r\n', '2012', 'Action', 5.0, 120, '/uploads/movies/694850e8cd3d28.12267734.jpeg', '', '2025-12-21 19:56:24', '2025-12-21 19:56:24', 0),
(17, 'Devdas', 'Devdas is a 2002 Indian Hindi-language period romantic drama film directed by Sanjay Leela Bhansali and produced by Bharat Shah under his banner, Mega Bollywood. It stars Shah Rukh Khan, Aishwarya Rai and Madhuri Dixit in lead roles, with Jackie Shroff, Kirron Kher, Smita Jaykar, and Vijayendra Ghatge in supporting roles. Based on the Bengali-language 1917 novel of the same name by Sarat Chandra Chattopadhyay, the film narrates the story of Devdas Mukherjee (Khan), a wealthy law graduate who returns from London to marry his childhood friend, Parvati \"Paro\" (Rai). However, the rejection of their marriage by his own family sparks his descent into alcoholism, ultimately leading to his emotional deterioration and him seeking refuge with the golden-hearted courtesan Chandramukhi (Dixit).\r\n\r\n', '2000', 'Fantasy', 8.2, 136, '/uploads/movies/6948518850d862.22337162.jpeg', '', '2025-12-21 19:59:04', '2025-12-21 19:59:04', 0),
(18, 'Crew', 'Crew is a 2024 Indian Hindi-language heist comedy film directed by Rajesh A Krishnan and written by Nidhi Mehra and Mehul Suri. Produced by Ekta Kapoor, Rhea Kapoor, Anil Kapoor, and Digvijay Purohit under Balaji Motion Pictures and Anil Kapoor Films & Communication Network, it stars Kareena Kapoor Khan, Kriti Sanon, and Tabu with Diljit Dosanjh and Kapil Sharma in supporting roles. In the film, three air hostesses become involved in a gold smuggling operation. The film is noted to be a parody of Vijay Mallya owned Kingfisher Airlines, which closed down due to bankruptcy and non-payment of dues and salaries to employees.[4]\r\n\r\n', '2024', 'Drama', 5.1, 120, '/uploads/movies/694854d7781044.36305323.jpg', '', '2025-12-21 20:13:11', '2025-12-21 20:13:11', 0),
(19, 'Ashique 2', 'Aashiqui 2 (transl. Romance 2) is a 2013 Indian Hindi-language musical romantic drama film directed by Mohit Suri, and produced by Bhushan Kumar and Mukesh Bhatt under the T-Series Films and Vishesh Films production banners. It is a spiritual successor to the 1990 musical film Aashiqui. The film stars Aditya Roy Kapur and Shraddha Kapoor, with Shaad Randhawa and Mahesh Thakur in supporting roles. The film centers on a turbulent romantic relationship between a failing singer, Rahul Jaykar, and his protege, aspiring singer Aarohi Keshav Shirke, which is affected by Rahul\'s issues with alcohol abuse and temperament.[1]\r\n\r\n', '2014', 'Fantasy', 9.5, 120, '/uploads/movies/694855866e7a18.51824636.jpg', '', '2025-12-21 20:16:06', '2025-12-21 20:16:06', 0),
(20, 'Do Dil Bhande Ek Dori se', 'Do Dil Bandhe Ek Dori Se (English: Two Hearts Connected by One Thread) is an Indian Hindi-language drama television series that premiered on 12 August 2013.[1][2] The show aired on Zee TV Monday through Friday nights.[3] The show was musically treated by Dony Hazarika. It replaced Hitler Didi.[4] it was replaced by Jamai Raja in its timeslot.[5]\r\n\r\n', '2013', 'Fantasy', 10.0, 120, '/uploads/movies/69485661219b91.37210252.jpg', '', '2025-12-21 20:19:45', '2025-12-21 20:19:45', 0);

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
(3, 'Despacito', 'Luis Fonsi ft. Daddy Yankee', 'Vida', 'Reggaeton', 229, 'Spanish', '/images/Despacito.jpg', NULL, '2025-11-28 10:16:15', '2025-11-28 10:16:15'),
(11, 'Chikni Chammeli', 'Shreya Goushal', 'Agnipath', 'Dance', 240, 'Hindi', '/uploads/songs/69484b326fadf3.08424054.png', '/uploads/songs/audio/69484b32706653.63839304.mp3', '2025-12-21 19:32:02', '2025-12-21 19:32:02'),
(12, 'Dance Pe Chance  ', 'Sunidhi Chuhaan', 'Rab ne banadi Jodi', 'Dance', 360, 'Hindi', '/uploads/songs/69484bfe0213e1.89747083.jpg', '/uploads/songs/audio/69484bfe023967.58750861.mp3', '2025-12-21 19:35:26', '2025-12-21 19:35:26'),
(13, 'Hale Dil', 'Harshit saxsena', 'Merder 2', 'Romantic', 240, 'Hindi', '/uploads/songs/69484d7e99e870.71253169.jpeg', '/uploads/songs/audio/69484d7e9a28d2.16642381.m4a', '2025-12-21 19:41:50', '2025-12-21 19:41:50'),
(14, 'Kaun Tuje', 'Palak Muchchal', 'Ms Dhoni', 'Romantic', 350, 'Hindi', '/uploads/songs/69484dfce8a348.45269689.jpeg', '/uploads/songs/audio/69484dfce8c7c7.94026255.mp3', '2025-12-21 19:43:56', '2025-12-21 19:43:56'),
(15, 'Kaho na Kaho', 'Unkown', 'Unkwon', 'Romantic', 250, 'Hindi', '/uploads/songs/69485244e30187.44575299.jpeg', '/uploads/songs/audio/69485244e330c6.70874574.m4a', '2025-12-21 20:02:12', '2025-12-21 20:02:12'),
(16, 'Tu Zaroori', 'Sunidhi Chauhaan', 'Zid', 'Romantic', 260, 'Hindi', '/uploads/songs/69485373beeb50.08621708.jpg', '/uploads/songs/audio/69485373bf24d5.51790318.mp3', '2025-12-21 20:07:15', '2025-12-21 20:07:15');

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
(4, 'samudi', 'contact.leewya@gmail.com', '$2y$10$E7KxxAkcWUw8pWhH2/Ve8O95R3U7AWarq/TqoOym/j2vokqeRrXS6', 'samudi', 'kawya', '2003-03-10', 'Sri Lanka', 'profile_6944ceaaa96a8.jpeg', 'admin', 1, 0, '2025-12-19 04:03:54', '2025-12-21 19:52:12', '2025-12-21 19:52:12');

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
  MODIFY `movie_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `songs`
--
ALTER TABLE `songs`
  MODIFY `song_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

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

  ALTER TABLE users ADD COLUMN status ENUM('Active', 'Inactive', 'Suspend') DEFAULT 'Active' AFTER user_type;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
