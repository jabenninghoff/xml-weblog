<?php
// administration block

require_once "include/auth.inc.php";

// only display for authenticated administrators
if (xwl_auth_user_authenticated() && xwl_auth_user_authorized("admin")) {
echo <<< END
<block>
  <title>Admin Menu</title>
  <content>
    <a href="admin.php">Administration</a><br class="br"/><br class="br"/>
    Pending Articles: 0
  </content>
</block>
END;
}
?>
