<?php
if (basename($_SERVER['PHP_SELF']) == "index.php") {
    echo "<block>\n";
    echo "  <title>Archives</title>\n";
    echo "  <content>\n";

    $e = date_to_datenum($article[0]['date']);
    $a = end($article);
    $s = date_to_datenum($a['date']);

    if ($start || $end) {
        echo "<a href=\"index.php?end=$e\">Newer Articles</a><br class=\"br\"/>\n";
    } else {
        echo "Newer Articles<br class=\"br\"/>\n";
    }
    echo "<a href=\"index.php?start=$s\">Older Articles</a><br class=\"br\"/>\n";
    echo "  </content>\n";
    echo "</block>\n";
}
?>
