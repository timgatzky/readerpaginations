-- **********************************************************
-- *                                                        *
-- * IMPORTANT NOTE                                         *
-- *                                                        *
-- * Do not import this file manually but use the TYPOlight *
-- * install tool to create and maintain database tables!   *
-- *                                                        *
-- **********************************************************


-- --------------------------------------------------------

-- 
-- Table `tl_module`
-- 

CREATE TABLE `tl_module` (
  	`addReaderPagination` char(1) NOT NULL default '',
	`readerpagination_template` varchar(64) NOT NULL default '',
	`readerpagination_numberOfLinks` int(4) NOT NULL default '7',
	`readerpagination_format` varchar(32) NOT NULL default '',
	`readerpagination_customsql` mediumtext NULL,
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- 
-- Table `tl_news`
-- 

CREATE TABLE `tl_news` (
	`hide_in_pagination` char(1) NOT NULL default '',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- 
-- Table `tl_calendar_events`
-- 

CREATE TABLE `tl_calendar_events` (
	`hide_in_pagination` char(1) NOT NULL default '',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;