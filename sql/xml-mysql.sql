-- $Id: xml-mysql.sql,v 1.5 2002/10/16 19:31:28 loki Exp $
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
  php_script varchar(255) NOT NULL default '',
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
-- test values
INSERT INTO article VALUES (1,1,1,'Some Mail Someone Sent Me','Loki','2002-10-16 12:00:00','Here\'s some interesting email I got.','Blah blah blah blah blah. Blah. Blah Blah Blah. What else do you expect from this ?','en');
INSERT INTO blocks VALUES (1,0,'left',0,'News Sites','<a href=\"http://www.openbsd.org/\">OpenBSD Journal</a><br/>\r\n<a href=\"http://daily.daemonnews.org/\">daemonnews</a><br/>\r\n<a href=\"http://slashdot.org/\">Slashdot</a><br/>','','en');
INSERT INTO blocks VALUES (2,1,'left',0,'Bogus Block','This block is <b>bogus!!</b>','','en');
INSERT INTO site VALUES (1,'http://blade/secure/xml/','blade XML-Weblog test site','We need a slogan !!','','Test site for XML-Weblog development (description)','<p><a href=\"http://www.google.com\">Search</a></p>','All trademarks and copyrights on this page are owned by their respective owners. Comments are owned by the Poster. The Rest © 2002 tm.net\r\n','<p>\r\n<a href=\"http://validator.w3.org/check?uri=http://blade.corp.isib.net/secure/xml/test/xslt_doc_test.php;ss=1\">\r\n<img src=\"http://www.w3.org/Icons/valid-xhtml10\" alt=\"Valid XHTML 1.0!\" height=\"31\" width=\"88\"/>\r\n</a>\r\n</p>','en');
INSERT INTO topic VALUES (1,'No Topic','Default (no topic)','');
