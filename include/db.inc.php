<?php
// $Id: db.inc.php,v 1.3 2002/10/29 23:28:51 loki Exp $

// connect
require_once "include/config.inc.php";
require_once "DB.php";
$xlw_db = DB::connect("$xlw_db_type://$xlw_db_user:$xlw_db_password@$xlw_db_server/$xlw_db_database", true);

// page functions
function fetch_site($url)
{
    global $xlw_db;

    return $xlw_db->getRow("select * from site where url='$url' or id=1 group by id desc", DB_FETCHMODE_ASSOC);
}

function fetch_block()
{
    global $xlw_db;

    return $xlw_db->getAll("select * from block group by sidebar_align,sidebar_index,block_index", DB_FETCHMODE_ASSOC);
}

function fetch_message()
{
    global $xlw_db;

    return $xlw_db->getAll("select * from message where (start_date < now() or start_date=0) and (end_date > now() or end_date=0) group by message_index", DB_FETCHMODE_ASSOC);
}

function fetch_article($limit)
{
    global $xlw_db, $xlw_article_default_limit;

    if (!$limit || $limit <= 0) $limit = $xlw_article_default_limit;

    $query = "select article.*,topic.name as topic_name,topic.icon as topic_icon,user.userid as author from article,topic,user where article.topic=topic.id and article.user=user.id group by article.date desc limit $limit";
    return $xlw_db->getAll($query, DB_FETCHMODE_ASSOC);
}

function fetch_article_single($id)
{
    global $xlw_db;

    $query = "select article.*,topic.name as topic_name,topic.icon as topic_icon,user.userid as author from article,topic,user where article.topic=topic.id and article.user=user.id and article.id='$id'";
    return $xlw_db->getRow($query, DB_FETCHMODE_ASSOC);
}

function fetch_topic()
{
    global $xlw_db;

    return $xlw_db->getAll("select * from topic group by id", DB_FETCHMODE_ASSOC);
}

// image functions
function fetch_image($name)
{
    global $xlw_db;

    return $xlw_db->getRow("select * from image where name='$name'", DB_FETCHMODE_ASSOC);
}

function fetch_icon($name)
{
    global $xlw_db;

    return $xlw_db->getRow("select * from icon where name='$name'", DB_FETCHMODE_ASSOC);
}

// auth functions
function fetch_user($userid)
{
    global $xlw_db;

    $user = $xlw_db->getRow("select * from user where userid='$userid'", DB_FETCHMODE_ASSOC);
    if (DB::isError($user)) $user = false;

    return $user;
}

// admin functions
function fetch_type($type)
{
    global $xlw_db;

    return $xlw_db->getAll("select * from $type group by id", DB_FETCHMODE_ASSOC);
}

function fetch_schema($type)
{
    global $xlw_db;

    return $xlw_db->getAll("select distinct property,datatype,required from schema where object='$type'", DB_FETCHMODE_ASSOC);
}

function fetch_object($type, $id)
{
    global $xlw_db;

    return $xlw_db->getRow("select * from $type where id='$id'", DB_FETCHMODE_ASSOC);
}

function delete_object($type, $id)
{
    global $xlw_db;

    $result = $xlw_db->query("DELETE from $type where id=$id");

    return !(DB::isError($result));
}

function update_object($type, $object)
{
    global $xlw_db;

    $query = "UPDATE $type SET";
    foreach ($object as $field => $value) {
        $query .= " $field='$value',";
    }
    // strip the last comma & specify ID
    $query = substr($query, 0, strlen($query)-1)." WHERE id='{$object['id']}'";

    $result = $xlw_db->query($query);

    return !(DB::isError($result));
}

function insert_object($type, $object)
{
    global $xlw_db;

    $query = "INSERT INTO $type SET";
    foreach ($object as $field => $value) {
        $query .= " $field='$value',";
    }
    // strip the last comma
    $query = substr($query, 0, strlen($query)-1);

    $result = $xlw_db->query($query);

    return !(DB::isError($result));
}

function fetch_table_list()
{
    global $xlw_db;

    return $xlw_db->getCol("select distinct object from schema group by object");
}

?>
