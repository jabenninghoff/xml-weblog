<?php
// archive.org feed validator logo/link
$uri = "http://".$_SERVER['SERVER_NAME']."/rss.php";
$uri = rawurlencode($uri);

echo '<a class="img" href="http://feeds.archive.org/validator/check.cgi?url=',
  $uri, '">', '<img src="images/valid-rss.gif" alt="Valid RSS!" height="31" width="88"/></a>';
?>
