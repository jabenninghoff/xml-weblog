<?php
// $Id: article.xml.php,v 1.2 2002/10/19 15:49:21 loki Exp $
require_once "include/functions.inc.php";
require_once "include/config.inc.php";

if (basename($_SERVER['PHP_SELF']) == "article.xml.php") {
    // standalone
    header('Content-Type: text/xml');
}

// build variables
$site = $db->getRow("select * from site where id=1", DB_FETCHMODE_ASSOC);
$block = $db->getAll("select * from block group by sidebar_align,sidebar_index,block_index", DB_FETCHMODE_ASSOC);
$topic = $db->getAll("select * from topic group by id", DB_FETCHMODE_ASSOC);

// fetch article
$id = $_GET['id'];
$article = $db->getRow("select * from article where id='$id'", DB_FETCHMODE_ASSOC);
?>
<?xml version="1.0" encoding="iso-8859-1" standalone="yes"?>
<page lang="en" title="<?php echo $site['name']; ?>">

<?php require "xml/header.xml.php"; ?>

<?php require "xml/sidebar.xml.php"; ?>

  <!-- main: main section of document. index page contains articles. -->
  <main>
    <!-- 0 or more articles, default 10 most recent. topmost is index 0 -->
    <article content="show">
      <!-- metadata -->
      <id><?php echo $id; ?></id>
      <topic>
        <name><?php echo $topic[($article['topic'])-1]['name']; ?></name>
        <icon>[icon: not implemented]</icon>
      </topic>
      <language><?php echo $article['language']; ?></language>
      <url>article.php?id=<?php echo $id; ?></url>

      <!-- "header" info -->
      <title><?php echo $article['title']; ?></title>
      <author><?php echo $article['author']; ?></author>
      <date><?php echo $article['date']; ?></date>

      <!-- actual content -->
      <leader>
<?php echo $article['leader']; ?>
      </leader>
      <content>
<?php echo $article['content']; ?>
      </content>

      <!-- comments (not yet implemented) -->
    </article>
  </main>

<?php require "xml/footer.xml.php"; ?>

</page>
