<?php
// $Id: admin.xml.php,v 1.1 2002/10/19 16:34:35 loki Exp $
require_once "include/functions.inc.php";
require_once "include/config.inc.php";

if (basename($_SERVER['PHP_SELF']) == "admin.xml.php") {
    // standalone
    header('Content-Type: text/xml');
    // check authentication
    if (!isset($_SERVER['PHP_AUTH_USER'])) {
        header('WWW-Authenticate: Basic realm="private"');
        header('HTTP/1.0 401 Unauthorized');
        exit;
    }
}

// build variables
$site = $db->getRow("select * from site where id=1", DB_FETCHMODE_ASSOC);
$block = $db->getAll("select * from block group by sidebar_align,sidebar_index,block_index", DB_FETCHMODE_ASSOC);

?>
<?xml version="1.0" encoding="iso-8859-1" standalone="yes"?>
<page lang="en" title="<?php echo $site['name']; ?>">

<?php require "xml/header.xml.php"; ?>

<?php require "xml/sidebar.xml.php"; ?>

  <main>
    <content>
      <p>welcome to admin.</p>
    </content>
  </main>

<?php require "xml/footer.xml.php"; ?>

</page>
