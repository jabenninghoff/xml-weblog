<?php
// $Id: admin.xml.php,v 1.7 2002/10/24 20:23:50 loki Exp $

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

function admin_input($name, $type, $value) {
    global $req_mode;

    echo "          <td><b>", ucfirst($name), "</b></td>\n";

    if ($req_mode == "delete") {
        echo "<td>$value</td>\n";
        return;
    }

    echo "          <td>";
    switch ($type) {

    case "ID":
        echo "ID"; 
        break;

    case "URI":
    case "string":
    case "string-XHTML":
        echo '<input name="',$name,'" type="text" maxlength="255" size="40" ',
            'value="', $value, '"/>';
        break;

    case "boolean":
        echo '<input name="', $name, '" type="checkbox" value="', $value,
            '" />';
        break;

    case "date":
        echo '<input name="', $name, '" type="text" maxlength="19" size="20" ',
            'value="', $value, '"/>';
        break;

    case "int":
        echo '<input name="', $name, '" type="text" maxlength="10" size="10" ',
            'value="', $value, '"/>';
        break;

    case "lang":
        echo '<input name="', $name, '" type="text" maxlength="255" size="5" ',
            'value="', $value, '"/>';
        break;

    case "XHTML-code":
    case "XHTML-fragment":
    case "XHTML-long":
        if (!$value) $value = "enter_text";
        echo '<textarea name="', $name, '" cols="40" rows="4">';
        echo "$value</textarea>";
        break;

    }
    echo "</td>\n";
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

// site variables
$site = fetch_site(1);
$block = fetch_block();

// admin.php variables
$type = $db->getCol("select distinct object from schema group by object");

$req_mode = $_GET['mode'];
if ($req_mode != "edit" && $req_mode != "delete") $req_mode = "create";

$req_type = $_GET['type'];
if (!in_array($req_type,$type)) $req_type = $type[0];

$req_id = valid_ID($_GET['id']);

$schema = $db->getAll("select distinct property,datatype from schema where ".
    "object='$req_type'", DB_FETCHMODE_ASSOC);

$object = fetch_type($req_type);

$req_object = $db->getRow("select * from $req_type where id='$req_id'",
    DB_FETCHMODE_ASSOC);

?>
<?xml version="1.0" encoding="iso-8859-1" standalone="yes"?>
<?php echo "<!-- {$req_object['id']} -->\n"; ?>
<page lang="en" title="<?php echo $site['name']; ?>">

<?php require "xml/header.xml.php"; ?>

<?php require "xml/sidebar.xml.php"; ?>

  <main>
    <admin>
<?php
// create top menu
foreach ($type as $item) {
    echo "      <menu link=\"?type=$item\">",ucfirst($item."s"),"</menu>\n";
}

if ($req_mode == "create") {
    echo "      <title>", ucfirst($req_type."s"), "</title>\n";
    // only display object list in create mode
    foreach ($object as $obj) {
        echo "      <object type=\"$req_type\">\n";
        foreach ($schema as $s) {
            if ($display[$s['datatype']]) {
                echo "        ";
                echo "<property name=\"{$s['property']}\">",
                    "{$obj[$s['property']]}</property>\n";
            }
        }
    echo "        <property><a href=\"?type=$req_type&amp;mode=edit&amp;id=",
        $obj['id'], "\">edit</a></property>\n";
    echo "        <property><a href=\"?type=$req_type&amp;mode=delete&amp;id=",
        $obj['id'], "\">delete</a></property>\n";
        echo "      </object>\n";
    }
} else {
    echo "      <title>", ucfirst($req_mode), " ", ucfirst($req_type),
        "</title>\n";
}
echo "      <form action=\"{$_SERVER['PHP_SELF']}\" method=\"post\">\n";
echo "      <table>\n";
foreach ($schema as $s) {
    echo "        <tr>\n";
    admin_input($s['property'], $s['datatype'],
        htmlspecialchars($req_object[$s['property']]));
    echo "        </tr>\n";
}
echo "      </table>\n";
echo '      <input name="mode" type="hidden" value="', $req_mode, '"/>',"\n";
echo '      <input name="type" type="hidden" value="', $req_type, '"/>',"\n";
if ($req_mode == "edit") $button = "Save";
else $button = ucfirst($req_mode);
echo '      <p><button name="button" type="submit" value="submit">',
    $button, '</button> <button name="button" type="submit" value="cancel">',
    "Cancel</button></p>\n";
echo "      </form>\n";

?>
    </admin>
  </main>

<?php require "xml/footer.xml.php"; ?>

</page>
