<?php
// $Id: header.xml.php,v 1.11 2002/10/28 17:23:13 loki Exp $

require_once "include/db.inc.php";
require_once "include/functions.inc.php";

if (basename($_SERVER['PHP_SELF']) == "header.xml.php") {
    // standalone
    header('Content-Type: text/xml');
    xml_declaration();
    $site = fetch_site(base_url());
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
    <content>
      <?php echo trim(process_code($site['header_content'])), "\n"; ?>
    </content>

    <!-- zero or more messages, topmost is index 0 -->
<?php
for ($i=0; $message[$i]; $i++) {
    echo "    <message index=\"$i\">\n";
    echo "      {$message[$i]['content']}\n";
    echo "    </message>\n";
}
?>
  </header>
