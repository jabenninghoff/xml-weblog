<?php
// $Id: index.php,v 1.6 2002/10/31 00:41:29 loki Exp $
// front page renderer

include_once "include/style.inc.php";

$style_path = get_style_path();

// get php-formatted xml document
ob_start();
require "xml/index.xml.php";
$xml = ob_get_contents();
ob_end_clean();

$arguments = array(
     '/_xml' => $xml
);

// render & display the document using xslt
$xh = xslt_create();
$result = xslt_process($xh, 'arg:/_xml', $style_path, NULL, $arguments);
print $result;

xslt_free($xh);
?>
