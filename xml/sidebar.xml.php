<?php
// $Id: sidebar.xml.php,v 1.2 2002/10/17 05:45:33 loki Exp $

require_once "include/functions.inc.php";

if (basename($_SERVER['PHP_SELF']) == "sidebar.xml.php") {
    // standalone
    header('Content-Type: text/xml');
    echo '<?xml version="1.0" encoding="iso-8859-1" standalone="yes"?>',"\n";
}
?>
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
