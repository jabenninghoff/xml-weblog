-- $Id: xml-mysql.sql,v 1.3 2002/10/15 22:24:05 loki Exp $
--
-- XML MySQL database definitions / initial values

--
-- test site "technomagik"
--

-- DROP only for test version
DROP DATABASE xml_tmnet;

CREATE DATABASE xml_tmnet;

USE xml_tmnet;

CREATE TABLE site (
  id int unsigned NOT NULL auto_increment,
  url varchar(255) NOT NULL default '',
  name varchar(255) NOT NULL default '',
  slogan varchar(255) NOT NULL default '',
  logo mediumblob NOT NULL,
  description tinytext NOT NULL,
  header_content text NOT NULL,
  disclaimer tinytext NOT NULL,
  footer_content text NOT NULL,
  language varchar(255) NOT NULL default 'en',
  PRIMARY KEY  (id)
) TYPE=MyISAM COMMENT='Site Configuration';

CREATE TABLE topic (
  id int unsigned NOT NULL auto_increment,
  name varchar(255) NOT NULL default '',
  description tinytext NOT NULL,
  icon blob NOT NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM COMMENT='Topics';

CREATE TABLE blocks (
  id int unsigned NOT NULL auto_increment,
  block_index int(10) unsigned NOT NULL default 0,
  sidebar_align char(4) NOT NULL default 'left',
  sidebar_index int(10) unsigned NOT NULL default 0,
  title varchar(255) NOT NULL default '',
  content text NOT NULL,
  language varchar(255) NOT NULL default 'en',
  PRIMARY KEY  (id)
) TYPE=MyISAM COMMENT='Sidebar Blocks';

CREATE TABLE article (
  id int unsigned NOT NULL auto_increment,
  topic int unsigned NOT NULL default 1,
  site int unsigned NOT NULL default 1,
  title varchar(255) NOT NULL default '',
  author varchar(255) NOT NULL default '',
  date datetime NOT NULL default '0000-00-00 00:00:00',
  leader mediumtext NOT NULL,
  content mediumtext NOT NULL,
  language varchar(255) NOT NULL default 'en',
  PRIMARY KEY  (id)
) TYPE=MyISAM COMMENT='Articles';
