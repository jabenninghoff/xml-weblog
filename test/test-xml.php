<?php
print '<?xml version="1.0" encoding="iso-8859-1"?>'."\n";
require_once "include/functions.inc.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "DTD/xhtml1-strict.dtd">
<!-- $Id: test-xml.php,v 1.9 2002/10/17 05:45:33 loki Exp $ -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <title>Title</title>
  </head>
  <body>
    <pre>
<?php
$doc = domxml_new_doc("1.0");

$page = $doc->append_child($doc->create_element("page"));

$header = $page->append_child($doc->create_element("header"));
$header_text = $header->append_child($doc->create_text_node("_header"));

$main = $page->append_child($doc->create_element("main"));

$article = $main->append_child($doc->create_element("article"));

$content = $article->append_child($doc->create_element("content"));
$content_text = $content->append_child($doc->create_text_node("_content_12"));

$footer = $page->append_child($doc->create_element("footer"));
$footer_text = $footer->append_child($doc->create_text_node("_footer"));

$tmpstr = "<p>this is where the <i>article</i> content goes.</p>";
$tmpdoc = str_replace("_content_12", $tmpstr, $doc->dump_mem());
$doc = domxml_open_mem($tmpdoc);

print htmlentities($doc->dump_mem(true));
?>
    </pre>
<?php
validate_self();
?>
  </body>
</html>
