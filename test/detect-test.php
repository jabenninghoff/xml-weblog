<?php
print '<?xml version="1.0" encoding="iso-8859-1"?>'."\n";
include "include/functions.inc.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "DTD/xhtml1-strict.dtd">
<!-- $Id: detect-test.php,v 1.2 2002/07/20 16:15:23 loki Exp $ -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <title>Site Name</title>
  </head>
  <body>
<?php
print "<p>server = ".$_SERVER['SERVER_NAME']."</p>\n";
print "<p>user = ".$_SERVER['PHP_AUTH_USER']."</p>\n";
print "<p>self = ".$_SERVER['PHP_SELF']."</p>\n";

validate_self();
?>
  </body>
</html>
