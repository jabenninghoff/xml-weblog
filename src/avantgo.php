<?php
// avantgo front page

require_once "include/style.inc.php";

// get php-formatted xml document (must be in the global context)
ob_start();

// we use the same index.xml page for avantgo.php
require "xml/index.xml.php";
$xml = ob_get_contents();
ob_end_clean();

$style = new XWL_filename;
$style->set_value("avantgo");

xwl_style_render_page($xml, $style);
?>
