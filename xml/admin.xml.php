<?php
// $Id: admin.xml.php,v 1.18 2002/11/01 17:25:02 loki Exp $

require_once "include/auth.inc.php";
require_once "include/config.inc.php";
require_once "include/db.inc.php";
require_once "include/functions.inc.php";
require_once "include/types.inc.php";

function form_error()
{
    echo "      <content>\n";
    echo "        <p><b>Error: invalid post_type or post_mode</b></p>\n";
    echo "      </content>\n";
}

function admin_input($name, $type, $value, $mode) {

    global $xwl_default_lang;

    echo "              <td><b>", ucfirst($name), "</b></td>\n";

    if ($mode == "delete") {
        if ($type == "image" || $type == "image_small") $value = "[image]";
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
        if ($value) $checked=" checked=\"checked\"";
        echo '<input name="', $name, '" type="checkbox"', $checked, ' />';
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
        $value = $value ? $value : $xwl_default_lang;
        echo '<input name="', $name, '" type="text" maxlength="255" size="5" ',
            'value="', $value, '"/>';
        break;

    case "XHTML_code":
    case "XHTML_fragment":
    case "XHTML_long":
        if (!$value) $value = "%enter_text%";
        echo '<textarea name="', $name, '" cols="48" rows="8">';
        echo "$value</textarea>";
        break;

    case "image":
    case "image_small":
        echo '<input name="', $name, '" type="file"/>';
        break;
    }
    echo "</td>\n";
}

function admin_input_select($name, $menu, $default)
{
    echo "              <td><b>", ucfirst($name), "</b></td>\n";
    echo "              <td><select name=\"$name\">\n";
    for ($i=1; $menu[$i]; $i++) {
        echo "                <option ", $i==$default ? "selected=\"selected\" " : "", "value=\"$i\">$menu[$i]</option>\n";
    }
    echo "              </select></td>\n";
}

//
// custom form handlers
//

function admin_form_article($object, $type, $schema, $mode)
{
    global $xwl_default_site, $xwl_default_topic, $xwl_default_user;

    echo "          <table>\n";
    foreach ($schema as $s) {
        switch ($s['property']) {
            case "site":
                $menu = fetch_column_by_id("site", "url");
                echo "            <tr>\n";
                admin_input_select("site", $menu, $object['site'] ? $object['site'] : $xwl_default_site);
                echo "            </tr>\n";
                break;

            case "topic":
                $menu = fetch_column_by_id("topic", "name");
                echo "            <tr>\n";
                admin_input_select("topic", $menu, $object['topic'] ? $object['topic'] : $xwl_default_topic);
                echo "            </tr>\n";
                break;

            case "user":
                $menu = fetch_column_by_id("user", "userid");
                echo "            <tr>\n";
                admin_input_select("user", $menu, $object['user'] ? $object['user'] : $xwl_default_user);
                echo "            </tr>\n";
                break;

            default:
                echo "            <tr>\n";
                admin_input($s['property'], $s['datatype'],
                    htmlspecialchars($object[$s['property']]), $mode);
                echo "            </tr>\n";
                break;
        }
    }
    echo "          </table>\n";
}

function admin_form_block($object, $type, $schema, $mode)
{
    echo "          <table>\n";
    foreach ($schema as $s) {
        if ($s['property'] == "sidebar_align") {
            $right_select = $object['sidebar_align'] == "right" ? " selected=\"selected\" " : "";
            echo "            <tr>\n";
            echo "              <td><b>Sidebar_align</b></td>\n";
            echo "              <td><select name=\"sidebar_align\">\n";
            echo "              <option>left</option>\n";
            echo "              <option$right_select>right</option>\n";
            echo "            </select></td>\n";
            echo "            </tr>\n";
        } else {
            echo "            <tr>\n";
            admin_input($s['property'], $s['datatype'],
                htmlspecialchars($object[$s['property']]), $mode);
            echo "            </tr>\n";
        }
    }
    echo "          </table>\n";
}

function admin_form_user($object, $type, $schema, $mode)
{
    echo "          <table>\n";
    foreach ($schema as $s) {
        if ($s['property'] == "password") {
            echo "            <tr>\n";
            echo "              <td><b>Password</b></td>\n";
            echo "              <td>";
            echo '<input name="password" type="password" maxlength="255" size="40"/>';
            echo "</td>\n";
            echo "            </tr>\n";
        } else {
            echo "            <tr>\n";
            admin_input($s['property'], $s['datatype'],
                htmlspecialchars($object[$s['property']]), $mode);
            echo "            </tr>\n";
        }
    }
    echo "          </table>\n";
}

function admin_form_image($object, $type, $schema, $mode)
{
    echo "          <table>\n";
    foreach ($schema as $s) {
        if ($s['property'] == "mime" || $s['property'] == "width" || $s['property'] == "height") {
            echo "            <tr>\n";
            echo "              <td><b>", ucfirst($s['property']), "</b></td>\n";
            echo "              <td><i>automatically generated</i></td>\n";
            echo "            </tr>\n";
        } else {
            echo "            <tr>\n";
            admin_input($s['property'], $s['datatype'],
                htmlspecialchars($object[$s['property']]), $mode);
            echo "            </tr>\n";
        }
    }
    echo "          </table>\n";
}

function admin_form($object, $type, $schema, $mode)
{
global $admin_form_handler;

echo "        <form action=\"{$_SERVER['PHP_SELF']}?type=$type\" ",
    "method=\"post\" enctype=\"multipart/form-data\">\n";
if ($admin_form_handler[$type] && $mode != "delete") {
    $admin_form_handler[$type]($object, $type, $schema, $mode);
} else {
    // default handler
    echo "          <table>\n";
    foreach ($schema as $s) {
        echo "            <tr>\n";
        admin_input($s['property'], $s['datatype'],
            htmlspecialchars($object[$s['property']]), $mode);
        echo "            </tr>\n";
    }
    echo "          </table>\n";
}
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

function admin_results($object, $schema)
{
echo "          <table>\n";
foreach ($schema as $s) {
    echo "            <tr>\n";
    echo "              <td><b>", $s['property'], "</b></td>\n";
    if ($s['datatype'] == "image" || $s['datatype'] == "image_small")
        $value = "[image]";
    else
        $value = htmlspecialchars($object[$s['property']]);
    echo "              <td>", $value, "</td>\n";
    echo "            </tr>\n";
}
echo "          </table>\n";
}

function display_form()
{
global $type,$admin_display;

// display variables
$get_mode = $_GET['mode'];
if ($get_mode != "edit" && $get_mode != "delete") $get_mode = "create";

$get_type = $_GET['type'];
if (!in_array($get_type,$type)) $get_type = $type[0];

$get_id = valid_ID($_GET['id']);

$schema = fetch_schema($get_type);
$object = fetch_type($get_type);
$get_object = fetch_object($get_type, $get_id);

if ($get_mode == "create") {
    echo "      <title>", ucfirst($get_type."s"), "</title>\n";
    // only display object list in create mode
    foreach ($object as $obj) {
        echo "      <object type=\"$get_type\">\n";
        foreach ($schema as $s) {
            if ($admin_display[$s['datatype']]) {
                echo "        ";
                echo "<property name=\"{$s['property']}\">",
                    htmlspecialchars($obj[$s['property']]), "</property>\n";
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

function process_form_image($schema)
{
    global $mime_type;

    $size = array("", "", 0);

    foreach ($schema as $s) {
        $validate = "valid_".$s['datatype'];
        $object[$s['property']] = $validate($_POST[$s['property']]); 
        if ($file = $_FILES[$s['property']]) {
            if (is_uploaded_file($file['tmp_name'])) {
                if ($size = getimagesize($file['tmp_name'])) {
                    $object[$s['property']] = addslashes(fread(fopen($file['tmp_name'], "r"), filesize($file['tmp_name'])));
                 } else $object[$s['property']] = "";
            } else $object[$s['property']] = "";
        }
    }

    if (!$object['mime']) $object['mime'] = $mime_type[$size[2]];
    if (!$object['width']) $object['width'] = $size[0];
    if (!$object['height']) $object['height'] = $size[1];

    return $object;
}

function process_form_user($schema)
{
    foreach ($schema as $s) {
       $validate = "valid_".$s['datatype'];
       $object[$s['property']] = $validate($_POST[$s['property']]); 
       if ($_FILES[$s['property']])
           $object[$s['property']] = $validate($_FILES[$s['property']]);
    }
    $object['password'] = crypt($object['password']);

    return $object;
}

function process_form()
{
global $type, $admin_form_processor;

$valid_mode = array("create", "edit", "delete");

$post_mode = $_POST['mode'];
$post_type = $_POST['type'];
if (!in_array($post_mode, $valid_mode) || !in_array($post_type, $type)) {
    form_error();
    return;
}

if ($post_mode == "delete") {
    $id = valid_ID($_POST['id']);
    if (delete_object($post_type, $id)) {
        echo "      <title>", ucfirst($post_type), " deleted</title>\n";
    } else {
        echo "      <title>Error deleting $post_type id=$id!</title>\n";
    }
    return;
}

$schema = fetch_schema($post_type);

// valid-ize all data
if ($admin_form_processor[$post_type]) {
    $object = $admin_form_processor[$post_type]($schema);
} else {
    // default handler
    foreach ($schema as $s) {
       $validate = "valid_".$s['datatype'];
       $object[$s['property']] = $validate($_POST[$s['property']]); 
       if ($_FILES[$s['property']])
           $object[$s['property']] = $validate($_FILES[$s['property']]);
    }
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

    $slashes = array("string", "string_XHTML", "XHTML_code", "XHTML_fragment",
        "XHTML_long");
    foreach ($schema as $s) {
        if (in_array($s['datatype'],$slashes)) {
            $object[$s['property']] = stripslashes($object[$s['property']]);
        }
    }
    admin_form($object, $post_type, $schema, $post_mode);

    echo "      </content>\n";
    return;
}


// add to table & display if not missing any required values
if ($post_mode == "edit") {
    $success = update_object($post_type, $object);
} else {
    $success = insert_object($post_type, $object);
}

$action = $post_mode == "edit" ? "updated" : "created";
if ($success) {
    echo "      <title>", ucfirst($post_type), " $action</title>\n";
} else {
    echo "      <title>Error: ", ucfirst($post_type), " not $action</title>\n";
}

echo "      <content>\n";
admin_results($object, $schema);
echo "      </content>\n";
}

if (basename($_SERVER['PHP_SELF']) == "admin.xml.php") {
    // standalone
    header('Content-Type: text/xml');

    // check authentication
    if (!user_authenticated() || !user_authorized("admin")) {
        unauthorized("private");
        exit;
    }
}

// site variables
$site = fetch_site(base_url());
$block = fetch_block();

// admin.php variables
$type = fetch_table_list();

xml_declaration();
?>
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
