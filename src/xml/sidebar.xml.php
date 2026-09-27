<?php
// sidebar stub (xml only)

require_once "lib/XWL.php";
require_once "include/site.php";

if (basename($_SERVER['PHP_SELF']) == "sidebar.xml.php") {
    // standalone
    header('Content-Type: text/xml');
    XWL::xml_declaration();
    echo "<page>\n";
}

echo "  <!-- left or right sidebar(s), outermost is index 0 -->\n";
$xwl_block = $xwl_db->fetch_blocks();

$i = 0;
// this loop only works because the blocks are already sorted!
while ($xwl_block[$i]) {

    // get the new sidebar index & alignment
    $align = $xwl_block[$i]->property['sidebar_align']->value;
    $index = $xwl_block[$i]->property['sidebar_index']->value;
    echo '  <sidebar align="', $align, '" index="', $index, '">', "\n";
    echo "    <!-- zero or more blocks, topmost is index 0 -->\n";

    // this will run at least once, so i will be incremented
    while ($xwl_block[$i]->property['sidebar_align']->value === $align && $xwl_block[$i]->property['sidebar_index']->value == $index) {
        if (!$xwl_block[$i]->property['sysblock']->value) {
            echo '    <block index="', $xwl_block[$i]->property['block_index']->value, '">', "\n";
            echo "      <title>", $xwl_block[$i]->property['title']->display_XML(), "</title>\n";
            echo "      <content>\n";
            echo $xwl_block[$i]->property['content']->display_XML(), "\n";
            echo "      </content>\n";
            echo "    </block>\n";
        } else {
            // run sysblock code
            include "block/".$xwl_block[$i]->property['sysblock']->display_XML().".php";
        }
        $i++;
    }
    echo "  </sidebar>\n";
}

if (basename($_SERVER['PHP_SELF']) == "sidebar.xml.php") {
    echo "</page>\n";
}
?>
