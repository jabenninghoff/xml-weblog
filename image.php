<?php
// $Id: image.php,v 1.6 2002/10/27 07:46:16 loki Exp $
// image renderer

require_once "include/config.inc.php";
require_once "include/functions.inc.php";

$name = safe_gpc_addslashes($_GET['name']);
$q = "select * from image where name='".$name."'";
$image = $db->getRow($q, DB_FETCHMODE_ASSOC);

header("Content-Type: ".$image['type']);
echo $image['src'];
?>
