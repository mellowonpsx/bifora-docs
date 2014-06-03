-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Giu 03, 2014 alle 11:10
-- Versione del server: 5.6.16
-- PHP Version: 5.5.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `bifora_docs`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `Categorized`
--

CREATE TABLE IF NOT EXISTS `Categorized` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idCategory` int(11) NOT NULL,
  `idDocument` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=191 ;

--
-- Dump dei dati per la tabella `Categorized`
--

INSERT INTO `Categorized` (`id`, `idCategory`, `idDocument`) VALUES
(168, 35, 1),
(176, 35, 2),
(177, 35, 3),
(178, 35, 4),
(179, 35, 5),
(180, 35, 6),
(181, 35, 7),
(182, 35, 8),
(183, 38, 1),
(184, 38, 2),
(185, 38, 3),
(186, 38, 4),
(187, 38, 5),
(188, 38, 6),
(189, 38, 7),
(190, 38, 8);

-- --------------------------------------------------------

--
-- Struttura della tabella `Category`
--

CREATE TABLE IF NOT EXISTS `Category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=39 ;

--
-- Dump dei dati per la tabella `Category`
--

INSERT INTO `Category` (`id`, `name`) VALUES
(38, 'cane'),
(35, 'una categoria');

-- --------------------------------------------------------

--
-- Struttura della tabella `Configuration`
--

CREATE TABLE IF NOT EXISTS `Configuration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parameterName` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `value` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `parameterName` (`parameterName`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dump dei dati per la tabella `Configuration`
--

INSERT INTO `Configuration` (`id`, `parameterName`, `value`) VALUES
(1, 'numRighe', '5');

-- --------------------------------------------------------

--
-- Struttura della tabella `Document`
--

CREATE TABLE IF NOT EXISTS `Document` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `filename` varchar(300) CHARACTER SET utf8 DEFAULT NULL,
  `extension` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `description` varchar(1000) CHARACTER SET utf8 DEFAULT NULL,
  `type` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `isPrivate` tinyint(1) DEFAULT '0',
  `ownerId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dump dei dati per la tabella `Document`
--

INSERT INTO `Document` (`id`, `title`, `filename`, `extension`, `description`, `type`, `date`, `isPrivate`, `ownerId`) VALUES
(1, 'Un libro bruttissimo', '1.txt', 'txt', 'Un libro bruttissimo, che in realtà non si dovrebbe perdere tempo nemmeno a leggere, però lo fanno in molti e questo ha reso ricco l''autore', 'UNKNOWN', '2014-05-30 09:00:41', 1, 0),
(2, 'Fisica quantistica', '1.doc', 'doc', 'Trattato di fisica quantistica dell''esimio professor pomponazzi', 'DOCUMENT', '2014-05-30 09:04:47', 0, 2),
(3, 'Urlo', '1.mp3', 'mp3', 'Registrazione di un urlo', 'AUDIO', '2014-05-30 09:16:38', 1, 0),
(4, 'La spada nella doccia', '1.avi', 'avi', 'Filmato osè anni 70', 'VIDEO', '2014-05-30 09:24:32', 0, 2),
(5, 'Gianni ', '1.mp4', 'mp4', 'Filmato di gianni, in formato non codificato', 'OTHER', '2014-05-30 09:24:54', 1, 0),
(6, 'Foglia', '1.jpg', 'jpg', 'Foto di una foglia di fico', 'PHOTO', '2014-05-30 09:29:38', 0, 2),
(7, 'Rosa', '2.jpg', 'jpg', 'Foto di una rosa bellissima, vista in via le rose di sotto a bressscia', 'PHOTO', '2014-05-30 09:31:00', 0, 2),
(8, 'Pornazzi', '1.rar', 'rar', 'Collezzione di pornazzi dell''autore del sito', 'ARCHIVE', '2014-05-30 09:32:11', 1, 2);

-- --------------------------------------------------------

--
-- Struttura della tabella `Tag`
--

CREATE TABLE IF NOT EXISTS `Tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=34 ;

--
-- Dump dei dati per la tabella `Tag`
--

INSERT INTO `Tag` (`id`, `name`) VALUES
(23, 'primo'),
(31, 'prova26'),
(24, 'secondo'),
(33, 'terzo');

-- --------------------------------------------------------

--
-- Struttura della tabella `Tagged`
--

CREATE TABLE IF NOT EXISTS `Tagged` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idTag` int(11) NOT NULL,
  `idDocument` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=78 ;

--
-- Dump dei dati per la tabella `Tagged`
--

INSERT INTO `Tagged` (`id`, `idTag`, `idDocument`) VALUES
(2, 23, 2),
(76, 31, 2),
(77, 24, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `User`
--

CREATE TABLE IF NOT EXISTS `User` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `surname` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `password` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `salt` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `mail` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `type` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dump dei dati per la tabella `User`
--

INSERT INTO `User` (`id`, `username`, `name`, `surname`, `password`, `salt`, `mail`, `type`) VALUES
(1, 'admin', 'ad', 'min', '44838103a6bfc98c7bc5d60de7a5c83e', 'nosalt', 'admin@admin.it', 'ADMIN'),
(2, 'utente', 'utente', 'normale', '505d61a250ed9f1cd28a44cd1fba5d39', 'tipodisalemotlolungoemoltocasualeautogenerato111', 'utente@utente.it', 'USER');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
