<?php
// $Id: header.xml.php,v 1.1 2002/10/17 05:04:15 loki Exp $

require_once "functions.inc.php";
require_once "config.inc.php";

if (basename($_SERVER['PHP_SELF']) == "header.xml.php") {
    // standalone
    header('Content-Type: text/xml');
    echo '<?xml version="1.0" encoding="iso-8859-1" standalone="yes"?>',"\n";
    // get site info 
    $site = $db->getRow("select * from site where id=1", DB_FETCHMODE_ASSOC);
}
?>
  <!-- header: top of the page, with logo, slogan, etc.  -->
  <header>
    <banner>[banner: not implemented]</banner>
<?php
$element = array ( "name", "slogan", "logo", "url", "description" );
foreach ($element as $tag) {
    echo "<$tag>", $site[$tag], "</$tag>\n";
}
?>
    <content>
<?php echo $site['header_content'] ?>
    </content>
    <!-- zero or more messages, topmost is index 0 -->
    <message index="0">
      <b>still under development... not open yet!</b>
      [message: not implemented]
    </message>
  </header>
