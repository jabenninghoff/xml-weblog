<?php
// $Id: footer.xml.php,v 1.4 2002/10/19 21:04:52 loki Exp $

require_once "include/config.inc.php";
require_once "include/functions.inc.php";

if (basename($_SERVER['PHP_SELF']) == "footer.xml.php") {
    // standalone
    header('Content-Type: text/xml');
    echo '<?xml version="1.0" encoding="iso-8859-1" standalone="yes"?>',"\n";

    $site = fetch_site(1);
}
?>
  <!-- footer: bottom of page, includes disclaimer -->
  <footer>
    <disclaimer>
<?php echo $site['disclaimer'], "\n"; ?>
    </disclaimer>
    <content>
<?php
echo "<!-- ", $site['footer_content'], "-->\n";
echo process_code($site['footer_content']);
?>
    </content>
  </footer>
