<?php
// $Id: load_image.php,v 1.1 2002/10/18 03:11:08 loki Exp $
// image loader

require_once "include/functions.inc.php";
require_once "include/config.inc.php";

$list = array("/tmp/logo.gif");
foreach ($list as $name) {
    echo "loading image: $name\n";
    $load_image = AddSlashes(fread(fopen($name, "r"), filesize($name)));
    $db->query("insert into image values (0,\"{$load_image}\",'logo','','')");
}

?>
