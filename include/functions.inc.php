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

// replace <?code include="file.php"> with include "code/file.php"
function process_code($string)
{
// Loop through to find the dynamic code processing instruction
while ( ($pos = strpos( $string, '<?code' )) !== FALSE ) {
    // find the end of the instruction
    if ( ($pos2 = strpos( $string, '?>', $pos + 6)) !== FALSE) {
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
    } else die('error: missing closing ?> for <?code directive');
    
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
     "and (end_date > now() or end_date=0)"; // add "group by index"
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

function safe_gpc_addslashes($string)
{
return get_magic_quotes_gpc() ? $string : addslashes($string);
}

function valid_ID($id)
{
if (strspn($id,"012345679") == strlen($id)) {
    return $id;
} else {
    return "";
}
}

?>
