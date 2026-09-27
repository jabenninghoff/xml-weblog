<?php
// avantgo article renderer

require_once "include/style.inc.php";

/*
 * The only reason why we have to have avantgo_article.php is because avantgo
 * seems to choke on the following link:
 *     <a href="article.php?id=47&amp;style=avantgo">Read More...</a>
 * A bare & can't be used in style/avantgo/main.xsl, that is invalid xml.
 *
 * There should be a better fix for this.
 */

// get php-formatted xml document (must be in the global context)
ob_start();

// we use the same article.xml page for avantgo_article.php
require "xml/article.xml.php";
$xml = ob_get_contents();
ob_end_clean();

$style = new XWL_filename;
$style->set_value("avantgo");

xwl_style_render_page($xml, $style);
?>
