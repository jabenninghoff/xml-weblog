<?php
// $Id: test-index.php,v 1.1 2001/12/28 09:37:03 death Exp $

// create page, header & footer blocks 
$doc = domxml_new_xmldoc("1.0");
$page = $doc->add_root("page");
$page->new_child("header", "_header");
$page->new_child("footer", "_footer");

// header & footer replacement goes here

// sidebars go here

// create & load main block from database
$main_block = $page->new_child("block", "");
$main_block->setattr("class","main");
$main_block->setattr("id","1");
$story = $main_block->new_child("story", "");


$story->new_child("content", "_content_12");
$tmpstr = "<p>this is where the <em>story</em> content goes.</p>";
$tmpdoc = str_replace("_content_12", $tmpstr, $doc->dumpmem());
$doc = xmldoc($tmpdoc);

// display the web page
print $doc->dumpformat();

?>
