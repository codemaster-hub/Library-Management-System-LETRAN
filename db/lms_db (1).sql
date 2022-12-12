-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 19, 2022 at 08:57 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lms_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `isbn` varchar(30) NOT NULL,
  `thumbnail` varchar(250) DEFAULT NULL,
  `title` varchar(250) NOT NULL,
  `description` longtext NOT NULL,
  `series` varchar(20) DEFAULT NULL,
  `year` varchar(20) NOT NULL,
  `quantity` int(11) NOT NULL,
  `author` varchar(100) NOT NULL,
  `e_books` varchar(255) DEFAULT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `isbn`, `thumbnail`, `title`, `description`, `series`, `year`, `quantity`, `author`, `e_books`, `category_id`) VALUES
(10, 'ISBN 10: 0061964360', '41nkw6FDa5L.jpg', 'Pride and Prejudice (Teen Classics)', 'Try', '2', '2000', 16, 'HarperTeen', NULL, 24),
(11, 'ISBN 1088822', '637885ff1fd9ddownload.jfif', 'Diary of Wimp Kid (For Kids and Teens )', 'Diary of Wimp Kid', '2', '2019', 13, 'ABRAMS', NULL, 26),
(12, 'ISBN 1088822566', '63788bd75f77d58690308.jpg', 'Book Lovers (Teen and Kids Lovers of Romance)', 'A troubled young mother yearns for a shot at redemption in this heartbreaking yet hopeful story from #1 New York Times bestselling author Colleen Hoover. After serving five years in prison for a tragic mistake, Kenna Rowan returns to the town where it all went wrong, hoping to reunite with her four-year-old daughter. But the bridges Kenna burned are proving impossible to rebuild. Everyone in her daughter’s life is determined to shut Kenna out, no matter how hard she works to prove herself. The only person who hasn’t closed the door on her completely is Ledger Ward, a local bar owner and one of the few remaining links to Kenna’s daughter. But if anyone were to discover how Ledger is slowly becoming an important part of Kenna’s life, both would risk losing the trust of everyone important to them. The two form a connection despite the pressure surrounding them, but as their romance grows, so does the risk. Kenna must find a way to absolve the mistakes of her past in order to build a future out of hope and healing.', '25', '1837', 15, 'Emily Henry', NULL, 24);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `description` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `description`) VALUES
(24, 'Science'),
(26, 'Filipino');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `date_issued` date NOT NULL,
  `date_returned` date NOT NULL,
  `date_declined` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `school_id` varchar(11) DEFAULT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `role` int(11) NOT NULL,
  `address` text NOT NULL,
  `birthday` date NOT NULL,
  `gender` int(11) NOT NULL,
  `contact` varchar(15) NOT NULL,
  `registration_date` datetime NOT NULL DEFAULT current_timestamp(),
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `school_id`, `username`, `email`, `password`, `first_name`, `middle_name`, `last_name`, `role`, `address`, `birthday`, `gender`, `contact`, `registration_date`, `status`) VALUES
(1, NULL, 'admin', 'admin@gmail.com', '12345678', 'Mr.', NULL, 'Admin', 1, 'admin building', '2022-11-12', 1, '09993478837', '2022-11-12 13:47:32', 1),
(47, '636f5735d90', 'janiicole12', 'joshuavino12@gmail.com', 'bf9f8d1f05dc08cc3b02e8fcf2c2ba57', 'Joshua', 'Jingco', 'Vino', 3, 'Poblacion,Balungao,Pangasinan', '2022-11-23', 1, '09673278142', '2022-11-12 16:20:05', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author_id` (`author`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
