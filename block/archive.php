<block>
  <title>Archives</title>
  <content>
<?php
$e = date_to_datenum($article[0]['date']);
$a = end($article);
$s = date_to_datenum($a['date']);

if ($start || $end) {
    echo "<a href=\"index.php?end=$e\">Newer Articles</a><br class=\"br\"/>\n";
} else {
    echo "Newer Articles<br class=\"br\"/>\n";
}
echo "<a href=\"index.php?start=$s\">Older Articles</a><br class=\"br\"/>\n";
?>
  </content>
</block>
