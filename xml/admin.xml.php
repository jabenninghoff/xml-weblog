<?php
// $Id: admin.xml.php,v 1.11 2002/10/26 05:19:52 loki Exp $

require_once "include/config.inc.php";
require_once "include/functions.inc.php";

$display = array(
    "ID" => true,
    "URI" => true,
    "boolean" => true,
    "date" => false,
    "image" => false,
    "image_small" => false,
    "int" => true,
    "lang" => true,
    "string" => true,
    "string_XHTML" => true,
    "XHTML_code" => false,
    "XHTML_fragment" => false,
    "XHTML_long" => false
);

function form_error()
{
echo "      <content>\n";
echo "        <p><b>Error: invalid post_type or post_mode</b></p>\n";
echo "      </content>\n";
}

function admin_input($name, $type, $value, $mode) {

    echo "              <td><b>", ucfirst($name), "</b></td>\n";

    if ($mode == "delete") {
        echo "<td>$value", '<input name="', $name, '" type="hidden" value="',
            $value, '"/>', "</td>\n";
        return;
    }

    echo "              <td>";
    switch ($type) {

    case "ID":
        $id = $value ? $value : 0;
        $value = $value ? $value : "<i>next_id</i>";
        echo $value,'<input name="',$name,'" type="hidden" value="',$id,'"/>';
        break;

    case "URI":
    case "string":
    case "string_XHTML":
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

    case "XHTML_code":
    case "XHTML_fragment":
    case "XHTML_long":
        if (!$value) $value = "enter_text";
        echo '<textarea name="', $name, '" cols="40" rows="4">';
        echo "$value</textarea>";
        break;

    }
    echo "</td>\n";
}


function admin_form($object, $type, $schema, $mode)
{
echo "        <form action=\"{$_SERVER['PHP_SELF']}?type=$type\" ",
    "method=\"post\">\n";
echo "          <table>\n";
foreach ($schema as $s) {
    echo "            <tr>\n";
    admin_input($s['property'], $s['datatype'],
        htmlspecialchars($object[$s['property']]), $mode);
    echo "            </tr>\n";
}
echo "          </table>\n";
echo "          <p>\n";
echo '            <input name="mode" type="hidden" value="', $mode, '"/>',"\n";
echo '            <input name="type" type="hidden" value="', $type, '"/>',"\n";
if ($mode == "edit") $button = "Save";
else $button = ucfirst($mode);
echo '            <input name="submit" type="submit" value="', $button, '"/> ',
    '<input name="cancel" type="submit" value="Cancel"/>', "\n";
echo "          </p>\n";
echo "        </form>\n";
}

function display_form()
{
global $db,$type,$display;

// display variables
$get_mode = $_GET['mode'];
if ($get_mode != "edit" && $get_mode != "delete") $get_mode = "create";

$get_type = $_GET['type'];
if (!in_array($get_type,$type)) $get_type = $type[0];

$get_id = valid_ID($_GET['id']);

$schema = $db->getAll("select distinct property,datatype from schema where ".
    "object='$get_type'", DB_FETCHMODE_ASSOC);

$object = fetch_type($get_type);

$get_object = $db->getRow("select * from $get_type where id='$get_id'",
    DB_FETCHMODE_ASSOC);

if ($get_mode == "create") {
    echo "      <title>", ucfirst($get_type."s"), "</title>\n";
    // only display object list in create mode
    foreach ($object as $obj) {
        echo "      <object type=\"$get_type\">\n";
        foreach ($schema as $s) {
            if ($display[$s['datatype']]) {
                echo "        ";
                echo "<property name=\"{$s['property']}\">",
                    "{$obj[$s['property']]}</property>\n";
            }
        }
    echo "        <property><a href=\"?type=$get_type&amp;mode=edit&amp;id=",
        $obj['id'], "\">edit</a></property>\n";
    echo "        <property><a href=\"?type=$get_type&amp;mode=delete&amp;id=",
        $obj['id'], "\">delete</a></property>\n";
        echo "      </object>\n";
    }
} else {
    echo "      <title>", ucfirst($get_mode), " ", ucfirst($get_type),
        "</title>\n";
}
echo "      <content>\n";
admin_form($get_object, $get_type, $schema, $get_mode);
echo "      </content>\n";
}

function process_form()
{
global $db,$type,$display;

$valid_mode = array("create", "edit", "delete");

$post_mode = $_POST['mode'];
$post_type = $_POST['type'];
if (!in_array($post_mode, $valid_mode) || !in_array($post_type, $type)) {
    form_error();
    return;
}

if ($post_mode == "delete") {
    $id = valid_ID($_POST['id']);
    $query = "DELETE from $post_type where id=$id";
    $db->query($query);

    echo "      <content>\n";
    echo "        <pre>$query;</pre>\n";
    echo "      </content>\n";
    return;
}

$schema = $db->getAll("select distinct property,datatype,required from schema ".
    "where object='$post_type'", DB_FETCHMODE_ASSOC);

// valid-ize all data
foreach ($schema as $s) {
   $validate = "valid_".$s['datatype'];
   $object[$s['property']] = $validate($_POST[$s['property']]); 
}
if ($post_mode == "create") $object['id'] = "0";

// check for missing values
$i = 0;
foreach ($schema as $s) {
   if ($object[$s['property']] == "" && $s['required']) {
       $missing[$i++] = $s['property'];
   }
}

// if missing values, prompt with form for resubmit.
if ($i) {
    echo "      <content>\n";
    echo "        <p><b>please re-enter missing values:</b><i>";
    foreach ($missing as $val) {
        echo " $val";
    }
    echo "</i></p>\n";
    admin_form($object, $post_type, $schema, $post_mode);
    echo "      </content>\n";
    return;
}


// add to table & display if not missing any required values
$query = ($post_mode == "edit") ? "UPDATE $post_type SET" :
    "INSERT INTO $post_type SET";

foreach ($schema as $s) {
    $query = $query." ".$s['property']."='".$object[$s['property']]."',";
}
$query = substr($query, 0, strlen($query)-1);
$query = ($post_mode == "edit") ? $query." WHERE id='".$object['id']."'" :
    $query;

$db->query($query);

echo "      <content>\n";
echo "        <p>".htmlspecialchars($query).";</p>\n";
echo "      </content>\n";
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
if (isset($_POST['submit'])) {
    process_form();
} else {
    display_form();
}
?>
    </admin>
  </main>

<?php require "xml/footer.xml.php"; ?>

</page>
