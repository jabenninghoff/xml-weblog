<?php
// $Id: admin.php,v 1.5 2002/11/01 17:00:28 loki Exp $
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

// textarea hack
$result = str_replace("%enter_text%", "", $result);

echo $result;

xslt_free($xh);
?>
