<?php
// $Id: article.inc.php,v 1.1 2002/10/30 15:43:03 loki Exp $

function display_article($article, $index, $content) {
    echo "    <article index=\"$index\" content=\"$content\">\n";
?>
      <!-- metadata -->
      <id><?php echo $article['id']; ?></id>
      <topic>
        <name><?php echo $article['topic_name']; ?></name>
        <icon><?php echo $article['topic_icon']; ?></icon>
      </topic>
      <language><?php echo $article['language']; ?></language>
      <url>article.php?id=<?php echo $article['id']; ?></url>

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
<?php
}

?>
