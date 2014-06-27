-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Giu 27, 2014 alle 11:05
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
-- Struttura della tabella `configuration`
--

CREATE TABLE IF NOT EXISTS `configuration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parameterName` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `value` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `parameterName` (`parameterName`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dump dei dati per la tabella `configuration`
--

INSERT INTO `configuration` (`id`, `parameterName`, `value`) VALUES
(1, 'numRighe', '5'),
(2, 'documentPerPage', '5'),
(3, 'uploadDirectory', './ul/'),
(4, 'downloadDirectory', './dl/');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
