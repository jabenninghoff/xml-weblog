<?php
// $Id: header.xml.php,v 1.8 2002/10/18 22:00:12 loki Exp $

require_once "include/functions.inc.php";
require_once "include/config.inc.php";

if (basename($_SERVER['PHP_SELF']) == "header.xml.php") {
    // standalone
    header('Content-Type: text/xml');
    echo '<?xml version="1.0" encoding="iso-8859-1" standalone="yes"?>',"\n";
    // get site info 
    $site = $db->getRow("select * from site where id=1", DB_FETCHMODE_ASSOC);
    // get message(s)
    $q = "select * from message where (start_date < now() or start_date=0)".
         "and (end_date > now() or end_date=0)"; // add "group by index"
    $message = $db->getAll($q, DB_FETCHMODE_ASSOC);
}
?>
  <!-- header: top of the page, with logo, slogan, etc.  -->
  <header>
    <banner>[banner: not implemented]</banner>
    <logo>image.php?name=<?php echo $site['logo']; ?></logo>
<?php
$element = array ( "name", "slogan", "url", "description" );
foreach ($element as $tag) {
    echo "    <$tag>", $site[$tag], "</$tag>\n";
}
?>
    <content><?php echo $site['header_content'] ?></content>

    <!-- zero or more messages, topmost is index 0 -->
<?php
for ($i=0; $message[$i]; $i++) {
?>
    <message index="<?php echo $i; ?>">
      <?php echo $message[$i]['content'], "\n"; ?>
    </message>
  </header>
<?php
}
?>
