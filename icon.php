<?php
// $Id: icon.php,v 1.1 2002/10/29 23:28:51 loki Exp $
// icon renderer

require_once "include/db.inc.php";
require_once "include/functions.inc.php";

$icon = fetch_icon(safe_gpc_addslashes($_GET['name']));

header("Content-Type: {$icon['type']}");
echo $icon['src'];
?>
