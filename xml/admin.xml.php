<?php
// $Id: admin.xml.php,v 1.5 2002/10/22 22:13:51 loki Exp $

require_once "include/config.inc.php";
require_once "include/functions.inc.php";

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

function admin_menu($object) {
    foreach ($object as $item) {
        echo "      <menu link=\"?type=$item\">",ucfirst($item."s"),"</menu>\n";
    }
}

function admin_input($name, $type) {
    global $mode;

    echo "          <td><b>", ucfirst($name), "</b></td>\n";
    switch ($type) {

    case "ID":
       echo "          <td>ID</td>\n"; 
       break;

    case "URI":
    case "string":
    case "string-XHTML":
        echo '          <td><input name="', $name, '" type="text" ',
            'maxlength="255" size="40"/></td>', "\n";
        break;

    case "boolean":
        echo '          <td><input name="', $name, '" type="checkbox"/></td>',
            "\n";
        break;

    case "date":
        echo '          <td><input name="', $name, '" type="text" ',
            'maxlength="19" size="20"/></td>', "\n";
        break;

    case "int":
        echo '          <td><input name="', $name, '" type="text" ',
            'maxlength="10" size="10"/></td>', "\n";
        break;

    case "lang":
        echo '          <td><input name="', $name, '" type="text" ',
            'maxlength="255" size="5"/></td>', "\n";
        break;

    case "XHTML-code":
    case "XHTML-fragment":
    case "XHTML-long":
        echo '          <td><textarea name="', $name, '" cols="40" rows="4">';
        echo "enter_text</textarea></td>\n";
        break;

    }
}

function admin_form($mode, $type) {
    global $property, $input;

    echo "      <form action=\"{$_SERVER['PHP_SELF']}\" method=\"post\">\n";
    echo "      <table>\n";
    foreach ($property as $p) {
        echo "        <tr>\n";
        admin_input($p['property'], $p['datatype']);
        echo "        </tr>\n";
    }
    echo "      </table>\n";
    echo '      <p><button type="submit">', ucfirst($mode), "</button></p>\n";
    echo "      </form>\n";
}

function mode_create() {
    global $object,$type,$property,$display,$object_table;

    echo "    <admin>\n";
    echo "      <title>", ucfirst($type."s"), "</title>\n";
    admin_menu($object);
    foreach ($object_table as $obj) {
        echo "      <object type=\"$type\">\n";
        foreach ($property as $p) {
            if ($display[$p['datatype']]) {
                echo "        ";
                echo "<property name=\"{$p['property']}\">",
                    "{$obj[$p['property']]}</property>\n";
            }
        }
    echo "        <property><a href=\"?type=$type&amp;mode=edit&amp;id=",
        $obj['id'], "\">edit</a></property>\n";
    echo "        <property><a href=\"?type=$type&amp;mode=delete&amp;id=",
        $obj['id'], "\">delete</a></property>\n";
        echo "      </object>\n";
    }
    admin_form("create",$type);
    echo "    </admin>\n";
}

function mode_edit() {
    global $object,$type,$property,$display,$object_table;

    echo "    <admin>\n";
    echo "      <title>Edit ", ucfirst($type), "</title>\n";
    admin_menu($object);
    admin_form("edit",$type);
    echo "    </admin>\n";
}

function mode_delete() {
    global $object,$type,$property,$display,$object_table;

    echo "    <admin>\n";
    echo "      <title>Delete ", ucfirst($type), "</title>\n";
    admin_menu($object);
    admin_form("delete",$type);
    echo "    </admin>\n";
}

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
?>
<?xml version="1.0" encoding="iso-8859-1" standalone="yes"?>
<page lang="en" title="<?php echo $site['name']; ?>">

<?php require "xml/header.xml.php"; ?>

<?php require "xml/sidebar.xml.php"; ?>

  <main>
<?php
if ($mode == "edit") {
mode_edit();
} else if ($mode == "delete") {
mode_delete();
} else {
mode_create();
}
?>
  </main>

<?php require "xml/footer.xml.php"; ?>

</page>
