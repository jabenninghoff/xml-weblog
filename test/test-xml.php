<?php
// $Id: test-xml.php,v 1.3 2001/12/28 09:37:03 death Exp $

$doc = domxml_new_xmldoc("1.0");
$page = $doc->add_root("page");
$header = $page->new_child("header", "_header");
$main_block = $page->new_child("block", "");
$main_block->setattr("class","main");
$main_block->setattr("id","1");
$story = $main_block->new_child("story", "");

$story->new_child("content", "_content_12");
$tmpstr = "<p>this is where the <i>story</i> content goes.</p>";
$tmpdoc = str_replace("_content_12", $tmpstr, $doc->dumpmem());
$doc = xmldoc($tmpdoc);

print $doc->dumpformat();

?>
