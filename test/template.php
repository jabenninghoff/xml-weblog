<?php include_once "../include/functions.inc.php"; ?>
<?xml version="1.0" encoding="iso-8859-1" standalone="yes"?>
<!-- $Id: template.php,v 1.4 2002/10/16 19:31:15 loki Exp $ -->
<!-- XML weblog template/test page -->

<!-- page: defines a single weblog "page" -->
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
    <!-- one or more messages -->
    <message>
      <b>still under development... not open yet!</b>
      (site.message,XHTML,optional)
    </message>
  </header>

  <!--
    sidebar: either left- or right-aligned. contains 0 or more blocks. multiple
      sidebars are allowed, however, normally there is only one left and one
      right sidebar.
    -->
  <sidebar align="left" index="0">

    <!--
      block: a block of text in the header, footer, or sidebar. May be static or
        dynamic content (implemented on the back-end with <include> tag)
      -->
    <block index="0">

      <!-- block title: name of block -->
      <title>News Sites (block.title)</title>

      <!-- block content -->
      <content>
        <a href="http://www.openbsd.org/">OpenBSD Journal</a><br />
        <a href="http://daily.daemonnews.org/">daemonnews</a><br />
        <a href="http://slashdot.org/">Slashdot</a><br />
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

    <!-- ... additional blocks ... -->

  </sidebar>

  <!-- main: main section of document. index page contains articles. -->
  <main>
    <!-- 0 or more articles, default 10 most recent -->
    <!-- article: -->
    <article index="0">

      <!-- metadata -->
      <id>23 (article.id)</id>
      <topic>email (article.topic)</topic>
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
      owners. Comments are owned by the Poster. The Rest © 2002 tm.net
      (site.disclaimer)
    </disclaimer>
    <content>
      <?php validate_self(); ?>
      <p>(site.footer_content,XHTML+,optional)</p>
    </content>
  </footer>
</page>
