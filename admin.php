<?php
// $Id: admin.php,v 1.1 2002/10/19 16:34:35 loki Exp $
// admin front page

require_once "include/functions.inc.php";

// check authentication
if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="private"');
    header('HTTP/1.0 401 Unauthorized');
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
