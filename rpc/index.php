<?php
// $Id: index.php,v 1.16 2004/07/10 00:12:21 loki Exp $
// vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4:

// xml-rpc interface

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

require_once "XWL.php";
require_once "include/site.php";
require_once "include/auth.inc.php";
require_once "XML/RPC/Server.php";

// XML-RPC errors
define('_XWL_XMLRPC_ERROR_AUTH_FAIL', $GLOBALS['xmlrpcerruser']+11);
define('_XWL_XMLRPC_ERROR_NOT_AUTHORIZED', $GLOBALS['xmlrpcerruser']+12);
define('_XWL_XMLRPC_ERROR_NOSSL', $GLOBALS['xmlrpcerruser']+19);
define('_XWL_XMLRPC_ERROR_INVALID_POSTID', $GLOBALS['xmlrpcerruser']+21);
define('_XWL_XMLRPC_ERROR_INVALID_NUMPOSTS', $GLOBALS['xmlrpcerruser']+22);
define('_XWL_XMLRPC_ERROR_INVALID_CONTENT', $GLOBALS['xmlrpcerruser']+23);
define('_XWL_XMLRPC_ERROR_UNSUPP_PUBLISH', $GLOBALS['xmlrpcerruser']+29);
define('_XWL_XMLRPC_ERROR_NOTFOUND_POSTID', $GLOBALS['xmlrpcerruser']+31);
define('_XWL_XMLRPC_ERROR_DB_ERROR', $GLOBALS['xmlrpcerruser']+32);

$_xwl_xmlrpc_error[_XWL_XMLRPC_ERROR_AUTH_FAIL] = "authentication failed: bad username/password.";
$_xwl_xmlrpc_error[_XWL_XMLRPC_ERROR_NOT_AUTHORIZED] = "%s failed: user not authorized for supplied method.";
$_xwl_xmlrpc_error[_XWL_XMLRPC_ERROR_NOSSL] = "authentication failed: not using SSL.";
$_xwl_xmlrpc_error[_XWL_XMLRPC_ERROR_INVALID_POSTID] = "%s failed: invalid postid.";
$_xwl_xmlrpc_error[_XWL_XMLRPC_ERROR_INVALID_NUMPOSTS] = "%s failed: invalid numberOfPosts.";
$_xwl_xmlrpc_error[_XWL_XMLRPC_ERROR_INVALID_CONTENT] = "%s failed: invalid content.";
$_xwl_xmlrpc_error[_XWL_XMLRPC_ERROR_UNSUPP_PUBLISH] = "%s failed: unsupported publish value.";
$_xwl_xmlrpc_error[_XWL_XMLRPC_ERROR_NOTFOUND_POSTID] = "%s failed: postid not found.";
$_xwl_xmlrpc_error[_XWL_XMLRPC_ERROR_DB_ERROR] = "%s failed: database error.";

function _xmlrpc_error($error_id) {

    global $_xwl_xmlrpc_error, $_xwl_xmlrpc_method;

    return new XML_RPC_Response(0, $error_id, sprintf($_xwl_xmlrpc_error[$error_id], $_xwl_xmlrpc_method));
}

// XML-RPC authentication/wrapper
$_xwl_xmlrpc_api = "unknownAPI";
$_xwl_xmlrpc_method = "unknownMethod";
$_pshift = 0;

function xwl_xmlrpc($params) {

    global $xwl_db, $xwl_site_value_xml, $_xwl_auth_user, $_xwl_xmlrpc_api, $_xwl_xmlrpc_method, $_pshift;

    // make sure we are using SSL if available
    if ($xwl_site_value_xml['ssl_port'] && !$_SERVER['HTTPS']) {
        return _xmlrpc_error(_XWL_XMLRPC_ERROR_NOSSL);
    }

    // we support the MetaWeblog & Blogger APIs
    $m = explode(".", $params->method());
    $_xwl_xmlrpc_api = $m[0];
    $_xwl_xmlrpc_method = $m[1];

    // authenticate user
    $_pshift = ($_xwl_xmlrpc_api == "blogger" ? 1 : 0);

    $username = $params->getParam(1+$_pshift);
    $password = $params->getParam(2+$_pshift);
    $user = $username->scalarval();
    $pass = $password->scalarval();

    // we have to fetch $_xwl_auth_user ourselves
    if ($user && XWL_string::valid($user)) {
        $_xwl_auth_user = $xwl_db->fetch_user($user);
    } else {
        return _xmlrpc_error(_XWL_XMLRPC_ERROR_AUTH_FAIL);
    }

    // we also have to check the password ourselves
    if (crypt($pass, $_xwl_auth_user->property['password']->value) != $_xwl_auth_user->property['password']->value) {
        return _xmlrpc_error(_XWL_XMLRPC_ERROR_AUTH_FAIL);
    }

    // call the appropriate function
    $rpc_function = "_".$_xwl_xmlrpc_method;
    return $rpc_function($params);
}


// metaWeblog.getCategories (blogid, username, password) returns struct (of structs, each is a category)
function _getCategories($params) {

    global $xwl_db, $xwl_site_value_xml;

    $xwl_topic = $xwl_db->fetch_topics();

    for ($i=0; $xwl_topic[$i]; $i++) {
        $resp_struct = array(
            "description" => new XML_RPC_Value($xwl_topic[$i]->property['description']->value),
            "htmlUrl" => new XML_RPC_Value($xwl_site_value_xml['url']."topic.php?id=".$xwl_topic[$i]->property['id']->value),
            "rssUrl" => new XML_RPC_Value("")
        );

        $resp_array[$xwl_topic[$i]->property['name']->value] = new XML_RPC_Value($resp_struct, "struct");
    }

    return new XML_RPC_Response(new XML_RPC_Value($resp_array, "struct"));
}

function _blogger_translate_post($article) {

    $post_struct = array(
        "userid" => new XML_RPC_Value($article->property['user_name']->value),
        "dateCreated" => new XML_RPC_Value($article->property['date']->iso8601_date(), "dateTime.iso8601"),
        "content" => new XML_RPC_Value($article->property['leader']->value),
        "postid" => new XML_RPC_Value($article->property['id']->value)
    );

    return new XML_RPC_Value($post_struct, "struct");
}

function _metaWeblog_translate_post($article) {

    global $xwl_site_value_xml;

    $id = $article->property['id']->value;
    $link = $xwl_site_value_xml['url']."article.php?id=$id";
    $post_struct = array(
        "categories" => new XML_RPC_Value(
            array(new XML_RPC_Value($article->property['topic_name']->value)), "array"),
        "userid" => new XML_RPC_Value($article->property['user_name']->value),
        "dateCreated" => new XML_RPC_Value($article->property['date']->iso8601_date(), "dateTime.iso8601"),
        "description" => new XML_RPC_Value($article->property['leader']->value),
        "postid" => new XML_RPC_Value($id),
        "title" => new XML_RPC_Value($article->property['title']->value),
        "link" => new XML_RPC_Value($link),
        "permaLink" => new XML_RPC_Value($link)
    );

    return new XML_RPC_Value($post_struct, "struct");
}

// blogger.getPost (appkey, postId, username, password) returns struct: content, userId, postId, dateCreated
// metaWeblog.getPost (postid, username, password) returns struct (post as RSS 2.0 item)
function _getPost($params) {

    global $xwl_db, $_xwl_xmlrpc_api, $_pshift;

    $postid = $params->getParam(0+$_pshift);
    $id = $postid->scalarval();

    // validate $postid
    if (!XWL_integer::valid($id)) {
        return _xmlrpc_error(_XWL_XMLRPC_ERROR_INVALID_POSTID);
    }

    if (!$xwl_article = $xwl_db->fetch_article($id)) {
        // this doesn't work for some reason ???
        return _xwl_xmlrpc_error(_XWL_XMLRPC_ERROR_NOTFOUND_POSTID);
    }

    $translate_function = "_${_xwl_xmlrpc_api}_translate_post";

    return new XML_RPC_Response($translate_function($xwl_article));
}

// blogger.getRecentPosts (appkey, blogId, username, password, numberOfPosts) returns array of structs (each is a post)
// metaWeblog.getRecentPosts (blogid, username, password, numberOfPosts) returns array of structs (each is a post)
function _getRecentPosts($params) {

    global $xwl_db, $xwl_site_value_xml, $_xwl_xmlrpc_api, $_pshift;

    $numberOfPosts = $params->getParam(3+$_pshift);
    $num = $numberOfPosts->scalarval();

    // validate numberOfPosts
    if (!XWL_integer::valid($num)) {
        return _xmlrpc_error(_XWL_XMLRPC_ERROR_INVALID_NUMPOSTS);
    }

    $translate_function = "_${_xwl_xmlrpc_api}_translate_post";

    $xwl_article = $xwl_db->fetch_articles($num ? $num : $xwl_site_value_xml['article_limit'], 0, 0);

    for ($i=0; $xwl_article[$i]; $i++) {
        $resp_array[$i] = $translate_function($xwl_article[$i]);
    }

    return new XML_RPC_Response(new XML_RPC_Value($resp_array, "array"));
}

function _blogger_post($content, $publish) {

    global $xwl_db, $_xwl_auth_user;

    $post = new XWL_article;

    $post->property['site']->set_value($GLOBALS['xwl_default_site']);
    $post->property['topic']->set_value($GLOBALS['xwl_default_topic']);
    $post->property['title']->set_value(date("F j, Y"));
    $post->property['user']->set_value($_xwl_auth_user->property['id']->value);
    $post->property['date']->set_value("now");
    $post->property['leader']->set_value($content->scalarval());
    $post->property['language']->set_value($GLOBALS['xwl_default_lang']);

    if ($post->missing_required()) {
        return _xmlrpc_error(_XWL_XMLRPC_ERROR_INVALID_CONTENT);
    }

    if ($xwl_db->create_object("article", $post)) {
        $created_post = $xwl_db->fetch_article_last_id();
        return new XML_RPC_Response(new XML_RPC_Value($created_post->property['id']->value, "int"));
    }

    return _xmlrpc_error(_XWL_XMLRPC_ERROR_DB_ERROR);
}

function _metaWeblog_post($post_struct, $publish) {

    global $xwl_db, $_xwl_auth_user;

    $post = new XWL_article;

    $post->property['site']->set_value($GLOBALS['xwl_default_site']);

    // we use only the first category provided
    $xmlarr = $post_struct->structmem("categories");
    $xmlval = $xmlarr->arraymem(0);
    $topic_str = $xmlval->scalarval();

    $topic = $GLOBALS['xwl_default_topic'];
    $xwl_topic = $xwl_db->fetch_topics();
    foreach ($xwl_topic as $t) {
        if ($t->property['name']->value == $topic_str) {
            $topic = $t->property['id']->value;
            break;
        }
    }

    $post->property['topic']->set_value($topic);

    $xmlval = $post_struct->structmem("title");
    $post->property['title']->set_value($xmlval->scalarval());

    $post->property['user']->set_value($_xwl_auth_user->property['id']->value);
    $post->property['date']->set_value("now");

    $xmlval = $post_struct->structmem("description");
    $post->property['leader']->set_value($xmlval->scalarval());

    $post->property['language']->set_value($GLOBALS['xwl_default_lang']);

    if ($post->missing_required()) {
        return _xmlrpc_error(_XWL_XMLRPC_ERROR_INVALID_CONTENT);
    }

    if ($xwl_db->create_object("article", $post)) {
        $created_post = $xwl_db->fetch_article_last_id();
        return new XML_RPC_Response(new XML_RPC_Value($created_post->property['id']->value, "int"));
    }

    return _xmlrpc_error(_XWL_XMLRPC_ERROR_DB_ERROR);
}

// blogger.newPost (appkey, blogId, username, password, content, publish) returns postId
// metaWeblog.newPost (blogid, username, password, struct, publish) returns string (postId)
function _newPost($params) {

    global $xwl_db, $_xwl_xmlrpc_api, $_pshift;

    // get the post content/struct and publish bit
    $post = $params->getParam(3+$_pshift);
    $p = $params->getParam(4+$_pshift);
    $publish = $p->scalarval();

    if (!$publish) {
        // not publishing to home page is currently not supported.
        // will be implemented with user submission system.
        return _xmlrpc_error(_XWL_XMLRPC_ERROR_UNSUPP_PUBLISH);
    }

    // check authorization - require admin for now
    if (!xwl_auth_user_authorized("admin")) {
        return _xmlrpc_error(_XWL_XMLRPC_ERROR_NOT_AUTHORIZED);
    }

    $post_function = "_${_xwl_xmlrpc_api}_post";

    return $post_function($post, $publish);
}

// blogger.editPost (appkey, postId, username, password, content, publish) returns true
// metaWeblog.editPost (postid, username, password, struct, publish) returns true

// blogger.deletePost (appkey, postId, username, password, publish) returns true
function _deletePost($params) {

    global $xwl_db, $_pshift;

    // get the postid and publish bit
    $p = $params->getParam(0+$_pshift);
    $postid = $p->scalarval();
    $p = $params->getParam(3+$_pshift);
    $publish = $p->scalarval();

    if (!$publish) {
        // not publishing to home page is currently not supported.
        // will be implemented with user submission system.
        return _xmlrpc_error(_XWL_XMLRPC_ERROR_UNSUPP_PUBLISH);
    }

    // check authorization - require admin for now
    if (!xwl_auth_user_authorized("admin")) {
        return _xmlrpc_error(_XWL_XMLRPC_ERROR_NOT_AUTHORIZED);
    }

    if ($xwl_db->delete_object("article", $postid)) {
        return new XML_RPC_Response(new XML_RPC_Value(1, "boolean"));
    }

    return _xmlrpc_error(_XWL_XMLRPC_ERROR_DB_ERROR);
}

$dispatch_map = array(
    "blogger.deletePost" => array("function" => "xwl_xmlrpc"),
    "blogger.getPost" => array("function" => "xwl_xmlrpc"),
    "blogger.getRecentPosts" => array("function" => "xwl_xmlrpc"),
    "blogger.newPost" => array("function" => "xwl_xmlrpc"),
    "metaWeblog.getCategories" => array("function" => "xwl_xmlrpc"),
    "metaWeblog.getPost" => array("function" => "xwl_xmlrpc"),
    "metaWeblog.getRecentPosts" => array("function" => "xwl_xmlrpc"),
    "metaWeblog.newPost" => array("function" => "xwl_xmlrpc")
);

// generate response
$xml_rpc_server = new XML_RPC_Server($dispatch_map);

?>
