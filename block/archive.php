<?php
if (basename($_SERVER['PHP_SELF']) == "index.php") {
    echo "<block>\n";
    echo "  <title>Archives</title>\n";
    echo "  <content>\n";

    $e = date_to_datenum($article[0]['date']);
    $a = end($article);
    $s = date_to_datenum($a['date']);

    $first_a = fetch_article_first();
    $last_a = fetch_article_last();

    if (($start || $end) && date_to_datenum($first_a['date']) != $e) {
        echo "<a href=\"index.php?end=$e\">Newer Articles</a><br class=\"br\"/>\n";
    } else {
        echo "Newer Articles<br class=\"br\"/>\n";
    }
    if (date_to_datenum($last_a['date']) != $s) {
        echo "<a href=\"index.php?start=$s\">Older Articles</a><br class=\"br\"/>\n";
    } else {
        echo "Older Articles<br class=\"br\"/>\n";
    }
    echo "  </content>\n";
    echo "</block>\n";
}
?>
