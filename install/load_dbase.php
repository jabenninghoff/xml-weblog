<?php
// $Id: load_dbase.php,v 1.8 2002/10/27 15:58:18 loki Exp $
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
    "image_small" => "blob NOT NULL",
    "int" => "int unsigned NOT NULL default 0",
    "lang" => "varchar(255) NOT NULL default 'en'",
    "string" => "varchar(255) NOT NULL default ''",
    "string_XHTML" => "varchar(255) NOT NULL default ''",
    "XHTML_code" => "text NOT NULL",
    "XHTML_fragment" => "text NOT NULL",
    "XHTML_long" => "mediumtext NOT NULL"
);

// image types
$mime_type = array("", "image/gif", "image/jpeg", "image/png",
    "application/x-shockwave-flash", "PSD", "image/bmp", "image/tiff",
    "image/tiff", "JPC", "JP2", "JPX", "JB2", "SWC", "IFF"); 

// table definitions

// $object = array(
//     "field" => array("type", required),

$site = array(
    "id" => array("ID", 1),
    "url" => array("URI", 1),
    "name" => array("string", 1),
    "slogan" => array("string_XHTML", 0),
    "logo" => array("URI", 0),
    "description" => array("XHTML_fragment", 0),
    "header_content" => array("XHTML_code", 0),
    "disclaimer" => array("XHTML_fragment", 0),
    "footer_content" => array("XHTML_code", 0),
    "language" => array("lang", 1)
);

$message = array(
    "id" => array("ID", 1),
    "message_index" => array("int", 1),
    "start_date" => array("date", 0),
    "end_date" => array("date", 0),
    "content" => array("XHTML_fragment", 1),
    "language" => array("lang", 1)
);

$topic = array(
    "id" => array("ID", 1),
    "name" => array("string", 1),
    "description" => array("XHTML_fragment", 0),
    "icon" => array("URI", 1)
);

$block = array(
    "id" => array("ID", 1),
    "sidebar_align" => array("string", 1),
    "sidebar_index" => array("int", 1),
    "block_index" => array("int", 1),
    "title" => array("string", 1),
    "content" => array("XHTML_code", 1),
    "language" => array("lang", 1)
);

$article = array(
    "id" => array("ID", 1),
    "site" => array("int", 1),
    "topic" => array("int", 1),
    "title" => array("string", 1),
    "author" => array("string", 1),
    "date" => array("date", 1),
    "leader" => array("XHTML_long", 1),
    "content" => array("XHTML_long", 0),
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
    "mime" => array("string", 1),
    "alt" => array("string", 0),
    "width" => array("int", 0),
    "height" => array("int", 0)
);

$icon = array(
    "id" => array("ID", 1),
    "name" => array("string", 1),
    "src" => array("image_small", 1),
    "mime" => array("string", 1),
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
    $size = getimagesize($name);
    $db->query("insert into image values (0,'$base','$load_image',
        '{$mime_type[$size[2]]}','',$size[0],$size[1])");
       
    echo "loaded.\n";
}
?>
