-- $Id: xml-mysql.sql,v 1.1 2002/07/20 19:19:33 loki Exp $
--
-- XML MySQL database definitions / initial values

--
-- test site "technomagik"
--

CREATE DATABASE xml_technomagik;

USE xml_technomagik;

CREATE TABLE articles (
  id int(10) unsigned NOT NULL auto_increment,
  topic varchar(255) NOT NULL default '',
  language char(2) NOT NULL default '',
  url varchar(255) NOT NULL default '',
  title varchar(255) NOT NULL default '',
  author varchar(255) NOT NULL default '',
  date datetime NOT NULL default '0000-00-00 00:00:00',
  content mediumtext NOT NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM COMMENT='Article Objects';
