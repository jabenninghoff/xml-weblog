<?php
// $Id: admin.xml.php,v 1.2 2002/10/19 18:36:55 loki Exp $
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
    <admin>
      <menu link="?menu=site">Site Configuration</menu>
      <menu link="?menu=article">Articles</menu>
      <menu link="?menu=block">Blocks</menu>
      <menu link="?menu=message">Messages</menu>
      <menu link="?menu=topic">Topics</menu>
      <menu link="?menu=user">Users</menu>
<?php
for ($i=0; $block[$i]; $i++) {
?>
      <object class="block">
        <property name="id"><?php echo $block[$i]['id']; ?></property>
        <property name="align"><?php echo $block[$i]['sidebar_align']; ?></property>
        <property name="sindex"><?php echo $block[$i]['sidebar_index']; ?></property>
        <property name="bindex"><?php echo $block[$i]['block_index']; ?></property>
        <property name="title"><?php echo $block[$i]['title']; ?></property>
        <property><?php echo '<a href="?menu=block&amp;op=edit&amp;id=', $block[$i]['id'], '">edit</a>'; ?></property>
        <property><?php echo '<a href="?menu=block&amp;op=delete&amp;id=', $block[$i]['id'], '">delete</a>'; ?></property>
      </object>
<?php
}
?>
    </admin>
  </main>

<?php require "xml/footer.xml.php"; ?>

</page>
