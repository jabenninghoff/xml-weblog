<?php
// style/xml rendering module

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
