<?php
// $Id: functions.inc.php,v 1.26 2003/10/22 21:44:35 loki Exp $
// vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4:

// core xml-weblog functions

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

// basic functions

function xml_declaration()
{
    echo '<?xml version="1.0" encoding="iso-8859-1" standalone="yes"?>',"\n"; //<?
}

function base_url()
{
    $url = "http://".$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];

    return substr($url,0,strrpos($url,"/")+1);
}

// private functions

function safe_gpc_stripslashes($string)
{
    return get_magic_quotes_gpc() ? stripslashes($string) : $string;
}

function safe_gpc_addslashes($string)
{
    return get_magic_quotes_gpc() ? $string : addslashes($string);
}

function only_has($source,$valid)
{
    if (strspn($source,$valid) == strlen($source)) return true;
    else return false;
}

// public functions

// replace <code include="file.php"/> with include "code/file.php"
function xwl_process_code($string)
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
                    $file = xwl_valid_filename(trim(substr($command, $cpos+9, $cpos2-($cpos+9))));
                    ob_start();
                    include "code/$file";
                    $results = ob_get_contents();
                    ob_end_clean();
                } else die('error: missing closing " for include');
            }
        } else die('error: missing closing /> for <code tag');

    // paste the results and rescan
    $string = substr($string, 0, $pos) . $results . substr($string, $pos2 + 2);
    }
    return $string;
}

// validity checks

function xwl_valid_ID($id)
{
    $num =  "0123456789";

    if (only_has($id,$num)) return $id;
    else return "";
}

function xwl_valid_URI($uri)
{
    $alpha = "abcdefghijklmnopqrstuvwxyz"."ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $num =  "0123456789";
    $dns = "-.";
    $url = "$-_.+"."!*'(),%";
    $path = "/";
    $query = ";:@&=";

    $parsed = parse_url($uri);

    // sanity check - make sure we parsed right
    $new_uri = "";
    if (isset($parsed['scheme'])) $new_uri .= $parsed['scheme']."://";
    if (isset($parsed['pass'])) $new_uri .= "$parsed[user]:$parsed[pass]@";
    elseif (isset($parsed['user'])) $uri .= "$parsed[user]@";
    if (isset($parsed['host'])) $new_uri .= $parsed['host'];
    if (isset($parsed['port'])) $new_uri .= ":$parsed[port]";
    if (isset($parsed['path'])) $new_uri .= $parsed['path'];
    if (isset($parsed['query'])) $new_uri .= "?$parsed[query]";
    if (isset($parsed['fragment'])) $new_uri .= "#$parsed[fragment]";
    if ($uri != $new_uri) return "";

    // check each component
    if ($parsed['scheme'] && $parsed['scheme'] != "http") return "";
    if (!only_has($parsed['user'],$alpha.$num.$url)) return "";
    if (!only_has($parsed['pass'],$alpha.$num.$url)) return "";
    if (!only_has($parsed['host'],$alpha.$num.$dns)) return "";
    if (!only_has($parsed['port'],$num)) return "";
    if (!only_has($parsed['path'],$alpha.$num.$url.$path.$query)) return "";
    if (!only_has($parsed['query'],$alpha.$num.$url.$query)) return "";
    if (!only_has($parsed['fragment'],$alpha.$num.$url)) return "";

    // uri is OK!
    return $uri;
}

function xwl_valid_filename($file)
{
    $alpha = "abcdefghijklmnopqrstuvwxyz"."ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $num =  "0123456789";
    $sym = "-_.";

    if (only_has($file, $alpha.$num.$sym) && $file != ".." & $file != ".") return $file;
    else return "";
}

function xwl_valid_boolean($b)
{
    if (isset($b)) return "1";
    else return "0";
}

function xwl_valid_date($date)
{
    if (!$date) return "0000-00-00 00:00:00";
    if (($timestamp = strtotime($date)) === -1) return "";
    else return date("Y-m-d H:i:s",$timestamp);
}

function xwl_valid_datenum($datenum)
{
    $num =  "0123456789";

    if (only_has($datenum,$num) && strlen($datenum) == 14) return $datenum;
    else return "";
}

function xwl_valid_image($file)
{
    if (!is_uploaded_file($file['tmp_name'])) return "";
    if (!getimagesize($file['tmp_name'])) return "";

    return addslashes(fread(fopen($file['tmp_name'], "r"), filesize($file['tmp_name'])));
}

function xwl_valid_image_small($file)
{
    if (!is_uploaded_file($file['tmp_name'])) return "";
    if (!getimagesize($file['tmp_name'])) return "";

    return addslashes(fread(fopen($file['tmp_name'], "r"), filesize($file['tmp_name'])));
}

function xwl_valid_int($int)
{
    $num =  "0123456789";

    if (only_has($int,$num)) return $int;
    else return "";
}

function xwl_valid_lang($lang)
{
    // valid rfc1766 language codes
    if (preg_match("/^[a-zA-Z]{2}$/", $lang)) return $lang;
    if (preg_match("/^[a-zA-Z]{2}-[-a-zA-Z]*$/", $lang)) return $lang;
    if (preg_match("/^[ix]-[-a-zA-Z]*$/", $lang)) return $lang;
    return "";
}

function xwl_valid_string($str)
{
    return addslashes(htmlspecialchars(safe_gpc_stripslashes($str)));
}

function xwl_valid_string_XHTML($str)
{
    $tags = "<a><b><i><s><span>";
    return addslashes(strip_tags(safe_gpc_stripslashes($str),$tags));
}

function xwl_valid_XHTML_code($xhtml)
{
    $tags = "<a><b><i><s><span><pre><br><br/><img><p><code>";
    return addslashes(strip_tags(safe_gpc_stripslashes($xhtml),$tags));
}

function xwl_valid_XHTML_fragment($xhtml)
{
    $tags = "<a><b><i><s><span><pre><br><br/><img>";
    return addslashes(strip_tags(safe_gpc_stripslashes($xhtml),$tags));
}

function xwl_valid_XHTML_long($xhtml)
{
    $tags = "<a><b><i><s><span><blockquote><table><tr><td><ul><ol><li><pre><br><br/><img><p>";
    return addslashes(strip_tags(safe_gpc_stripslashes($xhtml),$tags));
}
?>
