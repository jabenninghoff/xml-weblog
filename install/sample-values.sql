-- $Id: sample-values.sql,v 1.38 2004/09/21 16:11:58 loki Exp $
--
-- XML-weblog sample values
--

-- Copyright (c) 2002 - 2004 John Benninghoff <john@benninghoff.org>.
-- All rights reserved.
--
-- Redistribution and use in source and binary forms, with or without
-- modification, are permitted provided that the following conditions
-- are met:
--
-- 1. Redistributions of source code must retain the above copyright
--    notice, this list of conditions and the following disclaimer.
-- 2. Redistributions in binary form must reproduce the above copyright
--    notice, this list of conditions and the following disclaimer in the
--    documentation and/or other materials provided with the distribution.
-- 3. All advertising materials mentioning features or use of this software
--    must display the following acknowledgement:
--	This product includes software developed by John Benninghoff.
-- 4. Neither the name of the copyright holder nor the names of its 
--    contributors may be used to endorse or promote products derived from
--    this software without specific prior written permission.
--
-- THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
--  "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED
-- TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
-- PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR
-- CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
-- EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,
-- PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR
-- PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF
-- LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
-- NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
-- SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
--

INSERT INTO article VALUES (1,1,2,'Welcome to xml-weblog!',1,'2002-10-29 11:53:00','<p>Welcome to <strong><a href=\"http://www.xml-weblog.org\">xml-weblog</a></strong>, the first fully buzzword-compliant weblog/portal engine.</p>\r\n<p>All content and most configuration information is stored on the database (currently MySQL, but in the future, additional databases will be supported) back-end, with all code residing on the web server. The intermediate content layer is a PHP-generated XML page, using the built-in xml-weblog format (dtd not yet written). This intermediate format is converted into XHTML or other formats using the XSLT/PHP front-end.</p>\r\n<p>This separation of content, logical presentation, and actual presentation (style) makes it easy to change the look &amp; feel of the site or present the site in multiple styles and formatted for different display devices (i.e. mobile/AvantGo).</p>\r\n<p><strong>Welcome!</strong></p>','<p>You\'re still here? Get to work and get the site set up !!</p>',1,'en');

INSERT INTO block VALUES (1,'left',1,1,'Main Menu','<a href=\"index.php\">Home</a><br class=\"br\"/>\r\n<a href=\"topic.php\">Topics</a><br class=\"br\"/>\r\n<a href=\"user.php\">Users</a>','',1,'en');
INSERT INTO block VALUES (2,'left',1,2,'sysblock','','archive',1,'en');
INSERT INTO block VALUES (3,'left',1,3,'News Links','<a href=\"http://undeadly.org\">OpenBSD Journal</a><br class=\"br\"/>\r\n<a href=\"http://bsdnews.com\">daemonnews</a><br class=\"br\"/>\r\n<a href=\"http://www.macrumors.com\">Mac Rumors</a><br class=\"br\"/>\r\n<a href=\"http://apple.slashdot.org\">Slashdot (Apple)</a><br class=\"br\"/>\r\n<a href=\"http://bsd.slashdot.org\">Slashdot (BSD)</a><br class=\"br\"/>\r\n<a href=\"http://yro.slashdot.org\">Slashdot (YRO)</a><br class=\"br\"/>\r\n<a href=\"http://slashdot.org\">Slashdot</a>\r\n','',1,'en');
INSERT INTO block VALUES (4,'left',1,4,'sysblock','','user',1,'en');
INSERT INTO block VALUES (5,'left',1,5,'sysblock','','admin',1,'en');

INSERT INTO message VALUES (1,1,'0000-00-00 00:00:00','0000-00-00 00:00:00','<strong>Under Construction:</strong> This site has not yet been configured.','en');

INSERT INTO site VALUES (1,'http://www.xml-weblog.org/',0,10,'xml-weblog','the first fully buzzword-compliant weblog engine','images/xml-weblog-logo.gif','xml-weblog sample site','<p class=\"zero\"><code include=\"date.php\"/></p>',' All trademarks and copyrights on this page are owned by their respective owners.<br class=\"br\"/>Comments are owned by the Poster. The Rest &#169; 2002-2004 xml-weblog.org','<p>\r\n<a class=\"img\" href=\"http://httpd.apache.org\"><img src=\"images/apache_pb.gif\" alt=\"Powered by Apache\"/></a>\r\n<a class=\"img\" href=\"http://www.mysql.com\"><img src=\"images/mysql_pb.gif\" alt=\"Powered by MySQL\"/></a>\r\n<a class=\"img\" href=\"http://www.php.net\"><img src=\"images/php_pb.gif\" alt=\"Powered by PHP\"/></a>\r\n<code include=\"validxml.php\"/>\r\n<a class=\"img\" href=\"http://jigsaw.w3.org/css-validator/\"><img src=\"images/valid-css.gif\" alt=\"Valid CSS\"/></a>\r\n<code include=\"validrss.php\"/>\r\n</p>\r\n<p>\r\n<a class=\"img\" href=\"rss.php\"><img src=\"wl_icons/xml.gif\" alt=\"xml\"/></a> News aggregators can get a full RSS feed from <a href=\"rss.php\">rss.php</a>.<br class=\"br\"/>\r\n<code include=\"feeduri.php\"/>\r\n</p>','en');

INSERT INTO topic VALUES (1,'No Topic','No topic.','wl_icons/blank.gif');
INSERT INTO topic VALUES (2,'Announcements','Site Information and Announcements.','wl_icons/announce.gif');
INSERT INTO topic VALUES (3,'Apple','Apple Computer, Mac OS X, and all things Macintosh.','wl_icons/apple.gif');
INSERT INTO topic VALUES (4,'BSD','BSD and Berkeley-derived Unices: FreeBSD, NetBSD, OpenBSD, and Darwin.','wl_icons/bsd.gif');
INSERT INTO topic VALUES (5,'Books','Book news and reviews.','wl_icons/books.gif');
INSERT INTO topic VALUES (6,'Bugs','xml-weblog bug reports.','wl_icons/bugs.gif');
INSERT INTO topic VALUES (7,'Censorship','censorship and attacks on free speech principles.','wl_icons/censorship.gif');
INSERT INTO topic VALUES (8,'Courts','The Courts and Legal matters.','wl_icons/courts.gif');
INSERT INTO topic VALUES (9,'Games','Computer, board, card, and other games.','wl_icons/games.gif');
INSERT INTO topic VALUES (10,'Hackers','The dark side of computer security.','wl_icons/hackers.gif');
INSERT INTO topic VALUES (11,'Hardware','Computer and Electronic hardware and equipment.','wl_icons/hardware.gif');
INSERT INTO topic VALUES (12,'Humor','Humor. Laugh, damn it!','wl_icons/humor.gif');
INSERT INTO topic VALUES (13,'Mail','Messages from the aether.','wl_icons/mail.gif');
INSERT INTO topic VALUES (14,'Microsoft','Microsoft Corporation and all things Bill.','wl_icons/microsoft.gif');
INSERT INTO topic VALUES (15,'Miscellaneous','Random stuff.','wl_icons/misc.gif');
INSERT INTO topic VALUES (16,'Money','Money, Business, and Economics.','wl_icons/money.gif');
INSERT INTO topic VALUES (17,'Music','Music on CD, LP, MP3, and live events.','wl_icons/music.gif');
INSERT INTO topic VALUES (18,'News','News from the outside world.','wl_icons/news.gif');
INSERT INTO topic VALUES (19,'Privacy','Privacy in the digital age.','wl_icons/privacy.gif');
INSERT INTO topic VALUES (20,'Security','Computer and Network security.','wl_icons/security.gif');
INSERT INTO topic VALUES (21,'Sites','Interesting places to visit on the web.','wl_icons/sites.gif');
INSERT INTO topic VALUES (22,'Software','Software and programming.','wl_icons/software.gif');
INSERT INTO topic VALUES (23,'Technology','Modern and post-modern technology.','wl_icons/technology.gif');
INSERT INTO topic VALUES (24,'XML-Weblog','XML-Weblog site management engine','wl_icons/xml-weblog.gif');
