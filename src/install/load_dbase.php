<?php
// database/image loader

header('Content-Type: text/plain');

require_once "../include/config.inc.php";
require_once "../lib/XWL.php";
require_once "DB.php";

$db = DB::connect("$xwl_db_type://$xwl_db_user:$xwl_db_password@$xwl_db_server/$xwl_db_database", true);

if (DB::isError($db)) {
    die("Error: database doesn't exist!\n");
} else {
    $res =& $db->query("SHOW TABLES");
    if (PEAR::isError($res)) die($res->getMessage());
    if ($res->numRows() != 0) die("Error: database tables already exist!\n");
}

$res =& $db->query("SET SQL_MODE = ''"); // workaround for strict SQL modes
if (PEAR::isError($res)) die ("Error: couldn't set SQL_MODE!\n");

ob_start();

foreach ($xwl_object_class as $class) {

    echo "CREATE TABLE $class (\n";
    $xwl_class = "XWL_$class";
    $obj = new $xwl_class;
    foreach ($obj->property as $name => $prop) {
        if ($prop->sql_type) echo "  $name {$prop->sql_type},\n";
    }
    echo "  PRIMARY KEY (id)\n";
    echo ") ENGINE=MyISAM;\n\n";
}

include "./sample-values.sql";

// generate default admin password using system default encryption algorithm
echo "\nINSERT INTO user VALUES (1, 'Administrator', 'www@xml-weblog.org', 'admin','", crypt("weblog"), "',1,0,'');\n";

// match unix/dos/mac newline
$query = preg_split("/;[(\n)(\r\n)(\cM)]/",ob_get_contents());
foreach ($query as $q) {
    $db->query(trim($q));
}

ob_end_flush();
?>
