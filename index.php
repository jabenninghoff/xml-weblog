<?php
// $Id: index.php,v 1.4 2002/10/17 05:45:33 loki Exp $
// front page renderer

require_once "include/functions.inc.php";

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
$result = xslt_process($xh, 'arg:/_xml', 'style/basic_xhtml.xsl', NULL, $arguments);
print $result;

xslt_free($xh);
?>
