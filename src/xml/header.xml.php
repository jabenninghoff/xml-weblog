<?php
// header stub (xml only)

require_once "lib/XWL.php";
require_once "include/site.php";
require_once "include/auth.inc.php";

// check authentication
if (xwl_auth_login() && !xwl_auth_user_authenticated()) {
    xwl_auth_unauthorized($xwl_auth_realm);
    exit;
}

if (basename($_SERVER['PHP_SELF']) == "header.xml.php") {
    // standalone
    header('Content-Type: text/xml');
    XWL::xml_declaration();
}

echo <<< END
  <!-- header: top of the page, with logo, slogan, etc.  -->
  <header>
    <!-- <banner>[banner: not implemented]</banner> -->
    <logo>{$xwl_site_value_xml['logo']}</logo>
    <name>{$xwl_site_value_xml['name']}</name>
    <slogan>{$xwl_site_value_xml['slogan']}</slogan>
    <url>{$xwl_site_value_xml['url']}</url>
    <description>{$xwl_site_value_xml['description']}</description>
    <content>
      {$xwl_site_value_xml['header_content']}
    </content>


END;

// messages
echo "    <!-- zero or more messages, topmost is index 0 -->\n";
$xwl_message = $xwl_db->fetch_messages();

for ($i=0; $xwl_message[$i]; $i++) {
    echo "    <message index=\"$i\">\n";
    echo $xwl_message[$i]->property['content']->display_XML(), "\n";
    echo "    </message>\n";
}
echo "  </header>\n";
?>
