<?php
// $Id: footer.xml.php,v 1.5 2002/10/28 17:23:13 loki Exp $

require_once "include/db.inc.php";
require_once "include/functions.inc.php";

if (basename($_SERVER['PHP_SELF']) == "footer.xml.php") {
    // standalone
    header('Content-Type: text/xml');
    xml_declaration();
    $site = fetch_site(base_url());
}
?>
  <!-- footer: bottom of page, includes disclaimer -->
  <footer>
    <disclaimer>
      <?php echo trim($site['disclaimer']), "\n"; ?>
    </disclaimer>
    <content>
      <?php echo trim(process_code($site['footer_content'])), "\n"; ?>
    </content>
  </footer>
