<?php
// $Id: image.php,v 1.4 2002/10/20 00:34:54 loki Exp $
// image renderer

require_once "include/config.inc.php";

$q = "select * from image where name='".basename($_GET['name'])."'";
$image = $db->getRow($q, DB_FETCHMODE_ASSOC);

header("Content-Type: image/gif");
echo $image['src'];
?>
