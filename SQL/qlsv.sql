-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 07, 2025 at 05:43 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `qlsv`
--

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `teacher` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) DEFAULT '0.00',
  `day` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `time` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `name`, `teacher`, `description`, `price`, `day`, `time`, `created_at`) VALUES
(1, 'Lập trình PHP cơ bản', 'Nguyễn Văn A', 'Khóa học giúp bạn nắm vững kiến thức PHP từ cơ bản đến nâng cao.', 400000.00, 'Thứ 2', '08:00', '2025-11-02 16:11:40'),
(2, 'Thiết kế Web với HTML & CSS', 'Trần Thị B', 'Tìm hiểu cách tạo website đẹp, chuẩn SEO và responsive.', 350000.00, 'Thứ 4', '14:00', '2025-11-02 16:11:40'),
(3, 'Cơ bản về Cấu trúc dữ liệu', 'Lê Hoàng C', 'Khóa học nhập môn CTDL & GT cho sinh viên CNTT.', 300000.00, 'Thứ 6', '09:30', '2025-11-02 16:11:40');

-- --------------------------------------------------------

--
-- Table structure for table `course_videos`
--

CREATE TABLE `course_videos` (
  `id` int NOT NULL,
  `course_id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `video_url` varchar(255) NOT NULL,
  `description` text,
  `duration` int DEFAULT '0',
  `is_demo` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `course_videos`
--

INSERT INTO `course_videos` (`id`, `course_id`, `title`, `video_url`, `description`, `duration`, `is_demo`) VALUES
(1, 3, 'video', 'https://www.youtube.com/watch?v=XeS1EqtnwgI', 'học', 0, 0),
(2, 1, 'học', 'https://www.youtube.com/watch?v=XeS1EqtnwgI', '1231', 0, 0),
(3, 2, 'đưa', 'https://www.youtube.com/watch?v=XeS1EqtnwgI', '2133', 0, 1),
(4, 3, 'ádas', 'https://www.youtube.com/watch?v=XeS1EqtnwgI', '213as', 0, 1),
(5, 1, 'học php', 'https://www.youtube.com/embed/0mde1O_bX_4', 'q231', 11, 1),
(6, 1, 'học php', 'https://www.youtube.com/embed/0mde1O_bX_4', 'q231', 11, 1);

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `id` int NOT NULL,
  `course_id` int NOT NULL,
  `student_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `student_email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `enrollments`
--

INSERT INTO `enrollments` (`id`, `course_id`, `student_name`, `student_email`, `created_at`) VALUES
(1, 1, 'Nguyễn Văn A', 'vana@example.com', '2025-11-02 16:11:40'),
(2, 2, 'Trần Thị B', 'thib@example.com', '2025-11-02 16:11:40');

-- --------------------------------------------------------

--
-- Table structure for table `memberships`
--

CREATE TABLE `memberships` (
  `id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `plan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `memberships`
--

INSERT INTO `memberships` (`id`, `name`, `email`, `plan`, `created_at`) VALUES
(1, 'Nguyễn Văn A', 'vana@example.com', 'Premium', '2025-11-02 16:11:40'),
(2, 'Trần Thị B', 'thib@example.com', 'Basic', '2025-11-02 16:11:40');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `name`, `email`, `phone`, `created_at`) VALUES
(1, 'Nguyễn Văn A', 'vana@example.com', '0909123456', '2025-11-02 09:11:40'),
(2, 'Trần Thị B', 'thib@example.com', '0909234567', '2025-11-02 09:11:40');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `bank_code` varchar(20) DEFAULT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `account_no` varchar(50) DEFAULT NULL,
  `account_name` varchar(100) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status` enum('pending','success','failed') DEFAULT 'pending',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `confirmed_at` datetime DEFAULT NULL,
  `expire_at` datetime GENERATED ALWAYS AS ((`created_at` + interval 15 minute)) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `amount`, `bank_code`, `bank_name`, `account_no`, `account_name`, `description`, `status`, `created_at`, `confirmed_at`) VALUES
(2, 1, 100000.00, NULL, 'MB', NULL, NULL, 'NAP1', 'pending', '2025-11-05 20:24:22', NULL),
(3, 1, 100000.00, NULL, 'MB', NULL, NULL, 'NAP1', 'pending', '2025-11-05 21:04:00', NULL),
(4, 1, 100000.00, NULL, 'MB', NULL, NULL, 'NAP1', 'pending', '2025-11-05 21:04:21', NULL),
(5, 1, 1000000.00, NULL, 'MB', NULL, NULL, 'NAP1', 'pending', '2025-11-05 21:04:26', NULL),
(6, 1, 10000.00, NULL, 'MB', NULL, NULL, 'NAP1', 'pending', '2025-11-05 21:05:04', NULL),
(7, 1, 100000.00, 'MB', NULL, NULL, NULL, 'NAP1', 'pending', '2025-11-05 21:06:16', NULL),
(8, 1, 10000.00, 'MB', NULL, NULL, NULL, 'NAP1', 'pending', '2025-11-05 21:07:20', NULL),
(9, 1, 100000.00, 'MB', NULL, NULL, NULL, 'NAP1', 'pending', '2025-11-05 21:08:00', NULL),
(10, 1, 100000.00, 'MB', NULL, NULL, NULL, 'NAP1', 'pending', '2025-11-05 21:11:36', NULL),
(11, 1, 100000.00, 'MB', NULL, NULL, NULL, 'NAP1', 'pending', '2025-11-05 21:12:25', NULL),
(12, 1, 100000.00, 'MB', NULL, NULL, NULL, 'NAP1', 'pending', '2025-11-05 21:14:00', NULL),
(13, 1, 100000.00, 'MB', NULL, NULL, NULL, 'NAP1', 'pending', '2025-11-05 21:17:19', NULL),
(14, 1, 100000.00, 'MB', NULL, NULL, NULL, 'NAP1', 'pending', '2025-11-05 21:51:59', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `balance` decimal(15,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `balance`) VALUES
(1, 'Phan Le Ba Khang', 0.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `course_videos`
--
ALTER TABLE `course_videos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `memberships`
--
ALTER TABLE `memberships`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `course_videos`
--
ALTER TABLE `course_videos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `memberships`
--
ALTER TABLE `memberships`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `course_videos`
--
ALTER TABLE `course_videos`
  ADD CONSTRAINT `course_videos_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `enrollments_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
