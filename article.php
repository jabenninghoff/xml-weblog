<?php
// $Id: article.php,v 1.4 2002/11/01 17:00:28 loki Exp $
// single article renderer

include_once "include/style.inc.php";

$style_path = get_style_path();

// get php-formatted xml document
ob_start();
require "xml/article.xml.php";
$xml = ob_get_contents();
ob_end_clean();

$arguments = array(
     '/_xml' => $xml
);

// render & display the document using xslt
$xh = xslt_create();
$result = xslt_process($xh, 'arg:/_xml', $style_path, NULL, $arguments);
echo $result;

xslt_free($xh);
?>
