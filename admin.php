<?php
// $Id: admin.php,v 1.3 2002/10/27 17:25:54 loki Exp $
// admin front page

include "include/auth.inc.php";

// check authentication
if (!user_authenticated() || !user_authorized("admin")) {
    unauthorized("private");
    exit;
}

// get php-formatted xml document
ob_start();
require "xml/admin.xml.php";
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
