<?php
// $Id: index.php,v 1.10 2004/05/03 04:08:21 loki Exp $
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
require_once "XML/RPC/Server.php";

// XML-RPC errors
define('_XWL_XMLRPC_ERROR_UNAUTHORIZED', $GLOBALS['xmlrpcerruser']+11);
define('_XWL_XMLRPC_ERROR_NOSSL', $GLOBALS['xmlrpcerruser']+12);
define('_XWL_XMLRPC_ERROR_INVALID_POSTID', $GLOBALS['xmlrpcerruser']+21);
define('_XWL_XMLRPC_ERROR_INVALID_NUMPOSTS', $GLOBALS['xmlrpcerruser']+22);
define('_XWL_XMLRPC_ERROR_NOTFOUND_POSTID', $GLOBALS['xmlrpcerruser']+31);

// xmlrpc interface
class XWL_xmlrpc {

    var $_db;
    var $_site_value_xml;
    var $_api;
    var $_method;
    var $_xmlrpc_err;
    var $_param_shift;

    // contstructor function
    function XWL_xmlrpc($db, $site_value_xml) {
        $this->_xmlrpc_err[_XWL_XMLRPC_ERROR_UNAUTHORIZED] = "authorization failed: bad username/password.";
        $this->_xmlrpc_err[_XWL_XMLRPC_ERROR_NOSSL] = "authorization failed: not using SSL.";
        $this->_xmlrpc_err[_XWL_XMLRPC_ERROR_INVALID_POSTID] = "%s failed: invalid postid.";
        $this->_xmlrpc_err[_XWL_XMLRPC_ERROR_INVALID_NUMPOSTS] = "%s failed: invalid numberOfPosts.";
        $this->_xmlrpc_err[_XWL_XMLRPC_ERROR_NOTFOUND_POSTID] = "%s failed: postid not found.";

        $this->_db = new XWL_database;
        $this->_db = $db;
        $this->_site_value_xml = $site_value_xml;
    }

    // private functions

    function _xmlrpc_error($error_id) {
        return new XML_RPC_Response(0, $error_id, sprintf($this->_xmlrpc_err[$error_id], $this->_method));
    }

    // rpc authorization routine
    function _rpc_auth($user, $pass) {

        if ($user && XWL_string::valid($user)) {
            $auth_user = $this->_db->fetch_user($user);
        }
        if (crypt($pass, $auth_user->property['password']->value) != $auth_user->property['password']->value) return false;
    
        return true;
    }


    // public functions

    // XML-RPC authentication wrapper
    function process_xmlrpc($params) {
        
        // make sure we are using SSL if available
        if ($this->_site_value_xml['ssl_port'] && !$_SERVER['HTTPS']) {
            return $this->_xmlrpc_error(_XWL_XMLRPC_ERROR_NOSSL);
        }

        // we support the MetaWeblog & Blogger APIs
        $m = explode(".", $params->method());
        $this->_api = $m[0];
        $this->_method = $m[1];
        $this->_param_shift = ($this->_method == "blogger" ? 1 : 0);
    
        // authenticate user
        $username = $params->getParam(1+$this->_param_shift);
        $password = $params->getParam(2+$this->_param_shift);
    
        if (!$this->_rpc_auth($username->scalarval(), $password->scalarval())) {
            // user error 1
            return $this->_xmlrpc_error(_XWL_XMLRPC_ERROR_UNAUTHORIZED);
        }
    
        // call the appropriate function
//        $rpc_function = "_".$_xwl_xmlrpc_method;
//        return $rpc_function($params);
    }
}


// blogger functions

// blogger.newPost (appkey, blogId, username, password, content, publish) returns postId
// blogger.editPost (appkey, postId, username, password, content, publish) returns true

function _blogger_translate_post($article) {

    $post_struct = array(
        "userid" => new XML_RPC_Value($article->property['user_name']->value),
        "dateCreated" => new XML_RPC_Value($article->property['date']->iso8601_date(), "dateTime.iso8601"),
        "content" => new XML_RPC_Value($article->property['leader']->value),
        "postid" => new XML_RPC_Value($article->property['id']->value)
    );

    return new XML_RPC_Value($post_struct, "struct");
}

// blogger.getPost (appkey, postId, username, password) returns struct: content, userId, postId, dateCreated
// metaWeblog.getPost (postid, username, password) returns struct
function _getPost($params) {

    global $xwl_db;

    $postid = $params->getParam(1);
    $id = $postid->scalarval();

    // validate $postid
    if (!XWL_integer::valid($id)) {
        return _xmlrpc_error(_XWL_XMLRPC_ERROR_INVALID_POSTID);
    }

    if (!$xwl_article = $xwl_db->fetch_article($id)) {
        // this doesn't work for some reason ???
        return _xwl_xmlrpc_error(_XWL_XMLRPC_ERROR_NOTFOUND_POSTID);
    }

    return new XML_RPC_Response(_blogger_translate_post($xwl_article));
}

// blogger.getRecentPosts (appkey, blogId, username, password, numberOfPosts) returns array of structs (each is a post)
function _blogger_getRecentPosts($params) {

    global $xwl_db, $xwl_site_value_xml;

    $numberOfPosts = $params->getParam(4);
    $num = $numberOfPosts->scalarval();

    // validate numberOfPosts
    if (!XWL_integer::valid($num)) {
        return _xmlrpc_error(_XWL_XMLRPC_ERROR_INVALID_NUMPOSTS);
    }

    $xwl_article = $xwl_db->fetch_articles($num ? $num : $xwl_site_value_xml['article_limit'], 0, 0);

    for ($i=0; $xwl_article[$i]; $i++) {
        $resp_array[$i] = _blogger_translate_post($xwl_article[$i]);
    }

    return new XML_RPC_Response(new XML_RPC_Value($resp_array, "array"));
}

// blogger.deletePost (appkey, postId, username, password, publish) returns true


// metaWeblog.newPost (blogid, username, password, struct, publish) returns string
// metaWeblog.editPost (postid, username, password, struct, publish) returns true


// metaWeblog.getCategories (blogid, username, password) returns struct
function _metaWeblog_getCategories($params) {

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

// metaWeblog.getPost (postid, username, password) returns struct
function _metaWeblog_getPost($params) {

    global $xwl_db, $xwl_site_value_xml;

    $postid = $params->getParam(0);
    $id = $postid->scalarval();

    // validate $postid
    if (!XWL_integer::valid($id)) {
        return _xmlrpc_error(_XWL_XMLRPC_ERROR_INVALID_POSTID);
    }

    if (!$xwl_article = $xwl_db->fetch_article($id)) {
        // this doesn't work for some reason ???
        return _xwl_xmlrpc_error(_XWL_XMLRPC_ERROR_NOTFOUND_POSTID);
    }

    $link = $xwl_site_value_xml['url']."article.php?id=$id";
    $resp_struct = array(
            "categories" => new XML_RPC_Value(
                array(new XML_RPC_Value($xwl_article->property['topic_name']->value)), "array"),
        "userid" => new XML_RPC_Value($xwl_article->property['user_name']->value),
        "dateCreated" => new XML_RPC_Value($xwl_article->property['date']->iso8601_date(), "dateTime.iso8601"),
        "description" => new XML_RPC_Value($xwl_article->property['leader']->value),
        "postid" => new XML_RPC_Value($id),
        "title" => new XML_RPC_Value($xwl_article->property['title']->value),
        "link" => new XML_RPC_Value($link),
        "permaLink" => new XML_RPC_Value($link)
    );

    return new XML_RPC_Response(new XML_RPC_Value($resp_struct, "struct"));
}

// metaWeblog.getRecentPosts (blogid, username, password, numberOfPosts) returns array of structs
function _metaWeblog_getRecentPosts($params) {

    global $xwl_db, $xwl_site_value_xml;

    $numberOfPosts = $params->getParam(3);
    $num = $numberOfPosts->scalarval();

    // validate numberOfPosts
    if (!XWL_integer::valid($num)) {
        return _xmlrpc_error(_XWL_XMLRPC_ERROR_INVALID_NUMPOSTS);
    }

    $xwl_article = $xwl_db->fetch_articles($num ? $num : $xwl_site_value_xml['article_limit'], 0, 0);

    for ($i=0; $xwl_article[$i]; $i++) {
        $id = $xwl_article[$i]->property['id']->value;
        $link = $xwl_site_value_xml['url']."article.php?id=$id";
        $resp_struct = array(
            "categories" => new XML_RPC_Value(
                array(new XML_RPC_Value($xwl_article[$i]->property['topic_name']->value)), "array"),
            "userid" => new XML_RPC_Value($xwl_article[$i]->property['user_name']->value),
            "dateCreated" => new XML_RPC_Value($xwl_article[$i]->property['date']->iso8601_date(), "dateTime.iso8601"),
            "description" => new XML_RPC_Value($xwl_article[$i]->property['leader']->value),
            "postid" => new XML_RPC_Value($id),
            "title" => new XML_RPC_Value($xwl_article[$i]->property['title']->value),
            "link" => new XML_RPC_Value($link),
            "permaLink" => new XML_RPC_Value($link)
        );

        $resp_array[$i] = new XML_RPC_Value($resp_struct, "struct");
    }

    return new XML_RPC_Response(new XML_RPC_Value($resp_array, "array"));
}

function _xmlrpc_wrapper($params) {
    global $xwl_db, $xwl_site_value_xml;

    $xwl_xmlrpc = new XWL_xmlrpc($xwl_db, $xwl_site_value_xml);
    return $xwl_xmlrpc->process_xmlrpc($params);
}

$dispatch_map = array(
    "blogger.getPost" => array("function" => "_xmlrpc_wrapper"),
    "blogger.getRecentPosts" => array("function" => "_xmlrpc_wrapper"),
    "metaWeblog.getRecentPosts" => array("function" => "_xmlrpc_wrapper"),
    "metaWeblog.getCategories" => array("function" => "_xmlrpc_wrapper"),
    "metaWeblog.getPost" => array("function" => "_xmlrpc_wrapper")
);

// generate response
$xml_rpc_server = new XML_RPC_Server($dispatch_map);

?>
