<?php
// user configuration page

require_once "include/style.inc.php";

// get php-formatted xml document (must be in the global context)
ob_start();
require "xml/user.xml.php";
$xml = ob_get_contents();
ob_end_clean();

xwl_style_render_page($xml, xwl_style_get());
?>
