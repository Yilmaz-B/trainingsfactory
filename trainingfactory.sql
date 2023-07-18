-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Gegenereerd op: 14 jun 2023 om 11:49
-- Serverversie: 10.4.27-MariaDB
-- PHP-versie: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `trainingfactory`
--
CREATE DATABASE IF NOT EXISTS `trainingfactory` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `trainingfactory`;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Gegevens worden geëxporteerd voor tabel `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20230612200221', '2023-06-12 20:02:30', 30),
('DoctrineMigrations\\Version20230612201326', '2023-06-12 20:13:34', 141),
('DoctrineMigrations\\Version20230613110336', '2023-06-13 11:03:42', 27);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `lesson`
--

CREATE TABLE `lesson` (
  `id` int(11) NOT NULL,
  `training_id` int(11) NOT NULL,
  `instructor_id` int(11) NOT NULL,
  `time` time NOT NULL,
  `date` date NOT NULL,
  `location` varchar(255) NOT NULL,
  `max_persons` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `registration`
--

CREATE TABLE `registration` (
  `id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `participants_id` int(11) NOT NULL,
  `payment` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `training`
--

CREATE TABLE `training` (
  `id` int(11) NOT NULL,
  `decription` varchar(255) NOT NULL,
  `duration` int(11) NOT NULL,
  `extra_costs` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(180) NOT NULL,
  `roles` longtext NOT NULL COMMENT '(DC2Type:json)',
  `password` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `dateofbirth` date NOT NULL,
  `hiring_date` date DEFAULT NULL,
  `salary` decimal(8,2) DEFAULT NULL,
  `street` varchar(255) DEFAULT NULL,
  `place` varchar(255) DEFAULT NULL,
  `social_sec_number` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Gegevens worden geëxporteerd voor tabel `user`
--

INSERT INTO `user` (`id`, `username`, `roles`, `password`, `firstname`, `lastname`, `dateofbirth`, `hiring_date`, `salary`, `street`, `place`, `social_sec_number`) VALUES
(1, 'huwimana', '[\"ROLE_ADMIN\"]', '$2y$13$SwCdFVx3H505WDOKt0Zsy.rUu07flzAO9KJ6CUFJL9BttfeqfJ18e', 'Hussein', 'Uwimana', '2000-02-20', NULL, NULL, NULL, NULL, NULL),
(2, 'test', '[\"ROLE_CUSTOMER\"]', '$2y$13$SwCdFVx3H505WDOKt0Zsy.rUu07flzAO9KJ6CUFJL9BttfeqfJ18e', 'klant', 'klant', '2000-02-20', NULL, NULL, 'tinwerf 16', 'Den Haag', NULL),
(3, 'ben', '[\"ROLE_INSTRUCTOR\"]', '$2y$13$/oJx9NYjUGqU.aU/CLiOn.l2VYLLyTScPnyW36vvn5pnJHwj8xuPq', 'ben', 'ten', '2000-02-20', '2023-06-15', '5000.00', NULL, NULL, 123456789),
(6, 'husimana', '[\"ROLE_CUSTOMER\"]', '$2y$13$wNeke..0cXffVW/ozifmK.xqUZ3cunv6HePkdNutW1XX/c8H4uTl.', 'terfd', 'evfd', '2023-06-01', NULL, NULL, 'erfd', 'qrfecfd', NULL),
(9, 'rfewdsrfvf', '[\"ROLE_CUSTOMER\"]', '$2y$13$9Bzb3r4ns0Bcvq.KdD3Rr.45RpZktDCZr6Xt07Y/ULWKTiGo5KAja', 'frews', 'r3f', '2023-06-01', NULL, NULL, '42', 'eed', NULL),
(14, 'kjmn', '[\"ROLE_CUSTOMER\"]', '$2y$13$lKZLRqKnBW/7GDzvZ.iND.sBOiAIZbwONxfeCOoPWok8ai7wwWfmu', 'jk', 'jk', '2023-06-02', NULL, NULL, 'oubjk', 'job', NULL),
(15, 'fred', '[\"ROLE_CUSTOMER\"]', '$2y$13$5fFATn7.JGhEGRFfhA8Yx.Ez/ELqa1SFfa.sWMimL3wk2OdQB9nly', 'red', 'refcd', '2023-06-02', NULL, NULL, 'refd', 'red', NULL),
(16, 'nknbjhtg', '[\"ROLE_CUSTOMER\"]', '$2y$13$0SpIzBfIdKphf9Kauv8Ni.s1aEKjZHuMAGSqv/4Jp4spS.ZZfNP46', 'trbgfv', 'tgfv', '2023-06-03', NULL, NULL, 'rtgfvc', 'rtgf', NULL),
(17, 'trend', '[\"ROLE_CUSTOMER\"]', '$2y$13$dDrlg275.zwWIRskzppv5.WWgO/dRFLqjz7uoyfo1mpoRd2wPhLoO', '4gtrfed', '4rfe', '2023-06-01', NULL, NULL, '4reffd', 'rfe', NULL),
(18, 'gereed', '[\"ROLE_CUSTOMER\"]', '$2y$13$Ude/RXG/fkYptsMs2y5A2.CH4yMKhGTkC/L6fQ08SyNH6mN3e/L0y', 'rfedcs', 'rfedcs', '2023-06-02', NULL, NULL, 'freds', 'reeds', NULL);

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Indexen voor tabel `lesson`
--
ALTER TABLE `lesson`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_F87474F3BEFD98D1` (`training_id`),
  ADD KEY `IDX_F87474F38C4FC193` (`instructor_id`);

--
-- Indexen voor tabel `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Indexen voor tabel `registration`
--
ALTER TABLE `registration`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_62A8A7A7CDF80196` (`lesson_id`),
  ADD KEY `IDX_62A8A7A7838709D5` (`participants_id`);

--
-- Indexen voor tabel `training`
--
ALTER TABLE `training`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649F85E0677` (`username`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `lesson`
--
ALTER TABLE `lesson`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `registration`
--
ALTER TABLE `registration`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `training`
--
ALTER TABLE `training`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `lesson`
--
ALTER TABLE `lesson`
  ADD CONSTRAINT `FK_F87474F38C4FC193` FOREIGN KEY (`instructor_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_F87474F3BEFD98D1` FOREIGN KEY (`training_id`) REFERENCES `training` (`id`);

--
-- Beperkingen voor tabel `registration`
--
ALTER TABLE `registration`
  ADD CONSTRAINT `FK_62A8A7A7838709D5` FOREIGN KEY (`participants_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_62A8A7A7CDF80196` FOREIGN KEY (`lesson_id`) REFERENCES `lesson` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
