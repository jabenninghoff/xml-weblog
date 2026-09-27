<?php
// icon renderer

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

if ($icon = $xwl_db->fetch_icon($name->value)) {
    header("Content-Type: ".$icon->property['mime']->value);
    echo $icon->property['src']->value;
}
?>
