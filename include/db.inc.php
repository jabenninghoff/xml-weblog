<?php
// $Id: db.inc.php,v 1.4 2002/11/01 03:32:06 loki Exp $

// connect
require_once "include/config.inc.php";
require_once "DB.php";
$xwl_db = DB::connect("$xwl_db_type://$xwl_db_user:$xwl_db_password@$xwl_db_server/$xwl_db_database", true);

// page functions
function fetch_site($url)
{
    global $xwl_db;

    return $xwl_db->getRow("select * from site where url='$url' or id=1 group by id desc", DB_FETCHMODE_ASSOC);
}

function fetch_block()
{
    global $xwl_db;

    return $xwl_db->getAll("select * from block group by sidebar_align,sidebar_index,block_index", DB_FETCHMODE_ASSOC);
}

function fetch_message()
{
    global $xwl_db;

    return $xwl_db->getAll("select * from message where (start_date < now() or start_date=0) and (end_date > now() or end_date=0) group by message_index", DB_FETCHMODE_ASSOC);
}

function fetch_article($limit)
{
    global $xwl_db, $xwl_article_default_limit;

    if (!$limit || $limit <= 0) $limit = $xwl_article_default_limit;

    $query = "select article.*,topic.name as topic_name,topic.icon as topic_icon,user.userid as author from article,topic,user where article.topic=topic.id and article.user=user.id group by article.date desc limit $limit";
    return $xwl_db->getAll($query, DB_FETCHMODE_ASSOC);
}

function fetch_article_single($id)
{
    global $xwl_db;

    $query = "select article.*,topic.name as topic_name,topic.icon as topic_icon,user.userid as author from article,topic,user where article.topic=topic.id and article.user=user.id and article.id='$id'";
    return $xwl_db->getRow($query, DB_FETCHMODE_ASSOC);
}

function fetch_topic()
{
    global $xwl_db;

    return $xwl_db->getAll("select * from topic group by id", DB_FETCHMODE_ASSOC);
}

// image functions
function fetch_image($name)
{
    global $xwl_db;

    return $xwl_db->getRow("select * from image where name='$name'", DB_FETCHMODE_ASSOC);
}

function fetch_icon($name)
{
    global $xwl_db;

    return $xwl_db->getRow("select * from icon where name='$name'", DB_FETCHMODE_ASSOC);
}

// auth functions
function fetch_user($userid)
{
    global $xwl_db;

    $user = $xwl_db->getRow("select * from user where userid='$userid'", DB_FETCHMODE_ASSOC);
    if (DB::isError($user)) $user = false;

    return $user;
}

// admin functions
function fetch_column_by_id($table, $column)
{
    global $xwl_db;

    $result = $xwl_db->getCol("select $column from $table group by id");
    array_unshift($result, "");
    return $result;
}

function fetch_type($type)
{
    global $xwl_db;

    return $xwl_db->getAll("select * from $type group by id", DB_FETCHMODE_ASSOC);
}

function fetch_schema($type)
{
    global $xwl_db;

    return $xwl_db->getAll("select distinct property,datatype,required from schema where object='$type'", DB_FETCHMODE_ASSOC);
}

function fetch_object($type, $id)
{
    global $xwl_db;

    return $xwl_db->getRow("select * from $type where id='$id'", DB_FETCHMODE_ASSOC);
}

function delete_object($type, $id)
{
    global $xwl_db;

    $result = $xwl_db->query("DELETE from $type where id=$id");

    return !(DB::isError($result));
}

function update_object($type, $object)
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

function insert_object($type, $object)
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

function fetch_table_list()
{
    global $xwl_db;

    return $xwl_db->getCol("select distinct object from schema group by object");
}

?>
