--
-- Table structure for table `altPeople`
--

CREATE TABLE `altPeople` (
  `apid` int(12) unsigned NOT NULL auto_increment,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `midInt` char(1) default NULL,
  `ename` varchar(8) NOT NULL,
  `nmuid` varchar(8) NOT NULL,
  PRIMARY KEY  (`apid`)
) ENGINE=MyISAM ;

-- --------------------------------------------------------

--
-- Table structure for table `courseContact`
--

CREATE TABLE `courseContact` (
  `ccid` int(12) NOT NULL auto_increment,
  `cid` int(12) NOT NULL,
  `uid` int(12) NOT NULL,
  `contactDate` datetime NOT NULL,
  `toEadd` varchar(8) NOT NULL,
  `contactText` text NOT NULL,
  PRIMARY KEY  (`ccid`)
) ENGINE=MyISAM ;

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `cid` int(12) NOT NULL auto_increment,
  `cprefix` varchar(6) NOT NULL,
  `clisting` varchar(30) NOT NULL,
  `pnmuin` varchar(9) default NULL,
  `userid` varchar(8) default NULL,
  `term` varchar(6) NOT NULL,
  `csize` int(12) default NULL,
  `syllabus` tinyint(1) NOT NULL default '0',
  `afiles` tinyint(1) NOT NULL default '0',
  `lrngMods` tinyint(1) NOT NULL default '0',
  `assignments` tinyint(1) NOT NULL default '0',
  `aquestions` tinyint(1) NOT NULL default '0',
  `aimages` tinyint(1) NOT NULL default '0',
  `disscussions` tinyint(1) NOT NULL default '0',
  `gradebook` tinyint(1) NOT NULL default '0',
  `camtasia` tinyint(1) NOT NULL default '0',
  `avfiles` tinyint(1) NOT NULL default '0',
  `webbased` tinyint(1) NOT NULL default '0',
  `notes` text,
  `dateAdded` datetime NOT NULL,
  PRIMARY KEY  (`cid`)
) ENGINE=MyISAM ;

-- --------------------------------------------------------

--
-- Table structure for table `courseStatus`
--

CREATE TABLE `courseStatus` (
  `csid` int(12) NOT NULL auto_increment,
  `cid` int(12) NOT NULL,
  `curStatus` enum('inProgress','waitingConfirm','notStarted','Complete','Discard') NOT NULL default 'notStarted',
  `assignedTo` int(12) NOT NULL default '0',
  `dateAssigned` datetime default NULL,
  `syllabusStatus` enum('P','C','NA') NOT NULL default 'NA',
  `syllabusCompleteDate` datetime default NULL,
  `syllabusCompleteUid` int(12) default NULL,
  `afilesStatus` enum('P','C','NA') NOT NULL default 'NA',
  `afilesCompleteDate` datetime default NULL,
  `afilesCompleteUid` int(12) default NULL,
  `lrngModsStatus` enum('P','C','NA') NOT NULL default 'NA',
  `lrngModsCompleteDate` datetime default NULL,
  `lrngModsCompleteUid` int(12) default NULL,
  `assignmentsStatus` enum('P','C','NA') NOT NULL default 'NA',
  `assignmentsCompleteDate` datetime default NULL,
  `assignmentsCompleteUid` int(12) default NULL,
  `aquestionsStatus` enum('P','C','NA') NOT NULL default 'NA',
  `aquestionsCompleteDate` datetime default NULL,
  `aquestionsCompleteUid` int(12) default NULL,
  `aimagesStatus` enum('P','C','NA') NOT NULL default 'NA',
  `aimagesCompleteDate` datetime default NULL,
  `aimagesCompleteUid` int(12) default NULL,
  `disscussionsStatus` enum('P','C','NA') NOT NULL default 'NA',
  `disscussionsCompleteDate` datetime default NULL,
  `disscussionsCompleteUid` int(12) default NULL,
  `gradebookStatus` enum('P','C','NA') NOT NULL default 'NA',
  `gradebookCompleteDate` datetime default NULL,
  `gradebookCompleteUid` int(12) default NULL,
  `camtasiaStatus` enum('P','C','NA') NOT NULL default 'NA',
  `camtasiaCompleteDate` datetime default NULL,
  `camtasiaCompleteUid` int(12) default NULL,
  `avfilesStatus` enum('P','C','NA') NOT NULL default 'NA',
  `avfilesCompleteDate` datetime default NULL,
  `avfilesCompleteUid` int(12) default NULL,
  `webbasedStatus` enum('P','C','NA') NOT NULL default 'NA',
  `webbasedCompleteDate` datetime default NULL,
  `webbasedCompleteUid` int(12) default NULL,
  `confirmDate` datetime default NULL,
  PRIMARY KEY  (`csid`)
) ENGINE=MyISAM ;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `gid` int(10) unsigned NOT NULL auto_increment,
  `gdesc` varchar(25) NOT NULL default '',
  PRIMARY KEY  (`gid`),
  UNIQUE KEY `gdesc` (`gdesc`)
) ENGINE=MyISAM ;

INSERT INTO `groups` VALUES(1, 'Administrator');
INSERT INTO `groups` VALUES(2, 'Converter');
INSERT INTO `groups` VALUES(3, 'Course Owner');
-- --------------------------------------------------------

--
-- Table structure for table `migFAQ`
--

CREATE TABLE `migFAQ` (
  `faqid` int(12) unsigned NOT NULL auto_increment,
  `faqSubject` varchar(35) NOT NULL,
  `faqText` text NOT NULL,
  `vStatus` enum('Yes','No') NOT NULL default 'Yes',
  `hitCount` int(12) unsigned NOT NULL,
  `dateAdded` datetime NOT NULL,
  PRIMARY KEY  (`faqid`)
) ENGINE=MyISAM ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `uid` int(10) unsigned NOT NULL auto_increment,
  `fname` varchar(20) NOT NULL default '',
  `lname` varchar(20) NOT NULL default '',
  `midinit` char(1) default NULL,
  `nmuin` varchar(9) NOT NULL default '',
  `ename` varchar(8) NOT NULL default '',
  `passwd` varchar(41) default NULL,
  `gid` int(12) NOT NULL,
  `date_create` datetime NOT NULL default '0000-00-00 00:00:00',
  `last_login` datetime default NULL,
  `last_ip` varchar(15) NOT NULL default '0.0.0.0',
  `status` enum('S','St','D','F') NOT NULL default 'D',
  `sessid` varchar(50) default NULL,
  PRIMARY KEY  (`uid`),
  UNIQUE KEY `nmuin` (`nmuin`,`ename`),
  UNIQUE KEY `sessid` (`sessid`)
) ENGINE=MyISAM ;

INSERT INTO `user` VALUES(1, 'Admin', 'User', 'T', '00000000', 'admin', '*4ACFE3202A5FF5CF467898FC58AAB1D615029441', 1, NOW(), NULL, '0.0.0.0', 'S', NULL);