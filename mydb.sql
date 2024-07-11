-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2024-07-11 21:04:16
-- サーバのバージョン： 10.4.32-MariaDB
-- PHP のバージョン: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `mydb`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `baseball_scores`
--

CREATE TABLE `baseball_scores` (
  `id` int(11) NOT NULL,
  `inning` int(11) DEFAULT NULL,
  `score` varchar(255) DEFAULT NULL,
  `game` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `baseball_scores`
--

INSERT INTO `baseball_scores` (`id`, `inning`, `score`, `game`) VALUES
(1, 1, '0', 0),
(2, 1, '0', 1),
(3, 2, '1', 0),
(4, 2, '1', 1),
(5, 3, '0', 0),
(6, 3, '0', 1),
(7, 4, '2', 0),
(8, 4, '0', 1),
(9, 5, '0', 0),
(10, 5, '0', 1),
(11, 6, '0', 0),
(12, 6, '5', 1),
(13, 7, '1', 0),
(14, 7, '0', 1),
(154, 8, '0', 0);

-- --------------------------------------------------------

--
-- テーブルの構造 `images`
--

CREATE TABLE `images` (
  `id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `images`
--

INSERT INTO `images` (`id`, `url`) VALUES
(313, '1塁.jpg'),
(314, '満塁.jpg'),
(315, '1塁.jpg'),
(316, '1塁.jpg'),
(317, 'ランナーなし.jpg'),
(318, '満塁.jpg'),
(319, '2.3塁.jpg'),
(320, '1.3塁.jpg'),
(321, 'ランナーなし.jpg'),
(322, '満塁.jpg'),
(323, '2.3塁.jpg');

-- --------------------------------------------------------

--
-- テーブルの構造 `images_table`
--

CREATE TABLE `images_table` (
  `id` int(11) NOT NULL,
  `inning` varchar(255) NOT NULL,
  `image` longblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `red_circles`
--

CREATE TABLE `red_circles` (
  `id` int(11) NOT NULL,
  `x_position` int(11) DEFAULT NULL,
  `y_position` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `red_circles`
--

INSERT INTO `red_circles` (`id`, `x_position`, `y_position`) VALUES
(14, 20, 20);

-- --------------------------------------------------------

--
-- テーブルの構造 `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(5, '5678', '$2y$10$Kf7UuKqujzVmgPQdtpz/Euk4tkP3DBYfcQofKT3UTG45onEprBvTS'),
(9, '0319', '$2y$10$k.rLLeWnTGvWhEWlCxJf6.iYJq8UySw0JP321zJzO6tvhIQFKDvLi');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `baseball_scores`
--
ALTER TABLE `baseball_scores`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `images_table`
--
ALTER TABLE `images_table`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `red_circles`
--
ALTER TABLE `red_circles`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `baseball_scores`
--
ALTER TABLE `baseball_scores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=155;

--
-- テーブルの AUTO_INCREMENT `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=324;

--
-- テーブルの AUTO_INCREMENT `images_table`
--
ALTER TABLE `images_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `red_circles`
--
ALTER TABLE `red_circles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- テーブルの AUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
