<?php
// $Id: sidebar.xml.php,v 1.6 2002/10/28 17:23:13 loki Exp $

require_once "include/db.inc.php";
require_once "include/functions.inc.php";

if (basename($_SERVER['PHP_SELF']) == "sidebar.xml.php") {
    // standalone
    header('Content-Type: text/xml');
    xml_declaration();
    echo "<page>\n";
    $block = fetch_block();
}

echo "  <!-- left or right sidebar(s), outermost is index 0 -->\n";

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
    echo "      <title>{$block[$i]['title']}</title>\n";
    echo "      <content>\n";
    echo trim($block[$i]['content']), "\n";
    echo "      </content>\n";
    echo "    </block>\n";
        $i++;
    }
    echo "  </sidebar>\n";
}

if (basename($_SERVER['PHP_SELF']) == "sidebar.xml.php") {
    echo "</page>\n";
}
?>
