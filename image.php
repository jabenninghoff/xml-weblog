<?php
// $Id: image.php,v 1.1 2002/10/18 03:11:08 loki Exp $
// image renderer

require_once "include/functions.inc.php";
require_once "include/config.inc.php";

$image = $db->getRow("select * from image limit 1", DB_FETCHMODE_ASSOC);

header("Content-Type: image/gif");
echo $image['src'];
?>
