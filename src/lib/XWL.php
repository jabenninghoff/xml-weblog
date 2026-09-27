<?php
// xml-weblog base library

// XWL modules
require_once "XWL/datatype.php";
require_once "XWL/object.php";
require_once "XWL/database.php";

/*
 * the class globals are necessary in order to give the article object access
 * to the list of sites, topics, and users. does this really belong here ?
 * need to rethink/research how exactly the class should be organized.
 */
// class globals
$GLOBALS['XWL_topic_list'] = array();
$GLOBALS['XWL_site_list'] = array();
$GLOBALS['XWL_user_list'] = array();

// core functions
class XWL
{
    // private functions
    function _only_has($source, $valid_chars)
    {
        if (strspn($source, $valid_chars) == strlen($source)) return true;
        else return false;
    }

    function _test_xml($string)
    {
        $xml_doc = '<?xml version="1.0" encoding="iso-8859-1" standalone="yes"?>'."\n"; //<?
        $xml_doc .= "<testxml>$string</testxml>\n";

        // xml_parse value to make sure it's valid X(HT)ML
        $xml_parser = xml_parser_create();

        return xml_parse($xml_parser, $xml_doc, TRUE);
    }

    // public functions
    function magic_unslash($string)
    {
        if (!$string) return $string;
        return get_magic_quotes_gpc() ? stripslashes($string) : $string;
    }

    function xml_declaration()
    {
        echo '<?xml version="1.0" encoding="iso-8859-1" standalone="yes"?>',"\n"; //<?
    }

    function base_url()
    {
        $url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];

        return substr($url,0,strrpos($url,"/")+1);
    }

    // replace <code include="file.php"/> with include "code/file.php"
    function process_code($string)
    {
        // Loop through to find the dynamic code processing instruction
        while ( ($pos = strpos( $string, '<code' )) !== FALSE ) {
            // find the end of the instruction
            if ( ($pos2 = strpos( $string, '/>', $pos + 6)) !== FALSE) {
                // extract the command
                $command = trim( substr( $string, $pos + 6, $pos2 - ($pos + 6) ) );
                // parse the command
                if (($cpos = strpos($command, 'include="')) !== FALSE) {
                    if (($cpos2 = strpos( $command, '"', $cpos + 9)) !== FALSE) {
                        // got the filename ... include it
                        $file = new XWL_filename;
                        if ($file->set_value(trim(substr($command, $cpos+9, $cpos2-($cpos+9))))) {
                            ob_start();
                            include "code/$file->value";
                            $results = ob_get_contents();
                            ob_end_clean();
                        } else die('error: invalid filename');
                    } else die('error: missing closing " for include');
                }
            } else die('error: missing closing /> for <code tag');

        // paste the results and rescan
        $string = substr($string, 0, $pos) . $results . substr($string, $pos2 + 2);
        }
        return $string;
    }
}
