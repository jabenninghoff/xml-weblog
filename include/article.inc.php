<?php
// $Id: article.inc.php,v 1.6 2003/04/21 20:54:12 loki Exp $
// vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4:

// display article on index.xml.php or article.xml.php page

/*
 * Copyright (c) 2002, John Benninghoff <john@benninghoff.org>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 * 3. All advertising materials mentioning features or use of this software
 *    must display the following acknowledgement:
 *	This product includes software developed by John Benninghoff.
 * 4. Neither the name of the copyright holder nor the names of its
 *    contributors may be used to endorse or promote products derived from
 *    this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 *  "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED
 * TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR
 * CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 * EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,
 * PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR
 * PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF
 * LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 * NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 */

function display_article($article, $index, $content, $topic) {
    echo "    <article index=\"$index\" content=\"$content\">\n";
?>
      <!-- metadata -->
      <id><?php echo $article['id']; ?></id>
      <topic>
        <name><?php echo $topic[($article['topic'])-1]['name']; ?></name>
        <icon><?php echo $topic[($article['topic'])-1]['icon']; ?></icon>
        <url>topic.php?id=<?php echo $article['topic']; ?></url>
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
