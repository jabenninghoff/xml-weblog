<?php
// $Id: index.xml.php,v 1.10 2002/10/29 23:28:51 loki Exp $

require_once "include/db.inc.php";
require_once "include/functions.inc.php";

// build variables
$site = fetch_site(base_url());
$block = fetch_block();
$message = fetch_message();
$article = fetch_article($site['article_limit']);

if (basename($_SERVER['PHP_SELF']) == "index.xml.php") {
    // standalone
    header('Content-Type: text/xml');
}

xml_declaration();
?>
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
        <name><?php echo $article[$i]['topic_name']; ?></name>
        <icon><?php echo $article[$i]['topic_icon']; ?></icon>
      </topic>
      <language><?php echo $article[$i]['language']; ?></language>
      <url>article.php?id=<?php echo $id; ?></url>

      <!-- "header" info -->
      <title><?php echo $article[$i]['title']; ?></title>
      <author><?php echo $article[$i]['author']; ?></author>
      <date><?php echo $article[$i]['date']; ?></date>

      <!-- actual content -->
      <leader>
<?php echo trim($article[$i]['leader']), "\n"; ?>
      </leader>
      <content>
<?php echo trim($article[$i]['content']), "\n"; ?>
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
