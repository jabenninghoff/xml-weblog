<?php
// $Id: image.php,v 1.3 2002/10/19 21:04:52 loki Exp $
// image renderer

require_once "include/functions.inc.php";
require_once "include/config.inc.php";

$q = "select * from image where name='".basename($_GET['name'])."'";
$image = $db->getRow($q, DB_FETCHMODE_ASSOC);

header("Content-Type: image/gif");
echo $image['src'];
?>
