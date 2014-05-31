-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mag 31, 2014 alle 15:55
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dump dei dati per la tabella `Categorized`
--

INSERT INTO `Categorized` (`id`, `idCategory`, `idDocument`) VALUES
(1, -1, -1);

-- --------------------------------------------------------

--
-- Struttura della tabella `Category`
--

CREATE TABLE IF NOT EXISTS `Category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=35 ;

--
-- Dump dei dati per la tabella `Category`
--

INSERT INTO `Category` (`id`, `name`) VALUES
(13, 'cane'),
(2, 'PORN'),
(33, 'PRO VA'),
(27, 'PROVAG'),
(34, 'provagliodiseo'),
(1, 'UNCATEGORIZED');

-- --------------------------------------------------------

--
-- Struttura della tabella `Configuration`
--

CREATE TABLE IF NOT EXISTS `Configuration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parameterName` varchar(50) NOT NULL,
  `value` varchar(50) NOT NULL,
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
  `title` varchar(100) DEFAULT NULL,
  `filename` varchar(300) DEFAULT NULL,
  `extension` varchar(10) DEFAULT NULL,
  `description` varchar(1000) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `isPrivate` tinyint(1) DEFAULT '0',
  `ownerId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dump dei dati per la tabella `Document`
--

INSERT INTO `Document` (`id`, `title`, `filename`, `extension`, `description`, `type`, `date`, `isPrivate`, `ownerId`) VALUES
(1, '', '', '', '', '', '2014-05-30 09:00:41', 0, 0),
(2, '', '', '', '', '', '2014-05-30 09:04:47', 0, -1),
(3, 'la casa nel fosco', 'casa.txt', 'txt', 'breve racconto di 150 pagine', 'AUDIO', '2014-05-30 09:16:38', 0, 0),
(4, NULL, NULL, NULL, NULL, NULL, '2014-05-30 09:24:32', 0, NULL),
(5, '', '', '', '', '', '2014-05-30 09:24:54', 0, 0),
(6, NULL, NULL, NULL, NULL, NULL, '2014-05-30 09:29:38', 0, NULL),
(7, NULL, NULL, NULL, NULL, NULL, '2014-05-30 09:31:00', 0, NULL),
(8, NULL, NULL, NULL, NULL, NULL, '2014-05-30 09:32:11', 0, NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `Tag`
--

CREATE TABLE IF NOT EXISTS `Tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

--
-- Dump dei dati per la tabella `Tag`
--

INSERT INTO `Tag` (`id`, `name`) VALUES
(23, 'primo'),
(26, 'prova26'),
(24, 'secondo');

-- --------------------------------------------------------

--
-- Struttura della tabella `Tagged`
--

CREATE TABLE IF NOT EXISTS `Tagged` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idTag` int(11) NOT NULL,
  `idDocument` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;

--
-- Dump dei dati per la tabella `Tagged`
--

INSERT INTO `Tagged` (`id`, `idTag`, `idDocument`) VALUES
(2, 23, 2);

-- --------------------------------------------------------

--
-- Struttura della tabella `User`
--

CREATE TABLE IF NOT EXISTS `User` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `salt` varchar(50) NOT NULL,
  `mail` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dump dei dati per la tabella `User`
--

INSERT INTO `User` (`id`, `username`, `name`, `surname`, `password`, `salt`, `mail`, `type`) VALUES
(1, 'admin', 'ad', 'min', '44838103a6bfc98c7bc5d60de7a5c83e', 'nosalt', 'admin@admin.it', 'ADMIN');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
