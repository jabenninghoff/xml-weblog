<?php
// user logon/personal menu block

require_once "include/auth.inc.php";

$userblock_page = basename($_SERVER['PHP_SELF']);
if ($_SERVER['QUERY_STRING']) $userblock_page .= "?".$_SERVER['QUERY_STRING'];
$userblock_page = htmlspecialchars($userblock_page);
$user = xwl_auth_user_fetch();

// only display for authenticated users
if (xwl_auth_user_authenticated()) {

    echo "<block>\n";
    echo "  <title>", ucfirst($user->property['userid']->display_XML()), "'s Menu</title>\n";
    echo "  <content>\n";

    if ($block = $user->property['block']->display_XML()) {
        echo $block, "\n";
    } else {
        echo "<a href=\"user.php\">Customize</a> your personal menu\n";
    }

    echo "  </content>\n";
    echo "</block>\n";
}

// display login/logout block
echo "<block>";
echo "  <title>Access</title>";
echo "  <content>";
echo "    <form action=\"$userblock_page\" method=\"post\">\n";
echo "      <div>\n";
if (xwl_auth_user_authenticated()) {
    echo "        <input name=\"logout\" type=\"submit\" value=\"Logout\"/><br class=\"br\"/>\n";
    echo "      You are currently logged in as <strong>".$user->property['userid']->display_XML().".</strong>\n";
    echo "      </div>\n";
    echo "    </form>";
} else {
    echo "        <input name=\"login\" type=\"submit\" value=\"Login\"/>\n";
    echo "      </div>\n";
    echo "    </form>";
    echo "      If you do not have an account, you can <a href=\"user.php?mode=new\">create</a> one.\n";
}
echo "  </content>";
echo "</block>";
?>
