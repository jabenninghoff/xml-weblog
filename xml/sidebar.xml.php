<?php
// $Id: sidebar.xml.php,v 1.3 2002/10/17 20:29:42 loki Exp $

require_once "include/functions.inc.php";
require_once "include/config.inc.php";

if (basename($_SERVER['PHP_SELF']) == "sidebar.xml.php") {
    // standalone
    header('Content-Type: text/xml');
    echo '<?xml version="1.0" encoding="iso-8859-1" standalone="yes"?>',"\n";
    echo "<page>\n";
    // retrieve blocks
    $block = $db->getAll("select * from block group by sidebar_align,sidebar_index,block_index", DB_FETCHMODE_ASSOC);
}
?>
  <!-- left or right sidebar(s), outermost is index 0 -->
<?php

$i = 0;
// this loop only works because the blocks are already sorted!
while ($block[$i]) {

    // get the new sidebar index & alignment
    $align = $block[$i]['sidebar_align'];
    $index = $block[$i]['sidebar_index'];

    echo '  <sidebar align="', $align, '" index="', $index, '">', "\n";
    echo "    <!-- zero or more blocks, topmost is index 0 -->\n";

    // this will run at least once, so i will be incremented
    while ($block[$i]['sidebar_align'] == $align &&
           $block[$i]['sidebar_index'] == $index) {
        echo '    <block index="', $block[$i]['block_index'], '">', "\n";
        echo "      <title>", $block[$i]['title'], "</title>\n";
        echo "      <content>", $block[$i]['content'], "</content>\n";
        echo "    </block>\n";
        $i++;
    }

    echo "  </sidebar>\n";
}

if (basename($_SERVER['PHP_SELF']) == "sidebar.xml.php") {
    echo "</page>\n";
}
?>
