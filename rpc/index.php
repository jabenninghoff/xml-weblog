<?php
// $Id: index.php,v 1.4 2004/05/01 19:24:23 loki Exp $
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
require_once "XML/RPC/Server.php";
require_once "include/site.php";

// rpc authorization routine
function rpc_auth($user, $pass) {
    global $xwl_db;

    if ($user && XWL_string::valid($user)) {
        $_auth_user = $xwl_db->fetch_user($user);
    }
    if (crypt($pass, $_auth_user->property['password']->value) != $_auth_user->property['password']->value) return false;

    return true;
}

// we use the MetaWeblog & Blogger APIs

$blogger = array(
    "getRecentPosts"
);

// blogger.newPost (appkey, blogId, username, password, content, publish) returns postId
// blogger.editPost (appkey, postId, username, password, content, publish) returns true
// blogger.getPost (appkey, postId, username, password) returns struct: content, userId, postId, dateCreated

// blogger.getRecentPosts (appkey, blogId, username, password, numberOfPosts) returns array of structs (each is a post)
function blogger_getRecentPosts($params) {

    global $xwl_db, $xwl_site_value_xml, $xmlrpcerruser;
    $resp_array = array();

    $username = $params->getParam(2);
    $password = $params->getParam(3);
    if (!rpc_auth($username->scalarval(), $password->scalarval())) {
        // user error 1
        return new XML_RPC_Response(0, $xmlrpcerruser+1, "authorization failed: bad username/password.");
    }

    $numberOfPosts = $params->getParam(4);
    $num = $numberOfPosts->scalarval();

    $xwl_article = $xwl_db->fetch_articles($num ? $num : $xwl_site_value_xml['article_limit']);

    for ($i=0; $xwl_article[$i]; $i++) {
        $resp_struct = array(
            "userid" => new XML_RPC_Value($xwl_article[$i]->property['user_name']->value),
            "dateCreated" => new XML_RPC_Value($xwl_article[$i]->property['date']->iso8601_date(), "dateTime.iso8601"),
            "content" => new XML_RPC_Value($xwl_article[$i]->property['leader']->value),
            "postid" => new XML_RPC_Value($xwl_article[$i]->property['id']->value)
        );

        $resp_array[$i] = new XML_RPC_Value($resp_struct, "struct");
    }

    return new XML_RPC_Response(new XML_RPC_Value($resp_array, "array"));
}

// blogger.deletePost (appkey, postId, username, password, publish) returns true


$metaWeblog = array(
    "getRecentPosts"
);

// metaWeblog.newPost
// metaWeblog.editPost
// metaWeblog.getCategories
// metaWeblog.getPost

// metaWeblog.getRecentPosts (blogid, username, password, numberOfPosts)
function metaWeblog_getRecentPosts($params) {

    global $xwl_db, $xwl_site_value_xml, $xmlrpcerruser;
    $resp_array = array();

    $username = $params->getParam(1);
    $password = $params->getParam(2);
    if (!rpc_auth($username->scalarval(), $password->scalarval())) {
        // user error 1
        return new XML_RPC_Response(0, $xmlrpcerruser+1, "authorization failed: bad username/password.");
    }

    $numberOfPosts = $params->getParam(3);
    $num = $numberOfPosts->scalarval();

    $xwl_article = $xwl_db->fetch_articles($num ? $num : $xwl_site_value_xml['article_limit']);

    for ($i=0; $xwl_article[$i]; $i++) {
        $id = $xwl_article[$i]->property['id']->value;
        $link = $xwl_site_value_xml['url']."article.php?id=$id";
        $resp_struct = array(
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


// build the server
foreach (array("blogger", "metaWeblog") as $api) {
    foreach ($$api as $m) {
        $dispatch_map["$api.$m"] = array("function" => "${api}_$m");
    }
}

// generate response
$xml_rpc_server = new XML_RPC_Server($dispatch_map);

?>
