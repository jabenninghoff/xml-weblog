<?php
// $Id: sidebar.xml.php,v 1.5 2002/10/19 21:04:52 loki Exp $

require_once "include/config.inc.php";
require_once "include/functions.inc.php";

if (basename($_SERVER['PHP_SELF']) == "sidebar.xml.php") {
    // standalone
    header('Content-Type: text/xml');
    echo '<?xml version="1.0" encoding="iso-8859-1" standalone="yes"?>',"\n";
    echo "<page>\n";

    $block = fetch_block();
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
?>
  <sidebar align="<?php echo $align; ?>" index="<?php echo $index; ?>">
    <!-- zero or more blocks, topmost is index 0 -->
<?php
    // this will run at least once, so i will be incremented
    while ($block[$i]['sidebar_align'] == $align &&
           $block[$i]['sidebar_index'] == $index) {
?>
    <block index="<?php echo $block[$i]['block_index']; ?>">
      <title><?php echo $block[$i]['title']; ?></title>
      <content>
<?php echo "{$block[$i]['content']}"; ?>
      </content>
    </block>
<?php
        $i++;
    }
?>
  </sidebar>
<?php
}

if (basename($_SERVER['PHP_SELF']) == "sidebar.xml.php") {
    echo "</page>\n";
}
?>
