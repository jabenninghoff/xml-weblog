<?php
// $Id: image.php,v 1.5 2002/10/21 06:18:36 loki Exp $
// image renderer

require_once "include/config.inc.php";
require_once "include/functions.inc.php";

$name = safe_gpc_addslashes($_GET['name']);
$q = "select * from image where name='".$name."'";
$image = $db->getRow($q, DB_FETCHMODE_ASSOC);

header("Content-Type: image/gif");
echo $image['src'];
?>
