<?php
print '<?xml version="1.0" encoding="iso-8859-1"?>'."\n";
require_once "include/functions.inc.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "/DTD/xhtml1-strict.dtd">
<!-- $Id: xslt_test.php,v 1.6 2002/10/17 05:45:33 loki Exp $ -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <title>XSLT transform test</title>
  </head>
  <body>
<?php
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
if ($result) {
    print "<p>SUCCESS, template.xml was transformed by basic_xhtml.xsl into the \$result";
    print " variable. The \$result variable has the following contents:</p>\n";
    print "<pre>\n";
    print htmlspecialchars($result);
    print "</pre>\n";
}
else {
    print "<p>Sorry, template.xml could not be transformed by basic_xhtml.xsl into";
    print "  the \$result variable. The reason is: \"" . xslt_error($xh). "\"";
    print " and the error code is: " . xslt_errno($xh). "</p>\n";
}

xslt_free($xh);

validate_self();
?>
  </body>
</html>
