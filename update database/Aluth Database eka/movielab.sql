-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 12, 2026 at 12:04 PM
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
-- Table structure for table `massages_and_comment`
--

CREATE TABLE `massages_and_comment` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `subject` varchar(20) NOT NULL,
  `massage` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `massages_and_comment`
--

INSERT INTO `massages_and_comment` (`id`, `name`, `email`, `subject`, `massage`, `created_at`) VALUES
(0, 'f', 'gf@gmail.com', 'Comment', 'sdsdd', '2026-01-12 11:02:56');

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
  `play_url` varchar(255) DEFAULT NULL,
  `download_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `view_count` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`movie_id`, `title`, `description`, `release_year`, `genre`, `rating`, `duration`, `poster_image`, `trailer_url`, `play_url`, `download_url`, `created_at`, `updated_at`, `view_count`) VALUES
(1, 'Avatar: The Way of Water', 'Jake Sully lives with his newfound family formed on the extrasolar moon Pandora.', '2022', 'Sci-Fi', 7.8, 192, '/images/Avatar.jpg', NULL, NULL, NULL, '2025-11-28 10:16:15', '2025-11-28 10:16:15', 0),
(2, 'Oppenheimer', 'The story of American scientist J. Robert Oppenheimer and his role in the development of the atomic bomb.', '2023', 'Biography', 8.6, 180, '/images/Oppenheimer.jpg', NULL, NULL, NULL, '2025-11-28 10:16:15', '2025-12-23 17:25:06', 0),
(3, 'Spider-Man: Across the Spider-Verse', 'Miles Morales catapults across the Multiverse.', '2023', 'Animation', 8.9, 140, '/images/SpiderMan.jpg', NULL, NULL, NULL, '2025-11-28 10:16:15', '2025-11-28 10:16:15', 0),
(4, 'Dune: Part Two', 'Paul Atreides unites with Chani and the Fremen.', '2024', 'Sci-Fi', 8.8, 166, '/images/Dune.jpg', NULL, NULL, NULL, '2025-11-28 10:16:15', '2025-11-28 10:16:15', 0),
(8, 'KIck', 'Kick is a 2014 Indian Hindi-language action comedy film produced and directed by Sajid Nadiadwala in his directorial debut under the Nadiadwala Grandson Entertainment banner and starring Salman Khan, Jacqueline Fernandez, Randeep Hooda and Nawazuddin Siddiqui in the lead roles.[4] An official remake of the eponymous 2009 Telugu original, it was made in collaboration with UTV Motion Pictures on a reported budget of ₹55 crore (US$6.5 million).', '2011', 'Romance', 6.8, 120, 'uploads/movies/6944ce3698b4d5.16738709_1766116918.jpeg', 'https://www.youtube.com/watch?v=u-j1nx_HY5o', NULL, NULL, '2025-12-19 04:01:58', '2025-12-27 14:03:33', 0),
(14, 'Zid', 'Zid (transl. Obstinance) is a 2014 Indian Hindi-language thriller film directed by Vivek Agnihotri and produced by Anubhav Sinha. The film stars Karanvir Sharma, Mannara Chopra and Shraddha Das in the principal roles.\r\n\r\n', '2014', 'Musical', 5.0, 160, 'uploads/movies/69484f4844ce98.04949081.jpg', 'https://www.youtube.com/watch?v=bArdCUba1EI', NULL, NULL, '2025-12-21 19:49:28', '2025-12-27 14:03:38', 0),
(15, 'Manikarnika', 'Manikarnika: The Queen of Jhansi is a 2019 Indian Hindi-language epic historical drama film[3] based on the life of Rani Lakshmi Bai of Jhansi.[7] It is directed by Krish Jagarlamudi and Kangana Ranaut from a screenplay written by V. Vijayendra Prasad. Produced by Zee Studios, the film stars Ranaut in the title role.[8]\r\n\r\n', '2019', 'History', 9.2, 150, 'uploads/movies/69484fddecd328.00202133.jpg', 'https://www.youtube.com/watch?v=eBw8SPPvGXQ', NULL, NULL, '2025-12-21 19:51:57', '2025-12-27 14:03:45', 0),
(16, 'Taiger Zinda hai', 'Tiger Zinda Hai (transl. Tiger is Alive) is a 2017 Indian Hindi-language action thriller film written and directed by Ali Abbas Zafar and produced by Aditya Chopra under Yash Raj Films.[1][6][7] It is a sequel to Ek Tha Tiger (2012) and the second instalment in the YRF Spy Universe. The film stars Salman Khan and Katrina Kaif who reprise their roles from the predecessor.[8][9][10] Five years after the events of Ek Tha Tiger, Tiger and Zoya find themselves pulled out of hiding to save nurses held hostage by the ISC, a terrorist organisation based in Iraq.\r\n\r\n', '2012', 'Action', 5.0, 120, 'uploads/movies/694850e8cd3d28.12267734.jpeg', 'https://www.youtube.com/watch?v=ePO5M5DE01I', NULL, NULL, '2025-12-21 19:56:24', '2025-12-27 14:03:54', 0),
(17, 'Devdas', 'Devdas is a 2002 Indian Hindi-language period romantic drama film directed by Sanjay Leela Bhansali and produced by Bharat Shah under his banner, Mega Bollywood. It stars Shah Rukh Khan, Aishwarya Rai and Madhuri Dixit in lead roles, with Jackie Shroff, Kirron Kher, Smita Jaykar, and Vijayendra Ghatge in supporting roles. Based on the Bengali-language 1917 novel of the same name by Sarat Chandra Chattopadhyay, the film narrates the story of Devdas Mukherjee (Khan), a wealthy law graduate who returns from London to marry his childhood friend, Parvati \"Paro\" (Rai). However, the rejection of their marriage by his own family sparks his descent into alcoholism, ultimately leading to his emotional deterioration and him seeking refuge with the golden-hearted courtesan Chandramukhi (Dixit).\r\n\r\n', '2000', 'Fantasy', 8.2, 136, 'uploads/movies/6948518850d862.22337162.jpeg', 'https://www.youtube.com/watch?v=8tuHQWGMQwY', NULL, NULL, '2025-12-21 19:59:04', '2025-12-27 14:03:59', 0),
(18, 'Crew', 'Crew is a 2024 Indian Hindi-language heist comedy film directed by Rajesh A Krishnan and written by Nidhi Mehra and Mehul Suri. Produced by Ekta Kapoor, Rhea Kapoor, Anil Kapoor, and Digvijay Purohit under Balaji Motion Pictures and Anil Kapoor Films & Communication Network, it stars Kareena Kapoor Khan, Kriti Sanon, and Tabu with Diljit Dosanjh and Kapil Sharma in supporting roles. In the film, three air hostesses become involved in a gold smuggling operation. The film is noted to be a parody of Vijay Mallya owned Kingfisher Airlines, which closed down due to bankruptcy and non-payment of dues and salaries to employees.[4]\r\n\r\n', '2024', 'Drama', 5.1, 120, 'uploads/movies/694854d7781044.36305323.jpg', 'https://www.youtube.com/watch?v=3uvfq4Cu8R8', NULL, NULL, '2025-12-21 20:13:11', '2025-12-27 14:04:05', 0),
(19, 'Ashique 2', 'Aashiqui 2 (transl. Romance 2) is a 2013 Indian Hindi-language musical romantic drama film directed by Mohit Suri, and produced by Bhushan Kumar and Mukesh Bhatt under the T-Series Films and Vishesh Films production banners. It is a spiritual successor to the 1990 musical film Aashiqui. The film stars Aditya Roy Kapur and Shraddha Kapoor, with Shaad Randhawa and Mahesh Thakur in supporting roles. The film centers on a turbulent romantic relationship between a failing singer, Rahul Jaykar, and his protege, aspiring singer Aarohi Keshav Shirke, which is affected by Rahul&#39;s issues with alcohol abuse and temperament.[1]\r\n\r\n', '2014', 'Romance', 9.5, 120, 'uploads/movies/1767594914_695b5ba23ed5a.jpg', 'https://www.youtube.com/watch?v=x8blxWAOsaw&list=RDx8blxWAOsaw&start_radio=1', 'https://cinesubz.lk/movies/aashiqui-2-2013-sinhala-subtitles/', 'https://cloud.sonic-cloud.online/server1/qmsyfzbjcavekxfuwqbi/Movies/2021-08-22/CineSubz.com%20-%20Aashiqui.2.2013%20720p?ext=mp4', '2025-12-21 20:16:06', '2026-01-05 06:35:14', 0),
(20, 'Do Dil Bhande Ek Dori se', 'Do Dil Bandhe Ek Dori Se (English: Two Hearts Connected by One Thread) is an Indian Hindi-language drama television series that premiered on 12 August 2013.[1][2] The show aired on Zee TV Monday through Friday nights.[3] The show was musically treated by Dony Hazarika. It replaced Hitler Didi.[4] it was replaced by Jamai Raja in its timeslot.[5]\r\n\r\n', '2013', 'Fantasy', 10.0, 120, 'uploads/movies/1767594684_695b5abca950f.jpg', 'https://www.youtube.com/watch?v=wOBq674bV68', 'https://www.youtube.com/results?search_query=do+dil+bandhe+ek+dori+se', '', '2025-12-21 20:19:45', '2026-01-05 06:31:24', 0),
(21, 'Anwar', 'Anwar is a 2007 Indian romantic thriller film written and directed by Manish Jha, who is famous for his work in Matrubhoomi. The film stars the siblings Siddharth Koirala and Manisha Koirala along with Rajpal Yadav and Nauheed Cyrusi[2]. The songs \"Maula Mere Maula\" and \"Tose Naina Lage\" are two of the most popular songs of 2007[3]. The movie is most notable for featuring Siddharth Koirala.\r\n\r\n', '2007', 'Fantasy', 5.6, 120, 'uploads/movies/694977ff20ea32.44749749.jpg', 'https://www.youtube.com/watch?v=l5sgIqzlPXc&list=RDl5sgIqzlPXc&start_radio=1', NULL, NULL, '2025-12-22 16:55:27', '2025-12-27 14:03:25', 0),
(23, 'xx', '11', '2024', 'War', 4.0, 200, 'uploads/movies/1766505930_Screenshot (4).png', '', NULL, NULL, '2025-12-23 16:03:25', '2025-12-23 16:05:30', 0),
(24, 'Jannat', 'Jannat (transl. Heaven) is a 2008 Indian Hindi-language romantic crime film directed by Kunal Deshmukh, and produced by Mukesh Bhatt. The film stars Emraan Hashmi opposite Sonal Chauhan in lead roles. It was released on 16 May 2008 and was a success worldwide, receiving positive responses from critics. Its sequel Jannat 2, starring Emraan Hashmi, Randeep Hooda and Esha Gupta, was released in 4 May 2012 and It&#39;s Sequel Jannat 3, starting Emraan Hashmi, Gaurav Arora, and Neha Dhupia, Kriti Kharbanda, and Randeep Hooda, was released in 15 May 2026.', '2008', 'Romance', 6.8, 150, 'uploads/movies/1767594540_695b5a2c73d59.jpg', 'https://www.youtube.com/watch?v=K9n-aMlLJlo', 'https://cinesubz.lk/movies/jannat-2008-sinhala-subtitles/', 'https://cloud.sonic-cloud.online/server4/new/Jannat.In.Search.Of.Heaven.2008-%5BCineSubz.co%5D-480p?ext=mp4', '2025-12-27 13:48:14', '2026-01-05 06:29:00', 0),
(25, 'Razz', 'Raaz: Do You Want to Know A Secret…? (transl. Secret) is a 2002 Indian supernatural horror film directed by Vikram Bhatt. The film stars Dino Morea and Bipasha Basu in lead roles, with Malini Sharma and Ashutosh Rana in supporting roles. In the Indian media, the film is widely regarded as a Landmark in the history of Indian cinema and considered one of the best Hindi horror cinemas.[a] The film is an unofficial adaptation of the American film What Lies Beneath (2000).[8][9] American entertainment publication Collider has termed it better than the original.[10][11][12]\r\n\r\n', '2002', 'Horror', 8.0, 120, 'uploads/movies/1767594245_695b590569e56.jpg', 'https://www.youtube.com/watch?v=O6GUoVApVkc', 'https://cinesubz.lk/movies/raaz-2002-sinhala-subtitles/', 'https://cloud.sonic-cloud.online/server4/new/Raaz.2002.WEBRip-%5BCineSubz.co%5D-720p?ext=mp4', '2025-12-27 13:58:43', '2026-01-05 06:24:05', 0),
(26, 'Hosefull 2', 'Housefull 2, sometimes called Housefull 2: The Dirty Dozen, is a 2012 Indian Hindi-language action comedy film written and directed by Sajid Khan. It was co-written by brothers Sajid Samji and Farhad Samji based on a story by Sajid Nadiadwala. Produced by Nadiadwala under Nadiadwala Grandson Entertainment and distributed by Eros International, it is a remake of Mattupetty Machan and the second installment of the Housefull franchise, is a standalone sequel to Housefull (2010).', '2010', 'Family', 6.0, 120, 'uploads/movies/1767593876_695b5794bbc97.jpg', 'https://www.youtube.com/watch?v=u-j1nx_HY5o', 'https://cinesubz.lk/movies/housefull-2-2012-sinhala-subtitles/', 'https://cloud.sonic-cloud.online/server5/202506/Housefull%202%20(2012)%20BluRay-%5BCineSubz.co%5D-720p?ext=mp4', '2025-12-30 16:48:27', '2026-01-05 06:17:56', 0),
(27, 'Housefull', 'sss', '2010', 'Thriller', 7.0, 120, 'uploads/movies/1767593797_695b57450fc62.jpg', 'https://www.youtube.com/watch?v=0eRVTe98Lz4', 'https://cinesubz.lk/movies/housefull-2010-sinhala-subtitles/', 'https://cloud.sonic-cloud.online/server5/202506/Housefull%20(2010)%20BluRay-%5BCineSubz.co%5D-720p?ext=mp4', '2025-12-30 16:50:25', '2026-01-05 06:16:37', 0),
(29, 'Saiyaraa', 'Saiyaara (lit. &#39;Wanderer star&#39; or &#39;Wandering lover&#39;) is a 2025 Indian Hindi-language musical romantic drama film directed by Mohit Suri.[6] Produced by Yash Raj Films, it is loosely based on the 2004 Korean film A Moment to Remember.[7][8] It stars debutant Ahaan Panday and Aneet Padda in the lead roles of a singer-songwriter duo who fall in love. Originally planned as a spiritual sequel to Suri&#39;s Aashiqui 2 (2013), creative differences with producers Mukesh Bhatt and Bhushan Kumar led to the project being reworked into a standalone film under Yash Raj Films.[9]', '2025', 'History', 5.6, 120, 'uploads/movies/1767593178_695b54da02593.jpg', 'https://www.youtube.com/watch?v=BSJa1UytM8w&list=RDBSJa1UytM8w&start_radio=1', 'https://cinesubz.lk/movies/saiyaara-2025-sinhala-subtitles/', 'https://cloud.sonic-cloud.online/server5/202508/Saiyaara%20(2025)%20Hindi%20WEB-DL-%5BCineSubz.co%5D-720p?ext=mp4', '2025-12-30 16:56:24', '2026-01-05 06:06:18', 0),
(30, 'Ek Deewane Ki Deewaniyat', 'Ek Deewane Ki Deewaniyat (transl. The obsession of a crazy lover) is a 2025 Indian Hindi-language romantic drama film directed by Milap Zaveri and written by Zaveri and Mushtaq Shiekh.[4] Produced under the banner Desi Movies Factory, the film stars Harshvardhan Rane and Sonam Bajwa in the lead roles.\r\n\r\nThe film was theatrically released on 21 October 2025, coinciding with Diwali. It received generally negative reviews from critics[5] but was a major commercial success, grossing ₹112 crore worldwide and emerged as the 12th highest-grossing Hindi film of 2025.[6]', '2025', 'Romance', 5.3, 150, 'uploads/movies/1767592906_695b53ca7914c.jpg', 'https://www.youtube.com/watch?v=dGb3acfZp2k', 'https://cinesubz.lk/movies/ek-deewane-ki-deewaniyat-2025-sinhala-subtitles/', 'https://cloud.sonic-cloud.online/server5/202510/Ek%20Deewane%20Ki%20Deewaniyat%20(2025)%20Hindi%20WEB-%5BCineSubz.co%5D-720p?ext=mp4', '2025-12-30 17:29:28', '2026-01-05 06:01:46', 0);

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
(11, 'Chikni Chammeli', 'Shreya Goushal', 'Agnipath', 'Dance', 240, 'Hindi', 'uploads/songs/69484b326fadf3.08424054.png', 'uploads/songs/audio/69484b32706653.63839304.mp3', '2025-12-21 19:32:02', '2025-12-27 14:07:30'),
(12, 'Dance Pe Chance  ', 'Sunidhi Chuhaan', 'Rab ne banadi Jodi', 'Dance', 360, 'Hindi', 'uploads/songs/69484bfe0213e1.89747083.jpg', 'uploads/songs/audio/69484bfe023967.58750861.mp3', '2025-12-21 19:35:26', '2025-12-27 14:07:35'),
(13, 'Hale Dil', 'Harshit saxsena', 'Merder 2', 'Romantic', 240, 'Hindi', 'uploads/songs/69484d7e99e870.71253169.jpeg', 'uploads/songs/audio/69484d7e9a28d2.16642381.m4a', '2025-12-21 19:41:50', '2025-12-27 14:07:39'),
(14, 'Kaun Tuje', 'Palak Muchchal', 'Ms Dhoni', 'Romantic', 350, 'Hindi', 'uploads/songs/69484dfce8a348.45269689.jpeg', 'uploads/songs/audio/69484dfce8c7c7.94026255.mp3', '2025-12-21 19:43:56', '2025-12-27 14:07:45'),
(15, 'Kaho na Kaho', 'Unkown', 'Unkwon', 'Romantic', 250, 'Hindi', 'uploads/songs/69485244e30187.44575299.jpeg', 'uploads/songs/audio/69485244e330c6.70874574.m4a', '2025-12-21 20:02:12', '2025-12-27 14:07:50'),
(16, 'Tu Zaroori', 'Sunidhi Chauhaan', 'Zid', 'Romantic', 260, 'Hindi', 'uploads/songs/69485373beeb50.08621708.jpg', 'uploads/songs/audio/69485373bf24d5.51790318.mp3', '2025-12-21 20:07:15', '2025-12-27 14:07:57'),
(18, 'ff', 'jjjjjjjjj', 'ss', 'Jazz', 210, 'Hindi', 'uploads/songs/694fe64ac234e2.84103379.jpeg', 'uploads/songs/audio/1767115686_69540ba6b05a3.mp3', '2025-12-27 13:59:38', '2025-12-30 17:28:06'),
(19, 'sa', 'as', 'sas', 'Jazz', 201, 'Hindi', 'uploads/songs/69540531927c17.44826839.jpg', 'uploads/songs/audio/6954053192e502.56128591.mp3', '2025-12-30 17:00:33', '2025-12-30 17:00:33');

-- --------------------------------------------------------

--
-- Table structure for table `subscription_plans`
--

CREATE TABLE `subscription_plans` (
  `plan_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `billing_cycle` enum('week','month','year') NOT NULL,
  `features` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subscription_plans`
--

INSERT INTO `subscription_plans` (`plan_id`, `name`, `description`, `price`, `billing_cycle`, `features`, `is_active`, `created_at`) VALUES
(1, 'Weekly Pass', 'Perfect for a short binge-watching session', 1.99, 'week', 'HD Streaming,1 simultaneous screen', 1, '2025-12-28 04:51:23'),
(2, 'Monthly Pro', 'Flexible, no long-term contract', 5.99, 'month', 'HD Streaming,2 simultaneous screens,Offline Downloads', 1, '2025-12-28 04:51:23'),
(3, 'Yearly Pro', 'Limited time offer for long-term commitment', 49.99, 'year', '4K Ultra HD Streaming,5 simultaneous screens,Offline Downloads,Priority Support', 1, '2025-12-28 04:51:23');

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
  `status` varchar(15) NOT NULL,
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

INSERT INTO `users` (`user_id`, `username`, `email`, `password_hash`, `first_name`, `last_name`, `birthday`, `country`, `profile_image`, `status`, `user_type`, `is_active`, `email_verified`, `created_at`, `updated_at`, `last_login`) VALUES
(3, 'Lochana', 'nimnalochana@gmail.com', '$2y$10$NbKKkpIG5Na08uxoWN1GcuGPHaBJMB.wn0ya4GVxfQxh0KK61WfEi', 'Nimna', 'lochana', '2006-05-25', 'Sri Lanka', 'profile_69298f68e4787.jpg', 'Active', 'admin', 1, 0, '2025-11-28 12:02:49', '2026-01-05 07:17:43', '2025-12-04 07:39:50'),
(4, 'samudi', 'contact.leewya@gmail.com', '$2y$10$1zJrcZ7tExXG5zy/I0tuyuhgRKITsp1rcqv666uwHil2J.D74VH7S', 'samudi', 'kawya', '2003-03-10', 'Sri Lanka', 'profile_6944ceaaa96a8.jpeg', 'Active', 'admin', 1, 0, '2025-12-19 04:03:54', '2026-01-05 07:30:38', '2026-01-05 07:30:38'),
(5, 'manu123', 'manu@gmail.com', '$2y$10$Y5YQcUFnZagms730b0hebutarxggwnheEA.8ZgOJpDNvKMDQjpHb2', 'Manu', 'Srivastava', '1994-06-05', 'Austria', 'profile_695b65502ccb5.png', 'Active', 'normal', 1, 0, '2026-01-05 07:16:32', '2026-01-05 07:19:00', NULL);

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
(2, 4, 'add_song', 'Added song: Hale Dil by Harshit saxsena (ID: 8)', '::1', '2025-12-19 04:10:37'),
(3, 4, 'logout', 'User logged out successfully', '::1', '2025-12-23 17:16:09'),
(4, 4, 'logout', 'User logged out successfully', '::1', '2025-12-23 17:38:31'),
(5, 4, 'logout', 'User logged out successfully', '::1', '2025-12-27 15:25:31'),
(6, 4, 'logout', 'User logged out successfully', '::1', '2025-12-28 05:13:44'),
(7, 4, 'logout', 'User logged out successfully', '::1', '2025-12-28 05:14:15'),
(8, 4, 'logout', 'User logged out successfully', '::1', '2025-12-28 05:17:40'),
(9, 4, 'logout', 'User logged out successfully', '::1', '2025-12-28 05:38:43'),
(10, 4, 'logout', 'User logged out successfully', '::1', '2025-12-28 06:13:34'),
(11, 4, 'logout', 'User logged out successfully', '::1', '2025-12-28 06:30:10'),
(12, 4, 'logout', 'User logged out successfully', '::1', '2025-12-28 06:47:53'),
(13, 4, 'logout', 'User logged out successfully', '::1', '2025-12-28 07:04:32'),
(14, 4, 'logout', 'User logged out successfully', '::1', '2025-12-28 08:08:58'),
(15, 4, 'delete_user', 'Deleted user: admin (admin@movielab.com)', '::1', '2025-12-30 15:55:57'),
(16, 4, 'logout', 'User logged out successfully', '::1', '2026-01-05 05:41:13'),
(17, 4, 'logout', 'User logged out successfully', '::1', '2026-01-05 07:12:10'),
(18, 4, 'logout', 'User logged out successfully', '::1', '2026-01-05 07:19:55'),
(19, 4, 'delete_user', 'Deleted user: Sasindu (sasindusanjana88@gmail.com)', '::1', '2026-01-05 07:21:13');

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

-- --------------------------------------------------------

--
-- Table structure for table `user_subscriptions`
--

CREATE TABLE `user_subscriptions` (
  `subscription_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `status` enum('active','canceled','expired','pending') DEFAULT 'pending',
  `current_period_start` datetime DEFAULT NULL,
  `current_period_end` datetime DEFAULT NULL,
  `stripe_subscription_id` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `massages_and_comment`
--
ALTER TABLE `massages_and_comment`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `subscription_plans`
--
ALTER TABLE `subscription_plans`
  ADD PRIMARY KEY (`plan_id`);

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
-- Indexes for table `user_subscriptions`
--
ALTER TABLE `user_subscriptions`
  ADD PRIMARY KEY (`subscription_id`),
  ADD KEY `plan_id` (`plan_id`),
  ADD KEY `idx_user_status` (`user_id`,`status`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `movie_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `songs`
--
ALTER TABLE `songs`
  MODIFY `song_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `subscription_plans`
--
ALTER TABLE `subscription_plans`
  MODIFY `plan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user_activity`
--
ALTER TABLE `user_activity`
  MODIFY `activity_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `user_favorites`
--
ALTER TABLE `user_favorites`
  MODIFY `favorite_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_subscriptions`
--
ALTER TABLE `user_subscriptions`
  MODIFY `subscription_id` int(11) NOT NULL AUTO_INCREMENT;

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

--
-- Constraints for table `user_subscriptions`
--
ALTER TABLE `user_subscriptions`
  ADD CONSTRAINT `user_subscriptions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_subscriptions_ibfk_2` FOREIGN KEY (`plan_id`) REFERENCES `subscription_plans` (`plan_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
