<?php
// require_once only if not called from somewhere else
if (basename($_SERVER['PHP_SELF']) == "index.xml.php") {
    require_once "../include/functions.inc.php";
}
?>
<?xml version="1.0" encoding="iso-8859-1" standalone="yes"?>
<!-- $Id: index.xml.php,v 1.2 2002/10/16 22:13:25 loki Exp $ -->
<!-- XML-weblog front page -->

<!-- page: XML-weblog root element -->
<page lang="en" title="technomagik.net (generated)">

  <!-- header: top of the page, with logo, slogan, etc.  -->
  <header>
    <banner>ad-banner-here (image,generated,optional)</banner>
    <name>technomagik.net (site.name)</name>
    <slogan>We need a slogan! (XHTML+,site.slogan)</slogan>
    <logo>(site.logo,image,optional)</logo>
    <url>http://www.technomagik.net</url><!-- site.url -->
    <description>random weblog. (site.description)</description>
    <content>
      Any content, like a <a href="http://www.google.com/">search page</a>,
      could go here.
      (site.header_content,XHTML+,optional)
    </content>
    <!-- zero or more messages, topmost is index 0 -->
    <message index="0">
      <b>still under development... not open yet!</b>
      (site.message,XHTML,optional)
    </message>
  </header>

  <!-- left or right sidebar(s), outermost is index 0 -->
  <sidebar align="left" index="0">

    <!-- zero or more blocks, topmost is index 0 -->
    <block index="0">
      <title>News Sites (block.title)</title>
      <content>
        <a href="http://www.openbsd.org/">OpenBSD Journal</a><br/>
        <a href="http://daily.daemonnews.org/">daemonnews</a><br/>
        <a href="http://slashdot.org/">Slashdot</a><br/>
        (block.content,XHTML+)
      </content>
    </block>

    <block index="1">
      <title>Bogus Block (block.title)</title>
      <content>
        This block is <b>bogus!!!</b>
        (block.content,XHTML+)
      </content>
    </block>

  </sidebar>

  <!-- main: main section of document. index page contains articles. -->
  <main>
    <!-- 0 or more articles, default 10 most recent. topmost is index 0 -->
    <article index="0">

      <!-- metadata -->
      <id>23 (article.id)</id>
      <topic>
        <name>email (article.topic)</name>
        <icon>(topic icon image)</icon>
      </topic>
      <language>en (article.language)</language>
      <url>article.php?id=23 (generated)</url>

      <!-- "header" info -->
      <title>Some Mail Someone Sent Me (article.title)</title>
      <author>Loki (article.author)</author>
      <date>2002-10-14 13:31:56 GMT-5 (article.date)</date>

      <!-- actual content -->
      <leader>
        <p>Here's some interesting email I got.
        (article.leader,XHTML+)</p>
      </leader>
      <content>
        <p>the body of the message would, of course, go here.
        (article.content,XHTML+)</p>
      </content>

      <!-- comments (not yet implemented) -->
    </article>
  </main>

  <!-- footer: bottom of page, includes disclaimer -->
  <footer>
    <disclaimer>
      All trademarks and copyrights on this page are owned by their respective
      owners. Comments are owned by the Poster. The Rest (c) 2002 tm.net
      (site.disclaimer)
    </disclaimer>
    <content>
      <?php validate_self(); ?>
      <p>(site.footer_content,XHTML+,optional)</p>
    </content>
  </footer>
</page>
