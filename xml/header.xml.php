<?php
// $Id: header.xml.php,v 1.10 2002/10/19 21:04:52 loki Exp $

require_once "include/config.inc.php";
require_once "include/functions.inc.php";

if (basename($_SERVER['PHP_SELF']) == "header.xml.php") {
    // standalone
    header('Content-Type: text/xml');
    echo '<?xml version="1.0" encoding="iso-8859-1" standalone="yes"?>',"\n";

    $site = fetch_site(1);
    $message = fetch_message();
}
?>
  <!-- header: top of the page, with logo, slogan, etc.  -->
  <header>
    <banner>[banner: not implemented]</banner>
    <logo>image.php?name=<?php echo $site['logo']; ?></logo>
    <name><?php echo $site['name']; ?></name>
    <slogan><?php echo $site['slogan']; ?></slogan>
    <url><?php echo $site['url']; ?></url>
    <description><?php echo $site['description']; ?></description>
    <content><?php echo $site['header_content']; ?></content>

    <!-- zero or more messages, topmost is index 0 -->
<?php
for ($i=0; $message[$i]; $i++) {
?>
    <message index="<?php echo $i; ?>">
      <?php echo $message[$i]['content'], "\n"; ?>
    </message>
<?php
}
?>
  </header>
