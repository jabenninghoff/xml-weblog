<?php
print '<?xml version="1.0" encoding="iso-8859-1"?>'."\n";
include_once "../include/functions.inc.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "/DTD/xhtml1-strict.dtd">
<!-- $Id: xslt_test.php,v 1.1 2002/10/11 20:13:01 loki Exp $ -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <title>XSLT transform test</title>
  </head>
  <body>
<?php
// Allocate a new XSLT processor
$xh = xslt_create();

// Process the document, returning the result into the $result variable
$result = xslt_process($xh, '../template.xml', '../style/basic_xhtml.xsl');
if ($result) {
    print "<p>SUCCESS, template.xml was transformed by basic_xhtml.xsl into the \$result";
    print " variable, the \$result variable has the following contents\n</p>\n";
    print "<pre>\n";
    print $result;
    print "</pre>\n";
}
else {
    print "<p>Sorry, template.xml could not be transformed by basic_xhtml.xsl into";
    print "  the \$result variable the reason is that " . xslt_error($xh) . 
    print " and the error code is " . xslt_errno($xh). "</p>";
}

xslt_free($xh);

validate_self();
?>
  </body>
</html>
