<?php
// $Id: load_dbase.php,v 1.1 2002/10/20 00:01:59 loki Exp $
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

$site = array(
    "id" => "ID",
    "url" => "URI",
    "name" => "string",
    "slogan" => "string-XHTML",
    "logo" => "URI",
    "description" => "XHTML-fragment",
    "header_content" => "XHTML-code",
    "disclaimer" => "XHTML-fragment",
    "footer_content" => "XHTML-code",
    "language" => "lang"
);

$message = array(
    "id" => "ID",
    "message_index" => "int",
    "start_date" => "date",
    "end_date" => "date",
    "content" => "XHTML-fragment",
    "language" => "lang"
);

$topic = array(
    "id" => "ID",
    "name" => "string",
    "description" => "XHTML-fragment",
    "icon" => "URI"
);

$block = array(
    "id" => "ID",
    "sidebar_align" => "string",
    "sidebar_index" => "int",
    "block_index" => "int",
    "title" => "string",
    "content" => "XHTML-code",
    "language" => "lang"
);

$article = array(
    "id" => "ID",
    "site" => "int",
    "topic" => "int",
    "title" => "string",
    "author" => "string",
    "date" => "date",
    "leader" => "XHTML-long",
    "content" => "XHTML-long",
    "language" => "lang"
);

$user = array(
    "id" => "ID",
    "userid" => "string",
    "password" => "string",
    "admin" => "boolean"
);

// image-BLOB tables
$image = array(
    "id" => "ID",
    "name" => "string",
    "src" => "image",
    "alt" => "string",
    "width" => "int",
    "height" => "int"
);

$icon = array(
    "id" => "ID",
    "name" => "string",
    "src" => "image-small",
    "alt" => "string",
    "width" => "int",
    "height" => "int"
);

$tables = array(
    "site", "message", "topic", "block", "article", "user", "image", "icon"
);

ob_start();
?>
CREATE TABLE datatype (
  id int unsigned NOT NULL auto_increment,
  object varchar(255) NOT NULL default '',
  property varchar(255) NOT NULL default '',
  datatype varchar(255) NOT NULL default 'string',
  PRIMARY KEY (id)
) TYPE=MyISAM;

<?php
foreach ($tables as $table) {

    // table schema
    foreach ($$table as $col => $t) {
        echo "INSERT INTO datatype VALUES(0,'$table','$col','$t');\n";
    }
    echo "\n";

    echo "CREATE TABLE $table (\n";
    foreach ($$table as $col => $t) {
        echo "  $col $type[$t],\n";
    }
    echo "  PRIMARY KEY (id)\n";
    echo ") TYPE=MyISAM;\n\n";
}
include "./sample-values.sql";
$query = split(";",ob_get_contents());
foreach ($query as $q) {
    $db->query(trim($q));
} 
ob_end_flush();

echo "\n";
$list = array("/tmp/logo.gif", "/tmp/valid-xhtml10.png");
foreach ($list as $name) {
    echo "loading image: $name...";
    $base = basename($name);
    $load_image = AddSlashes(fread(fopen($name, "r"), filesize($name)));
    $db->query("insert into image values (0,'$base','$load_image','logo','','')");
    echo "loaded.\n";
}
?>
