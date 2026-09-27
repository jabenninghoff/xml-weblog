<?php
// image renderer

require_once "lib/XWL.php";
require_once "include/site.php";
require_once "include/auth.inc.php";

// check authentication
if (xwl_auth_login() && !xwl_auth_user_authenticated()) {
    xwl_auth_unauthorized($xwl_auth_realm);
    exit;
}

$name = new XWL_string;
$name->set_value(XWL::magic_unslash($_GET['name']));

if ($image = $xwl_db->fetch_image($name->value)) {
    header("Content-Type: ".$image->property['mime']->value);
    echo $image->property['src']->value;
}
?>
