<?php
// $Id: topic.xml.php,v 1.9 2003/10/20 19:23:55 loki Exp $
// vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4:

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

require_once "XWL.php";
require_once "include/site.php";
require_once "include/article.inc.php";
require_once "include/auth.inc.php";

// check authentication
if (xwl_auth_login() && !xwl_auth_user_authenticated()) {
    xwl_auth_unauthorized($xwl_auth_realm);
    exit;
}

if (basename($_SERVER['PHP_SELF']) == "topic.xml.php") {
    // standalone
    header('Content-Type: text/xml');
}

XWL::xml_declaration();

echo "<page lang=\"en\" title=\"{$xwl_site_value_xml['name']}\">\n\n";

require "xml/header.xml.php";
echo "\n";

require "xml/sidebar.xml.php";
echo "\n";

echo "  <!-- main: main section of document. index page contains articles. -->\n";
echo "    <main>\n";

$id = new XWL_ID;
$xwl_topic = $xwl_db->fetch_topics();

// if no valid id, present topic list
if (!$id->set_value($_GET['id'])) {

    echo "    <topiclist>\n";
    foreach ($xwl_topic as $topic) {

        $t = $topic->XML_values();
        echo "      <topic>\n";
        echo "        <name>{$t['name']}</name>\n";
        echo "        <icon>{$t['icon']}</icon>\n";
        echo "        <link>topic.php?id={$t['id']}</link>\n";
        echo "      </topic>\n";
    }
    echo "    </topiclist>\n";

} else {

    $xwl_article = $xwl_db->fetch_articles_by_topic($id->value);
    $topic_name = $xwl_topic[$id->value-1]->property['name']->display_XML();

    echo "    <articlelist>\n";
    echo "      <heading>{$topic_name}</heading>\n";

    if ($xwl_article) {
        $i = 1;
        foreach ($xwl_article as $article) {
            $a = $article->XML_values();

            echo "        <article index=\"$i\">\n";
            echo "          <url>article.php?id={$a['id']}</url>\n";
            echo "          <title>{$a['title']}</title>\n";
            echo "          <author>{$a['author']}</author>\n";
            echo "          <date>{$a['date']}</date>\n";
            echo "        </article>\n";
            $i++;
        }
    }
    echo "</articlelist>\n";

}

echo "    </main>\n";

require "xml/footer.xml.php";
echo "\n";

echo "</page>\n";
?>
