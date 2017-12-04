<?php
// archive.org feed validator logo/link

$uri = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
$uri = preg_replace("/[^\/]+$/","rss.php", $uri);

echo '<a class="img" href="http://feeds.archive.org/validator/check.cgi?url=',
  $uri, '">', '<img src="images/valid-rss.gif" alt="Valid RSS!" height="31" width="88"/></a>';
?>
