-- $Id: sample-values.sql,v 1.16 2002/11/14 23:10:06 loki Exp $
--
-- XML-weblog sample values
--

/*
 * Copyright (c) 2002, John Benninghoff <john@benninghoff.org>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 * 3. All advertising materials mentioning features or use of this software
 *    must display the following acknowledgement:
 *	This product includes software developed by John Benninghoff.
 * 4. Neither the name of the copyright holder nor the names of its 
 *    contributors may be used to endorse or promote products derived from
 *    this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 *  "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED
 * TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR
 * CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 * EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,
 * PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR
 * PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF
 * LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 * NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 */

INSERT INTO article VALUES (1,1,2,'Welcome to xml-weblog!',1,'2002-10-29 11:53:00','<p>Welcome to <b><a href=\"http://www.xml-weblog.org\">xml-weblog</a></b>, the first fully buzzword-compliant weblog/portal engine.</p>\r\n<p>All content and most configuration information is stored on the database (currently MySQL, but in the future, additional databases will be supported) back-end, with all code residing on the web server. The intermediate content layer is a PHP-generated XML page, using the built-in xml-weblog format (dtd not yet written). This intermediate format is converted into XHTML or other formats using the XSLT/PHP front-end.</p>\r\n<p>This separation of content, logical presentation, and actual presentation (style) makes it easy to change the look &amp; feel of the site or present the site in multiple styles and formatted for different display devices (i.e. mobile/AvantGo).</p>\r\n<p><b>Welcome!</b></p>','<p>You\'re still here? Get to work and get the site set up !!</p>','en');

INSERT INTO block VALUES (1,'left',1,1,'News Links','<a href=\"http://www.deadly.org\">OpenBSD Journal</a><br class=\"br\"/>\r\n<a href=\"http://daily.daemonnews.org\">daemonnews</a><br class=\"br\"/>\r\n<a href=\"http://www.bsdtoday.com\">BSD Today</a><br class=\"br\"/>\r\n<a href=\"http://www.macosrumors.com\">MacOS Rumors</a><br class=\"br\"/>\r\n<a href=\"http://apple.slashdot.org\">Slashdot (Apple)</a><br class=\"br\"/>\r\n<a href=\"http://bsd.slashdot.org\">Slashdot (BSD)</a><br class=\"br\"/>\r\n<a href=\"http://yro.slashdot.org\">Slashdot (YRO)</a><br class=\"br\"/>\r\n<a href=\"http://slashdot.org\">Slashdot</a>\r\n','en');
INSERT INTO block VALUES (2,'left',1,2,'Administration','<a href=\"admin.php\">login</a><br class=\"br\"/><br class=\"br\"/>\r\nPlease delete this block after configuring the site.','en');

INSERT INTO message VALUES (1,1,'0000-00-00 00:00:00','0000-00-00 00:00:00','<b>Under Construction:</b> This site has not yet been configured.','en');

INSERT INTO site VALUES (1,'http://www.xml-weblog.org/',10,'xml-weblog','the first fully buzzword-compliant weblog engine','images/xml-weblog-logo.png','xml-weblog sample site','<p><code include=\"date.php\"/></p>',' All trademarks and copyrights on this page are owned by their respective owners.<br class=\"br\"/>Comments are owned by the Poster. The Rest &#169; 2002 xml-weblog.org','<p>\r\n<a class=\"img\" href=\"http://httpd.apache.org\"><img src=\"images/apache_pb.png\" alt=\"Powered by Apache\"/></a>\r\n<a class=\"img\" href=\"http://www.mysql.com\"><img src=\"images/mysql_pb.png\" alt=\"Powered by MySQL\"/></a>\r\n<a class=\"img\" href=\"http://www.php.net\"><img src=\"images/php_pb.png\" alt=\"Powered by PHP\"/></a>\r\n<code include=\"validxml.php\"/>\r\n<a class=\"img\" href=\"http://jigsaw.w3.org/css-validator/\"><img src=\"images/valid-css.png\" alt=\"Valid CSS!\"/></a>\r\n</p>','en');

INSERT INTO topic VALUES (1,'No Topic','No topic.','icons/blank.png');
INSERT INTO topic VALUES (2,'Announcements','Site Information and Announcements.','icons/announce.png');
INSERT INTO topic VALUES (3,'Apple','Apple Computer, Mac OS X, and all things Macintosh.','icons/apple.png');
INSERT INTO topic VALUES (4,'BSD','BSD and Berkeley-derived Unices: FreeBSD, NetBSD, OpenBSD, and Darwin.','icons/bsd.png');
INSERT INTO topic VALUES (5,'Books','Book news and reviews.','icons/books.png');
INSERT INTO topic VALUES (6,'Bugs','xml-weblog bug reports.','icons/bugs.png');
INSERT INTO topic VALUES (7,'Censorship','censorship and attacks on free speech principles.','icons/censorship.png');
INSERT INTO topic VALUES (8,'Courts','The Courts and Legal matters.','icons/courts.png');
INSERT INTO topic VALUES (9,'Games','Computer, board, card, and other games.','icons/games.png');
INSERT INTO topic VALUES (10,'Hackers','The dark side of computer security.','icons/hackers.png');
INSERT INTO topic VALUES (11,'Hardware','Computer and Electronic hardware and equipment.','icons/hardware.png');
INSERT INTO topic VALUES (12,'Humor','Humor. Laugh, damn it!','icons/humor.png');
INSERT INTO topic VALUES (13,'Mail','Messages from the aether.','icons/mail.png');
INSERT INTO topic VALUES (14,'Microsoft','Microsoft Corporation and all things Bill.','icons/microsoft.png');
INSERT INTO topic VALUES (15,'Miscellaneous','Random stuff.','icons/misc.png');
INSERT INTO topic VALUES (16,'Money','Money, Business, and Economics.','icons/money.png');
INSERT INTO topic VALUES (17,'Music','Music on CD, LP, MP3, and live events.','icons/music.png');
INSERT INTO topic VALUES (18,'News','News from the outside world.','icons/news.png');
INSERT INTO topic VALUES (19,'Privacy','Privacy in the digital age.','icons/privacy.png');
INSERT INTO topic VALUES (20,'Security','Computer and Network security.','icons/security.png');
INSERT INTO topic VALUES (21,'Sites','Interesting places to visit on the web.','icons/sites.png');
INSERT INTO topic VALUES (22,'Software','Software and programming.','icons/software.png');
INSERT INTO topic VALUES (23,'Technology','Modern and post-modern technology.','icons/technology.png');
INSERT INTO topic VALUES (24,'XML-Weblog','XML-Weblog site management engine','icons/xml-weblog.png');

INSERT INTO user VALUES (1,'admin','$1$djcMXWAa$C7BumIUNqvkpTcabiU9IT0',1);
INSERT INTO user VALUES (2,'loki','$1$dk3BJfNA$0u1ZFF3PvBy24L0Z.wZdb/',1);
