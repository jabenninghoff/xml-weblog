-- $Id: sample-values.sql,v 1.1 2002/10/20 00:01:59 loki Exp $
--
-- XML-weblog sample values
--

--
-- test site "technomagik"
--

INSERT INTO user VALUES(1,'admin','',1);

INSERT INTO article VALUES (1,1,1,'Some Mail Someone Sent Me','Loki','2002-10-16 12:00:00','<p>Here\'s some interesting email I got.</p>','<p>Blah blah blah blah blah. Blah. Blah Blah Blah. What else do you expect from this ?</p>','en');
INSERT INTO article VALUES (2,1,1,'Test Article 2','Loki','2002-10-17 19:08:05','<p>First milestone is <b>complete!</b></p>','<p>Successfully retrieved articles, blocks, topics, and site configuration from MySQL back-end.</p>\r\n<p>Process:<br/>MySQL > PHP > XML document > XSLT transform > XHTML page</p>','en');
INSERT INTO block VALUES (1,'left',0,0,'News Sites','<a href=\"http://www.openbsd.org/\">OpenBSD Journal</a><br/>\r\n<a href=\"http://daily.daemonnews.org/\">daemonnews</a><br/>\r\n<a href=\"http://slashdot.org/\">Slashdot</a><br/>','en');
INSERT INTO block VALUES (2,'left',0,1,'Bogus Block','This block is <b>bogus!!</b>','en');
INSERT INTO message VALUES (1,0,'2002-10-17 00:00:00','0000-00-00 00:00:00','<b>NOTE: this is only a prototype; don\'t expect anything to work.</b>','en');
INSERT INTO site VALUES (1,'http://www.technomagik.net/test/','XML-Weblog test site','We need a slogan !!','logo.gif','Test site for XML-Weblog development (description)','<a href=\"http://www.google.com\">Search</a>','All trademarks and copyrights on this page are owned by their respective owners. Comments are owned by the Poster. The Rest (c) 2002 tm.net\r\n','<?code include=\"test.php\" ?>','en');
INSERT INTO topic VALUES (1,'No Topic','Default (no topic)','');
