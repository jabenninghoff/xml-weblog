<?php
// $Id: sidebar.xml.php,v 1.16 2003/10/22 21:44:36 loki Exp $
// vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4:

/*
 * Copyright (c) 2002, 2003 John Benninghoff <john@benninghoff.org>.
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

if (basename($_SERVER['PHP_SELF']) == "sidebar.xml.php") {
    // standalone
    header('Content-Type: text/xml');
    XWL::xml_declaration();
    echo "<page>\n";
}

echo "  <!-- left or right sidebar(s), outermost is index 0 -->\n";
$xwl_block = $xwl_db->fetch_blocks();

$i = 0;
// this loop only works because the blocks are already sorted!
while ($xwl_block[$i]) {

    // get the new sidebar index & alignment
    $align = $xwl_block[$i]->property['sidebar_align']->value;
    $index = $xwl_block[$i]->property['sidebar_index']->value;
    echo '  <sidebar align="', $align, '" index="', $index, '">', "\n";
    echo "    <!-- zero or more blocks, topmost is index 0 -->\n";

    // this will run at least once, so i will be incremented
    while ($xwl_block[$i]->property['sidebar_align']->value === $align && $xwl_block[$i]->property['sidebar_index']->value == $index) {
        if (!$xwl_block[$i]->property['sysblock']->value) {
            echo '    <block index="', $xwl_block[$i]->property['block_index']->value, '">', "\n";
            echo "      <title>", $xwl_block[$i]->property['title']->display_XML(), "</title>\n";
            echo "      <content>\n";
            echo $xwl_block[$i]->property['content']->display_XML(), "\n";
            echo "      </content>\n";
            echo "    </block>\n";
        } else {
            // run sysblock code
            include "block/".$xwl_block[$i]->property['sysblock']->display_XML().".php";
        }
        $i++;
    }
    echo "  </sidebar>\n";
}

if (basename($_SERVER['PHP_SELF']) == "sidebar.xml.php") {
    echo "</page>\n";
}
?>
