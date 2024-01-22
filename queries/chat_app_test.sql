-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Gen 23, 2024 alle 00:09
-- Versione del server: 10.4.28-MariaDB
-- Versione PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `chat_app_test`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `chat`
--

CREATE TABLE `chat` (
  `id_chat` int(11) NOT NULL,
  `statoChat` int(11) DEFAULT NULL,
  `partecipante1` int(11) DEFAULT NULL,
  `partecipante2` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `chat`
--

INSERT INTO `chat` (`id_chat`, `statoChat`, `partecipante1`, `partecipante2`) VALUES
(32, 1, 1, 2),
(34, 1, 1, 3);

-- --------------------------------------------------------

--
-- Struttura della tabella `messaggi`
--

CREATE TABLE `messaggi` (
  `id_messaggio` int(11) NOT NULL,
  `utente_id` int(11) DEFAULT NULL,
  `contenuto` text NOT NULL,
  `ora_invio` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `letto` tinyint(4) NOT NULL,
  `consegnato` tinyint(4) NOT NULL,
  `chat_id` int(11) NOT NULL,
  `oraVisualizzazione` timestamp NOT NULL DEFAULT current_timestamp(),
  `tipo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `messaggi`
--

INSERT INTO `messaggi` (`id_messaggio`, `utente_id`, `contenuto`, `ora_invio`, `letto`, `consegnato`, `chat_id`, `oraVisualizzazione`, `tipo`) VALUES
(1, 1, 'cdwad', '2024-01-14 11:59:19', 0, 0, 32, '2024-01-14 11:59:19', 1),
(2, 1, 'cwada', '2024-01-14 11:59:22', 0, 0, 32, '2024-01-14 11:59:22', 1),
(3, 1, 'dawda', '2024-01-18 22:21:41', 0, 0, 32, '2024-01-18 22:21:41', 1),
(4, 1, 'dawda', '2024-01-18 22:22:16', 0, 0, 32, '2024-01-18 22:22:16', 1),
(18, 1, 'dwa', '2024-01-18 22:27:45', 0, 0, 32, '2024-01-18 22:27:45', 1),
(20, 1, 'dawd', '2024-01-18 22:31:16', 0, 0, 32, '2024-01-18 22:31:16', 1),
(21, 1, 'tghf', '2024-01-18 22:32:43', 0, 0, 32, '2024-01-18 22:32:43', 1),
(22, 1, 'tghf', '2024-01-18 22:35:13', 0, 0, 32, '2024-01-18 22:35:13', 1),
(23, 1, 'tghf', '2024-01-18 22:35:18', 0, 0, 32, '2024-01-18 22:35:18', 1),
(24, 1, 'tghf', '2024-01-18 22:35:22', 0, 0, 32, '2024-01-18 22:35:22', 1),
(25, 1, 'tghf', '2024-01-18 22:35:26', 0, 0, 32, '2024-01-18 22:35:26', 1),
(26, 1, 'tghf', '2024-01-18 22:35:30', 0, 0, 32, '2024-01-18 22:35:30', 1),
(27, 1, 'dawd', '2024-01-18 22:35:33', 0, 0, 32, '2024-01-18 22:35:33', 1),
(28, 1, 'dawd', '2024-01-18 22:36:09', 0, 0, 32, '2024-01-18 22:36:09', 1),
(29, 1, 'dawd', '2024-01-18 22:36:14', 0, 0, 32, '2024-01-18 22:36:14', 1),
(30, 1, 'dawd', '2024-01-18 22:36:21', 0, 0, 32, '2024-01-18 22:36:21', 1),
(31, 1, 'dawd', '2024-01-18 22:36:28', 0, 0, 32, '2024-01-18 22:36:28', 1),
(48, 1, 'dwad', '2024-01-18 22:42:11', 0, 0, 34, '2024-01-18 22:42:11', 1),
(49, 1, 'fas', '2024-01-18 22:43:08', 0, 0, 34, '2024-01-18 22:43:08', 1),
(50, 1, 'daw', '2024-01-18 22:43:09', 0, 0, 34, '2024-01-18 22:43:09', 1),
(51, 1, 'daw', '2024-01-18 22:45:08', 0, 0, 34, '2024-01-18 22:45:08', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `stati`
--

CREATE TABLE `stati` (
  `id_stato` tinyint(4) NOT NULL,
  `descrizione` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `stati`
--

INSERT INTO `stati` (`id_stato`, `descrizione`) VALUES
(1, 'Online'),
(2, 'Offline'),
(3, 'Deactivated');

-- --------------------------------------------------------

--
-- Struttura della tabella `statochat`
--

CREATE TABLE `statochat` (
  `id_tipo` int(11) NOT NULL,
  `descrizione` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `statochat`
--

INSERT INTO `statochat` (`id_tipo`, `descrizione`) VALUES
(1, 'Active'),
(2, 'Deactivated');

-- --------------------------------------------------------

--
-- Struttura della tabella `tipomessaggio`
--

CREATE TABLE `tipomessaggio` (
  `id_tipo` int(11) NOT NULL,
  `descrizione` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `tipomessaggio`
--

INSERT INTO `tipomessaggio` (`id_tipo`, `descrizione`) VALUES
(1, 'Testo'),
(2, 'Immagine'),
(3, 'Vocale'),
(4, 'File');

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

CREATE TABLE `utenti` (
  `id_utente` int(11) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `cognome` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `stato` tinyint(4) NOT NULL,
  `ultimo_accesso` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `password` varchar(255) NOT NULL,
  `salt` varchar(255) NOT NULL,
  `remember_me_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `utenti`
--

INSERT INTO `utenti` (`id_utente`, `mail`, `nome`, `cognome`, `username`, `stato`, `ultimo_accesso`, `password`, `salt`, `remember_me_token`) VALUES
(1, 'mail@gmail.com', 'Lorenzo', 'D\'Aniello', 'Fantey', 1, '2024-01-20 18:54:20', '$2y$10$6/F5Ocx7MvuPDrCnnwD4kefGzc1Kw2jFXzXMP4U0KgIODGlEqUfIi', '499fbbd994dd41eef170f95442629cd3', '371fb35422dd5a91aadc1915dc7da38009a9543a95669b8f227d5cb8aea12a0f'),
(2, 'lorida1602@gmail.com', 'Salvatore', 'D\'Aniello', 'Lorenzo', 1, '2024-01-11 21:50:56', '$2y$10$H0GZo9BOCtFtQCJ7dCsj..0AvexJunN8PS.bC7qNDgQ9XWwOFVODO', '87c92085accefed1c73bb7c26705365c', 'f8d9b4db79abaef2f4692feb36bf1973ad9e6f093d6e25a73426fcb01d17c1b7'),
(3, 'user1@example.com', 'John', 'Doe', 'john_doe', 1, '2024-01-18 20:07:21', 'password_hash_1', 'salt_1', NULL),
(4, 'user2@example.com', 'Jane', 'Smith', 'jane_smith', 2, '2024-01-18 20:07:21', 'password_hash_2', 'salt_2', NULL);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`id_chat`),
  ADD KEY `partecipante1` (`partecipante1`),
  ADD KEY `partecipante2` (`partecipante2`),
  ADD KEY `statoChat` (`statoChat`);

--
-- Indici per le tabelle `messaggi`
--
ALTER TABLE `messaggi`
  ADD PRIMARY KEY (`id_messaggio`),
  ADD KEY `fk_chat` (`chat_id`),
  ADD KEY `fk_utente` (`utente_id`),
  ADD KEY `fk_tipo` (`tipo`);

--
-- Indici per le tabelle `stati`
--
ALTER TABLE `stati`
  ADD PRIMARY KEY (`id_stato`);

--
-- Indici per le tabelle `statochat`
--
ALTER TABLE `statochat`
  ADD PRIMARY KEY (`id_tipo`);

--
-- Indici per le tabelle `tipomessaggio`
--
ALTER TABLE `tipomessaggio`
  ADD PRIMARY KEY (`id_tipo`);

--
-- Indici per le tabelle `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`id_utente`),
  ADD UNIQUE KEY `mail` (`mail`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `fk_stato` (`stato`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `chat`
--
ALTER TABLE `chat`
  MODIFY `id_chat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT per la tabella `messaggi`
--
ALTER TABLE `messaggi`
  MODIFY `id_messaggio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT per la tabella `statochat`
--
ALTER TABLE `statochat`
  MODIFY `id_tipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT per la tabella `tipomessaggio`
--
ALTER TABLE `tipomessaggio`
  MODIFY `id_tipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT per la tabella `utenti`
--
ALTER TABLE `utenti`
  MODIFY `id_utente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `chat`
--
ALTER TABLE `chat`
  ADD CONSTRAINT `chat_ibfk_1` FOREIGN KEY (`partecipante1`) REFERENCES `utenti` (`id_utente`),
  ADD CONSTRAINT `chat_ibfk_2` FOREIGN KEY (`partecipante2`) REFERENCES `utenti` (`id_utente`),
  ADD CONSTRAINT `chat_ibfk_3` FOREIGN KEY (`statoChat`) REFERENCES `statochat` (`id_tipo`);

--
-- Limiti per la tabella `messaggi`
--
ALTER TABLE `messaggi`
  ADD CONSTRAINT `fk_chat` FOREIGN KEY (`chat_id`) REFERENCES `chat` (`id_chat`),
  ADD CONSTRAINT `fk_tipo` FOREIGN KEY (`tipo`) REFERENCES `tipomessaggio` (`id_tipo`),
  ADD CONSTRAINT `fk_utente` FOREIGN KEY (`utente_id`) REFERENCES `utenti` (`id_utente`);

--
-- Limiti per la tabella `utenti`
--
ALTER TABLE `utenti`
  ADD CONSTRAINT `fk_stato` FOREIGN KEY (`stato`) REFERENCES `stati` (`id_stato`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
