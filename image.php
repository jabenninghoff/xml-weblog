<?php
// $Id: image.php,v 1.8 2002/10/29 23:28:51 loki Exp $
// image renderer

require_once "include/db.inc.php";
require_once "include/functions.inc.php";

$image = fetch_image(safe_gpc_addslashes($_GET['name']));

header("Content-Type: {$image['type']}");
echo $image['src'];
?>
