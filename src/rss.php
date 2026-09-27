<?php
// RSS XML renderer

require_once "include/style.inc.php";

// get php-formatted xml document (must be in the global context)
ob_start();

// we use the same index.xml page for rss.php
require "xml/index.xml.php";
$xml = ob_get_contents();
ob_end_clean();

$style = new XWL_filename;
$style->set_value("rss");

// use RSS content-type
header("Content-type: application/rss+xml");
xwl_style_render_page($xml, $style);
?>
