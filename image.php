<?php
// $Id: image.php,v 1.2 2002/10/18 22:00:12 loki Exp $
// image renderer

require_once "include/functions.inc.php";
require_once "include/config.inc.php";

$name = $_GET['name'];
$image = $db->getRow("select * from image where name='$name'", DB_FETCHMODE_ASSOC);

header("Content-Type: image/gif");
echo $image['src'];
?>
