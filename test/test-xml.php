<?php
print '<?xml version="1.0" encoding="iso-8859-1"?>'."\n";
?>
<!DOCTYPE html  PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "DTD/xhtml1-strict.dtd">
<!-- $Id: test-xml.php,v 1.4 2002/06/21 19:24:27 loki Exp $ -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <title>Title</title>
  </head>
  <body>
    <pre>
<?php
$doc = domxml_new_doc("1.0");
$page = $doc->create_element("page");
$page = $doc->append_child($page);
$header = $page->new_child("header", "_header");
$main_block = $page->new_child("block", "");
#$main_block->setattr("class","main");
#$main_block->setattr("id","1");
$story = $main_block->new_child("story", "");

$story->new_child("content", "_content_12");
$tmpstr = "<p>this is where the <i>story</i> content goes.</p>";
$tmpdoc = str_replace("_content_12", $tmpstr, $doc->dumpmem());
$doc = xmldoc($tmpdoc);

print htmlentities($doc->dump_mem(true));
?>
    </pre>
<?php
print '<p><a href="http://validator.w3.org/check?uri=http://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'].';ss=1">validate</a></p>'."\n";
?>
  </body>
</html>
