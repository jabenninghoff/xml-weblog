-- $Id: xml-mysql.sql,v 1.12 2002/10/18 15:49:01 loki Exp $
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
  logo int unsigned NOT NULL default 0,
  description tinytext NOT NULL,
  header_content text NOT NULL,
  disclaimer tinytext NOT NULL,
  footer_content text NOT NULL,
  language varchar(255) NOT NULL default 'en',
  PRIMARY KEY  (id)
) TYPE=MyISAM COMMENT='Site Configuration';

CREATE TABLE message (
  id int unsigned NOT NULL auto_increment,
  start_date datetime NOT NULL default '0000-00-00 00:00:00',
  end_date datetime NOT NULL default '0000-00-00 00:00:00',
  content text NOT NULL,
  language varchar(255) NOT NULL default 'en',
  PRIMARY KEY  (id)
) TYPE=MyISAM COMMENT='front page messages';

CREATE TABLE topic (
  id int unsigned NOT NULL auto_increment,
  name varchar(255) NOT NULL default '',
  description tinytext NOT NULL,
  icon int unsigned NOT NULL default 0,
  PRIMARY KEY  (id)
) TYPE=MyISAM COMMENT='Topics';

CREATE TABLE block (
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

--
-- image (blob) tables
--

CREATE TABLE image (
  id int unsigned NOT NULL auto_increment,
  src blob NOT NULL,
  alt varchar(255) NOT NULL default '',
  width varchar(255) NOT NULL default '',
  height varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM COMMENT='larger images (16MB max)';

CREATE TABLE icon (
  id int unsigned NOT NULL auto_increment,
  src mediumblob NOT NULL,
  alt varchar(255) NOT NULL default '',
  width varchar(255) NOT NULL default '',
  height varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM COMMENT='small images/icons (64K max)';

--
-- test values
--

INSERT INTO article VALUES (1,1,1,'Some Mail Someone Sent Me','Loki','2002-10-16 12:00:00','<p>Here\'s some interesting email I got.</p>','<p>Blah blah blah blah blah. Blah. Blah Blah Blah. What else do you expect from this ?</p>','en');
INSERT INTO article VALUES (2,1,1,'Test Article 2','Loki','2002-10-17 19:08:05','<p>First milestone is <b>complete!</b></p>','<p>Successfully retrieved articles, blocks, topics, and site configuration from MySQL back-end.</p>\r\n<p>Process:<br/>MySQL > PHP > XML document > XSLT transform > XHTML page</p>','en');
INSERT INTO block VALUES (1,0,'left',0,'News Sites','<a href=\"http://www.openbsd.org/\">OpenBSD Journal</a><br/>\r\n<a href=\"http://daily.daemonnews.org/\">daemonnews</a><br/>\r\n<a href=\"http://slashdot.org/\">Slashdot</a><br/>','en');
INSERT INTO block VALUES (2,1,'left',0,'Bogus Block','This block is <b>bogus!!</b>','en');
INSERT INTO site VALUES (1,'http://www.technomagik.net/test/','XML-Weblog test site','We need a slogan !!',1,'Test site for XML-Weblog development (description)','<a href=\"http://www.google.com\">Search</a>','All trademarks and copyrights on this page are owned by their respective owners. Comments are owned by the Poster. The Rest (c) 2002 tm.net\r\n','<?code include=\"test.php\" ?>','en');
INSERT INTO topic VALUES (1,'No Topic','Default (no topic)',1);
INSERT INTO message VALUES (1,'2002-10-17 00:00:00','0000-00-00 00:00:00','<b>NOTE: this is only a prototype; don\'t expect anything to work.</b>','en');
