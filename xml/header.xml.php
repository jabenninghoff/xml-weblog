<?php
// $Id: header.xml.php,v 1.19 2003/10/22 21:44:36 loki Exp $
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
require_once "include/auth.inc.php";

// check authentication
if (xwl_auth_login() && !xwl_auth_user_authenticated()) {
    xwl_auth_unauthorized($xwl_auth_realm);
    exit;
}

if (basename($_SERVER['PHP_SELF']) == "header.xml.php") {
    // standalone
    header('Content-Type: text/xml');
    XWL::xml_declaration();
}

echo <<< END
  <!-- header: top of the page, with logo, slogan, etc.  -->
  <header>
    <!-- <banner>[banner: not implemented]</banner> -->
    <logo>{$xwl_site_value_xml['logo']}</logo>
    <name>{$xwl_site_value_xml['name']}</name>
    <slogan>{$xwl_site_value_xml['slogan']}</slogan>
    <url>{$xwl_site_value_xml['url']}</url>
    <description>{$xwl_site_value_xml['description']}</description>
    <content>
      {$xwl_site_value_xml['header_content']}
    </content>


END;

// messages
echo "    <!-- zero or more messages, topmost is index 0 -->\n";
$xwl_message = $xwl_db->fetch_messages();

for ($i=0; $xwl_message[$i]; $i++) {
    echo "    <message index=\"$i\">\n";
    echo $xwl_message[$i]->property['content']->display_XML(), "\n";
    echo "    </message>\n";
}
echo "  </header>\n";
?>
