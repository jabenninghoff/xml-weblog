<?php

$doc = domxml_new_xmldoc("1.0");
$page = $doc->add_root("page");
$header = $page->new_child("header", "_header");
$main_block = $page->new_child("block", "");
$story = $main_block->new_child("story", "");

$content = $story->new_child("content", "_content_12");
$tmpstr = "<p>this is where the <i>story</i> content goes.</p>";
$tmpdoc = str_replace("_content_12", $tmpstr, $doc->dumpmem());
$doc = xmldoc($tmpdoc);

print $doc->dumpformat();

?>
