<?php
// $Id: index.xml.php,v 1.3 2002/10/17 05:04:15 loki Exp $
require_once "functions.inc.php";
require_once "config.inc.php";

if (basename($_SERVER['PHP_SELF']) == "index.xml.php") {
    // standalone
    header('Content-Type: text/xml');
}

// get site info 
$site = $db->getRow("select * from site where id=1", DB_FETCHMODE_ASSOC);
?>
<?xml version="1.0" encoding="iso-8859-1" standalone="yes"?>
<!-- XML-weblog front page -->
<page lang="en" title="technomagik.net (generated)">

<?php require "header.xml.php"; ?>

<?php require "sidebar.xml.php"; ?>

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

<?php require "footer.xml.php"; ?>

</page>
