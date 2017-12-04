<?php
// W3C valid XTML 1.0 logo/link

$uri = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
if ($_SERVER['QUERY_STRING']) $uri .= "?".$_SERVER['QUERY_STRING'];
$uri = rawurlencode($uri);

echo '<a class="img" href="http://validator.w3.org/check?uri=', $uri, ';ss=1">',
  '<img src="images/valid-xhtml10.gif" alt="Valid XHTML 1.0!" height="31" width="88"/></a>';
?>
