<?php
// $Id: index.xml.php,v 1.6 2002/10/18 00:12:34 loki Exp $
require_once "include/functions.inc.php";
require_once "include/config.inc.php";

if (basename($_SERVER['PHP_SELF']) == "index.xml.php") {
    // standalone
    header('Content-Type: text/xml');
}

// build variables
$site = $db->getRow("select * from site where id=1", DB_FETCHMODE_ASSOC);
$block = $db->getAll("select * from block group by sidebar_align,sidebar_index,block_index", DB_FETCHMODE_ASSOC);
$article = $db->getAll("select * from article group by date limit 10", DB_FETCHMODE_ASSOC);
$topic = $db->getAll("select * from topic group by id", DB_FETCHMODE_ASSOC);

?>
<?xml version="1.0" encoding="iso-8859-1" standalone="yes"?>
<!-- XML-weblog front page -->
<page lang="en" title="<?php echo $site['name']; ?>">

<?php require "xml/header.xml.php"; ?>

<?php require "xml/sidebar.xml.php"; ?>

  <!-- main: main section of document. index page contains articles. -->
  <main>
    <!-- 0 or more articles, default 10 most recent. topmost is index 0 -->
<?php
$i = 0;
while ($article[$i]) {
$id = $article[$i]['id'];
?>
    <article index="<?php echo $i; ?>">
      <!-- metadata -->
      <id><?php echo $id; ?></id>
      <topic>
        <name><?php echo $topic[($article[$i]['topic'])-1]['name']; ?></name>
        <icon>[icon: not implemented]</icon>
      </topic>
      <language><?php echo $article[$i]['language']; ?></language>
      <url>article.php?id=<?php echo $id; ?></url>

      <!-- "header" info -->
      <title><?php echo $article[$i]['title']; ?></title>
      <author><?php echo $article[$i]['author']; ?></author>
      <date><?php echo $article[$i]['date']; ?></date>

      <!-- actual content -->
      <leader>
<?php echo $article[$i]['leader']; ?>
      </leader>
      <content>
<?php echo $article[$i]['content']; ?>
      </content>

      <!-- comments (not yet implemented) -->
    </article>
<?php
    $i++;
}
?>
  </main>

<?php require "xml/footer.xml.php"; ?>

</page>
