<?php
// $Id: article.php,v 1.2 2002/10/20 00:34:54 loki Exp $
// front page renderer

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
$result = xslt_process($xh, 'arg:/_xml', 'style/basic_xhtml.xsl', NULL, $arguments);
print $result;

xslt_free($xh);
?>
