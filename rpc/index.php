<?php
// $Id: index.php,v 1.20 2004/07/11 22:02:57 loki Exp $
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
define('_XWL_XMLRPC_ERROR_INVALID_PARMS', $GLOBALS['xmlrpcerruser']+1);
define('_XWL_XMLRPC_ERROR_AUTH_FAIL', $GLOBALS['xmlrpcerruser']+11);
define('_XWL_XMLRPC_ERROR_NOT_AUTHORIZED', $GLOBALS['xmlrpcerruser']+12);
define('_XWL_XMLRPC_ERROR_NOSSL', $GLOBALS['xmlrpcerruser']+19);
define('_XWL_XMLRPC_ERROR_INVALID_POSTID', $GLOBALS['xmlrpcerruser']+21);
define('_XWL_XMLRPC_ERROR_INVALID_NUMPOSTS', $GLOBALS['xmlrpcerruser']+22);
define('_XWL_XMLRPC_ERROR_INVALID_CONTENT', $GLOBALS['xmlrpcerruser']+23);
define('_XWL_XMLRPC_ERROR_UNSUPP_PUBLISH', $GLOBALS['xmlrpcerruser']+29);
define('_XWL_XMLRPC_ERROR_NOTFOUND_POSTID', $GLOBALS['xmlrpcerruser']+31);
define('_XWL_XMLRPC_ERROR_DB_ERROR', $GLOBALS['xmlrpcerruser']+32);

$_xwl_xmlrpc_error[_XWL_XMLRPC_ERROR_INVALID_PARMS] = "%s failed: invalid parameters.";
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

    global $xwl_db, $_xwl_auth_user, $_xwl_xmlrpc_api, $_xwl_xmlrpc_method, $_pshift;

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

    if (!$username || !$password) {
        return _xmlrpc_error(_XWL_XMLRPC_ERROR_INVALID_PARMS);
    }

    $user = $username->scalarval();
    $pass = $password->scalarval();

    // we have to fetch $_xwl_auth_user ourselves
    if ($user && XWL_string::valid($user) && $_xwl_auth_user = $xwl_db->fetch_user($user)) {
        // we have a valid user
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

    if (!$topic = $xwl_db->fetch_topics()) {
        // something is really wrong...
        return _xmlrpc_error(_XWL_XMLRPC_ERROR_DB_ERROR);
    }

    foreach ($topic as $t) {
        $resp_struct = array(
            "description" => new XML_RPC_Value($t->property['description']->value),
            "htmlUrl" => new XML_RPC_Value($xwl_site_value_xml['url']."topic.php?id=".$t->property['id']->value),
            "rssUrl" => new XML_RPC_Value("")
        );

        $resp_array[$t->property['name']->value] = new XML_RPC_Value($resp_struct, "struct");
    }

    return new XML_RPC_Response(new XML_RPC_Value($resp_array, "struct"));
}

function _blogger_translate_post($article) {

    $content = $article->property['leader']->value;
    if ($article->property['content']->value) {
        $content .= "<xwl function=\"split\"/>\n".$article->property['content']->value;
    }

    $post_struct = array(
        "userid" => new XML_RPC_Value($article->property['user_name']->value),
        "dateCreated" => new XML_RPC_Value($article->property['date']->iso8601_date(), "dateTime.iso8601"),
        "content" => new XML_RPC_Value($content),
        "postid" => new XML_RPC_Value($article->property['id']->value)
    );

    return new XML_RPC_Value($post_struct, "struct");
}

function _metaWeblog_translate_post($article) {

    global $xwl_site_value_xml;

    $id = $article->property['id']->value;
    $link = $xwl_site_value_xml['url']."article.php?id=$id";
    $content = $article->property['leader']->value;
    if ($article->property['content']->value) {
        $content .= "<xwl function=\"split\"/>\n".$article->property['content']->value;
    }

    $post_struct = array(
        "categories" => new XML_RPC_Value(
            array(new XML_RPC_Value($article->property['topic_name']->value)), "array"),
        "userid" => new XML_RPC_Value($article->property['user_name']->value),
        "dateCreated" => new XML_RPC_Value($article->property['date']->iso8601_date(), "dateTime.iso8601"),
        "description" => new XML_RPC_Value($content),
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

    if (!$postid) {
        // this should never happen, but check anyway
        return _xmlrpc_error(_XWL_XMLRPC_ERROR_INVALID_PARMS);
    }

    $id = $postid->scalarval();

    // validate $postid
    if (!XWL_integer::valid($id)) {
        return _xmlrpc_error(_XWL_XMLRPC_ERROR_INVALID_POSTID);
    }

    if (!$article = $xwl_db->fetch_article($id)) {
        // this doesn't work for some reason ???
        return _xmlrpc_error(_XWL_XMLRPC_ERROR_NOTFOUND_POSTID);
    }

    $translate_function = "_${_xwl_xmlrpc_api}_translate_post";

    return new XML_RPC_Response($translate_function($article));
}

// blogger.getRecentPosts (appkey, blogId, username, password, numberOfPosts) returns array of structs (each is a post)
// metaWeblog.getRecentPosts (blogid, username, password, numberOfPosts) returns array of structs (each is a post)
function _getRecentPosts($params) {

    global $xwl_db, $xwl_site_value_xml, $_xwl_xmlrpc_api, $_pshift;

    $numPosts = $params->getParam(3+$_pshift);

    if (!$numPosts) {
        return _xmlrpc_error(_XWL_XMLRPC_ERROR_INVALID_PARMS);
    }

    $num = $numPosts->scalarval();

    // validate numberOfPosts
    if (!XWL_integer::valid($num)) {
        return _xmlrpc_error(_XWL_XMLRPC_ERROR_INVALID_NUMPOSTS);
    }

    $translate_function = "_${_xwl_xmlrpc_api}_translate_post";

    if (!$article = $xwl_db->fetch_articles($num ? $num : $xwl_site_value_xml['article_limit'], 0, 0)) {
        return _xmlrpc_error(_XWL_XMLRPC_ERROR_DB_ERROR);
    }

    for ($i=0; $article[$i]; $i++) {
        $resp_array[$i] = $translate_function($article[$i]);
    }

    return new XML_RPC_Response(new XML_RPC_Value($resp_array, "array"));
}

function _blogger_post($content, $publish) {

    global $xwl_db, $_xwl_auth_user;

    $article = explode('<xwl function="split"/>', $content->scalarval());

    $post = new XWL_article;

    $post->property['site']->set_value($GLOBALS['xwl_default_site']);
    $post->property['topic']->set_value($GLOBALS['xwl_default_topic']);
    $post->property['title']->set_value($GLOBALS['xwl_blogger_title']);
    $post->property['user']->set_value($_xwl_auth_user->property['id']->value);
    $post->property['date']->set_value("now");
    $post->property['leader']->set_value($article[0]);
    $post->property['content']->set_value($article[1]);
    $post->property['language']->set_value($GLOBALS['xwl_default_lang']);

    if ($post->missing_required()) {
        return _xmlrpc_error(_XWL_XMLRPC_ERROR_INVALID_CONTENT);
    }

    if ($xwl_db->create_object("article", $post)) {
        if (!$created_post = $xwl_db->fetch_article_last_id()) {
            // created, but we can't find it ???
            return new XML_RPC_Response(new XML_RPC_Value("0"));
        }
        return new XML_RPC_Response(new XML_RPC_Value($created_post->property['id']->value));
    }

    return _xmlrpc_error(_XWL_XMLRPC_ERROR_DB_ERROR);
}

function _metaWeblog_post($post_struct, $publish) {

    global $xwl_db, $_xwl_auth_user;

    // extract the topic, title, and content
    if ($post_struct->kindOf() != "struct") {
        return _xmlrpc_error(_XWL_XMLRPC_ERROR_INVALID_PARMS);
    }

    $categories = $post_struct->structmem("categories");
    $title = $post_struct->structmem("title");
    $description = $post_struct->structmem("description");

    if (!$categories || !$title || !$description || $categories->kindOf() != "array") {
        return _xmlrpc_error(_XWL_XMLRPC_ERROR_INVALID_PARMS);
    }

    // we use only the first category provided
    $cat = $categories->arraymem(0);

    if (!$cat) {
        return _xmlrpc_error(_XWL_XMLRPC_ERROR_INVALID_PARMS);
    }

    $topic_str = $cat->scalarval();

    if (!$xwl_topic = $xwl_db->fetch_topics()) {
        return _xmlrpc_error(_XWL_XMLRPC_ERROR_DB_ERROR);
    }

    $topic = $GLOBALS['xwl_default_topic'];
    foreach ($xwl_topic as $t) {
        if ($t->property['name']->value == $topic_str) {
            $topic = $t->property['id']->value;
            break;
        }
    }

    $article = explode('<xwl function="split"/>', $description->scalarval());

    $post = new XWL_article;

    $post->property['site']->set_value($GLOBALS['xwl_default_site']);
    $post->property['topic']->set_value($topic);
    $post->property['title']->set_value($title->scalarval());
    $post->property['user']->set_value($_xwl_auth_user->property['id']->value);
    $post->property['date']->set_value("now");
    $post->property['leader']->set_value($article[0]);
    $post->property['content']->set_value($article[1]);
    $post->property['language']->set_value($GLOBALS['xwl_default_lang']);

    if ($post->missing_required()) {
        return _xmlrpc_error(_XWL_XMLRPC_ERROR_INVALID_CONTENT);
    }

    if ($xwl_db->create_object("article", $post)) {
        if (!$created_post = $xwl_db->fetch_article_last_id()) {
            // created, but we can't find it ???
            return new XML_RPC_Response(new XML_RPC_Value("0"));
        }
        return new XML_RPC_Response(new XML_RPC_Value($created_post->property['id']->value));
    }

    return _xmlrpc_error(_XWL_XMLRPC_ERROR_DB_ERROR);
}

// blogger.newPost (appkey, blogId, username, password, content, publish) returns postId
// metaWeblog.newPost (blogid, username, password, struct, publish) returns string (postId)
function _newPost($params) {

    global $xwl_db, $_xwl_xmlrpc_api, $_pshift;

    // get the post content/struct and publish bit
    $post = $params->getParam(3+$_pshift);
    $publish = $params->getParam(4+$_pshift);

    if (!$post || !$publish) {
        return _xmlrpc_error(_XWL_XMLRPC_ERROR_INVALID_PARMS);
    }

    $pub = $publish->scalarval();

    if (!$pub) {
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

function _blogger_edit($id, $content, $publish) {

    global $xwl_db, $_xwl_auth_user;

    if (!$post = $xwl_db->fetch_article($id)) {
        return _xwl_xmlrpc_error(_XWL_XMLRPC_ERROR_NOTFOUND_POSTID);
    }

    $article = explode('<xwl function="split"/>', $content->scalarval());
    $post->property['leader']->set_value($article[0]);
    $post->property['content']->set_value($article[1]);

    if ($post->missing_required()) {
        return _xmlrpc_error(_XWL_XMLRPC_ERROR_INVALID_CONTENT);
    }

    if ($xwl_db->edit_object("article", $post)) {
        return new XML_RPC_Response(new XML_RPC_Value(1, "boolean"));
    }

    return _xmlrpc_error(_XWL_XMLRPC_ERROR_DB_ERROR);
}

function _metaWeblog_edit($id, $post_struct, $publish) {

    global $xwl_db, $_xwl_auth_user;

    if (!$post = $xwl_db->fetch_article($id)) {
        return _xwl_xmlrpc_error(_XWL_XMLRPC_ERROR_NOTFOUND_POSTID);
    }

    // extract the topic, title, and content
    if ($post_struct->kindOf() != "struct") {
        return _xmlrpc_error(_XWL_XMLRPC_ERROR_INVALID_PARMS);
    }

    $categories = $post_struct->structmem("categories");
    $title = $post_struct->structmem("title");
    $description = $post_struct->structmem("description");

    if (!$categories || !$title || !$description || $categories->kindOf() != "array") {
        return _xmlrpc_error(_XWL_XMLRPC_ERROR_INVALID_PARMS);
    }

    // we use only the first category provided
    $cat = $categories->arraymem(0);

    if (!$cat) {
        return _xmlrpc_error(_XWL_XMLRPC_ERROR_INVALID_PARMS);
    }

    $topic_str = $cat->scalarval();

    if (!$xwl_topic = $xwl_db->fetch_topics()) {
        return _xmlrpc_error(_XWL_XMLRPC_ERROR_DB_ERROR);
    }

    $topic = $GLOBALS['xwl_default_topic'];
    foreach ($xwl_topic as $t) {
        if ($t->property['name']->value == $topic_str) {
            $topic = $t->property['id']->value;
            break;
        }
    }

    $article = explode('<xwl function="split"/>', $description->scalarval());

    $post->property['topic']->set_value($topic);
    $post->property['title']->set_value($title->scalarval());
    $post->property['leader']->set_value($article[0]);
    $post->property['content']->set_value($article[1]);


    if ($post->missing_required()) {
        return _xmlrpc_error(_XWL_XMLRPC_ERROR_INVALID_CONTENT);
    }

    if ($xwl_db->edit_object("article", $post)) {
        return new XML_RPC_Response(new XML_RPC_Value(1, "boolean"));
    }

    return _xmlrpc_error(_XWL_XMLRPC_ERROR_DB_ERROR);
}

// blogger.editPost (appkey, postId, username, password, content, publish) returns true
// metaWeblog.editPost (postid, username, password, struct, publish) returns true
function _editPost($params) {

    global $xwl_db, $_xwl_xmlrpc_api, $_pshift;

    // get the post id, content/struct and publish bit
    $postid = $params->getParam(0+$_pshift);
    $post = $params->getParam(3+$_pshift);
    $publish = $params->getParam(4+$_pshift);

    if (!$postid || !$post || !$publish) {
        return _xmlrpc_error(_XWL_XMLRPC_ERROR_INVALID_PARMS);
    }

    $id = $postid->scalarval();
    $pub = $publish->scalarval();

    if (!$pub) {
        // not publishing to home page is currently not supported.
        // will be implemented with user submission system.
        return _xmlrpc_error(_XWL_XMLRPC_ERROR_UNSUPP_PUBLISH);
    }

    // check authorization - require admin for now
    if (!xwl_auth_user_authorized("admin")) {
        return _xmlrpc_error(_XWL_XMLRPC_ERROR_NOT_AUTHORIZED);
    }

    // validate $postid
    if (!XWL_integer::valid($id)) {
        return _xmlrpc_error(_XWL_XMLRPC_ERROR_INVALID_POSTID);
    }

    $post_function = "_${_xwl_xmlrpc_api}_edit";

    return $post_function($id, $post, $publish);
}

// blogger.deletePost (appkey, postId, username, password, publish) returns true
function _deletePost($params) {

    global $xwl_db, $_pshift;

    // get the post id, content/struct and publish bit
    $postid = $params->getParam(0+$_pshift);
    $publish = $params->getParam(3+$_pshift);

    if (!$postid || !$publish) {
        return _xmlrpc_error(_XWL_XMLRPC_ERROR_INVALID_PARMS);
    }

    $id = $postid->scalarval();
    $pub = $publish->scalarval();

    if (!$pub) {
        // not publishing to home page is currently not supported.
        // will be implemented with user submission system.
        return _xmlrpc_error(_XWL_XMLRPC_ERROR_UNSUPP_PUBLISH);
    }

    // check authorization - require admin for now
    if (!xwl_auth_user_authorized("admin")) {
        return _xmlrpc_error(_XWL_XMLRPC_ERROR_NOT_AUTHORIZED);
    }

    // validate $postid
    if (!XWL_integer::valid($id)) {
        return _xmlrpc_error(_XWL_XMLRPC_ERROR_INVALID_POSTID);
    }

    if ($xwl_db->delete_object("article", $id)) {
        return new XML_RPC_Response(new XML_RPC_Value(1, "boolean"));
    }

    return _xmlrpc_error(_XWL_XMLRPC_ERROR_NOTFOUND_POSTID);
}

$dispatch_map = array(
    "blogger.editPost" => array("function" => "xwl_xmlrpc"),
    "blogger.deletePost" => array("function" => "xwl_xmlrpc"),
    "blogger.getPost" => array("function" => "xwl_xmlrpc"),
    "blogger.getRecentPosts" => array("function" => "xwl_xmlrpc"),
    "blogger.newPost" => array("function" => "xwl_xmlrpc"),
    "metaWeblog.editPost" => array("function" => "xwl_xmlrpc"),
    "metaWeblog.getCategories" => array("function" => "xwl_xmlrpc"),
    "metaWeblog.getPost" => array("function" => "xwl_xmlrpc"),
    "metaWeblog.getRecentPosts" => array("function" => "xwl_xmlrpc"),
    "metaWeblog.newPost" => array("function" => "xwl_xmlrpc")
);

// generate response
$xml_rpc_server = new XML_RPC_Server($dispatch_map);

?>
