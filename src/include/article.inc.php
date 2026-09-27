<?php
// display article on index.xml.php or article.xml.php page

function xwl_display_article($article, $index, $content)
{
echo <<< END
    <article index="$index" content="$content">
      <!-- metadata -->
      <id>{$article['id']}</id>
      <topic>
        <name>{$article['topic_name']}</name>
        <icon>{$article['topic_icon']}</icon>
        <url>topic.php?id={$article['topic']}</url>
      </topic>
      <language>{$article['language']}</language>
      <url>article.php?id={$article['id']}</url>

      <!-- "header" info -->
      <title>{$article['title']}</title>
      <author>
        <name>{$article['user_name']}</name>
        <mail>{$article['user_mail']}</mail>
      </author>
      <date>{$article['date']}</date>

      <!-- actual content -->
      <leader>
{$article['leader']}
      </leader>
      <content>
{$article['content']}
      </content>

      <!-- comments (not yet implemented) -->
    </article>

END;
}
?>
