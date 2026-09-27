<?php
// footer stub (xml only)

require_once "lib/XWL.php";
require_once "include/site.php";
require_once "include/auth.inc.php";

// check authentication
if (xwl_auth_login() && !xwl_auth_user_authenticated()) {
    xwl_auth_unauthorized($xwl_auth_realm);
    exit;
}

if (basename($_SERVER['PHP_SELF']) == "footer.xml.php") {
    // standalone
    header('Content-Type: text/xml');
    XWL::xml_declaration();
}
echo <<< END
  <!-- footer: bottom of page, includes disclaimer -->
  <footer>
    <disclaimer>
      {$xwl_site_value_xml['disclaimer']}
    </disclaimer>
    <content>
      {$xwl_site_value_xml['footer_content']}
    </content>
  </footer>

END;
?>
