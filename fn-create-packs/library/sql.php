<?php
$tblPlayers = '
CREATE TABLE `pcklcktblPlayers` (
  `fldId` int(11) unsigned NOT NULL,
  `fldPlayerData` text NOT NULL,
  `fldPSMin` int(11) unsigned NOT NULL,
  `fldPSMax` int(11) unsigned NOT NULL,
  `fldXBMin` int(11) unsigned NOT NULL,
  `fldXBMax` int(11) unsigned NOT NULL,
  `fldXBQuickSell` int(11) unsigned NOT NULL,
  `fldPSQuickSell` int(11) unsigned NOT NULL,
  `fldType` varchar(18) NOT NULL,
  `fldLastUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`fldId`),
  KEY `fldType` (`fldType`(12)),
  KEY `fldXBMin_fldXBQuickSell` (`fldXBMin`,`fldXBQuickSell`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
';

$tblStatistics = '
CREATE TABLE `pcklckPackStatistics` (
  `fldId` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `fldType` varchar(22) NOT NULL,
  `fldValue` int(11) unsigned NOT NULL,
  `fldstdValue` int(11) unsigned NOT NULL,
  `fldUser` int(11) unsigned NOT NULL,
  `fldCards` text NOT NULL,
  `fldUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`fldId`),
  KEY `fldType` (`fldType`(12)),
  KEY `fldUser` (`fldUser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
';