<?php
echo "<b>";
if ($_SERVER['PHP_AUTH_USER']) echo htmlspecialchars($_SERVER['PHP_AUTH_USER']), " @ ";
echo date("l, F j Y", time()), "</b>";
?>
