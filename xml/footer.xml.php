<?php
// $Id: footer.xml.php,v 1.3 2002/10/17 07:13:49 loki Exp $

require_once "include/functions.inc.php";
require_once "include/config.inc.php";

if (basename($_SERVER['PHP_SELF']) == "footer.xml.php") {
    // standalone
    header('Content-Type: text/xml');
    echo '<?xml version="1.0" encoding="iso-8859-1" standalone="yes"?>',"\n";
    // get site info 
    $site = $db->getRow("select * from site where id=1", DB_FETCHMODE_ASSOC);
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
