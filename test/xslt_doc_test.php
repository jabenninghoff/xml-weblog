<?php
include_once "../include/functions.inc.php";
// get php-formatted xml document
$xmlData = fopen ("http://loki:luzer@www.technomagik.net/private/xml/template.pp", "r");
while ($line = fgets ($xmlData))
    $xml .= $line;

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
