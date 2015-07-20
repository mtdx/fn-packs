<?php

$tblUsersCoins = '
CREATE TABLE `pcklckUsersCoins` (
  `fldUserId` int(11) unsigned NOT NULL,
  `fldCoins` int(11) unsigned NOT NULL,
  `fldWon` int(11) unsigned NOT NULL,
  `fldLost` int(11) unsigned NOT NULL,
  `fldPacks` int(11) unsigned NOT NULL,
  `fldLastUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`fldUserId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
';

$tblCoinTransactions = '
    CREATE TABLE `pcklckUserTransactions` (
  `fldId` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `fldUserId` int(10) unsigned NOT NULL,
  `fldType` varchar(12) NOT NULL,
  `fldAdmin` int(10) unsigned NOT NULL,
  `fldValue` int(10) NOT NULL,
  `fldLastUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`fldId`),
  KEY `fldType` (`fldType`(8))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
';