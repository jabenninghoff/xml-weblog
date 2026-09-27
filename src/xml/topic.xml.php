<?php
// topics page

require_once "lib/XWL.php";
require_once "include/site.php";
require_once "include/article.inc.php";
require_once "include/auth.inc.php";

// check authentication
if (xwl_auth_login() && !xwl_auth_user_authenticated()) {
    xwl_auth_unauthorized($xwl_auth_realm);
    exit;
}

if (basename($_SERVER['PHP_SELF']) == "topic.xml.php") {
    // standalone
    header('Content-Type: text/xml');
}

XWL::xml_declaration();

echo "<page lang=\"en\" title=\"{$xwl_site_value_xml['name']}\">\n\n";

require "xml/header.xml.php";
echo "\n";

require "xml/sidebar.xml.php";
echo "\n";

echo "  <!-- main: main section of document. index page contains articles. -->\n";
echo "    <main>\n";

$id = new XWL_ID;
$xwl_topic = $xwl_db->fetch_topics();

// if no valid id, present topic list
if (!$id->set_value(XWL::magic_unslash($_GET['id']))) {

    echo "    <topiclist>\n";
    foreach ($xwl_topic as $topic) {

        $t = $topic->XML_values();
        echo "      <topic>\n";
        echo "        <name>{$t['name']}</name>\n";
        echo "        <icon>{$t['icon']}</icon>\n";
        echo "        <link>topic.php?id={$t['id']}</link>\n";
        echo "      </topic>\n";
    }
    echo "    </topiclist>\n";

} else {

    $xwl_article = $xwl_db->fetch_articles_by_topic($id->value);
    $topic_name = $xwl_topic[$id->value-1]->property['name']->display_XML();

    echo "    <articlelist>\n";
    echo "      <heading>{$topic_name}</heading>\n";

    if ($xwl_article) {
        $i = 1;
        foreach ($xwl_article as $article) {
            $a = $article->XML_values();

            echo "        <article index=\"$i\">\n";
            echo "          <url>article.php?id={$a['id']}</url>\n";
            echo "          <title>{$a['title']}</title>\n";
            echo "          <author>{$a['author']}</author>\n";
            echo "          <date>{$a['date']}</date>\n";
            echo "        </article>\n";
            $i++;
        }
    }
    echo "</articlelist>\n";

}

echo "    </main>\n";

require "xml/footer.xml.php";
echo "\n";

echo "</page>\n";
?>
