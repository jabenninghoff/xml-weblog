<?php
// $Id: topic.xml.php,v 1.3 2002/11/24 22:13:24 loki Exp $

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

require_once "include/db.inc.php";
require_once "include/functions.inc.php";

if (basename($_SERVER['PHP_SELF']) == "topic.xml.php") {
    // standalone
    header('Content-Type: text/xml');
}

// build variables
$site = fetch_site(base_url());
$block = fetch_block();
$topic = fetch_topic();

xml_declaration();
?>
<page lang="en" title="<?php echo $site['name']; ?>">

<?php require "xml/header.xml.php"; ?>

<?php require "xml/sidebar.xml.php"; ?>

  <!-- main: main section of document. index page contains articles. -->
  <main>
<?php
// if no id, present topic list
if (!isset($_GET['id'])) {
    echo "    <topiclist>\n";
    foreach ($topic as $t) {
        echo "      <topic>\n";
        echo "        <name>{$t['name']}</name>\n";
        echo "        <icon>{$t['icon']}</icon>\n";
        echo "        <link>topic.php?id={$t['id']}</link>\n";
        echo "      </topic>\n";
    }
    echo "    </topiclist>\n";
} else {
    $id = valid_id($_GET['id']);
    $article = fetch_article_by_topic($id);
    echo "    <articlelist>\n";
    echo "      <heading>{$topic[$id-1]['name']}</heading>\n";
    $i = 1;
    foreach ($article as $a) {
        echo "        <article index=\"$i\">\n";
        echo "          <url>article.php?id={$a['id']}</url>\n";
        echo "          <title>{$a['title']}</title>\n";
        echo "          <author>{$a['author']}</author>\n";
        echo "          <date>{$a['date']}</date>\n";
        echo "        </article>\n";
        $i++;
    }
    echo "</articlelist>\n";
}
?>
  </main>

<?php require "xml/footer.xml.php"; ?>

</page>
