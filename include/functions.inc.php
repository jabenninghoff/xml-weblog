<?php

function validate_self()
{
print '<p><a href="http://validator.w3.org/check?uri=http://'.
      $_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'].';ss=1">'."\n".
      '<img src="http://www.w3.org/Icons/valid-xhtml10"'."\n".
      'alt="Valid XHTML 1.0!" height="31" width="88" /></a></p>'."\n";
}
?>
