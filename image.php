<?php
// $Id: image.php,v 1.7 2002/10/28 17:23:11 loki Exp $
// image renderer

require_once "include/db.inc.php";
require_once "include/functions.inc.php";

$image = fetch_image(safe_gpc_addslashes($_GET['name']));

header("Content-Type: ".$image['type']);
echo $image['src'];
?>
