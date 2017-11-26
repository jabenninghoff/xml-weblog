<?php
// $Id: style.inc.php,v 1.18 2005/06/16 17:07:34 loki Exp $
// vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4:

// style/xml rendering module

/*
 * Copyright (c) 2002 - 2004 John Benninghoff <john@benninghoff.org>.
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

require_once "lib/XWL.php";
require_once "include/config.inc.php";

// public functions

function xwl_style_get()
{
    global $xwl_default_style;


    $style = new XWL_filename;

    if (!$style->set_value(XWL::magic_unslash($_GET['style']))) $style->set_value($xwl_default_style);

    return $style;
}

function xwl_style_render_page($xml, $style)
{
    // serve up the page as XHTML
    // header("Content-type: application/xhtml+xml");

    // load the stylesheet
    ob_start();
    require "style/{$style->value}/main.xsl";
    $xsl = ob_get_contents();
    ob_end_clean();

    // render & display the document using xslt
    $xml_doc = new DomDocument;
    $xsl_doc = new DomDocument;
    $xsltproc = new XsltProcessor();

    $xml_doc->loadXML($xml);
    $xsl_doc->loadXML($xsl);

    $xsltproc->importStyleSheet($xsl_doc);
    $result = $xsltproc->transformToXML($xml_doc);

    // textarea hack
    $result = preg_replace("'\s*<xwl function=\"remove\"/>\s*'", "", $result);

    // cdata hack
    $result = preg_replace("'\s*<xwl function=\"cdata_open\"/>\s*'", "<![CDATA[", $result);
    $result = preg_replace("'\s*<xwl function=\"cdata_close\"/>\s*'", "]]>", $result);

    echo $result;
}
?>
