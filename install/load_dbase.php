<?php
// $Id: load_dbase.php,v 1.13 2002/11/01 03:32:09 loki Exp $
// database/image loader

header('Content-Type: text/plain');

require_once "include/config.inc.php";
require_once "include/types.inc.php";
require_once "DB.php";

$db = DB::connect("$xwl_db_type://$xwl_db_user:$xwl_db_password@$xwl_db_server/$xwl_db_database", true);

if (DB::isError($db)) {
    $link = mysql_pconnect($xwl_db_server, $xwl_db_user, $xwl_db_password)
        or die("Error: couldn't connect!\n");
    mysql_create_db($xwl_db_database)
        or die("Error: couldn't create database!\n");
    $db = DB::connect("$xwl_db_type://$xwl_db_user:$xwl_db_password@$xwl_db_server/$xwl_db_database", true);
    if (DB::isError($db)) die("Error: WTF Happened ?\n");
} else die("Error: database already exists. not installing.\n");

ob_start();
echo $create_schema_query, "\n\n";

foreach ($tables as $table) {

    // table schema
    foreach ($$table as $col => $t) {
        echo "INSERT INTO schema VALUES(0,'$table','$col','$t[0]',$t[1]);\n";
    }
    echo "\n";

    echo "CREATE TABLE $table (\n";
    foreach ($$table as $col => $t) {
        echo "  $col {$type[$t[0]]},\n";
    }
    echo "  PRIMARY KEY (id)\n";
    echo ") TYPE=MyISAM;\n\n";
}
include "./sample-values.sql";
$query = split(";\n",ob_get_contents());
foreach ($query as $q) {
    $db->query(trim($q));
} 
ob_end_flush();

echo "\n";
ob_start();
include "./image_list.txt";
$list = split("\n",trim(ob_get_contents()));
ob_end_clean();

foreach ($list as $name) {
    echo "loading image: $name...";
    $base = basename($name);
    $load_image = AddSlashes(fread(fopen($name, "r"), filesize($name)));
    $size = getimagesize($name);
    $db->query("insert into image values (0,'$base','$load_image',
        '{$mime_type[$size[2]]}',$size[0],$size[1])");
       
    echo "loaded.\n";
}

ob_start();
include "./icon_list.txt";
$list = split("\n",trim(ob_get_contents()));
ob_end_clean();

foreach ($list as $name) {
    echo "loading icon: $name...";
    $base = basename($name);
    $load_image = AddSlashes(fread(fopen($name, "r"), filesize($name)));
    $size = getimagesize($name);
    $db->query("insert into icon values (0,'$base','$load_image',
        '{$mime_type[$size[2]]}',$size[0],$size[1])");
       
    echo "loaded.\n";
}

?>
