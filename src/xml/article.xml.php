<?php
// single article page

require_once "lib/XWL.php";
require_once "include/site.php";
require_once "include/article.inc.php";
require_once "include/auth.inc.php";

// check authentication
if (xwl_auth_login() && !xwl_auth_user_authenticated()) {
    xwl_auth_unauthorized($xwl_auth_realm);
    exit;
}

if (basename($_SERVER['PHP_SELF']) == "article.xml.php") {
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

// fetch article
$id = new XWL_ID;
$id->set_value(XWL::magic_unslash($_GET['id']));
$xwl_article = $xwl_db->fetch_article($id->SQL_safe_value(), true);
$xwl_article_value_xml = $xwl_article->XML_values();

xwl_display_article($xwl_article_value_xml, 0, "show");

echo "    </main>\n";

require "xml/footer.xml.php";
echo "\n";

echo "</page>\n";
?>
