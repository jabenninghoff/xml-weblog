<?php
// $Id: admin.php,v 1.4 2002/10/31 00:41:29 loki Exp $
// admin front page

include_once "include/auth.inc.php";
include_once "include/style.inc.php";

// check authentication
if (!user_authenticated() || !user_authorized("admin")) {
    unauthorized("private");
    exit;
}

$style_path = get_style_path();

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
$result = xslt_process($xh, 'arg:/_xml', $style_path, NULL, $arguments);
print $result;

xslt_free($xh);
?>
