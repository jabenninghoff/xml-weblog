<?php
// $Id: load_image.php,v 1.2 2002/10/18 22:00:12 loki Exp $
// image loader

require_once "include/functions.inc.php";
require_once "include/config.inc.php";

$list = array("/tmp/logo.gif", "/tmp/valid-xhtml10.png");
foreach ($list as $name) {
    echo "<p>loading image: $name...";
    $base = basename($name);
    $load_image = AddSlashes(fread(fopen($name, "r"), filesize($name)));
    $image = $db->getRow("select * from image where name='$base'");
    if ($image) {
    echo "already there!";
    } else {
    $db->query("insert into image values (0,'$base','$load_image','logo','','')");
    echo "loaded.";
    }
    echo "</p>\n";
}

?>
