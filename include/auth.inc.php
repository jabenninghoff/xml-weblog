<?php
require_once "include/config.inc.php";

if (isset($_SERVER['PHP_AUTH_USER'])) {
    $auth_user = $db->getRow("select * from user where userid='".
            $_SERVER['PHP_AUTH_USER']."'", DB_FETCHMODE_ASSOC);
    if (DB::isError($user)) $user = false;
} else {
    $auth_user = false;
}

function user_authenticated()
{
global $auth_user;

    if (!$auth_user) return false;
    if (crypt($_SERVER['PHP_AUTH_PW'], $auth_user['password'])
        != $auth_user['password']) return false;

    return true;
}

function user_authorized($priv)
{
global $auth_user;

    if (!$auth_user) return false;
    if (!$auth_user[$priv]) return false;

    return true;
}

function unauthorized($realm)
{
    header('WWW-Authenticate: Basic realm="'.$realm.'"');
    header('HTTP/1.0 401 Unauthorized');
?>
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<HTML><HEAD>
<TITLE>401 Authorization Required</TITLE>
</HEAD><BODY>
<H1>401 Authorization Required</H1>
<P>This server could not verify that you are authorized to access the document
requested. Either you supplied the wrong credentials (e.g., bad password), or
your browser doesn't understand how to supply the credentials required.</P>
</BODY></HTML>
<?php
    exit;
}
?>
