<?php
// $Id: article.inc.php,v 1.7 2003/04/23 04:35:11 loki Exp $
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

function xwl_display_article($article, $index, $content) {
echo <<< END
    <article index="$index" content="$content">
      <!-- metadata -->
      <id>{$article['id']}</id>
      <topic>
        <name>topic_name</name>
        <icon>topic_icon</icon>
        <url>topic_url</url>
      </topic>
      <language>{$article['language']}</language>
      <url>article.php?id={$article['id']}</url>

      <!-- "header" info -->
      <title>{$article['title']}</title>
      <author>user_name</author>
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
