<?php
print '<?xml version="1.0" encoding="iso-8859-1"?>'."\n";
?>
<!DOCTYPE html  PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "DTD/xhtml1-strict.dtd">
<!-- $Id: php_template.php,v 1.2 2002/06/04 04:24:54 loki Exp $ -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <title>Title</title>
  </head>
  <body>
    <p>Empty Page</p>
<?php
print '<p><a href="http://validator.w3.org/check?uri=http://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'].';ss=1">validate</a></p>'."\n";
?>
  </body>
</html>
