<?php
// $Id: article.xml.php,v 1.7 2002/10/29 23:28:51 loki Exp $

require_once "include/db.inc.php";
require_once "include/functions.inc.php";

if (basename($_SERVER['PHP_SELF']) == "article.xml.php") {
    // standalone
    header('Content-Type: text/xml');
}

// build variables
$site = fetch_site(base_url());
$block = fetch_block();

$id = valid_ID($_GET['id']);
$article = fetch_article_single($id);

xml_declaration();
?>
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
        <name><?php echo $article['topic_name']; ?></name>
        <icon><?php echo $article['topic_icon']; ?></icon>
      </topic>
      <language><?php echo $article['language']; ?></language>
      <url>article.php?id=<?php echo $id; ?></url>

      <!-- "header" info -->
      <title><?php echo $article['title']; ?></title>
      <author><?php echo $article['author']; ?></author>
      <date><?php echo $article['date']; ?></date>

      <!-- actual content -->
      <leader>
<?php echo trim($article['leader']), "\n"; ?>
      </leader>
      <content>
<?php echo trim($article['content']), "\n"; ?>
      </content>

      <!-- comments (not yet implemented) -->
    </article>
  </main>

<?php require "xml/footer.xml.php"; ?>

</page>
