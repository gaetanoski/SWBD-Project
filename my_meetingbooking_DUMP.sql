-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 14, 2024 at 03:23 PM
-- Server version: 10.4.13-MariaDB
-- PHP Version: 7.2.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `my_meetingbooking`
--

-- --------------------------------------------------------

--
-- Table structure for table `commenti`
--

CREATE TABLE `commenti` (
  `cf_dipendente` varchar(16) NOT NULL,
  `codice_riunione` varchar(6) NOT NULL,
  `commento` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `dipendenti`
--

CREATE TABLE `dipendenti` (
  `codiceFiscale` varchar(16) NOT NULL,
  `nome` varchar(45) NOT NULL,
  `cognome` varchar(45) NOT NULL,
  `data_nascita` date NOT NULL,
  `email` varchar(100) NOT NULL,
  `tipo` int(11) NOT NULL,
  `password` varchar(512) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `dipendenti`
--

INSERT INTO `dipendenti` (`codiceFiscale`, `nome`, `cognome`, `data_nascita`, `email`, `tipo`, `password`) VALUES
('MPRGTN03B24B963U', 'Gaetano', 'Improta', '2003-02-24', 'gaetano.improta@azienda.it', 3, '$2a$07$usesomesillystringforeIjsYNntBt1rkAJukNqh5mZhR4pRkSgi'),
('MPRNCL69L17L150P', 'Luigi', 'Bianchi', '2002-10-15', 'luigi.bianchi@azienda.it', 1, '$2a$07$usesomesillystringforeIjsYNntBt1rkAJukNqh5mZhR4pRkSgi'),
('MRROSN02B10B894Z', 'Mario', 'Rossi', '2001-10-10', 'mario.rossi@azienda.it', 2, '$2a$07$usesomesillystringforeIjsYNntBt1rkAJukNqh5mZhR4pRkSgi');

-- --------------------------------------------------------

--
-- Table structure for table `partecipazioni`
--

CREATE TABLE `partecipazioni` (
  `cf_dipendente` varchar(16) NOT NULL,
  `codice_riunione` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `partecipazioni`
--

INSERT INTO `partecipazioni` (`cf_dipendente`, `codice_riunione`) VALUES
('MPRGTN03B24B963U', 'tN3fA5'),
('MPRGTN03B24B963U', 'PsNNVP'),
('MPRGTN03B24B963U', 'pcGah1'),
('MPRGTN03B24B963U', '1s1Fxk'),
('MRROSN02B10B894Z', 'tN3fA5'),
('MRROSN02B10B894Z', '1s1Fxk'),
('MRROSN02B10B894Z', 'LTR5CA'),
('MPRNCL69L17L150P', 'tN3fA5'),
('MPRNCL69L17L150P', 'PsNNVP');

-- --------------------------------------------------------

--
-- Table structure for table `riunioni`
--

CREATE TABLE `riunioni` (
  `codice` varchar(6) NOT NULL,
  `nome` varchar(45) NOT NULL,
  `descrizione` longtext NOT NULL,
  `data_riunione` date NOT NULL,
  `ora_inizio` time NOT NULL,
  `ora_fine` time NOT NULL,
  `cf_organizzatore` varchar(16) NOT NULL,
  `ID_sala` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `riunioni`
--

INSERT INTO `riunioni` (`codice`, `nome`, `descrizione`, `data_riunione`, `ora_inizio`, `ora_fine`, `cf_organizzatore`, `ID_sala`) VALUES
('1s1Fxk', 'Riunione progetto \"Z\"', ' ...', '2024-07-19', '14:00:00', '16:00:00', 'MPRGTN03B24B963U', 3),
('LTR5CA', 'Riunione \"Zeta\"', ' ...', '2024-07-16', '14:00:00', '16:00:00', 'MRROSN02B10B894Z', 3),
('pcGah1', 'Riunione progetto \"Y\"', ' ...', '2024-07-22', '10:00:00', '12:00:00', 'MPRGTN03B24B963U', 2),
('PsNNVP', 'Riunione progetto \"X\"', ' ...', '2024-07-16', '10:00:00', '12:00:00', 'MPRGTN03B24B963U', 3),
('tN3fA5', 'Riunione riguardo progetto interno', ' ...', '2024-07-18', '12:00:00', '14:00:00', 'MPRGTN03B24B963U', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sale_riunioni`
--

CREATE TABLE `sale_riunioni` (
  `idSala` int(11) NOT NULL,
  `capienza` int(11) NOT NULL,
  `nome_sala` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sale_riunioni`
--

INSERT INTO `sale_riunioni` (`idSala`, `capienza`, `nome_sala`) VALUES
(1, 10, 'MeetingRoom1'),
(2, 20, 'MeetingRoom2'),
(3, 10, 'MeetingRoom3');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `commenti`
--
ALTER TABLE `commenti`
  ADD KEY `cf_dipendente` (`cf_dipendente`),
  ADD KEY `codice_riunione` (`codice_riunione`);

--
-- Indexes for table `dipendenti`
--
ALTER TABLE `dipendenti`
  ADD PRIMARY KEY (`codiceFiscale`);

--
-- Indexes for table `partecipazioni`
--
ALTER TABLE `partecipazioni`
  ADD KEY `cf_dipendente` (`cf_dipendente`),
  ADD KEY `codice_riunione` (`codice_riunione`);

--
-- Indexes for table `riunioni`
--
ALTER TABLE `riunioni`
  ADD PRIMARY KEY (`codice`),
  ADD KEY `cf_organizzatore` (`cf_organizzatore`),
  ADD KEY `ID_sala` (`ID_sala`);

--
-- Indexes for table `sale_riunioni`
--
ALTER TABLE `sale_riunioni`
  ADD PRIMARY KEY (`idSala`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `partecipazioni`
--
ALTER TABLE `partecipazioni`
  ADD CONSTRAINT `cf_dipendente` FOREIGN KEY (`cf_dipendente`) REFERENCES `dipendenti` (`codiceFiscale`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `codice_riunione` FOREIGN KEY (`codice_riunione`) REFERENCES `riunioni` (`codice`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `riunioni`
--
ALTER TABLE `riunioni`
  ADD CONSTRAINT `ID_sala` FOREIGN KEY (`ID_sala`) REFERENCES `sale_riunioni` (`idSala`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cf_organizzatore` FOREIGN KEY (`cf_organizzatore`) REFERENCES `dipendenti` (`codiceFiscale`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
