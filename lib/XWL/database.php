<?php
// $Id: database.php,v 1.15 2004/04/30 18:15:02 loki Exp $
// vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4:

// database functions

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

// uses PEAR DB
require_once "DB.php";

class XWL_database
{
    var $_db;

    // private functions
    function _fetch_single($class, $query)
    {
        $result = $this->_db->getRow($query, DB_FETCHMODE_ASSOC);
        if (DB::isError($result) || !$result) return false;

        $obj = new $class;
        $obj->load_SQL($result);
        return $obj;
    }

    function _fetch_multiple($class, $query)
    {
        $result = $this->_db->getAll($query, DB_FETCHMODE_ASSOC);
        if (DB::isError($result) || !$result) return false;

        foreach ($result as $res) {
            $tmp = new $class;
            $tmp->load_SQL($res);
            $obj[] = $tmp;
        }
        return $obj;
    }

    // public functions
    function connect($type, $user, $password, $server, $database)
    {
        $this->_db = DB::connect("$type://$user:$password@$server/$database", true);

        // fetch globals
        $GLOBALS['XWL_topic_list'] = $this->_db->getAll("select id,name from topic", DB_FETCHMODE_ASSOC);
        $GLOBALS['XWL_site_list'] = $this->_db->getAll("select id,name from site", DB_FETCHMODE_ASSOC);
        $GLOBALS['XWL_user_list'] = $this->_db->getAll("select id,userid as name from user", DB_FETCHMODE_ASSOC);
    }

    function fetch_site($url)
    {
        // get the specified site if it exists, otherwise get the default site (id=1)
        return $this->_fetch_single("XWL_site", "select * from site where url='$url' or id=1 order by id desc");
    }

    function fetch_messages()
    {
        // get all active messages
        return $this->_fetch_multiple("XWL_message", "select * from message where (start_date < now() or start_date=0) and (end_date > now() or end_date=0) order by message_index");
    }

    function fetch_blocks()
    {
        // get all blocks in the order they will be displayed
        return $this->_fetch_multiple("XWL_block", "select * from block order by sidebar_align,sidebar_index,block_index");
    }

    function fetch_article($id)
    {
        // get article by id
        $query = "select article.*, topic.name as topic_name, topic.icon as topic_icon, user.name as user_name, user.mail as user_mail from article, topic, user where article.topic = topic.id and article.user = user.id and article.id = '$id'";

        return $this->_fetch_single("XWL_article", $query);
    }


    function fetch_article_first()
    {
        $query = "select article.*, topic.name as topic_name, topic.icon as topic_icon, user.name as user_name, user.mail as user_mail from article, topic, user where article.topic = topic.id and article.user = user.id and article.user=user.id order by article.date desc limit 1";

        return $this->_fetch_single("XWL_article", $query);
    }

    function fetch_article_last()
    {
        $query = "select article.*, topic.name as topic_name, topic.icon as topic_icon, user.name as user_name, user.mail as user_mail from article, topic, user where article.topic = topic.id and article.user = user.id and article.user=user.id order by article.date asc limit 1";

        return $this->_fetch_single("XWL_article", $query);
    }

    function fetch_articles($limit, $start, $end)
    {
        if (!$limit || $limit <= 0) $limit = 1;

        // fetch top ($limit) articles
        if ($start) {
            $query = "select article.*, topic.name as topic_name, topic.icon as topic_icon, user.name as user_name, user.mail as user_mail from article, topic, user where article.topic = topic.id and article.user = user.id and article.date <= $start order by article.date desc limit $limit";
        } elseif ($end) {
            $query = "select article.*, topic.name as topic_name, topic.icon as topic_icon, user.name as user_name, user.mail as user_mail from article, topic, user where article.topic = topic.id and article.user = user.id and article.date >= $end order by article.date asc limit $limit";
            return array_reverse($this->_fetch_multiple("XWL_article", $query));
        } else {
            $query = "select article.*, topic.name as topic_name, topic.icon as topic_icon, user.name as user_name, user.mail as user_mail from article, topic, user where article.topic = topic.id and article.user = user.id order by article.date desc limit $limit";
        }

        return $this->_fetch_multiple("XWL_article", $query);
    }

    function fetch_topics()
    {
        // fetch all topics
        return $this->_fetch_multiple("XWL_topic", "select * from topic order by id");
    }

    function fetch_articles_by_topic($topic)
    {
        // fetch all articles for a specific topic
        $query = "select article.*, topic.name as topic_name, topic.icon as topic_icon, user.name as user_name, user.mail as user_mail from article, topic, user where article.topic = topic.id and article.user = user.id and article.topic='$topic' order by article.date desc";

        return $this->_fetch_multiple("XWL_article", $query);
    }

    // image functions
    function fetch_image($name)
    {
        // fetch image specified by name
        return $this->_fetch_single("XWL_image", "select * from image where name='$name'");
    }

    function fetch_icon($name)
    {
        // fetch icon specified by name
        return $this->_fetch_single("XWL_icon", "select * from icon where name='$name'");
    }

    // admin functions
    function fetch_objects($class)
    {
        // fetch all objects for provided class (table)
        $query = "select * from $class order by id";
        return $this->_fetch_multiple("XWL_$class", $query); 
    }

    function fetch_object($class, $id)
    {
        // fetch single object
        $query = "select * from $class where id='$id'";
        return $this->_fetch_single("XWL_$class", $query);
    }

    function delete_object($class, $id)
    {
        // delete object
        $result = $this->_db->query("DELETE from $class where id=$id");
        return !(DB::isError($result));
    }

    function edit_object($class, $object)
    {
        $query  = "UPDATE $class SET";
        $query .= $object->query_string();
        $query .= " WHERE id='".$object->property['id']->SQL_safe_value()."'";

        $result = $this->_db->query($query);
        return !(DB::isError($result));
    }

    function create_object($class, $object)
    {
        $query = "INSERT INTO $class SET";
        $query .= $object->query_string();

        $result = $this->_db->query($query);
        return !(DB::isError($result));
    }

    // auth functions
    function fetch_user($userid)
    {
        // we don't use the generic fetch for security sensitive functions
        $result = $this->_db->getRow("select * from user where userid='$userid'", DB_FETCHMODE_ASSOC);
        if (DB::isError($result) || !$result) return false;

        $user = new XWL_user;
        $user->load_SQL($result);
        return $user;
    }
}

?>
