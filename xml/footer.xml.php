<?php
// $Id: footer.xml.php,v 1.2 2002/10/17 05:45:33 loki Exp $

require_once "include/functions.inc.php";

if (basename($_SERVER['PHP_SELF']) == "footer.xml.php") {
    // standalone
    header('Content-Type: text/xml');
    echo '<?xml version="1.0" encoding="iso-8859-1" standalone="yes"?>',"\n";
}
?>
  <!-- footer: bottom of page, includes disclaimer -->
  <footer>
    <disclaimer>
      All trademarks and copyrights on this page are owned by their respective
      owners. Comments are owned by the Poster. The Rest (c) 2002 tm.net
      (site.disclaimer)
    </disclaimer>
    <content>
      <?php validate_self(); ?>
      <p>(site.footer_content,XHTML+,optional)</p>
    </content>
  </footer>
