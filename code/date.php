<?php
// print date and user (if logged in)

echo "<b>";
if ($_SERVER['PHP_AUTH_USER'] && !($_GET['logout'] || $_POST['logout'] || $_COOKIE['logout'])) {
    echo htmlspecialchars($_SERVER['PHP_AUTH_USER']), " @ ";
}
echo date("l, F j Y", time()), "</b>";
?>
