<?php
// front page

require_once "lib/XWL.php";
require_once "include/site.php";
require_once "include/article.inc.php";
require_once "include/auth.inc.php";

// check authentication
if (xwl_auth_login() && !xwl_auth_user_authenticated()) {
    xwl_auth_unauthorized($xwl_auth_realm);
    exit;
}

if (basename($_SERVER['PHP_SELF']) == "index.xml.php") {
    // standalone
    header('Content-Type: text/xml');
}

// pre-fetch articles
$start = new XWL_datenum;
$start->set_value(XWL::magic_unslash($_GET['start']));

$end = new XWL_datenum;
$end->set_value(XWL::magic_unslash($_GET['end']));

$xwl_article = $xwl_db->fetch_articles($xwl_site_value_xml['article_limit'], $start->value, $end->value, true);

XWL::xml_declaration();

echo "<page lang=\"en\" title=\"{$xwl_site_value_xml['name']}\">\n\n";

require "xml/header.xml.php";
echo "\n";

require "xml/sidebar.xml.php";
echo "\n";

echo "  <!-- main: main section of document. index page contains articles. -->\n";
echo "    <main>\n";

// display articles
$i = 0;
while ($xwl_article[$i]) {
    $xwl_article_value_xml = $xwl_article[$i]->XML_values();
    xwl_display_article($xwl_article_value_xml, $i++, "");
}

echo "    </main>\n";

require "xml/footer.xml.php";
echo "\n";

echo "</page>\n";
?>
