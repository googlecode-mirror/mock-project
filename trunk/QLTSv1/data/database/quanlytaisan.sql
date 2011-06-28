-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 26, 2011 at 03:40 AM
-- Server version: 5.0.22
-- PHP Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `quanlytaisan`
--

-- --------------------------------------------------------

--
-- Table structure for table `historyinfor`
--

CREATE TABLE IF NOT EXISTS `historyinfor` (
  `HistoryID` int(10) unsigned NOT NULL auto_increment,
  `LUserID` int(8) unsigned NOT NULL,
  `RUserID` int(8) unsigned NOT NULL,
  `ItemID` int(8) unsigned NOT NULL,
  `Detail` varchar(255) NOT NULL,
  `Date` date NOT NULL,
  PRIMARY KEY  (`HistoryID`),
  KEY `LUserID` (`LUserID`),
  KEY `RUserID` (`RUserID`),
  KEY `ItemID` (`ItemID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `historyinfor`
--


-- --------------------------------------------------------

--
-- Table structure for table `iteminfor`
--

CREATE TABLE IF NOT EXISTS `iteminfor` (
  `ItemID` int(8) unsigned NOT NULL,
  `Ma_tai_san` int(10) NOT NULL,
  `Ten_tai_san` int(10) NOT NULL,
  `Description` varchar(255) NOT NULL,
  `Type` enum('0','1') NOT NULL default '0',
  `Start Date` date NOT NULL,
  `Price` int(10) NOT NULL,
  `WarrantyTime` int(3) NOT NULL,
  `Status` enum('0','1','2') NOT NULL default '0',
  `Place` varchar(12) NOT NULL,
  PRIMARY KEY  (`ItemID`),
  UNIQUE KEY `Ma_tai_san` (`Ma_tai_san`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `iteminfor`
--


-- --------------------------------------------------------

--
-- Table structure for table `loaninfor`
--

CREATE TABLE IF NOT EXISTS `loaninfor` (
  `ItemID` int(8) unsigned NOT NULL auto_increment,
  `UserID` int(8) unsigned NOT NULL,
  `Detail` varchar(255) NOT NULL,
  `Date` date NOT NULL,
  PRIMARY KEY  (`ItemID`),
  KEY `UserID` (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `loaninfor`
--


-- --------------------------------------------------------

--
-- Table structure for table `memberinfor`
--

CREATE TABLE IF NOT EXISTS `memberinfor` (
  `UserID` int(8) unsigned NOT NULL auto_increment,
  `Username` varchar(20) NOT NULL,
  `Password` varchar(20) NOT NULL,
  `Role` enum('3','2','1','0') NOT NULL default '3',
  `Email` varchar(50) NOT NULL,
  `FullName` varchar(50) NOT NULL,
  `Group` varchar(5) NOT NULL,
  `Birthday` date default NULL,
  `Phone` varchar(30) default NULL,
  `Address` varchar(255) default NULL,
  PRIMARY KEY  (`UserID`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `memberinfor`
--


-- --------------------------------------------------------

--
-- Table structure for table `messageinfor`
--

CREATE TABLE IF NOT EXISTS `messageinfor` (
  `MessageID` int(10) unsigned NOT NULL auto_increment,
  `SendID` int(8) unsigned NOT NULL,
  `ReceiveID` int(8) unsigned NOT NULL,
  `Detail` varchar(255) NOT NULL,
  `Date` date NOT NULL,
  PRIMARY KEY  (`MessageID`),
  KEY `SendID` (`SendID`),
  KEY `ReceiveID` (`ReceiveID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `messageinfor`
--


-- --------------------------------------------------------

--
-- Table structure for table `requestinfor`
--

CREATE TABLE IF NOT EXISTS `requestinfor` (
  `RequestID` int(10) unsigned NOT NULL auto_increment,
  `UserID` int(8) unsigned NOT NULL,
  `ItemID` int(8) unsigned NOT NULL,
  `Type` enum('0','1') NOT NULL,
  `Detail` varchar(255) NOT NULL,
  `Date` date NOT NULL,
  `Accept` enum('0','1','2') NOT NULL default '0',
  PRIMARY KEY  (`RequestID`),
  KEY `UserID` (`UserID`),
  KEY `ItemID` (`ItemID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `requestinfor`
--


-- --------------------------------------------------------

--
-- Table structure for table `upgradeinfor`
--

CREATE TABLE IF NOT EXISTS `upgradeinfor` (
  `UpgradeID` int(10) unsigned NOT NULL auto_increment,
  `UserID` int(8) unsigned NOT NULL,
  `ManagerID` int(8) unsigned NOT NULL,
  `ItemID` int(8) unsigned NOT NULL,
  `Detail` varchar(255) NOT NULL,
  `Date` date NOT NULL,
  PRIMARY KEY  (`UpgradeID`),
  KEY `UserID` (`UserID`),
  KEY `ManagerID` (`ManagerID`),
  KEY `ItemID` (`ItemID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `upgradeinfor`
--


--
-- Constraints for dumped tables
--

--
-- Constraints for table `historyinfor`
--
ALTER TABLE `historyinfor`
  ADD CONSTRAINT `ItemID` FOREIGN KEY (`ItemID`) REFERENCES `iteminfor` (`ItemID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `LUserID` FOREIGN KEY (`LUserID`) REFERENCES `memberinfor` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `RUserID` FOREIGN KEY (`RUserID`) REFERENCES `memberinfor` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `loaninfor`
--
ALTER TABLE `loaninfor`
  ADD CONSTRAINT `loaninfor_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `memberinfor` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `loaninfor_ibfk_1` FOREIGN KEY (`ItemID`) REFERENCES `iteminfor` (`ItemID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `messageinfor`
--
ALTER TABLE `messageinfor`
  ADD CONSTRAINT `messageinfor_ibfk_2` FOREIGN KEY (`ReceiveID`) REFERENCES `memberinfor` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `messageinfor_ibfk_1` FOREIGN KEY (`SendID`) REFERENCES `memberinfor` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `requestinfor`
--
ALTER TABLE `requestinfor`
  ADD CONSTRAINT `requestinfor_ibfk_2` FOREIGN KEY (`ItemID`) REFERENCES `iteminfor` (`ItemID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `requestinfor_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `memberinfor` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `upgradeinfor`
--
ALTER TABLE `upgradeinfor`
  ADD CONSTRAINT `upgradeinfor_ibfk_3` FOREIGN KEY (`ItemID`) REFERENCES `iteminfor` (`ItemID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `upgradeinfor_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `memberinfor` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `upgradeinfor_ibfk_2` FOREIGN KEY (`ManagerID`) REFERENCES `memberinfor` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
