-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 10. Feb 2023 um 13:34
-- Server-Version: 10.4.24-MariaDB
-- PHP-Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `schule_db`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tbl_klasse`
--

CREATE TABLE `tbl_klasse` (
  `IDKlasse` int(11) NOT NULL,
  `Bezeichnung` varchar(32) NOT NULL,
  `FIDRaum` int(11) DEFAULT NULL,
  `FIDLehrer` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `tbl_klasse`
--

INSERT INTO `tbl_klasse` (`IDKlasse`, `Bezeichnung`, `FIDRaum`, `FIDLehrer`) VALUES
(1, '1A', 1, 2),
(2, '2A', 2, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tbl_lehrer`
--

CREATE TABLE `tbl_lehrer` (
  `IDLehrer` int(11) NOT NULL,
  `Vorname` varchar(64) NOT NULL,
  `Nachname` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `tbl_lehrer`
--

INSERT INTO `tbl_lehrer` (`IDLehrer`, `Vorname`, `Nachname`) VALUES
(1, 'Herr', 'Lehrer'),
(2, 'Frau', 'Lehrerin');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tbl_raeume`
--

CREATE TABLE `tbl_raeume` (
  `IDRaum` int(11) NOT NULL,
  `Bezeichnung` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `tbl_raeume`
--

INSERT INTO `tbl_raeume` (`IDRaum`, `Bezeichnung`) VALUES
(1, 'Raum 1'),
(2, 'Raum 2');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tbl_schueler`
--

CREATE TABLE `tbl_schueler` (
  `ID_Schueler` int(11) NOT NULL,
  `Vorname` varchar(64) NOT NULL,
  `Nachname` varchar(64) NOT NULL,
  `GebDat` date NOT NULL,
  `FIDKlasse` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `tbl_schueler`
--

INSERT INTO `tbl_schueler` (`ID_Schueler`, `Vorname`, `Nachname`, `GebDat`, `FIDKlasse`) VALUES
(1, 'Pert', 'Gaben', '2023-02-08', 1),
(2, 'Gon', 'Freaks', '2023-02-01', 1),
(3, 'Medin', 'Mustafa', '2004-02-12', 2),
(4, 'Jessi', 'Ka', '2014-02-05', 2),
(5, 'Herbert', 'Bert', '2023-01-03', 1);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `tbl_klasse`
--
ALTER TABLE `tbl_klasse`
  ADD PRIMARY KEY (`IDKlasse`) USING BTREE,
  ADD KEY `FIDRaum` (`FIDRaum`),
  ADD KEY `FIDLehrer` (`FIDLehrer`);

--
-- Indizes für die Tabelle `tbl_lehrer`
--
ALTER TABLE `tbl_lehrer`
  ADD PRIMARY KEY (`IDLehrer`);

--
-- Indizes für die Tabelle `tbl_raeume`
--
ALTER TABLE `tbl_raeume`
  ADD PRIMARY KEY (`IDRaum`);

--
-- Indizes für die Tabelle `tbl_schueler`
--
ALTER TABLE `tbl_schueler`
  ADD PRIMARY KEY (`ID_Schueler`),
  ADD KEY `FIDKlasse` (`FIDKlasse`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `tbl_klasse`
--
ALTER TABLE `tbl_klasse`
  MODIFY `IDKlasse` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `tbl_lehrer`
--
ALTER TABLE `tbl_lehrer`
  MODIFY `IDLehrer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `tbl_raeume`
--
ALTER TABLE `tbl_raeume`
  MODIFY `IDRaum` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `tbl_schueler`
--
ALTER TABLE `tbl_schueler`
  MODIFY `ID_Schueler` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `tbl_klasse`
--
ALTER TABLE `tbl_klasse`
  ADD CONSTRAINT `tbl_klasse_ibfk_1` FOREIGN KEY (`FIDLehrer`) REFERENCES `tbl_lehrer` (`IDLehrer`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_klasse_ibfk_2` FOREIGN KEY (`FIDRaum`) REFERENCES `tbl_raeume` (`IDRaum`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints der Tabelle `tbl_schueler`
--
ALTER TABLE `tbl_schueler`
  ADD CONSTRAINT `tbl_schueler_ibfk_1` FOREIGN KEY (`FIDKlasse`) REFERENCES `tbl_klasse` (`IDKlasse`) ON DELETE NO ACTION ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
