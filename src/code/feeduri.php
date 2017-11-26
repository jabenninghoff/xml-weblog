<?php
// feed URI link builder

$uri = "feed://".$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];
$uri = preg_replace("/[^\/]+$/","rss.php", $uri);

echo 'RSS browsers supporting the <a href="http://www.25hoursaday.com/draft-obasanjo-feed-URI-scheme-02.html">feed URI scheme</a> ',
    'can get a full RSS feed <a href="', $uri, '">here</a>.';
?>
