<?php
require_once "include/config.inc.php";

// W3C valid XTML 1.0 logo/link
function validate_self()
{
echo '<p><a href="http://validator.w3.org/check?uri=http://',
    $_SERVER['SERVER_NAME'], $_SERVER['PHP_SELF'], ';ss=1">', "\n",
    '<img src="image.php?name=valid-xhtml10.png"', "\n",
    'alt="Valid XHTML 1.0!" height="31" width="88" /></a></p>',"\n";
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
                $file = basename(trim(substr($command, $cpos+9, $cpos2-($cpos+9))));
                ob_start();
                require "code/$file";
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

function fetch_site($id)
{
if (!$id) $id = 1;
global $db;

return $db->getRow("select * from site where id=$id", DB_FETCHMODE_ASSOC);
}

function fetch_block()
{
global $db;

$q = "select * from block group by sidebar_align,sidebar_index,block_index";
return $db->getAll($q, DB_FETCHMODE_ASSOC);
}

function fetch_message()
{
global $db;

$q = "select * from message where (start_date < now() or start_date=0)".
     "and (end_date > now() or end_date=0) group by message_index";
return $db->getAll($q, DB_FETCHMODE_ASSOC);
}

function fetch_article($limit)
{
if (!$limit) $limit = 10;
global $db;

$q = "select * from article group by date desc limit $limit";
return $db->getAll($q, DB_FETCHMODE_ASSOC);
}

function fetch_topic()
{
global $db;

return $db->getAll("select * from topic group by id", DB_FETCHMODE_ASSOC);
}

function fetch_type($type)
{
global $db;

return $db->getAll("select * from $type group by id", DB_FETCHMODE_ASSOC);
}

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

function valid_ID($id)
{
    $num =  "0123456789";

    if (only_has($id,$num)) return $id;
    else return "";
}

function valid_URI($uri)
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

function valid_boolean($b)
{
    if (isset($b)) return "1";
    else return "0";
}

function valid_date($date)
{
    if (!$date) return "0000-00-00 00:00:00";
    if (($timestamp = strtotime($date)) === -1) return "";
    else return date("Y-m-d H:i:s",$timestamp);
}

function valid_image($file)
{
    if (!is_uploaded_file($file['tmp_name'])) return "";
    if (!getimagesize($file['tmp_name'])) return "";

    return addslashes(fread(fopen($file['tmp_name'], "r"),
        filesize($file['tmp_name'])));
}

function valid_image_small($file)
{
    if (!is_uploaded_file($file['tmp_name'])) return "";
    if (!getimagesize($file['tmp_name'])) return "";

    return addslashes(fread(fopen($file['tmp_name'], "r"),
        filesize($file['tmp_name'])));
}

function valid_int($int)
{
    $num =  "0123456789";

    if (only_has($int,$num)) return $int;
    else return "";
}

function valid_lang($lang)
{
    // valid rfc1766 language codes
    if (preg_match("/^[a-zA-Z]{2}$/", $lang)) return $lang;
    if (preg_match("/^[a-zA-Z]{2}-[-a-zA-Z]*$/", $lang)) return $lang;
    if (preg_match("/^[ix]-[-a-zA-Z]*$/", $lang)) return $lang;
    return "";
}

function valid_string($str)
{
    return addslashes(htmlspecialchars(safe_gpc_stripslashes($str)));
}

function valid_string_XHTML($str)
{
    $tags = "<a><b><i><s><u>";
    return addslashes(strip_tags(safe_gpc_stripslashes($str),$tags));
}

function valid_XHTML_code($xhtml)
{
    $tags = "<a><b><i><s><u><br><br/><p><code>";
    return addslashes(strip_tags(safe_gpc_stripslashes($xhtml),$tags));
}

function valid_XHTML_fragment($xhtml)
{
    $tags = "<a><b><i><s><u><br><br/>";
    return addslashes(strip_tags(safe_gpc_stripslashes($xhtml),$tags));
}

function valid_XHTML_long($xhtml)
{
    $tags = "<a><b><i><s><u><br><br/><p>";
    return addslashes(strip_tags(safe_gpc_stripslashes($xhtml),$tags));
}

?>
