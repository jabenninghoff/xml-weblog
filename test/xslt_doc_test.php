<?php
require_once "../include/functions.inc.php";
// get php-formatted xml document
ob_start();
require "template.php";
$xml = ob_get_contents();
ob_end_clean();

$arguments = array(
     '/_xml' => $xml
);

// Allocate a new XSLT processor
$xh = xslt_create();

// Process the document, returning the result into the $result variable
$result = xslt_process($xh, 'arg:/_xml', '../style/basic_xhtml.xsl', NULL, $arguments);

print $result;

xslt_free($xh);
?>
