<?php
// $Id: index.xml.php,v 1.18 2003/04/23 16:10:27 loki Exp $
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

if (basename($_SERVER['PHP_SELF']) == "index.xml.php") {
    // standalone
    header('Content-Type: text/plain');
}

XWL::xml_declaration();

echo "<page lang=\"en\" title=\"{$xwl_site_value_xml['name']}\">\n\n";

require "xml/header.xml.php";
echo "\n";

require "xml/sidebar.xml.php";
echo "\n";

echo "  <!-- main: main section of document. index page contains articles. -->\n";
echo "    <main>\n";

/*
// build variables
$xwl_article_start = xwl_valid_datenum($_GET['start']);
$xwl_article_end = xwl_valid_datenum($_GET['end']);

$article = xwl_db_fetch_article($site['article_limit'],$xwl_article_start,$xwl_article_end);
*/

// fetch articles
$xwl_article = $xwl_db->fetch_articles($xwl_site_value_xml['article_limit']);

// display articles
$i = 0;
while ($xwl_article[$i]) {

    // convert to _xml values for convenience
    foreach ($xwl_article[$i]->property as $key => $value) {
        $xwl_article_value_xml[$key] = $value->display_XML();
    }

    xwl_display_article($xwl_article_value_xml, $i++, "");
}

echo "    </main>\n";

require "xml/footer.xml.php";
echo "\n";

echo "</page>\n";
?>
