<?php
// $Id: user.php,v 1.13 2003/12/01 16:13:09 loki Exp $
// vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4:

// user logon/personal menu block

/*
 * Copyright (c) 2002, 2003 John Benninghoff <john@benninghoff.org>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 * 3. All advertising materials mentioning features or use of this software
 *    must display the following acknowledgement:
 *	This product includes software developed by John Benninghoff.
 * 4. Neither the name of the copyright holder nor the names of its
 *    contributors may be used to endorse or promote products derived from
 *    this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 *  "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED
 * TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR
 * CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 * EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,
 * PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR
 * PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF
 * LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 * NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 */

require_once "include/auth.inc.php";

$userblock_page = basename($_SERVER['PHP_SELF']);
if ($_SERVER['QUERY_STRING']) $userblock_page .= "?".$_SERVER['QUERY_STRING'];
$userblock_page = htmlspecialchars($userblock_page);

// only display for authenticated users
if (xwl_auth_user_authenticated()) {

    $user = xwl_auth_user_fetch();

    echo "<block>\n";
    echo "  <title>", ucfirst($user->property['userid']->display_XML()), "'s Menu</title>\n";
    echo "  <content>\n";

    if ($block = $user->property['block']->display_XML()) {
        echo $block, "\n";
    } else {
        echo "<a href=\"user.php\">Customize</a> your personal menu\n";
    }

    echo "    <br class=\"br\"/>\n";
    echo "    <br class=\"br\"/>\n";
    echo "    <object><form action=\"$userblock_page\" method=\"post\">\n";
    echo "      <div><input name=\"logout\" type=\"submit\" value=\"Logout\"/></div>\n";
    echo "    </form></object>\n";

    echo "  </content>\n";
    echo "</block>\n";
} else {
    // display logon block
    echo <<< END
<block>
  <title>Access</title>
  <content>
    <object><form action="$userblock_page" method="post">
      <div><input name="login" type="submit" value="Login"/></div>
    </form></object>
    If you do not have an account, you can <a href="user.php?mode=new">create</a> one.
  </content>
</block>
END;
}
?>
