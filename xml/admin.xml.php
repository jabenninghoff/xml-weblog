<?php
// $Id: admin.xml.php,v 1.4 2002/10/21 06:25:17 loki Exp $

require_once "include/config.inc.php";
require_once "include/functions.inc.php";

if (basename($_SERVER['PHP_SELF']) == "admin.xml.php") {
    // standalone
    header('Content-Type: text/xml');
    // check authentication
    if (!isset($_SERVER['PHP_AUTH_USER'])) {
        header('WWW-Authenticate: Basic realm="private"');
        header('HTTP/1.0 401 Unauthorized');
        exit;
    }
}

// build variables
$site = fetch_site(1);
$block = fetch_block();
$object = $db->getCol("select distinct object from datatype group by object");

$mode = $_GET['mode'];
$id = valid_ID($_GET['id']);

$type = $_GET['type'];
if (!in_array($type,$object)) $type = $object[0];

$q = "select distinct property,datatype from datatype where object='$type'";
$property = $db->getAll($q, DB_FETCHMODE_ASSOC);
$object_table = fetch_type($type);

$display = array(
    "ID" => true,
    "URI" => true,
    "boolean" => true,
    "date" => false,
    "image" => false,
    "image-small" => false,
    "int" => true,
    "lang" => true,
    "string" => true,
    "string-XHTML" => true,
    "XHTML-code" => false,
    "XHTML-fragment" => false,
    "XHTML-long" => false
);
?>
<?xml version="1.0" encoding="iso-8859-1" standalone="yes"?>
<page lang="en" title="<?php echo $site['name']; ?>">

<?php require "xml/header.xml.php"; ?>

<?php require "xml/sidebar.xml.php"; ?>

  <main>
    <admin>
<?php
if ($mode == "edit") {
echo "      <title>Edit</title>\n";
} else if ($mode == "delete") {
echo "      <title>Delete</title>\n";
} else {
?>
      <title><?php echo ucfirst($type."s"); ?></title>
<?php
foreach ($object as $item) {
?> 
      <menu link="?type=<?php echo $item; ?>"><?php echo ucfirst($item."s"); ?></menu>
<?php
}
foreach ($object_table as $obj) {
?>
      <object type="<?php echo $type; ?>">
<?php
    foreach ($property as $p) {
        if ($display[$p['datatype']]) {
            echo "        ";
            echo '<property name="'.$p['property'].'">'.
                $obj[$p['property']]."</property>\n";
        }
    }
?>
        <property><a href="?type=<?php echo $type; ?>&amp;mode=edit&amp;id=<?php echo $obj['id']; ?>">edit</a></property>
        <property><a href="?type=<?php echo $type; ?>&amp;mode=delete&amp;id=<?php echo $obj['id']; ?>">delete</a></property>
      </object>
<?php
}
}
?>
    </admin>
  </main>

<?php require "xml/footer.xml.php"; ?>

</page>
