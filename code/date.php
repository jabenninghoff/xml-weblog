<?php
echo "<b>";
if ($_SERVER['PHP_AUTH_USER']) echo xwl_valid_string($_SERVER['PHP_AUTH_USER']), " @ ";
echo date("l, F j Y", time()), "</b>";
?>
