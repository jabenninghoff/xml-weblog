<?php
// $Id: auth.inc.php,v 1.19 2004/09/23 18:53:21 loki Exp $
// vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4:

// authentication & authorization module

/*
 * Copyright (c) 2002 - 2004 John Benninghoff <john@benninghoff.org>.
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

require_once "config.php";
require_once "XWL.php";
require_once "include/site.php";

// private globals
$_xwl_auth_user = NULL;

// initialization
if (isset($_SERVER['PHP_AUTH_USER']) && XWL_string::valid($_SERVER['PHP_AUTH_USER'])) {
    $_xwl_auth_user = $xwl_db->fetch_user($_SERVER['PHP_AUTH_USER']);
} else {
    $_xwl_auth_user = false;
}

// set the always_login cookie (if it isn't there) unless we're logged out
if ($_xwl_auth_user->property['always_login']->value && !$_COOKIE['always_login'] && !$_COOKIE['logout']) {
    // set a cookie to expire in 90 days
    setcookie('always_login', true, time()+60*60*24*90);
}

// private functions
function _redirect_ssl()
{
    global $xwl_site_value_xml;

    if (!$xwl_site_value_xml['ssl_port']) return;

    // redirect to ssl side
    $parsed_url = parse_url($xwl_site_value_xml['url']);
    $ssl_port = $xwl_site_value_xml['ssl_port'] == 443 ? "" : ":".$xwl_site_value_xml['ssl_port'];
    $ssl_url = "https://".$parsed_url['host'].$ssl_port.$_SERVER['PHP_SELF'];
    if ($_SERVER['QUERY_STRING']) $ssl_url .= "?".$_SERVER['QUERY_STRING'];
    header("Location: $ssl_url");
    exit;
}

// SSL (oops) check
if (isset($_SERVER['PHP_AUTH_PW']) && !$_SERVER['HTTPS']) {
    _redirect_ssl();
}

// public functions
function xwl_auth_login()
{
    global $_xwl_auth_user, $xwl_site_value_xml;

    // see if the user explicity requested a login
    if ($_GET['login'] || $_POST['login']) {

        // set the ste to login
        setcookie('login', true);

        return true;
    }

    // see if the user explicitly requested a logout
    if ($_GET['logout'] || $_POST['logout']) {     
        // flush the login cookies & set the state to logout
        setcookie('login', false);
        setcookie('always_login', false);
        setcookie('logout', true);

        // redirect to home page
        header("Location: ".$xwl_site_value_xml['url']);
        exit;
    }

    // return true if a cookie is trying to log us in
    return ($_COOKIE['login'] || $_COOKIE['always_login']);
}

function xwl_auth_user_authenticated()
{
    global $_xwl_auth_user;

    // don't authenticate if we're logged out
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
    if (!$_SERVER['HTTPS']) {
        // redirect to ssl page before auth failure so browser won't send cleartext password
        _redirect_ssl();
    }

    // if we failed a login, we are no longer logged out
    setcookie('logout', false);

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
