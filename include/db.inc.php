<?php
// $Id: db.inc.php,v 1.12 2003/04/21 17:41:20 loki Exp $
// database functional abstraction module

/*
 * Copyright (c) 2002, John Benninghoff <john@benninghoff.org>.
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

require_once "include/config.inc.php";
require_once "DB.php";

// connect
$xwl_db = DB::connect("$xwl_db_type://$xwl_db_user:$xwl_db_password@$xwl_db_server/$xwl_db_database", true);

// public functions

// page functions
function xwl_db_fetch_site($url)
{
    global $xwl_db;

    return $xwl_db->getRow("select * from site where url='$url' or id=1 order by id desc", DB_FETCHMODE_ASSOC);
}

function xwl_db_fetch_block()
{
    global $xwl_db;

    return $xwl_db->getAll("select * from block order by sidebar_align,sidebar_index,block_index", DB_FETCHMODE_ASSOC);
}

function xwl_db_fetch_message()
{
    global $xwl_db;

    return $xwl_db->getAll("select * from message where (start_date < now() or start_date=0) and (end_date > now() or end_date=0) order by message_index", DB_FETCHMODE_ASSOC);
}

function xwl_db_fetch_article($limit, $start, $end)
{
    global $xwl_db, $xwl_default_article_limit;

    if (!$limit || $limit <= 0) $limit = $xwl_default_article_limit;

    if ($start) {
        $query = "select article.*,user.userid as author from article,user where article.user=user.id and article.date <= $start order by article.date desc limit $limit";
        return $xwl_db->getAll($query, DB_FETCHMODE_ASSOC);
    } else if ($end) {
        $query = "select article.*,user.userid as author from article,user where article.user=user.id and article.date >= $end order by article.date limit $limit";
        return array_reverse($xwl_db->getAll($query, DB_FETCHMODE_ASSOC));
    } else {
        $query = "select article.*,user.userid as author from article,user where article.user=user.id order by article.date desc limit $limit";
        return $xwl_db->getAll($query, DB_FETCHMODE_ASSOC);
    }
}

function xwl_db_fetch_article_first()
{
    global $xwl_db;

    $query = "select article.*,user.userid as author from article,user where article.user=user.id order by article.date desc limit 1";
    return $xwl_db->getRow($query, DB_FETCHMODE_ASSOC);
}

function xwl_db_fetch_article_last()
{
    global $xwl_db;

    $query = "select article.*,user.userid as author from article,user where article.user=user.id order by article.date asc limit 1";
    return $xwl_db->getRow($query, DB_FETCHMODE_ASSOC);
}

function xwl_db_fetch_article_single($id)
{
    global $xwl_db;

    $query = "select article.*,user.userid as author from article,user where article.user=user.id and article.id=$id";
    return $xwl_db->getRow($query, DB_FETCHMODE_ASSOC);
}

function xwl_db_fetch_article_by_topic($topic)
{
    global $xwl_db;

    $query = "select article.*,user.userid as author from article,user where article.user=user.id and article.topic='$topic' order by article.date desc";
    return $xwl_db->getAll($query, DB_FETCHMODE_ASSOC);
}

function xwl_db_fetch_topic()
{
    global $xwl_db;

    return $xwl_db->getAll("select * from topic order by id", DB_FETCHMODE_ASSOC);
}

// image functions
function xwl_db_fetch_image($name)
{
    global $xwl_db;

    return $xwl_db->getRow("select * from image where name='$name'", DB_FETCHMODE_ASSOC);
}

function xwl_db_fetch_icon($name)
{
    global $xwl_db;

    return $xwl_db->getRow("select * from icon where name='$name'", DB_FETCHMODE_ASSOC);
}

// auth functions
function xwl_db_fetch_user($userid)
{
    global $xwl_db;

    $user = $xwl_db->getRow("select * from user where userid='$userid'", DB_FETCHMODE_ASSOC);
    if (DB::isError($user)) $user = false;

    return $user;
}

// admin functions
function xwl_db_fetch_column_by_id($table, $column)
{
    global $xwl_db;

    $result = $xwl_db->getCol("select $column from $table order by id");
    array_unshift($result, "");
    return $result;
}

function xwl_db_fetch_type($type)
{
    global $xwl_db;

    return $xwl_db->getAll("select * from $type order by id", DB_FETCHMODE_ASSOC);
}

function xwl_db_fetch_schema($type)
{
    global $xwl_db;

    return $xwl_db->getAll("select distinct property,datatype,required from schema where object='$type'", DB_FETCHMODE_ASSOC);
}

function xwl_db_fetch_object($type, $id)
{
    global $xwl_db;

    return $xwl_db->getRow("select * from $type where id='$id'", DB_FETCHMODE_ASSOC);
}

function xwl_db_delete_object($type, $id)
{
    global $xwl_db;

    $result = $xwl_db->query("DELETE from $type where id=$id");

    return !(DB::isError($result));
}

function xwl_db_update_object($type, $object)
{
    global $xwl_db;

    $query = "UPDATE $type SET";
    foreach ($object as $field => $value) {
        $query .= " $field='$value',";
    }
    // strip the last comma & specify ID
    $query = substr($query, 0, strlen($query)-1)." WHERE id='{$object['id']}'";

    $result = $xwl_db->query($query);

    return !(DB::isError($result));
}

function xwl_db_insert_object($type, $object)
{
    global $xwl_db;

    $query = "INSERT INTO $type SET";
    foreach ($object as $field => $value) {
        $query .= " $field='$value',";
    }
    // strip the last comma
    $query = substr($query, 0, strlen($query)-1);

    $result = $xwl_db->query($query);

    return !(DB::isError($result));
}

function xwl_db_fetch_table_list()
{
    global $xwl_db;

    return $xwl_db->getCol("select distinct object from schema order by object");
}

?>
