<?php
// $Id: load_dbase.php,v 1.5 2002/10/26 03:20:24 loki Exp $
// database/image loader

header('Content-Type: text/plain');

require_once('DB.php');
$db = DB::connect("mysql://xml:weblog@localhost/xml_tmnet", true);
if (DB::isError($db)) {
    $link = mysql_pconnect("localhost", "xml", "weblog")
        or die("Error: couldn't connect!\n");
    mysql_create_db("xml_tmnet")
        or die("Error: couldn't create database!\n");
    $db = DB::connect("mysql://xml:weblog@localhost/xml_tmnet", true);
    if (DB::isError($db)) die("Error: WTF Happened ?\n");
} else die("Error: database already exists. not installing.\n");

// datatype definitions
$type = array(
    "ID" => "int unsigned NOT NULL auto_increment",
    "URI" => "varchar(255) NOT NULL default ''",
    "boolean" => "tinyint NOT NULL default 0",
    "date" => "datetime NOT NULL default '0000-00-00 00:00:00'",
    "image" => "mediumblob NOT NULL",
    "image-small" => "blob NOT NULL",
    "int" => "int unsigned NOT NULL default 0",
    "lang" => "varchar(255) NOT NULL default 'en'",
    "string" => "varchar(255) NOT NULL default ''",
    "string-XHTML" => "varchar(255) NOT NULL default ''",
    "XHTML-code" => "text NOT NULL",
    "XHTML-fragment" => "text NOT NULL",
    "XHTML-long" => "mediumtext NOT NULL"
);


// table definitions

// $object = array(
//     "field" => array("type", required),

$site = array(
    "id" => array("ID", 1),
    "url" => array("URI", 1),
    "name" => array("string", 1),
    "slogan" => array("string-XHTML", 0),
    "logo" => array("URI", 0),
    "description" => array("XHTML-fragment", 0),
    "header_content" => array("XHTML-code", 0),
    "disclaimer" => array("XHTML-fragment", 0),
    "footer_content" => array("XHTML-code", 0),
    "language" => array("lang", 1)
);

$message = array(
    "id" => array("ID", 1),
    "message_index" => array("int", 1),
    "start_date" => array("date", 0),
    "end_date" => array("date", 0),
    "content" => array("XHTML-fragment", 1),
    "language" => array("lang", 1)
);

$topic = array(
    "id" => array("ID", 1),
    "name" => array("string", 1),
    "description" => array("XHTML-fragment", 0),
    "icon" => array("URI", 1)
);

$block = array(
    "id" => array("ID", 1),
    "sidebar_align" => array("string", 1),
    "sidebar_index" => array("int", 1),
    "block_index" => array("int", 1),
    "title" => array("string", 1),
    "content" => array("XHTML-code", 1),
    "language" => array("lang", 1)
);

$article = array(
    "id" => array("ID", 1),
    "site" => array("int", 1),
    "topic" => array("int", 1),
    "title" => array("string", 1),
    "author" => array("string", 1),
    "date" => array("date", 1),
    "leader" => array("XHTML-long", 1),
    "content" => array("XHTML-long", 0),
    "language" => array("lang", 1)
);

$user = array(
    "id" => array("ID", 1),
    "userid" => array("string", 1),
    "password" => array("string", 1),
    "admin" => array("boolean", 1)
);

// image-BLOB tables
$image = array(
    "id" => array("ID", 1),
    "name" => array("string", 1),
    "src" => array("image", 1),
    "alt" => array("string", 0),
    "width" => array("int", 0),
    "height" => array("int", 0)
);

$icon = array(
    "id" => array("ID", 1),
    "name" => array("string", 1),
    "src" => array("image-small", 1),
    "alt" => array("string", 0),
    "width" => array("int", 0),
    "height" => array("int", 0)
);

$tables = array(
    "site", "message", "topic", "block", "article", "user", "image", "icon"
);

ob_start();
?>
CREATE TABLE schema (
  id int unsigned NOT NULL auto_increment,
  object varchar(255) NOT NULL default '',
  property varchar(255) NOT NULL default '',
  datatype varchar(255) NOT NULL default 'string',
  required tinyint NOT NULL default 1,
  PRIMARY KEY (id)
) TYPE=MyISAM;

<?php
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
    $db->query("insert into image values (0,'$base','$load_image','logo','','')");
    echo "loaded.\n";
}
?>
