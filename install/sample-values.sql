-- $Id: sample-values.sql,v 1.6 2002/10/29 18:19:46 loki Exp $
--
-- XML-weblog sample values
--

INSERT INTO article VALUES (1,1,2,'Welcome to xml-weblog!','Loki','2002-10-29 11:53:00','<p>Welcome to <b><a href=\"http://www.xml-weblog.org\">xml-weblog</a></b>, the first fully buzzword-compliant weblog/portal engine.</p>\r\n<p>All content and most configuration information is stored on the database (currently MySQL, but in the future, additional databases will be supported) back-end, with all code residing on the web server. The intermediate content layer is a PHP-generated XML page, using the built-in xml-weblog format (dtd not yet written). This intermediate format is converted into XHTML or other formats using the XSLT/PHP front-end.</p>\r\n<p>This separation of content, logical presentation, and actual presentation (style) makes it easy to change the look &amp; feel of the site or present the site in multiple styles and formatted for different display devices (i.e. mobile/AvantGo).</p>\r\n<p><b>Welcome!</b></p>','','en');

INSERT INTO block VALUES (1,'left',1,1,'News Links','<a href=\"http://www.deadly.org\">OpenBSD Journal</a><br/>\r\n<a href=\"http://daily.daemonnews.org\">daemonnews</a><br/>\r\n<a href=\"http://www.bsdtoday.com\">BSD Today</a><br/>\r\n<a href=\"http://www.macosrumors.com\">MacOS Rumors</a><br/>\r\n<a href=\"http://apple.slashdot.org\">Slashdot (Apple)</a><br/>\r\n<a href=\"http://bsd.slashdot.org\">Slashdot (BSD)</a><br/>\r\n<a href=\"http://yro.slashdot.org\">Slashdot (YRO)</a><br/>\r\n<a href=\"http://slashdot.org\">Slashdot</a>\r\n','en');

INSERT INTO message VALUES (1,1,'0000-00-00 00:00:00','0000-00-00 00:00:00','<b>Under Construction:</b> This site has not yet been configured.','en');

INSERT INTO site VALUES (1,'http://www.xml-weblog.org/',10,'xml-weblog','the first fully buzzword-compliant weblog engine','xml-weblog-logo.png','xml-weblog sample site','<p><code include=\"date.php\"/></p>',' All trademarks and copyrights on this page are owned by their respective owners. Comments are owned by the Poster. The Rest &#169; 2002 xml-weblog.org','<p><code include=\"validxml.php\"/></p>','en');

INSERT INTO topic VALUES (1,'No Topic','No topic.','blank.png');
INSERT INTO topic VALUES (2,'Announcements','Site Information and Announcements.','announce.png');
INSERT INTO topic VALUES (3,'Apple','Apple Computer, Mac OS X, and all things Macintosh.','apple.png');
INSERT INTO topic VALUES (4,'BSD','BSD and Berkeley-derived Unices: FreeBSD, NetBSD, OpenBSD, and Darwin.','bsd.png');
INSERT INTO topic VALUES (5,'Books','Book news and reviews.','books.png');
INSERT INTO topic VALUES (6,'Bugs','xml-weblog bug reports.','bugs.png');
INSERT INTO topic VALUES (7,'Censorship','censorship and attacks on free speech principles.','censorship.png');
INSERT INTO topic VALUES (8,'Courts','The Courts and Legal matters.','courts.png');
INSERT INTO topic VALUES (9,'Games','Computer, board, card, and other games.','games.png');
INSERT INTO topic VALUES (10,'Hackers','The dark side of computer security.','hackers.png');
INSERT INTO topic VALUES (11,'Hardware','Computer and Electronic hardware and equipment.','hardware.png');
INSERT INTO topic VALUES (12,'Humor','Humor. Laugh, damn it!','humor.png');
INSERT INTO topic VALUES (13,'Mail','Messages from the aether.','mail.png');
INSERT INTO topic VALUES (14,'Microsoft','Microsoft Corporation and all things Bill.','microsoft.png');
INSERT INTO topic VALUES (15,'Miscellaneous','Random stuff.','misc.png');
INSERT INTO topic VALUES (16,'Money','Money, Business, and Economics.','money.png');
INSERT INTO topic VALUES (17,'Music','Music on CD, LP, MP3, and live events.','music.png');
INSERT INTO topic VALUES (18,'News','News from the outside world.','news.png');
INSERT INTO topic VALUES (19,'Privacy','Privacy in the digital age.','privacy.png');
INSERT INTO topic VALUES (20,'Security','Computer and Network security.','security.png');
INSERT INTO topic VALUES (21,'Sites','Interesting places to visit on the web.','sites.png');
INSERT INTO topic VALUES (22,'Software','Software and programming.','software.png');
INSERT INTO topic VALUES (23,'Technology','Modern and post-modern technology.','technology.png');
INSERT INTO topic VALUES (24,'XML-Weblog','XML-Weblog site management engine','xml-weblog.png');

INSERT INTO user VALUES (1,'admin','$1$djcMXWAa$C7BumIUNqvkpTcabiU9IT0',1);
INSERT INTO user VALUES (2,'loki','$1$dk3BJfNA$0u1ZFF3PvBy24L0Z.wZdb/',1);
