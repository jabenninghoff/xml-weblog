<?php

function validate_self()
{
print '<p><a href="http://validator.w3.org/check?uri=http://'.
      $_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'].';ss=1">'."\n".
      '<img src="http://www.w3.org/Icons/valid-xhtml10"'."\n".
      'alt="Valid XHTML 1.0!" height="31" width="88" /></a></p>'."\n";
}

function process_code($string) {
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

?>
