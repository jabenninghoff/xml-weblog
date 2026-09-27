<?php
// newer/older articles selection block
// requires: xml/index.xml.php, site.php

// private functions
function date_to_datenum($date)
{
    return preg_replace("'[- :]'i","",$date);
}

// only draw on the index page (only place where it makes sense)
if (basename($_SERVER['PHP_SELF']) == "index.php") {
    echo "<block>\n";
    echo "  <title>Archives</title>\n";
    echo "  <content>\n";

    $end = date_to_datenum($xwl_article[0]->property['date']->value);
    $s = end($xwl_article);
    $start = date_to_datenum($s->property['date']->value);

    $first_article = $xwl_db->fetch_article_first();
    $last_article = $xwl_db->fetch_article_last();

    if (date_to_datenum($first_article->property['date']->value) != $end) {
        echo "<a href=\"index.php?end=$end\">Newer Articles</a><br class=\"br\"/>\n";
    } else {
        echo "Newer Articles<br class=\"br\"/>\n";
    }
    if (date_to_datenum($last_article->property['date']->value) != $start) {
        echo "<a href=\"index.php?start=$start\">Older Articles</a><br class=\"br\"/>\n";
    } else {
        echo "Older Articles<br class=\"br\"/>\n";
    }
    echo "  </content>\n";
    echo "</block>\n";
}
?>
