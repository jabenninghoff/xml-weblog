<?php
// $Id: archive.php,v 1.8 2003/05/14 22:44:44 loki Exp $
// vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4:

// newer/older articles selection block

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


// requires: xml/index.xml.php, site.php

// private functions
function date_to_datenum($date)
{
    return preg_replace("'[- :]'i","",$date);
}

// only draw on the index page (only place where it makes sense)
if (basename($_SERVER['PHP_SELF']) == "index.php") {
    echo "<block>\n";
    echo "  <title>Archives</title>\n";
    echo "  <content>\n";

    $end = date_to_datenum($xwl_article[0]->property['date']->value);
    $s = end($xwl_article);
    $start = date_to_datenum($s->property['date']->value);

    $first_article = $xwl_db->fetch_article_first();
    $last_article = $xwl_db->fetch_article_last();

    if (date_to_datenum($first_article->property['date']->value) != $end) {
        echo "<a href=\"index.php?end=$end\">Newer Articles</a><br class=\"br\"/>\n";
    } else {
        echo "Newer Articles<br class=\"br\"/>\n";
    }
    if (date_to_datenum($last_article->property['date']->value) != $start) {
        echo "<a href=\"index.php?start=$start\">Older Articles</a><br class=\"br\"/>\n";
    } else {
        echo "Older Articles<br class=\"br\"/>\n";
    }
    echo "  </content>\n";
    echo "</block>\n";
}
?>
