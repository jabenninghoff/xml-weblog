<?php
// $Id: header.xml.php,v 1.6 2002/10/18 03:11:08 loki Exp $

require_once "include/functions.inc.php";
require_once "include/config.inc.php";

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
    <logo>image.php?type=image&amp;id=<?php echo $site['logo']; ?></logo>
<?php
$element = array ( "name", "slogan", "url", "description" );
foreach ($element as $tag) {
    echo "    <$tag>", $site[$tag], "</$tag>\n";
}
?>
    <content><?php echo $site['header_content'] ?></content>

    <!-- zero or more messages, topmost is index 0 -->
    <message index="0">
      <b>NOTE: this is only a prototype; don't expect anything to work.</b>
      [message: not implemented]
    </message>
  </header>
