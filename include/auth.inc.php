<?php
// $Id: auth.inc.php,v 1.14 2003/11/30 02:35:49 loki Exp $
// vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4:

// authentication & authorization module

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

require_once "XWL.php";
require_once "include/site.php";
require_once "include/config.inc.php";

// private globals
$_xwl_auth_user = NULL;

// initialization
if (isset($_SERVER['PHP_AUTH_USER']) && XWL_string::valid($_SERVER['PHP_AUTH_USER'])) {
    $_xwl_auth_user = $xwl_db->fetch_user($_SERVER['PHP_AUTH_USER']);
} else {
    $_xwl_auth_user = false;
}


// public functions
function xwl_auth_login()
{
    global $_xwl_auth_user, $xwl_site_value_xml;

    // see if the user explicity requested a login & there's no existing cookie
    if (($_GET['login'] || $_POST['login']) && !$_COOKIE['login']) {

        // flush any logout cookies
        setcookie('logout', false);

        /*
         * Some browsers (K-Meleon) only send credentials when asked, so to
         * force them to give up the user & pass we set a cookie. While this
         * does not violate rfc2617 (HTTP Authentication), all other browsers
         * will send credentials every time if asked once, since it's more
         * efficient that way. *sigh* This isn't too intrusive, since pretty
         * much everyone silently accepts session cookies.
         */
        if ($_xwl_auth_user->property['always_login']->value) {
            // set a cookie to expire in 90 days
            setcookie('login', true, time()+60*60*24*90);
        } else {
            // set a session cookie
            setcookie('login', true);
        }

        return true;
    }

    // see if the user explicitly requested a logout
    if ($_GET['logout'] || $_POST['logout']) {     
        // flush the login cookie & kill the login
        setcookie('login', false);
        setcookie('logout', true);
        header("Location: ".$xwl_site_value_xml['url']);
        exit;
    }

    // this will be true (only) if the session cookie has been sent & user is not logged out.
    return ($_COOKIE['login'] && !$_COOKIE['logout']);
}

function xwl_auth_user_authenticated()
{
    global $_xwl_auth_user;

    if (!$_xwl_auth_user || $_COOKIE['logout']) return false;

    if (crypt($_SERVER['PHP_AUTH_PW'], $_xwl_auth_user->property['password']->value) != $_xwl_auth_user->property['password']->value) return false;

    return true;
}

function xwl_auth_user_authorized($priv)
{
global $_xwl_auth_user;

    if (!$_xwl_auth_user) return false;
    return $_xwl_auth_user->property[$priv]->value;
}

function xwl_auth_user_fetch()
{
global $_xwl_auth_user;

    return $_xwl_auth_user;
}

function xwl_auth_unauthorized($realm)
{
    header('WWW-Authenticate: Basic realm="'.$realm.'"');
    header('HTTP/1.0 401 Unauthorized');
    echo <<< END
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<HTML><HEAD>
<TITLE>401 Authorization Required</TITLE>
</HEAD><BODY>
<H1>401 Authorization Required</H1>
<P>This server could not verify that you are authorized to access the document
requested. Either you supplied the wrong credentials (e.g., bad password), or
your browser doesn't understand how to supply the credentials required.</P>
</BODY></HTML>
END;
    exit;
}
?>
